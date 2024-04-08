@extends('layouts.user.auth')

@section('breadcrumbs')
	@if (isset($cat['sel']))
		{{ Breadcrumbs::render('comp_detail_eval' ,$comp ,$cat['name']) }}
	@endif
@endsection


@section('addheader')
	<title>{{ $comp->name }}の{{ $cat['name'] }}クチコミ評価一覧｜外資IT企業のクチコミ評価・求人なら外資IT.com</title>
	<meta name="description" content="{{ $comp->name }}の元社員・在籍社員による{{ $cat['name'] }}のクチコミ・評価レビューの一覧ページです。採用活動中にはなかなか耳にすることができない生の声を転職活動に役立てていただけます。｜外資IT.comは外資系IT企業に特化したクチコミ・求人サイトです。興味のある企業の担当者とは直接コミュニケーションも可能です。">
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
	@include ('user/partials/company_simple_intro')
	<br>
{{-- 簡易的な企業の紹介情報 --}}

			<div class="ttl">
				<h2>{{ $cat['name'] }}クチコミ評価一覧</h2>
			</div>


@if (isset($evalList[0]))
	<div class="paywall">
	@foreach ($evalList as $eval)
			<div class="con-wrap">
				<div class="item thumb">
					<div class="inner">

						<p class="eval-header">
							<span style="font-size:14px;width:200px;padding: 4px 16px; font-weight: bold;">
							@if ($eval->cat_sel == '1')給与
							@elseif ($eval->cat_sel == '2')福利厚生
							@elseif ($eval->cat_sel == '3')育成
							@elseif ($eval->cat_sel == '4')法令遵守の意識
							@elseif ($eval->cat_sel == '5')社員のモチベーション
							@elseif ($eval->cat_sel == '6')ワークライフバランス
							@elseif ($eval->cat_sel == '7')リモート勤務
							@elseif ($eval->cat_sel == '8')定年
							@endif
							</span><span class="star5_rating" style="--rate: {{ $eval->salary_point * 100 / 5  . '%' }};"></span>
						</p>

						<div class="txt" style="font-size:14px; padding: 4px 16px;text-align: right;">
							回答者：{{ $eval->occupation }}、
							在籍@if ($eval->retire_year == '9999'){{ \Carbon\Carbon::today()->format('Y') - $eval->join_year}}@else{{ $eval->retire_year - $eval->join_year }}@endif年、
							@if ($eval->sex == '1')男性@elseif ($eval->sex == '2')女性@elseif ($eval->sex == '0')性別なし@else @if ($eval->id % 5 == '2')女性@else男性@endif @endif 
							 - 回答日:{{ str_replace('-','/',substr($eval->updated_at,0,10)) }}
						</div>

						<div class="eval-item">
							<div class="eval-txt">
								<p class="eval-detail">
									@if ( Auth::guard('user')->check() 
										|| (!isset($cat['sel']))
										|| ($evalList->currentPage() == 1 && $loop->index == 0)
									)
										@if ($eval->cat_sel == '1'){!! nl2br(e($eval->salary_content)) !!}
										@elseif ($eval->cat_sel == '2'){!! nl2br(e($eval->welfare_content)) !!}
										@elseif ($eval->cat_sel == '3'){!! nl2br(e($eval->upbring_content)) !!}
										@elseif ($eval->cat_sel == '4'){!! nl2br(e($eval->compliance_content)) !!}
										@elseif ($eval->cat_sel == '5'){!! nl2br(e($eval->motivation_content)) !!}
										@elseif ($eval->cat_sel == '6'){!! nl2br(e($eval->work_life_content)) !!}
										@elseif ($eval->cat_sel == '7'){!! nl2br(e($eval->remote_content)) !!}
										@elseif ($eval->cat_sel == '8'){!! nl2br(e($eval->retire_content)) !!}
										@endif
									@else
										@if ($eval->cat_sel == '1')
											{!! mb_strimwidth($eval->salary_content, 0, 40, "...") !!}
											<div class="blur">{!! nl2br(e($eval->salary_content)) !!}</div>
										@elseif ($eval->cat_sel == '2')
											{!! mb_strimwidth($eval->welfare_content, 0, 40, "...") !!}
											<div class="blur">{!! nl2br(e($eval->welfare_content)) !!}</div>
										@elseif ($eval->cat_sel == '3')
											{!! mb_strimwidth($eval->upbring_content, 0, 40, "...") !!}
											<div class="blur">{!! nl2br(e($eval->upbring_content)) !!}</div>
										@elseif ($eval->cat_sel == '4')
											{!! mb_strimwidth($eval->compliance_content, 0, 40, "...") !!}
											<div class="blur">{!! nl2br(e($eval->compliance_content)) !!}</div>
										@elseif ($eval->cat_sel == '5')
											{!! mb_strimwidth($eval->motivation_content, 0, 40, "...") !!}
											<div class="blur">{!! nl2br(e($eval->motivation_content)) !!}</div>
										@elseif ($eval->cat_sel == '6')
											{!! mb_strimwidth($eval->work_life_content, 0, 40, "...") !!}
											<div class="blur">{!! nl2br(e($eval->work_life_content)) !!}</div>
										@elseif ($eval->cat_sel == '7')
											{!! mb_strimwidth($eval->remote_content, 0, 40, "...") !!}
											<div class="blur">{!! nl2br(e($eval->remote_content)) !!}</div>
										@elseif ($eval->cat_sel == '8')
											{!! mb_strimwidth($eval->retire_content, 0, 40, "...") !!}
											<div class="blur">{!! nl2br(e($eval->retire_content)) !!}</div>
										@endif
										<div class="login">
											<a class="eval-button" href="{{ route('user.register') }}" style="color:white;white-space:nowrap;">無料のユーザ登録をお願いします</a><br><br>
											<a class="eval-button2" href="{{ route('user.login') }}" style="white-space:nowrap;">ログインはこちら</a>
										</div>
									@endif
								</p>
							</div>
						</div>
					</div><!-- item-inner -->
				</div><!-- item -->
			</div><!-- con-wrap -->
			<br>
		@isset($cat['sel'])
			<div class="pager">
				{{ $evalList->appends(request()->query())->links('pagination.user') }}
			</div>
		@endisset
	@endforeach
	</div>
@endisset

{{-- カテゴリ別クチコミボタン --}}
	@include ('user/partials/eval_cat_button')
{{-- END カテゴリ別クチコミボタン --}}

{{-- 求人一覧 --}}
	@include ('user/partials/job_list_comp_new')
{{-- END 求人一覧 --}}

{{--  チャート --}}
	@include ('user/partials/eval_chart')
{{--  END チャート --}}
		</div><!-- inner -->
	</main>


{{-- ログインモーダル  --}}
	@include('user/partials/login_modal')
{{-- END ログインモーダル  --}}

@endsection
