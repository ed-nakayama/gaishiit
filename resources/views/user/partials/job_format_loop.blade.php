{{-- ジョブフォーマット $jobList --}}

@php
	$pre_comp_id = '0';
	$next_arg = 1;
@endphp

	<div class="pickup">
		<ol class="pickup-list">

			@foreach ($jobList as $job)

				@if ($pre_comp_id != $job->company_id)
@php
	$ranking = $job->getCompanyRanking();
@endphp
					<li class="pickup-list__item">
						<div class="company-item">
							<figure class="company-item__image">
								@if (!empty($job->logo_file))
									<img src="{{ $job->logo_file }}" alt="">
								@endif
							</figure>
							<div class="company-item__content">
								<p class="company-item__name"><a href="/company/{{ $job->company_id }}">{{ $job->company_name }}</a></p>
								<dl class="company-item__reviews">
									<dt>総合評価</dt>
									<dd>
										<span>{{ number_format($ranking->total_point, 2) }}</span>
										<span class="star5_rating" style="--rate: {{ $ranking->total_rate . '%' }};"></span>
									</dd>
									<dt>クチコミ件数</dt>
									<dd>{{ number_format($ranking->answer_count) }} 件</dd>
								</dl>
								<p class="company-item__button"><a href="/company/{{ $ranking->company_id }}">詳細を見る</a></p>
							</div>
						</div>
						<ul class="job-opening-list">
				@endif
						<li class="job-opening-list__item">
							<h3 class="job-opening-list__title"><a href="/company/{{ $job->company_id }}/{{ $job->id }}">{{ $job->name }}</a></h3>
							<p class="job-opening-list__text">{{ mb_strimwidth($job->intro, 0, 250, "...") }}</p>
							<div class="job-opening-list__footer">
								<dl class="job-opening-list__dl">
									<dt>年収</dt>
									<dd>{{ $job->getIncome() }}</dd>
									<dt>エリア</dt>
									<dd>{{ $job->getLocations() }} @if (!empty($job->else_location))({{ $job->else_location }})@endif</dd>
								</dl>
								<p class="detail-link-button"><a href="/company/{{ $job->company_id }}/{{ $job->id }}">求人詳細を見る</a></p>
							</div>
						</li>

	@if (!empty($jobList[$next_arg]))
		@if ($jobList[$next_arg]->company_id != $job->company_id)
						</ul>
					</li>
		@endif
	@endif
	@php
		$pre_comp_id = $job->company_id;
		$next_arg++;
	@endphp

@endforeach

		</ol>
		<p class="detail-link-button"><a href="/job">求人一覧を見る</a></p>
	</div>


{{-- END ジョブフォーマット --}}
