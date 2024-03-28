@extends('layouts.user.auth')

@section('content')

<head>
	<title>企業詳細｜{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/department.css') }}" rel="stylesheet">
    <link href="{{ asset('css/chart.css') }}" rel="stylesheet">
</head>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.js"></script>

<body>

<style>
.pane-main .eval .inner ul li .on {
  background: #fff;
  border: 1px solid #4AA5CE;
  border-radius: 20px;
  overflow: hidden;
  width: 48%;
  padding: 4px 10px;
  margin: 8px auto;
  text-align: center;
  background:#4AA5CE;
}

.button-chart2 {
  display: flex;
  justify-content: center;
  margin: 2px auto;
  transform: rotate(0.03deg);
}

.button-chart2 a {
  display: inline-block;
  font-size: 1.4rem;
  color:#4AA5CE;
  border-radius: 20px;
  max-width: 240px;
  width: 100%;
  text-align: center;
}

</style>

@if (Auth::guard('user')->check())
@include('user.user_activity')
@endif

	<main class="pane-main">
		<div class="inner">
			<div class="ttl">
				<h2>企業</h2>
			</div>

			<div class="con-wrap">
				<div class="item thumb">
					<div class="inner">
						<figure class="corp_icon">
							<img src="/storage/comp/10000000/corp_ico_01.png" alt="">
						</figure>
	 					<figure class="corp_bg">
	 						<img src="/storage/comp/10000000/corp_img_01.jpg" alt="">
						</figure>
					</div>
				</div>

				<div class="item info">
					<div class="item-inner">
						<div class="ttl">
							<div class="txt">
								<p class="name">
									<a>
										イーソニック（System setting test）
									</a>
								</p>
							</div>
	  						<div class="button-flex">
								<form method="POST" action="https://gaishiit.com/compfaq" accept-charset="UTF-8" name="unitform" id="faqform"><input name="_token" type="hidden" value="Mfgher0lUtWQnbZnj4gEoDcFfbWXTwAli3lJ0ML3">
								<input class="form-control" name="company_id" type="hidden" value="10000000">
								<a href="javascript:faqform.submit()">よくあるお問合せ</a>
								</form>
							</div>
						</div>

						<div class="item-info">
							<p>紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介<br />
紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介<br />
紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介<br />
紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介<br />
紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介<br />
紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介紹介1</p>
							<div class="button-flex">
								<a href="javascript:intform.submit()">カジュアル面談を依頼</a>
							</div>
						</div>
					</div>
				</div>

{{--  グラフ --}}

			<div class="ttl">
				<h3>社員による会社評価スコア</h3>
			</div>


			<div class="canClass">
				<div>
					<canvas id="myRadarChart"></canvas>
				</div>
				<div style="text-align: center;">
					<p class="txt">総合評価</p>　<b>{{ number_format($total_eval, 2) }}</b>
					<p class="txt" style="font-size:12px;">回答者数 {{ $average->cnt }}人</p>
					<p><span class="star5_rating"  style="--rate: {{ $total_rate . '%' }};"></span></p>
					<div class="button-chart">
						<a href="/eval/regist?comp_id={{ $comp->id }}" >企業の評価をする</a>
					</div>
				</div>
			</div>


			<div class="eval">
				<div class="inner">
					<ul>
						
						<li @if ($cat_sel == '1') style="background:#4AA5CE;" @endif>
							<div class="button-chart2">
								{{ Form::open(['url' => '/chart', 'name' => 'salform', 'method' => 'get' ]) }}
								{{ Form::hidden("comp_id", $comp->id) }}
								{{ Form::hidden("cat_sel", '1') }}
								{{ Form::close() }}
								<a href="javascript:salform.submit()" @if ($cat_sel == '1') style="color:#ffffff;" @endif>給与（{{ $average->salary_cnt }}件）</a>
							</div>
						</li>
						<li @if ($cat_sel == '2') style="background:#4AA5CE;" @endif>
							<div class="button-chart2">
								{{ Form::open(['url' => '/chart', 'name' => 'welform', 'method' => 'get' ]) }}
								{{ Form::hidden("comp_id", $comp->id) }}
								{{ Form::hidden("cat_sel", '2') }}
								{{ Form::close() }}
								<a href="javascript:welform.submit()" @if ($cat_sel == '2') style="color:#ffffff;" @endif>福利厚生（{{ $average->welfare_cnt }}件）</a>
							</div>
						</li>
						<li @if ($cat_sel == '3') style="background:#4AA5CE;" @endif>
							<div class="button-chart2">
								{{ Form::open(['url' => '/chart', 'name' => 'upform', 'method' => 'get' ]) }}
								{{ Form::hidden("comp_id", $comp->id) }}
								{{ Form::hidden("cat_sel", '3') }}
								{{ Form::close() }}
								<a href="javascript:upform.submit()" @if ($cat_sel == '3') style="color:#ffffff;" @endif>育成（{{ $average->upbring_cnt }}件）</a>
							</div>
						</li>
						<li @if ($cat_sel == '4') style="background:#4AA5CE;" @endif>
							<div class="button-chart2">
								{{ Form::open(['url' => '/chart', 'name' => 'compform', 'method' => 'get' ]) }}
								{{ Form::hidden("comp_id", $comp->id) }}
								{{ Form::hidden("cat_sel", '4') }}
								{{ Form::close() }}
								<a href="javascript:compform.submit()" @if ($cat_sel == '4') style="color:#ffffff;" @endif>法令遵守の意識（{{ $average->compliance_cnt }}件）</a>
							</div>
						</li>
						<li @if ($cat_sel == '5') style="background:#4AA5CE;" @endif>
							<div class="button-chart2">
								{{ Form::open(['url' => '/chart', 'name' => 'motform', 'method' => 'get' ]) }}
								{{ Form::hidden("comp_id", $comp->id) }}
								{{ Form::hidden("cat_sel", '5') }}
								{{ Form::close() }}
								<a href="javascript:motform.submit()" @if ($cat_sel == '5') style="color:#ffffff;" @endif>社員のモチベーション（{{ $average->motivation_cnt }}件）</a>
							</div>
						</li>
						<li @if ($cat_sel == '6') style="background:#4AA5CE;" @endif>
							<div class="button-chart2">
								{{ Form::open(['url' => '/chart', 'name' => 'workform', 'method' => 'get' ]) }}
								{{ Form::hidden("comp_id", $comp->id) }}
								{{ Form::hidden("cat_sel", '6') }}
								{{ Form::close() }}
								<a href="javascript:workform.submit()" @if ($cat_sel == '6') style="color:#ffffff;" @endif>ワークライフバランス（{{ $average->work_life_cnt }}件）</a>
							</div>
						</li>
						<li @if ($cat_sel == '7') style="background:#4AA5CE;" @endif>
							<div class="button-chart2">
								{{ Form::open(['url' => '/chart', 'name' => 'remform', 'method' => 'get' ]) }}
								{{ Form::hidden("comp_id", $comp->id) }}
								{{ Form::hidden("cat_sel", '7') }}
								{{ Form::close() }}
								<a href="javascript:remform.submit()" @if ($cat_sel == '7') style="color:#ffffff;" @endif>リモート勤務（{{ $average->remote_cnt }}件）</a>
							</div>
						</li>
						<li @if ($cat_sel == '8') style="background:#4AA5CE;" @endif>
							<div class="button-chart2">
								{{ Form::open(['url' => '/chart', 'name' => 'retform', 'method' => 'get' ]) }}
								{{ Form::hidden("comp_id", $comp->id) }}
								{{ Form::hidden("cat_sel", '8') }}
								{{ Form::close() }}
								<a href="javascript:retform.submit()" @if ($cat_sel == '8') style="color:#ffffff;" @endif>定年（{{ $average->retire_cnt }}件）</a>
							</div>
						</li>
					</ul>
				</div>
			</div>



			<div class="ttl">
				<h3>Pick up 社員クチコミ</h3>
			</div>

@foreach ($evalList as $eval)
			<div class="con-wrap">
				<div class="item thumb">
					<div class="inner">

						<div class="txt" style="padding: 4px 16px;text-align: right;">
							　回答者：{{ $eval->occupation }}、在籍@if ($eval->retire_year == '9999'){{ \Carbon\Carbon::today()->format('Y') - $eval->join_year}}@else{{ $eval->retire_year - $eval->join_year }}@endif年、@if ($eval->sex == '1')男性@elseif ($eval->sex == '2')女性@elseif ($eval->sex == '0')性別なし@else @if ($eval->id % 5 == '2')女性@else男性@endif @endif
						</div>

						<div class="eval-item">
							<p class="eval-header">
								<span style="width:200px;">
								@if ($eval->cat_sel == '1')給与
								@elseif ($eval->cat_sel == '2')福利厚生
								@elseif ($eval->cat_sel == '3')育成
								@elseif ($eval->cat_sel == '4')法令遵守の意識
								@elseif ($eval->cat_sel == '5')社員のモチベーション
								@elseif ($eval->cat_sel == '6')ワークライフバランス
								@elseif ($eval->cat_sel == '7')リモート勤務
								@elseif ($eval->cat_sel == '8')定年
								@endif
								</span><span class="star5_rating" style="--rate: {{ $eval->salary_point * 100 / 5  . '%' }};"></span>
							</p>
							<div class="eval-txt">
								<p class="eval-detail">
								@if (Auth::guard('user')->check() || $loop->index == 0)
									@if ($eval->cat_sel == '1'){!! nl2br($eval->salary_content) !!}
									@elseif ($eval->cat_sel == '2'){!! nl2br($eval->welfare_content) !!}
									@elseif ($eval->cat_sel == '3'){!! nl2br($eval->upbring_content) !!}
									@elseif ($eval->cat_sel == '4'){!! nl2br($eval->compliance_content) !!}
									@elseif ($eval->cat_sel == '5'){!! nl2br($eval->motivation_content) !!}
									@elseif ($eval->cat_sel == '6'){!! nl2br($eval->work_life_content) !!}
									@elseif ($eval->cat_sel == '7'){!! nl2br($eval->remote_content) !!}
									@elseif ($eval->cat_sel == '8'){!! nl2br($eval->retire_content) !!}
									@endif
								@else
									@if ($eval->cat_sel == '1'){!! mb_strimwidth($eval->salary_content, 0, 40, "...") !!}
									@elseif ($eval->cat_sel == '2'){!! mb_strimwidth($eval->welfare_content, 0, 40, "...") !!}
									@elseif ($eval->cat_sel == '3'){!! mb_strimwidth($eval->upbring_content, 0, 40, "...") !!}
									@elseif ($eval->cat_sel == '4'){!! mb_strimwidth($eval->compliance_content, 0, 40, "...") !!}
									@elseif ($eval->cat_sel == '5'){!! mb_strimwidth($eval->motivation_content, 0, 40, "...") !!}
									@elseif ($eval->cat_sel == '6'){!! mb_strimwidth($eval->work_life_content, 0, 40, "...") !!}
									@elseif ($eval->cat_sel == '7'){!! mb_strimwidth($eval->remote_content, 0, 40, "...") !!}
									@elseif ($eval->cat_sel == '8'){!! mb_strimwidth($eval->retire_content, 0, 40, "...") !!}
									@endif
									{{ mb_strimwidth($eval->salary_content, 0, 40, "...") }}<br>
									<div class="blur">
										{!! nl2br($dummyMsg) !!}
									</div>
								</p>
								<div class="login">
									<a class="eval-button" href="#" style="color:white;white-space:nowrap;">無料のユーザ登録をお願いします</a><br><br>
									<a class="eval-button2" href="#" style="white-space:nowrap;">ログインはこちら</a>
								</div>
								@endif
							</div>
						</div>
					</div><!-- item-inner -->
				</div><!-- item -->
			</div><!-- con-wrap -->
<br>
@endforeach

{{--  求人 --}}
						<div class="job">
                            <div class="inner">
                                <h2>求人</h2>
                                <ul>
													<li>
								<div class="inner">
									<p class="job-name">
									<a href="javascript:jobform4433.submit()" style="font-weight: bold;">銀行業界コンサルタント【FS-Banking】１</a></p>
									<div class="info-btm">
										<ul class="tag">
																				</ul>
																				<p class="location" style="font-size: 1.4rem;">東京/大阪/福岡 </p>
																			</div>
									<p class="txt">
										<a href="javascript:jobform4433.submit()" style="font-size: 1.4rem;">とにかくすごいんです。</a>
									</p>
								</div>
								<form method="POST" action="https://gaishiit.com/job/detail" accept-charset="UTF-8" name="jobform4433"><input name="_token" type="hidden" value="Mfgher0lUtWQnbZnj4gEoDcFfbWXTwAli3lJ0ML3">
								<input class="form-control" name="job_id" type="hidden" value="4433">
								</form>
							</li>
													<li>
								<div class="inner">
									<p class="job-name">
									<a href="javascript:jobform4434.submit()" style="font-weight: bold;">銀行業界コンサルタント【FS-Banking】２</a></p>
									<div class="info-btm">
										<ul class="tag">
																				</ul>
																				<p class="location" style="font-size: 1.4rem;">東京/大阪 </p>
																			</div>
									<p class="txt">
										<a href="javascript:jobform4434.submit()" style="font-size: 1.4rem;">職務内容 Roles and Responsibilities
        
        
          ◆担当業界
　- 国内メガバンク（海外現地法人、子会社を含む）、地方銀行、政府系金融機関
　- ノンバンク・流通・通信系等企業の子会社銀行
　- 信託銀行

◆担当業務
【Gowth &amp; Innovation】
　- ...</a>
									</p>
								</div>
								<form method="POST" action="https://gaishiit.com/job/detail" accept-charset="UTF-8" name="jobform4434"><input name="_token" type="hidden" value="Mfgher0lUtWQnbZnj4gEoDcFfbWXTwAli3lJ0ML3">
								<input class="form-control" name="job_id" type="hidden" value="4434">
								</form>
							</li>
													<li>
								<div class="inner">
									<p class="job-name">
									<a href="javascript:jobform4436.submit()" style="font-weight: bold;">銀行業界コンサルタント【FS-Banking】99</a></p>
									<div class="info-btm">
										<ul class="tag">
																				</ul>
																				<p class="location" style="font-size: 1.4rem;">東京/大阪 </p>
																			</div>
									<p class="txt">
										<a href="javascript:jobform4436.submit()" style="font-size: 1.4rem;">職務内容 Roles and Responsibilities
        
        
          ◆担当業界
　- 国内メガバンク（海外現地法人、子会社を含む）、地方銀行、政府系金融機関
　- ノンバンク・流通・通信系等企業の子会社銀行
　- 信託銀行

◆担当業務
【Gowth &amp; Innovation】
　- ...</a>
									</p>
								</div>
								<form method="POST" action="https://gaishiit.com/job/detail" accept-charset="UTF-8" name="jobform4436"><input name="_token" type="hidden" value="Mfgher0lUtWQnbZnj4gEoDcFfbWXTwAli3lJ0ML3">
								<input class="form-control" name="job_id" type="hidden" value="4436">
								</form>
							</li>
													<li>
								<div class="inner">
									<p class="job-name">
									<a href="javascript:jobform4437.submit()" style="font-weight: bold;">銀行業界コンサルタント【FS-Banking】98</a></p>
									<div class="info-btm">
										<ul class="tag">
																				</ul>
																				<p class="location" style="font-size: 1.4rem;">東京/大阪 </p>
																			</div>
									<p class="txt">
										<a href="javascript:jobform4437.submit()" style="font-size: 1.4rem;">職務内容 Roles and Responsibilities
        
        
          ◆担当業界
　- 国内メガバンク（海外現地法人、子会社を含む）、地方銀行、政府系金融機関
　- ノンバンク・流通・通信系等企業の子会社銀行
　- 信託銀行

◆担当業務
【Gowth &amp; Innovation】
　- ...</a>
									</p>
								</div>
								<form method="POST" action="https://gaishiit.com/job/detail" accept-charset="UTF-8" name="jobform4437"><input name="_token" type="hidden" value="Mfgher0lUtWQnbZnj4gEoDcFfbWXTwAli3lJ0ML3">
								<input class="form-control" name="job_id" type="hidden" value="4437">
								</form>
							</li>
													<li>
								<div class="inner">
									<p class="job-name">
									<a href="javascript:jobform4438.submit()" style="font-weight: bold;">銀行業界コンサルタント【FS-Banking】98-1</a></p>
									<div class="info-btm">
										<ul class="tag">
																				</ul>
																				<p class="location" style="font-size: 1.4rem;">東京/大阪 </p>
																			</div>
									<p class="txt">
										<a href="javascript:jobform4438.submit()" style="font-size: 1.4rem;">職務内容 Roles and Responsibilities
        
        
          ◆担当業界
　- 国内メガバンク（海外現地法人、子会社を含む）、地方銀行、政府系金融機関
　- ノンバンク・流通・通信系等企業の子会社銀行
　- 信託銀行

◆担当業務
【Gowth &amp; Innovation】
　- ...</a>
									</p>
								</div>
								<form method="POST" action="https://gaishiit.com/job/detail" accept-charset="UTF-8" name="jobform4438"><input name="_token" type="hidden" value="Mfgher0lUtWQnbZnj4gEoDcFfbWXTwAli3lJ0ML3">
								<input class="form-control" name="job_id" type="hidden" value="4438">
								</form>
							</li>
													<li>
								<div class="inner">
									<p class="job-name">
									<a href="javascript:jobform4439.submit()" style="font-weight: bold;">銀行業界コンサルタント【FS-Banking】97</a></p>
									<div class="info-btm">
										<ul class="tag">
																				</ul>
																				<p class="location" style="font-size: 1.4rem;">東京/大阪 </p>
																			</div>
									<p class="txt">
										<a href="javascript:jobform4439.submit()" style="font-size: 1.4rem;">職務内容 Roles and Responsibilities
        
        
          ◆担当業界
　- 国内メガバンク（海外現地法人、子会社を含む）、地方銀行、政府系金融機関
　- ノンバンク・流通・通信系等企業の子会社銀行
　- 信託銀行

◆担当業務
【Gowth &amp; Innovation】
　- ...</a>
									</p>
								</div>
								<form method="POST" action="https://gaishiit.com/job/detail" accept-charset="UTF-8" name="jobform4439"><input name="_token" type="hidden" value="Mfgher0lUtWQnbZnj4gEoDcFfbWXTwAli3lJ0ML3">
								<input class="form-control" name="job_id" type="hidden" value="4439">
								</form>
							</li>
						                                </ul>
                                                            </div>
						</div>


		</div><!-- inner -->
	</main>

<script>

	var ctx = document.getElementById("myRadarChart");
	var myRadarChart = new Chart(ctx, {
		//グラフの種類
		type: 'radar',
		//データの設定
		data: {
			labels: [
				['給与',                 '{{ number_format($average->salary_point,     1) }}' ],
				['福利厚生',             '{{ number_format($average->welfare_point,    1) }}' ],
				['育成',                 '{{ number_format($average->upbring_point,    1) }}' ],
				['法令遵守の意識',       '{{ number_format($average->compliance_point, 1) }}' ],
				['社員のモチベーション', '{{ number_format($average->motivation_point, 1) }}' ],
				['ワークライフバランス', '{{ number_format($average->work_life_point,  1) }}' ],
				['リモート勤務',         '{{ number_format($average->remote_point,     1) }}' ],
				['定年',                 '{{ number_format($average->retire_point,     1) }}' ],
			],
			datasets: [{
				//グラフのデータ
				data: [
					'{{ number_format($average->salary_point,     1) }}',
					'{{ number_format($average->welfare_point,    1) }}',
					'{{ number_format($average->upbring_point,    1) }}',
					'{{ number_format($average->compliance_point, 1) }}',
					'{{ number_format($average->motivation_point, 1) }}',
					'{{ number_format($average->work_life_point,  1) }}',
					'{{ number_format($average->remote_point,     1) }}',
					'{{ number_format($average->retire_point,     1) }}',
				 ],
				// データライン
				borderColor: '#5D99FF',
				borderWidth: 1,
    		}],
  		},
		//オプションの設定
		options: {
			responsive: true,
			maintainAspectRatio: false,
			scales: {
				r: {
					//グラフの最小値・最大値
					min: 0,
					max: 5,
					//背景色
					backgroundColor: 'white',
					//グリッドライン
					grid: {
						color: '#CCCCCC',
					},
					//アングルライン
					angleLines: {
						color: '#CCCCCC',
					},
					//各項目のラベル
					pointLabels: {
						color: '#444444',
						font: {
							size: 12,
						}
					},
					ticks: {
						stepSize: 1,
					}
				},
			},
			plugins:{
				legend:{
					display:false // lavel 非表示
				}
			}
		}, 
	});

</script>

@endsection
