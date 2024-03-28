@extends('layouts.admin.auth')
<head>
    <title>メンバー管理 | {{ config('app.name', 'Laravel') }}</title>
</head>

@section('content')

            <div class="mainContentsInner-oneColumn">

                <div class="secTitle">
                    <div class="title-main">
					@if ( !isset($admin->id) )
                    	<h2>メンバー管理 - 新規作成</h2>
					@else
						<h2>メンバー管理 - 編集</h2>
					@endif
                    </div><!-- /.mainTtl -->
                </div><!-- /.sec-title -->
               
                <div class="containerContents">
                
					{{ html()->form('POST', '/admin/admin/register')->id('fregform')->attribute('name', 'regform')->open() }}
					{{ html()->hidden('admin_id', $admin->id) }}
                    <section class="secContents-mb">
                        <div class="secContentsInner">
                            
                            <div class="formContainer mg-ajust-midashi">
                                <div class="item-name">
                                    <p>氏名<span>*</span></p>
                                </div><!-- /.item-name -->
                                <div class="item-input">
                                    <input type="text" name="name" value="{{ old('name' ,$admin->name) }}">
                                    <ul class="oneRow">
                                    @error('name')
                                        <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                                    @enderror
                                    </ul>
                                </div><!-- /.item-input -->
                             </div>

                            <div class="formContainer mg-ajust-midashi">
                                <div class="item-name">
                                    <p>メールアドレス<span>*</span></p>
                                </div><!-- /.item-name -->
                                <div class="item-input">
									{{ html()->text('email', $admin->email) }}
                                    <ul class="oneRow">
                                    @error('email')
                                        <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                                    @enderror
                                    </ul>
                                </div><!-- /.item-input -->
                             </div>

                            <div class="formContainer mg-ajust-midashi">
                                <div class="item-name">
                                    <p>新規候補者承認権限</p>
                                </div><!-- /.item-name -->
                                <div class="item-input">
                                    <input type="checkbox" name="aprove_priv" value="1" @if (old('aprove_priv' ,$admin->aprove_priv) == '1')  checked="checked" @endif>
                                </div><!-- /.item-input -->
                             </div>

                            <div class="formContainer mg-ajust-midashi">
                                <div class="item-name">
                                    <p>企業管理権限</p>
                                </div><!-- /.item-name -->
                                <div class="item-input">
                                    <input type="checkbox" name="comp_priv" value="1" @if (old('comp_priv' ,$admin->comp_priv) == '1')  checked="checked" @endif>
                                </div><!-- /.item-input -->
                             </div>

                            <div class="formContainer mg-ajust-midashi">
                                <div class="item-name">
                                    <p>請求管理権限</p>
                                </div><!-- /.item-name -->
                                <div class="item-input">
                                    <input type="checkbox" name="bill_priv" value="1" value="1" @if (old('bill_priv' ,$admin->bill_priv) == '1')  checked="checked" @endif>
                                </div><!-- /.item-input -->
                             </div>

                            <div class="formContainer mg-ajust-midashi">
                                <div class="item-name">
                                    <p>設定変更権限</p>
                                </div><!-- /.item-name -->
                                <div class="item-input">
                                    <input type="checkbox" name="cat_priv" value="1" @if (old('cat_priv' ,$admin->cat_priv) == '1')  checked="checked" @endif>
                                </div><!-- /.item-input -->
                             </div>

                            <div class="formContainer mg-ajust-midashi">
                                <div class="item-name">
                                    <p>お知らせ管理権限</p>
                                </div><!-- /.item-name -->
                                <div class="item-input">
                                    <input type="checkbox" name="info_priv" value="1" @if (old('info_priv' ,$admin->info_priv) == '1')  checked="checked" @endif>
                                </div><!-- /.item-input -->
                             </div>
                             
                             <div class="formContainer mg-ajust-midashi">
                                <div class="item-name">
                                    <p>ピックアップ管理権限</p>
                                </div><!-- /.item-name -->
                                <div class="item-input">
                                    <input type="checkbox" name="pickup_priv" value="1" @if (old('pickup_priv' ,$admin->pickup_priv) == '1')  checked="checked" @endif>
                                </div><!-- /.item-input -->
                             </div>

                             <div class="formContainer mg-ajust-midashi">
                                <div class="item-name">
                                    <p>メンバー管理権限</p>
                                </div><!-- /.item-name -->
                                <div class="item-input">
                                    <input type="checkbox" name="account_priv" value="1" @if (old('account_priv' ,$admin->account_priv) == '1')  checked="checked" @endif>
                                </div><!-- /.item-input -->
                             </div>

                             <div class="formContainer mg-ajust-midashi">
                                <div class="item-name">
                                    <p>クチコミ承認</p>
                                </div><!-- /.item-name -->
                                <div class="item-input">
                                    <input type="checkbox" name="eval_priv" value="1" @if (old('eval_priv' ,$admin->eval_priv) == '1')  checked="checked" @endif>
                                </div><!-- /.item-input -->
                             </div>

							@if (auth()->user()->account_priv == '1')
                                <div class="btnContainer">
                                    <a href="javascript:regform.submit()" class="squareBtn btn-large">保存</a>
                                </div><!-- /.btn-container -->
                            @endif
                                
                        </div><!-- /.secContentsInner -->
                    </section><!-- /.secContents -->
					{{ html()->form()->close() }}
                    
                </div><!-- /.containerContents -->

            </div><!-- /.mainContentsInner -->



@endsection
