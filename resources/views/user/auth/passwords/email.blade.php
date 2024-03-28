@extends('layouts.user.app')


@section('addheader')
    <title>パスワードリセット | {{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/department.css') }}" rel="stylesheet">
@endsection


@section('content')


			<div class="mainContentsInner">

				<div class="mainTtl title-main">
                        <h2>パスワードリセット</h2>
                </div><!-- /.mainTtl -->

                <div class="containerContents">

                    <section class="secContents">
                        <div class="secContentsInner">

							<form method="POST" name ="form1" action="{{ route('user.password.email') }}">
							@csrf

                                <div class="formContainer mg-ajust-midashi">
                                    <div class="item-name"></div>
                                    <div class="item-name"><p>メールアドレス</p></div>
                                    <div class="item-input">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus  style="width:300px; height: 30px;font-size: 14px;">

                               			@if (session('status'))
                                    		<div class="alert alert-success" role="alert" style="color:#0000ff;">
                                        		{{ session('status') }}
                                    		</div>
                                		@endif
                                		@error('email')
                                    		<span class="invalid-feedback" role="alert" style="color:#ff0000;">
                                        		{{ $message }}
                                    		</span>
                                		@enderror
                                     </div><!-- /.item-input -->
                                </div>

                       			<div class="btnContainer">
                                    <a href="javascript:form1.submit()" class="squareBtn btn-large">送信</a>
                        		</div><br>

							{{ html()->form()->close() }}

						</div><!-- /.secContentsInner -->
					</section><!-- /.secContents-mb -->
				</div><!-- /.containerContents -->
            </div><!-- /.mainContentsInner-oneColumn -->

@endsection
