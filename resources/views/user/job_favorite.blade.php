@extends('layouts.user.auth')


@section('addheader')
	<title>お気に入り求人｜{{ config('app.name', 'Laravel') }}</title>
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
					<ul>
						{{-- ジョブフォーマット $jobList --}}
							@include ('user/partials/job_format_loop')
						{{-- END ジョブフォーマット --}}
					</ul>
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
