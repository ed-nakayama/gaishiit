@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('unit_detail' ,$comp ,$unit) }}
@endsection


@section('addheader')
	<title>{{ $comp->name }}]の{{ $unit->name }}紹介｜{{ config('app.title') }}</title>
	<meta name="description" content="{{ $comp->name }}の {{ $unit->name }}のご紹介ページです。部署がもつ特色や業務内容、業務範囲を確認していただくことができます。｜{{ config('app.description') }}">
	<meta name="description" content="マイページ｜{{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="{{ $comp->name }}]の{{ $unit->name }}紹介｜{{ config('app.title') }}" />
	<meta property="og:description" content="{{ $comp->name }}の {{ $unit->name }}のご紹介ページです。部署がもつ特色や業務内容、業務範囲を確認していただくことができます。｜{{ config('app.description') }}" />
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
				<h1>{{ $comp->name }}の部署紹介</h1>
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
							<a href="/company/{{ $comp->id }}">{{ $comp->name }}</a>
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
						<p class="company-item__button"><a href="/company/{{ $comp->id }}">詳細を見る</a></p>
					</div>
				</div>

				<div class="company-item">
					<div class="company-item__content">
						<p class="company-item__name">{{ $unit->name }}</p>
					</div>
				</div>

				<div class="item-info">
						<p>{!! nl2br(e($unit->intro)) !!}</p>

					<div  style="display:flex;">
						@if ( $comp->casual_flag == '1')
							<div class="button-flex">
								@if (Auth::guard('user')->check())
									<a href="javascript:intform.submit()">カジュアル面談を依頼</a>
								@else
									<a class="openModal button-modal" href="#modalLogin">カジュアル面談を依頼</a>
								@endif
							</div>
						@endif

					</div>

				</div><!-- item-info -->

@if (!empty($interview))
				<p>以前にこの部署へのカジュアル面談の依頼をしたことがあります</p>
				<table style="font-size: 1.4rem;">
					<tr>
						<th>依頼日</th><th>依頼内容</th>
					</tr>
					<tr>
						<td>{{ $interview->created_at->format('Y/m/d/H:i') }}</td>
						<td>　　@if ($interview->interview_type == '0')カジュアル面談@endif</td>
					</tr>
				</table>
@endisset

			</div><!-- company-details -->

{{-- 簡易的な企業の紹介情報 --}}


@if (!empty($eventList[0]) )
				<div class="event">
					<div class="inner">                                
						<h2>イベント</h2>
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
										<p class="dept-name">{{ $event->unit_name }}</p>
										<p class="ttl">
											{{ $event->name }}
										</p>
										<dl>
											<dt>@if ($event->online_flag == '1')オンライン@else @if (empty($event->place))オフライン @else{{ $event->place }} @endif @endif</dt>
											<dd>{{ str_replace('-','/', substr($event->event_date, 0 ,10)) . '/' . $event->start_hour . ':' . $event->start_min . '〜' . $event->end_hour . ':' . $event->end_min }}</dd>
										</dl>
										<p class="txt">
											{{ mb_strimwidth($event->intro, 0, 180, "...") }}
										</p>
									</div>
								</li>
							@endforeach
						</ul>
						@if ($eventCnt > $more_event)
							{{ html()->form('GET', "/company/{$unit->cmpany_id}/unit/{$unit->id}")->id('moreeventform')->attribute('name', 'moreeventform')->open() }}
							{{ html()->hidden("more_event", $more_event) }}
							<div class="button-wrap">
								<button type="submit">もっと見る</button>
							</div>
							{{ html()->form()->close() }}
						@endif
					</div>
				</div>
@endif

{{-- 求人一覧 --}}
@if (!empty($jobList[0]) )

	<div class="job">
		<h2>{{ $unit->name }}の求人一覧</h2>

		<div class="job-opening">
			<ul class="job-opening-list">
				@foreach ($jobList as $job)
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
				@endforeach
			</ul>
			<p class="detail-link-button"><a href="/company/{{ $comp->id }}/joblist">求人一覧を見る</a></p>
		</div><!-- job-opening -->
	</div>

@endif

{{-- END 求人一覧 --}}

{{-- 部門 --}}
@isset($unitList[0])
	<div class="eval">
		<h2>部門</h2>

		<div class="job-opening">
			<ul class="job-opening-list">
				<li>
					<div class="inner">
						<ul>
							@foreach ($unitList as $unit)
								<li>
									<a href="/company/{{ $comp->id }}/unit/{{ $unit->id }}" style="font-size:16px;color:#4AA5CE;">{{ $unit->name }}</a>
								</li>
								 @endforeach
						</ul>
					</div><!-- inner -->
				</li>
			</ul>
		</div><!-- job-opening -->
	</div><!-- eval -->

@endisset
{{-- END 部門 --}}

	<div class="company-details">
{{--  チャート --}}
	@include ('user/partials/eval_chart')
{{--  END チャート --}}
	</div><!-- company-details -->
	</div>



{{-- その他部署求人一覧 --}}
@if (!empty($elseJobList[0]) )

	<div class="job">
		<h2>{{ $comp->name }}のその他部署求人一覧</h2>

		<div class="job-opening">
			<ul class="job-opening-list">
				@foreach ($elseJobList as $job)
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
				@endforeach
			</ul>
			<p class="detail-link-button"><a href="/company/{{ $comp->id }}/joblist">求人一覧を見る</a></p>
		</div><!-- job-opening -->
	</div>

@endif
{{-- END その他部署求人一覧 --}}


				{{ html()->form('POST', '/interview/request')->attribute('name', 'intform')->open() }}
				{{ html()->hidden('comp_id', $comp->id) }}
				{{ html()->hidden('unit_id', $unit->id) }}
				{{ html()->hidden('int_type', '0') }}
				{{ html()->hidden('int_kind', '1') }}
				{{ html()->form()->close() }}

{{-- クチコミ数ランキング --}}
	@include ('user/partials/eval_ranking_fix')
{{-- END クチコミ数ランキング --}}
<br>
{{-- 3種 求人検索 --}}
	@include ('user/partials/job_search_3type')
{{-- END 3種 求人検索ボタン --}}

			</div>
		</div>
	</main>

{{-- ログインモーダル  --}}
	@include('user/partials/login_modal')
{{-- END ログインモーダル  --}}

@endsection
