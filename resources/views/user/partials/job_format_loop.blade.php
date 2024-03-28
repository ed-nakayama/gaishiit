{{-- ジョブフォーマット $jobList --}}

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

.expand-name {
	font-size: 2.4rem;
	margin-right: 20px;
}
.expand-title {
	font-size: 2.0rem;
	margin-left: 20px;
	margin-right: 20px;
	font-weight:bold;
}

.expand-content {
	font-size: 1.6rem;
	margin-left: 20px;
	margin-right: 20px;
}

@media screen and (max-width: 820px) {
	.expand-name {
		font-size: 1.8rem;
	}
	.expand-title {
		font-size: 1.8rem;
	}
	.expand-content {
		font-size: 1.2rem;
	}
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
@php
	$pre_comp_id = '0';
	$next_arg = 1;
@endphp

@foreach ($jobList as $job)

	@if ($pre_comp_id != $job->company_id)
	<div class="job-item" style="width:100%;">
	@endif
		<div class="inner">
			@if ($pre_comp_id != $job->company_id)
				@include ('user/partials/job_format_header')
			@endif

			@include ('user/partials/job_format_content')
		</div>

	@if (!empty($jobList[$next_arg]))
		@if ($jobList[$next_arg]->company_id != $job->company_id)
			</div>
		@else
			<hr>
		@endif
	@endif
	@php
		$pre_comp_id = $job->company_id;
		$next_arg++;
	@endphp

@endforeach
	</div>


{{-- END ジョブフォーマット --}}
