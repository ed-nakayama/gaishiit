{{-- 企業フォーマットヘッダ $comp --}}

@php
	$ranking = $comp->getCompanyRanking();
@endphp

	<li class="company-list__item company-item">
		<figure class="company-item__image">
			@if(!empty($comp->logo_file))
				<img src="{{ $comp->logo_file }}" alt="">
			@endif
		</figure>
		<div class="company-item__content">
			<p class="company-item__name"><a href="/company/{{ $comp->id }}">{{ $comp->name }}</a></p>
			<dl class="company-item__reviews">
				<dt>総合評価</dt>
				<dd>
					<span>{{ number_format($ranking->total_point, 2) }}</span>
					<span class="star5_rating" style="--rate: {{  $ranking->total_rate . '%' }};"></span>
				</dd>
				<dt>クチコミ件数</dt>
				<dd>{{ number_format($ranking->answer_count) }} 件</dd>
			</dl>
			<p class="company-item__button"><a href="/company/{{ $comp->id }}">詳細を見る</a></p>
		</div>
	</li>

{{-- END 企業フォーマットヘッダ --}}
