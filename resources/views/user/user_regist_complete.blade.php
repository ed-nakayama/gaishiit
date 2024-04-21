@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('register_complete') }}
@endsection


@section('addheader')
    <title>基本情報（登録完了）| {{ config('app.title') }}</title>
	<meta name="description" content="基本情報（登録完了）| {{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="基本情報（登録完了）｜{{ config('app.title') }}" />
	<meta property="og:description" content="基本情報（登録完了）｜{{ config('app.description') }}" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

    <link href="{{ asset('css/career0.css') }}" rel="stylesheet">
@endsection


@section('content')


<main class="pane-main">

	<div class="inner">
		<div class="ttl">
			<h2>基本情報（登録完了）</h2>
		</div>

		<div class="con-wrap">
			基本情報の登録が完了しました。
		</div>
	</div>
</main>

<script src="{{ asset('js/career.js') }}"></script>

@endsection
