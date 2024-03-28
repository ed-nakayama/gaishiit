@extends('layouts.admin.auth')

@section('content')

<head>
    <title>お知らせ管理 | {{ config('app.name', 'Laravel') }}</title>
</head>

	<div class="mainContentsInner-oneColumn">

		<div class="secTitle">
			<div class="title-main">
				@if ( !isset($info->id) )
					<h2>お知らせ管理 - 新規作成</h2>
				@else
					 <h2>お知らせ管理 - 編集</h2>
				@endif
			</div><!-- /.mainTtl -->
		</div><!-- /.sec-title -->

		<div class="containerContents">
@if ( isset($info->id) )
 <!--  修正  -->
			{{ Form::open(['url' => '/admin/info/change', 'name' => 'changeform' , 'id' => 'changeform']) }}
			{{ Form::hidden('info_id', old('info_id' ,$info->id), ['class' => 'form-control', 'id'=>'info_id' ] )}}
			<section class="secContents-mb">
				<div class="secContentsInner">
                            
					<ul class="jobToggleList leftAlign">
						<li>
							<div class="button-radio">
								<input id="c_ch1" class="radiobutton" name="open_flag" type="radio" value="1"  @if (old('open_flag' ,$info->open_flag) == '1')  checked="checked" @endif  onchange="checkOpen()"  />
								<label for="c_ch1">公開</label> /
								<input id="c_ch2" class="radiobutton" name="open_flag" type="radio" value="0"  @if (old('open_flag' ,$info->open_flag) == '0')  checked="checked" @endif  onchange="checkOpen()" />
								<label for="c_ch2">非公開</label> 
							</div>
						</li>
						<li>
							<label id="del_lavel" for=""><span>削除する</span><input type="checkbox"  name="del_flag" id="del_flag" value="1"  @if (old('del_flag' ,$info->del_flag) == '1')  checked="checked" @endif  onchange="clearMsg()"/></label>
						</li>
							<a href="javascript:changeform.submit()" class="squareBtn btn-short">保存</a>
						<li>
							<div class="btnContainer">
							</div><!-- /.btn-container -->
						</li>
					</ul><!-- /.unitToggle -->

					<div class="btnContainer">
						{{-- 更新成功メッセージ --}}
						@if (session('option_success'))
							<div id="success1"  class="alert alert-success"  style="color:#0000ff;text-align: center;">
								{{session('option_success')}}
							</div>
						@endif
					</div><!-- /.btn-container -->
                            
				</div><!-- /.secContentsInner -->
			</section><!-- /.secContents-mb -->
			{{ html()->form()->close() }}
<!--  修正 END  -->
@endif
			<section class="secContents">
				<div class="secContentsInner">
				{{ Form::open(['url' => '/admin/info', 'name' => 'regform' , 'id' => 'regform']) }}
				{{ Form::hidden('info_id', old('info_id' ,$info->id), ['class' => 'form-control', 'id'=>'info_id' ] )}}
{{--
					<div class="formContainer mg-ajust-midashi">
						<div class="item-name">
							<p>タイトル<span>*</span></p>
						</div><!-- /.item-name -->
						<div class="item-input">
							<input type="text"  name="title"  value="{{ old('title' ,$info->title) }}" >
							<ul class="oneRow">
								@error('title')
									<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
								@enderror
							</ul>
						</div><!-- /.item-input -->
					</div>
--}}                                
					<div class="formContainer al-item-none mg-ajust">
						<div class="item-name">
							<p>メッセージ<span>*</span></p>
						</div><!-- /.item-name -->
						<div class="item-input">
							<textarea class="form-mt" name="content" id="" cols="30" rows="10" placeholder="本文" >{{ old('content' ,$info->content) }}</textarea>
							<ul class="oneRow">
								@error('content')
									<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
 								@enderror
							</ul>
						</div><!-- /.item-input -->
					</div>

					<div class="formContainer mg-ajust-midashi">
						<div class="item-name">
							<p>公開期限</p>
						</div><!-- /.item-name -->
						<div class="item-input">
							<label style="padding: 5px 5px;border: 1px solid #ccc;"><input type="date"  name="open_limit"  value="{{ old('open_limit' ,$info->open_limit) }}" ></label>
						</div><!-- /.item-input -->
					</div>                                
{{--
					<div class="formContainer mg-ajust">
						<div class="item-name">
							<p>公開範囲<span>*</span></p>
						</div><!-- /.item-name -->
						<div class="item-input">
							<ul class="radioList">
								<li><label><input type="radio" name="open_type" id="" value="0"  @if (old('open_type' ,$info->open_type) == 0)  checked="checked" @endif><span>すべて</span></label></li>
								<li><label><input type="radio" name="open_type" id="" value="1"  @if (old('open_type' ,$info->open_type) == 1)  checked="checked" @endif><span>ユーザのみ</span></label></li>
								<li><label><input type="radio" name="open_type" id="" value="2"  @if (old('open_type' ,$info->open_type) == 2)  checked="checked" @endif><span>企業のみ</span></label></li>
							</ul><!-- /.radioList -->
							<ul class="oneRow">
								@error('open_type')
									<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
								@enderror
							</ul>
						</div><!-- /.item-input -->
					</div>
--}}
					@if (auth()->user()->info_priv == '1')
						<div class="btnContainer">
							{{-- 更新成功メッセージ --}}
							@if (session('update_success'))
								<div id="success2"  class="alert alert-success"  style="color:#0000ff;">
							 		{{session('update_success')}}
								</div>
							@endif
 							<a href="javascript:regform.submit()" class="squareBtn btn-large">保存</a>
						</div><!-- /.btn-container -->
					@endif
					{{ html()->form()->close() }}
				</div><!-- /.secContentsInner -->
			</section><!-- /.secContents -->
                   
		</div><!-- /.containerContents -->
	</div><!-- /.mainContentsInner -->


<script>

/////////////////////////////////////////////////////////
// 成功メッセージクリア
/////////////////////////////////////////////////////////
function clearMsg() {

	const p1 = document.getElementById("success1");
	const p2 = document.getElementById("success2");

	if (p1) p1.style.display ="none";
	if (p2) p2.style.display ="none";

}


/////////////////////////////////////////////////////////
// 削除　表示／非表示
/////////////////////////////////////////////////////////
function delDisp() {

	var open_flag = document.getElementById("c_ch1");
	var del_flag = document.getElementById("del_flag");

	if (open_flag) {
		if (open_flag.checked) {
			del_flag.checked = false;
			del_lavel.style.display = "none";
		} else {
			del_lavel.style.display = "";
		}
	}
}


/////////////////////////////////////////////////////////
// 公開フラグチェック
/////////////////////////////////////////////////////////
function checkOpen() {

	clearMsg();
	delDisp();
}


/////////////////////////////////////////////////////////
// 初回起動
/////////////////////////////////////////////////////////
$(document).ready(function() {

	delDisp();

});

</script>


@endsection
