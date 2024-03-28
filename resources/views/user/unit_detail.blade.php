@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('unit_detail' ,$comp ,$unit) }}
@endsection


@section('addheader')
	<title>{{ $comp->name }}]の{{ $unit->name }}紹介｜外資IT企業のクチコミ評価・求人なら外資IT.com</title>
	<meta name="description" content="{{ $comp->name }}の {{ $unit->name }}のご紹介ページです。部署がもつ特色や業務内容、業務範囲を確認していただくことができます。｜外資IT.comは外資系IT企業に特化したクチコミ・求人サイトです。採用が決まるまで完全無料、興味のある企業の担当者とは直接コミュニケーションも可能です。">
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
	@include ('user/partials/company_simple_intro')
	<br>
{{-- 簡易的な企業の紹介情報 --}}

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

						<div class="item-top">
							<p class="name">
								<a>
									{{ $comp->name }}
								 </a>
							</p>
						</div>
                            
						<div class="ttl">
{{--
							<a href="">
								<figure>
									<img src="{{ $comp->logo_file }}" alt="">
								</figure>
							</a>
--}}
							<div class="txt">
								<p class="name">
									<a>
										{{ $unit->name }}
									</a>
								</p>
							</div>
						</div>

						<div class="item-info">
							<p>{!! nl2br(e($unit->intro)) !!}</p>

							<div class="button-flex">
								@if ($unit->casual_flag == '1')
									<a href="javascript:intform.submit()">カジュアル面談を依頼</a>
								@endif
							</div>
						</div>
@if (!empty($interview))
						以前にこの部署へのカジュアル面談の依頼をしたことがあります
						<table style="font-size: 1.4rem;">
							<tr>
								<th>依頼日</th><th>依頼内容</th>
							</tr>
							<tr>
								<td>{{ $interview->created_at->format('Y/m/d/H:i') }}</td>
								<td>　　@if ($interview->interview_type == '0')カジュアル面談@endif</td>
							</tr>
						</table>
@endif
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
					<div class="inner">
						<h2>{{ $comp->name }}の{{ $unit->name }}求人一覧</h2>
						<ul>
							@foreach ($jobList as $job)
								{{-- ジョブフォーマット $job --}}
									@include ('user/partials/job_format')
								{{-- END ジョブフォーマット --}}
							@endforeach
						</ul>
					</div>
				</div>
@endif

{{-- END 求人一覧 --}}

{{-- 求人一覧ボタン $comp --}}
	@include ('user/partials/job_list_button')
{{-- END 求人一覧ボタン --}}

{{-- 部門 --}}
@isset($unitList[0])
			<div class="eval">
				<div class="inner">
					<h2>部門</h2>
					<ul>
						@foreach ($unitList as $unit)
						<li>
							<a href="/company/{{ $comp->id }}/unit/{{ $unit->id }}" style="font-size:16px;color:#4AA5CE;">{{ $unit->name }}</a>
						</li>
						 @endforeach
					</ul>
				</div>
			</div>

@endisset
{{-- END 部門 --}}

{{-- その他部署求人一覧 --}}
@if (!empty($elseJobList[0]) )

				<div class="job">
					<div class="inner">
						<h2>{{ $comp->name }}のその他部署求人一覧</h2>
						<ul>
							@foreach ($elseJobList as $job)
								{{-- ジョブフォーマット $job --}}
									@include ('user/partials/job_format')
								{{-- END ジョブフォーマット --}}
							@endforeach
						</ul>
					</div>
				</div>
@endif
{{-- END その他部署求人一覧 --}}


				{{ html()->form('POST', '/interview/request')->attribute('name', 'intform')->open() }}
				{{ html()->hidden('comp_id', $comp->id) }}
				{{ html()->hidden('unit_id', $unit->id) }}
				{{ html()->hidden('int_type', '0') }}
				{{ html()->hidden('int_kind', '1') }}
				{{ html()->form()->close() }}

{{--  チャート --}}
	@include ('user/partials/eval_chart')
{{--  END チャート --}}

			<div class="button-flex">
				@if (Auth::guard('user')->check())
					<a href="/eval/regist?comp_id={{ $comp->id }}" >企業の評価をする</a>
				@else
					<a class="openModal button-modal" href="#modalLogin">企業の評価をする</a>
				@endif
			</div>

{{-- カテゴリ別クチコミボタン --}}
	@include ('user/partials/eval_cat_button')
{{-- END カテゴリ別クチコミボタン --}}

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
