@extends('layouts.user.auth')


@section('addheader')
	<title>会社概要｜{{ config('app.name', 'Laravel') }}</title>
	<link href="{{ asset('css/corporate.css') }}" rel="stylesheet">
@endsection


@section('content')


@auth
@include('user.user_activity')
@endauth

<main class="pane-main">

	<div class="inner">
		<div class="ttl">
			<h1>会社概要</h1>
		</div>

		<div class="con-wrap">

			<div class="item corp">
				<div class="item-inner">

					<div class="corp-list">
							<div class="item-block">
								<p class="ttl">会社名</p>
								<div class="corp-wrap">
								<span>株式会社ARK</span>
								</div>
							</div>
					</div>

					<div class="item-block">
						<p class="ttl">設立</p>
						<div class="corp-wrap">
							<span>2007年10月01日</span>
						</div>
					</div>

					<div class="item-block">
						<p class="ttl">代表取締役社長</p>
						<div class="corp-wrap">
							<span>荒木 大介</span>
						</div>
					</div>

					<div class="item-block">
						<p class="ttl">厚生労働省　許認可番号</p>
						<div class="corp-wrap">
							<span>13-ユ-303041</span>
						</div>
					</div>

					<div class="item-block">
						<p class="ttl">電話番号（代表）</p>
						<div class="corp-wrap">
							<span>03-6902-0360</span>
						</div>
					</div>

					<div class="item-block">
						<p class="ttl">メールアドレス</p>
						<div class="corp-wrap">
							<span>info@d-ark.co.jp</span>
						</div>
					</div>

					<div class="item-block">
						<p class="ttl">住所</p>
						<div class="corp-wrap">
							<span>〒102-0093<br>東京都千代田区平河町１丁目７-２１平河町昭和ビル 1階</span>
						</div>
					</div>
	
				</div>
			</div>

		</div>
	</div>
</main>

@endsection
