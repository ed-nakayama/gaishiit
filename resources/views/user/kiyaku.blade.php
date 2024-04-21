@extends('layouts.user.auth')


@section('addheader')
	<title>利用規約｜{{ config('app.title') }}</title>
	<meta name="description" content="利用規約｜{{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="利用規約｜{{ config('app.title') }}" />
	<meta property="og:description" content="利用規約｜{{ config('app.description') }}" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

    <link href="{{ asset('css/privacy.css') }}" rel="stylesheet">
@endsection


@section('content')


@auth
@include('user.user_activity')
@endauth

<main class="pane-main">

	<div class="inner">
		<div class="ttl">
			<h1>利用規約（工事中）</h1>
		</div>
		<div class="con-wrap">

			<div class="item corp">
				<div class="item-inner">


				</div>
			</div>
	
		</div>
	</div>
</main>

@endsection
