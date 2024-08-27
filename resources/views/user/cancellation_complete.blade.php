@extends('layouts.user.auth')


@section('addheader')
	<title>退会のお手続き完了｜{{ config('app.title') }}</title>
	<meta name="description" content="クチコミのある外資IT・外資コンサル企業の一覧ページです。転職に役立つ社員クチコミを集め、スコアを集計して提供しております。｜{{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="退会のお手続き完了｜{{ config('app.title') }}" />
	<meta property="og:description" content="クチコミのある外資IT・外資コンサル企業の一覧ページです。転職に役立つ社員クチコミを集め、スコアを集計して提供しております。｜{{ config('app.description') }}" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

	<link href="{{ asset('/css/add_new.css') }}" rel="stylesheet">
@endsection


@section('content')

<main class="pane-main">
	<div class="inner">
		<div class="ttl">
		</div>

		<div class="con-wrap">
			<div class="maypage-main">
				<div class="maypage-main__title">
					<h2>退会が完了しました</h2>
				</div>
				<div class="maypage-main__content">
					<div class="maypage-main__box">
						<p>ご利用ありがとうございました。<br>
						またのご利用をお待ちしております。
						</p>
					</div>
				</div>
			</div>
		</div><!-- con-wrap -->
	</div><!-- inner -->
</main> <!-- pane-main -->


@endsection
