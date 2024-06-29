@extends('layouts.user.app')

@section('content')

<head>
    <title>パスワードリセット | {{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/job.css') }}" rel="stylesheet">
</head>

            <main class="pane-main">
                <div class="inner">
                	<div class="ttl">
                      <h2>パスワードリセット</h2>
                	</div>

                    <div class="con-wrap">

						<div class="item setting">
							<div class="item-inner">
								<div class="item-top" style="text-align: center;">

                    				<form method="POST" name ="form1"  action="{{ route('user.password.email') }}">
                        			@csrf

                            		<div class="item-name">
                                		<p>メールアドレス</p>
                            		</div><!-- /.formTitle -->

                            		<div class="formBody">
                                		<ul class="twoRow">
                                    		<li><input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus  style="width:300px; height: 30px;font-size: 14px;"></li>
                                		</ul>
                                		@if (session('status'))
                                    		<div class="alert alert-success" role="alert" style="color:#0000ff;">
                                        		{{ session('status') }}
                                    		</div>
                                		@endif

                                		@error('email')
                                    		<span class="invalid-feedback" role="alert" style="color:#ff0000;">
                                        		<strong>{{ $message }}</strong>
                                    		</span>
                                		@enderror
                            		</div>
                            		
                        			<div class="button-flex">
                                		<a href="javascript:form1.submit()">{{ __('Send Password Reset Link') }}</a><br>
                        			</div><!-- /.formBtn -->
                   					</form>
 
                        		</div>
                        	</div>
                        </div>
                
                    </div>
                </div>
            </main>

@endsection
