@extends('layouts.user.auth')


@section('addheader')
	<title>転職エージェントに相談 ｜{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/interview.css') }}" rel="stylesheet">
    <link href="{{ asset('css/interview_request.css') }}" rel="stylesheet">
@endsection


@section('content')


@include('user.user_activity')

<main class="pane-main">
	<div class="inner">
		<div class="ttl">
			<h2>転職エージェントに相談</h2>
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
						@endif
					</div>
					<div class="item-btm">
						<p class="job-income "style="font-size: 1.4rem;">
							年収 
						{{ $job->getIncome() }}
						</p>
						<p class="location"  style="font-size: 1.4rem;">{{ $job->getLocations() }} @if (!empty($job->else_location)) ({{ $job->else_location }})@endif</p>
					</div>
				</div>
			</div>

			<div class="request">
				<div class="request-inner">
					<h3>転職エージェントに相談する</h3>
					<p style="font-size: 16px; color:red;">キャリア、スキル感が弊社規定にマッチしないとご判断させて頂いた場合、申し訳ございませんが転職相談が出来かねます場合がございます。あらかじめご了承くださいませ。</p>

					{{ html()->form('POST', '/agent/request/send')->attribute('name', 'reqform')->open() }}
					{{ html()->hidden('comp_id', $comp->id) }}
					@if(!empty($unit))
						{{ html()->hidden('unit_id', $unit->id) }}
					@endif
					@if(!empty($job))
						{{ html()->hidden('job_id', $job->id) }}
					@endif
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
