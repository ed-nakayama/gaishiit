@extends('layouts.user.auth')


@section('addheader')
	<title>よくあるお問合せ｜{{ config('app.title') }}</title>
	<meta name="description" content="よくあるお問合せ｜{{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="よくあるお問合せ｜{{ config('app.title') }}" />
	<meta property="og:description" content="よくあるお問合せ｜{{ config('app.description') }}" />
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
			<h1>よくあるお問合せ</h1>
		</div>

		<div class="con-wrap">

			<div class="item thumb">
				<div class="inner">

					<div class="ttl">
						<h2>　{{ $comp->name }}</h2>
					</div>

					<div class="ac-wrap">
						@foreach ($qa_list as $qa)
						<div class="ac-item">
							<p class="ac-header">
								Q. {{ $qa->question }}
							</p>
							<div class="ac-txt">
								<p class="ac-header">
									A. {!! nl2br(e($qa->answer)) !!}
								</p>
								<p>
									{!! nl2br(e($qa->exp)) !!}
								</p>
							</div>
						</div>
						@endforeach
					</div>
				</div><!-- item-inner　ーー>
			</div><!-- item -->
		</div><!--  con-wrap -->

	</div><!-- inner -->
</main>

@endsection
