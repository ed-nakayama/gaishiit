@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('job_detail' ,$comp ,$job) }}
@endsection


@section('addheader')
	<title>{{ $job->name }}-{{ $comp->name }}｜外資IT企業のクチコミ評価・求人なら外資IT.com</title>
	<meta name="description" content="{{ $comp->name }}の{{ $job->name }}の求人です。募集要項には給与・雇用形態・勤務地・給与・勤務時間といった基本的な情報から、休暇制度・待遇・福利厚生・リモートワークの有無などの詳細情報も記載しております。｜外資IT.comは外資系IT企業に特化したクチコミ・求人サイトです。採用が決まるまで完全無料、興味のある企業の担当者とは直接コミュニケーションも可能です。">
    <link href="{{ asset('css/department.css') }}" rel="stylesheet">
@endsection


@section('content')


<style>
.corp-name figure {
  border-radius: 50%;
  overflow: hidden;
  width: 70px;
  border: 1px solid #D6D6D6;
  background: #D6D6D6;
}

</style>

@if (Auth::guard('user')->check())
@include('user.user_activity')
@endif

<main class="pane-main">
	<div class="inner">
		<div class="ttl">
			<h1>{{ $comp->name }}の{{ $job->name }}求人</h1>
		</div>
{{--
		<div class="item thumb">
			<div class="inner">
				<figure class="corp_icon">
					<img src="{{ $comp->logo_file }}" alt="" style="vertical-align: middle;">
				</figure>
				<figure class="corp_bg">
					@if ($comp->image_file == '')
						<img src="/img/corp_img_01.jpg" alt="">
					@else
						<img src="{{ $comp->image_file }}" alt="">
					@endif
				</figure>
			</div>
		</div>
--}}

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
							<div class="corp-name">
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
