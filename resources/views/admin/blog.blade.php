@extends('layouts.admin.auth')

@section('content')

<head>
	<title>ブログ管理</title>
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
							<label><span>公開日</span>
							<input type="date" name="open_date" value="{{ old('open_date' ,$blog->open_date) }}" style="border: solid 1px"></label>
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
							<p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-blue-400 dark:text-blue-400" style="color: blue;">{{session('option_success')}}</p>
						@endif
						@error('open_date')
							<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
						@enderror
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
								<p>meta description</p>
							</div><!-- /.item-name -->
							<div class="item-input">
								<textarea class="form-mt" name="meta_desc" id="" cols="30" rows="2"  placeholder="[記事ディスクリプション文]">{{ old('meta_desc' ,$blog->meta_desc) }}</textarea>
							</div><!-- /.item-input -->
						</div>
						<div class="formContainer mg-ajust-midashi">
							@error('meta_desc')
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
											<option value="{{ $cat->id }}" @if (old('meta_desc' ,$blog->cat_id) == $cat->id)  selected @endif>{{ $cat->name }}</option>
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

						<div class="formContainer mg-ajust-midashi">
							<div class="item-name">
								<p>イメージ</p>
							</div><!-- /.item-name -->
							<div class="item-input">
								@if ( isset($blog->image) )
									<img src="{{ $blog->image }}" class="css-class" alt="" width="250">
								@endif
{{--
								@if ( isset($blog->thumb) )
									<img src="{{ $blog->thumb }}" class="css-class" alt="">
									<br>サムネイル
								@endif
--}}
							</div><!-- /.item-input -->
							<div class="item-input">
								{{ html()->file('image') }}
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

						<div class="formContainer al-item-none">
							<div class="item-name">
								<p>イントロ</p>
							</div><!-- /.item-name -->
							<div class="item-input">
								<textarea class="form-mt" name="intro" id="" cols="30" rows="5" placeholder="イントロ" >{{ old('intro' ,$blog->intro) }}</textarea>
							</div><!-- /.item-input -->
						</div>
						<div class="formContainer mg-ajust-midashi">
							@error('intro')
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
								<p>目次</p>
							</div><!-- /.item-name -->
							<div class="item-input">
								{!! e($blog->contents_table) !!}
							</div><!-- /.item-input -->
						</div>
						<div class="formContainer mg-ajust-midashi">
						</div>

						<div class="formContainer mg-ajust-midashi">
							<div class="item-name">
								<p>監修者</p>
							</div><!-- /.item-name -->
							<div class="item-input">
								<div class="selectWrap">
									<select name="supervisor"  class="selectWrap">
										@foreach ($superList as $super)
											<option value="{{ $super->id }}" @if (old('supervisor' ,$blog->supervisor) == $super->id)  selected @endif>{{$super->name }}</option>
										@endforeach
									</select>
								</div>
							</div><!-- /.item-input -->
						</div>
						<div class="formContainer mg-ajust-midashi">
						</div>

						<div id="content_box">
						@foreach ($blogContentList as $cont)
							{{ html()->hidden('idList[]' ,$cont->id) }}
							<div class="formContainer mg-ajust-midashi">
								<div class="item-name">
									<p>タグ</p>
								</div><!-- /.item-name -->
								<div class="selectWrap harf" style="width:120px;">
									<select name="tag[]"  class="select-no">
										<option value="99"></option>
										<option value="2" @if ($cont->tag == '2')  selected @endif>見出し２</option>
										<option value="3" @if ($cont->tag == '3')  selected @endif>見出し３</option>
										<option value="4" @if ($cont->tag == '4')  selected @endif>見出し４</option>
										<option value="5" @if ($cont->tag == '5')  selected @endif>表</option>
										<option value="6" @if ($cont->tag == '6')  selected @endif>テキスト</option>
									</select>
								</div><!-- /.item-input -->

								<div class="item-name">
									<p>見出し</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<input type="text" name="sub_title[]" value="{{ $cont->sub_title }}">
								</div><!-- /.item-input -->
							</div>

							<div class="formContainer al-item-none">
								<div class="item-name">
									<p>内容</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<textarea class="form-mt" name="content[]" id="" cols="30" rows="5" placeholder="本文" >{{ $cont->content }}</textarea>
								</div><!-- /.item-input -->
							</div>
							<br>
						@endforeach
</div>
	<a href="javascript:addForm()" class="squareBtn btn-short">項目追加</a>
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

function addForm() {
	// id属性で要素を取得
	let content_element = document.getElementById('content_box');

	let text =
		'{{ html()->hidden('idList[]') }}' +
		'<div class="formContainer mg-ajust-midashi">' +
		'<div class="item-name">' +
		'<p>タグ</p>' +
		'</div>' +
		'<div class="selectWrap harf" style="width:120px;">' +
		'<select name="tag[]"  class="select-no">' +
		'<option value="99"></option>' +
		'<option value="2">見出し２</option>' +
		'<option value="3">見出し３</option>' +
		'<option value="4">見出し４</option>' +
		'<option value="5">表</option>' +
		'<option value="6">テキスト</option>' +
		'</select>' +
		'</div>' +

		'<div class="item-name">' +
		'<p>見出し</p>' +
		'</div>' +
		'<div class="item-input">' +
		'<input type="text" name="sub_title[]" value="">' +
		'</div>' +
		'</div>' +

		'<div class="formContainer al-item-none">' +
		'<div class="item-name">' +
		'<p>内容</p>' +
		'</div>' +
		'<div class="item-input">' +
		'<textarea class="form-mt" name="content[]" id="" cols="30" rows="5" placeholder="本文" ></textarea>' +
		'</div>' +
		'</div>' +
		'<br>';
	
	content_element.insertAdjacentHTML('afterend', text);
}

</script>

@endsection
