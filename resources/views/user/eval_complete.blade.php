@extends('layouts.user.auth')


@section('addheader')
	<title>企業評価 送信完了｜{{ config('app.title') }}</title>
	<meta name="description" content="企業評価 送信完了｜{{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="企業評価 送信完了｜{{ config('app.title') }}" />
	<meta property="og:description" content="企業評価 送信完了｜{{ config('app.description') }}" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

    <link href="{{ asset('css/eval.css') }}" rel="stylesheet">
@endsection


@section('content')


@include('user.user_activity')

<main class="pane-main">

	<div class="inner">
		<div class="ttl">
			<h2>企業評価 送信完了</h2>
		</div>

		<div class="con-wrap">

{{-- 評価企業選択 --}}
			<div class="item setting">
				<div class="item-inner">
					<div class="setting-list">
						<div class="item-block">
							<p class="ttl"></p>
							<div class="form-wrap">
							企業評価の登録が完了しました。審査完了まで数日かかります。<br>
							審査完了後、編集、削除することはできません。
							</div>
						</div>
					</div>
				</div>
			</div>
{{-- 評価企業選択 END --}}


		</div>
	</div>
</main>


<script src="{{ asset('js/career.js') }}"></script>

@endsection
