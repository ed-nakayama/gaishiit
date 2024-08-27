{{-- クチコミ数ランキング --}}
@php
	$rankingList = App\Models\Ranking::Join('companies','rankings.company_id','=','companies.id')
		->where('companies.open_flag' ,'1')
		->selectRaw('rankings.*, companies.name as company_name ,companies.logo_file as logo_file ,companies.image_file as image_file')
		->orderBy('total_point', 'DESC')
		->take(10)
		->get();

@endphp


	<div class="con-wrap">
		<h2>クチコミ評価ランキング</h2>
		<div class="form-wrap">

			<ol class="ranking-list">
				@foreach ($rankingList as $ranking)
					@if ($loop->iteration <= 3)
						<li class="ranking-list__item -rank{{ $loop->iteration }} company-item">
					@else
						<li class="ranking-list__item company-item">
					@endif

					<figure class="company-item__image">
						@if(!empty($ranking->logo_file))
							<img src="{{ $ranking->logo_file }}" alt="">
						@endif
					</figure>
					<div class="company-item__content">
						<p class="company-item__name"><a href="/company/{{ $ranking->company_id }}">{{ $ranking->company_name }}</a></p>
							<dl class="company-item__reviews">
								<dt>総合評価</dt>
								<dd>
									<span>{{ number_format($ranking->total_point, 2) }}</span>
									<span class="star5_rating" style="--rate:  {{ $ranking->total_rate . '%' }};"></span>
								</dd>
								<dt>クチコミ件数</dt>
								<dd>{{ number_format($ranking->answer_count) }} 件</dd>
							</dl>
						<p class="company-item__button"><a href="/company/{{ $ranking->company_id }}">詳細を見る</a></p>
					</div>
                  </li>
				@endforeach
			</ol>

			<div class="con-wrap">
				<div class="button-flex">
					<a href="/company/ranking">クチコミ企業ランキングへ</a>
				</div>
			</div>

		</div>
	</div>
{{-- END クチコミ数ランキング --}}
