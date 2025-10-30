@extends('layouts.user.auth')


@section('addheader')
	@if ($interview->interview_type == '0')
		<title>カジュアル面談｜{{ config('app.title') }}</title>
		<meta name="description" content="カジュアル面談｜{{ config('app.description') }}">
		<meta property="og:title" content="カジュアル面談｜{{ config('app.title') }}">
		<meta property="og:description" content="カジュアル面談｜{{ config('app.description') }}">
	@elseif ($interview->interview_type == '1')
		<title>正式応募｜{{ config('app.title') }}</title>
		<meta name="description" content="正式応募｜{{ config('app.description') }}">
		<meta property="og:title" content="正式応募｜{{ config('app.title') }}">
		<meta property="og:description" content="正式応募｜{{ config('app.description') }}">
	@elseif ($interview->interview_type == '2')
		<title>イベント｜{{ config('app.title') }}</title>
		<meta name="description" content="イベント｜{{ config('app.description') }}">
		<meta property="og:title" content="イベント｜{{ config('app.title') }}">
		<meta property="og:description" content="イベント｜{{ config('app.description') }}">
	@else
		<title>？？？？｜{{ config('app.title') }}</title>
		<meta name="description" content="？？？？｜{{ config('app.description') }}">
		<meta property="og:title" content="？？？？｜{{ config('app.title') }}">
		<meta property="og:description" content="？？？？｜{{ config('app.description') }}">
	@endif

	<meta property="og:type" content="article" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />


	<link href="{{ asset('css/interview.css') }}" rel="stylesheet">
@endsection


@section('content')

<style>
.scroll{
  height: 350px;
  overflow: auto;
}
</style>


@include('user.user_activity')

<main class="pane-main">
	<div class="inner">
		<div class="ttl">
			@if ($interview->interview_type == '0')
				<h2>カジュアル面談</h2>
			@elseif ($interview->interview_type == '1')
				<h2>正式応募</h2>
			@elseif ($interview->interview_type == '2')
				<h2>イベント</h2>
			@else
				<h2>？？？？</h2>
			@endif
		</div>

		<div class="con-wrap">

			<div class="item">
				<div class="item-inner">
					<div class="date">
						<dl>
							<dt>依頼日：</dt>
							<dd>{{ str_replace('-', '/' ,substr($interview->created_at, 0, 16)) }}</dd>
						</dl>
					</div>
					<div class="ttl"> 
						<a>
							<figure>
								<img src="{{ $interview->company->logo_file }}" alt="">
							</figure>
						</a>
						<div class="txt">
							<p class="name">
								<a>
									{{ $interview->company->name }}
								</a>
							</p>
						</div>
					</div>
					<div class="item-info">
						<dl>
							@if ( !empty($interview->unit) )
								<dt style="white-space:nowrap;width:64px;">部門</dt>
								<dd>{{ mb_strimwidth($interview->unit->name, 0, 30, "...") }}</dd>
							@endif
							@if ( !empty($interview->event) )
								<dt style="white-space:nowrap;width:64px;">イベント</dt>
								<dd>{{ mb_strimwidth($interview->event->name, 0, 30, "...") }}</dd>
							@endif
						</dl>
						@if ( !empty($interview->job) )
							<dl>
								<dt style="white-space:nowrap;">求人</dt>
								<dd>
									<span class="job-txt">{{ $interview->job->name }}@if(!empty($interview->job->job_code)) [ID:{{ $interview->job->job_code }}]@endif</span>
								</dd>
							</dl>
							<dl>
								<dd>
									<div class="item-btm" style="margin-top: 0px;">
										@if (!empty($interview->job->getJobCategoryName()) )
										<a style="background:#E5AF24;color:#fff;border:4px solid #E5AF24;border-radius: 20px;" >{{  $interview->job->getJobCategoryName() }}</a>
										@endif
										@if (!empty($interview->job->getLocations()) )
										<p class="location"  style="font-size: 1.4rem;">{{ $interview->job->getLocations() }} @if (!empty($job->else_location)) ({{ $job->else_location }})@endif</p>
										@endif
									</div>
								</dd>
							</dl>
						@endif
					</div>
				</div><!-- END item-inner -->
			</div><!-- END item -->

{{-- ??? --}}
			@if ($interview->propose_type == '0' && $interview->aprove_flag == '0')
				<div class="chat">
					<div class="chat-inner">
						@if ($interview->interview_type == '0')
							<h3 class="title-sub">申請中　まだ承認されていません</h3>
						@elseif ($interview->interview_type == '1')
							<h3 class="title-sub">申請中　まだ承認されていません</h3>
						@elseif ($interview->interview_type == '2')
							<h3 class="title-sub">申請中　まだ承認されていません</h3>
						@endif
					</div> <!-- END request-inner -->
				</div> <!-- END request -->
			@elseif ($interview->propose_type == '0' && $interview->aprove_flag == '2')
				<div class="chat">
					<div class="chat-inner">
						@if ($interview->interview_type == '0')
							<h3 class="title-sub">カジュアル面談の申込みは否認されました</h3>
						@elseif ($interview->interview_type == '1')
							<h3 class="title-sub">正式応募の申込みは否認されました</h3>
						@elseif ($interview->interview_type == '2')
							<h3 class="title-sub">イベントの申込みは否認されました</h3>
						@endif
					</div> <!-- END request-inner -->
				</div> <!-- END chat -->
			@elseif ($interview->propose_type == '1' && $interview->aprove_flag == '2')
				<div class="chat">
					<div class="chat-inner">
						@if ($interview->interview_type == '0')
							<h3 class="title-sub">カジュアル面談の依頼を辞退しました</h3>
						@elseif ($interview->interview_type == '1')
							<h3 class="title-sub">正式応募の依頼を辞退しました</h3>
						@endif
					</div> <!-- END request-inner -->
				</div> <!-- END request -->
			@elseif ($interview->propose_type == '1' && $interview->aprove_flag == '0')
				<div class="chat">
					<div class="chat-inner">
						@if ($interview->interview_type == '0')
							<h3>カジュアル面談の依頼が届いています</h3>
							<p>あなたの氏名、メールアドレスを企業へ公開することに同意し、依頼を承諾しますか？</p>
						@elseif ($interview->interview_type == '1')
							<h3>正式応募の依頼が届いています</h3>
							<p>あなたの氏名、メールアドレスを企業へ公開することに同意し、依頼を承諾しますか？</p>
						@endif
						
						<div class="message-area" style="margin-top: 10px;">
							{{ html()->form('POST', "/interview/aprove")->attribute('name', "apform")->open() }}
							{{ html()->hidden('interview_id' ,$interview->id) }}
							{{ html()->hidden('aprove_flag','1') }}
							<button type="submit" style="margin-top: 10px;">はい、承諾します</button>
							{{ html()->form()->close() }}
						</div>

						<div class="message-area" style="margin-top: 10px;">
							{{ html()->form('POST', "/interview/aprove")->attribute('name', "rejform")->open() }}
							{{ html()->hidden('interview_id', $interview->id) }}
							{{ html()->hidden('aprove_flag','2') }}
							<button type="submit" style="margin-top: 10px;">辞退します</button>
							{{ html()->form()->close() }}
						</div>

					</div> <!-- END request-inner -->
				</div> <!-- END request -->

			@else
{{-- メッセージ --}}
				<div class="chat">
					<div class="chat-inner">

<div class="scroll">
						@foreach ($msgList as $msg)
							@if ( !empty($msg->user_name) )
								<div class="balloon-chat right">
									<div class="time" style="font-size: 1.2rem;">{{  $msg->created_at->format('Y/m/d/H:i') }}</div>
									<div class="chatting">
										<p class="txt" style="line-height: 14px;padding-left: 5px;">
											{!! nl2br(e($msg->content)) !!}
										</p>
										@if ( !empty($msg->raw_file) )
											{{ html()->form('POST', '/interview/dl/attach')->id('dlform' . $msg->id)->attribute('name', 'dlform' . $msg->id)->open() }}
											{{ html()->hidden('comp_id', $interview->company_id) }}
											{{ html()->hidden('raw_file', $msg->raw_file) }}
											{{ html()->hidden('up_file', $msg->up_file) }}
											<p class="txt" style="line-height: 14px;padding-right: 5px;text-align: right;">
												<a href="javascript:dlform{{ $msg->id }}.submit()"  style="text-decoration: underline;">{{ $msg->raw_file }}</a>
											</p>
											{{ html()->form()->close() }}
										@endif
									</div>
								</div>



							@else 
								<div class="balloon-chat left">
									<div class="time" style="font-size: 1.2rem;">{{ $msg->created_at->format('Y/m/d/H:i') }}</div>
									<div class="chatting">
										<p class="txt" style="line-height: 14px;padding-left: 5px;">
											{!! nl2br(e($msg->content)) !!}
										</p>
										@if ( !empty($msg->raw_file) )
											{{ html()->form('POST', '/interview/dl/attach')->id('dlform' . $msg->id)->attribute('name', 'dlform' . $msg->id)->open() }}
											{{ html()->hidden('comp_id', $interview->company_id) }}
											{{ html()->hidden('raw_file', $msg->raw_file) }}
											{{ html()->hidden('up_file', $msg->up_file) }}
											<p class="txt" style="line-height: 14px;padding-right: 5px;text-align: right;">
												<a href="javascript:dlform{{ $msg->id }}.submit()"  style="text-decoration: underline; color:white;">{{ $msg->raw_file }}</a>
											</p>
											{{ html()->form()->close() }}
										@endif
									</div>
								</div>
							@endif
  						@endforeach
</div>{{-- END scroll --}}

						@if (!empty($interview->end_thread))
							<div style="display: flex; display: -webkit-flex; -webkit-justify-content: space-between; justify-content: space-between;">
								<div></div>
								<div style="padding:5px; font-size:18px;">―このスレッドは終了しました―</div>
								<div></div>
							</div>
						@endif
					</div> <!-- END chat-inner -->


					@if (empty($interview->end_thread))
						<hr>
						<div class="chat-inner message-wrap">
							<div class="toggle-btn">
								<span></span>
								<span></span>
							</div>
	 						<div class="messageBg">
								<h2>新しいメッセージを送る</h2>
								<div class="message-area">
									{{ html()->form('POST', "/interview/flowpost")->id('postform')->attribute('name', "postform")->acceptsFiles()->open() }}
									{{ html()->hidden('interview_id', $interview->id)->id('interview_id') }}
									@if ($interview->aprove_flag == '0')
										<input type="radio" name="aprove_flag" value="1" @if (old('aprove_flag') == '1') checked @endif>承認　　
										<input type="radio" name="aprove_flag" value="2" @if (old('aprove_flag') == '2') checked @endif>否認
										@error('aprove_flag')
											<ul class="oneRow">
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											</ul>
										@enderror
										<br><br>
									@endif
									<textarea name="content" id="" cols="30" rows="10" placeholder="本文を入力してください"></textarea>
									@error('content')
										<ul class="oneRow">
											<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
										</ul>
									@enderror
									<div style="text-align:left;">
									添付ファイル：{{ html()->file('up_file') }}
									</div>
									<button type="submit">送信</button>
									{{ html()->form()->close() }}
								</div> <!-- END message-area -->
							</div> <!-- END messageBg -->
						</div> <!-- END chat-inner message-wrap -->
					</div> <!-- END chat -->
					@endif
{{-- END メッセージ --}}
			@endif
					
		</div><!-- END con-wrap -->
	</div><!-- END inner -->
</main>

{{--     <button class="modal-btn">メッセージを送る</button>--}}
	 

<script>

/////////////////////////////////////////////////////////
// 初回起動
/////////////////////////////////////////////////////////
$(document).ready(function() {

	// 要素を特定して取得
	const scrollContainer = document.querySelector('.scroll');

	// 要素のスクロールバーを最下部まで移動させる
	scrollContainer.scrollTop = scrollContainer.scrollHeight - scrollContainer.clientHeight;

});


</script>

@endsection

