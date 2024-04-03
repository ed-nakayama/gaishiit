<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>外資IT企業のクチコミ評価・求人なら外資IT.com</title>
	<meta name="description" content="外資IT.comは外資系IT企業に特化したクチコミ・求人サイトです。採用が決まるまで完全無料、興味のある企業の担当者とは直接コミュニケーションも可能です。">
{{--
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+1p:wght@400;700&display=swap" rel="stylesheet">
--}}
    <link href="http://fonts.googleapis.com/earlyaccess/notosansjp.css">
    <link rel="canonical" href="{{ url()->current() }}" />
    <link rel="stylesheet" href="/css/slick.css">
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/common.css">
    <link rel="stylesheet" href="/css/top.css">
    <link rel="stylesheet" href="/css/job.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/chart.css') }}" rel="stylesheet">
{{--    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.js"></script>--}}
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

#sec-title .title_block .inner {
  position: absolute;
/*  top: 42%;*/
  top: 30%;
  left: 50%;
  width: 60%;
  text-align: center;
  transform: translate(-50%, -50%);
}

.formbox .btn-login2 {
  position: relative;
  width: 170px;
  height: 35px;
  font-size: 15px;
  line-height: 35px;
  text-align: center;
  border: none;
  overflow: visible;
  top:0px;
  color: #fff;
/*  background-color: rgb(47, 46, 46);*/
  margin-top: 0px;
  padding-bottom: 1em;
}

</style>

<script>

/////////////////////////////////////////////////////////
// 企業選択モーダル
/////////////////////////////////////////////////////////
function OpenComp() {

	let element = document.getElementById('comp_name');
	let comp_name = element.value;

	$('.subtile').empty( );
	$('.block-ttl').empty( );
	$('.cate-list').empty( );

	if (comp_name == "") {
		html = "企業名を指定してください。";
		$('.subtile').append(html);
		$('body').css('overflow-y', 'hidden');  // 本文の縦スクロールを無効
		$('.modalAreaJob').fadeIn();

	} else {

		$('.subtile').append("企業を選択");
		$('.block-ttl').append(comp_name);

	    // ajaxでリクエストを送信
		$.ajax({
			headers: {
				"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
			},
	        url: "https://gaishiit.com/api/complist",
	        type: "GET",
	        data: { 'comp_name' : comp_name },
	        dataType: 'json',
		}).done(
	      // 通信成功時の処理
			function (data) {

			if (data.length === 0) {
				html = "<center><label style='transform: rotate(0.03deg);'>データが見つかりません。</label></center><br>";
				$('.cate-list').append(html);
				$('body').css('overflow-y', 'hidden');  // 本文の縦スクロールを無効
				$('.modalAreaJob').fadeIn();

	
			} else if (data.length === 1) {
				$.each(data, function (index, value) { //dataの中身からvalueを取り出す
					CompDetail(value.id);
				})
				$('body').css('overflow-y','auto');     // 本文の縦スクロールを有効
				$('.modalAreaJob').fadeOut();

			} else {
				$.each(data, function (index, value) { //dataの中身からvalueを取り出す
				//ここの記述はリファクタ可能
					let id = value.id;
					let name = value.name;

					html = `
						<li>
							<label><a href="/company/${id}"><span>${name}</span></a></label>
						</li>
					`
					$('.cate-list').append(html);
				})

				$('.cate-list').append("<br><br>");
				$('body').css('overflow-y', 'hidden');  // 本文の縦スクロールを無効
				$('.modalAreaJob').fadeIn();
			}

		}).fail(function(event) {
			alert('URLにアクセス失敗')
		});
	}
}

function onPress(code) {
	//エンターキー押下なら
	if(13 === code)
	{
		OpenComp();
	}
}
</script>


<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PQ27GXX7"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

	<div id="wrapper">
		<div class="mv"></div>
		<section id="sec-title" class="items">
			<div class="title_logo"><img src="/img/top/logo.png" alt="Gaishi IT.com" class="v-pc" style="width=100%;"><img src="/img/top/logo_sp.png" alt="Gaishi IT.com" class="v-sp" style="width=100%;"></div>

			<div class="title_block">
<ul id="menu-content">
	<li style="transform: rotate(0.03deg);"><a href="/company"">企業を探す</a></li>
	<li style="transform: rotate(0.03deg);"><a href="/job">求人を探す</a></li>
</ui>
				<div class="inner">
{{-- クチコミ検索 --}}
					<div class="search_container">
						<div class="formbox">
							<ul id="menu_contents">
								<li style="display:flex;white-space:nowrap;width:100%;">
									<a class="btn-login2" href="{{ route('user.register') }}">新規会員登録</a>
									　<a class="btn-login2" href="/login">ログイン</a>
									<br>
								</li>
								<li>
									<div class="input-group">
										<input type="text"  id="comp_name" name="comp_name"  class="form-control" placeholder="企業名で検索する" style="width:60%;"  onkeypress="onPress(event.keyCode);">
										<div class="input-group-append">
											<button class="btn btn-secondary" title="kuchikomi_btn" type="button" style="font-size: 24px;background-color:#DAA520;border: none;" onclick="OpenComp();">
												<i class="fa fa-search"></i>
											</button>
										</div>
									</div>
								</li>
								<li>
									　
								</li>
							</ul>
						</div>
					</div>
{{-- END クチコミ検索 --}}

                    <h1><img src="/img/top/ttl_main.png" alt="外資IT企業へのハイクラスな転職なら" style="width=100%;"></h1>
                    <div class="formbox">
{{--
						<form method="POST" name ="form1" action="{{ route('user.login') }}">
	                        @csrf
                            <ul>
                                <li><input type="text" name="email" value="{{ old('email') }}" placeholder="メールアドレス" required autocomplete="email" autofocus></li>
                                <li>
                                	@error('email')
                                    	<span class="invalid-feedback" role="alert" style="color:#ff0000;">
                                        	<strong>{{ $message }}</strong>
                                    	</span>
                                	@enderror
                                </li>
                                <li><input type="password" name="password" value="" placeholder="パスワード" required autocomplete="current-password"></li>
                                <li>
                                	@error('password')
                                    	<span class="invalid-feedback" role="alert" style="color:#ff0000;">
                                       	 <strong>{{ $message }}</strong>
                                    	</span>
                                	@enderror
                                </li>
                                <li>
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                     <label class="form-check-label" for="remember">
                                        {{ __('remember') }}
                                    </label>
                                </li>
                            </ul>
                            <div class="btnarea">
                                <div class="btn btn-login"><input type="submit" value="ログイン"></div>
                                <div class="btn btn-register"><a href="{{ route('user.register') }}">新規会員登録</a></div>
                            </div>
                            <p class="forget"><a href="{{ route('user.password.request') }}">パスワードを忘れた方はこちら</a></p>
                        </form>
--}}
                    </div>
                </div>
            </div>
        </section>

        <div id="logo-slider">
            <div class="slider">
                <div class="slider_item"><img src="/img/top/slider_accenture.png" alt="accenture"></div>
                <div class="slider_item"><img src="/img/top/slider_adobe.png" alt="Adobe"></div>
                <div class="slider_item"><img src="/img/top/slider_aws.png" alt="aws"></div>
                <div class="slider_item"><img src="/img/top/slider_bcg.png" alt="BCG"></div>
                <div class="slider_item"><img src="/img/top/slider_cisco.png" alt="cisco"></div>
                <div class="slider_item"><img src="/img/top/slider_deloitte.png" alt="Deloitte"></div>
                <div class="slider_item"><img src="/img/top/slider_google.png" alt="Google"></div>
                <div class="slider_item"><img src="/img/top/slider_ibm.png" alt="IBM"></div>
                <div class="slider_item"><img src="/img/top/slider_meta.png" alt="Meta"></div>
                <div class="slider_item"><img src="/img/top/slider_microsoft.png" alt="Microsoft"></div>
                <div class="slider_item"><img src="/img/top/slider_oracle.png" alt="oracle"></div>
                <div class="slider_item"><img src="/img/top/slider_pwc.png" alt=pws"></div>
                <div class="slider_item"><img src="/img/top/slider_salesforce.png" alt="salesforce"></div>
            </div>
        </div>

        <section id="sec-casual" class="items">
            <div class="items_detail">
                <h2 class="items_h2 fadein"><img src="/img/top/ttl_casual.png" alt="Casual"></h2>
                <div class="items_box fadein">
                    <h3>カジュアルに面談できる</h3>
                    <p>
                        企業にとっても、転職者にとっても、重要なのはミスマッチを防ぐこと。<br>
                        「正式応募とは別のカジュアルな面談」を気軽に設定いただくことが可能なので、応募の前に、お互いの雰囲気を確認することができます。
                    </p>
                </div>
            </div>
            <div class="items_bg">
                <video class="items_video rellax" data-rellax-percentage="0.5" data-rellax-speed="-7" data-rellax-xs-speed="-7" data-rellax-mobile-speed="-11" data-rellax-tablet-speed="-8" data-rellax-desktop-speed="-7" playsinline autoplay muted loop>
                    <source src="/img/top/movie/casual-hd.mp4">
                </video>
            </div>
        </section> 

        <section id="sec-high" class="items">
            <div class="items_detail">
                <h2 class="items_h2 fadein"><img src="/img/top/ttl_high.png" alt="High class"></h2>
                <div class="items_box fadein">
                    <h3>先進的な待遇と環境が整う、<br>ハイクラスな求人だけ</h3>
                    <p>
                        活躍の場を求める高スキルな人材にふさわしい、ハイクラスな求人をそろえました。<br>
                        あなたのキャリアを伸ばす、先進的な待遇と環境をご用意しています。
                    </p>
                </div>
            </div>
            <div class="items_bg">
                <video class="items_video rellax" data-rellax-percentage="0.5" data-rellax-speed="-7" data-rellax-xs-speed="-7" data-rellax-mobile-speed="-11" data-rellax-tablet-speed="-8" data-rellax-desktop-speed="-7" playsinline autoplay muted loop>
                    <source src="/img/top/movie/high-hd.mp4">
                </video>
            </div>
        </section>

        <section id="sec-global" class="items">
            <div class="items_detail">
                <h2 class="items_h2 fadein"><img src="/img/top/ttl_global.png" alt="Global IT"></h2>
                <div class="items_box fadein">
                    <h3>外資系 IT企業に特化</h3>
                    <p>
                        ガイシITは外資系のIT企業に特化した転職支援サービス。<br>
                        ​自分に合った求人探しから、条件の交渉、採用までを、豊富なマッチング経験でサポートします。
                    </p>
                </div>
            </div>
            <div class="items_bg">
                <video class="items_video rellax" data-rellax-percentage="0.5" data-rellax-speed="-7" data-rellax-xs-speed="-7" data-rellax-mobile-speed="-11" data-rellax-tablet-speed="-8" data-rellax-desktop-speed="-7" playsinline autoplay muted loop>
                    <source src="/img/top/movie/global-hd.mp4">
                </video>
            </div>
        </section>

        <section id="sec-other" class="items">
            <div class="other_block">
                <div class="items_box fadein">
                    <h3>採用が決まるまで<br>完全無料</h3>
                    <p>求人情報の検索から採用まで、費用をいただくことは一切ありません</p>
                </div>
                <div class="items_box fadein">
                    <h3>企業の担当者と<br>ダイレクトに</h3>
                    <p>興味のある企業の担当者とはエージェントを介さずに​直接コミュニケーションをとっていただきます</p>
                </div>
                <div class="items_box fadein">
                    <h3>採用が決まるまで<br>完全無料</h3>
                    <p>求人情報の検索から採用まで、費用をいただくことは一切ありません</p>
                </div>
            </div>
        </section>

        <section id="sec-other" class="items">


			{{-- クチコミ数ランキング --}}
				@include ('user/partials/eval_ranking_fix')
			{{-- END クチコミ数ランキング --}}
<br>
			{{-- ピックアップ求人 --}}
				@include ('user/partials/job_pickup')
			{{-- END ピックアップ求人 --}}
<br>

			{{-- 求人検索ボタン --}}
				@include ('user/partials/job_search_button')
			{{-- END 求人検索ボタン --}}

<br>
			{{-- 3種 求人検索 --}}
				@include ('user/partials/job_search_3type')
			{{-- END 3種 求人検索ボタン --}}
        </section>

        <footer id="footer">
            <div class="footer_inner">
                <p class="copy">
                <a href="/corporate">運営会社</a>　
                <a href="/blog">お役立ちコラム</a>　
                <a href="/kiyaku">利用規約</a>　
                <a href="/privacy">プライバシーポリシー</a>　
                <a href="/adminfaq">お問い合わせ</a>　
                	(c) Gaishi-IT.com
                </p>
            </div>
        </footer>
    </div>

{{-- 企業選択モーダル  --}}
        <div id="modalAreaJob" class="modalAreaJob modalSort">
            <div id="modalBg" class="modalBg"></div>
            <div class="modalWrapper">
              <div class="modalContents">
                <h3 class="subtile">企業を選択</h3>    
                    <div id="" class="block">
                        <p class="block-ttl"></p>
                        <ul class="cate-list">
                        </ul>
                    </div>
    
              </div>
              <div id="closeModal" class="closeModal">
                ×
              </div>
            </div>
        </div>
{{-- END 企業選択モーダル  --}}



    <script src="/js/jquery-3.5.1.min.js"></script>
    <script src="/js/rellax.min.js"></script>
    <script src="/js/slick.min.js"></script>
    <script src="/js/common.js"></script>
    <script src="/js/top.js"></script>
    <script src="/js/job.js"></script>
</body>


</html>