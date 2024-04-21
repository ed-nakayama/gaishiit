@extends('layouts.user.auth')

@section('breadcrumbs')
	@if ($param_count == 1)
		{{ Breadcrumbs::render('job_list_cat',$param1_name) }}
	@elseif  ($param_count == 2)
		{{ Breadcrumbs::render('job_list_cat2', $param1_name, $param2_name) }}
	@elseif  ($param_count == 3)
		{{ Breadcrumbs::render('job_list_cat3', $param1_name, $param2_name, $param3_name) }}
	@else
		{{ Breadcrumbs::render('job_list') }}
	@endif
@endsection


@section('addheader')
	@if (empty($jobList[0]))
		<meta name="robots" content="noindex"/>
	@else
		@if (empty($noindex))
			<meta name="robots" content="index"/>
		@else
			<meta name="robots" content="noindex"/>
		@endif
	@endif

	@if ($param_count == 1)
		<title>{{ $param1_name }}の求人一覧｜{{ config('app.title') }}</title>
		<meta name="description" content="{{ $param1_name }}の求人情報一覧ページです。外資IT.comは、{{ $param1_name }}の外資系IT企業への転職希望者に役立つ口コミを掲載した求人サイトです。興味のある企業の担当者とは直接コミュニケーションも可能です。">

		<meta property="og:title" content=">{{ $param1_name }}の求人一覧｜{{ config('app.title') }}" />
		<meta property="og:description" content="{{ $param1_name }}の求人情報一覧ページです。外資IT.comは、{{ $param1_name }}の外資系IT企業への転職希望者に役立つ口コミを掲載した求人サイトです。興味のある企業の担当者とは直接コミュニケーションも可能です。" />

	@elseif  ($param_count == 2)
		<title>{{ $param1_name }}の{{ $param2_name }}の求人一覧｜{{ config('app.title') }}</title>
		<meta name="description" content="{{ $param1_name }}の{{ $param2_name }}の求人情報一覧ページです。外資IT.comは、{{ $param1_name }}の{{ $param2_name }}の外資系IT企業への転職希望者に役立つ口コミを掲載した求人サイトです。興味のある企業の担当者とは直接コミュニケーションも可能です。">

		<meta property="og:title" content=">{{ $param1_name }}の{{ $param2_name }}の求人一覧｜{{ config('app.title') }}" />
		<meta property="og:description" content="{{ $param1_name }}の{{ $param2_name }}の求人情報一覧ページです。外資IT.comは、{{ $param1_name }}の{{ $param2_name }}の外資系IT企業への転職希望者に役立つ口コミを掲載した求人サイトです。興味のある企業の担当者とは直接コミュニケーションも可能です。" />

	@elseif  ($param_count == 3)
		<title>{{ $param1_name }}の{{ $param2_name }}の{{ $param3_name }}の求人一覧｜{{ config('app.title') }}</title>
		<meta name="description" content="{{ $param1_name }}の{{ $param2_name }}の{{ $param3_name }}の求人情報一覧ページです。外資IT.comは、{{ $param1_name }}の{{ $param2_name }}の{{ $param3_name }}の外資系IT企業への転職希望者に役立つ口コミを掲載した求人サイトです。興味のある企業の担当者とは直接コミュニケーションも可能です。">

		<meta property="og:title" content=">{{ $param1_name }}の{{ $param2_name }}の{{ $param3_name }}の求人一覧｜{{ config('app.title') }}" />
		<meta property="og:description" content="{{ $param1_name }}の{{ $param2_name }}の{{ $param3_name }}の求人情報一覧ページです。外資IT.comは、{{ $param1_name }}の{{ $param2_name }}の{{ $param3_name }}の外資系IT企業への転職希望者に役立つ口コミを掲載した求人サイトです。興味のある企業の担当者とは直接コミュニケーションも可能です。" />

	@else
		<title>求人一覧｜{{ config('app.title') }}</title>
		<meta name="description" content="外資IT.comに掲載中のすべての求人情報一覧ページです。絞り込み条件としてエリアや年収や企業名だけではなく、細かな職種やこだわり条件もご用意しております。｜{{ config('app.description') }}">

		<meta property="og:title" content=">求人一覧｜{{ config('app.title') }}" />
		<meta property="og:description" content="外資IT.comに掲載中のすべての求人情報一覧ページです。絞り込み条件としてエリアや年収や企業名だけではなく、細かな職種やこだわり条件もご用意しております。｜{{ config('app.description') }}" />
	@endif

	<meta property="og:type" content="article" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

	<link href="{{ asset('css/job.css') }}" rel="stylesheet">
@endsection


@section('content')


@if (Auth::guard('user')->check())
@include('user.user_activity')
@endif


	<main class="pane-main">
		<div class="inner">
			<div class="ttl">
				@if ($param_count == 1)
					<h1>{{ $param1_name }}の求人一覧</h1>
				@elseif  ($param_count == 2)
					<h1>{{ $param1_name }}の{{ $param2_name }}の求人一覧</h1>
				@elseif  ($param_count == 3)
					<h1>{{ $param1_name }}の{{ $param2_name }}の{{ $param3_name }}の求人一覧</h1>
				@else
					<h1>求人一覧</h1>
				@endif
			</div>

			<div class="con-wrap">

@if (empty($jobList[0]))
				<div class="item info job">
					<center>該当なし</center>
				</div>
@else
				<div class="item info job">
					{{-- ジョブフォーマット $jobList --}}
						@include ('user/partials/job_format_loop')
					{{-- END ジョブフォーマット --}}
				</div>
				
				<div class="pager">
					<ul class="page">
						{{ $jobList->links('pagination.user') }}
					</ul>
				</div>
@endif

{{-- 回答者別口コミの一覧 $eval --}}
	@include ('user/partials/eval_list')
{{-- END 回答者別口コミの一覧 --}}

{{-- クチコミ数ランキング --}}
	@include ('user/partials/eval_ranking_fix')
{{-- END クチコミ数ランキング --}}

@if ( (!empty($jobList[0])) && (!empty($param1)) )
	{{-- 3種 求人検索 --}}
		@include ('user/partials/job_search_variable')
	{{-- END 3種 求人検索ボタン --}}
@endif
                           
			</div><!-- con-wrap -->
		</div><!-- inner -->
	</main>


<script src="{{ asset('js/job.js') }}"></script>

@endsection
