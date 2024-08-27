<section id="kodawari" class="items">
	<div class="kodawari_block">

{{-- クチコミ評価ランキング --}}
@php
	$rankingList = App\Models\Ranking::Join('companies','rankings.company_id','=','companies.id')
		->where('companies.open_flag' ,'1')
		->selectRaw('rankings.*, companies.name as company_name ,companies.logo_file as logo_file ,companies.image_file as image_file')
		->orderBy('total_point', 'DESC')
		->take(10)
		->get();

@endphp
	<div class="kodawari_box">
		<div class="top-ranking">
			<h3 class="top-section-title">クチコミ評価ランキング</h3>
			<ol class="top-ranking-list">

				@foreach ($rankingList as $ranking)
					<li class="top-ranking-list__item company-item">
						<figure class="company-item__image"><img src="{{ $ranking->logo_file }}" alt=""></figure>
						<div class="company-item__content">
							@if ($loop->iteration <= 3)
								<p class="company-item__name -rank{{ $loop->iteration }}">
							@else
								<p class="company-item__name">
							@endif
								<a href="/company/{{ $ranking->company_id }}">{{ $ranking->company_name }}</a>
							</p>
							<dl class="company-item__reviews">
								<dt>総合<br>評価</dt>
								<dd>
									<span>{{ number_format($ranking->total_point, 2) }}</span>
									<span class="star5_rating" style="--rate: {{ $ranking->total_rate . '%' }};"></span>
								</dd>
								<dt>クチコミ<br>件数</dt>
								<dd>{{ number_format($ranking->answer_count) }} 件</dd>
							</dl>
							<p class="company-item__button"><a href="/company/{{ $ranking->company_id }}">詳細を見る</a></p>
						</div>
					</li>
				@endforeach

			</ol>
			<p class="detail-link-button"><a href="/company/ranking">ランキング一覧を見る</a></p>
		</div>
	</div>


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

	<div class="kodawari_box">
            <!-- 新規追加 -->
		<div class="top-pickup">
			<h3 class="top-section-title">ピックアップ求人</h3>
			<ol class="top-pickup-list">

				@foreach ($randomJobList as $job)
@php
	$ranking = $job->getCompanyRanking();
@endphp
				<li class="top-pickup-list__item">
					<div class="company-item">
						<figure class="company-item__image"><img src="{{ $job->logo_file }}" alt=""></figure>
						<div class="company-item__content">
							<p class="company-item__name"><a href="/company/{{ $job->company_id }}">{{ $job->company_name }}</a></p>
							<dl class="company-item__reviews">
								<dt>総合評価</dt>
								<dd>
									<span>{{ number_format($ranking->total_point, 2) }}</span>
									<span class="star5_rating" style="--rate: {{ $ranking->total_rate . '%' }};"></span>
								</dd>
								<dt>クチコミ件数</dt>
								<dd>{{ number_format($ranking->answer_count) }} 件</dd>
							</dl>
						</div>
					</div>
					<div class="company-information">
						<div class="company-information__header">
							<h4 class="company-information__title">求人名</h4>
							<p class="company-information__position">{{ $job->name }}</p>
						</div>
						<dl class="company-information__info">
							<dt>年収</dt>
							<dd>{{ $job->getIncome() }}</dd>
							<dt>勤務地</dt>
							<dd>{{ $job->getLocations() }} @if (!empty($job->else_location))({{ $job->else_location }})@endif</dd>
						</dl>
						<h4 class="company-information__subtitle">業務内容</h4>
						<p class="company-information__description">{{ mb_strimwidth($job->intro, 0, 250, "...") }}</p>
						<p class="detail-link-button">
							<a class="" href="/company/{{ $job->company_id }}/{{ $job->id }}">求人詳細を見る</a>
						</p>
					</div>
				</li>
				@endforeach

			</ol>
			<p class="detail-link-button"><a href="/job">求人一覧を見る</a></p>
			</div>
		</div>
	</div>


	<div class="kodawari_block">
		<div class="top-search-link">

{{-- エリアから求人を探す --}}
			<div class="con-wrap search-link-list">
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


{{-- 職種から求人を探す --}}
			<div class="con-wrap search-link-list">
				<h2>職種から求人を探す</h2>
				<div class="job-item">
					<div class="area_box">
						<div class="form-wrap">
							<div class="form-block">
								<div class="job-check-box-btn">
									@foreach ($jobCat as $cat)
										<label>
											<a href="/job/list/jobcategory{{ $cat->id }}" class="internal_link_cat">{{ $cat->name }}</a>
										</label><br>

										@foreach ($jobCatDetail as $detail)
											@if ($cat->id == $detail->job_cat_id)
												<label>
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


			<div class="con-wrap search-link-list">
				<h2>特徴・こだわり</h2>
				<div class="job-item">


{{-- 担当業界から求人を探す --}}
					<div class="con-wrap">
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
														<label>
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


{{-- IT業界の業種から求人を探す --}}
					<div class="con-wrap">
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
														<label>
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



{{-- 年収から求人を探す --}}
					<div class="con-wrap">
						<h3>年収から求人を探す</h3>
						<div class="job-item">
							<div class="area_box">
								<div class="form-wrap">
									<div class="form-block">
										<div class="job-check-box-btn">
											@foreach ($incomeList as $income)
												<label>
													<a href="/job/list/income{{ $income->id }}"><div class="internal_link">{{ $income->name }}</div></a>
												</label>
											@endforeach
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


{{-- こだわりから求人を探す --}}
					<div class="con-wrap">
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
														<label>
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


				</div>
			</div>
		</div>
	</div>

</section>

