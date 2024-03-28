@extends('layouts.user.auth')


@section('addheader')
	<title>利用規約｜{{ config('app.name', 'Laravel') }}</title>
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
