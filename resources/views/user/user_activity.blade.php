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
	<nav>
		<div class="inner">

			<div class="user-info">
				<!-- <figure class="user-icon">
					<img src="/img/user_ico_01.png" alt="">
				</figure> -->
				<p class="name">{{ Auth::guard('user')->user()->name }}</p>
				<p class="id" style="transform: rotate(0.03deg);">お客様番号 : {{ Auth::guard('user')->user()->nick_name }}</p>
				<figure class="user-icon">
					<img src="/img/{{ $user_act['rate']  }}.png" alt="">
				</figure>
			</div>
			<div class="user-prof" style="transform: rotate(0.03deg);">
				<p >プロフィール完成度</p>
				<a href="/setting" class="edit">もっと充実させる</a>
			</div>
			<div class="user-btn" style="transform: rotate(0.03deg);">
				{{ html()->form('POST', '/interview/list')->id('actallform')->attribute('name', 'actallform')->open() }}
				<a href="javascript:actallform.submit()" class="unread">@if ($user_act['unread_cnt'] > 0) 未読のメッセージがあります<span class="counts">{{ $user_act['unread_cnt'] }}</span> @else メッセージ@endif</a>
				{{ html()->form()->close() }}
				<ul>
					<li>
						{{ html()->form('POST', '/interview/list')->id('actcasform')->attribute('name', 'actcasform')->open() }}
						{{ html()->hidden('interview_type', '0') }}
						<a href="javascript:actcasform.submit()" class="interview">カジュアル面談</a>
						{{ html()->form()->close() }}
					</li>
					<li>
						{{ html()->form('POST', '/interview/list')->id('actformform')->attribute('name', 'actformform')->open() }}
						{{ html()->hidden('interview_type', '1') }}
						<a href="javascript:actformform.submit()" class="entry">正式応募</a>
						{{ html()->form()->close() }}
					</li>
					<li>
						{{ html()->form('POST', '/interview/list')->id('acteventform')->attribute('name', 'acteventform')->open() }}
						{{ html()->hidden('interview_type', '2') }}
						<a href="javascript:acteventform.submit()" class="event">イベント</a>
						{{ html()->form()->close() }}
					</li>
					<li>
						<a href="/job/favorite"" class="job">お気に入り</a>
					</li>
				</ul>
			</div>
		</div>

	</nav>

	<nav>

		<div class="inner">

			<div class="terms">
				<div class="top">
					現在の希望条件
					<a href="/job" class="edit">編集する</a>
				</div>
				<div class="tag-list" style="word-break: break-all;">
				@if (!empty($searchUserHist->freeword))
					<a>{{ $searchUserHist->freeword }}</a>
				@endif
				@if (!empty($searchUserHist->locations))
					<a>{{ $searchUserHist->getLocations() }}</a>
				@endif
				@if (!empty($searchUserHist->comps))
					<a>{{ $searchUserHist->getCompanyName() }}</a>
				@endif
				@if (!empty($searchUserHist->job_cats))
					<a>{{ $searchUserHist->getJobCatName() }}</a>
				@endif
				@if (!empty($searchUserHist->job_cat_details))
					<a>{{ $searchUserHist->getJobCategoryName() }}</a>
				@endif
				@if (!empty($searchUserHist->industory_cats))
					<a>{{ $searchUserHist->getIndcatName() }}</a>
				@endif
				@if (!empty($searchUserHist->industory_cat_details))
					<a>{{ $searchUserHist->getIndustoryName() }}</a>
				@endif
				@if (!empty($searchUserHist->business_cats))
					<a>{{ $searchUserHist->getBuscatName() }}</a>
				@endif
				@if (!empty($searchUserHist->business_cat_details))
					<a>{{ $searchUserHist->getBusinessName() }}</a>
				@endif
				@if (!empty($searchUserHist->incomes))
					<a>{{ $searchUserHist->getIncome() }}</a>
				@endif
				@if (!empty($searchUserHist->commit_cat_details))
					<a>{{ $searchUserHist->getCommitName() }}</a>
				@endif
				</div>
			</div>

		</div>

	</nav>

	 <nav class="attention">

		<div class="inner">

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

		</div>

	</nav>

</div>
