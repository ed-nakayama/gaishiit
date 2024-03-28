{{-- エリアから求人を探す --}}
<style>
.job-check-box-btn label {
  display: inline-block; line-height: 2;
}
.job-check-box-btn label input {
  display: none;
}

.job-item {
  background: #fff;
  overflow: hidden;
  margin-top: 10px;
}

.area_box {
  margin: 10px 10px 10px 10px;
}

</style>

	<div class="con-wrap">
		<h2>エリアから求人を探す</h2>
		<div class="job-item">
			<div class="area_box">
				<div class="form-wrap">
					<div class="form-block">
						<div class="job-check-box-btn">
							@foreach ($constLocation as $loc)
								<label>
									<a href="/job/list/location{{ $loc->id }}"><div class="internal_link">{{ $loc->name }}</div></a>
								</label>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
{{-- END エリアから求人を探す --}}

{{-- 職種から求人を探す --}}
	<div class="con-wrap">
		<h2>職種から求人を探す</h2>
		<div class="job-item">
			<div class="area_box">
				<div class="form-wrap">
					<div class="form-block">
						<div class="job-check-box-btn">
							@foreach ($jobCat as $cat)
								<label>
									<a href="/job/list/jobcategory{{ $cat->id }}"  class="internal_link_cat">{{ $cat->name }}</a>
								</label><br>
								@foreach ($jobCatDetail as $detail)
									@if ($cat->id == $detail->job_cat_id)
										<label style="margin-bottom:10px;">
											<a href="/job/list/occupation{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
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
{{-- END 職種から求人を探す --}}


{{-- 特徴・こだわり --}}
	<div class="con-wrap">
		<h2>特徴・こだわり</h2>
		<div class="job-item">

{{-- インダストリから求人を探す --}}
			<div class="con-wrap" style="width:95%;margin: 0 auto 20px;">
				<h3>担当業界から求人を探す</h3>
				<div class="job-item">
					<div class="area_box">
						<div class="form-wrap">
							<div class="form-block">
								<div class="job-check-box-btn">
									@foreach ($industoryCat as $cat)
										<label>
											<a href="/job/list/indcat{{$cat->id}}"  class="internal_link_cat">{{ $cat->name }}</a>
										</label><br>
										@foreach ($industoryCatDetail as $detail)
											@if ($cat->id == $detail->industory_cat_id)
												<label style="margin-bottom:10px;">
													<a href="/job/list/industory{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
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
{{-- END インダストリから求人を探す --}}

{{-- 業種から求人を探す --}}
			<div class="con-wrap" style="width:95%;margin: 0 auto 20px;">
				<h3>IT業界の業種から求人を探す</h3>
				<div class="job-item">
					<div class="area_box">
						<div class="form-wrap">
							<div class="form-block">
								<div class="job-check-box-btn">
									@foreach ($businessCat as $cat)
										<label>
											<a href="/job/list/buscat{{$cat->id}}"  class="internal_link_cat">{{ $cat->name }}</a>
										</label><br>
										@foreach ($businessCatDetail as $detail)
											@if ($cat->id == $detail->business_cat_id)
												<label style="margin-bottom:10px;">
													<a href="/job/list/business{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
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
{{-- END 業種から求人を探す --}}

{{-- 年収から求人を探す --}}
			<div class="con-wrap" style="width:95%;margin: 0 auto 20px;">
				<h3>年収から求人を探す</h3>
				<div class="job-item">
					<div class="area_box">
						<div class="form-wrap">
							<div class="form-block">
								<div class="job-check-box-btn">
									@foreach ($incomeList as $income)
									<label style="margin-bottom:10px;">
											<a href="/job/list/income{{ $income->id }}"><div class="internal_link">{{ $income->name }}</div></a>
										</label>
									@endforeach
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
{{-- END 年収から求人を探す --}}

{{-- こだわりから求人を探す --}}
			<div class="con-wrap" style="width:95%;margin: 0 auto 20px;">
				<h3>こだわりから求人を探す</h3>
				<div class="job-item">
					<div class="area_box">
						<div class="form-wrap">
							<div class="form-block">
								<div class="job-check-box-btn">
									@foreach ($commitCat as $cat)
										<div class="internal_nolink_cat">{{ $cat->name }}</div>
										@foreach ($commitCatDetail as $detail)
											@if ($cat->id == $detail->commit_cat_id)
												<label style="margin-bottom:10px;">
													<a href="/job/list/commit{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
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
{{-- END こだわりから求人を探す --}}

		</div>
	</div>
