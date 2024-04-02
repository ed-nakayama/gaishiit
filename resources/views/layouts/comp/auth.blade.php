<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
{{--
                <div class="containerNavBtn">
                    <ul class="navBtn">
                        <li style="white-space:nowrap;"><a href="/comp/user">新しい候補者を探す</a></li>
                        <li style="white-space:nowrap;"><a href="/comp/client">面談進捗管理</a></li>
                    </ul><!-- /.navBtn -->
                </div><!-- /.containerNav -->
--}}
                <div class="containerMenu">
                    <ul class="menu">
                        <li style="white-space:nowrap;"><a href="/comp/candidate">候補者管理</a></li>
                        <li style="white-space:nowrap;"><a href="/comp/job">ジョブ管理</a></li>
                        <li style="white-space:nowrap;"><a href="/comp/unit">部門設定</a></li>
                        <li style="white-space:nowrap;"><a href="/comp/event">イベント管理</a></li>
                        <li><a href="/comp/faq/list">FAQ</a></li>
                    </ul>
                </div><!-- /.containerMenu -->
            </div><!-- /.menu -->

			<div style="display: flex; align-items: center;">
				<div class="dropdown-menu userName">
					<ul class="usrNameMenu">
						<li><a href="/comp/edit" class="name"  style="white-space:nowrap; color:white;  background:gray;">企業設定</a></li>
					</ul>
				</div><!-- /.dropdown-men -->　
				<div class="dropdown-menu userName">
					<ul class="usrNameMenu">
						<li>
							<a href="#" class="name" style="white-space:nowrap;">{{ Auth::user()->name }}　▼</a>
							<ul class="inner">
								<li><a href="/comp/member/setting">個人設定</a></li>
								<li><a href="{{ route('comp.password.edit') }}" onclick="event.preventDefault(); document.getElementById('password_edit-form').submit();">{{ __('Change Password') }}</a></li>
								<li><a href="{{ route('comp.logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a></li>
							</ul>
							<form id="password_edit-form" action="{{ route('comp.password.edit') }}" method="GET" style="display: none;">
							</form>
							<form id="logout-form" action="{{ route('comp.logout') }}" method="POST" style="display: none;">
								@csrf
							</form>
						</li>
					</ul>
				</div><!-- /.dropdown-men -->
			</div>


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
{{--    <script src="{{ asset('comp/assets/js/easyselectbox.min.js') }}"></script>--}}
    <script src="{{ asset('comp/assets/js/script.js') }}"></script>

</body>

<style>

</style>


</html>

