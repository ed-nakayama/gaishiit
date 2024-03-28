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

		@foreach ($randomJobList as $job)
			{{-- ジョブフォーマット $job --}}
				@include ('user/partials/job_format')
			{{-- END ジョブフォーマット --}}
		@endforeach

	</div>
{{-- END ピックアップ求人 --}}
