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


@php

	$job_cat_id = '';
	$ind_cat_id = '';
	$bus_cat_id = '';

	if  ( (false !== strpos($param1, 'jobcategory')) || (false !== strpos($param2, 'jobcategory')) ) {
		$valJobCat       = \app\Models\JobCat::where('id', $param['job_cats'])->where('del_flag','0')->orderBy('order_num')->orderBy('id')->get();
		$valJobCatDetail = \app\Models\JobCatDetail::where('job_cat_id', $param['job_cats'])->where('del_flag','0')->orderBy('order_num')->orderBy('id')->get();

	} else if ( (false !== strpos($param1, 'occupation')) || (false !== strpos($param2, 'occupation')) ) {
		$cat             = \app\Models\JobCatDetail::where('id', $param['job_cat_details'])->where('del_flag','0')->first();
		$valJobCat       = \app\Models\JobCat::where('id', $cat->job_cat_id)->where('del_flag','0')->orderBy('order_num')->orderBy('id')->get();
		$valJobCatDetail = \app\Models\JobCatDetail::where('job_cat_id', $cat->job_cat_id)->where('del_flag','0')->orderBy('order_num')->orderBy('id')->get();
		$job_cat_id = $cat->job_cat_id;
	} else {
		$valJobCat       = \app\Models\JobCat::where('del_flag','0')->orderBy('order_num')->orderBy('id')->get();
		$valJobCatDetail = \app\Models\JobCatDetail::where('del_flag','0')->orderBy('order_num')->orderBy('id')->get();
	}


	if  ( (false !== strpos($param1, 'indcat')) || (false !== strpos($param2, 'indcat'))  || (false !== strpos($param3, 'indcat')) ) {
		$valIndustoryCat       = \app\Models\IndustoryCat::where('id', $param['industory_cats'])->where('del_flag','0')->orderBy('order_num')->orderBy('id')->get();
		$valIndustoryCatDetail = \app\Models\IndustoryCatDetail::where('industory_cat_id', $param['industory_cats'])->where('del_flag','0')->orderBy('order_num')->orderBy('id')->get();

	} else if ( (false !== strpos($param1, 'industory')) || (false !== strpos($param2, 'industory')) || (false !== strpos($param3, 'industory')) ) {
		$cat                   = \app\Models\IndustoryCatDetail::where('id', $param['industory_cat_details'])->where('del_flag','0')->orderBy('order_num')->orderBy('id')->first();
		$valIndustoryCat       = \app\Models\IndustoryCat::where('id', $cat->industory_cat_id)->where('del_flag','0')->orderBy('order_num')->orderBy('id')->get();
		$valIndustoryCatDetail = \app\Models\IndustoryCatDetail::where('industory_cat_id', $cat->industory_cat_id)->where('del_flag','0')->orderBy('order_num')->orderBy('id')->get();
		$ind_cat_id = $cat->industory_cat_id;

	} else {
		$valIndustoryCat       = \app\Models\IndustoryCat::where('del_flag','0')->orderBy('order_num')->orderBy('id')->get();
		$valIndustoryCatDetail = \app\Models\IndustoryCatDetail::where('del_flag','0')->orderBy('order_num')->orderBy('id')->get();
	}


	if  ( (false !== strpos($param1, 'buscat')) || (false !== strpos($param2, 'buscat')) || (false !== strpos($param3, 'buscat')) ) {
		$valBusinessCat       = \app\Models\BusinessCat::where('id', $param['business_cats'])->where('del_flag','0')->orderBy('order_num')->orderBy('id')->get();
		$valBusinessCatDetail = \app\Models\BusinessCatDetail::where('business_cat_id', $param['business_cats'])->where('del_flag','0')->orderBy('order_num')->orderBy('id')->get();

	} else if ( (false !== strpos($param1, 'business')) || (false !== strpos($param2, 'business')) || (false !== strpos($param3, 'business')) ) {
		$cat = \app\Models\BusinessCatDetail::where('id', $param['business_cat_details'])->where('del_flag','0')->orderBy('order_num')->orderBy('id')->first();
		$valBusinessCat       = \app\Models\BusinessCat::where('id', $cat->business_cat_id)->where('del_flag','0')->orderBy('order_num')->orderBy('id')->get();
		$valBusinessCatDetail = \app\Models\BusinessCatDetail::where('business_cat_id', $cat->business_cat_id)->where('del_flag','0')->orderBy('order_num')->orderBy('id')->get();
		$bus_cat_id = $cat->business_cat_id;

	} else {
		$valBusinessCat       = \app\Models\BusinessCat::where('del_flag','0')->orderBy('order_num')->orderBy('id')->get();
		$valBusinessCatDetail = \app\Models\BusinessCatDetail::where('del_flag','0')->orderBy('order_num')->orderBy('id')->get();
	}

@endphp

{{-- エリアから求人を探す --}}
	<div class="con-wrap">
		<h2>エリアから求人を探す</h2>
		<div class="job-item">
			<div class="area_box">
				<div class="form-wrap">
					<div class="form-block">
						<div class="job-check-box-btn">
							@foreach ($constLocation as $loc)
								<label>
									@if ($param_count == 1)
										@if (false !== strpos($param1, 'location'))
											<a href="/job/list/location{{ $loc->id }}"><div class="internal_link">{{ $loc->name }}</div></a>
										@else
											<a href="/job/list/location{{ $loc->id }}/{{ $param1 }}"><div class="internal_link">{{ $loc->name }}</div></a>
										@endif
									@elseif ($param_count == 2)
										@if (false !== strpos($param1, 'location'))
											<a href="/job/list/location{{ $loc->id }}/{{ $param2 }}"><div class="internal_link">{{ $loc->name }}</div></a>
										@else
											<a href="/job/list/location{{ $loc->id }}/{{ $param1 }}/{{ $param2 }}"><div class="internal_link">{{ $loc->name }}</div></a>
										@endif
									@elseif ($param_count == 3)
										<a href="/job/list/location{{ $loc->id }}/{{ $param2 }}/{{ $param3 }}"><div class="internal_link">{{ $loc->name }}</div></a>
									@else
										{{ html()->form('POST', '/job/list')->attribute('name', "locForm{$loc->id}")->open() }}
										{{ html()->hidden('locations[]', $loc->id) }}
										@if (!empty($param['job_cats']))
											{{ html()->hidden('job_cats', $param['job_cats']) }}
										@endif
										@if (!empty($param['job_cat_details']))
											{{ html()->hidden('job_cat_details', $param['job_cat_details']) }}
										@endif
										@if (!empty($param['industory_cats']))
											{{ html()->hidden('industory_cats', $param['industory_cats']) }}
										@endif
										@if (!empty($param['industory_cat_details']))
											{{ html()->hidden('industory_cat_details', $param['industory_cat_details']) }}
										@endif
										@if (!empty($param['business_cats']))
											{{ html()->hidden('business_cats', $param['business_cats']) }}
										@endif
										@if (!empty($param['business_cat_details']))
											{{ html()->hidden('business_cat_details', $param['business_cat_details']) }}
										@endif
										@if (!empty($param['incomes']))
											@foreach ($param['incomes'] as $val)
												{{ html()->hidden('incomes[]', $val) }}
											@endforeach
										@endif
										@if (!empty($param['commit_cat_details']))
											{{ html()->hidden('commit_cat_details', $param['commit_cat_details']) }}
										@endif
										{{ html()->form()->close() }}
										<a href="javascript:locForm{{ $loc->id }}.submit()"><div class="internal_link">{{ $loc->name }}</div></a>
									@endif
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
								@foreach ($valJobCat as $cat)
									@if ( (false !== strpos($param1, 'occupation')) || (false !== strpos($param2, 'occupation')) )
										@if ($cat->id == $job_cat_id)
											<div class="internal_nolink_cat">{{ $cat->name }}</div>
										@endif
									@else
										<label>
											@if ($param_count == 1)
												@if (false !== strpos($param1, 'location'))
													<a href="/job/list/{{ $param1 }}/jobcategory{{ $cat->id }}" class="internal_link_cat">{{ $cat->name }}</a>
												@elseif ( (false !== strpos($param1, 'jobcategory')) || (false !== strpos($param1, 'occupation')) )
													<a href="/job/list/jobcategory{{ $cat->id }}" class="internal_link_cat">{{ $cat->name }}</a>
												@else
													<a href="/job/list/jobcategory{{ $cat->id }}/{{ $param1 }}" class="internal_link_cat">{{ $cat->name }}</a>
												@endif
											@elseif ($param_count == 2)
												@if (false !== strpos($param1, 'location'))
													@if ( (false !== strpos($param2, 'jobcategory')) || (false !== strpos($param2, 'occupation')) )
														<a href="/job/list/{{ $param1 }}/jobcategory{{ $cat->id }}/{{ $param2 }}" class="internal_link_cat">{{ $cat->name }}</a>
													@else
														<a href="/job/list/{{ $param1 }}/jobcategory{{ $cat->id }}/{{ $param2 }}" class="internal_link_cat">{{ $cat->name }}</a>
													@endif
												@elseif ( (false !== strpos($param1, 'jobcategory')) || (false !== strpos($param1, 'occupation')) )
													<a href="/job/list/jobcategory{{ $cat->id }}/{{ $param2 }}"  class="internal_link_cat">{{ $cat->name }}</a>
												@else
													<a href="/job/list/jobcategory{{ $cat->id }}/{{ $param1 }}/{{ $param2 }}"  class="internal_link_cat">{{ $cat->name }}</a>
												@endif
											@elseif ($param_count == 3)
												@if (false !== strpos($param1, 'location'))
													@if ( (false !== strpos($param2, 'jobcategory')) || (false !== strpos($param2, 'occupation')) )
														<a href="/job/list/{{ $param1 }}/jobcategory{{ $cat->id }}/{{ $param3 }}"  class="internal_link_cat">{{ $cat->name }}</a>
													@endif
												@else
													{{ html()->form('POST', '/job/list')->attribute('name', "jobcatForm{$cat->id}")->open() }}
													{{ html()->hidden('job_cats', $cat->id) }}
													@if (!empty($param['industory_cats']))
														{{ html()->hidden('industory_cats', $param['industory_cats']) }}
													@endif
													@if (!empty($param['industory_cat_details']))
														{{ html()->hidden('industory_cat_details', $param['industory_cat_details']) }}
													@endif
													@if (!empty($param['business_cats']))
														{{ html()->hidden('business_cats', $param['business_cats']) }}
													@endif
													@if (!empty($param['business_cat_details']))
														{{ html()->hidden('business_cat_details', $param['business_cat_details']) }}
													@endif
													@if (!empty($param['incomes']))
														@foreach ($param['incomes'] as $val)
															{{ html()->hidden('incomes[]', $val) }}
														@endforeach
													@endif
													@if (!empty($param['commit_cat_details']))
														{{ html()->hidden('commit_cat_details', $param['commit_cat_details']) }}
													@endif
													{{ html()->form()->close() }}
													<a href="javascript:jobcatForm{{ $cat->id }}.submit()">{{ $cat->name }}</a>
												@endif
											@else
												{{ html()->form('POST', '/job/list')->attribute('name', "jobcatForm{$cat->id}")->open() }}
												{{ html()->hidden('job_cats', $cat->id) }}
												@if (!empty($param['industory_cats']))
													{{ html()->hidden('industory_cats', $param['industory_cats']) }}
												@endif
												@if (!empty($param['industory_cat_details']))
													{{ html()->hidden('industory_cat_details', $param['industory_cat_details']) }}
												@endif
												@if (!empty($param['business_cats']))
													{{ html()->hidden('business_cats', $param['business_cats']) }}
												@endif
												@if (!empty($param['business_cat_details']))
													{{ html()->hidden('business_cat_details', $param['business_cat_details']) }}
												@endif
												@if (!empty($param['incomes']))
													@foreach ($param['incomes'] as $val)
														{{ html()->hidden('incomes[]', $val) }}
													@endforeach
												@endif
												@if (!empty($param['commit_cat_details']))
													{{ html()->hidden('commit_cat_details', $param['commit_cat_details']) }}
												@endif
												{{ html()->form()->close() }}
												<a href="javascript:jobcatForm{{ $cat->id }}.submit()">{{ $cat->name }}</a>
											@endif
										</label><br>
									@endif

									@foreach ($valJobCatDetail as $detail)
										@if ($cat->id == $detail->job_cat_id)
											<label style="margin-bottom:10px;">
												@if ($param_count == 1)
													@if (false !== strpos($param1, 'location'))
														<a href="/job/list/{{ $param1 }}/occupation{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
													@elseif ( (false !== strpos($param1, 'jobcategory')) || (false !== strpos($param1, 'occupation')) )
														<a href="/job/list/occupation{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
													@else
														<a href="/job/list/occupation{{ $detail->id }}/{{ $param1 }}"><div class="internal_link">{{ $detail->name }}</div></a>
													@endif

												@elseif ($param_count == 2)
													@if (false !== strpos($param1, 'location'))
														@if ( (false !== strpos($param2, 'jobcategory')) || (false !== strpos($param2, 'occupation')) )
															<a href="/job/list/{{ $param1 }}/occupation{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
														@else
															<a href="/job/list/{{ $param1 }}/occupation{{ $detail->id }}/{{ $param2 }}"><div class="internal_link">{{ $detail->name }}</div></a>
														@endif
													@elseif ( (false !== strpos($param1, 'jobcategory')) || (false !== strpos($param1, 'occupation')) )
														<a href="/job/list/occupation{{ $detail->id }}/{{ $param1 }}"><div class="internal_link">{{ $detail->name }}</div></a>
													@elseif ( (false !== strpos($param2, 'jobcategory')) || (false !== strpos($param2, 'occupation')) )
														<a href="/job/list/occupation{{ $detail->id }}/{{ $param1 }}/{{ $param2 }}"><div class="internal_link">{{ $detail->name }}</div></a>
													@else
														<a href="/job/list/occupation{{ $detail->id }}/{{ $param1 }}/{{ $param2 }}"><div class="internal_link">{{ $detail->name }}</div></a>
													@endif

												@elseif ($param_count == 3)
													@if (false !== strpos($param1, 'location'))
														@if ( (false !== strpos($param2, 'jobcategory')) || (false !== strpos($param2, 'occupation')) )
															<a href="/job/list/{{ $param1 }}/occupation{{ $detail->id }}/{{ $param3 }}"><div class="internal_link">{{ $detail->name }}</div></a>
														@else
														@endif
													@elseif ( (false !== strpos($param1, 'jobcategory')) || (false !== strpos($param1, 'occupation')) )
														<a href="/job/list/occupation{{ $detail->id }}/{{ $param2 }}/{{ $param3 }}"><div class="internal_link">{{ $detail->name }}</div></a>
													@elseif ( (false !== strpos($param2, 'jobcategory')) || (false !== strpos($param2, 'occupation')) )
														<a href="/job/list/{{ $param1 }}/occupation{{ $detail->id }}/{{ $param3 }}"><div class="internal_link">{{ $detail->name }}</div></a>
													@else
														{{ html()->form('POST', '/job/list')->attribute('name', "jobDetailForm{$cat->id}")->open() }}
														{{ html()->hidden('job_cat_details', $detail->id) }}
														@if (!empty($param['locations']))
															@foreach ($param['locations'] as $val)
																{{ html()->hidden('locations[]', $val) }}
															@endforeach
														@endif
														@if (!empty($param['industory_cats']))
															{{ html()->hidden('industory_cats', $param['industory_cats']) }}
														@endif
														@if (!empty($param['industory_cat_details']))
															{{ html()->hidden('industory_cat_details', $param['industory_cat_details']) }}
														@endif
														@if (!empty($param['business_cats']))
															{{ html()->hidden('business_cats', $param['business_cats']) }}
														@endif
														@if (!empty($param['business_cat_details']))
															{{ html()->hidden('business_cat_details', $param['business_cat_details']) }}
														@endif
														@if (!empty($param['incomes']))
															@foreach ($param['incomes'] as $val)
																{{ html()->hidden('incomes[]', $val) }}
															@endforeach
														@endif
														@if (!empty($param['commit_cat_details']))
															{{ html()->hidden('commit_cat_details', $param['commit_cat_details']) }}
														@endif
														{{ html()->form()->close() }}
														<a href="javascript:jobDetailForm{{ $detail->id }}.submit()"><div class="internal_link">{{ $detail->name }}</div></a>
													@endif
												@else
													{{ html()->form('POST', '/job/list')->attribute('name', "jobDetailForm{$cat->id}")->open() }}
													{{ html()->hidden('job_cat_details', $detail->id) }}
													@if (!empty($param['locations']))
														@foreach ($param['locations'] as $val)
															{{ html()->hidden('locations[]', $val) }}
														@endforeach
													@endif
													@if (!empty($param['industory_cats']))
														{{ html()->hidden('industory_cats', $param['industory_cats']) }}
													@endif
													@if (!empty($param['industory_cat_details']))
														{{ html()->hidden('industory_cat_details', $param['industory_cat_details']) }}
													@endif
													@if (!empty($param['business_cats']))
														{{ html()->hidden('business_cats', $param['business_cats']) }}
													@endif
													@if (!empty($param['business_cat_details']))
														{{ html()->hidden('business_cat_details', $param['business_cat_details']) }}
													@endif
													@if (!empty($param['incomes']))
														@foreach ($param['incomes'] as $val)
															{{ html()->hidden('incomes[]', $val) }}
														@endforeach
													@endif
													@if (!empty($param['commit_cat_details']))
														{{ html()->hidden('commit_cat_details', $param['commit_cat_details']) }}
													@endif
													{{ html()->form()->close() }}
													<a href="javascript:jobDetailForm{{ $detail->id }}.submit()"><div class="internal_link">{{ $detail->name }}</div></a>
												@endif
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
										@foreach ($valIndustoryCat as $cat)
											@if ( (false !== strpos($param1, 'industory')) || (false !== strpos($param2, 'industory')) || (false !== strpos($param3, 'industory')) )
												@if ($cat->id == $ind_cat_id)
													<div class="internal_nolink_cat">{{ $cat->name }}</div>
												@endif
											@else
												<label>
													@if ($param_count == 1)
														@if ( (false !== strpos($param1, 'indcat')) || (false !== strpos($param1, 'industory')) || (false !== strpos($param1, 'buscat')) || (false !== strpos($param1, 'business')) || (false !== strpos($param1, 'commit')) || (false !== strpos($param1, 'income')) )
															<a href="/job/list/indcat{{$cat->id}}" class="internal_link_cat">{{ $cat->name }}</a>
														@else
															<a href="/job/list/{{ $param1 }}/indcat{{ $cat->id }}" class="internal_link_cat">{{ $cat->name }}</a>
														@endif
													@elseif ($param_count == 2)
														@if ( (false !== strpos($param2, 'indcat')) || (false !== strpos($param2, 'industory')) || (false !== strpos($param2, 'buscat')) || (false !== strpos($param2, 'business')) || (false !== strpos($param2, 'commit')) || (false !== strpos($param2, 'income')))
															<a href="/job/list/{{ $param1 }}/indcat{{ $cat->id }}" class="internal_link_cat">{{ $cat->name }}</a>
														@else
															<a href="/job/list/{{ $param1 }}/{{ $param2 }}/indcat{{ $cat->id }}" class="internal_link_cat">{{ $cat->name }}</a>
														@endif
													@elseif ($param_count == 3)
														<a href="/job/list/{{ $param1 }}/{{ $param2 }}/indcat{{ $cat->id }}" class="internal_link_cat">{{ $cat->name }}</a>
													@else
														{{ html()->form('POST', '/job/list')->attribute('name', "indcatForm{$cat->id}")->open() }}
														{{ html()->hidden('industory_cats', $cat->id) }}
														@if (!empty($param['locations']))
															@foreach ($param['locations'] as $val)
																{{ html()->hidden('locations[]', $val) }}
															@endforeach
														@endif
														@if (!empty($param['job_cats']))
															{{ html()->hidden('job_cats', $param['job_cats']) }}
														@endif
														@if (!empty($param['job_cat_details']))
															{{ html()->hidden('job_cat_details', $param['job_cat_details']) }}
														@endif
														{{ html()->form()->close() }}
														<a href="javascript:indcatForm{{ $cat->id }}.submit()">{{ $cat->name }}</a>
													@endif
												</label><br>
											@endif

											@foreach ($valIndustoryCatDetail as $detail)
												@if ($cat->id == $detail->industory_cat_id)
													<label style="margin-bottom:10px;">
														@if ($param_count == 1)
															@if ( (false !== strpos($param1, 'indcat')) || (false !== strpos($param1, 'industory')) || (false !== strpos($param1, 'buscat')) || (false !== strpos($param1, 'business')) || (false !== strpos($param1, 'commit')) || (false !== strpos($param1, 'income')) )
																<a href="/job/list/industory{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
															@else
																<a href="/job/list/{{ $param1 }}/industory{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
															@endif
														@elseif ($param_count == 2)
															@if ( (false !== strpos($param2, 'indcat')) || (false !== strpos($param2, 'industory')) || (false !== strpos($param2, 'buscat')) || (false !== strpos($param2, 'business')) || (false !== strpos($param2, 'commit')) || (false !== strpos($param2, 'income')))
																<a href="/job/list/{{ $param1 }}/industory{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
															@else
																<a href="/job/list/{{ $param1 }}/{{ $param2 }}/industory{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
															@endif
														@elseif ($param_count == 3)
															<a href="/job/list/{{ $param1 }}/{{ $param2 }}/industory{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
														@else
															{{ html()->form('POST', '/job/list')->attribute('name', "industoryForm{$detail->id}")->open() }}
															{{ html()->hidden('industory_cat_details', $detail->id) }}
															@if (!empty($param['locations']))
																@foreach ($param['locations'] as $val)
																	{{ html()->hidden('locations[]', $val) }}
																@endforeach
															@endif
															@if (!empty($param['job_cats']))
																{{ html()->hidden('job_cats', $param['job_cats']) }}
															@endif
															@if (!empty($param['job_cat_details']))
																{{ html()->hidden('job_cat_details', $param['job_cat_details']) }}
															@endif
															{{ html()->form()->close() }}
															<a href="javascript:industoryForm{{ $detail->id }}.submit()"><div class="internal_link">{{ $detail->name }}</div></a>
														@endif
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
										@foreach ($valBusinessCat as $cat)
											@if ( (false !== strpos($param1, 'business')) || (false !== strpos($param2, 'business')) || (false !== strpos($param3, 'business')) )
												@if ($cat->id == $bus_cat_id)
													<div class="internal_nolink_cat">{{ $cat->name }}</div>
												@endif
											@else
												<label>
													@if ($param_count == 1)
														@if ( (false !== strpos($param1, 'indcat')) || (false !== strpos($param1, 'industory')) || (false !== strpos($param1, 'buscat')) || (false !== strpos($param1, 'business')) || (false !== strpos($param1, 'commit')) || (false !== strpos($param1, 'income')) )
															<a href="/job/list/buscat{{$cat->id}}" class="internal_link_cat">{{ $cat->name }}</a>
														@else
															<a href="/job/list/{{ $param1 }}/buscat{{ $cat->id }}" class="internal_link_cat">{{ $cat->name }}</a>
														@endif
													@elseif ($param_count == 2)
														@if ( (false !== strpos($param2, 'indcat')) || (false !== strpos($param2, 'industory')) || (false !== strpos($param2, 'buscat')) || (false !== strpos($param2, 'business')) || (false !== strpos($param2, 'commit')) || (false !== strpos($param2, 'income')))
															<a href="/job/list/{{ $param1 }}/buscat{{ $cat->id }}" class="internal_link_cat">{{ $cat->name }}</a>
														@else
															<a href="/job/list/{{ $param1 }}/{{ $param2 }}/buscat{{ $cat->id }}" class="internal_link_cat">{{ $cat->name }}</a>
														@endif
													@elseif ($param_count == 3)
														<a href="/job/list/{{ $param1 }}/{{ $param2 }}/buscat{{ $cat->id }}" class="internal_link_cat">{{ $cat->name }}</a>
													@else
														{{ html()->form('POST', '/job/list')->attribute('name', "buscatForm{$cat->id}")->open() }}
														{{ html()->hidden('business_cats', $cat->id) }}
														@if (!empty($param['locations']))
															@foreach ($param['locations'] as $val)
																{{ html()->hidden('locations[]', $val) }}
															@endforeach
														@endif
														@if (!empty($param['job_cats']))
															{{ html()->hidden('job_cats', $param['job_cats']) }}
														@endif
														@if (!empty($param['job_cat_details']))
															{{ html()->hidden('job_cat_details', $param['job_cat_details']) }}
														@endif
														{{ html()->form()->close() }}
														<a href="javascript:buscatForm{{ $cat->id }}.submit()"  class="internal_link_cat">{{ $cat->name }}</a>
													@endif
												</label><br>
											@endif

											@foreach ($valBusinessCatDetail as $detail)
												@if ($cat->id == $detail->business_cat_id)
													<label style="margin-bottom:10px;">
														@if ($param_count == 1)
															@if ( (false !== strpos($param1, 'indcat')) || (false !== strpos($param1, 'industory')) || (false !== strpos($param1, 'buscat')) || (false !== strpos($param1, 'business')) || (false !== strpos($param1, 'commit')) || (false !== strpos($param1, 'income')) )
																<a href="/job/list/business{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
															@else
																<a href="/job/list/{{ $param1 }}/business{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
															@endif
														@elseif ($param_count == 2)
															@if ( (false !== strpos($param2, 'indcat')) || (false !== strpos($param2, 'industory')) || (false !== strpos($param2, 'buscat')) || (false !== strpos($param2, 'business')) || (false !== strpos($param2, 'commit')) || (false !== strpos($param2, 'income')))
																<a href="/job/list/{{ $param1 }}/business{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
															@else
																<a href="/job/list/{{ $param1 }}/{{ $param2 }}/business{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
															@endif
														@elseif ($param_count == 3)
															<a href="/job/list/{{ $param1 }}/{{ $param2 }}/business{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
														@else
															{{ html()->form('POST', '/job/list')->attribute('name', "businessForm{$detail->id}")->open() }}
															{{ html()->hidden('business_cat_details', $detail->id) }}
															@if (!empty($param['locations']))
																@foreach ($param['locations'] as $val)
																	{{ html()->hidden('locations[]', $val) }}
																@endforeach
															@endif
															@if (!empty($param['job_cats']))
																{{ html()->hidden('job_cats', $param['job_cats']) }}
															@endif
															@if (!empty($param['job_cat_details']))
																{{ html()->hidden('job_cat_details', $param['job_cat_details']) }}
															@endif
															{{ html()->form()->close() }}
															<a href="javascript:businessForm{{ $detail->id }}.submit()"><div class="internal_link">{{ $detail->name }}</div></a>
														@endif
													</label>
												@endif
											@endforeach
											@if (empty($bus_cat_id))<br>@endif
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
										@foreach ($incomeList as $detail)
											<label style="margin-bottom:10px;">
												@if ($param_count == 1)
													@if ( (false !== strpos($param1, 'indcat')) || (false !== strpos($param1, 'industory')) || (false !== strpos($param1, 'buscat')) || (false !== strpos($param1, 'business')) || (false !== strpos($param1, 'commit')) || (false !== strpos($param1, 'income')) )
														<a href="/job/list/income{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
													@else
														<a href="/job/list/{{ $param1 }}/income{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
													@endif
												@elseif ($param_count == 2)
													@if ( (false !== strpos($param2, 'indcat')) || (false !== strpos($param2, 'industory')) || (false !== strpos($param2, 'buscat')) || (false !== strpos($param2, 'business')) || (false !== strpos($param2, 'commit')) || (false !== strpos($param2, 'income')))
														<a href="/job/list/{{ $param1 }}/income{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
													@else
														<a href="/job/list/{{ $param1 }}/{{ $param2 }}/income{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
													@endif
												@elseif ($param_count == 3)
													<a href="/job/list/{{ $param1 }}/{{ $param2 }}/income{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
												@else
													{{ html()->form('POST', '/job/list')->attribute('name', "incomeForm{$detail->id}")->open() }}
													{{ html()->hidden('incomes', $detail->id) }}
													@if (!empty($param['locations']))
														@foreach ($param['locations'] as $val)
															{{ html()->hidden('locations[]', $val) }}
														@endforeach
													@endif
													@if (!empty($param['job_cats']))
														{{ html()->hidden('job_cats', $param['job_cats']) }}
													@endif
													@if (!empty($param['job_cat_details']))
														{{ html()->hidden('job_cat_details', $param['job_cat_details']) }}
													@endif
													{{ html()->form()->close() }}
													<a href="javascript:incomeForm{{ $detail->id }}.submit()"><div class="internal_link">{{ $detail->name }}</div></a>
												@endif
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
														@if ($param_count == 1)
															@if ( (false !== strpos($param1, 'indcat')) || (false !== strpos($param1, 'industory')) || (false !== strpos($param1, 'buscat')) || (false !== strpos($param1, 'business')) || (false !== strpos($param1, 'commit')) || (false !== strpos($param1, 'income')) )
																<a href="/job/list/commit{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
															@else
																<a href="/job/list/{{ $param1 }}/commit{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
															@endif
														@elseif ($param_count == 2)
															@if ( (false !== strpos($param2, 'indcat')) || (false !== strpos($param2, 'industory')) || (false !== strpos($param2, 'buscat')) || (false !== strpos($param2, 'business')) || (false !== strpos($param2, 'commit')) || (false !== strpos($param2, 'income')))
																<a href="/job/list/{{ $param1 }}/commit{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
															@else
																<a href="/job/list/{{ $param1 }}/{{ $param2 }}/commit{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
															@endif
														@elseif ($param_count == 3)
															<a href="/job/list/{{ $param1 }}/{{ $param2 }}/commit{{ $detail->id }}"><div class="internal_link">{{ $detail->name }}</div></a>
														@else
															{{ html()->form('POST', '/job/list')->attribute('name', "commitForm{$detail->id}")->open() }}
															{{ html()->hidden('commit_cat_details', $detail->id) }}
															@if (!empty($param['locations']))
																@foreach ($param['locations'] as $val)
																	{{ html()->hidden('locations[]', $val) }}
																@endforeach
															@endif
															@if (!empty($param['job_cats']))
																{{ html()->hidden('job_cats', $param['job_cats']) }}
															@endif
															@if (!empty($param['job_cat_details']))
																{{ html()->hidden('job_cat_details', $param['job_cat_details']) }}
															@endif
															{{ html()->form()->close() }}
															<a href="javascript:commitForm{{ $detail->id }}.submit()"><div class="internal_link">{{ $detail->name }}</div></a>
														@endif
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
