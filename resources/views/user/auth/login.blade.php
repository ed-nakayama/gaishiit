@extends('layouts.user.auth')


@section('addheader')
	<title>ログイン｜{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/department.css') }}" rel="stylesheet">
@endsection


@section('content')


<style>

.pane-main {
  width: 100%;
  margin: 30px;
  flex-grow: 1;
}

.formbox {
  margin-top: 12px;
}
.formbox input[type="text"],
.formbox input[type="password"] {
  padding: 12px;
  font-size: 16px;
  border-radius: 5px;
  background-color: rgba(255,255,255,0.62);
  width: 40%;
  margin-top: 12px;
  margin-bottom: 12px;
}


.formbox input::placeholder {
  color: rgb(160, 160, 159);
}
.formbox ul > li + li {
  margin-top: 12px;
  margin: auto;
}
.formbox .btnarea {
  margin-top: 20px;
}
.formbox .btnarea .btn + .btn {
  margin-top: 12px;
}
.formbox .btnarea .btn > * {
  display: block;
  width: 170px;
  height: 35px;
  font-size: 15px;
  line-height: 35px;
  text-align: center;
  border: none;
  margin: auto;
}
.formbox .btnarea .btn-login > * {
  color: #fff;
  background-color: rgb(47, 46, 46);
}
.formbox .btnarea .btn-register > * {
  color: #fff;
  background-color: #E5AF24;
}
.formbox .forget {
  margin-top: 1em;
}
.formbox .forget a {
  color: rgb(14, 46, 71);
  font-size: 14px;
  text-decoration: underline;
}


@media (max-width: 767px) {

  .pane-main {
    width: 100%;
    margin: 0px;
  }

  .formbox input[type="text"],
  .formbox input[type="password"] {
    width: 80%;
  }
}

</style>


	<main class="pane-main">
		<div class="inner" style="text-align:center;">
			<div class="ttl">
				<h2>ログイン</h2>
			</div>

			<div class="con-wrap">
				<div class="item thumb">

					<div class="formbox">
						<form method="POST" name ="form1" action="{{ route('user.login') }}">
						@csrf
							<ul>
								<li><input type="text" name="email" value="{{ old('email') }}" placeholder="メールアドレス" required autocomplete="email" autofocus></li>
								<li>
									@error('email')
										<span class="invalid-feedback" role="alert" style="color:#ff0000;">
										{{ $message }}
										</span>
									@enderror
								</li>
								<li><input type="password" name="password" value="" placeholder="パスワード" required autocomplete="current-password"></li>
								<li>
									@error('password')
										<span class="invalid-feedback" role="alert" style="color:#ff0000;">
											{{ $message }}
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
					</div>

				</div>
			</div>

		</div><!-- inner -->
	</main>

@endsection
