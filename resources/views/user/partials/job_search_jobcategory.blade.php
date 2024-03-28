{{-- エリアから求人を探す --}}
<style>
.job-check-box-btn label {
  cursor: pointer; display: inline-block; line-height: 2;
}
.job-check-box-btn label input {
  display: none;
}
.job-check-box-btn label span {
  color: #4AA5CE;
  font-size: 14px;
  border: 1px solid #4AA5CE;
  border-radius: 20px;
  padding: 5px 20px;
}

.job-item {
  background: #fff;
  border: 4px solid #E5AF24;
  border-radius: 20px;
  overflow: hidden;
  margin-top: 10px;
}
.area_box {
  margin: 10px 10px 10px 10px;
}

</style>

{{-- 職種から求人を探す --}}
	<div class="con-wrap">
		<h2>職種から求人を探す</h2>
		<div class="job-item">
			<div class="area_box">
				<div class="form-wrap">
					<div class="form-block">
						<div class="form-inner">
							<div class="job-check-box-btn">
								@foreach ($jobCat as $cat)
									<label>
										<a href="/job/list/jobcategory{{ $cat->id }}" style="border-bottom: 1px solid #000;"><b>{{ $cat->name }}</b></a>
									</label><br>
									@foreach ($jobCatDetail as $detail)
										@if ($cat->id == $detail->job_cat_id)
											<label style="margin-bottom:10px;">
												<a href="/job/list/occupation{{ $detail->id }}"><span>{{ $detail->name }}</span></a>
											</label>
										@endif
									@endforeach
									<br>
								@endforeach
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
{{-- END 職種から求人を探す --}}
