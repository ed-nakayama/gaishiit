<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ config('app.name', 'Laravel') }}</title>
	<link href="{{ asset('comp/assets/css/destyle.css') }}" rel="stylesheet">
	<link href="{{ asset('comp/assets/css/style.css') }}" rel="stylesheet">
	<link href="{{ asset('comp/assets/css/remodal.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('comp/assets/css/remodal-default-theme.css') }}" rel="stylesheet" type="text/css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>
<body class="bg-admin">
    
    <header class="header" style="position: fixed;width: 100%;z-index:1;">
		<div class="headInner">
 			<div class="headMain">
				<h1 class="logo"><a href="/comp/mypage"><img src="/images/logo_on_comp.png" width="150"></a></h1>
				<div class="containerMenu">
					<ul class="menu">
						<li><a href="/comp/edit">企業情報の管理</a></li>
						<li><a href="/comp/admin/unit">部門設定</a></li>
						<li><a href="/comp/member">責任者登録</a></li>
{{--					<li><a href="/comp/billing">請求管理</a></li>--}}
						<li><a href="/comp/claim/every">請求管理</a></li>
					</ul>
				</div><!-- /.containerMenu -->
			</div><!-- /.menu -->

			<div class="dropdown-menu userName" style="display: flex;">
				<ul class="usrNameMenu">
					<li><a href="javascript:void(0);" class="name" style="white-space:nowrap;">企業設定</a></li>
				</ul>　
				<ul class="usrNameMenu">
					<li>
						<a href="/comp/mypage" class="name" style="white-space:nowrap; color:white;  background:gray;">{{ Auth::user()->name }}</a>
					</li>
				</ul>
			</div><!-- /.userName -->

		</div><!-- /.headInner -->
		<div class="infoBar">
			<div class="infoBarInner">
				<div class="infoBarTitle">
					<h2>お知らせ</h2>
				</div><!-- /.infoBarTitle -->
				<div class="infoBarText">
					@if (!empty($information[0]))
						<p>{{ $information[0]->created_at->format('Y/m/d') }} {{ $information[0]->content }}</p>
					@endif
				</div><!-- /.infoBarText -->
@if(Auth::user()->ark_priv == '1')                
				<div class="infoBarText" style="text-align: right">
					－ {{ $member_act['comp_name'] }}でログイン中 －
				</div><!-- /.infoBarTitle -->
				<div class="infoBarTitle">
					<a href="{{ route('comp.logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><h2>ログアウト</h2></a>
				</div><!-- /.infoBarTitle -->
@endif
			</div><!-- /.infoBarInner -->
		</div><!-- /.infoBar -->

		<div class="msgBar">
			<div class="msgBarInner">
				<div class="msgBarTitle">
					<h2>メッセージ</h2>
				</div><!-- /.infoBarTitle -->
				<div class="msgBarText">
					<ul class="list">
						<li><a href="/comp/msg/casual/list">カジュアル面談</a>：<span>未読 {{ $member_act['user_casual_cnt'] }}件</span></li>
						<li><a href="/comp/msg/formal/list">正式応募</a>：<span>未読 {{ $member_act['user_formal_cnt'] }}件</span></li>
						<li><a href="/comp/msg/event/list">イベント</a>：<span>未読 {{ $member_act['event_cnt'] }}件</span></li>
					</ul>
				</div><!-- /.msgBarText -->
				<div style="display:flex;">
					<h2 style="color:#0000ff;font-size: 16px;font-weight: 500;">{{ $member_act['comp_name'] }}</h2>
				</div><!-- /.infoBarTitle -->
			</div><!-- /.msgBarInner -->
		</div><!-- /.msgBar -->

	</header>


    <main class="main" style="margin-top: 130px;">
		<div class="mainContents">
			@yield('content')
		</div>
	</main>
        
	<footer class="footer">
		<small>Copyright ARK All Rights Reserved.</small>
	</footer>

	<script src="{{ asset('comp/assets/js/remodal.min.js') }}"></script>
	<script src="{{ asset('comp/assets/js/easyselectbox.min.js') }}"></script>

</body>


<style>
input[type="email"] {
    width: 160%;
    padding: 7px 10px;
    background-color: #fff;
    border: 1px solid #C7C7C7;
    border-radius: 3px;
}
input[type="password"] {
    width: 40%;
    padding: 7px 10px;
    background-color: #fff;
    border: 1px solid #C7C7C7;
    border-radius: 3px;
}


.dropdown-menu .inner {
    margin-top: 0;
    padding: 0;
    text-align: center;
    border-top: none;
    border-bottom: none;

    width: auto;
    position: absolute;
    top: 25px;
    left: 0;
/*    padding: 15px;*/
    padding: 5px;
    background-color: #fff;
    visibility: hidden;
    opacity: 0;
    transition: .2s;
}

</style>

</html>

