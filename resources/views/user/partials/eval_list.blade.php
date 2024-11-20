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
		<h2>回答者別クチコミの一覧</h2>
	</div>

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
                  			@if ($eval->sel == '1')給与
							@elseif ($eval->sel == '2')福利厚生
							@elseif ($eval->sel == '3')育成
							@elseif ($eval->sel == '4')法令遵守の意識
							@elseif ($eval->sel == '5')社員のモチベーション
							@elseif ($eval->sel == '6')ワークライフバランス
							@elseif ($eval->sel == '7')勤務体系
							@elseif ($eval->sel == '8')定年
							@endif
							<span class="star5_rating" style="--rate: {{ $eval->point * 100 / 5  . '%' }};"></span>
						</p>
						<p class="review-list__footerText">
							{!! nl2br(e($eval->content)) !!}
						</p>
					</div>
				</li>
			@endforeach
		</ul>

	</div><!-- paywall -->

@endisset

{{-- END 回答者別口コミの一覧 --}}
