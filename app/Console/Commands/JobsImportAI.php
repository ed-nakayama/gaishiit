<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Mail;

use App\Models\Job;
use App\Mail\OpenAPING;

define('ERROR_MSG', 'エラーが発生しました');
define('MAIL_ADDR', 't.nakayama@d-ark.co.jp');

class JobsImportAI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:jobsImportAI';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import job for AI';

	private $openai_api_key;
	private $status_code;
	private $response;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$this->openai_api_key = config('const.OPENAI_API_KEY');

		$jobList = Job::Join('companies', 'jobs.company_id','=','companies.id')
			->where('companies.open_flag' , '1')
			->where('jobs.open_flag' , '1')
//			->where('jobs.company_id' , '10000001')
//			->where('jobs.id' , '24772')
			->whereNull('jobs.app_contents')
			->selectRaw('jobs.id, jobs.intro')
			->get();

		$cnt = 0;
		
		foreach ($jobList as $job) {
			$cnt++;
			echo $cnt . "  "  . $job->id . "  " . date("Y/m/d H:i:s") . "\n";

			$this->status_code = 200;

			$job_arr = $this->getDataAI($job);
			if ($this->status_code != 200) {

//				echo $this->response;
				Mail::send(new OpenAPING(MAIL_ADDR ,$this->response));

				// クレジット残高不足のエラーの場合は、メール送信後にプログラムを終了する
				if (strpos($this->response, 'You have no credits remaining') !== false) {
					exit(1);
				}

				break;
			}

			if(strpos($job_arr['contents'], ERROR_MSG) !== false) {
				echo 'contents : ' . $job_arr['contents'] . "\n";

			} else if(strpos($job_arr['place'], ERROR_MSG) !== false) {
				echo 'place : ' . $job_arr['place'] . "\n";

			} else if(strpos($job_arr['income'], ERROR_MSG) !== false) {
				echo 'income : ' . $job_arr['income'] . "\n";

			} else if(strpos($job_arr['work_time'], ERROR_MSG) !== false) {
				echo 'work_time : ' . $job_arr['work_time'] . "\n";

			} else if(strpos($job_arr['holiday'], ERROR_MSG) !== false) {
				echo 'holiday : ' . $job_arr['holiday'] . "\n";

			} else if(strpos($job_arr['welfare'], ERROR_MSG) !== false) {
				echo 'welfare : ' . $job_arr['welfare'] . "\n";

			} else if(strpos($job_arr['remote'], ERROR_MSG) !== false) {
				echo 'remote : ' . $job_arr['remote'] . "\n";

			} else if(strpos($job_arr['others'], ERROR_MSG) !== false) {
				echo 'others : ' . $job_arr['others'] . "\n";

			} else {
				if ( ($job_arr['place'] == '-') && !empty($job->working_place) ) {
					$job_arr['place'] = $job->working_place;
				}

				$details = "<table>";
				$details = $details . "<tr><th>勤務地</th><td>"         . $job_arr['place']     . "</td></tr>";
				$details = $details . "<tr><th>給与</th><td>"           . $job_arr['income']    . "</td></tr>";
				$details = $details . "<tr><th>勤務時間</th><td>"       . $job_arr['work_time'] . "</td></tr>";
				$details = $details . "<tr><th>休暇制度</th><td>"       . $job_arr['holiday']   . "</td></tr>";
				$details = $details . "<tr><th>待遇・福利厚生</th><td>" . $job_arr['welfare']   . "</td></tr>";
				$details = $details . "<tr><th>リモートワーク</th><td>" . $job_arr['remote']    . "</td></tr>";
				$details = $details . "<tr><th>その他</th><td>"         . $job_arr['others']    . "</td></tr>";
				$details = $details . "</table>";

				$job->app_contents = $job_arr['contents'];
				$job->app_details = $details;
				$job->save();
			}

		}

	}


// エラーが発生しました。   エラーチェック用

	/*
	* AI からデータ取得
	*/
	private function getDataAI($job)
	{
	    $job_arr = [];
	    $content = $job->intro;
	    $userPrompt = "以下の募集内容：\n" . $content;

	    // 各項目に対応するシステムプロンプト
	    $prompts = [
	        'contents'   => $this->getJobDescriptionPrompt(),
	        'place'      => $this->getWorkplacePrompt(),
	        'income'     => $this->getIncomePrompt(),
	        'work_time'  => $this->getWorkTimePrompt(),
	        'holiday'    => $this->getHolidayPrompt(),
	        'welfare'    => $this->getWelfarePrompt(),
	        'remote'     => $this->getRemoteWorkPrompt(),
	        'others'     => $this->getOthersPrompt(), 
	    ];

		// 各プロンプトに対して OpenAI API を呼び出し、結果を配列に格納
	    foreach ($prompts as $key => $systemPrompt) {
			// OpenAI API を呼び出し、結果を取得
	        $output = $this->callOpenAI($systemPrompt, $userPrompt);

			if ($this->status_code != 200) return;
			
	        if ($key === 'remote') {
	            $job_arr[$key] = $this->processRemoteWorkAvailability($output);
	        } else {
	            $job_arr[$key] = $this->processContent($output);
	        }
	    }

		// 抽出結果の配列を返す
    return $job_arr;
	}


	private function getJobDescriptionPrompt()
	{
	    return <<<EOT
募集内容から**仕事内容のみ**を400-600文字で要約してください。
**募集内容が英語なら英語、日本語なら日本語**で出力してください。 

# Steps

1. **募集内容を丁寧に読み、仕事内容の全体像と主要なポイントを把握します。**
2. **「業務範囲」「日常的なタスク」「必要なスキル・経験」「期待される成果」など、仕事内容に関する重要な情報を抜き出します。**
3. **主語を「私たち」に固定し、第三者視点にならないように要約文を作成します。**
4. **要約文は400-600文字以内で、自然な日本語または英語でまとめます。**
5. **仕事内容に関する情報が見つからない場合は、「仕事内容の記載なし」と出力してください。**

# Output Format

- 要約文は400-600文字で、日本語または英語のまとまりのある文章として記述してください。  
- **仕事内容以外の情報は一切含めない**こと。  
- **仕事内容が記載されていない場合は、「仕事内容の記載なし」と明記してください。**

# Example Output

私たちは、企業の新たな成長とイノベーションを、戦略とテクノロジーを融合させながら支援することで、クライアントの経営課題を解決し、持続的かつ飛躍的な成長を実現することを目指しています。具体的には、成長戦略の立案や、それを実現するための変革テーマの推進、新規事業の戦略立案・立ち上げ、新商品・サービスの企画から推進まで幅広い業務に携わっていただきます。これらを通じて、社会にインパクトを与えながら、ビジネス成長の実現に貢献します。

# Notes

- **業務内容に関する必要な情報を取りこぼさない**ことを最優先とする。
- **複雑な表現は避け、読み手にとって理解しやすい文章**を心がける。
- **仕事内容が不明瞭または未記載の場合は、「仕事内容の記載なし」と記載する。**
EOT;
	}


	private function getWorkplacePrompt()
	{
	    return <<<EOT
募集内容から**勤務地のみ**を抽出してください。  
**募集内容が英語なら英語、日本語なら日本語**で出力してください。  

# 手順

1. **募集内容を読み、勤務地に関する記載箇所を特定します。**
2. **「勤務地」「勤務先」「就業場所」「オフィス所在地」など、勤務地を示す用語を確認します。**
3. **複数の勤務地が記載されている場合は、すべての勤務地を抽出してください。**
4. **複数の求人が記載されている場合は、それぞれの求人に対応する勤務地を個別に抽出します。**
5. **勤務地が明確でない場合は、「勤務地の記載なし」と記載してください。**

# 出力形式

- 抽出結果は、日本語または英語の自然な表現で簡潔に示してください。
- 求人が複数ある場合は、各求人ごとに勤務地を箇条書きで整理してください。

# 注意事項

- **「勤務地」以外の情報は一切含めない**ようにしてください。
- **記載された住所は可能な限り正確に転記**してください。
- **複数の求人がある場合は、各求人ごとに番号付きで区別し、明確に整理してください。**
- **勤務地の記載がない場合は、「勤務地の記載なし」と明示してください。**
EOT;
}

private function getIncomePrompt()
{
    return <<<EOT
募集内容から**給与のみ**を抽出してください。  
**募集内容が英語なら英語、日本語なら日本語**で出力してください。  

# 手順

1. **「給与」「年収」「月給」「時給」「賞与」「昇給」などの記載箇所を特定します。**
2. **給与に関連する詳細情報（昇給・賞与など）があれば、各求人ごとに併記します。**
3. **給与が記載されていない場合は、「給与の記載なし」と記載してください。**

# 出力形式

- 各求人ごとに給与情報を整理し、以下の形式で記載してください。
  - 年収：〇〇万円〜〇〇万円
  - 月給：〇〇万円
  - 賞与：年〇回
  - 昇給：年〇回

# 注意事項

- **給与に関する情報のみを記載**し、それ以外の情報は含めないようにしてください。
- **複数の求人がある場合は、各求人ごとに番号を付けて整理してください。**
- **給与情報が記載されていない場合は、「給与の記載なし」と明記してください。**
EOT;
}


	private function getWorkTimePrompt()
	{
    return <<<EOT
募集内容から**勤務時間のみ**を抽出してください。  
**募集内容が英語なら英語、日本語なら日本語**で出力してください。  

# 手順

1. **「勤務時間」「就業時間」「シフト」「フレックスタイム」などの記載箇所を探します。**
2. **フレックスタイム制の場合は、コアタイムなども含めて併記します。**
3. **勤務時間の記載がない場合は、「勤務時間の記載なし」と記載します。**

# 出力形式

- 各求人ごとに勤務時間を整理し、以下の形式で記載してください。
  - 〇〇〜〇〇（実働〇時間）
  - フレックスタイム制（コアタイム〇〇〜〇〇）

# 注意事項

- **勤務時間に関する情報のみを記載**し、それ以外の情報は含めないようにしてください。
- **複数の求人がある場合は、各求人ごとに番号を付けて整理してください。**
- **勤務時間の記載がない場合は、「勤務時間の記載なし」と明記してください。**
EOT;
	}


	private function getHolidayPrompt()
	{
	    return <<<EOT
募集内容から**休暇制度のみ**を抽出してください。
**募集内容が英語なら英語、日本語なら日本語**で出力してください。 

# Steps

1. **「休暇」「休日」「有給」「年間休日」「産休・育休」などに関する記載を探します。**
2. **休暇の種類が複数ある場合は、全てを抽出します。**
3. **休暇制度が記載されていない場合は、「休暇制度の記載なし」と記載します。**

# Output Format

  - 〇〇休暇（詳細）

# Notes

- **休暇制度以外の情報は含めない**こと。
- 記載がない場合は、「休暇制度の記載なし」と明記してください。
EOT;
	}


	private function getWelfarePrompt()
	{
	    return <<<EOT
募集内容から**待遇・福利厚生のみ**を抽出してください。
**募集内容が英語なら英語、日本語なら日本語**で出力してください。 

# Steps

1. **「待遇」「福利厚生」「手当」「支援制度」などの記載を探します。**
2. **記載がない場合は、「待遇・福利厚生の記載なし」と記載します。**

# Output Format

  - 〇〇（詳細があれば併記）

# Notes

- **待遇・福利厚生以外の情報は含めない**こと。
- 記載がない場合は、「待遇・福利厚生の記載なし」と明記してください。
EOT;
	}


	private function getRemoteWorkPrompt()
	{
	    return <<<EOT
募集内容から**リモートワークの可否**を抽出してください。

# Steps

1. **「リモートワーク」「在宅勤務」「テレワーク」に関する記載を探します。**
2. **以下のいずれかを抽出します：**
   - 「可」：完全にリモートワークが可能な場合  
   - 「不可」：リモートワークができない場合  
   - 「一部可」：条件付きで一部リモートワークが許可されている場合  
3. **記載がない場合は、「記載なし」と記載します。**

# Output Format

- 可 / 不可 / 一部可 / 記載なし

# Notes

- **リモートワークに関する情報のみ**を抽出すること。
- 必ず「可」「不可」「一部可」「記載なし」のいずれかを回答してください。
- 記載がない場合は、「記載なし」と明記してください。
EOT;
	}


	private function getOthersPrompt()
	{
    return <<<EOT
募集内容から指定された情報を抽出し、該当内容をそのまま提供してください。

# 取得対象

- **応募要件**: 該当する記載内容を本文そのまま取得します。
- **望ましい経験**: 該当する記載内容を本文そのまま取得します。

# 要件

- 抽出された情報は、原文をそのままで記載します。意訳や要約は行わないでください。
- 募集内容が英語の場合は英語、日本語の場合は日本語で表現してください。
- 該当する記載がない場合は、「該当記載なし」と明記してください。
- 【取得対象】以外の情報は含めないでください。

# 除外対象

次の情報は取得対象外ですので、含まないでください。

- 勤務地
- 給与
- 勤務時間
- 休暇制度
- 待遇・福利厚生
- リモートワーク

# Output Format

- 文章形式で、順序良く「応募要件」と「望ましい経験」を記載し、それぞれに該当する記載があるか、ないかを「該当記載なし」と記載してください。
EOT;
	}


	private function callOpenAI($systemPrompt, $userPrompt)
	{
		// OpenAI API の API キー
	    $apiKey = $this->openai_api_key;
	    $url = 'https://api.openai.com/v1/chat/completions';

	    $headers = [
	        'Content-Type: application/json',
	        'Authorization: Bearer ' . $apiKey,
	    ];

	    $data = [
	        'model' => 'gpt-4o', 
	        'messages' => [
	            ['role' => 'system', 'content' => $systemPrompt],
	            ['role' => 'user', 'content' => $userPrompt],
	        ],
	    ];

	    $ch = curl_init($url);

	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	    $response = curl_exec($ch);
	    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	    if (curl_errno($ch)) {
	        $error_msg = curl_error($ch);
	        curl_close($ch);
	        throw new \Exception('Curl error: ' . $error_msg);
	    }

	    curl_close($ch);

	    if ($status_code != 200) {
//	        throw new \Exception('OpenAI API error: ' . $response);

			$this->status_code = $status_code;
			$this->response = $response;
			return;
	    }

	    $result = json_decode($response, true);

	    return trim($result['choices'][0]['message']['content']);
	}


	private function processRemoteWorkAvailability($output)
	{
	    // 全角英数字を半角に変換し、前後の空白を削除
	    $output = trim(mb_convert_kana($output, 'as', 'UTF-8'));

	    // 許容される応答のリスト
	    $validResponses = ['可', '不可', '一部可'];

	    // 応答がリストに含まれているか確認
	    $foundResponses = array_filter($validResponses, function ($response) use ($output) {
	        return strpos($output, $response) !== false;
	    });

	    if (count($foundResponses) === 1) {
	        return reset($foundResponses); // 見つかった応答を返す
	    } else {
	        return "-"; // 見つからない場合、または複数の場合は「-」
	    }
	}


	private function processContent($output)
	{
	    $output = trim($output);

	    // "記載" または "記載なし" を含む場合、"-" を返す
	    if (strpos($output, '記載') !== false || strpos($output, '記載なし') !== false) {
	        return "-";
	    }

	    return $output; // 条件に該当しない場合はそのまま返す
	}

} // end class

?>
