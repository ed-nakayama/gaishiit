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
{{--
						<li>
							<span class="job-detail__buttonTitle">外資IT特化のプロに相談しながら進めたい！</span>
							@if (Auth::guard('user')->check())
								<a class="job-detail__buttonLink" href="javascript:agentform.submit()">転職エージェント<span>相談する</span></a>
							@else
								<a class="job-detail__buttonLink openModal button-modal" href="#modalLogin">転職エージェント<span>相談する</span></a>
							@endif
						</li>
--}}
					</ul>
{{--
					<p class="job-detail__caution">※「正式に応募する」と「外資IT特化の転職エージェントに相談」は、職務経歴書 、履歴書 が必要です。個人設定よりご登録をお願いします。</p>
--}}
					<p class="job-detail__caution">※「正式に応募する」は、職務経歴書 、履歴書 が必要です。個人設定よりご登録をお願いします。</p>
					<p class="job-detail__caution">尚、カジュアル面談は保証されているものではなく不成立になる場合もございますのでご了承ください。</p>
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
					@if (empty($job->app_contents) || $job->app_contents == '-')
						<h3 class="job-detail__subTitle">仕事内容</h3>
						<div class="job-detail__description">
							<p>{!! nl2br(e($job->intro)) !!}</p>
						</div>
					@else
						<h3 class="job-detail__subTitle">仕事内容</h3>
						<div class="job-detail__description">
							<p>{!! nl2br(e($job->app_contents)) !!}</p>
						</div>

						<h3 class="job-detail__subTitle">募集要項</h3>
						<div class="job-detail__requirements">
							<p>
								{!! nl2br($job->app_details) !!}
							</p>
						</div>
					@endif

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
{{--
						<li>
							<span class="job-detail__buttonTitle">外資IT特化のプロに相談しながら進めたい！</span>
							@if (Auth::guard('user')->check())
								<a class="job-detail__buttonLink" href="javascript:agentform.submit()">転職エージェント<span>相談する</span></a>
							@else
								<a class="job-detail__buttonLink openModal button-modal" href="#modalLogin">転職エージェント<span>相談する</span></a>
							@endif
						</li>
--}}
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
