@extends('layouts.user.auth')


@section('addheader')
	<title>個人設定｜{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/setting.css') }}" rel="stylesheet">
@endsection


@section('content')


@include('user.user_activity')

<main class="pane-main">
	<div class="inner">
		<div class="ttl">
			<h2>個人設定</h2>
		</div>
		<div class="con-wrap">

{{-- 情報登録 --}}
			<div class="item info">
				<div class="item-inner">

					<h3>情報登録</h3>
					
					<div class="info-list">
						<div class="item-block">
							<dl>
								<dt class="ttl">基本情報</dt>
								<dd class="stat">登録済み</dd>
							</dl>
							<button onclick="location.href='/base'">変更</button>
						</div>
						<div class="item-block">
							<dl>
								<dt class="ttl">職務経歴書</dt>
								@if ($user_act['cv_comp'] == '1')<dd class="stat">登録済み@else<dd class="stat yet">未完成@endif</dd>
							</dl>
							<button onclick="location.href='/cv'">変更</button>
						</div>
						<div class="item-block">
							<dl>
								<dt class="ttl">職務経歴書（英文）</dt>
								@if ($user_act['cv_eng_comp'] == '1')<dd class="stat">登録済み@else<dd class="stat yet">未完成@endif</dd>
							</dl>
							<button onclick="location.href='/cv/eng'">変更</button>
						</div>
						<div class="item-block">
							<dl>
								<dt class="ttl">履歴書</dt>
								@if ($user_act['vitae_comp'] == '1')<dd class="stat">登録済み@else<dd class="stat yet">未完成@endif</dd>
							</dl>
							<button onclick="location.href='/vitae'">変更</button>
						</div>
					</div>
				</div>
			</div>
{{-- END 情報登録 --}}



{{--メール受信設定 --}}
			<div class="item setting">
				<div class="item-inner">

					<h3>メール受信設定</h3>
					
					<div class="setting-list">
						{{ html()->form('POST', '/setting/store')->id('postform')->attribute('name', 'postform')->open() }}

						<div class="item-block">
							<p class="ttl">受信する通知内容</p>
							<div class="form-wrap">
								<div class="form-block">
									<p class="ttl-s">新しいメッセージを受信</p>

									<div class="form-inner">
										<div>
											<input type="checkbox" id="casual_mail_flag" name="casual_mail_flag" @if ($user->casual_mail_flag == '1') checked @endif>
											<label for="mendan">カジュアル面談</label>
										</div>
										<div>
											<input type="checkbox" id="formal_mail_flag" name="formal_mail_flag" @if ($user->formal_mail_flag == '1') checked @endif>
											<label for="oubo">正式応募</label>
										</div>
										<div>
											<input type="checkbox" id="event_mail_flag" name="event_mail_flag" @if ($user->event_mail_flag == '1') checked @endif>
											<label for="event">イベント</label>
										</div>
									</div>

								</div>
								<div class="form-block">
									<input type="checkbox" id="job_mail_flag" name="job_mail_flag" @if ($user->job_mail_flag == '1') checked @endif>
									<label for="job">保存した条件に新しい求人が追加</label>
								</div>
								<div class="form-block">
									<input type="checkbox" id="favorite_mail_flag" name="favorite_mail_flag" @if ($user->favorite_mail_flag == '1') checked @endif>
									<label for="favorite">お気に入りに登録した求人が更新</label>
								</div>
							</div>
						</div>

						<div class="btn-wrap">
							{{-- 更新成功メッセージ --}}
							<button type="submit">登録 / 変更</button>
							@if (session('update_success'))
								<p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-blue-400 dark:text-blue-400" style="color: blue;">{{session('update_success')}}</p>
							@endif
						</div>
						{{ html()->form()->close() }}
					</div>
				</div>
			</div>
{{--END メール受信設定 --}}

 {{-- メールアドレス変更 --}}
		   <div class="item setting">
				<div class="item-inner">

					<h3>メールアドレス変更</h3>

					<div class="setting-list">
						{{ html()->form('POST', "/email/change")->id('mailform')->attribute('name', 'mailform')->open() }}

						<div class="form-inner contact">
							<div class="contact-list">
								<div class="input-wrap">現メールアドレス：{{ $user->email }}</div>
								<div class="input-wrap">新メールアドレス：<input type="emai"  name="email" value="{{ old('email') }}"></div>
								@error('email')
									<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
								@enderror
							</div>

							<div class="btn-wrap">
								<!-- フラッシュメッセージ -->
								@if (session('success_message'))
									 <div class="alert alert-success"  style="color:#0000ff;">
										{{ session('success_message') }}
									</div>
								@endif
			 					@if (session('error_message'))
									 <div class="alert alert-success"  style="color:#ff0000;">
										{{ session('error_message') }}
									</div>
								@endif
								<button type="submit">変更</button>
								@if (session('success_message'))
									<p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-blue-400 dark:text-blue-400" style="color: blue;">{{ session('success_message') }}</p>
								@endif
							</div>
						</div>
						{{ html()->form()->close() }}
					</div>

				</div>
			</div>
{{-- END メールアドレス変更 --}}

		</div>
	</div>
</main>

@endsection
