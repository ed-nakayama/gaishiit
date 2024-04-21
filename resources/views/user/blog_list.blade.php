@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('blog_list', $blogCat) }}
@endsection

@section('addheader')
	<title>{{ $blogCat->name }}記事一覧｜外資IT企業のクチコミ・評価・求人なら外資IT.com</title>
	<meta name="description" content="外資IT企業への転職に関する{{ $blogCat->name }}カテゴリのお役立ち情報を発信しております。外資IT企業への転職なら外資IT.com｜外資IT.comは外資系IT企業に特化した口コミ・求人サイトです。興味のある企業の担当者とは直接コミュニケーションも可能です。">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="{{ $blogCat->name }}記事一覧｜外資IT企業のクチコミ・評価・求人なら外資IT.com" />
	<meta property="og:description" content="外資IT企業への転職に関する{{ $blogCat->name }}カテゴリのお役立ち情報を発信しております。外資IT企業への転職なら外資IT.com｜外資IT.comは外資系IT企業に特化した口コミ・求人サイトです。興味のある企業の担当者とは直接コミュニケーションも可能です。" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

	<link rel="stylesheet" href="{{ asset('css/blog.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/new_common.css') }}">
	<link href="{{ asset('css/expand.css') }}" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
@endsection


@section('content')


@if (Auth::guard('user')->check())
@include('user.user_activity')
@endif

<main class="pane-main">
	<div class="main" style="padding:15px;">
		<h1>{{ $blogCat->name }}の記事一覧</h1>
	</div>
		
	<section id="blog" class="items">
		<div class="blog_block">
			<div class="blog_main">
				<h2>記事一覧</h2>
				<div class="content">
					@foreach ($blogList as $blog)
					<div class="content_block">
						<a href="/blog/{{ $blog->cat_id }}/{{ $blog->id }}">
							<figure>
								@if (!empty($blog->thumb))
									<img src="{{ $blog->thumb }}" alt="">
								@else
									<img src="/storage/blog/thumb/h_logo.png" alt="">
								@endif
							</figure>
							<p class="blog_ttl">{{ $blog->title }}</p>
							<div class="blog_info">
								<p class="tag">{{ $blog->getCatName() }}</p>
								<p class="date">{{ str_replace('-', '.', $blog->open_date) }}</p>
							</div>
						</a>
					</div>
					@endforeach
				</div>
				<div class="pager">
					{{ $blogList->links('pagination.user') }}
				</div>
			</div>

			<div class="blog_menu">
				<div class="item">
					<h2>カテゴリ</h2>
					<div class="category">
						@foreach ($blogCatLlist as $blogCat)
							<p><a href="/blog/{{ $blogCat->id }}">{{ $blogCat->name }}</a></p>
						@endforeach
					</div>
				</div>
				<div class="item">
					<h2>人気記事ランキング</h2>
					<div class="popular-articles">
						@foreach ($blogRankList as $blog)
							<div class="popular-article-item">
								<p class="article-rank">{{ $loop->index + 1 }}</p>
								<figure style="width:45%;">
									<a href="/blog/{{ $blog->cat_id }}/{{ $blog->id }}">
										@if (!empty($blog->thumb))
											<img src="{{ $blog->thumb }}" alt="">
										@else
											<img src="/storage/blog/thumb/h_logo.png" alt="">
										@endif
									</a>
								</figure>
								<p class="article-ttl" style="width:55%;"><a href="/blog/{{ $blog->cat_id }}/{{ $blog->id }}">{{ $blog->title }}</a></p>
							</div>
						@endforeach
					</div>
				</div>
				<div class="item">
					<h2>企業クチコミ数ランキング</h2>
					
					@foreach ($rankingList as $ranking)
						<div class="ranking">
							<div class="corp">
								<figure><a href="/company/{{ $ranking->company_id }}"><img src="{{ $ranking->logo_file }}" alt=""></a></figure>
								<div class="inner">
									<p class="corp-name"><a href="/company/{{ $ranking->company_id }}">{{ $ranking->company_name }}</a></p>
									<div class="corp-rate">
										<p class="star5_rating" style="--rate: {{ $ranking->total_rate . '%' }};"></p>
										<p>総合評価：{{ number_format($ranking->total_point, 2) }}</p>
										<p>クチコミ数：{{ number_format($ranking->answer_count) }}</p>
										<p class="corp-button"><a href="/company/{{ $ranking->company_id }}">詳細</a></p>
									</div>
								</div>
							</div>
						</div>
					@endforeach
				</div>

				<div class="con-wrap">
					<div class="expand-button-flex">
						<a href="/company/ranking" style="font-size: 1.6rem;">クチコミ企業ランキングへ</a>
					</div>
				</div>
			</div>
		</div>
	</section>

</div>

@endsection
