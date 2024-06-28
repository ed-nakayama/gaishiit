		<section id="kodawari" class="items">
            <div class="kodawari_block">

{{-- クチコミ数ランキング --}}
@php
	$rankingList = App\Models\Ranking::Join('companies','rankings.company_id','=','companies.id')
		->where('companies.open_flag' ,'1')
		->selectRaw('rankings.*, companies.name as company_name ,companies.logo_file as logo_file ,companies.image_file as image_file')
		->orderBy('total_point', 'DESC')
		->take(10)
		->get();

@endphp
                 <div class="kodawari_box fadein">
                    <h3>クチコミ評価ランキング</h3>
					@foreach ($rankingList as $ranking)
						<div class="ranking">
							<div class="corp">
								<figure><img src="{{ $ranking->logo_file }}" alt=""></figure>
								<div class="inner">
									<p class="corp-name"><a href="/company/{{ $ranking->company_id }}" style="text-decoration:underline;">{{ $ranking->company_name }}</a></p>
									<div class="corp-rate">
										<p class="star5_rating" style="--rate: {{ $ranking->total_rate . '%' }};"></p>
										<p>総合評価：{{ number_format($ranking->total_point, 2) }}</p>
										<p>クチコミ数：{{ number_format($ranking->answer_count) }}</p>
										<p class="corp-button"><a href="/company/{{ $ranking->company_id }}">詳細</a></p>
									</div>
								</div>
							</div>
						</div>
					@endforeach
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
				<div class="kodawari_box fadein">
					<h3>ピックアップ求人</h3>

					@foreach ($randomJobList as $job)
@php
	$ranking = $job->getCompanyRanking();
@endphp
					 <div class="pickup">
						<div class="corp">
							<figure><img src="{{ $job->logo_file }}" alt=""></figure>
							<div class="inner">
								<p class="corp-name"><a href="/company/{{ $job->company_id }}" style="text-decoration:underline;">{{ $job->company_name }}</a></p>
								<div class="corp-rate">
									<p class="star5_rating" style="--rate: {{ $ranking->total_rate . '%' }};"></p>
									<p>総合評価：{{ number_format($ranking->total_point, 2) }}</p>
									<p>クチコミ数：{{ number_format($ranking->answer_count) }}</p>
								</div>
							</div>
						</div>
						<div class="corp">
							<div class="inner02">
								<p class="job-name">{{ $job->name }}</p>
								<div class="job-info">
									<div class="salary-location">
										<p class="salary">年収{{ $job->getIncome() }}</p>
										<p class="location">{{ $job->getLocations() }} @if (!empty($job->else_location))({{ $job->else_location }})@endif</p>
									</div>
								</div>
								<div class="job-discription">
									<p>{{ mb_strimwidth($job->intro, 0, 250, "...") }}</p>
								</div>
								<div class="job-button">
									<p><a href="/company/{{ $job->company_id }}/{{ $job->id }}">求人詳細</a></p>
								</div>
							</div>
						</div>
					</div>
					@endforeach
                </div>
            </div>


{{-- エリアから求人を探す --}}
			<div class="kodawari_block">
                <div class="kodawari_box fadein">
                    <h3>エリアから求人を探す</h3>
					<div class="serch-center">
						@foreach ($constLocation as $loc)
							<p><a href="/job/list/location{{ $loc->id }}">{{ $loc->name }}</a></p>
						@endforeach
					</div>
				</div>
            </div>

{{-- 職種から求人を探す --}}
			<div class="kodawari_block">
                <div class="kodawari_box fadein">
                    <h3>職種から求人を探す</h3>
					@foreach ($jobCat as $cat)
						<h4><a href="/job/list/jobcategory{{ $cat->id }}">{{ $cat->name }}</a></h4>
						 <div class="serch-left">
						@foreach ($jobCatDetail as $detail)
							@if ($cat->id == $detail->job_cat_id)
								 <p><a href="/job/list/occupation{{ $detail->id }}">{{ $detail->name }}</a></p>
							@endif
						@endforeach
						</div>
					@endforeach
				</div>
            </div>

			<div class="kodawari_ttl">
				<h2>特徴・こだわり</h2>
			</div>

{{-- 担当業界から求人を探す --}}
			<div class="kodawari_block">
                <div class="kodawari_box fadein">
                    <h3>担当業界から求人を探す</h3>
					@foreach ($industoryCat as $cat)
						<h4><a href="/job/list/indcat{{$cat->id}}">{{ $cat->name }}</a></h4>
						<div class="serch-left">
						@foreach ($industoryCatDetail as $detail)
							@if ($cat->id == $detail->industory_cat_id)
								<p><a href="/job/list/industory{{ $detail->id }}">{{ $detail->name }}</a></p>
							@endif
						@endforeach
						</div>
					@endforeach
				</div>
            </div>

{{-- T業界の業種から求人を探す --}}
            <div class="kodawari_block">
                <div class="kodawari_box fadein">
                    <h3>IT業界の業種から求人を探す</h3>
					@foreach ($businessCat as $cat)
						<h4><a href="/job/list/buscat{{$cat->id}}">{{ $cat->name }}</a></h4>
						<div class="serch-left">
						@foreach ($businessCatDetail as $detail)
							@if ($cat->id == $detail->business_cat_id)
								<p><a href="/job/list/business{{ $detail->id }}">{{ $detail->name }}</a></p>
							@endif
						@endforeach
						</div>
					@endforeach
				</div>
            </div>

{{-- 年収から求人を探す --}}
            <div class="kodawari_block">
				<div class="kodawari_box fadein">
                    <h3>年収から求人を探す</h3>
					 <div class="serch-center">
						@foreach ($incomeList as $income)
							<p><a href="/job/list/income{{ $income->id }}">{{ $income->name }}</a></p>
						@endforeach
					</div>
				</div>
            </div>

{{-- こだわりから求人を探す --}}
            <div class="kodawari_block">
                <div class="kodawari_box fadein">
                    <h3>こだわりから求人を探す</h3>
					@foreach ($commitCat as $cat)
						<h4 style="text-decoration:none;">{{ $cat->name }}</h4>
						<div class="serch-left">
						@foreach ($commitCatDetail as $detail)
							@if ($cat->id == $detail->commit_cat_id)
								<p><a href="/job/list/commit{{ $detail->id }}">{{ $detail->name }}</a></p>
							@endif
						@endforeach
						</div>
					@endforeach
				</div>
            </div>


        </section>

