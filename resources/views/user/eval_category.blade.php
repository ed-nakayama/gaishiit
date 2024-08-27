@extends('layouts.user.auth')

@section('breadcrumbs')
	@if (isset($cat['sel']))
		{{ Breadcrumbs::render('comp_detail_eval' ,$comp ,$cat['name']) }}
	@endif
@endsection


@section('addheader')
	<title>{{ $comp->name }}の{{ $cat['name'] }}クチコミ評価一覧｜{{ config('app.title') }}</title>
	<meta name="description" content="{{ $comp->name }}の元社員・在籍社員による{{ $cat['name'] }}のクチコミ・評価レビューの一覧ページです。採用活動中にはなかなか耳にすることができない生の声を転職活動に役立てていただけます。｜{{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="{{ $comp->name }}の{{ $cat['name'] }}クチコミ評価一覧｜{{ config('app.title') }}" />
	<meta property="og:description" content="{{ $comp->name }}の元社員・在籍社員による{{ $cat['name'] }}のクチコミ・評価レビューの一覧ページです。採用活動中にはなかなか耳にすることができない生の声を転職活動に役立てていただけます。｜{{ config('app.description') }}" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

    <link href="{{ asset('css/department.css') }}" rel="stylesheet">
@endsection


@section('content')


<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "NewsArticle",
      "headline": "Article headline",
      "image": "https://example.org/thumbnail1.jpg",
      "datePublished": "2024-02-12T08:00:00+08:00",
      "dateModified": "2024-02-12T09:20:00+08:00",
      "author": {
        "@type": "Person",
        "name": "Tetsuji Nakayama",
        "url": "{{ url()->current() }}"
      },
      "description": "kuchikomi list",
      "isAccessibleForFree": "False",
      "hasPart":
        {
        "@type": "WebPageElement",
        "isAccessibleForFree": "False",
        "cssSelector" : ".paywall"
        }
    }
</script>

@if (Auth::guard('user')->check())
@include('user.user_activity')
@endif

	<main class="pane-main">
		<div class="inner">

			<div class="ttl">
				<h1>{{ $comp->name }}の{{ $cat['name'] }}クチコミ評価一覧</h1>
			</div>

{{-- 簡易的な企業の紹介情報 --}}
@php

	$ranking = App\Models\Ranking::find( $comp->id);
	$total_rate = $ranking->total_rate;
	$total_point = $ranking->total_point;

@endphp

			<div class="company-details">
				<div class="company-item">
					<figure class="company-item__image">
						@if(!empty($comp->logo_file))
							<img src="{{ $comp->logo_file }}" alt="">
						@endif
					</figure>
					<div class="company-item__content">
						<p class="company-item__name">
							{{ $comp->name }}
						</p>

						<dl class="company-item__reviews">
							<dt>総合評価</dt>
							<dd>
								<span>{{ number_format($comp->total_point, 2) }}</span>
								<span class="star5_rating" style="--rate:  {{ $comp->total_rate . '%' }};"></span>
							</dd>
							<dt>クチコミ件数</dt>
							<dd>{{ number_format($comp->answer_count) }} 件</dd>
						</dl>
					</div>
				</div>

				<div class="item-info">

					<div  style="display:flex;">
						@if ( $comp->casual_flag == '1')
							<div class="button-flex">
								@if (Auth::guard('user')->check())
									<a href="javascript:intform.submit()">カジュアル面談を依頼</a>
								@else
									<a class="openModal button-modal" href="#modalLogin">カジュアル面談を依頼</a>
								@endif
							</div>
						@endif

						@if ($qa_count > 0)
							<div class="button-flex">
								<a href="javascript:faqform.submit()">よくあるお問合せ</a>
							</div>
							{{ html()->form('POST', '/compfaq')->id('faqform')->attribute('name', 'unitform')->open() }}
							{{ html()->hidden('company_id', $comp->id) }}
							{{ html()->form()->close() }}
						@endif
					</div>

				</div><!-- item-info -->

@isset($interview)
				<p>以前にこの企業へのカジュアル面談の依頼をしたことがあります</p>
				<table style="font-size: 1.4rem;">
					<tr>
						<th>依頼日</th><th>依頼内容</th>
					</tr>
					<tr>
						<td>{{ $interview->created_at->format('Y/m/d/H:i') }}</td>
						<td>　　@if ($interview->interview_type == '0')カジュアル面談@endif</td>
					</tr>
				</table>
@endisset

{{--  チャート --}}
	@include ('user/partials/eval_chart')
{{--  END チャート --}}

			</div><!-- company-details -->
		</div><!-- item-inner -->

{{-- 簡易的な企業の紹介情報 --}}

			<div class="ttl">
				<h2>{{ $cat['name'] }}クチコミ評価一覧</h2>
			</div>


@if (isset($evalList[0]))
	<div class="paywall">
		<ul class="review-list">
			@foreach ($evalList as $eval)
				<li class="review-list__item">
					<div class="review-list__header">
						<div class="review-list__detal">
							<dl class="review-list__dl">
								<dt class="rl-job">職種</dt>
								<dd class="rl-job">{{ $eval->occupation }}</dd>
								<dt class="rl-employment">在籍</dt>
								<dd class="rl-employment">@if ($eval->retire_year == '9999'){{ \Carbon\Carbon::today()->format('Y') - $eval->join_year}}@else{{ $eval->retire_year - $eval->join_year }}@endif年</dd>
								<dt class="rl-sex">性別</dt>
								<dd class="rl-sex">@if ($eval->sex == '1')男性@elseif ($eval->sex == '2')女性@elseif ($eval->sex == '0')性別なし@else @if ($eval->id % 5 == '2')女性@else男性@endif @endif</dd>
							</dl>
							<p class="review-list__date">回答時期:@if (!empty($eval->answer_date)){{ substr(str_replace('-','/',$eval->answer_date),0,7) }}@else{{ str_replace('-','/',substr($eval->updated_at,0,7)) }}@endif</p>
						</div>
						<figure class="review-list__image"><img src="/img/icon-man.svg" alt=""></figure>
					</div>
					<div class="review-list__footer">
						@if ( Auth::guard('user')->check() || !isset($cat['sel']) )
						@else
							<div class="review-list__Register">
								<p class="review-list__RegisterTxt">無料のユーザー登録で全ての口コミをご覧いただけます</p>
								<p class="review-list__RegisterBtn"><a href="/register">登録する</a></p>
							</div>
						@endif
						<p class="review-list__footerRate">
							@if ($cat == '1')給与
							@elseif ($eval->cat_sel == '2')福利厚生
							@elseif ($eval->cat_sel == '3')育成
							@elseif ($eval->cat_sel == '4')法令遵守の意識
							@elseif ($eval->cat_sel == '5')社員のモチベーション
							@elseif ($eval->cat_sel == '6')ワークライフバランス
							@elseif ($eval->cat_sel == '7')勤務体系
							@elseif ($eval->cat_sel == '8')定年
							@endif

							@if ($eval->cat_sel == '1')給与
							@elseif ($eval->cat_sel == '2')<span class="star5_rating" style="--rate: {{ $eval->welfare_point * 100 / 5  . '%' }};"></span>
							@elseif ($eval->cat_sel == '3')<span class="star5_rating" style="--rate: {{ $eval->upbring_point * 100 / 5  . '%' }};"></span>
							@elseif ($eval->cat_sel == '4')<span class="star5_rating" style="--rate: {{ $eval->compliance_point * 100 / 5  . '%' }};"></span>
							@elseif ($eval->cat_sel == '5')<span class="star5_rating" style="--rate: {{ $eval->motivation_point * 100 / 5  . '%' }};"></span>
							@elseif ($eval->cat_sel == '6')<span class="star5_rating" style="--rate: {{ $eval->work_life_point * 100 / 5  . '%' }};"></span>
							@elseif ($eval->cat_sel == '7')<span class="star5_rating" style="--rate: {{ $eval->remote_point * 100 / 5  . '%' }};"></span>
							@elseif ($eval->cat_sel == '8')<span class="star5_rating" style="--rate: {{ $eval->retire_point * 100 / 5  . '%' }};"></span>
							@endif
						</p>
						<p class="review-list__footerText">
							
							@if ($eval->cat_sel == '1'){!! nl2br(e($eval->salary_content)) !!}
							@elseif ($eval->cat_sel == '2'){!! nl2br(e($eval->welfare_content)) !!}
							@elseif ($eval->cat_sel == '3'){!! nl2br(e($eval->upbring_content)) !!}
							@elseif ($eval->cat_sel == '4'){!! nl2br(e($eval->compliance_content)) !!}
							@elseif ($eval->cat_sel == '5'){!! nl2br(e($eval->motivation_content)) !!}
							@elseif ($eval->cat_sel == '6'){!! nl2br(e($eval->work_life_content)) !!}
							@elseif ($eval->cat_sel == '7'){!! nl2br(e($eval->remote_content)) !!}
							@elseif ($eval->cat_sel == '8'){!! nl2br(e($eval->retire_content)) !!}
							@endif
						</p>
					</div>
				</li>
			@endforeach
		</ul>

		@isset($cat['sel'])
			<div class="pager">
				{{ $evalList->appends(request()->query())->links('pagination.user') }}
			</div>
		@endisset

	</div><!-- paywall -->
@endif

{{-- 求人一覧 --}}
	@include ('user/partials/job_list_comp_new')
{{-- END 求人一覧 --}}

		</div><!-- inner -->
	</main>


{{-- ログインモーダル  --}}
	@include('user/partials/login_modal')
{{-- END ログインモーダル  --}}

@endsection
