@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('comp_detail' ,$comp) }}
@endsection


@section('addheader')
	<title>{{ $comp->name }}のクチコミ評価・求人｜外資IT企業のクチコミ評価・求人なら外資IT.com</title>
	<meta name="description" content="{{ $comp->name }}の社員クチコミを掲載。給与・福利厚生・育成・法令遵守の意識・社員のモチベーション・ワークライフバランス・リモート勤務・定年という8つのテーマに分かれたクチコミをご覧いただけます。｜外資IT.comは外資系IT企業に特化したクチコミ・求人サイトです。興味のある企業の担当者とは直接コミュニケーションも可能です。">
    <link href="{{ asset('css/department.css') }}" rel="stylesheet">
@endsection


@section('content')


@if (Auth::guard('user')->check())
@include('user.user_activity')
@endif

	<main class="pane-main">
		<div class="inner">
			<div class="ttl">
				<h1>{{ $comp->name }}のクチコミ評価・求人</h1>
			</div>

			<div class="con-wrap">

				<div class="item info">
					<div class="item-inner">
						<div class="ttl">

							<table>
								<tr>
									<td style="width:10%;">
										<div class="job-corp-name">
											<figure>
												@if(!empty($comp->logo_file))
													<img src="{{ $comp->logo_file }}" alt="">
												@endif
											</figure>
										</div>
									</td>
									<td>
										<p class="expand-title">{{ $comp->name }}<p>
										<div style="text-align: center; display:flex; ">
											<p class="txt" style="font-size:16px;">
												<span class="star5_rating" style="--rate: {{ $comp->total_rate . '%' }};font-size:20px;"></span>
												　総合評価　<b>{{ number_format($comp->total_point, 2) }}</b>
											</p>
										</div>
									</td>
								</tr>
							</table>


							@if ($qa_count > 0)
								<div class="button-flex" style="margin-top:0px; margin-bottom:0px;">
									{{ html()->form('POST', '/compfaq')->id('faqform')->attribute('name', 'unitform')->open() }}
									{{ html()->hidden('company_id', $comp->id) }}
									<a href="javascript:faqform.submit()">よくあるお問合せ</a>
									{{ html()->form()->close() }}
								</div>
							@endif
						</div><!-- ttl -->

						<div class="item-info">
							<p>{!! nl2br($comp->intro) !!}</p>
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

@isset($interview)
						<p>以前にこの企業へのカジュアル面談の依頼をしたことがあります</p>
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
					</div><!-- item-inner -->
				</div><!-- item info -->
			</div><!-- con-wrap -->

{{--  チャート --}}
	@include ('user/partials/eval_chart')
{{--  END チャート --}}

{{-- カテゴリ別クチコミボタン --}}
	@include ('user/partials/eval_cat_button')
{{-- END カテゴリ別クチコミボタン --}}


{{-- 回答者別口コミの一覧 $eval --}}
	@include ('user/partials/eval_list')
{{-- END 回答者別口コミの一覧 --}}

{{-- 求人一覧 --}}
	@include ('user/partials/job_list_comp_new')
{{-- END 求人一覧 --}}


{{-- イベント --}}
@isset($eventList[0])
			<div class="event">
				<div class="inner">
					<h2>イベント</h2>
					<ul>
						@foreach ($eventList as $event)
							<li>
{{--
								<figure>
									@if (!empty($event->image))
										<img src="{{ $event->image }}" alt="">
									@else
										<img src="/img/mypage/img_event.jpg" alt="">
									@endif
								</figure>
--}}
								<div class="inner">
									<p class="ttl">
										{{ $event->name }}
									</p>
									<dl>
										<dt>@if ($event->online_flag == '1')<p>オンライン</p>@else @if (empty($event->place))<p>オフライン</p> @else{{ $event->place }} @endif @endif</dt>
										<dd>{{ str_replace('-','/', substr($event->event_date, 0 ,10)) . '/' . $event->start_hour . ':' . $event->start_min . '〜' . $event->end_hour . ':' . $event->end_min }}</dd>
									</dl>
									<p class="txt">
										{{ mb_strimwidth($event->intro, 0, 250, "...") }}
									</p>

									<div class="con-wrap">
										<div class="button-flex" style="width:200px;margin-top:10px; margin-bottom:5px;">
											<a href="/company/{{ $event->company_id }}/event/{{ $event->id }}">イベント詳細</a>
										</div>
									</div>
								</div>


							</li>
						@endforeach
					</ul>
					@if ($eventCnt > $more_event)
						<div class="button-wrap">
							<button type="submit">もっと見る</button>
						</div>
					@endif
				</div>
			</div>
<br>
@endisset

{{-- 部門一覧 --}}
	@include ('user/partials/unit_list')
{{-- END 部門一覧 --}}

			{{ html()->form('POST', '/interview/request')->attribute('name', 'intform')->open() }}
			{{ html()->hidden('comp_id', $comp->id) }}
			{{ html()->hidden('int_type', '0') }}
			{{ html()->hidden('int_kind', '0') }}
			{{ html()->form()->close() }}

			<div class="button-flex">
				@if ( $comp->casual_flag == '1')
					@if (Auth::guard('user')->check())
						<a href="javascript:intform.submit()">カジュアル面談を依頼</a>
					@else
						<a class="openModal button-modal" href="#modalLogin">カジュアル面談を依頼</a>
					@endif
				@endif
				@if (Auth::guard('user')->check())
					<a href="/eval/regist?comp_id={{ $comp->id }}" >企業の評価をする</a>
				@else
					<a class="openModal button-modal" href="#modalLogin">企業の評価をする</a>
				@endif
			</div>


{{-- クチコミ数ランキング --}}
	@include ('user/partials/eval_ranking_fix')
{{-- END クチコミ数ランキング --}}

{{-- ピックアップ求人 --}}
	@include ('user/partials/job_pickup')
{{-- END ピックアップ求人 --}}
<br>
{{-- 3種 求人検索 --}}
	@include ('user/partials/job_search_3type')
{{-- END 3種 求人検索ボタン --}}

		</div><!-- inner -->
	</main>

{{-- ログインモーダル  --}}
	@include('user/partials/login_modal')
{{-- END ログインモーダル  --}}


@endsection
