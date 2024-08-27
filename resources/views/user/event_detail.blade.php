@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('event_detail' ,$comp ,$event) }}
@endsection


@section('addheader')
	<title>{{ $event->name }}-{{ $comp->name }}｜{{ config('app.title') }}</title>
	<meta name="description" content="{{ $event->name }}-{{ $comp->name }}｜{{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="{{ $event->name }}-{{ $comp->name }}｜{{ config('app.title') }}" />
	<meta property="og:description" content="{{ $event->name }}-{{ $comp->name }}｜{{ config('app.description') }}" />
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
				<h1>{{ $comp->name }}のイベント詳細</h1>
			</div>

{{-- 簡易的な企業の紹介情報 --}}
			<div class="company-details">
				<div class="company-item">
					<figure class="company-item__image">
						@if(!empty($comp->logo_file))
							<img src="{{ $comp->logo_file }}" alt="">
						@endif
					</figure>
					<div class="company-item__content">
						<p class="company-item__name">
							{{ $comp->name }}
						</p>
							<dl class="company-item__reviews">
							<dt>総合評価</dt>
							<dd>
								<span>{{ number_format($comp->total_point, 2) }}</span>
								<span class="star5_rating" style="--rate:  {{ $comp->total_rate . '%' }};"></span>
							</dd>
							<dt>クチコミ件数</dt>
							<dd>{{ number_format($comp->answer_count) }} 件</dd>
						</dl>
					</div>
				</div>

{{-- 簡易的な企業の紹介情報 --}}

				<div class="con-wrap">
					<div class="item-inner">

						<div class="ttl">
							<div class="txt">
								<p class="name">
									{{ $event->name }}
								</p>
								<dl>
									<dt><span class="cate">@if ($event->online_flag == '1')オンライン@else @if (empty($event->place))オフライン @else{{ $event->place }} @endif @endif</span>{{ str_replace('-','/', substr($event->event_date, 0 ,10)) . '/' . $event->start_hour . ':' . $event->start_min . '〜' . $event->end_hour . ':' . $event->end_min }}</dt>
								</dl>
							</div>
						</div>
                                
						<div class="item-info">
							<p>{!! nl2br(e($event->intro)) !!}</p>
							@if (\Carbon\Carbon::now()->lt($event->deadline_date) )
								@if (empty($event->deadline_date) )
									<div class="button-flex">
										@if (Auth::guard('user')->check())
											<a href="javascript:intform.submit()">イベントを申し込む</a>
										@else
											<a class="openModal button-modal" href="#modalLogin">イベントを申し込む</a>
										@endif
									</div>
								@else
									@if (\Carbon\Carbon::now()->lt($event->deadline_date) )
										<div class="button-flex">
											@if (Auth::guard('user')->check())
												<a href="javascript:intform.submit()">イベントを申し込む</a>
											@else
												<a class="openModal button-modal" href="#modalLogin">イベントを申し込む</a>
											@endif
										</div>
									@endif
								@endif
							@else
								<div class="button-flex">
									<p class="name" style="color:red;">締め切られました</p>
								</div>
							@endif
						</div>
					</div><!-- item-inner -->

			{{ html()->form('POST', '/interview/request')->attribute('name', 'intform')->open() }}
			{{ html()->hidden('comp_id', $comp->id) }}
			{{ html()->hidden('event_id', $event->id) }}
			{{ html()->hidden("int_type", '2') }}
			{{ html()->form()->close() }}

				</div><!-- con-wrap -->
			</div><!-- company-details -->

{{-- 求人一覧 --}}
	@include ('user/partials/job_list_comp_new')
{{-- END 求人一覧 --}}

	<div class="company-details">
{{--  チャート --}}
	@include ('user/partials/eval_chart')
{{--  END チャート --}}
	</div><!-- company-details -->
	</div>

{{-- 部門一覧 --}}
	@include ('user/partials/unit_list')
{{-- END 部門一覧 --}}

			<div class="button-flex">
				@if (Auth::guard('user')->check())
					<a href="/eval/regist?comp_id={{ $comp->id }}" >企業の評価をする</a>
				@else
					<a class="openModal button-modal" href="#modalLogin">企業の評価をする</a>
				@endif
			</div>

{{-- クチコミ数ランキング --}}
	@include ('user/partials/eval_ranking_fix')
{{-- END クチコミ数ランキング --}}
<br>
{{-- 3種 求人検索 --}}
	@include ('user/partials/job_search_3type')
{{-- END 3種 求人検索ボタン --}}

</div><!--inner -->
</main>

{{-- ログインモーダル  --}}
	@include('user/partials/login_modal')
{{-- END ログインモーダル  --}}


@endsection
