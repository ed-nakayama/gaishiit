@extends('layouts.user.auth')


@section('addheader')
	<title>パスワード変更｜{{ config('app.title') }}</title>
	<meta name="description" content="パスワード変更｜{{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="パスワード変更｜{{ config('app.title') }}" />
	<meta property="og:description" content="パスワード変更｜{{ config('app.description') }}" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

    <link href="{{ asset('css/setting.css') }}" rel="stylesheet">
@endsection


@section('content')


@include('user.user_activity')

<main class="pane-main">
	<div class="inner">
		<div class="ttl">
			<h2>パスワード変更</h2>
		</div>
		<div class="con-wrap">
			<div class="item info">
				<div class="item-inner">
					{{-- フォーム --}}
					{{ html()->form('POST')->route('user.password.update')->id('passform')->attribute('name', 'passform')->open() }}
					<div class="setting-list">

						<div class="form-inner contact" style="text-align: center;">
							<div class="contact-list">
								<div class="input-wrap">現在のパスワード　　　　　</div>
								<div><input id="current" type="password"  name="current-password" required autofocus></div>
								<div class="input-wrap">新しいパスワード　　　　　</div>
								<div><input id="password" type="password" class="form-control" name="new-password" required></div>
								<div class="input-wrap">新しいパスワード（確認用）</div>
								<div><input id="confirm" type="password" class="form-control" name="new-password_confirmation" required></div>
							</div>
						</div>

						<div class="btn-wrap">
							{{-- エラーメッセージ --}}
							@if(count($errors) > 0)
								<div class="alert alert-danger" style="color:#ff0000;">
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</div>
				 			@endif

							{{-- 更新成功メッセージ --}}
							@if (session('update_password_success'))
								<div class="alert alert-success" style="color:#0000ff;">
									{{session('update_password_success')}}
								</div>
							@endif
							<button type="submit">パスワード変更</button>
						</div> <!-- btn-wrap -->

					</div> <!-- setting-list -->
					{{ html()->form()->close() }}
				</div> <!-- item-inner -->
			</div> <!-- item setting -->
		</div> <!-- con-wrap -->
	</div> <!-- inner -->
</main>

@endsection