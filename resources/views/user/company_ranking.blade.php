@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('comp_ranking') }}
@endsection


@section('addheader')
	<title>企業の評判・クチコミランキング｜外資IT企業のクチコミ評価・求人なら外資IT.com</title>
	<meta name="description" content="外資IT企業のクチコミ総合評価ランキングです。転職に役立つ社員クチコミを集め、スコアを集計して提供しております。｜外資IT.comは外資系IT企業に特化したクチコミ・求人サイトです。興味のある企業の担当者とは直接コミュニケーションも可能です。">
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
			<h1>外資IT企業クチコミ評価ランキング</h1>
		</div>

		<div class="con-wrap">
			<h2>クチコミ数ランキング</h2>
			<div class="form-wrap">

				@foreach ($rankingList as $ranking)
					{{-- ランキングフォーマット $ranking --}}
						@include ('user/partials/ranking_format')
					{{-- END ランキングフォーマット --}}
				@endforeach
		<div class="pager">
			{{ $rankingList->appends(request()->query())->links('pagination.user') }}
		</div>

		<br>

		{{-- ピックアップ求人 --}}
			@include ('user/partials/job_pickup')
		{{-- END ピックアップ求人 --}}

		{{-- 求人検索ボタン --}}
			@include ('user/partials/job_search_button')
		{{-- END 求人検索ボタン --}}

		{{-- 3種 求人検索 --}}
			@include ('user/partials/job_search_3type')
		{{-- END 3種 求人検索ボタン --}}

	</div> {{-- inner --}}
</main>

@endsection
