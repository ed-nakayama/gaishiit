@extends('layouts.user.auth')


@section('addheader')
	<title>お問い合わせ｜外資IT企業のクチコミ評価・求人なら外資IT.com</title>
    <link href="{{ asset('css/department.css') }}" rel="stylesheet">-

	<meta property="og:type" content="article" />
	<meta property="og:title" content="お問い合わせ｜｜外資IT企業のクチコミ評価・求人なら外資IT.com" />
	<meta property="og:description" content="お問い合わせ｜外資IT.comは外資系IT企業に特化した口コミ・求人サイトです。興味のある企業の担当者とは直接コミュニケーションも可能です。" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

@endsection


@section('content')


<style>

.frame-name {
	border-radius: 8px;
	padding: 10px 26px;
	border: 1px solid #B1B1B1;
	margin-bottom:20px;
}

.frame-def {
	width:80%;
	border-radius: 8px;
	padding: 10px 26px;
	border: 1px solid #B1B1B1;
	margin-bottom:20px;
}

.frame-area {
	width:100%;
	border-radius: 8px;
	padding: 10px 26px;
	border: 1px solid #B1B1B1;
}

</style>

@if (Auth::guard('user')->check())
@include('user.user_activity')
@endif

<main class="pane-main">
	<div class="inner">
		<div class="ttl">
			<h1>お問い合わせ</h1>
		</div>

		<div class="con-wrap">

			<div class="item thumb">
				<div class="inner">

					<div class="ttl">
						<h2>　よくあるお問い合わせ</h2>
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
				</div><!-- item-inner -->
			</div><!-- item info -->

			<div class="item info">
				<div class="item-inner">

					{{ html()->form('POST', '/adminfaq/store')->attribute('name', 'qaform')->open() }}
					<div class="item-block">
						<div class="ttl"><h2>お問い合わせ内容</h2></div>
						<div class="form-inner contact">
@if (!Auth::guard('user')->check())
							<div class="contact-list">
								<p class="ttl">氏名 *</p>
								<div class="input-wrap">
									{{ html()->text('user_name')->class('frame-name') }}
								</div>
							</div>
							@error('user_name')
								<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
							@enderror

							<div class="contact-list">
								<p class="ttl">メールアドレス *</p>
								<div class="input-wrap">
									{{ html()->text('email')->class('frame-def') }}
								</div>
							</div>
							@error('email')
								<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
							@enderror
@endif

							<div class="contact-list">
								<p class="ttl">内容 *</p>
								<div class="input-wrap">
									<textarea name="content" id=""  class="frame-area" rows="6">{{  old('content') }}</textarea>
								</div>
								@error('content')
									<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
								@enderror
							</div>

						</div>
					</div>

					<div class="btn-wrap" style="text-align: center;">
						{{-- 更新成功メッセージ --}}
						@if (session('send_success'))
							<p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-blue-400 dark:text-blue-400" style="color: blue;">{{ session('send_success') }}</p>
						@endif
						<div class="button-flex">
							<a href="javascript:qaform.submit()">送信</a>
 						</div>
					</div>
					{{ html()->form()->close() }}

				</div><!-- item-inner -->
			</div><!-- item info -->

		</div><!-- "con-wrap -->
	</div><!--inner -->
</main>


@endsection
