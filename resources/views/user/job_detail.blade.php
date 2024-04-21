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
			<div class="item info">
				<div class="item-inner">
					<div class="item-top">

					<p class="name">
						<a></a>
					</p>
				<div>
					@if (Auth::guard('user')->check())
						{{ html()->form('POST', '/job/favorite/add')->attribute('name', 'faveriteform')->open() }}
						{{ html()->hidden("comp_id", $comp->id) }}
						{{ html()->hidden("job_id", $job->id) }}

						@if ($favorite_on == 0)
							{{ html()->hidden("job_add", '1') }}
							<button type='submit'>お気に入り登録</button>
						@else
							{{ html()->hidden("job_add", '0') }}
							<button type='submit' style="background: #4AA5CE;color: #FFF;">お気に入り登録</button>
						@endif
						{{ html()->form()->close() }}
					@else
						<button type='submit' class="openModal button-modal" href="#modalLogin">お気に入り登録</button>
					@endif
				</div>
			</div>

			<div class="item-top">
				<table style="margin-left:20px; margin-right:20px; font-size: 16px;">
					<tr>
						<td style="display:flex; align-items: center;">
							<div class="expand-job-corp-name">
								<figure>
									@if(!empty($comp->logo_file))
										<img src="{{ $comp->logo_file }}" alt="" style="vertical-align: middle;">
									@endif
								</figure>
							</div>
							<p class="expand-name" style="padding-left:10px;">{{ $comp->name }}</p>
						</td>
					</tr>
					<tr>
						<td>
							<p class="expand-title">{{ $job->name }}</p>
						</td>
					</tr>
					<tr>
						<td>
							<div class="item-top">
								<ul class="tag">
									@if (!empty($job->job_cat_details))
										<li><a>{{ $job->getJobCategoryName() }}</a></li>
									@endif
								</ul>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div style="display: flex;">
								<p class="job-income">
									年収 
									{{ $job->getIncome() }}
								</p>
								<p class="job-location">{{ $job->getLocations() }} @if (!empty($job->else_location))({{ $job->else_location }})@endif</p>
							</div>
						</td>
					</tr>
				</table>
			</div>


			{{-- 面談リクエストボタン $job $user_act --}}
				@include ('user/partials/interview_request')
			{{-- END 面談リクエストボタン --}}
			
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

		</div>{{-- con-wrap --}}
	</div> {{-- inner --}}

	<br>
	<div class="con-wrap">
		<h2>仕事内容</h2>
			<div class="item info" style="margin-top:0px;">
				<div class="item-inner">
					<p style="transform: rotate(0.03deg);">{!! nl2br(e($job->intro)) !!}</p>
				</div>
				</div>
		</div>{{-- con-wrap --}}
	<br>

	{{-- 面談リクエストボタン $job $user_act --}}
		@include ('user/partials/interview_request')
	{{-- END 面談リクエストボタン --}}
			
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

</main>



@endsection
