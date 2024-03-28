{{-- ジョブフォーマットコンテント $job --}}

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

@media screen and (max-width: 1300px) {

  .button-flex {
    flex-wrap: wrap;
    margin: 32px auto;
  }


  .button-flex a {
    display: inline-block;
    font-size: 1.8rem;
    font-weight: 600;
    color: #fff;
    padding: 10px 20px;
    border-radius: 30px;
    max-width: 380px;
    width: 100%;
    text-align: center;
    margin: 12px auto 0 !important;
  }


}

</style>


		<table style="font-size: 20px; margin-left:20px; margin-right:20px;margin-right: 4px;border-spacing: 2px;width:100%;">
			<tr>
				<td colspan="2">
					<p class="expand-title"><a href="/company/{{ $job->company_id }}/{{ $job->id }}" style="text-decoration:underline;">{{ $job->name }}</a></p>
					<div class="info-btm" style="width:90%;">
						<ul class="tag" style="width:100%;">
						@if (!empty($job->job_cat_details))
							<li class="job-unit" style="padding: 6px 16px;border-radius: 20px;">{{ $job->getJobCategoryName() }}</li>
						@endif
						</ul>
					</div>
					<p class="expand-content">
						{{ mb_strimwidth($job->intro, 0, 250, "...") }}
					</p>
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

		<div class="con-wrap">
			<div class="button-flex" style="width:200px;margin-top:0px; margin-bottom:10px;">
				<a href="/company/{{ $job->company_id }}/{{ $job->id }}">求人詳細</a>
			</div>
		</div>

{{-- END ジョブフォーマットコンテント --}}
