@extends('layouts.user.auth')


@section('addheader')
	<title>お気に入り求人｜{{ config('app.title') }}</title>
	<meta name="description" content="お気に入り求人｜{{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="お気に入り求人｜{{ config('app.title') }}" />
	<meta property="og:description" content="お気に入り求人｜{{ config('app.description') }}" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

    <link href="{{ asset('css/job.css') }}" rel="stylesheet">
@endsection


@section('content')


@include('user.user_activity')


<main class="pane-main">
	<div class="inner">
		<div class="ttl">
			<h1>お気に入り求人</h1>
		</div>

		<div class="con-wrap">
			@if (empty($jobList[0]))
				<div class="item info job">
					<center>設定なし</center>
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


		</div><!-- con-wrap -->
	</div><!-- inner -->
</main>


<script src="{{ asset('js/job.js') }}"></script>

@endsection
