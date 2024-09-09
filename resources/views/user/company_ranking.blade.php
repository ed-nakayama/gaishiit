@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('comp_ranking') }}
@endsection


@section('addheader')
	<title>企業の評判・クチコミランキング｜{{ config('app.title') }}</title>
	<meta name="description" content="外資IT・外資コンサル企業のクチコミ総合評価ランキングです。転職に役立つ社員クチコミを集め、スコアを集計して提供しております。｜{{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="企業の評判・クチコミランキング｜{{ config('app.title') }}" />
	<meta property="og:description" content="外資IT・外資コンサル企業のクチコミ総合評価ランキングです。転職に役立つ社員クチコミを集め、スコアを集計して提供しております。｜{{ config('app.description') }}" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

    <link href="{{ asset('css/department.css') }}" rel="stylesheet">

	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.js"></script>
    <link href="{{ asset('css/chart.css') }}" rel="stylesheet">
@endsection


@section('content')


@if (Auth::guard('user')->check())
	@include('user.user_activity')
@endif

<main class="pane-main">
	<div class="inner">

		<div class="ttl">
			<h1>外資IT・外資コンサル企業クチコミ評価ランキング</h1>
		</div>

		<div class="con-wrap">
			<div class="form-wrap">

			<ol class="ranking-list">
				@foreach ($rankingList as $ranking)
					@if ($loop->iteration <= 3)
						<li class="ranking-list__item -rank{{ $loop->iteration }} company-item">
					@else
						<li class="ranking-list__item company-item">
					@endif
					<figure class="company-item__image">
						@if(!empty($ranking->logo_file))
							<img src="{{ $ranking->logo_file }}" alt="">
						@endif
					</figure>
					<div class="company-item__content">
						<p class="company-item__name"><a href="/company/{{ $ranking->company_id }}">{{ $ranking->company_name }}</a></p>
							<dl class="company-item__reviews">
								<dt>総合評価</dt>
								<dd>
									<span>{{ number_format($ranking->total_point, 2) }}</span>
									<span class="star5_rating" style="--rate:  {{ $ranking->total_rate . '%' }};"></span>
								</dd>
								<dt>クチコミ件数</dt>
								<dd>{{ number_format($ranking->answer_count) }} 件</dd>
							</dl>
						<p class="company-item__button"><a href="/company/{{ $ranking->company_id }}">詳細を見る</a></p>
					</div>
                  </li>
				@endforeach
			</ol>

		<div class="pager">
			{{ $rankingList->appends(request()->query())->links('pagination.user') }}
		</div>

		<br>

		{{-- ピックアップ求人 --}}
			@include ('user/partials/job_pickup')
		{{-- END ピックアップ求人 --}}

		{{-- 3種 求人検索 --}}
			@include ('user/partials/job_search_3type')
		{{-- END 3種 求人検索ボタン --}}

	</div> {{-- inner --}}
</main>

@endsection
