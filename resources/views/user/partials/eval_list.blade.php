{{-- 回答者別口コミの一覧 $eval --}}
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

@isset($evalList[0])
			<div class="ttl">
				<h2>回答者別口コミの一覧</h2>
			</div>

	<div class="paywall">
		@foreach ($evalList as $eval)
			<div class="con-wrap">
				<div class="item thumb">
					<div class="inner">

						<p class="eval-header">
							<span style="font-size:16px;width:200px;padding: 4px 16px; font-weight: bold;">
							@if ($eval->sel == '1')給与
							@elseif ($eval->sel == '2')福利厚生
							@elseif ($eval->sel == '3')育成
							@elseif ($eval->sel == '4')法令遵守の意識
							@elseif ($eval->sel == '5')社員のモチベーション
							@elseif ($eval->sel == '6')ワークライフバランス
							@elseif ($eval->sel == '7')リモート勤務
							@elseif ($eval->sel == '8')定年
							@endif
							</span><span class="star5_rating" style="--rate: {{ $eval->point * 100 / 5  . '%' }};"></span>
						</p>

						<div class="txt" style="font-size:14px; padding: 4px 16px;text-align: right;">
							回答者：{{ $eval->occupation }}、
							在籍@if ($eval->retire_year == '9999'){{ \Carbon\Carbon::today()->format('Y') - $eval->join_year}}@else{{ $eval->retire_year - $eval->join_year }}@endif年、
							@if ($eval->sex == '1')男性@elseif ($eval->sex == '2')女性@elseif ($eval->sex == '0')性別なし@else @if ($eval->id % 5 == '2')女性@else男性@endif @endif 
							 - 回答日:@if (!empty($eval->answer_date)){{ str_replace('-','/',$eval->answer_date) }}@else{{ str_replace('-','/',substr($eval->updated_at,0,10)) }}@endif
						</div>

						<div class="eval-item">
							<div class="eval-txt">
								<p class="eval-detail">
									@if ( Auth::guard('user')->check() 
										|| (!isset($cat['sel']))
										|| ($loop->index == 0 && $loop->index == 0)
									)
										{!! nl2br(e($eval->content)) !!}
									@else
										{!! mb_strimwidth(e($eval->content), 0, 40, "...") !!}
										<div class="blur">{!! nl2br(e($eval->content)) !!}</div>
										<div class="login">
											<a class="eval-button" href="{{ route('user.register') }}" style="color:white;white-space:nowrap;">無料のユーザ登録をお願いします</a><br><br>
											<a class="eval-button2" href="{{ route('user.login') }}" style="white-space:nowrap;">ログインはこちら</a>
										</div>
									@endif
								</p>
							</div><!-- eval-item -->
						</div><!-- eval-item -->
					</div><!-- item-inner -->
				</div><!-- item -->
			</div><!-- con-wrap -->
			<br>
		@endforeach
	</div>
@endisset
{{-- END 回答者別口コミの一覧 --}}
