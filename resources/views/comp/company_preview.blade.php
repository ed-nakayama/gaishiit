@extends('comp.user_extend')

@section('content')

<head>
	<title>プレビュー｜{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/department.css') }}" rel="stylesheet">
</head>

            <main class="pane-main">
                <div class="inner">
                	<div class="ttl">
                    	<h2>企業</h2>
                	</div>

                    <div class="con-wrap">

                        <div class="item thumb">
                            <div class="inner">
                                <figure class="corp_icon">
                                    <img src="{{ $comp->logo_file }}" alt="">
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

                        <div class="item info">
                            <div class="item-inner">
                                <div class="ttl">
                                    <!-- <a href="">
                                        <figure>
                                            <img src="{{ $comp->logo_file }}" alt="">
                                        </figure>
                                    </a> -->
                                    <div class="txt">
                                        <p class="name">
                                            <a>
                                                {{ $comp->name }}
                                            </a>
                                        </p>

                                    </div>
                                    <div class="button-flex">
                                        <a>よくあるお問合せ</a>
                                    </div>

                                </div>
                                
                                <div class="item-info">
                                    <p>{!! nl2b(e($comp->intro)) !!}</p>
									@if ( $comp->casual_flag == '1')
                                    <div class="button-flex">
                                        <a>カジュアル面談を依頼</a>
                                    </div>
									@endif
                                </div>

                            </div>
                        </div>

@if (!empty($eventList[0]) )
                        <div class="event">
                            <div class="inner">                                
                                <h2>イベント</h2>
                                <ul>
                                    @foreach ($eventList as $event)
                                    <li>
                                        <figure>
                                    		@if (!empty($event->image))
                                        		<a><img src="{{ $event->image }}" alt=""></a>
                                    		@else
                                        		<a><img src="/img/mypage/img_event.jpg" alt=""></a>
                                        	@endif
                                        </figure>
                                        <div class="inner">
                                            <p class="ttl">
                                                <a>{{ $event->name }}</a>
                                            </p>
                                            <a>
                                            	<dl>
                                                	<dt>@if ($event->online_flag == '1')オンライン@else @if (empty($event->place))オフライン @else{{ $event->place }} @endif @endif</dt>
                                                	<dd>{{ str_replace('-','/', substr($event->event_date, 0 ,10)) . '/' . $event->start_hour . ':' . $event->start_min . '〜' . $event->end_hour . ':' . $event->end_min }}</dd>
                                            	</dl>
                                            </a>
                                            <p class="txt">
                                                <a>{{ mb_strimwidth($event->intro, 0, 250, "...") }}</a>
                                            </p>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                                <div class="button-wrap">
                                    <button type="submit">もっと見る</button>
                                </div>
                            </div>
                        </div>
@endif

{{-- 部門 --}}
@if (!empty($unitList[0]) )
                        <div class="job"">
                            <div class="inner">
                                <h2>部門</h2>
                                <ul>
                                    @foreach ($unitList as $unit)
                                    <li>
	                                    <a>
                                        <p class="ttl">{{ $unit->name }}</p>
                                        </a>
                                        <p class="txt">
                                            <a>{!! nl2br(e($unit->intro)) !!}</a>
                                        </p>
										</a>
                                    </li>
                                    @endforeach
                                </ul>
                                @if ($unitCnt > $more_unit)
                                <div class="button-wrap">
                                    <button type="submit">もっと見る</button>
                                </div>
                                @endif
                            </div>
                        </div>
@endif

                    </div>
                </div>
            </main>

@endsection
