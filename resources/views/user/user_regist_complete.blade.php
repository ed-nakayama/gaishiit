@extends('layouts.user.auth')


@section('addheader')
    <title>送信完了| {{ config('app.title') }}</title>
	<meta name="description" content="送信完了| {{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="送信完了｜{{ config('app.title') }}" />
	<meta property="og:description" content="送信完了）｜{{ config('app.description') }}" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

    <link href="{{ asset('css/career0.css') }}" rel="stylesheet">
@endsection


@section('content')

<main class="pane-main">

	<div class="inner">
		<h1 style="text-align: center;">送信完了</h1>

		<div class="Stepnav">
			<ol>
				<li><p><label>STEP</label>01 情報のご入力</p></li>
				<li><p><label>STEP</label>02 内容のご確認</p></li>
				<li class="current"><p><label>STEP</label>03 送信完了</p></li>
			</ol>
		</div>



		<div class="con-wrap">
			<p style="text-align:center;">
			ご入力ありがとうございました。<br>
			<br>
			確認のためご入力いただいたメールアドレスにご入力いただいた内容を送信いたしましたので必ずご確認ください。<br>
			<br>
			※Gmailなどのフリーメールの場合、<br>
			送信したメールが迷惑メールフォルダに届いてしまうことがございますのでご注意ください。<br>
			また迷惑メールフォルダにも届いていない場合はメールアドレスに誤りがあった可能性がございます。<br>
			その場合は下記までご連絡ください。<br>
			</p>
		</div>
	</div>
</main>

<script src="{{ asset('js/career.js') }}"></script>

@endsection
