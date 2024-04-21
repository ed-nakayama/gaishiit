@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('blog_detail', $blog) }}
@endsection

@section('addheader')
	<title>{{ $blog->title }}｜{{ config('app.title') }}</title>
	<meta name="description" content="{{ $blog->meta_desc }}｜{{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="{{ $blog->title }}｜{{ config('app.title') }}" />
	<meta property="og:description" content="{{ $blog->meta_desc }}｜{{ config('app.description') }}" />
@if (!empty($blog->thumb))
	<meta property="og:image" content="{{ url($blog->thumb) }}" />
@else
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />
@endif
	<link rel="stylesheet" href="{{ asset('css/blog.css') }}">
	<link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/new_common.css') }}">
	<link href="{{ asset('css/expand.css') }}" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">

@endsection


@section('content')

@auth
@include('user.user_activity')
@endauth


<main class="pane-main" style="line-height:1.8;">
	<section id="blog" class="items">
		<div class="blog_block">
			<div class="blog_article">
				 <div class="article_ttl">
					 <h1>{{ $blog->title }}</h1>
					 <figure>@if (!empty($blog->image))<img src="{{ $blog->image }}" alt="">@endif</figure>
				 </div>
				 <div class="article_date">
					 <p class="timestamp">公開：{{ str_replace('-', '.', $blog->open_date) }}</p>
					 <p class="timestamp">最終更新：{{ str_replace('-', '.', substr($blog->updated_at, 0, 10)) }}</p>
					 <p class="tag">{{ $blog->getCatName() }}</p>
				 </div>
				 <p>{!! nl2br($blog->intro) !!}</p>
				 <div class="article_index">
					 <h2 class="index_ttl">目次</h2>
					 <div class="index_content" style="display: block;">
					 	{!! $blog->contents_table !!}
					 </div>
				 </div>

@php
	$super = $blog->getSuper();
@endphp
				@if (!empty($super))
					 <div class="supervision">
						 <figure>
							 <img src="{{ $super->image }}" alt="">
						 </figure>
						 <div class="supervision_name">
							 <p class="tag">監修</p>
							 <p>{{ $super->name }}</p>
						 </div>
						 <div class="supervision_info">
							 <p>
								 {!! nl2br($super->content) !!}
							 </p>
						 </div>
					 </div>
				 @endif
					 
				 {!! nl2br($blog->content) !!}

				<h2>関連記事</h2>
				<div class="related-articles">
					@foreach ($blogList as $blog)
						<div class="related_block">
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

@endsection
