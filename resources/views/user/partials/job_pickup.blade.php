{{-- ピックアップ求人 --}}
@php

	$randomJobList = App\Models\Job::Join('companies','jobs.company_id','=','companies.id')
		->where('companies.open_flag' ,'1')
		->where('jobs.open_flag','1')
		->selectRaw('jobs.*, companies.name as company_name ,companies.logo_file as logo_file ,companies.image_file as image_file')
		->inRandomOrder()->take(3)->get();

	$arg = 0;
	foreach ($randomJobList as $job) {
		// クチコミ
		$ranking = App\Models\Ranking::find($job->company_id);
		if (!empty($ranking)) {
			$randomJobList[$arg]->total_point = $ranking->total_point;
			$randomJobList[$arg]->total_rate = $ranking->total_rate;

		} else {
			$randomJobList[$arg]->total_eval = 0;
			$randomJobList[$arg]->total_rate = 0;
		}

		$arg++;

	} // end foreach

@endphp

	<div class="con-wrap">
		<h2>ピックアップ求人</h2>
		<div class="pickup">
			<ol class="pickup-list">

				@foreach ($randomJobList as $job)
@php
	$ranking = $job->getCompanyRanking();
	$total_rate = $ranking->total_rate;
	$total_point = $ranking->total_point;
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
										<span>{{ number_format($total_point, 2) }}</span>
										<span class="star5_rating" style="--rate:  {{ $total_rate . '%' }};"></span>
									</dd>
									<dt>クチコミ件数</dt>
									<dd>{{ number_format($ranking->answer_count) }} 件</dd>
								</dl>
								<p class="company-item__button"><a href="/company/{{ $job->company_id }}">詳細を見る</a></p>
							</div>
						 </div>

						 <div class="company-information">
							<div class="company-information__header">
								<h4 class="company-information__title">求人名</h4>
								<p class="company-information__position">{{ $job->name }}</p>
							</div>
							<dl class="company-information__info">
								<dt>年収</dt>
								<dd>{{ $job->getIncome() }}</dd>
								<dt>勤務地</dt>
								<dd>{{ $job->getLocations() }} @if (!empty($job->else_location))({{ $job->else_location }})@endif</dd>
							</dl>
							<h4 class="company-information__subtitle">業務内容</h4>
							<p class="company-information__description">{{ mb_strimwidth($job->intro, 0, 250, "...") }}</p>
							<p class="detail-link-button">
								<a class="" href="/company/{{ $job->company_id }}/{{ $job->id }}">求人詳細を見る</a>
							</p>
						</div>
					</li>
				@endforeach

			</ol>
		<p class="detail-link-button"><a href="/job">求人一覧を見る</a></p>
		</div>
	</div>
