@extends('layouts.user.auth')


@section('addheader')
	<title>ただいま準備中｜{{ config('app.title') }}</title>
	<meta name="description" content="ただいま準備中｜{{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="ただいま準備中｜{{ config('app.title') }}" />
	<meta property="og:description" content="ただいま準備中｜{{ config('app.description') }}" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

    <link href="{{ asset('css/privacy.css') }}" rel="stylesheet">
@endsection


@section('content')


@if (Auth::guard('user')->check())
	include('user.user_activity')
@endif

            <main class="pane-main">
                
                <div class="inner">
                    <div class="ttl">
                        <h2>現在準備中</h2>
                    </div>
                    
                </div>
            </main>

@endsection
