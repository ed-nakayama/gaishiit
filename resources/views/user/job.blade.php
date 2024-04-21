@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('job_search') }}
@endsection


@section('addheader')
	<title>求人を探す｜{{ config('app.title') }}</title>
	<meta name="description" content="外資IT.comに掲載中のすべての求人情報を検索いただけます。絞り込み条件としてエリアや年収や企業名だけではなく、細かな職種やこだわり条件もご用意しております。｜{{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="求人を探す｜{{ config('app.title') }}" />
	<meta property="og:description" content="外資IT.comに掲載中のすべての求人情報を検索いただけます。絞り込み条件としてエリアや年収や企業名だけではなく、細かな職種やこだわり条件もご用意しております。｜{{ config('app.description') }}" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

    <link href="{{ asset('css/job.css') }}" rel="stylesheet">
@endsection


@section('content')


@if (Auth::guard('user')->check())
@include('user.user_activity')
@endif


	<main class="pane-main">
		<div class="inner">
			<div class="ttl">
				<h1>求人を探す</h1>
			</div>

			<div class="con-wrap">

				<div class="item setting">
					<div class="item-inner">

						{{ html()->form('POST', '/job/list')->attribute('name', 'jobform')->open() }}
						<div class="setting-list">
							<div class="item-block">
								<p class="ttl">フリーワード</p>
								<div class="form-inner contact">
									<div class="contact-list">
										<div class="input-wrap">
											{{ html()->text('freeword', $searchUserHist->freeword) }}
										</div>
									</div>
								</div>
							</div>
    
							<div class="item-block">
								<p class="ttl">エリア</p>
								<div class="form-wrap">
									<div class="form-block">
										<div class="form-inner">
											<div class="check-box-btn">
												@foreach ($constLocation as $loc)
													<label>
														{{ html()->checkbox("locations[]", strstr($searchUserHist->locations ,$loc->id), $loc->id)->id('locations') }}
														<span>{{ $loc->name }}</span>
													</label>
												@endforeach
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="item-block">
								<p class="ttl">企業名</p>
								<div class="form-wrap">
									<div class="form-block">
										<div class="form-inner">
											<div class="check-box-btn">
												<ul id="comp_list">
												</ul>
												{{ html()->hidden('comps', $searchUserHist->comps)->id('comps') }}
												<a  class="openModalName button-modal" href="#modalAreaName">変更</a>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="item-block">
								<p class="ttl">職種</p>
								<div class="form-wrap">
									<div class="form-block">
										<div class="form-inner">
											<div class="check-box-btn">
												{{ html()->hidden('job_cats', $searchUserHist->getJobCat() )->id('job_cats') }}
												{{ html()->hidden('job_cat_details', $searchUserHist->getJobCategory() )->id('job_cat_details') }}
												<ul id="jobcat_name_list">
												</ul>
												<a  class="openModalJob button-modal" href="#modalAreaJob">変更</a>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>

						<br>
						<label style="font-size:14px;">＋特徴・こだわりで絞り込む</label>
						<div class="item-inner">
							<div class="setting-list">

								<div class="item-block">
									<p class="ttl">担当業界</p>
									<div class="form-wrap">
										<div class="form-block">
											<div class="form-inner">
												<div class="check-box-btn">
													{{ html()->hidden('industory_cats' ,$searchUserHist->getIndcatCat())->id('industory_cats') }}
													{{ html()->hidden('industory_cat_details', $searchUserHist->getIndustory())->id('industory_cat_details') }}
													<ul id="industorycat_list">
													</ul>
													<a  class="openModalIndustory button-modal" href="#modalAreaIndustory">変更</a>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="item-block">
									<p class="ttl">IT業界の業種</p>
									<div class="form-wrap">
										<div class="form-block">
											<div class="form-inner">
												<div class="check-box-btn">
													{{ html()->hidden('business_cats', $searchUserHist->getBusCat())->id('business_cats') }}
													{{ html()->hidden('business_cat_details', $searchUserHist->getBusiness())->id('business_cat_details') }}
													<ul id="buscat_list">
													</ul>
													<a  class="openModalBussiness button-modal" href="#modalAreaBussiness">変更</a>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="item-block">
									<p class="ttl">年収</p>
									<div class="form-wrap">
										<div class="form-block">
											<div class="form-inner">
												<div class="check-box-btn">
													@foreach ($incomeList as $income)
														<label style="margin-bottom:10px;">
															{{ html()->checkbox("incomes[]", strstr($searchUserHist->incomes ,$income->id), $income->id)->id('incomes') }}
															<span>{{ $income->name }}</span>
														</label>
													@endforeach
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="item-block">
									<p class="ttl">こだわり</p>
									<div class="form-wrap">
										<div class="form-block">
											<div class="form-inner">
												<div class="check-box-btn">
													{{ html()->hidden('commit_cat_details', $searchUserHist->getCommit())->id('commit_cat_details') }}
													<ul id="commitcat_list">
													</ul>
													<a  class="openModalCommit button-modal" href="#modalAreaCommit">変更</a>
												</div>
											</div>
										</div>
									</div>
								</div>

							</div><!-- setting-list -->
						</div><!-- internal item-inner -->
                                           
						{{ html()->hidden('save_flag') }}

						<div class="button-flex">
							<a href="javascript:void(0);" onclick="onSearchClick();">検索</a>
@if (Auth::guard('user')->check())
							<a href="javascript:void(0);" onclick="onLinkClick();">希望条件として保存</a>
@endif
						</div>
						{{ html()->form()->close() }}

					</div><!-- item-inner -->
				</div><!-- item setting -->

@if (empty($jobList[0]))
				<div class="item info job">
					<center>該当なし</center>
				</div>
@else
				<div class="item info job">
					{{-- ジョブフォーマット $jobList --}}
						@include ('user/partials/job_format_loop')
					{{-- END ジョブフォーマット --}}
				</div>

				<div class="pager">
					<ul class="page" style="margin-top:0px;margin-bottom:0px;">
						{{ $jobList->appends(request()->query())->links('pagination.user') }}
					</ul>
				</div>
@endif

{{-- クチコミ数ランキング --}}
	@include ('user/partials/eval_ranking_fix')
{{-- END クチコミ数ランキング --}}

{{-- 3種 求人検索 --}}
	@include ('user/partials/job_search_3type')
{{-- END 3種 求人検索ボタン --}}

			</div><!-- con-wrap -->
		</div><!-- inner -->
	</main>


{{-- 企業選択モーダル  --}}
	@include ('user/partials/job_company_modal')
{{-- END 企業選択モーダル --}}


{{-- 職種選択モーダル  --}}
	@include ('user/partials/job_category_modal')
{{-- END 職種選択モーダル --}}

{{-- インダストリ選択モーダル  --}}
	@include ('user/partials/job_industory_modal')
{{-- END インダストリ選択モーダル  --}}


{{-- 業種選択モーダル  --}}
	@include ('user/partials/job_business_modal')
{{-- END 業種選択モーダル  --}}

{{-- こだわり選択モーダル  --}}
	@include ('user/partials/job_commit_modal')
{{-- END こだわり選択モーダル  --}}

<script>


function onSearchClick() {

	var action_url = "{{ url('') }}" + '/job/list';

	// ロケーション
	var locations = document.jobform.locations;
	var locations_val = '';
	var locations_cnt = 0;

	for (var arg = 0; arg < locations.length; arg++) {
 		if (locations[arg].checked) {
			locations_val = locations[arg].value;
	 		locations_cnt++;
		}
	}

	// 職種１
	var job_cats = document.jobform.job_cats.value;
	var job_cats_cnt = 0;

	if (job_cats != '') {
		job_cats_cnt = job_cats.split(',').length;
	}

	// 職種２
	var job_cat_details = document.jobform.job_cat_details.value;
	var job_cat_details_cnt = 0;

	if (job_cat_details != '') {
		job_cat_details_cnt = job_cat_details.split(',').length;
	}

	// インダストリ１
	var industory_cats = document.jobform.industory_cats.value;
	var industory_cats_cnt = 0;

	if (industory_cats != '') {
		industory_cats_cnt = industory_cats.split(',').length;
	}

	// インダストリ２
	var industory_cat_details = document.jobform.industory_cat_details.value;
	var industory_cat_details_cnt = 0;

	if (industory_cat_details != '') {
		industory_cat_details_cnt = industory_cat_details.split(',').length;
	}

	// 業種１
	var business_cats = document.jobform.business_cats.value;
	var business_cats_cnt = 0;

	if (business_cats != '') {
		business_cats_cnt = business_cats.split(',').length;
	}

	// 業種２
	var business_cat_details = document.jobform. business_cat_details.value;
	var business_cat_details_cnt = 0;

	if ( business_cat_details != '') {
		 business_cat_details_cnt = business_cat_details.split(',').length;
	}

	// 年収
	var incomes = document.jobform.incomes;
	var incomes_val = '';
	var incomes_cnt = 0;

	for (var arg = 0; arg < incomes.length; arg++) {
 		if (incomes[arg].checked) {
			incomes_val = incomes[arg].value;
	 		incomes_cnt++;
		}
	}

	// こだわり
	var commit_cat_details = document.jobform.commit_cat_details.value;
	var commit_cat_details_cnt = 0;

	if (commit_cat_details != '') {
		commit_cat_details_cnt = commit_cat_details.split(',').length;
	}

	{{-- ************* 1語検索 ************ --}}
	// エリア
	if        ( (locations_cnt == 1) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0)&& (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/location' + locations_val;

	// 職種１
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 1) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/jobcategory' + job_cats;

	// 職種２
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 1) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/occupation' + job_cat_details;

	// インダストリ１
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 1) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/indcat' + industory_cats;

	// インダストリ２
 	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 1) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/industory' + industory_cat_details;

	// 業種１
 	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 1) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/buscat' + business_cats;

	// 業種２
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 1) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/business' + business_cat_details;

	// こだわり
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 1) && (incomes_cnt == 0) ) {
		action_url = action_url + '/commit' + commit_cat_details;

	// 年収
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 1) ) {
		action_url = action_url + '/income' + incomes_val;


	{{-- ************* 2語検索 ************ --}}
	{{-- *** エリア *** --}}
	// エリア x 職種１
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 1) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/location' + locations_val + '/jobcategory' + job_cats;

	// エリア x 職種２
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 0) && (job_cat_details_cnt == 1) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/location' + locations_val + '/occupation' + job_cat_details;

	// エリア x インダストリ１
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 1) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/location' + locations_val + '/indcat' + industory_cats;

	// エリア x インダストリ２
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 1) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/location' + locations_val + '/industory' + industory_cat_details;

	// エリア x 業種１
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 1) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/location' + locations_val + '/buscat' + business_cats;

	// エリア x 業種２
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 1) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/location' + locations_val + '/business' + business_cat_details;
	
	// エリア x こだわり２
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 1) && (incomes_cnt == 0) ) {
		action_url = action_url + '/location' + locations_val + '/commit' + commit_cat_details;
	
	// エリア x 年収
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 1) ) {
		action_url = action_url + '/location' + locations_val + '/income' + incomes_val;

	{{-- ***  職種１ *** --}}
	// 職種１ x インダストリ１
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 1) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 1) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/jobcategory' + job_cats + '/indcat' + industory_cats;

	// 職種１ x インダストリ２
 	} else if ( (locations_cnt == 0) && (job_cats_cnt == 1) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 1) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/jobcategory' + job_cats + '/industory' + industory_cat_details;

	// 職種１ x 業種１
 	} else if ( (locations_cnt == 0) && (job_cats_cnt == 1) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 1) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/jobcategory' + job_cats + '/buscat' + business_cats;

	// 職種１ x 業種２
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 1) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 1) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/jobcategory' + job_cats + '/business' + business_cat_details;

	// 職種１ x こだわり
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 1) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 1) && (incomes_cnt == 0) ) {
		action_url = action_url + '/jobcategory' + job_cats + '/commit' + commit_cat_details;

	// 職種１ x 年収
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 1) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 1) ) {
		action_url = action_url + '/jobcategory' + job_cats + '/income' + incomes_val;

	{{-- ***  職種2 *** --}}
	// 職種２ x インダストリ１
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 1) && (industory_cats_cnt == 1) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/occupation' + job_cat_details + '/indcat' + industory_cats;

	// 職種２ x インダストリ２
 	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 1) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 1) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/occupation' + job_cat_details + '/industory' + industory_cat_details;

	// 職種２ x 業種１
 	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 1) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 1) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/occupation' + job_cat_details + '/buscat' + business_cats;

	// 職種２ x 業種２
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 1) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 1) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/occupation' + job_cat_details + '/business' + business_cat_details;

	// 職種２ x こだわり
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 1) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 1) && (incomes_cnt == 0) ) {
		action_url = action_url + '/occupation' + job_cat_details + '/commit' + commit_cat_details;

	// 職種２ x 年収
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 1) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 1) ) {
		action_url = action_url + '/occupation' + job_cat_details + '/income' + incomes_val;

	{{-- ***  インダストリ１ *** --}}
{{--
	// インダストリ１ x 業種１
 	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 1) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 1) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/indcat' + industory_cats + '/buscat' + business_cats;

	// インダストリ１ x 業種２
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 1) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 1) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/indcat' + industory_cats + '/business' + business_cat_details;

	// インダストリ１ x こだわり
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 1) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 1) && (incomes_cnt == 0) ) {
		action_url = action_url + '/indcat' + industory_cats + '/commit' + commit_cat_details;

	// インダストリ１ x 年収
	// 職種２ x 年収
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 1) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 1) ) {
		action_url = action_url + '/indcat' + industory_cats + '/income' + incomes_val;
--}}
	{{-- ***  インダストリ２ *** --}}
{{--
	// インダストリ２ x 業種１
 	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 1) && (business_cats_cnt == 1) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/industory' + industory_cat_details + '/buscat' + business_cats;

	// インダストリ２ x 業種２
 	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 1) && (business_cats_cnt == 0) && (business_cat_details_cnt == 1) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/industory' + industory_cat_details + '/business' + business_cat_details;

	// インダストリ２ x こだわり
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 1) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 1) && (incomes_cnt == 0) ) {
		action_url = action_url + '/industory' + industory_cat_details + '/commit' + commit_cat_details;

	// インダストリ２ x 年収
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 1) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 1) ) {
		action_url = action_url + '/industory' + industory_cat_details + '/income' + incomes_val;
--}}
	{{-- ***  業種１ *** --}}
{{--
	// 業種１ x こだわり
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 1) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 1) && (incomes_cnt == 0) ) {
		action_url = action_url + '/indcat' + industory_cats + '/commit' + commit_cat_details;

	// 業種１ x 年収
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 1) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 1) ) {
		action_url = action_url + '/indcat' + industory_cats + '/income' + incomes_val;
--}}

	{{-- ***  業種２ *** --}}
{{--
	// 業種２ x こだわり
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 1) && (commit_cat_details_cnt == 1) && (incomes_cnt == 0) ) {
		action_url = action_url + '/business' + business_cat_details + '/commit' + commit_cat_details;

	// 業種２ x 年収
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 1) && (commit_cat_details_cnt == 0) && (incomes_cnt == 1) ) {
		action_url = action_url + '/business' + business_cat_details + '/income' + incomes_val;
--}}
	{{-- ***  こだわり *** --}}
{{--
	// こだわり x 年収
	} else if ( (locations_cnt == 0) && (job_cats_cnt == 0) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 1) && (incomes_cnt == 1) ) {
		action_url = action_url + '/commit' + commit_cat_details + '/income' + incomes_val;
--}}

	{{-- ************* 3語検索 ************ --}}
	// エリア x 職種１ x インダストリ１
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 1) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 1) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/location' + locations_val + '/jobcategory' + job_cats + '/indcat' + industory_cats;

	// エリア x 職種１ x インダストリ２
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 1) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 1) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/location' + locations_val + '/jobcategory' + job_cats + '/industory' + industory_cat_details;

	// エリア x 職種１ x 業種１
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 1) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 1) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/location' + locations_val + '/jobcategory' + job_cats + '/buscat' + business_cats;

	// エリア x 職種１ x 業種２
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 1) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 1) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/location' + locations_val + '/jobcategory' + job_cats + '/business' + business_cat_details;

	// エリア x 職種１ x こだわり
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 1) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 1) && (incomes_cnt == 0) ) {
		action_url = action_url + '/location' + locations_val + '/jobcategory' + job_cats + '/commit' + commit_cat_details;

	// エリア x 職種１ x 年収
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 1) && (job_cat_details_cnt == 0) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 1) ) {
		action_url = action_url + '/location' + locations_val + '/jobcategory' + job_cats + '/income' + incomes_val;


	// エリア x 職種２ x インダストリ１
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 0) && (job_cat_details_cnt == 1) && (industory_cats_cnt == 1) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_ur + '/location' + locations_val + '/occupation' + job_cat_details + '/indcat' + industory_cats;

	// エリア x 職種２ x インダストリ２
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 0) && (job_cat_details_cnt == 1) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 1) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/location' + locations_val + '/occupation' + job_cat_details + '/industory' + industory_cat_details;

	// エリア x 職種２ x 業種１
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 0) && (job_cat_details_cnt == 1) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 1) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/location' + locations_val + '/occupation' + job_cat_details + '/buscat' + business_cats;

	// エリア x 職種２ x 業種２
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 0) && (job_cat_details_cnt == 1) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 1) && (commit_cat_details_cnt == 0) && (incomes_cnt == 0) ) {
		action_url = action_url + '/location' + locations_val + '/occupation' + job_cat_details + '/business' + business_cat_details;

	// エリア x 職種２ x こだわり
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 0) && (job_cat_details_cnt == 1) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 1) && (incomes_cnt == 0) ) {
		action_url = action_url + '/location' + locations_val + '/occupation' + job_cat_details + '/commit' + commit_cat_details;

	// エリア x 職種２ x 年収
	} else if ( (locations_cnt == 1) && (job_cats_cnt == 0) && (job_cat_details_cnt == 1) && (industory_cats_cnt == 0) && (industory_cat_details_cnt == 0) && (business_cats_cnt == 0) && (business_cat_details_cnt == 0) && (commit_cat_details_cnt == 0) && (incomes_cnt == 1) ) {
		action_url = action_url + '/location' + locations_val + '/occupation' + job_cat_details + '/income' + incomes_val;

	}

//alert(job_cats_cnt + ':' + job_cat_details_cnt + ':' + industory_cat_parents_cnt + ':' + bus_cat_parents_cnt + ':' + locations_cnt + ':'  + commit_cats_cnt + ':' + incomes_cnt);

	document.jobform.action = action_url;
	document.jobform.submit();
}


function onLinkClick() {

	document.jobform.save_flag.value = "1";
	document.jobform.submit();
}


function getValList(lists) {

    var vals   = new Array();
    var titles = new Array();

	for (let i = 0; i < lists.length; ++i) {
		vals[i]   = lists[i]['val'];
		titles[i] = lists[i]['title'];
	};

    return $.makeArray(vals).join(',');
}

function getTitle(lists, titleList, flag) {

	for (let i = 0; i < lists.length; ++i) {

		if (flag ==1) {
			titleList = titleList  +  "<li><span style='font-weight: bold;'>" + lists[i]['title'] +  "</span></li>\n";
		} else {
			titleList = titleList  +  "<li><span>" + lists[i]['title'] +  "</span></li>\n";
		}
	};

    return titleList;
}


/////////////////////////////////////////////////////////
// 初回起動
/////////////////////////////////////////////////////////
$(document).ready(function() {

	// 選択企業対応
	ResetComp();
	putComp();

	// 選択職種対応
	ResetJob();
	putJob();
	
	// 選択業種対応
	ResetBus();
	putBus();

	// 選択インダストリ対応
	ResetIndustory();
	putIndustory();

	// 選択こだわり対応
	ResetCommit();
	putCommit();

});

</script>


<script src="{{ asset('js/job.js') }}"></script>

@endsection
