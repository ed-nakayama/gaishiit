@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('comp_job_list' ,$comp) }}
@endsection


@section('addheader')
	<title>{{ $comp->name }}の求人一覧｜外資IT企業のクチコミ・評価・求人なら外資IT.com</title>
	<meta name="description" content="">
    <link href="{{ asset('css/job.css') }}" rel="stylesheet">
@endsection


@section('content')


@if (Auth::guard('user')->check())
@include('user.user_activity')
@endif


	<main class="pane-main">
		<div class="inner">
			<div class="ttl">
				<h1>{{ $comp->name }}の求人一覧</h1>
			</div>

			<div class="con-wrap">

@if (empty($jobList[0]))
				<div class="item info job">
					<center>該当なし</center>
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

{{-- 回答者別口コミの一覧 $eval --}}
	@include ('user/partials/eval_list')
{{-- END 回答者別口コミの一覧 --}}

{{-- クチコミ数ランキング --}}
	@include ('user/partials/eval_ranking_fix')
{{-- END クチコミ数ランキング --}}

{{-- 3種 求人検索 --}}
	@include ('user/partials/job_search_3type')
{{-- END 3種 求人検索ボタン --}}
                            
			</div><!-- con-wrap -->
		</div><!-- inner -->
	</main>


<script src="{{ asset('js/job.js') }}"></script>

@endsection
