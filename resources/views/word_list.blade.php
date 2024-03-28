@extends('layouts.user.auth')

@section('content')

<head>
	<title>社員クチコミ一覧｜{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/word.css') }}" rel="stylesheet">
</head>

<style>
.star5_rating{
    position: relative;
    z-index: 0;
    display: inline-block;
    white-space: nowrap;
    color: #CCCCCC; /* グレーカラー 自由に設定化 */
    font-size: 20px; /* フォントサイズ 自由に設定化 */
}

.star5_rating:before, .star5_rating:after{
    content: '★★★★★';
}

.star5_rating:after{
    position: absolute;
    z-index: 1;
    top: 0;
    left: 0;
    overflow: hidden;
    white-space: nowrap;
    color: #ffcf32; /* イエローカラー 自由に設定化 */
}

.star5_rating:after{ width: var(--rate); }

</style>


<style>
.ac-header::before {
	position: absolute;
	z-index: 1;
/*  top: 1rem;*/
  top: 0.5rem;
	right: 0.1875rem;
  margin: auto;
	width: 1.2rem;
	height: 1.2rem;
	content: '';
          transition: all .2s;
	-webkit-transform: rotate(0deg) scale(1, 1);
	        transform: rotate(0deg) scale(1, 1);
}

.ac-header::before {
	-webkit-transform: rotate(-45deg) scale(1, 1);
	        transform: rotate(-45deg) scale(1, 1);
          border: 2px solid #4AA5CE;
          border-left-style: solid;
          border-top-style: none;
          border-right-style: none;
}

.ac-header.rotate-arrow::before {
	-webkit-transform: rotate(-225deg) scale(1, 1);
	        transform: rotate(-225deg) scale(1, 1);
          border: 2px solid #4AA5CE;
          border-left-style: solid;
          border-top-style: none;
          border-right-style: none;
          /* width: 1.2rem; */
}

.ac-item {
  width: 100%;
  margin: 0 auto;
  cursor: pointer;

  border-top: 1px dashed #DDDDDD;
/*  padding: 16px 20px;*/
  padding: 4px 20px;
}

.ac-header {
  position: relative;
  display: flex;
  align-items: center;
  transition: ease-in-out 100ms;
  font-size: 1.6rem;
  padding-right: 40px;
}

.ac-detail {
  position: relative;
  display: flex;
  align-items: center;
  transition: ease-in-out 100ms;
  font-size: 1.4rem;
  font-weight: 300;
  padding-right: 40px;
  background: #F5F5F5;
}


.ac-txt {
  width: 100%;
  
  display: none;
/*  padding-top: 8px;*/
  padding-top: 4px;
 }

 .ac-header span {
  transition: ease-in-out 300ms;
 }

 .rotate-arrow::before {
  transform: rotate(180deg);
 }


 .ac-header .fa {
  float: right;
  line-height: 35px;
 }

 .ac-gold {
  /* color: #ddba4d; */
 }

 .ac-no-bar {
  border-bottom: 0;
}

.blur{
  -ms-filter: blur(5px);
  filter: blur(5px);
  background: #F1F1F1;
}

.ac-txt .login{
	z-index: 2;
  position:absolute;
  top: 50%;
  left: 50%;
  text-align:center;
  -ms-transform: translate(-50%,-50%);
  -webkit-transform: translate(-50%,-50%);
  transform: translate(-50%,-50%);
  padding:2em 2em;
  background:#FFFFEE;
  }

.button{
  background:#3366CC;
  padding:0.2em 1em;
  border:black 1px solid;
  text-decoration:none;
  font-size:1.0em;
}
.button2{
  background:#FFFF99;
  padding:0.2em 4.8em;
  border:black 1px solid;
  text-decoration:none;
  font-size:1.0em;
}

</style>

@auth
@include('user.user_activity')
@endauth
			<main class="pane-main">
				<div class="inner">
					<div class="ttl">
						<h2>{{ $comp_name }}</h2>
					</div>
					<div class="ttl">
						<h2>社員クチコミ一覧</h2>
					</div>

{{--				@foreach ($qa_list as $qa)--}}
					<div class="con-wrap">
						<div class="item thumb">
							<div class="inner">

								<div style="padding: 4px 16px;text-align: right;">
									　回答者：営業、在籍5～10年、男性
								</div>

								<div class="ac-wrap">

									<div class="ac-item">
										<p class="ac-header">
											<span style="width:200px;">給与</span><span class="star5_rating" style="--rate: 25%;"></span>
										</p>
										<div class="ac-txt">
											<p class="ac-detail">
												{!! nl2br($dummyMsg) !!}
											</p>
										</div>
									</div>

									<div class="ac-item">
										<p class="ac-header">
											<span style="width:200px;">福利厚生</span><span class="star5_rating" style="--rate: 50%;"></span>
										</p>
										<div class="ac-txt">
											<p class="ac-detail">
											@if(Auth::check())
												{!! nl2br($dummyMsg) !!}
											@else
												とてもよいです。<br>
												<div class="blur">
													{!! nl2br($dummyMsg) !!}
												</div>
											@endif
											</p>
											<div class="login">
												<a class="button" href="#" style="color:white;white-space:nowrap;">無料のユーザ登録をお願いします</a><br><br>
												<a class="button2" href="#" style="white-space:nowrap;">ログインはこちら</a>
											</div>
										</div>
									</div>

									<div class="ac-item">
										<p class="ac-header">
											<span style="width:200px;">法令遵守の意識</span><span class="star5_rating" style="--rate: 75%;"></span>
										</p>
										<div class="ac-txt">
											<p class="ac-detail">
											@if(Auth::check())
												{!! nl2br($dummyMsg) !!}
											@else
												とてもよいです。<br>
												<div class="blur">
													{!! $dummyMsg !!}
												</div>
											@endif
											</p>
											<div class="login">
												<a class="button" href="#" style="color:white;white-space:nowrap;">無料のユーザ登録をお願いします</a><br><br>
												<a class="button2" href="#" style="white-space:nowrap;">ログインはこちら</a>
											</div>
										</div>
									</div>

									<div class="ac-item">
										<p class="ac-header">
											<span style="width:200px;">社員のモチベーション</span><span class="star5_rating" style="--rate: 100%;"></span>
										</p>
										<div class="ac-txt">
											<p class="ac-detail">
											@if(Auth::check())
												{!! nl2br($dummyMsg) !!}
											@else
												とてもよいです。<br>
												<div class="blur">
													{!! $dummyMsg !!}
												</div>
											@endif
											</p>
											<div class="login">
												<a class="button" href="#" style="color:white;white-space:nowrap;">無料のユーザ登録をお願いします</a><br><br>
												<a class="button2" href="#" style="white-space:nowrap;">ログインはこちら</a>
											</div>
										</div>
									</div>

									<div class="ac-item">
										<p class="ac-header">
											<span style="width:200px;">育成</span><span class="star5_rating" style="--rate: 45%;"></span>
										</p>
										<div class="ac-txt">
											<p class="ac-detail">
											@if(Auth::check())
												{!! nl2br($dummyMsg) !!}
											@else
												とてもよいです。<br>
												<div class="blur">
													{!! $dummyMsg !!}
												</div>
											@endif
											</p>
											<div class="login">
												<a class="button" href="#" style="color:white;white-space:nowrap;">無料のユーザ登録をお願いします</a><br><br>
												<a class="button2" href="#" style="white-space:nowrap;">ログインはこちら</a>
											</div>
										</div>
									</div>

									<div class="ac-item">
										<p class="ac-header">
											<span style="width:200px;">ワークライフバランス</span><span class="star5_rating" style="--rate: 90%;"></span>
										</p>
										<div class="ac-txt">
											<p class="ac-detail">
											@if(Auth::check())
												{!! nl2br($dummyMsg) !!}
											@else
												とてもよいです。<br>
												<div class="blur">
													{!! $dummyMsg !!}
												</div>
											@endif
											</p>
											<div class="login">
												<a class="button" href="#" style="color:white;white-space:nowrap;">無料のユーザ登録をお願いします</a><br><br>
												<a class="button2" href="#" style="white-space:nowrap;">ログインはこちら</a>
											</div>
										</div>
									</div>

									<div class="ac-item">
										<p class="ac-header">
											<span style="width:200px;">リモート勤務</span><span class="star5_rating" style="--rate: 10%;"></span>
										</p>
										<div class="ac-txt">
											<p class="ac-detail">
											@if(Auth::check())
												{!! nl2br($dummyMsg) !!}
											@else
												とてもよいです。<br>
												<div class="blur">
													{!! $dummyMsg !!}
												</div>
											@endif
											</p>
											<div class="login">
												<a class="button" href="#" style="color:white;white-space:nowrap;">無料のユーザ登録をお願いします</a><br><br>
												<a class="button2" href="#" style="white-space:nowrap;">ログインはこちら</a>
											</div>
										</div>
									</div>

									<div class="ac-item">
										<p class="ac-header">
											<span style="width:200px;">定年</span><span class="star5_rating" style="--rate: 35%;"></span>
										</p>
										<div class="ac-txt">
											<p class="ac-detail">
											@if(Auth::check())
												{!! nl2br($dummyMsg) !!}
											@else
												とてもよいです。<br>
												<div class="blur">
													{!! $dummyMsg !!}
												</div>
											@endif
											</p>
											<div class="login">
												<a class="button" href="#" style="color:white;white-space:nowrap;">無料のユーザ登録をお願いします</a><br><br>
												<a class="button2" href="#" style="white-space:nowrap;">ログインはこちら</a>
											</div>
										</div>
									</div>

								</div><!--  ac-wrap -->
							</div><!-- item-inner -->
						</div><!-- item -->
					</div><!-- con-wrap -->
{{--					@endforeach--}}

<br>
{{-------------------- 後で削除 -----------------------------------------------}}
					<div class="con-wrap">

						<div class="item thumb">
							<div class="inner">

								<div style="padding: 4px 16px;text-align: right;">
									　回答者：コンサル、在籍1～5年、女性
								</div>

{{--							@foreach ($qa_list as $qa)--}}
								<div class="ac-wrap">

									<div class="ac-item">
										<p class="ac-header">
											<span style="width:200px;">給与</span><span class="star5_rating" style="--rate: 25%;"></span>
										</p>
										<div class="ac-txt">
											<p class="ac-detail">
											@if(Auth::check())
												{!! nl2br($dummyMsg) !!}
											@else
												とてもよいです。<br>
												<div class="blur">
													{!! $dummyMsg !!}
												</div>
											@endif
											</p>
											<div class="login">
												<a class="button" href="#" style="color:white;white-space:nowrap;">無料のユーザ登録をお願いします</a><br><br>
												<a class="button2" href="#" style="white-space:nowrap;">ログインはこちら</a>
											</div>
										</div>
									</div>

									<div class="ac-item">
										<p class="ac-header">
											<span style="width:200px;">福利厚生</span><span class="star5_rating" style="--rate: 50%;"></span>
										</p>
										<div class="ac-txt">
											<p class="ac-detail">
											@if(Auth::check())
												{!! nl2br($dummyMsg) !!}
											@else
												とてもよいです。<br>
												<div class="blur">
													{!! $dummyMsg !!}
												</div>
											@endif
											</p>
											<div class="login">
												<a class="button" href="#" style="color:white;white-space:nowrap;">無料のユーザ登録をお願いします</a><br><br>
												<a class="button2" href="#" style="white-space:nowrap;">ログインはこちら</a>
											</div>
										</div>
									</div>

									<div class="ac-item">
										<p class="ac-header">
											<span style="width:200px;">法令遵守の意識</span><span class="star5_rating" style="--rate: 75%;"></span>
										</p>
										<div class="ac-txt">
											<p class="ac-detail">
											@if(Auth::check())
												{!! nl2br($dummyMsg) !!}
											@else
												とてもよいです。<br>
												<div class="blur">
													{!! $dummyMsg !!}
												</div>
											@endif
											</p>
											<div class="login">
												<a class="button" href="#" style="color:white;white-space:nowrap;">無料のユーザ登録をお願いします</a><br><br>
												<a class="button2" href="#" style="white-space:nowrap;">ログインはこちら</a>
											</div>
										</div>
									</div>

									<div class="ac-item">
										<p class="ac-header">
											<span style="width:200px;">社員のモチベーション</span><span class="star5_rating" style="--rate: 100%;"></span>
										</p>
										<div class="ac-txt">
											<p class="ac-detail">
											@if(Auth::check())
												{!! nl2br($dummyMsg) !!}
											@else
												とてもよいです。<br>
												<div class="blur">
													{!! $dummyMsg !!}
												</div>
											@endif
											</p>
											<div class="login">
												<a class="button" href="#" style="color:white;white-space:nowrap;">無料のユーザ登録をお願いします</a><br><br>
												<a class="button2" href="#" style="white-space:nowrap;">ログインはこちら</a>
											</div>
										</div>
									</div>

									<div class="ac-item">
										<p class="ac-header">
											<span style="width:200px;">育成</span><span class="star5_rating" style="--rate: 45%;"></span>
										</p>
										<div class="ac-txt">
											<p class="ac-detail">
											@if(Auth::check())
												{!! nl2br($dummyMsg) !!}
											@else
												とてもよいです。<br>
												<div class="blur">
													{!! $dummyMsg !!}
												</div>
											@endif
											</p>
											<div class="login">
												<a class="button" href="#" style="color:white;white-space:nowrap;">無料のユーザ登録をお願いします</a><br><br>
												<a class="button2" href="#" style="white-space:nowrap;">ログインはこちら</a>
											</div>
										</div>
									</div>

									<div class="ac-item">
										<p class="ac-header">
											<span style="width:200px;">ワークライフバランス</span><span class="star5_rating" style="--rate: 90%;"></span>
										</p>
										<div class="ac-txt">
											<p class="ac-detail">
											@if(Auth::check())
													{!! $dummyMsg !!}
											@else
												とてもよいです。<br>
												<div class="blur">
													{!! $dummyMsg !!}
												</div>
											@endif
											</p>
											<div class="login">
												<a class="button" href="#" style="color:white;white-space:nowrap;">無料のユーザ登録をお願いします</a><br><br>
												<a class="button2" href="#" style="white-space:nowrap;">ログインはこちら</a>
											</div>
										</div>
									</div>

									<div class="ac-item">
										<p class="ac-header">
											<span style="width:200px;">リモート勤務</span><span class="star5_rating" style="--rate: 10%;"></span>
										</p>
										<div class="ac-txt">
											<p class="ac-detail">
											@if(Auth::check())
												{!! $dummyMsg !!}
											@else
												とてもよいです。<br>
												<div class="blur">
													{!! $dummyMsg !!}
												</div>
											@endif
											</p>
											<div class="login">
												<a class="button" href="#" style="color:white;white-space:nowrap;">無料のユーザ登録をお願いします</a><br><br>
												<a class="button2" href="#" style="white-space:nowrap;">ログインはこちら</a>
											</div>
										</div>
									</div>

									<div class="ac-item">
										<p class="ac-header">
											<span style="width:200px;">定年</span><span class="star5_rating" style="--rate: 35%;"></span>
										</p>
										<div class="ac-txt">
											<p class="ac-detail">
											@if(Auth::check())
												{!! $dummyMsg !!}
											@else
												とてもよいです。<br>
												<div class="blur">
													{!! $dummyMsg !!}
												</div>
											@endif
											</p>
											<div class="login">
												<a class="button" href="#" style="color:white;white-space:nowrap;">無料のユーザ登録をお願いします</a><br><br>
												<a class="button2" href="#" style="white-space:nowrap;">ログインはこちら</a>
											</div>
										</div>
									</div>

								</div><!--  ac-wrap -->
							</div><!-- item-inner -->
						</div><!-- item -->
					</div><!-- con-wrap -->


{{-------------------- END 後で削除 -----------------------------------------------}}


				</div><!-- inner -->
			</main>

@endsection
