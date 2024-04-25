
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.title') }}</title>
	<meta name="description" content="{{ config('app.description') }}">
    <link rel="canonical" href="{{ url()->current() }}" />

	<meta property="og:url" content="{{ url()->current() }}" />
	<meta property="og:site_name" content="{{ config('app.name') }}" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="{{ config('app.title') }}" />
	<meta property="og:description" content="{{ config('app.description') }}" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
{{--    <link href="https://fonts.googleapis.comcss2?family=M+PLUS+1p:wght@400;700&display=swap" rel="stylesheet">--}}
    <link href="http://fonts.googleapis.com/earlyaccess/notosansjp.css">
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/top.css') }}">
    <link rel="stylesheet" href="{{ asset('css/job.css') }}">
    <link rel="stylesheet" href="{{ asset('css/new_common.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://gaishiit.com/css/chart.css" rel="stylesheet">

</head>

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
	        url: "{{ url('/api/complist') }}",
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

/////////////////////////////////////////////////////////
// 企業詳細
/////////////////////////////////////////////////////////
function CompDetail(compId) {

	document.kuchiform.action = "/company/" + compId;
	document.kuchiform.submit();
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
@if (config('app.env') == 'production')
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PQ27GXX7"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
@endif
		
	{{ html()->form('POST', '/company')->attribute('name', 'kuchiform')->open() }}
	{{ html()->form()->close() }}

    <div id="wrapper">
        <div class="mv"></div>
        <section id="sec-title" class="items">
            <div class="title_logo"><a href="#"><img src="img/top/logo.png" alt="Gaishi IT.com" class="v-pc"><img src="img/top/logo_sp.png" alt="Gaishi IT.com" class="v-sp"></a></div>
			<div class="menu-content">
				<p><a href="/company">企業を探す</a></p>
				<p><a href="/job">求人を探す</a></p>
				<p><a href="/blog">お役立ちコラム</a></p>
			</div>
            <div class="title_block">		
                <div class="inner">
					<div class="signup-login">
						<p><a href="/register">新規会員登録</a></p>
						<p><a href="/login">ログイン</a></p>
					</div>
					<div class="search_container">
						<div class="searchbox">
							<div class="input-group">
								<input type="text"  id="comp_name" name="comp_name"  class="form-control" placeholder="企業名で検索する" style="width:60%;"  onkeypress="onPress(event.keyCode);">
								<div class="input-group-append">
									<button class="btn btn-secondary" title="kuchikomi_btn" type="button" style="font-size: 24px;background-color:#DAA520;border: none;" onclick="OpenComp();">
										<i class="fa fa-search"></i>
									</button>
									</div>
									</div>
								<ul>
							</ul>
						</div>
					</div>
					<h1><img src="img/top/ttl_main.png" alt="外資IT企業へのハイクラスな転職なら"></h1>
                </div>
            </div>
        </section>

        <div id="logo-slider">
            <div class="slider">
                <div class="slider_item"><img src="img/top/slider_accenture.png" alt="accenture"></div>
                <div class="slider_item"><img src="img/top/slider_adobe.png" alt="Adobe"></div>
                <div class="slider_item"><img src="img/top/slider_aws.png" alt="aws"></div>
                <div class="slider_item"><img src="img/top/slider_bcg.png" alt="BCG"></div>
                <div class="slider_item"><img src="img/top/slider_cisco.png" alt="cisco"></div>
                <div class="slider_item"><img src="img/top/slider_deloitte.png" alt="Deloitte"></div>
                <div class="slider_item"><img src="img/top/slider_google.png" alt="Google"></div>
                <div class="slider_item"><img src="img/top/slider_ibm.png" alt="IBM"></div>
                <div class="slider_item"><img src="img/top/slider_meta.png" alt="Meta"></div>
                <div class="slider_item"><img src="img/top/slider_microsoft.png" alt="Microsoft"></div>
                <div class="slider_item"><img src="img/top/slider_oracle.png" alt="oracle"></div>
                <div class="slider_item"><img src="img/top/slider_pwc.png" alt="pws"></div>
                <div class="slider_item"><img src="img/top/slider_salesforce.png" alt="salesforce"></div>
            </div>
        </div>

        <section id="sec-casual" class="items">
            <div class="items_detail">
                <h2 class="items_h2 fadein"><img src="img/top/ttl_casual.png" alt="Casual"></h2>
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
                    <source src="img/top/movie/casual-hd.mp4">
                </video>
            </div>
        </section> 

        <section id="sec-high" class="items">
            <div class="items_detail">
                <h2 class="items_h2 fadein"><img src="img/top/ttl_high.png" alt="High class"></h2>
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
                    <source src="img/top/movie/high-hd.mp4">
                </video>
            </div>
        </section>

        <section id="sec-global" class="items">
            <div class="items_detail">
                <h2 class="items_h2 fadein"><img src="img/top/ttl_global.png" alt="Global IT"></h2>
                <div class="items_box fadein">
                    <h3>外資系 IT企業に特化</h3>
                    <p>
                        ガイシITは外資系のIT企業に特化した転職支援サービス。<br>
                        自分に合ったジョブ探しから、条件の交渉、採用までを、豊富なマッチング経験でサポートします。
                    </p>
                </div>
            </div>
            <div class="items_bg">
                <video class="items_video rellax" data-rellax-percentage="0.5" data-rellax-speed="-7" data-rellax-xs-speed="-7" data-rellax-mobile-speed="-11" data-rellax-tablet-speed="-8" data-rellax-desktop-speed="-7" playsinline autoplay muted loop>
                    <source src="img/top/movie/global-hd.mp4">
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
                    <p>興味のある企業の担当者とはエージェントを介さずに直接コミュニケーションをとっていただきます</p>
                </div>
                <div class="items_box fadein">
                    <h3>採用が決まるまで<br>完全無料</h3>
                    <p>求人情報の検索から採用まで、費用をいただくことは一切ありません</p>
                </div>
            </div>
        </section>

		<section id="blog" class="items">
			<div class="blog_ttl">
				<h2>外資IT転職ガイド</h2>
			</div>
            <div class="blog_block">
				@foreach ($blogList as $blog)
					<div class="blog_box fadein">
						<div class="blog_img">
							<a href="/blog/{{ $blog->cat_id }}/{{ $blog->id }}">
								@if (!empty($blog->thumb))
									<img src="{{ $blog->thumb }}" alt="" class="v-pc"><img src="{{ $blog->thumb }}" alt="Gaishi IT.com" class="v-sp">
								@else
									<img src="/storage/blog/thumb/h_logo.png" alt="" class="v-pc"><img src="/storage/blog/thumb/h_logo.png" alt="Gaishi IT.com" class="v-sp">
								@endif
							</a>
						</div>
						<h3><a href="/blog/{{ $blog->cat_id }}/{{ $blog->id }}">{{ $blog->title }}</a></h3>
						<div class="blog_info">
							<p class="tag"><a href="/blog/{{ $blog->cat_id }} }}">{{ $blog->getCatName() }}</a></p>
							<p class="date">{{ str_replace('-', '.', $blog->open_date) }}</p>
						</div>
						<p>{!! mb_strimwidth($blog->intro, 0, 70, "...") !!}</p>
						<div class="blog_button02">
							<a href="/blog/{{ $blog->cat_id }}/{{ $blog->id }}">この記事を読む</a>
						</div>
					</div>
				@endforeach
            </div>
			<div class="blog_button01">
				<a href="/blog">一覧を見る</a>
			</div>
        </section>


		{{-- トップ内部リンク --}}
			@include ('user/partials/job_search_top')
		{{-- END トップ内部リンク --}}


        <footer id="footer">
            <div class="footer_inner">
				<p><a href="/corporate">運営会社</a></p>
                <p><a href="/blog">お役立ちコラム</a></p>
{{--
                <p><a href="/kiyaku">利用規約</a></p>
--}}
                <p><a href="/privacy">プライバシーポリシー</a></p>
                <p><a href="/adminfaq">お問い合わせ</a></p>
				<p>(c) Gaishi-IT.com</p>
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