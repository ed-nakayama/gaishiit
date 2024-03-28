@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('blog') }}
@endsection

@section('addheader')
	<title>外資ITお役立ちブログ｜外資IT企業のクチコミ・評価・求人なら外資IT.com</title>
	<meta name="description" content="外資IT企業への転職に関するお役立ち情報を発信しております。外資IT企業への転職なら外資IT.com｜外資IT.comは外資系IT企業に特化した口コミ・求人サイトです。採用が決まるまで完全無料、興味のある企業の担当者とは直接コミュニケーションも可能です。">
    <link href="{{ asset('css/privacy.css') }}" rel="stylesheet">
@endsection


@section('content')


@auth
@include('user.user_activity')
@endauth

<main class="pane-main">

	<div class="inner">
		<div class="ttl">
			<h1>ブログ（工事中）</h1>
		</div>
	</div>
</main>

@endsection
