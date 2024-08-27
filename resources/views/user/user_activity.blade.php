@php

	//現在の希望条件
	$searchUserHist = App\Models\SearchUserHist::where('user_id' ,Auth::guard('user')->user()->id)->first();

	// バナー
	$bannerList = App\Models\Banner::leftJoin('companies', 'banners.company_id', 'companies.id')
		->selectRaw('banners.* , companies.name as company_name')
		->where('banners.id', '>', '1')
		->orderBy('banners.id')
		->get();


@endphp

<div class="pane-leftbar">
	<nav class="maypage-sidenav">
		<p class="maypage-sidenav__name">{{ Auth::guard('user')->user()->name }}</p>
		<p class="maypage-sidenav__id">お客様番号 : {{ Auth::guard('user')->user()->nick_name }}</p>
		<figure class="maypage-sidenav__graph">
			<figcaption>プロフィール完成度</figcaption>
			<img src="/img/{{ $user_act['rate']  }}.png" alt="">
		</figure>
		<p class="maypage-sidenav__settingButton mypage-button">
			<a href="/setting" class="edit">もっと充実させる</a>
		</p>
		<div class="maypage-sidenav__box">
			<ul class="maypage-sidenav__messageLinks">
				<li>
					{{ html()->form('POST', '/interview/list')->id('actallform')->attribute('name', 'actallform')->open() }}
					<a href="javascript:actallform.submit()" class="unread">全てのメッセージ一覧</a>
					{{ html()->form()->close() }}
				</li>
				<li>
					{{ html()->form('POST', '/interview/list')->id('actcasform')->attribute('name', 'actcasform')->open() }}
					{{ html()->hidden('interview_type', '0') }}
					<a href="javascript:actcasform.submit()" class="interview">カジュアル面談のメッセージ一覧</a>
					{{ html()->form()->close() }}
				</li>
				<li>
					{{ html()->form('POST', '/interview/list')->id('actformform')->attribute('name', 'actformform')->open() }}
					{{ html()->hidden('interview_type', '1') }}
					<a href="javascript:actformform.submit()" class="entry">正式応募のメッセージ一覧</a>
					{{ html()->form()->close() }}
				</li>
@if (config('const.event_disp'))
				<li>
					{{ html()->form('POST', '/interview/list')->id('acteventform')->attribute('name', 'acteventform')->open() }}
					{{ html()->hidden('interview_type', '2') }}
					<a href="javascript:acteventform.submit()" class="event">イベントのメッセージ一覧</a>
					{{ html()->form()->close() }}
				</li>
@endif
			</ul>
		</div>
		<p class="maypage-sidenav__favoriteButton mypage-button">
			<a href="/job/favorite" class="job">お気に入り求人一覧</a>
		</p>
	</nav>
	<nav class="maypage-sidenav">
		<p class="maypage-sidenav__name">現在の希望条件</p>
		<div class="maypage-sidenav__box">
			<dl class="maypage-sidenav__dl">
				@if (!empty($searchUserHist->freeword))
					<dt style="width:140px;white-space:nowrap;">フリーワード</dt>
					<dd>{{ $searchUserHist->freeword }}</dd>
				@endif
				@if (!empty($searchUserHist->locations))
					<dt style="width:140px;white-space:nowrap;">エリア</dt>
					<dd>{{ $searchUserHist->getLocations() }}</dd>
				@endif
				@if (!empty($searchUserHist->comps))
					<dt style="width:140px;white-space:nowrap;">企業名</dt>
					<dd>{{ $searchUserHist->getCompanyName() }}</dd>
				@endif
				@if (!empty($searchUserHist->job_cats))
					<dt style="width:140px;white-space:nowrap;">職種</dt>
					<dd>{{ $searchUserHist->getJobCatName() }}</dd>
				@endif
				@if (!empty($searchUserHist->job_cat_details))
					<dt style="width:140px;white-space:nowrap;">職種詳細</dt>
					<dd>{{ $searchUserHist->getJobCategoryName() }}</dd>
				@endif
				@if (!empty($searchUserHist->industory_cats))
					<dt style="width:140px;white-space:nowrap;">担当業界</dt>
					<dd>{{ $searchUserHist->getIndcatName() }}</dd>
				@endif
				@if (!empty($searchUserHist->industory_cat_details))
					<dt style="width:140px;white-space:nowrap;">担当業界詳細</dt>
					<dd>{{ $searchUserHist->getIndustoryName() }}</dd>
				@endif
				@if (!empty($searchUserHist->business_cats))
					<dt style="width:140px;white-space:nowrap;">IT業界の業種</dt>
					<dd>{{ $searchUserHist->getBuscatName() }}</dd>
				@endif
				@if (!empty($searchUserHist->business_cat_details))
					<dt style="width:140px;white-space:nowrap;">IT業界の業種詳細</dt>
					<dd>{{ $searchUserHist->getBusinessName() }}</dd>
				@endif
				@if (!empty($searchUserHist->incomes))
					<dt style="width:140px;white-space:nowrap;">年収</dt>
					<dd>{{ $searchUserHist->getIncome() }}</dd>
				@endif
				@if (!empty($searchUserHist->commit_cat_details))
					<dt style="width:140px;white-space:nowrap;">こだわり</dt>
					<dd>{{ $searchUserHist->getCommitName() }}</dd>
				@endif
			</dl>
		</div>
		<p class="maypage-sidenav__settingButton mypage-button">
			<a href="/job" class="edit">編集する</a>
		</p>
	</nav>

	 <nav class="attention">

		<div class="inner">

@if (!empty($bannerList[0]))

			<div class="terms">
				<div class="top">
					注目の企業
				</div>
				<div class="company-list">

					@foreach ($bannerList as $banner)
						@if (!empty($banner->company_id) )
							@if ( empty($banner->url) )
								<a>
								@if ( empty($banner->image) )
									<img src="/img/setting/img_com_01.jpg" alt="" style="width: 300pxpx;height: 120px;object-fit: cover;">
								@else
									<img src="{{ $banner->image }}" alt="" style="width: 300pxpx;height: 120px;object-fit: cover;">
								@endif
							@else
								<a href="{{ $banner->url }}">
								@if ( empty($banner->image) )
									<img src="/img/setting/img_com_01.jpg" alt="">
								@else
									<img src="{{ $banner->image }}" a" style="width: 300pxpx;height: 120px;object-fit: cover;">
								@endif
							@endif
							<p style="transform: rotate(0.03deg);">{{ $banner->company_name }}</p>
							</a>
						@endif
					@endforeach

				</div>
			</div>
@endif
		</div>
	</nav>

</div>
