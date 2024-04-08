@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('comp_list') }}
@endsection


@section('addheader')
	<title>企業を探す｜外資IT企業のクチコミ評価・求人なら外資IT.com</title>
	<meta name="description" content="クチコミのある外資系IT企業の一覧ページです。転職に役立つ社員クチコミを集め、スコアを集計して提供しております。｜外資IT.comは外資系IT企業に特化したクチコミ・求人サイトです。興味のある企業の担当者とは直接コミュニケーションも可能です。">
	<link href="{{ asset('css/seek.css') }}" rel="stylesheet">
@endsection


@section('content')


@if (Auth::guard('user')->check())
@include('user.user_activity')
@endif

<main class="pane-main">
	<div class="inner">
		<div class="ttl">
			<h1>外資IT企業一覧</h1>
		</div>
				
{{-- 広告エリア --}}
		@isset($pickup)
			<div class="con-wrap">
				<div class="item">
					<a href="/company/{{ $pickup->id }}">
						<figure>
							@if ($pickup->image_file == '')
								<img src="/img/corp_img_01.jpg" alt="">
							@else
								<img src="{{ $pickup->image_file }}" alt="">
							@endif
						</figure>

						<div class="item-info">
							<div class="top">
								<figure>
									<img src="{{ $pickup->mid_logo_file }}" alt="">
 								</figure>
								<div class="txt">
									<p class="name">{{ $pickup->name }}</p>
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
		 @endisset
{{-- END 広告エリア --}}
			   
				
		<div class="pager sort_name">
			<h2>名称で探す</h2>
			<ul class="page">
				<li class="page__numbers"><a class="openModalSeek button-modal" href="#cate-a">A - G</a></li>
				<li class="page__numbers"><a class="openModalSeek button-modal" href="#cate-h">H - N</a></li>
				<li class="page__numbers"><a class="openModalSeek button-modal" href="#cate-o">O - U</a></li>
				<li class="page__numbers"><a class="openModalSeek button-modal" href="#cate-v">V - Z</a></li>
			</ul>
		</div>

		<div class="con-wrap">
			@foreach ($compList as $comp)
					<div class="item thumb" style="margin-top:10px;">
						<table style="margin-left:20px; margin-right:20px; font-size: 16px; width:100%;">
							<tr>
								<td style="width:10%;">
									<div class="job-corp-name">
										<figure>
											@if(!empty($comp->logo_file))
												<img src="{{ $comp->logo_file }}" alt="">
											@endif
										</figure>
									</div>
								</td>
								<td>
@php
	$ranking = $comp->getCompanyRanking();
	$total_rate = $ranking->total_rate;
	$total_point = $ranking->total_point;
@endphp
									<p style="font-size:20px;"><a href="/company/{{ $comp->id }}" style="text-decoration:underline;">{{ $comp->name }}</a></p>
									<div style="text-align: center; display:flex; ">
										<p class="txt" style="font-size:16px;">
											<span class="star5_rating" style="--rate: {{ $total_rate . '%' }};font-size:20px;"></span>
											　総合評価　<b>{{ number_format($total_point, 2) }}</b>
										</p>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<p style="transform: rotate(0.03deg); margin-right: 20px;">{!! nl2br(e($comp->intro)) !!}</p>
								</td>
							</tr>
						</table>

						<div class="con-wrap">
							<div class="button-flex" style="width:200px;margin-top:0px; margin-bottom:10px;">
								<a href="/company/{{ $comp->id }}">企業詳細</a>
							</div>
						</div>
					</div>


			@endforeach




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

		{{-- 求人検索ボタン --}}
		@include ('user/partials/job_search_button')
		{{-- END 求人検索ボタン --}}

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
{{-- END 企業選択モーダル  --}}

<script src="{{ asset('js/seek.js') }}"></script>


@endsection
