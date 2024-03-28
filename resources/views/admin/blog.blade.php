@extends('layouts.admin.auth')

@section('content')

<head>
	<title>>ブログ｜{{ config('app.name', 'Laravel') }}</title>
</head>

	<div class="mainContentsInner-oneColumn">

		<div class="secTitle">
			<div class="title-main">
				<h2>ブログ</h2>
			</div><!-- /.mainTtl -->
		</div><!-- /.sec-title -->
               
		<div class="containerContents">
@if ( isset($blog->id) )
 <!--  修正  -->
			{{ Form::open(['url' => '/admin/blog/change', 'name' => 'changeform' , 'id' => 'changeform']) }}
			{{Form::hidden('blog_id', old('blog_id' ,$blog->id), ['class' => 'form-control', 'id'=>'blog_id' ] )}}
 			<section class="secContents-mb">
				<div class="secContentsInner">
                            
					<ul class="jobToggleList leftAlign">
						<li>
							<div class="button-radio">
								<input id="c_ch1" class="radiobutton" name="open_flag" type="radio" value="1"  @if (old('open_flag' ,$blog->open_flag) == '1')  checked="checked" @endif  onchange="checkOpen()"  />
								<label for="c_ch1">公開</label> /
								<input id="c_ch2" class="radiobutton" name="open_flag" type="radio" value="0"  @if (old('open_flag' ,$blog->open_flag) == '0')  checked="checked" @endif  onchange="checkOpen()" />
								<label for="c_ch2">非公開</label> 
							</div>
						</li>
						<li>
							<label id="del_lavel" for=""><span>削除する</span><input type="checkbox"  name="del_flag" id="del_flag" value="1"  onchange="clearMsg()"/></label>
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
						{{ html()->form('POST', '/admin/blog')->id('blogform')->attribute('name', "blogform{$blog->id}")->acceptsFiles()->open() }}
						{{ html()->hidden('blog_id', $blog->id) }}
						<div class="formContainer mg-ajust-midashi">
							<div class="item-name">
								<p>タイトル</p>
							</div><!-- /.item-name -->
							<div class="item-input">
								<input type="text" name="title" value="{{ old('title' ,$blog->title) }}">
							</div><!-- /.item-input -->
						</div>
						<div class="formContainer mg-ajust-midashi">
							@error('title')
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
								<p>カテゴリ</p>
							</div><!-- /.item-name -->
							<div class="item-input">
								<div class="selectWrap">
									<select name="cat_id"  class="select-no" onchange="this.form.submit()">
										@foreach ($blogCatList as $cat)
											<option value="{{ $cat->id }}" @if ( $blog->cat_id == $cat->id)  selected @endif>{{ $cat->name }}</option>
										@endforeach
									</select>
								</div><!-- /.selectWrap -->
							</div><!-- /.item-input -->
						</div>
						<div class="formContainer mg-ajust-midashi">
							@error('cat_id')
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
								<p>内容</p>
							</div><!-- /.item-name -->
							<div class="item-input">
								<textarea class="form-mt" name="content" id="" cols="30" rows="30" placeholder="本文" >{{ old('content' ,$blog->content) }}</textarea>
							</div><!-- /.item-input -->
						</div>
						<div class="formContainer mg-ajust-midashi">
							@error('content')
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
								<p>イメージ</p>
							</div><!-- /.item-name -->
							<div class="item-input">
								@if ( isset($blog->image) )
									<img src="{{ $blog->image }}" class="css-class" alt="image" width="250">
								@endif
{{--
								@if ( isset($blog->thumb) )
									<img src="{{ $blog->thumb }}" class="css-class" alt="image">
									<br>サムネイル
								@endif
--}}
							</div><!-- /.item-input -->
							<div class="item-input">
								{{ Form::file('image', ['class'=>'form-control']) }}
							</div>
							<div class="item-input">
								<p> ※jpg、png、500KB以内</p>
							</div>
						</div>
						<div class="formContainer mg-ajust-midashi">
							<div class="item-name">
								<p></p>
							</div><!-- /.item-name -->
							<div class="item-input">
								<ul class="oneRow">
								@error('image')
									<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
								@enderror
								</ul>
							</div>
						</div>


						<br>
						<div class="btnContainer">
							{{-- 更新成功メッセージ --}}
							@if (session('update_success'))
								<p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-blue-400 dark:text-blue-400" style="color: blue;">{{session('update_success')}}</p>
							@endif
							<a href="javascript:blogform{{ $blog->id }}.submit()" class="squareBtn btn-large">保存</a>
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
