@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('register_complete') }}
@endsection


@section('addheader')
    <title>外資IT企業の口コミ評価・求人なら外資IT.com</title>
	<meta name="description" content="外資IT.comは外資系IT企業に特化した口コミ・求人サイトです。興味のある企業の担当者とは直接コミュニケーションも可能です。">
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
