{{-- 求人一覧 --}}
@php

	$randomJobList = App\Models\Job::Join('companies','jobs.company_id','=','companies.id')
		->leftJoin('job_cats','jobs.job_cat_id','=','job_cats.id')
		->leftJoin('job_cat_details','jobs.job_cat_detail_id','=','job_cat_details.id')
		->where('companies.open_flag' ,'1')
		->where('jobs.open_flag','1')
		->where('jobs.company_id',$comp->id)
		->selectRaw('jobs.*,' .
				  'companies.name as company_name ,companies.logo_file as logo_file ,companies.image_file as image_file,' .
				  'job_cats.name as job_cat_name, job_cat_details.name as job_cat_detail_name ')
		->inRandomOrder()->take(5)->get();

	$jobs = new App\Http\Controllers\JobController();

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

</style>

@isset($randomJobList[0])
	<div class="con-wrap">
		<div class="inner">
			<h2>求人一覧</h2>
			<ul>

				@foreach ($randomJobList as $job)
					<div class="job-item" style="cursor: pointer;"  onclick="javascript:jobform{{ $job->id }}.submit()">
						<table style="margin-left:20px; margin-right:20px; font-size: 16px;">
							<tr>
								<td style="width:10%;">
									@if(!empty($job->logo_file))
										<div class="job-corp-name">
											<figure>
												<img src="{{ $job->logo_file }}" alt="">
											</figure>
										</div>
									@endif
								</td>
								<td>
									{{ $job->company_name }}<br>
									<div style="text-align: center; display:flex; ">
										<p class="txt" style="font-size:16px;">
											<span class="star5_rating" style="--rate: {{ $job->total_rate . '%' }};font-size:20px;"></span>
											　総合評価　<b>{{ number_format($job->total_eval, 2) }}</b>
										</p>
									</div>
								</td>
							</tr>
							@if (!empty($job->job_cat_detail_id))
								<tr>
									<td colspan="2">
										{{ $job->getJobCategoryName() }}
									</td>
								</tr>
							@endif
							<tr>
								<td colspan="2" style="font-weight: bold;">
									{{ $job->name }}
								</td>
							</tr>
							<tr>
								<td  colspan="2">
								<div style="display: flex; font-size: 1.4rem;">
									<p class="job-income "style="font-size: 1.4rem;">
										年収 
									{{ $job->getIncome() }}
									</p>
									<p class="job-location "style="font-size: 1.4rem;">{{ $job->getLocations() }} @if (!empty($job->else_location))({{ $job->else_location }})@endif</p>
								</div>
								</td>
							</tr>
						</table>
						{{ html()->form('POST', "/company/{$job->company_id}/{$job->id}")->attribute('name', "jobform{$job->id}")->open() }}
						{{ html()->form()->close() }}
						
					</div>
				@endforeach
			</ul>    
		</div>
	</div>

@endisset

{{-- END 求人一覧 --}}
