@extends('layouts.user.auth')


@section('addheader')
	<title>外資IT企業のクチコミ評価・求人なら外資IT.com</title>
	<title>イベント｜{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/mypage.css') }}" rel="stylesheet">
@endsection


@section('content')


@include('user.user_activity')


<main class="pane-main">
	<div class="inner">
		<div class="ttl">
			<h2>イベント</h2>
		</div>

			<div class="con-wrap">
				@if (!empty($eventList[0]->id))

					<div class="item info event">
						<ul>
							@foreach ($eventList as $event)
								<li>
									<figure>
										@if (!empty($event->image))
											<a href="/company/{{ $event->company_id }}/event/{{ $event->id }}"><img src="{{ $event->image }}" alt=""></a>
										@else
											<a href="/company/{{ $event->company_id }}/event/{{ $event->id }}"><img src="/img/mypage/img_event.jpg" alt=""></a>
										@endif
									</figure>
									<div class="inner">
										<p class="event-name"><a href="/company/{{ $event->company_id }}/event/{{ $event->id }}">{{ mb_strimwidth($event->name, 0, 40, "...") }}</a></p>
										<div class="info-c">
											<div class="corp-name">
												<figure>
													<a href="/company/{{ $event->company_id }}/event/{{ $event->id }}"><img src="{{ $event->logo_file }}" alt=""></a>
												</figure>
												<p><a href="/company/{{ $event->company_id }}/event/{{ $event->id }}">{{ mb_strimwidth($event->company_name, 0, 40, "...") }}</a></p>
											</div>
											<div class="time">
												<a href="/company/{{ $event->company_id }}/event/{{ $event->id }}"><span class="cate">@if ($event->online_flag == '1')オンライン@else @if (empty($event->place))オフライン @else{{ $event->place }} @endif @endif</span></a>
												<a href="/company/{{ $event->company_id }}/event/{{ $event->id }}"><span class="date">{{ str_replace('-','/', substr($event->event_date, 0 ,10)) . '/' . $event->start_hour . ':' . $event->start_min . '〜' . $event->end_hour . ':' . $event->end_min }}</span></a>
											</div>
										</div>
										<p class="txt">
											<a href="/company/{{ $event->company_id }}/event/{{ $event->id }}">{{ mb_strimwidth($event->intro, 0, 180, "...") }}</a>
										</p>
									</div>
									</a>
								</li>
							@endforeach
						</ul>
					</div>
				@else
					設定なし
				@endif

				<div class="pager">
					<ul class="page">
						{{ $eventList->links('pagination.user') }}
					</ul>
				</div>

			</div><!-- con-wrap -->
		</div><!-- ttl -->
	</div><!-- inner -->
</main>


<script src="{{ asset('js/setting.js') }}"></script>

@endsection
