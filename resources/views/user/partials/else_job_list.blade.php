{{-- 他社の求人一覧 $comp $job --}}
@php

	$elseJobList = App\Models\Job::Join('companies','jobs.company_id','=','companies.id')
		->where('companies.open_flag' ,'1')
		->where('jobs.open_flag','1')
		->where('jobs.company_id', '!=', $comp->id)
		->selectRaw('jobs.*, companies.name as company_name ,companies.logo_file as logo_file ,companies.image_file as image_file')
		->inRandomOrder()->take(6)->get();

	$jobs = new App\Http\Controllers\JobController();

	$arg = 0;
	foreach ($elseJobList as $elseJob) {
		// クチコミ
		$ranking = App\Models\Ranking::find($elseJob->company_id);
		if (!empty($ranking)) {
			$elseJobList[$arg]->total_eval = $ranking->total_point;
			$elseJobList[$arg]->total_rate = $ranking->total_rate;

		} else {
			$elseJobList[$arg]->total_eval = 0;
			$elseJobList[$arg]->total_rate = 0;
		}

		$arg++;

	} // end foreach

@endphp


@isset($elseJobList[0])
	<div class="con-wrap">
		<h2>他社の類似する求人一覧</h2>
		<div class="pickup">
			<ol class="pickup-list">

				@foreach ($elseJobList as $job)
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
							</div>
						 </div>

						 <ul class="job-opening-list">
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
						 </ul>

					</li>
				@endforeach
			</ol>
			<p class="detail-link-button"><a href="/job">求人一覧を見る</a></p>
		</div><!-- pickup -->
	</div><!-- con-wrap -->

@endisset

{{-- END 他社の求人一覧 --}}
