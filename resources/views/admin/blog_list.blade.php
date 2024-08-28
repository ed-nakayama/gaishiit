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
@foreach ($errors->all() as $error)
	<li style="list-style:none; color:red;">{{ $error }}</li>
@endforeach

					<table class="tbl-blogList mb-ajust">
						<tr>
							<th>ブログID</th>
							<th>カテゴリ</th>
							<th>タイトル</th>
							<th>内容</th>
							<th>公開</th>
							<th>公開日</th>
							<th></th>
							<th></th>
						</tr>
						@foreach ($blogList as $blog)
						<tr>
							{{ html()->form('POST', '/admin/blog/list')->attribute('name', "updateform{$blog->id}")->open() }}
							{{ html()->hidden('blog_id', $blog->id) }}
							{{ html()->hidden('page', $blogList->currentPage()) }}
							<td>{{ $blog->id }}</td>
							<td>{{ $blog->getCatName() }}</td>
							<td>{{ mb_strimwidth( $blog->title, 0, 50, "...") }}</td>
							<td>{{ mb_strimwidth( $blog->content, 0, 50, "...") }}</td>
							<td>{{ html()->checkbox('open_flag' ,$blog->open_flag , '1') }}</td>
							<td>{{ html()->date('open_date' ,$blog->open_date) }}</td>
							<td>
								<div class="btnContainer">
									<a href="javascript:updateform{{ $blog->id }}.submit()" class="squareBtn btn-large">更新</a>
								</div><!-- /.btn-container -->
							</td>
							{{ html()->form()->close() }}
							<td>
								<div class="btnContainer">
									<a href="javascript:editform{{ $blog->id }}.submit()" class="squareBtn btn-large">編集</a>
								</div><!-- /.btn-container -->
							</td>
							{{ html()->form('GET', '/admin/blog')->attribute('name', "editform{$blog->id}")->open() }}
							{{ html()->hidden('blog_id', $blog->id) }}
							{{ html()->form()->close() }}
						</tr>
						@endforeach
					</table>
 	<div class="pager">
		{{ $blogList->appends(request()->query())->links('pagination.admin') }}
	</div>

				</div><!-- /.secContentsInner -->
			</section><!-- /.secContents -->
                   
		</div><!-- /.containerContents -->
	</div><!-- /.mainContentsInner -->
            

@endsection
