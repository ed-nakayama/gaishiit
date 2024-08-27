@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('cancellation') }}
@endsection

@section('addheader')
	<title>退会のお手続き｜{{ config('app.title') }}</title>
	<meta name="description" content="クチコミのある外資IT・外資コンサル企業の一覧ページです。転職に役立つ社員クチコミを集め、スコアを集計して提供しております。｜{{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="退会のお手続き｜{{ config('app.title') }}" />
	<meta property="og:description" content="クチコミのある外資IT・外資コンサル企業の一覧ページです。転職に役立つ社員クチコミを集め、スコアを集計して提供しております。｜{{ config('app.description') }}" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

@endsection


@section('content')

@include('user.user_activity')

<main class="pane-main">
	<div class="inner">
		<div class="ttl">
		</div>

		<div class="con-wrap">
			<div class="maypage-main">
				<div class="maypage-main__title">
					<h2>退会のお手続き</h2>
				</div>
				<div class="maypage-main__content">
					<h3 class="maypage-main__subTitle">退会前にご確認ください</h3>
					<div class="maypage-main__box">
						<ul class="maypage-confirm">
							<li class="maypage-confirm__item">
								<figure class="maypage-confirm__image"><img src="/img/mypage/icon-user-slash.svg" alt=""></figure>
								<p>ご登録いただいた履歴書や<br>職務経歴書のデータは<br>退会後に消失し復旧しません。 </p>
							</li>
							<li class="maypage-confirm__item">
								<figure class="maypage-confirm__image"><img src="/img/mypage/icon-file-slash.svg" alt=""></figure>
								<p>退会後に会員情報の復旧を<br>することはできません。 </p>
							</li>
							<li class="maypage-confirm__item">
								<figure class="maypage-confirm__image"><img src="/img/mypage/icon-comment.svg" alt=""></figure>
								<p>退会後も、口コミは削除されず<br>当サイトに掲載され続けます</p>
							</li>
						</ul>
					</div>
					<h3 class="maypage-main__subTitle">転職活動後もこのようにご利用いただいています</h3>
					<div class="maypage-main__box">
						<ul>
							<li>・取引先や競合の調査に口コミを利用する</li>
							<li>・他社の求人を見て今後のキャリアの年収を把握する</li>
							<li>・自社の評価を確認する</li>
						</ul>
					</div>
					<p class="maypage-main__caution">ご注意事項と活用メリットをご確認の上、退会を希望される場合は「退会する」ボタンを押してください。 </p>
					<div class="maypage-main__buttons form-button">
						<a href="/mypage" class="cancel">キャンセルする</a>
						<a href="javascript:cancelform.submit()" class="">退会する</a>
						{{ html()->form('POST', "/cancellation")->attribute('name', "cancelform")->open() }}
						{{ html()->form()->close() }}
					</div>
				</div>
			</div>
		</div><!-- con-wrap -->
	</div><!-- inner -->
</main> <!-- pane-main -->

@endsection
