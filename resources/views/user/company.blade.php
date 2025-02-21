@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('comp_list') }}
@endsection


@section('addheader')
	<title>企業を探す｜{{ config('app.title') }}</title>
	<meta name="description" content="クチコミのある外資IT・外資コンサル企業の一覧ページです。転職に役立つ社員クチコミを集め、スコアを集計して提供しております。｜{{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="企業を探す｜{{ config('app.title') }}" />
	<meta property="og:description" content="クチコミのある外資IT・外資コンサル企業の一覧ページです。転職に役立つ社員クチコミを集め、スコアを集計して提供しております。｜{{ config('app.description') }}" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

	<link href="{{ asset('css/seek.css') }}" rel="stylesheet">
@endsection


@section('content')


@if (Auth::guard('user')->check())
@include('user.user_activity')
@endif

<main class="pane-main">
	<div class="inner">
		<div class="ttl">
			<h1>外資IT・外資コンサル企業一覧</h1>
		</div>
				
{{-- 広告エリア --}}
@isset ($pickup)
@php

	$ranking = App\Models\Ranking::find( $pickup->id);
	$total_rate = $ranking->total_rate;
	$total_point = $ranking->total_point;

@endphp

		<div class="con-wrap">
			<h2>PICK UP企業</h2>
			<div class="con-wrap">
				<ol class="company-list">
					<li class="company-list__item company-item -pickup">
						<figure class="company-item__image">
							@if (!empty($pickup->logo_file))
								<img src="{{$pickup->logo_file }}" alt="">
							@endif
						</figure>
						<div class="company-item__content">
							<p class="company-item__name"><a href="/company/{{  $pickup->id }}">{{ $pickup->name }}</a></p>
							<dl class="company-item__reviews">
								<dt>総合評価</dt>
								<dd>
									<span>{{ number_format($total_point, 2) }}</span>
									<span class="star5_rating" style="--rate:  {{ $total_rate . '%' }};"></span>
								</dd>
								<dt>口コミ件数</dt>
								<dd>{{ number_format($ranking->answer_count) }} 件</dd>
							</dl>
							<p class="company-item__button"><a href="/company/{{ $pickup->id }}">詳細を見る</a></p>
						</div>
					</li>
				</ol>
			</div><!-- con-wrap -->
		</div><!-- con-wrap -->
@endisset
{{-- END 広告エリア --}}
			   
		<div class="pager sort_name">
			<h2>名称で探す</h2>
			<ul class="page">
				<li class="page__numbers"><a class="openModalSeek button-modal" href="#cate-a">A / B / C / D / E / F / G</a></li>
				<li class="page__numbers"><a class="openModalSeek button-modal" href="#cate-h">H / I / J / K / L / M / N</a></li>
				<li class="page__numbers"><a class="openModalSeek button-modal" href="#cate-o">O / P / Q / R / S / T / U</a></li>
				<li class="page__numbers"><a class="openModalSeek button-modal" href="#cate-v">V / W / X / Y / Z</a></li>
			</ul>

{{--  ****************************************** --}}

			<h2>フリーワード検索</h2>
			<div class="con-wrap">
				<div class="search-job">
					{{ html()->form('POST', '/company')->attribute('name', 'compform')->open() }}
					<div style="text-align: center;">{{ html()->text('freeword', $freeword)->class('search-job__input') }}</div>

					<p class="search-job__submit form-button" style=" justify-content: space-around;">
						<a href="javascript:compform.submit()">検索する</a>
					</p>
				</div><!-- search-job -->
				{{ html()->form()->close() }}
			</div><!-- con-wrap -->

{{--  ****************************************** --}}


		</div>

		<div class="con-wrap">
			<ol class="company-list">
				@foreach ($compList as $comp)
					@include ('user/partials/comp_format')
				@endforeach
              </ol>

			<div class="pager">
				{{ $compList->links('pagination.user') }}
			</div>
		</div>
<br>
		{{-- クチコミ数ランキング --}}
			@include ('user/partials/eval_ranking_fix')
		{{-- END クチコミ数ランキング --}}

		{{-- ピックアップ求人 --}}
		@include ('user/partials/job_pickup')
		{{-- END ピックアップ求人 --}}

		{{-- 3種 求人検索 --}}
		@include ('user/partials/job_search_3type')
		{{-- END 3種 求人検索ボタン --}}

	</div>
</main>

{{-- 企業選択モーダル  --}}
	<div id="modalArea" class="modalArea">
		<div id="modalBg" class="modalBg"></div>
		<div class="modalWrapper">
			<div class="modalContents">
				<h1>企業名で絞り込む</h1>
			</div>
			<div id="closeModal" class="closeModal">
				×
			</div>
		</div>
	</div>

	<div id="modalAreaSeek" class="modalAreaSeek">
		<div id="modalBg" class="modalBg"></div>
		<div class="modalWrapper">
		  <div class="modalContents">
			<h3>企業名を選ぶ</h3>    
				<div class="pager sort_name">
					<ul class="page">
						<li class="page__numbers"><a class="openModalSeek button-modal" href="#cate-a">A - G</a></li>
						<li class="page__numbers"><a class="openModalSeek button-modal" href="#cate-h">H - N</a></li>
						<li class="page__numbers"><a class="openModalSeek button-modal" href="#cate-o">O - U</a></li>
						<li class="page__numbers"><a class="openModalSeek button-modal" href="#cate-v">V - Z</a></li>
					</ul>
				</div>
	
				<div id="cate-a" class="block">
					<p class="block-ttl">A</p>
					<ul class="cate-list">
						@foreach ($comp_A as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>
	
				<div id="cate-b" class="block">
					<p class="block-ttl">B</p>
					<ul class="cate-list">
						@foreach ($comp_B as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>
	
				<div id="cate-c" class="block">
					<p class="block-ttl">C</p>
					<ul class="cate-list">
						@foreach ($comp_C as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-d" class="block">
					<p class="block-ttl">D</p>
					<ul class="cate-list">
						@foreach ($comp_D as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-e" class="block">
					<p class="block-ttl">E</p>
					<ul class="cate-list">
						@foreach ($comp_E as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-f" class="block">
					<p class="block-ttl">F</p>
					<ul class="cate-list">
						@foreach ($comp_F as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-g" class="block">
					<p class="block-ttl">G</p>
					<ul class="cate-list">
						@foreach ($comp_G as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-h" class="block">
					<p class="block-ttl">H</p>
					<ul class="cate-list">
						@foreach ($comp_H as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-i" class="block">
					<p class="block-ttl">I</p>
					<ul class="cate-list">
						@foreach ($comp_I as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-j" class="block">
					<p class="block-ttl">J</p>
					<ul class="cate-list">
						@foreach ($comp_J as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-k" class="block">
					<p class="block-ttl">K</p>
					<ul class="cate-list">
						@foreach ($comp_K as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-l" class="block">
					<p class="block-ttl">L</p>
					<ul class="cate-list">
						@foreach ($comp_L as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-m" class="block">
					<p class="block-ttl">M</p>
					<ul class="cate-list">
						@foreach ($comp_M as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-n" class="block">
					<p class="block-ttl">N</p>
					<ul class="cate-list">
						@foreach ($comp_N as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-o" class="block">
					<p class="block-ttl">O</p>
					<ul class="cate-list">
						@foreach ($comp_O as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-p" class="block">
					<p class="block-ttl">P</p>
					<ul class="cate-list">
						@foreach ($comp_P as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-q" class="block">
					<p class="block-ttl">Q</p>
					<ul class="cate-list">
						@foreach ($comp_Q as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-r" class="block">
					<p class="block-ttl">R</p>
					<ul class="cate-list">
						@foreach ($comp_R as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-s" class="block">
					<p class="block-ttl">S</p>
					<ul class="cate-list">
						@foreach ($comp_S as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-t" class="block">
					<p class="block-ttl">T</p>
					<ul class="cate-list">
						@foreach ($comp_T as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-u" class="block">
					<p class="block-ttl">U</p>
					<ul class="cate-list">
						@foreach ($comp_U as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-v" class="block">
					<p class="block-ttl">V</p>
					<ul class="cate-list">
						@foreach ($comp_V as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-w" class="block">
					<p class="block-ttl">W</p>
					<ul class="cate-list">
						@foreach ($comp_W as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-x" class="block">
					<p class="block-ttl">X</p>
					<ul class="cate-list">
						@foreach ($comp_X as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>

				<div id="cate-y" class="block">
					<p class="block-ttl">Y</p>
					<ul class="cate-list">
						@foreach ($comp_Y as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
				   </ul>
				</div>

				<div id="cate-z" class="block">
					<p class="block-ttl">Z</p>
					<ul class="cate-list">
						@foreach ($comp_Z as $comp)
							 <li>
								<label>
									<a href="/company/{{ $comp->id }}"><span>{{ $comp->name }}</span></a>
								</label>
							 </li>
						 @endforeach
					</ul>
				</div>
	
			</div>
			<div id="closeModal" class="closeModal">
				×
			</div>
		</div>
	</div>
</main>
{{-- END 企業選択モーダル  --}}

<script src="{{ asset('js/seek.js') }}"></script>


@endsection
