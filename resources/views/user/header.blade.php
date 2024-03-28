<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+1p:wght@400;700&display=swap" rel="stylesheet">
    <link href="http://fonts.googleapis.com/earlyaccess/notosansjp.css">
    <link href="{{ asset('css/base.css') }}" rel="stylesheet">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
</head>
<body>
    <div id="wrapper">

		<header>
			<div id="menu">
				<div id="menu-inner">
					<div class="logo">
						<a href="/mypage"><img src="/images/logo.png" width="250" alt=""></a>
					</div>

					<div class="nav-pc">
						<nav>
							<div class="inner">
								<ul id="menu-content">
									<li><a href="/company">企業を探す</a></li>
									<li><a href="/job">求人を探す</a></li>
									<li><a href="/interview/list">メッセージ</a></li>
									<li><a href="/adminfaq">お問合せ</a></li>
								</ul>

								<div class="userMenu">
									<input type="checkbox" id="toggle" autocomplete="off">
									<label for="toggle" onclick="">{{ Auth::user()->name }}</label>  
									<ul id="menu">  
										<li><a href="/mypage">マイページ　　</a></li>
										<li><a href="/setting">個人設定　　　</a></li>
										<li>
											<a href=""{{ route('user.password.edit') }}" onclick="event.preventDefault(); document.getElementById('password_edit-form').submit();">パスワード変更</a>
											<form id="password_edit-form" action="{{ route('user.password.edit') }}" method="GET" style="display: none;">
											</form>
										</li>
										<li>
											<a href="{{ route('user.logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト　　</a>
											<form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
											@csrf
											</form>
										</li>
									</ul>
								</div>
							</div>
						</nav>
					</div>

					<div class="nav-sp">
						<nav>
							<div class="inner">
								<div class="userName">
									<p>{{ Auth::user()->name }}</p>
								</div>
								<ul id="menu-content">
									<li><a href="/mypage">マイページ</a></li>
									<li><a href="/interview/list">メッセージ</a></li>
									<li><a href="/company">企業を探す</a></li>
									<li><a href="/job">求人を探す</a></li>
									<li><a href="/setting">個人設定</a></li>
{{--									<li><a href="/event">イベントを探す</a></li>--}}
									<li>
										<a href=""{{ route('user.password.edit') }}" onclick="event.preventDefault(); document.getElementById('password_edit-form').submit();"">パスワード変更</a>
									</li>
									<li>
										<a href="{{ route('user.logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
									</li>
								</ul>
							</div>
						</nav>
    
						<div class="toggle-btn">
							<span></span>
							<span></span>
							<span></span>
						</div>
						<div id="mask"></div>
					</div>
				</div>

				<div id="menu-bottom">
					<div id="newsArea">
						<div class="inner">
							<p class="cate">お知らせ</p>
							<dl>
								<dt class="date">{{ $information[0]->created_at->format('Y/m/d') }}</dt>
								<dd>{{ $information[0]->content }}</dd>
							</dl>
						</div>
					</div>
				</div>
			</div>
		</header>

