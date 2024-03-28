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

</style>

@isset($elseJobList[0])
	<div class="con-wrap">
		<h2>他社の求人一覧</h2>
		<div class="form-wrap">

			@foreach ($elseJobList as $job)
			{{-- ジョブフォーマット $job --}}
				@include ('user/partials/job_format')
			{{-- END ジョブフォーマット --}}
			@endforeach
		</div>
	</div>

@endisset

{{-- END 他社の求人一覧 --}}
