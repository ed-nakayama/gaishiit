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
                                    <input type="checkbox" id="aprove_priv" name="aprove_priv" value="1" @if (old('aprove_priv' ,$admin->aprove_priv) == '1')  checked="checked" @endif  onchange="foo2();">
                                </div><!-- /.item-input -->
                             </div>

                            <div class="formContainer mg-ajust-midashi">
                                <div class="item-name">
                                    <p>企業管理権限</p>
                                </div><!-- /.item-name -->
                                <div class="item-input">
                                    <input type="checkbox" id="comp_priv" name="comp_priv" value="1" @if (old('comp_priv' ,$admin->comp_priv) == '1')  checked="checked" @endif  onchange="foo2();">
                                </div><!-- /.item-input -->
                             </div>

                            <div class="formContainer mg-ajust-midashi">
                                <div class="item-name">
                                    <p>請求管理権限</p>
                                </div><!-- /.item-name -->
                                <div class="item-input">
                                    <input type="checkbox" id="bill_priv" name="bill_priv" value="1" value="1" @if (old('bill_priv' ,$admin->bill_priv) == '1')  checked="checked" @endif  onchange="foo2();">
                                </div><!-- /.item-input -->
                             </div>

                            <div class="formContainer mg-ajust-midashi">
                                <div class="item-name">
                                    <p>設定変更権限</p>
                                </div><!-- /.item-name -->
                                <div class="item-input">
                                    <input type="checkbox" id="cat_priv" name="cat_priv" value="1" @if (old('cat_priv' ,$admin->cat_priv) == '1')  checked="checked" @endif  onchange="foo2();">
                                </div><!-- /.item-input -->
                             </div>

                            <div class="formContainer mg-ajust-midashi">
                                <div class="item-name">
                                    <p>お知らせ管理権限</p>
                                </div><!-- /.item-name -->
                                <div class="item-input">
                                    <input type="checkbox" id="info_priv" name="info_priv" value="1" @if (old('info_priv' ,$admin->info_priv) == '1')  checked="checked" @endif  onchange="foo2();">
                                </div><!-- /.item-input -->
                             </div>
                             
                             <div class="formContainer mg-ajust-midashi">
                                <div class="item-name">
                                    <p>ピックアップ管理権限</p>
                                </div><!-- /.item-name -->
                                <div class="item-input">
                                    <input type="checkbox" id="pickup_priv" name="pickup_priv" value="1" @if (old('pickup_priv' ,$admin->pickup_priv) == '1')  checked="checked" @endif  onchange="foo2();">
                                </div><!-- /.item-input -->
                             </div>

                             <div class="formContainer mg-ajust-midashi">
                                <div class="item-name">
                                    <p>メンバー管理権限</p>
                                </div><!-- /.item-name -->
                                <div class="item-input">
                                    <input type="checkbox" id="account_priv" name="account_priv" value="1" @if (old('account_priv' ,$admin->account_priv) == '1')  checked="checked" @endif  onchange="foo2();">
                                </div><!-- /.item-input -->
                             </div>

                             <div class="formContainer mg-ajust-midashi">
                                <div class="item-name">
                                    <p>クチコミ承認</p>
                                </div><!-- /.item-name -->
                                <div class="item-input">
                                    <input type="checkbox" id="eval_priv" name="eval_priv" value="1" @if (old('eval_priv' ,$admin->eval_priv) == '1')  checked="checked" @endif  onchange="foo2();">
                                </div><!-- /.item-input -->
                             </div>

                             <div class="formContainer mg-ajust-midashi">
                                <div class="item-name">
                                    <p>エージェント機能のみ</p>
                                </div><!-- /.item-name -->
                                <div class="item-input">
                                    <input type="checkbox" id="agent_priv" name="agent_priv" value="1" @if (old('agent_priv' ,$admin->agent_priv) == '1')  checked="checked" @endif  onchange="foo();">
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


<script>
function foo() {

let aprove_priv  = document.getElementById('aprove_priv');
let comp_priv    = document.getElementById('comp_priv');
let bill_priv    = document.getElementById('bill_priv');
let cat_priv     = document.getElementById('cat_priv');
let info_priv    = document.getElementById('info_priv');
let pickup_priv  = document.getElementById('pickup_priv');
let account_priv = document.getElementById('account_priv');
let eval_priv    = document.getElementById('eval_priv');
let agent_priv   = document.getElementById('agent_priv');


	if (agent_priv.checked) {
		aprove_priv.checked  = false;
		comp_priv.checked    = false;
		bill_priv.checked    = false;
		cat_priv.checked     = false;
		info_priv.checked    = false;
		pickup_priv.checked  = false;
		account_priv.checked = false;
		eval_priv.checked    = false;
	}
}


function foo2() {

let aprove_priv  = document.getElementById('aprove_priv');
let comp_priv    = document.getElementById('comp_priv');
let bill_priv    = document.getElementById('bill_priv');
let cat_priv     = document.getElementById('cat_priv');
let info_priv    = document.getElementById('info_priv');
let pickup_priv  = document.getElementById('pickup_priv');
let account_priv = document.getElementById('account_priv');
let eval_priv    = document.getElementById('eval_priv');
let agent_priv   = document.getElementById('agent_priv');


	if (aprove_priv.checked || comp_priv.checked || bill_priv.checked || cat_priv.checked || info_priv.checked || pickup_priv.checked || account_priv.checked || eval_priv.checked) {
		agent_priv.checked  = false;
	}

}
</script>

@endsection
