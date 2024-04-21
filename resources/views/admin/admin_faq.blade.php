@extends('layouts.admin.auth')

@section('content')

<head>
	<title>>FAQ｜{{ config('app.name', 'Laravel') }}</title>
</head>

	<div class="mainContentsInner-oneColumn">

		<div class="secTitle">
			<div class="title-main">
				<h2>FAQ</h2>
			</div><!-- /.mainTtl -->
		</div><!-- /.sec-title -->
               
		<div class="containerContents">
@if ( isset($faq->id) )
 <!--  修正  -->
			{{ html()->form('POST', '/admin/faq/change')->id('changeform')->attribute('name', 'changeform')->open() }}
			{{ html()->hidden('faq_id' ,old('faq_id' ,$faq->id)) }}
 			<section class="secContents-mb">
				<div class="secContentsInner">
                            
					<ul class="jobToggleList leftAlign">
						<li>
							<div class="button-radio">
								<input id="c_ch1" class="radiobutton" name="open_flag" type="radio" value="1"  @if (old('open_flag' ,$faq->open_flag) == '1')  checked="checked" @endif  onchange="checkOpen()"  />
								<label for="c_ch1">公開</label> /
								<input id="c_ch2" class="radiobutton" name="open_flag" type="radio" value="0"  @if (old('open_flag' ,$faq->open_flag) == '0')  checked="checked" @endif  onchange="checkOpen()" />
								<label for="c_ch2">非公開</label> 
							</div>
						</li>
						<li>
							<label id="del_lavel" for=""><span>削除する</span><input type="checkbox"  name="del_flag" id="del_flag" value="1"  @if (old('del_flag' ,$faq->del_flag) == '1')  checked="checked" @endif  onchange="clearMsg()"/></label>
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

			<section class="secContents-mb">
				<div class="secContentsInner">
	
					<div class="formContainer-add ajust">
						{{ html()->form('POST', '/admin/faq')->id('faqform')->attribute('name', "faqform{$faq->id}")->open() }}
						{{ html()->hidden('faq_id', $faq->id) }}
						<div class="formContainer mg-ajust-midashi">
							<div class="item-name">
								<p>質問</p>
							</div><!-- /.item-name -->
							<div class="item-input">
								<input type="text" name="question" value="{{ old('question' ,$faq->question) }}">
							</div><!-- /.item-input -->
						</div>
						<div class="formContainer mg-ajust-midashi">
							@error('question')
								<div class="item-name">
									<p></p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
								</div><!-- /.item-input -->
							@enderror
						</div>

						<div class="formContainer mg-ajust-midashi">
							<div class="item-name">
								<p>回答</p>
							</div><!-- /.item-name -->
							<div class="item-input">
								<input type="text" name="answer" value="{{ old('answer' ,$faq->answer) }}">
							</div><!-- /.item-input -->
						</div>
						<div class="formContainer mg-ajust-midashi">
							@error('answer')
								<div class="item-name">
									<p></p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
								</div><!-- /.item-input -->
							@enderror
						</div>
								
						<div class="formContainer al-item-none">
							<div class="item-name">
								<p>説明</p>
							</div><!-- /.item-name -->
							<div class="item-input">
								<textarea class="form-mt" name="exp" id="" cols="30" rows="10" placeholder="本文" >{{ old('exp' ,$faq->exp) }}</textarea>
							</div><!-- /.item-input -->
						</div>
						<div class="formContainer mg-ajust-midashi">
							@error('exp')
								<div class="item-name">
									<p></p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
								</div><!-- /.item-input -->
							@enderror
						</div>
						<br>
						<div class="btnContainer">
							{{-- 更新成功メッセージ --}}
							@if (session('update_success'))
								<div id="success2"  class="alert alert-success"  style="color:#0000ff;">
							 		{{session('update_success')}}
								</div>
							@endif
							<a href="javascript:faqform{{ $faq->id }}.submit()" class="squareBtn btn-large">保存</a>
						</div><!-- /.btn-container -->
						{{ html()->form()->close() }}
					</div>

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
