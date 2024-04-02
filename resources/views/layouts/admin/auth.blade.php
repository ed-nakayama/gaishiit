<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('admin/assets/css/destyle.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/css/remodal.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/remodal-default-theme.css') }}" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.js" defer></script>
</head>
<body class="bg-admin">
    
    <header class="header">
        <div class="headInner">
            <div class="headMain">
                <h1 class="logo"><a href="/admin/mypage"><img src="/images/logo_on_admin.png" width="150"></a></h1>
                <div class="containerMenu">
{{--
                    <ul class="menu">
                        <li><a href="/admin/user/list">新規候補者の承認</a></li>
                        <li><a href="javascript:void(0);" onClick="openWin({{ Auth::id() }})">企業代理ログイン</a></li>
                    </ul><br>
--}}
                    <ul class="menu">【アカウント管理】　
                        <li><a href="/admin/comp/list">企業登録</a></li>
                        <li><a href="/admin/candidate">候補者管理</a></li>
                        <li><a href="/admin/ownership">オーナーシップ管理</a></li>
                        <li><a href="/admin/admin/list">メンバー管理</a></li>
                        <li><a href="/admin/log/list">ログイン履歴</a></li>
                    </ul><br>
                    <ul class="menu">【設定変更】　
                        <li><a href="/admin/buscat">業種管理</a></li>
                        <li><a href="/admin/buscatdetail">業種詳細管理</a></li>
                        <li><a href="/admin/jobcat">職種管理</a></li>
                        <li><a href="/admin/jobcatdetail">職種詳細管理</a></li>
                        <li><a href="/admin/blog/list">ブログ管理</a></li>
                    </ul><br>
                    <ul class="menu">　　　　　　　
                        <li><a href="/admin/industorycat">インダストリ管理</a></li>
                        <li><a href="/admin/industorycatdetail">インダストリ詳細管理</a></li>
                        <li><a href="/admin/commitcat">こだわり管理</a></li>
                        <li><a href="/admin/commitcatdetail">こだわり詳細管理</a></li>

                    </ul><br>
                    <ul class="menu">【運営】　
                        <li><a href="/admin/banner">バナー管理</a></li>
                        <li><a href="/admin/pickup">ピックアップ管理</a></li>
                        <li><a href="/admin/info/list">お知らせ管理</a></li>
                        <li><a href="/admin/claim/every">請求情報</a></li>
                        <li><a href="/admin/faq/list">FAQ</a></li>
                        <li><a href="/admin/ask">お問合せ一覧</a></li>
                    </ul><br>
                </div><!-- /.containerMenu -->
            </div><!-- /.menu -->

            <div class="dropdown-menu userName">
                <div class="userNameInner">
                    <ul class="usrNameMenu">
                      <li>
                        <a href="#" class="name">{{ Auth::user()->name }}</a>
                        <ul class="inner">
                            <li style="white-space:nowrap;"><a href="{{ route('admin.password.edit') }}" onclick="event.preventDefault(); document.getElementById('password_edit-form').submit();">{{ __('Change Password') }}</a></li>
                            <li><a href="{{ route('admin.logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a></li>
                        </ul>
                        <form id="password_edit-form" action="{{ route('admin.password.edit') }}" method="GET" style="display: none;">
                        </form>
                         <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                             @csrf
                         </form>
                      </li>
                    </ul>
                </div>
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
            </div><!-- /.infoBarInner -->
        </div><!-- /.infoBar -->
    </header>
    
    
    <main class="main">
        <div class="mainContents">
            @yield('content')
        </div>
    </main>
        
    <footer class="footer">
        <small>Copyright ARK All Rights Reserved.</small>
    </footer>

    <script src="{{ asset('admin/assets/js/remodal.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/script.js') }}"></script>

</body>


<script type="text/javascript">

var w;

function openWin(id) {

/*
		console.log("w=" + w)

    	if (!w || w.closed)
			alert("閉じてる");
		　else
			w.focus();
*/

	$.ajax({
		headers: {
			"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
		},
        type: "GET",
        url: "/admin/update/token",
        data: { 'id' : id },
        dataType: 'text',
	}).done(function(event) {
    	console.log('URLにアクセス成功');
/*		w = window.open("/comp/virtual/login?id=" + id + "&_token=" + event, "_blank", "width=800,height=640,noopener");*/
		w = window.open("/comp/virtual/login?id=" + id + "&_token=" + event, "_blank");
		console.log("Z=" + w)
			
	}).fail(function(event) {
		console.log('URLにアクセス失敗')
	});

}

</script>

<style>
input[type="email"] {
    width: 60%;
    padding: 7px 10px;
    background-color: #fff;
    border: 1px solid #C7C7C7;
    border-radius: 3px;
}
input[type="password"] {
    width: 60%;
    padding: 7px 10px;
    background-color: #fff;
    border: 1px solid #C7C7C7;
    border-radius: 3px;
}

</style>

</html>

