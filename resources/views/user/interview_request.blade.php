@extends('layouts.user.auth')


@section('addheader')
	<title>メッセージ一覧｜{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/interview.css') }}" rel="stylesheet">
    <link href="{{ asset('css/interview_request.css') }}" rel="stylesheet">
@endsection


@section('content')


@include('user.user_activity')

<main class="pane-main">
	<div class="inner">
		<div class="ttl">
			@if ($int_type == '0')
				<h2>カジュアル面談</h2>
			@elseif ($int_type == '1')
				<h2>正式応募</h2>
			@elseif ($int_type == '2')
				<h2>イベント</h2>
			@endif
		</div>

		<div class="con-wrap">

			<div class="item">
				<div class="item-inner">
					<div class="date">
						<dl>
							<dt>依頼日：</dt>
							<dd>{{  date("Y/m/d/H:i") }}</dd>
						</dl>
					</div>
					<div class="ttl"> 
						<a href="">
							<figure>
								@if(!empty($comp->logo_file))
									<img src="{{ $comp->logo_file }}" alt="">
								@endif
							</figure>
						</a>
						<div class="txt">
							<p class="name">
								<a href="#">
									{{ $comp->name }}
								</a>
							</p>
							<p class="location">{{ $comp->location_name }}</p>
						</div>
					</div>
                            
					<div class="item-info">
						@if(!empty($unit))
							<dl>
								<dt>部門</dt>
								<dd>{{ $unit->name }}</dd>
							</dl>
						@endif

						@if(!empty($job))
							<dl>
								<dt>求人</dt>
								<dd><span class="job-txt">{{ $job->name }}</span><span class="job-id">求人ID：{{ $job->job_code }}</span></dd>
							</dl>
						@endif
					</div>
					<div class="item-btm">
						@if(!empty($job))
							<ul class="tag">
								@if (!empty($job->job_cat_details))
									<li><a>{{ $job->getJobCategoryName() }}</a></li>
								@endif
							</ul>
							<p class="location"  style="font-size: 1.4rem;">{{ $job->getLocations() }} @if (!empty($job->else_location)) ({{ $job->else_location }})@endif</p>
						@endif
					</div>
				</div>
			</div>

			<div class="request">
				<div class="request-inner">
					@if ($int_type == '0')
						<h3>カジュアル面談を依頼する</h3>
						<p style="font-size: 16px;">氏名、メールアドレス、職務経歴書（簡易版）を企業へ公開することに同意し、応募をします。</p>
					@elseif ($int_type == '1')
						<h3>正式に応募する</h3>
						<p style="font-size: 16px;">氏名、メールアドレス、職務経歴書（簡易版）を企業へ公開することに同意し、応募をします。</p>
					@elseif ($int_type == '2')
						<h3>イベントを申し込む</h3>
						<p style="font-size: 16px;">氏名、メールアドレス、職務経歴書（簡易版）を企業へ公開することに同意し、応募をします。</p>
					@endif

					{{ html()->form('POST', '/interview/request/send')->attribute('name', 'reqform')->open() }}
					{{ html()->hidden('comp_id', $comp->id) }}
					@if(!empty($unit))
						{{ html()->hidden('unit_id', $unit->id) }}
					@endif
					@if(!empty($job))
						{{ html()->hidden('job_id', $job->id) }}
					@endif
					@if(!empty($event_id))
						{{ html()->hidden('event_id', $event_id) }}
					@endif
					{{ html()->hidden('int_type', $int_type) }}
					{{ html()->hidden('int_kind', $int_kind) }}
					<div class="button-wrap">
						@if (session('send_success'))
							<div class="alert alert-success"  style="color:#0000ff;font-size: 16px;">
								{{session('send_success')}}
							</div>
						@else
							<button type="submit">送信</button>
						@endif
					</div>
					{{ html()->form()->close() }}
				</div>

			</div>
		</div>
	</div>
</main>

@endsection
