<!DOCTYPE html>
<html lang="ja">

<head>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NPVBH5VW');</script>
<!-- End Google Tag Manager -->

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

@yield('addheader')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+1p:wght@400;700&display=swap" rel="stylesheet">
    <link href="http://fonts.googleapis.com/earlyaccess/notosansjp.css">
    <link rel="canonical" href="{{ url()->current() }}" />
    <link href="{{ asset('css/base.css') }}" rel="stylesheet">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    <link href="{{ asset('css/career.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

</head>

<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NPVBH5VW"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

    <div id="wrapper">

		<header>
			<div id="menu">
				<div id="menu-inner">
					<div class="logo">
						<a href="/"><figure><img src="/img/h_logo.png" alt="外資IT.com"></figure></a>
					</div>
				</div>
@yield('breadcrumbs')
			</div>
		</header>

		<div class="pane-contents">
			@yield('content')
		</div>

        
<footer class="pane-footer">
	<div class="inner">
		<ul>
			<li><a href="/corporate">運営会社</a></li>
			<li><a href="#">お役立ちコラム</a></li>
{{--
			<li><a href="/kiyaku">利用規約</a></li>
--}}
			<li><a href="/privacy">プライバシーポリシー</a></li>
			<li><a href="/adminfaq">お問い合わせ</a></li>
		</ul>
		<p>Copyright ARK All Rights Reserved.</p>
	</div>
</footer>

    <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('js/common.js') }}"></script>

</body>


</html>