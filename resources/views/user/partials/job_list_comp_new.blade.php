{{-- 求人一覧 $comp --}}
@php

	$randomJobList = App\Models\Job::Join('companies','jobs.company_id','=','companies.id')
		->where('companies.open_flag' ,'1')
		->where('jobs.open_flag','1')
		->where('jobs.company_id',$comp->id)
		->selectRaw('jobs.*, companies.name as company_name ,companies.logo_file as logo_file ,companies.image_file as image_file')
		->orderBy('jobs.updated_at', 'DESC')
		->limit(6)->get();

	$arg = 0;
	foreach ($randomJobList as $job) {

		// クチコミ
		$ranking = App\Models\Ranking::find($job->company_id);
		if (!empty($ranking)) {
			$randomJobList[$arg]->total_eval = $ranking->total_point;
			$randomJobList[$arg]->total_rate = $ranking->total_rate;

		} else {
			$randomJobList[$arg]->total_eval = 0;
			$randomJobList[$arg]->total_rate = 0;
		}

		$arg++;

	} // end foreach

@endphp


@if (!empty($randomJobList[0]))

	<div class="job">
		<h2>{{ $comp->name }}の求人一覧</h2>

		<div class="job-opening">
			<ul class="job-opening-list">
				@foreach ($randomJobList as $job)
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
				@endforeach
			</ul>
			<p class="detail-link-button"><a href="/company/{{ $comp->id }}/joblist">求人一覧を見る</a></p>
		</div><!-- job-opening -->
	</div>


{{-- END 求人一覧 --}}

@endif
