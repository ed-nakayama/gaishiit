<!DOCTYPE html>
<html lang="ja">

<head prefix="og: http://ogp.me/ns#">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+1p:wght@400;700&display=swap" rel="stylesheet">
	<link href="http://fonts.googleapis.com/earlyaccess/notosansjp.css">
	@if (empty($canonical))
		<link rel="canonical" href="{{ url()->current() }}" />
	@else
		<link rel="canonical" href="{{ $canonical }}" />
	@endif
	<link href="{{ asset('css/base.css') }}" rel="stylesheet">
	<link href="{{ asset('css/common.css') }}" rel="stylesheet">
	<link href="{{ asset('css/expand.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset('css/chart.css') }}" rel="stylesheet">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.js"></script>
	<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.js" defer></script>

	<meta property="og:url" content="{{ url()->current() }}" />
	<meta property="og:site_name" content="{{ config('app.name') }}" />

@yield('addheader')

{{--
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-BTKWNQWNMP"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-BTKWNQWNMP');
</script>
--}}

</head>
<style>

.formbox .btn-login2 {
  font-size: 15px;
  text-decoration:underline;
  transform: rotate(0.03deg);
  color:#666666;
}

</style>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PQ27GXX7');</script>
<!-- End Google Tag Manager -->

<body style="background-color:#FAFAFA;">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PQ27GXX7"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <div id="wrapper">
		<header>

			<div id="menu">
				@if ( config('app.env') == 'Staging')
					<center>
					<div style="background-color:blue;color:white;font-weight:bolder;font-size:16px;">
						{{ config('app.env') }}
					</div>
					</center>
				@endif

				<div id="menu-inner">
					<div class="logo">
						<a href="/mypage"><figure><img src="/img/h_logo.png" alt="外資IT.com" style="width:100%;"></figure></a>
					</div>

					<div class="nav-pc">
						<nav>
							<div class="inner">
								<ul id="menu-content">
									<li style="transform: rotate(0.03deg);"><a href="/company"">企業を探す</a></li>
									<li style="transform: rotate(0.03deg);"><a href="/job">求人を探す</a></li>
@if (Auth::guard('user')->check())
									<li style="transform: rotate(0.03deg);"><a href="/mypage">マイページ</a></li>
									<li style="transform: rotate(0.03deg);"><a href="/interview/list">メッセージ</a></li>
@endif
								</ul>
@if (Auth::guard('user')->check())
								<div class="userMenu">
									<input type="checkbox" id="toggle" autocomplete="off">
									<label for="toggle" onclick=""  style="transform: rotate(0.03deg);">{{ Auth::guard('user')->user()->name }}</label>  
									<ul id="menu">  
{{--										<li style="transform: rotate(0.03deg);"><a href="/mypage">マイページ　　</a></li>--}}
										<li style="transform: rotate(0.03deg);"><a href="/setting" style="font-size:1.6rem;">個人設定　　　</a></li>
										<li style="transform: rotate(0.03deg);white-space:nowrap;">
											<a href=""{{ route('user.password.edit') }}" onclick="event.preventDefault(); document.getElementById('password_edit-form').submit();" style="font-size:1.6rem;">パスワード変更</a>
											<form id="password_edit-form" action="{{ route('user.password.edit') }}" method="GET" style="display: none;">
											</form>
										</li>
										<li style="transform: rotate(0.03deg);">
											<a href="{{ route('user.logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="font-size:1.6rem;">ログアウト　　</a>
											<form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
											@csrf
											</form>
										</li>
									</ul>
								</div>
@else
								<div class="formbox">
									<ul>
										<li style="display:flex;white-space:nowrap;width:100%;">
											<a class="btn-login2" href="{{ route('user.register') }}">新規会員登録</a>
											　<a class="btn-login2" href="/login">ログイン</a>
										</li>
									</ul>
								</div>
@endif
							</div>
						</nav>
					</div>

					<div class="nav-sp">
						<nav>
							<div class="inner">
@if (Auth::guard('user')->check())
								<div class="userName">
									<p style="transform: rotate(0.03deg);">{{ Auth::guard('user')->user()->name }}</p>
								</div>
@endif
								<ul id="menu-content">
@if (Auth::guard('user')->check())
									<li style="font-size:1.6rem;"><a href="/mypage">マイページ</a></li>
									<li style="font-size:1.6rem;"><a href="/interview/list">メッセージ</a></li>
@endif
									<li style="font-size:1.6rem;"><a href="/company">企業を探す</a></li>
									<li style="font-size:1.6rem;"><a href="/job">求人を探す</a></li>
@if (Auth::guard('user')->check())
									<li style="font-size:1.6rem;"><a href="/setting">個人設定</a></li>
{{--								<li style="font-size:1.6rem;"><a href="/event">イベントを探す</a></li>--}}
									<li style="font-size:1.6rem;">
										<a href=""{{ route('user.password.edit') }}" onclick="event.preventDefault(); document.getElementById('password_edit-form').submit();"">パスワード変更</a>
									</li>
									<li style="font-size:1.6rem;">
										<a href="{{ route('user.logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
									</li>
@else
									<li style="font-size:1.6rem;">
										<a href="{{ route('user.register') }}">新規会員登録</a>
									</li>
									<li style="font-size:1.6rem;">
										<a href="/login">ログイン</a>
									</li>

@endif
								</ul>
							</div>
						</nav>
    
						<div class="toggle-btn">
							<span></span>
							<span></span>
							<span></span>
						</div>
						<div id="mask"></div>
					</div><!-- nav-sp -->

				</div><!-- menu-inner -->

@if (Auth::guard('user')->check())
				<div id="menu-bottom">
					<div id="newsArea">
						<div class="inner">
							<p class="cate">お知らせ</p>
							<dl>
                				@if (!empty($information[0]))
									<dt class="date">{{ $information[0]->updated_at->format('Y/m/d') }}</dt>
									<dd style="transform: rotate(0.03deg);">{{ $information[0]->content }}</dd>
                    			@endif
							</dl>
						</div>
					</div>
				</div>
@endif
			</div>
		</header>

<style>

.breadcrumbs-contents {
  display: flex;
  width: 96%;
  margin: 0 auto;
  padding-top: 20px;
  font-size:16px;
  backgroud-color:#fff;
}

@media screen and (max-width: 820px) {

  .pane-contents {
    padding-top: 10px;
  }

  .breadcrumbs-contents {
    position: relative;
    width: 94%;
    margin: 0 auto;
    padding-top: 80px;
    padding-bottom: 0px;
  }


}
</style>

		<div class="breadcrumbs-contents">
			@yield('breadcrumbs')
		</div>
		<div class="pane-contents" style="padding:0 0;">
			@yield('content')
		</div>
	</div>


<footer class="pane-footer">
	<div class="inner">
		<ul>
			<li style="font-size:14px;"><a href="/corporate">運営会社</a></li>
			<li style="font-size:14px;"><a href="/blog">お役立ちコラム</a></li>
{{--
			<li style="font-size:14px;"><a href="/kiyaku">利用規約</a></li>
--}}
			<li style="font-size:14px;"><a href="/privacy">プライバシーポリシー</a></li>
			<li style="font-size:14px;"><a href="/adminfaq">お問い合わせ</a></li>
		</ul>
		<p style="font-size:14px;">Copyright ARK All Rights Reserved.</p>
	</div>
</footer>

    <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('js/common.js') }}"></script>

</body>
</html>