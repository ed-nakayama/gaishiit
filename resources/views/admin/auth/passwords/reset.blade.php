@extends('layouts.admin.app')
<head>
    <title>パスワードリセット | {{ config('app.name', 'Laravel') }}</title>
</head>

@section('content')

			<div class="mainContentsInner">

				<div class="mainTtl title-main">
					<h2>{{ __('Change Password') }}</h2>
                </div><!-- /.mainTtl -->

                <div class="containerContents">
                    
                    <section class="secContents">
                        <div class="secContentsInner">


               				{{-- フォーム --}}
                    		<form method="post" name="regform"  action="{{route('admin.password.reupdate')}}">
                    		@csrf
                        
							<input type="hidden" name="token" value="{{ $token }}">

                    			<div class="formContainer mg-ajust-midashi">
                       				<div class="item-name"></div>
                       				<div class="item-name"><p>{{ __('E-Mail Address') }}</p></div>
                       				<div class="item-input">
                          				<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
 
                                 		@error('email')
                                    		<span class="invalid-feedback" role="alert" style="color:#ff0000;">
                                        		<strong>{{ $message }}</strong>
                                    		</span>
                                		@enderror
                      				</div><!-- /.item-input -->
                    			</div>
    
                   				<div class="formContainer mg-ajust-midashi">
                       				<div class="item-name"></div>
                       				<div class="item-name"><p>{{ __('New Password') }}</p></div>
                       				<div class="item-input">
                          				<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                       				</div><!-- /.item-input -->
                    			</div>

                    			<div class="formContainer mg-ajust-midashi">
                       				<div class="item-name"></div>
                       				<div class="item-name"><p>{!! nl2br(__('Confirm Password')) !!}</p></div>
                       				<div class="item-input">
                           				 <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">

                                		@error('password')
                                    		<span class="invalid-feedback" role="alert" style="color:#ff0000;">
                                        		<strong>{{ $message }}</strong>
                                    		</span>
                                		@enderror
                       				</div><!-- /.item-input -->
                    			</div>


                    			<div class="btnContainer">
                         			<a href="javascript:regform.submit()" class="squareBtn btn-large">{{ __('Reset Password') }}</a>
                    			</div>

							{{ html()->form()->close() }}
                    
                        </div><!-- /.secContentsInner -->
                    </section><!-- /.secContents -->
            
        </div>
    </div>
</div>

@endsection