@extends('layouts.admin.app')
<head>
    <title>パスワード変更 | {{ config('app.name', 'Laravel') }}</title>
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
                    		<form method="post" name="regform"  action="{{route('admin.password.update')}}">
                    		@csrf
                        
    
                    			<div class="formContainer mg-ajust-midashi">
                       				<div class="item-name"></div>
                       				<div class="item-name"><p>{{ __('Current Password') }}</p></div>
                       				<div class="item-input">
                          				<input id="current" type="password" class="form-control" name="current-password" required autofocus>
                       				</div><!-- /.item-input -->
                    			</div>
    
                   				<div class="formContainer mg-ajust-midashi">
                       				<div class="item-name"></div>
                       				<div class="item-name"><p>{{ __('New Password') }}</p></div>
                       				<div class="item-input">
                          				<input id="password" type="password" class="form-control" name="new-password" required>
                       				</div><!-- /.item-input -->
                    			</div>

                    			<div class="formContainer mg-ajust-midashi">
                       				<div class="item-name"></div>
                       				<div class="item-name"><p>{!! nl2br(__('Confirm Password')) !!}</p></div>
                       				<div class="item-input">
                           				<input id="confirm" type="password" class="form-control" name="new-password_confirmation" required>
                       				</div><!-- /.item-input -->
                    			</div>

                    			<div class="formContainer mg-ajust-midashi">
                       				<div class="item-name"></div>
                       				<div class="item-input">
                						{{-- エラーメッセージ --}}
                						@if(count($errors) > 0)
                							<div class="container mt-2">
                  								<div class="alert alert-danger" style="color:#ff0000;">
                   									<ul class="oneRow">
                       									@foreach ($errors->all() as $error)
                       										<li>{{ $error }}</li>
                       									@endforeach
                    								</ul>
                   								</div>
                							</div>
                						@endif

                						{{-- 更新成功メッセージ --}}
                						@if (session('update_password_success'))
                							<div class="container mt-2">
                    							<div class="alert alert-success" style="color:#0000ff;">
                        							{{session('update_password_success')}}
                    							</div>
                							</div>
                						@endif
                       				</div><!-- /.item-input -->
                    			</div>

                    			<div class="btnContainer">
                         			<a href="javascript:regform.submit()" class="squareBtn btn-large">{{ __('Change Password') }}</a>
                    			</div>

							{{ html()->form()->close() }}
                    
                        </div><!-- /.secContentsInner -->
                    </section><!-- /.secContents -->
            
        </div>
    </div>
</div>

@endsection