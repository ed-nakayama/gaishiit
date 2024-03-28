@extends('layouts.user.app')


@section('addheader')
    <title>パスワード変更 | {{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/job.css') }}" rel="stylesheet">
@endsection


@section('content')


            <main class="pane-main">
                <div class="inner">
                	<div class="ttl">
                      <h2>パスワード変更</h2>
                	</div>

                    <div class="con-wrap">

						<div class="item setting">
							<div class="item-inner">
								<div class="item-top" style="text-align: center;">


           							{{-- フォーム --}}
									<form method="post" name="regform"  action="{{route('user.password.reupdate')}}">
									@csrf
                        
									<input type="hidden" name="token" value="{{ $token }}">

									<div class="formContainer mg-ajust-midashi">
										<div class="item-name"></div>
										<div class="item-name"><p>メールアドレス</p></div>
										<div class="item-input">
											<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus  style="width:300px; height: 30px;font-size: 14px;">
 
	                                 		@error('email')
	                                    		<span class="invalid-feedback" role="alert" style="color:#ff0000;">
	                                        		{{ $message }}
	                                    		</span>
	                                		@enderror
	                      				</div><!-- /.item-input -->
	                    			</div>
    
	                   				<div class="formContainer mg-ajust-midashi">
	                       				<div class="item-name"></div>
	                       				<div class="item-name"><p>新しいパスワード</p></div>
	                       				<div class="item-input">
	                          				<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password"  style="width:300px; height: 30px;font-size: 14px;">
	                       				</div><!-- /.item-input -->
	                    			</div>

	                    			<div class="formContainer mg-ajust-midashi">
	                       				<div class="item-name"></div>
	                       				<div class="item-name"><p>新しいパスワード（確認用）</p></div>
	                       				<div class="item-input">
	                           				 <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password"  style="width:300px; height: 30px;font-size: 14px;">

	                                		@error('password')
	                                    		<span class="invalid-feedback" role="alert" style="color:#ff0000;">
	                                        		{{ $message }}
	                                    		</span>
	                                		@enderror
	                       				</div><!-- /.item-input -->
	                    			</div>


                        			<div class="button-flex">
                         				<a href="javascript:regform.submit()">パスワードリセット</a>
                    				</div>
									{{ html()->form()->close() }}
                    
                        		</div>
                        	</div>
                        </div>
                
                    </div>
                </div>
            </main>

@endsection