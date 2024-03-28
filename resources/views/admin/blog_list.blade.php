@extends('layouts.admin.auth')

@section('content')

<head>
	<title>ブログ一覧｜{{ config('app.name', 'Laravel') }}</title>
</head>

	<div class="mainContentsInner-oneColumn">

		<div class="mainTtl title-main">
			<h2>ブログ一覧</h2>
		</div><!-- /.mainTtl -->
                
		<div class="containerContents">
                    
			<section class="secContents">
				<div class="secContentsInner">

					<div class="secBtnHead">
						<div class="secBtnHead-btn">
							<ul class="item-btn">
								<li><a href="/admin/blog" class="squareBtn">新規作成</a></li>
							</ul><!-- /.item -->
						</div><!-- /.secBtnHead-btn -->
					</div><!-- /.sec-btn -->

					<table class="tbl-blogList mb-ajust">
						<tr>
							<th>カテゴリ</th>
							<th>タイトル</th>
							<th>内容</th>
							<th>公開</th>
							<th></th>
						</tr>
						@foreach ($blogList as $blog)
						<tr>
							<td>{{ $blog->category }}</td>
							<td>{{ mb_strimwidth( $blog->title, 0, 50, "...") }}</td>
							<td>{{ mb_strimwidth( $blog->content, 0, 50, "...") }}</td>
							<td>@if ($blog->open_flag == '1')公開@endif</td>
							<td>
								<div class="btnContainer">
									{{ html()->form('GET', '/admin/blog')->attribute('name', "editform{$blog->id}")->open() }}
									{{ html()->hidden('blog_id', $blog->id) }}
									<a href="javascript:editform{{ $blog->id }}.submit()" class="squareBtn btn-large">編集</a>
									{{ html()->form()->close() }}
								</div><!-- /.btn-container -->
							</td>
						</tr>
						@endforeach
					</table>
 
				</div><!-- /.secContentsInner -->
			</section><!-- /.secContents -->
                   
		</div><!-- /.containerContents -->
	</div><!-- /.mainContentsInner -->
            

@endsection
