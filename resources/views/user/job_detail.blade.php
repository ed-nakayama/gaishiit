@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('job_detail' ,$comp ,$job) }}
@endsection


@section('addheader')
	<title>{{ $job->name }}-{{ $comp->name }}｜{{ config('app.title') }}</title>
	<meta name="description" content="{{ $comp->name }}の{{ $job->name }}の求人です。募集要項には給与・雇用形態・勤務地・給与・勤務時間といった基本的な情報から、休暇制度・待遇・福利厚生・リモートワークの有無などの詳細情報も記載しております。｜{{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="{{ $job->name }}-{{ $comp->name }}｜{{ config('app.title') }}" />
	<meta property="og:description" content="{{ $comp->name }}の{{ $job->name }}の求人です。募集要項には給与・雇用形態・勤務地・給与・勤務時間といった基本的な情報から、休暇制度・待遇・福利厚生・リモートワークの有無などの詳細情報も記載しております。｜{{ config('app.description') }}" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

    <link href="{{ asset('css/department.css') }}" rel="stylesheet">
@endsection


@section('content')


@if (Auth::guard('user')->check())
@include('user.user_activity')
@endif

<main class="pane-main">
	<div class="inner">
		<div class="ttl">
			<h1>{{ $comp->name }}の{{ $job->name }}求人</h1>
		</div>

 		<div class="con-wrap">
			<div class="job-detail">
				<div class="job-detail__inner">
					<div class="job-detail__header">
						<figma class="job-detail__image">
							@if(!empty($comp->logo_file))
								<img src="{{ $comp->logo_file }}" alt="">
							@endif
						</figma>
						<div class="job-detail__data">
							<h2 class="job-detail__title">{{ $comp->name }}</h2>
							@if (Auth::guard('user')->check())
								{{ html()->form('POST', '/job/favorite/add')->attribute('name', 'faveriteform')->open() }}
								{{ html()->hidden("comp_id", $comp->id) }}
								{{ html()->hidden("job_id", $job->id) }}

								@if ($favorite_on == 0)
									{{ html()->hidden("job_add", '1') }}
									<button type='submit' class="job-detail__favorite">お気に入り登録</button>
								@else
									{{ html()->hidden("job_add", '0') }}
									<button type='submit' class="job-detail__favorite" style="background: #4AA5CE;color: #FFF;">お気に入り登録</button>
								@endif
								{{ html()->form()->close() }}
							@else
								<button type='submit' class="job-detail__favorite openModal button-modal" href="#modalLogin">お気に入り登録</button>
							@endif

							<dl class="job-detail__dl">
								<dt class="rl-job">職種</dt>
								<dd class="rl-job">{{ $job->getJobCatName() }}</dd>
								<dt class="rl-income">年収</dt>
								<dd class="rl-income">{{ $job->getIncome() }}</dd>
								<dt class="rl-area">エリア</dt>
								<dd class="rl-area">{{ $job->getLocations() }} @if (!empty($job->else_location))({{ $job->else_location }})@endif</dd>
							</dl>
						</div>
					</div>

					<ul class="job-detail__buttons">
						<li>
							<span class="job-detail__buttonTitle">少し話を聞いてみたい!!</span>
							@if ($job->casual_flag == '1')
								@if (Auth::guard('user')->check())
									<a class="job-detail__buttonLink" href="javascript:casualform.submit()">カジュアル面談<span>依頼する</span></a>
								@else
									<a class="job-detail__buttonLink openModal button-modal" href="#modalLogin">カジュアル面談<span>依頼する</span></a>
								@endif
							@endif
						</li>
						<li>
							<span class="job-detail__buttonTitle">会員登録済みなら簡単1分</span>
							@if (Auth::guard('user')->check())
								@if ($job->backg_flag == '1' && $user_act['cv_comp'] < 1)
									<a class="job-detail__buttonLink" style="background-color :lightgrey">正式応募<span>依頼する</span></a>
								@elseif ($job->backg_eng_flag == '1' && $user_act['cv_eng_comp'] < 1)
									<a class="job-detail__buttonLink" style="background-color :lightgrey">正式応募<span>依頼する</span></a>
								@elseif ($job->personal_flag == '1' && $user_act['vitae_comp'] < 1)
									<a class="job-detail__buttonLink" style="background-color :lightgrey">正式応募<span>依頼する</span></a>
								@else
									<a class="job-detail__buttonLink" href="javascript:formalform.submit()">正式応募<span>依頼する</span></a>
								@endif
							@else
								<a class="job-detail__buttonLink openModal button-modal" href="#modalLogin">正式応募<span>依頼する</span></a>
							@endif
						</li>
						<li>
							<span class="job-detail__buttonTitle">外資IT特化のプロに相談しながら進めたい！</span>
							@if (Auth::guard('user')->check())
								<a class="job-detail__buttonLink" href="javascript:agentform.submit()">転職エージェント<span>相談する</span></a>
							@else
								<a class="job-detail__buttonLink openModal button-modal" href="#modalLogin">転職エージェント<span>相談する</span></a>
							@endif
						</li>
					</ul>

					<p class="job-detail__caution">※「正式に応募する」と「外資IT特化の転職エージェントに相談」は、職務経歴書 、履歴書 が必要です。個人設定よりご登録をお願いします。</p>
					<br>
					@if (!empty($interviewList[0]))
						以前にこの求人へのカジュアル面談の依頼または、正式応募をしたことがあります
						<table style="font-size: 1.4rem;">
							<tr>
								<th>依頼/応募日</th><th>依頼/応募内容</th>
							</tr>
							@foreach ($interviewList as $int)
								<tr>
									<td>{{ $int->created_at->format('Y/m/d/H:i') }}</td>
									<td>　　@if ($int->interview_type == '0')カジュアル面談 @elseif ($int->interview_type == '1')正式応募@endif</td>
								</tr>
							@endforeach
						</table>
					@endif

					<hr class="job-detail__hr">

					<h3 class="job-detail__subTitle">仕事内容</h3>
					<div class="job-detail__description">
						<p>{!! nl2br(e($job->intro)) !!}</p>
					</div>
{{--
					<h3 class="job-detail__subTitle">募集要項</h3>
					<div class="job-detail__requirements">
						<p style="transform: rotate(0.03deg);">◆業務内容セールス＆コマースコンサルタントは、企業が顧客（生活者、法人顧客）に提供する価値や体験を再定義し、事業成長のためのEC含む顧客接点・企業変革の戦略立案・実現支援を担います。アクセンチュアの多様なタレントと協業して、お客様の売上拡大・コスト効率化を最短距離かつグローバルで実現するためのダイナミックなソリューションの提案や、お客様の環境を踏まえた全体アーキテクチャをデザイン・実現しています。セールス＆コマースコンサルタントは、お客様や業界に深い知見とオーナーシップを持ち、顧客を起点とした企業変革を実現する要となるポジションです。当ポジションは、多様なタレントがそれぞれのスペシャリティを追求しながらも、包括的な視点でのお客様の事業成長を実現するプロジェクトマネジメント、案件責任者を目指していただけます。◆具体的なプロジェクトの例・グローバルリテーラーのお客様向けグローバル売上拡大に向けたコマース変革・製造メーカーのお客様向け国内営業改革・製造メーカーのお客様向け新規販売チャネル・DTCブランド構築<br>
							<br> ◆求める人物像・変革マインドがあり、創意工夫・アイデアを出して、お客様の売上拡大を実現するまで支援したい方・デザイナーやクリエイティブ、テクノロジーなど、多様なケイパビリティとコラボレーションして新たなコマース・営業モデルを作ることを楽しめる方♦応募要件＜スタッフ＞・ECや営業、事業開発に関わる経験を３年以上経験している方＜マネジャー以上＞・ECや営業、事業開発に関わる経験３年以上・チームマネジメント経験・自身がコミットした売上拡大の実績をお持ちの方上記に加え、下記いずれかに該当する経験をお持ちの方・通信・ハイテク・メディア・エンターテイメント/製造・小売・流通・消費財・ヘルスケア・自動車・トラベル/金融/素材・エネルギー/官公庁のいずれかの業界経験・マーケティング業務理解・経験・経営企画・事業企画・営業・販売・カスタマーサービス業務理解・経験・コンサルティング経験（経営戦略・事業改革・EC関連）◆望ましい経験・スキル・英語を用いた実務経験（ビジネスレベルの英語力、TOEIC750点以上、海外常駐経験等）◆期待するヒューマンスキル・お客様や社内を巻き込んで推進するコミュニケーションスキル・既存の枠組みや業界の商習慣に捉われず、継続的に売上を上げるためのアイデアを出せるアイデアマンとして裏付けとなる勤勉性・研究熱心さ<br>
							<br>
							<br> Emp_RegularJPNSongCt_StrategyManage#LI-GM
						</p>
					</div>
--}}
					<ul class="job-detail__buttons">
						<li>
							<span class="job-detail__buttonTitle">少し話を聞いてみたい!!</span>
							@if ($job->casual_flag == '1')
								@if (Auth::guard('user')->check())
									<a class="job-detail__buttonLink" href="javascript:casualform.submit()">カジュアル面談<span>依頼する</span></a>
								@else
									<a class="job-detail__buttonLink openModal button-modal" href="#modalLogin">カジュアル面談<span>依頼する</span></a>
								@endif
							@endif
						</li>
						<li>
							<span class="job-detail__buttonTitle">会員登録済みなら簡単1分</span>
							@if (Auth::guard('user')->check())
								@if ($job->backg_flag == '1' && $user_act['cv_comp'] < 1)
									<a class="job-detail__buttonLink" style="background-color :lightgrey">正式応募<span>依頼する</span></a>
								@elseif ($job->backg_eng_flag == '1' && $user_act['cv_eng_comp'] < 1)
									<a class="job-detail__buttonLink" style="background-color :lightgrey">正式応募<span>依頼する</span></a>
								@elseif ($job->personal_flag == '1' && $user_act['vitae_comp'] < 1)
									<a class="job-detail__buttonLink" style="background-color :lightgrey">正式応募<span>依頼する</span></a>
								@else
									<a class="job-detail__buttonLink" href="javascript:formalform.submit()">正式応募<span>依頼する</span></a>
								@endif
							@else
								<a class="job-detail__buttonLink openModal button-modal" href="#modalLogin">正式応募<span>依頼する</span></a>
							@endif
						</li>
						<li>
							<span class="job-detail__buttonTitle">外資IT特化のプロに相談しながら進めたい！</span>
							@if (Auth::guard('user')->check())
								<a class="job-detail__buttonLink" href="javascript:agentform.submit()">転職エージェント<span>相談する</span></a>
							@else
								<a class="job-detail__buttonLink openModal button-modal" href="#modalLogin">転職エージェント<span>相談する</span></a>
							@endif
						</li>
					</ul>

				</div><!-- job-detail__inner -->
			</div><!-- job-detail -->
		</div><!-- con-wrap -->

		
		
		


	{{-- 他社の求人一覧 $comp $job --}}
		@include ('user/partials/else_job_list')
	{{-- END 他社の求人一覧 --}}

	{{-- クチコミ数ランキング --}}
		@include ('user/partials/eval_ranking_fix')
	{{-- END クチコミ数ランキング --}}

{{-- ログインモーダル  --}}
	@include('user/partials/login_modal')
{{-- END ログインモーダル  --}}

	{{ html()->form('POST', '/interview/request')->attribute('name', 'casualform')->open() }}
	{{ html()->hidden('comp_id', $comp->id) }}
	{{ html()->hidden('unit_id', $job->unit_id) }}
	{{ html()->hidden('job_id', $job->id) }}
	{{ html()->hidden('int_type', '0') }}
	{{ html()->hidden('int_kind', '2') }}
	{{ html()->form()->close() }}

	{{ html()->form('POST', '/interview/request')->attribute('name', 'formalform')->open() }}
	{{ html()->hidden('comp_id', $comp->id) }}
	{{ html()->hidden('unit_id', $job->unit_id) }}
	{{ html()->hidden('job_id', $job->id) }}
	{{ html()->hidden('int_type', '1') }}
	{{ html()->hidden('int_kind', '2') }}
	{{ html()->form()->close() }}

	{{ html()->form('POST', '/agent/request')->attribute('name', 'agentform')->open() }}
	{{ html()->hidden('comp_id', $comp->id) }}
	{{ html()->hidden('unit_id', $job->unit_id) }}
	{{ html()->hidden('job_id', $job->id) }}
	{{ html()->form()->close() }}

	</div><!-- inner -->
</main>



@endsection
