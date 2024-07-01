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

<style>
.job-item {
  background: #fff;
  border: 4px solid #E5AF24;
  border-radius: 20px;
  overflow: hidden;
  margin-top: 10px;
}

.job-corp-name figure {
  border-radius: 50%;
  overflow: hidden;
  width: 70px;
  border: 1px solid #D6D6D6;
  background: #D6D6D6;
}

.job-unit {
  font-size: 1.4rem;
  display: inline-block;
  background: #E5AF24;
  border-radius: 14px;
  margin-bottom: 6px;
  display: inline-block;
  color: #fff;
  padding: 4px 16px;
}


</style>

@if (!empty($randomJobList[0]))

	<div class="job">
		<div class="inner">
			<h2>{{ $comp->name }}の求人一覧</h2>
			<ul>

				<div class="job-item" style="width:100%;">
					<div class="inner">
						@foreach ($randomJobList as $job)
							@if ($loop->index == 0)
								@include ('user/partials/job_format_header')
							@endif

							@include ('user/partials/job_format_content')
							
							@if (!empty($jobList[$loop->index + 1]) )
								<hr>
							@endif
						@endforeach
					</div>
				</div>
			</ul>
		</div>
	</div>


{{-- END 求人一覧 --}}


{{-- 求人一覧ボタン --}}

<style>
.button-flex {
  display: flex;
  justify-content: center;
  margin: 32px auto;
}

.button-flex a {
  display: inline-block;
  font-size: 1.8rem;
  font-weight: 600;
  color: #fff;
  background: #4AA5CE;
  padding: 10px 20px;
  border-radius: 30px;
  max-width: 380px;
  width: 100%;
  text-align: center;
}

.button-flex a:nth-child(n+2) {
  margin-left: 30px;
}

</style>

	<div class="con-wrap">
		<div class="button-flex">
			<a href="/company/{{ $comp->id }}/joblist">求人一覧</a>
		</div>
	</div>

{{-- END 求人一覧ボタン --}}

@endif
