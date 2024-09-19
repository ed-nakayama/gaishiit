@extends('layouts.user.auth')

{{--
@section('breadcrumbs')
	{{ Breadcrumbs::render('mypage') }}
@endsection
--}}

@section('addheader')
	<title>マイページ｜{{ config('app.title') }}</title>
	<meta name="description" content="マイページ｜{{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="マイページ｜{{ config('app.title') }}" />
	<meta property="og:description" content="マイページ｜{{ config('app.description') }}" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

    <link href="{{ asset('css/mypage.css') }}" rel="stylesheet">
@endsection


@section('content')


@include('user.user_activity')

	<main class="pane-main">
		<div class="inner">

{{-- 広告領域 --}}
			<div class="con-wrap ads">
					<figure>
						@if ( empty($user_act['banner'][0]->image) )
							<img src="/img/mypage/img_ads.gif" alt="">
						@else
							@if ( empty($user_act['banner'][0]->url) )
								<a></a>
							@else
								<a href="{{ $user_act['banner'][0]->url }}">
									<img src="{{ $user_act['banner'][0]->image }}" alt="">
								</a>
							@endif
						@endif
					</figure>
			</div>
{{-- END 広告領域 --}}

			<div class="con-wrap col-2">

				<div class="item setting message">
					<div class="ttl">
						<h2>メッセージ</h2>
					</div>
					<div class="item-inner">
						<ul>
							@foreach ($interviewList as $int)
								<li>
									<a href="javascript:intform{{ $int['id'] }}.submit()">
										<dl>
 											<dt>
												<span class="tag">@if ($int->interview_type == '0')カジュアル面談 @elseif ($int->interview_type == '1')正式応募 @elseif ($int->interview_type == '2')イベント @endif </span>
												<span class="date">{{-- $int['updated_at']->format('Y/m/d/H:i') --}} {{ str_replace('-','/', substr($int->last_update, 0 ,16)) }} </span>
											</dt>
											<dd @if ($int['read_flag'] == '0')class="new"@endif>
												{{ mb_strimwidth($int->company_name, 0, 40, "...") }}
											</dd>
										</dl>
									</a>
									{{ html()->form('GET', '/interview/flow')->attribute('name', "intform{$int->id}")->open() }}
									{{ html()->hidden('interview_id', $int->id) }}
									{{ html()->form()->close() }}
								</li>
							@endforeach
						</ul>
						<button class="cate" onclick="location.href='/interview/list'">ALL</button>
					</div>
				</div>


				<div class="item info job">
 					<div class="ttl">
 						<h2>希望条件の求人</h2>
 					</div> 
 					<div class="item-inner">
 						<ul>
 							@if (empty($jobList[0]) )
 								<li>
 									希望条件を設定してください
 								</li>
 							@else
 								@foreach ($jobList as $job)
 									<li>
 										<a href="javascript:jobform{{ $job->id }}.submit()">
   											<p class="txt">{{ mb_strimwidth($job->name, 0, 40, "...") }}</p>
 											<div class="corp-name">
 												<figure>
													@if(!empty($job->logo_file))
														<img src="{{ $job->logo_file }}" alt="">
													@endif
  												</figure>
												{{ html()->form('POST', "/company/{$job->company_id}/{$job->id}")->attribute('name', "jobform{$job->id}")->open() }}
 												<p>{{ mb_strimwidth($job->company_name, 0, 40, "...") }}</p>
												{{ html()->form()->close() }}
 											</div>
 										</a>
 									</li>
 								@endforeach
 							@endif
 						</ul>
 						<button class="cate" onclick="location.href='/job'">ALL</button>
 					</div>
 				</div>
 			</div><!-- con-wrap col-2 -->

			<div class="con-wrap">
				<div class="ttl">
					<h2>お気に入り</h2>
				</div>        

@php
	$pre_comp_id = '0';
	$next_arg = 1;
@endphp

				<div class="pickup">
					<ol class="pickup-list">

						@foreach ($favoriteList as $job)

							@if ($pre_comp_id != $job->company_id)
@php
	$ranking = $job->getCompanyRanking();
@endphp
								<li class="pickup-list__item">
									<div class="company-item">
										<figure class="company-item__image">
											@if (!empty($job->logo_file))
												<img src="{{ $job->logo_file }}" alt="">
											@endif
										</figure>
										<div class="company-item__content">
											<p class="company-item__name"><a href="/company/{{ $job->company_id }}">{{ $job->company_name }}</a></p>
											<dl class="company-item__reviews">
												<dt>総合評価</dt>
												<dd>
													<span>{{ number_format($ranking->total_point, 2) }}</span>
													<span class="star5_rating" style="--rate: {{ $ranking->total_rate . '%' }};"></span>
												</dd>
												<dt>クチコミ件数</dt>
												<dd>{{ number_format($ranking->answer_count) }} 件</dd>
											</dl>
											<p class="company-item__button"><a href="/company/{{ $ranking->company_id }}">詳細を見る</a></p>
										</div>
									</div>
									<ul class="job-opening-list">
							@endif
									<li class="job-opening-list__item">
										<h3 class="job-opening-list__title">{{ $job->name }}</h3>
										<p class="job-opening-list__text">{{ mb_strimwidth($job->intro, 0, 250, "...") }}</p>
										<div class="job-opening-list__footer">
											<dl class="job-opening-list__dl">
												<dt>年収</dt>
												<dd>{{ $job->getIncome() }}</dd>
												<dt>エリア</dt>
												<dd>{{ $job->getLocations() }} @if (!empty($job->else_location))({{ $job->else_location }})@endif</dd>
											</dl>
											<p class="detail-link-button"><a href="/company/{{ $job->company_id }}/{{ $job->id }}">求人詳細を見る</a></p>
										</div>
									</li>

	@if (!empty($favoriteList[$next_arg]))
		@if ($favoriteList[$next_arg]->company_id != $job->company_id)
									</ul>
								</li>
		@endif
	@endif
	@php
		$pre_comp_id = $job->company_id;
		$next_arg++;
	@endphp

@endforeach

					</ol>
					@if (!empty($favoriteList[0]))
						<p class="detail-link-button"><a href="/job/favorite">お気に入り一覧</a></p>
					@endif
				</div>
			</div><!-- con-wrap -->


@if (!empty($eventList[0]))
			<div class="con-wrap">
				<div class="item info event">
					<div class="ttl">
						<h2>イベント</h2>
					</div>
					<ul>
						@foreach ($eventList as $event)
							<li>
								<figure>
									@if (!empty($event->image))
										<a href="javascript:eventform{{ $event->id }}.submit()"><img src="{{ $event->image }}" alt=""></a>
									@else
										<a href="javascript:eventform{{ $event->id }}.submit()"><img src="/img/mypage/img_event.jpg" alt=""></a>
									@endif
								</figure>
								<div class="inner">
									<p class="event-name"><a href="javascript:eventform{{ $event->id }}.submit()">{{ mb_strimwidth($event->name, 0, 40, "...") }}</a></p>
									<div class="info-c">
										<div class="corp-name">
											<figure>
												<a href="javascript:eventform{{ $event->id }}.submit()"><img src="{{ $event->logo_file }}" alt=""></a>
											</figure>
											<p><a href="javascript:eventform{{ $event->id }}.submit()">{{ mb_strimwidth($event->company_name, 0, 40, "...") }}</a></p>
										</div>
										<div class="time">
											<a href="javascript:eventform{{ $event->id }}.submit()"><span class="cate">@if ($event->online_flag == '1')オンライン@else @if (empty($event->place))オフライン @else{{ $event->place }} @endif @endif</span></a>
											<a href="javascript:eventform{{ $event->id }}.submit()"><span class="date">{{ str_replace('-','/', substr($event->event_date, 0 ,10)) . '/' . $event->start_hour . ':' . $event->start_min . '〜' . $event->end_hour . ':' . $event->end_min }}</span></a>
										</div>
									</div>
									<p class="txt">
										<a href="javascript:eventform{{ $event->id }}.submit()">{{ mb_strimwidth($event->intro, 0, 180, "...") }}</a>
									</p>
								</div>
								{{ html()->form('POST', "/company/{$event->company_id}/event/{$event->id}")->attribute('name', "eventform{$event->id}")->open() }}
								{{ html()->form()->close() }}
							</li>
						@endforeach
					</ul>
					<button class="cate" onclick="location.href='/event'">ALL</button>
				</div>
			</div><!-- con-wrap -->
@endif

		</div><!-- inner -->
	</main> <!-- pane-main -->

    <script src="{{ asset('/js/setting.js') }}"></script>
    
@endsection
