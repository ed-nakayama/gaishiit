@extends('layouts.comp.auth')

@section('content')

<head>
@if ($interview['interview_type'] == '0')
	<title>カジュアル面談｜{{ config('app.name', 'Laravel') }}</title>
@elseif ($interview['interview_type'] == '1')
	<title>正式応募｜{{ config('app.name', 'Laravel') }}</title>
@elseif ($interview['interview_type'] == '2')
	<title>イベント｜{{ config('app.name', 'Laravel') }}</title>
@else
	<title>？？？？｜{{ config('app.name', 'Laravel') }}</title>
@endif
</head>

<style>
.scroll{
  height: 350px;
  overflow: auto;
}
</style>

{{--@include('comp.member_activity')--}}

<div class="mainContentsInner">
	<div class="mainContentsInner">

		<div class="mainTtl title-main">
			@if ($interview->interview_type == '0')
				<h2>カジュアル面談</h2>
			@elseif ($interview->interview_type == '1')
				<h2>正式応募</h2>
			@elseif ($interview->interview_type == '2')
				<h2>イベント</h2>
			@else
				<h2>？？？？</h2>
			@endif
		</div><!-- /.mainTtl -->
                
		<div class="containerContents">

			<section class="secContents-mb">
				<div class="secContentsInner">
					<div class="containerProfile">
						<p class="requesteDate">依頼日：{{ str_replace('-', '/' ,substr($interview->created_at, 0, 16)) }}</p>
						@if ($interview->aprove_flag == '1' || $interview->interview_type == '1')
							<p class="profileName">{{ $interview->user->name  }}</p>
						@else
							<p class="profileName">{{ $interview->user->nick_name  }}</p>
						@endif
						<ul class="profileList">
						@if (!empty($interview->unit))
							<li>
								<p class="profileTag">部門</p>
								<p class="profileTagName">{{ $interview->unit->name }} @if (!empty($interview->unit->deleted_at))<font color='red'>（削除済）</font>@endif</p>
							</li>
						@endif
						@if (!empty($interview->job))
							<li>
								<div class="profileListInner">
									<p class="profileTag">ジョブ</p>
									<p class="profileTagName">
										<a href="/comp/job/ref/{{ $interview->job->id }}" target="_blank" style="text-decoration: underline;">{{ $interview->job->name }} @if (!empty($interview->job->deleted_at))<font color='red'>（削除済）</font>@endif</a>
									</p>
								</div>
								<p class="profileJobId">ジョブID：<a href="/comp/job/ref/{{ $interview->job->id }}" target="_blank" style="text-decoration: underline;">{{ $interview->job->job_code }}</a></p>
							</li>
						@endif
						@if (!empty($interview->event) )
							<li>
								<p class="profileTag">イベント</p>
								<p class="profileTagName">{{ $interview->event->name }} @if (!empty($interview->event->deleted_at))<font color='red'>（削除済）</font>@endif</p>
							</li>
						@endif
						</ul><!-- /.profileList -->
						@if (!empty($interview->job) )
							<div class="profileTagList">
								<ul class="profileTagListItem">
									@if (!empty($interview->job->getJobCategoryName()) )
									<li class="bg-pattern-b">{{ $interview->job->getJobCategoryName() }}</li>
									@endif
								</ul><!-- /.profileTagList -->
								<p class="profileMap mapIcon">{{  $interview->job->getLocations() }} @if (!empty( $interview->job->else_location)) ({{  $interview->job->else_location }})@endif</p>
							</div><!-- /.profileTag -->
						@endif
					</div><!-- /.containerProfile -->
				</div>
			</section><!-- /.secContents-mb -->
<a id="last_msg"></a>

			<section class="secContents">

				<div class="tab_box">
					<div class="btn_area">
						<p class="tab_btn active">メッセージ</p>
						<p class="tab_btn">基本情報</p>
						@if ($interview->interview_type == '0' || $interview->interview_type == '1')
							<p class="tab_btn">職務経歴書</p>
						@endif
					</div>
					<div class="panel_area">
						<div class="tab_panel active">
                                
							@if ($interview->propose_type == '1' && $interview->aprove_flag == '0')
								<div class="containerNewMessage">
									@if ($interview->interview_type == '0')
										<h2 class="title-sub">カジュアル面談の打診をしています</h2>
									@elseif ($interview->interview_type == '1')
										<h2 class="title-sub">正式応募の打診をしています</h2>
									@endif
									<h2 class="title-sub">まだ承認されていません</h2>
								</div><!-- /.containerNewMessage -->

							@elseif ($interview->propose_type == '1' && $interview->aprove_flag == '2')
								<div class="containerNewMessage">
									@if ($interview->interview_type == '0')
										<h2 class="title-sub">カジュアル面談の依頼は辞退されました</h2>
									@elseif ($interview->interview_type == '1')
										<h2 class="title-sub">正式応募の依頼は辞退されました</h2>
									@endif
								</div><!-- /.containerNewMessage -->

							@elseif ($interview->propose_type == '0' && $interview->aprove_flag == '2')
									@if ($interview->interview_type == '0')
										<h2 class="title-sub">カジュアル面談の申込みを否認しました</h2>
									@elseif ($interview->interview_type == '1')
										<h2 class="title-sub">正式応募の申込みを否認しました</h2>
									@elseif ($interview->interview_type == '2')
										<h2 class="title-sub">イベントの申込みを否認しました</h2>
									@endif
								</div><!-- /.containerNewMessage -->

							@elseif ($interview->propose_type == '0' && $interview->aprove_flag == '0')
								<div class="containerNewMessage">
									@if ($interview->interview_type == '0')
										<h2 class="title-sub">カジュアル面談の申込みが届いています</h2>
										<p>カジュアル面談の申込みを受け付けると、候補者の氏名、メールアドレス、職務経歴書（簡易版）を確認できるようになります</p>
									@elseif ($interview->interview_type == '1')
										<h2 class="title-sub">正式応募の申込みが届いています</h2>
										<p>正式応募の申込みを受け付けると、候補者の氏名、メールアドレス、職務経歴書（簡易版）を確認できるようになります</p>
									@endif
									<br>
									 <ul class="btnContainerList">
										<li>
											{{ html()->form('POST', '/comp/interview/aprove')->attribute('name', 'apform')->open() }}
											{{ html()->hidden('interview_id', $interview->id) }}
											{{ html()->hidden('aprove_flag', '1') }}
											{{ html()->form()->close() }}
											<div class="btnContainer">
												<a href="javascript:apform.submit()" class="squareBtn btn-large">承認</a>
											</div><!-- /.btn-container -->
										</li>
										<li>
											{{ html()->form('POST', '/comp/interview/aprove')->attribute('name', 'rejform')->open() }}
											{{ html()->hidden('interview_id', $interview->id) }}
											{{ html()->hidden('aprove_flag', '2') }}
											{{ html()->form()->close() }}
											<div class="btnContainer">
												<a href="javascript:rejform.submit()" class="squareBtn btn-large">否認</a>
											</div><!-- /.btn-container -->
										</li>
									</ul>
								</div><!-- /.containerNewMessage -->

							@else
								<div style="display: flex;"><a href="javascript:lastMsg();void(0)" class="squareBtn btn-large" style="padding: 5px;">最新のメッセージに移動</a></div><br>
								<div class="containerTalk talk-bd-bt" style="margin-bottom: 20px;padding-bottom: 20px;">
									<ul class="talkItemList" >
<div class="scroll">
										@foreach ($msgList as $msg)
											@if ($msg->user_name != '')
												<li>
													<div class="talkItem">
														<div class="talkItemProfile">
															<div class="text">
																<p class="date">{{  $msg->created_at->format('Y/m/d/H:i') }}</p>
															</div>
															@if ($interview->aprove_flag == '1')
																<p class="name">　{{ $msg->user_name }}</p>
															@else
																<p class="profileName">　{{ $msg->user_nick_name  }}</p>
															@endif
														</div><!-- /.talkItemProfile -->
														<div class="talkItemMesseage bg-pattern-a">
															<p>{!! nl2br(e($msg->content)) !!}</p>
														</div><!-- /.talkItemMesseage -->

														@if ( !empty($msg->raw_file) )
															<div class="talkItemMesseage bg-pattern-a"  style="text-align: right;">
																{{ html()->form('POST', '/comp/interview/dl/attach')->id('dlform' . $msg->id)->attribute('name', 'dlform' . $msg->id)->open() }}
																{{ html()->hidden('user_id', $interview->user->id) }}
																{{ html()->hidden('raw_file', $msg->raw_file) }}
																{{ html()->hidden('up_file', $msg->up_file) }}
																<a href="javascript:dlform{{ $msg->id }}.submit()"  style="text-decoration: underline;">{{ $msg->raw_file }}</a>
																{{ html()->form()->close() }}
															</div><!-- /.talkItemMesseage -->
														@endif
													</div><!-- /.talkItem -->
												</li>
											@else 
												<li class="companyTalk">
													<div class="talkItem">
														<div class="talkItemProfile">
															<div class="text">
																<p class="date">{{ $msg->created_at->format('Y/m/d/H:i') }}</p>
															</div>
														</div><!-- /.talkItemProfile -->
														<div class="talkItemMesseage bg-pattern-b">
															<p>{!! nl2br(e($msg->content)) !!}</p>
														</div><!-- /.talkItemMesseage -->

														{{-- 添付ファイル  --}}
														@if ( !empty($msg->raw_file) )
															<div class="talkItemMesseage bg-pattern-b"  style="text-align: right;">
																{{ html()->form('POST', '/comp/interview/dl/attach')->id('dlform' . $msg->id)->attribute('name', 'dlform' . $msg->id)->open() }}
																{{ html()->hidden('user_id', $interview->user->id) }}
																{{ html()->hidden('raw_file', $msg->raw_file) }}
																{{ html()->hidden('up_file', $msg->up_file) }}
																<a href="javascript:dlform{{ $msg->id }}.submit()"  style="text-decoration: underline;">{{ $msg->raw_file }}</a>
																{{ html()->form()->close() }}
															</div><!-- /.talkItemMesseage -->
														@endif
													</div><!-- /.talkItem -->
												</li>
											@endif
										@endforeach
</div>{{-- END scroll --}}
<br>
										<div style="display: flex; display: -webkit-flex; -webkit-justify-content: space-between; justify-content: space-between;">
											@if (!empty($interview->end_thread))
												<div></div>
												<div style="padding:5px; font-size:18px;">―このスレッドは終了しました―</div>
												<div></div>
											@else
												<div></div>
												<div></div>
												<a href="/comp/interview/endthread?interview_id={{$interview->id  }}" style="padding: 10px; width:auto; background-color: #011d70; color: #fff;">スレッド終了</a>
											@endif
										</div>
									</ul>
								</div><!-- /.containerTalk -->

								@if (empty($interview->end_thread))
									{{ html()->form('POST', "/comp/interview/flowpost")->id('postform')->attribute('name', "postform")->acceptsFiles()->open() }}
									{{ html()->hidden('interview_id', $interview->id)->id('interview_id') }}

									@if ($interview->interview_type == '0' || $interview->interview_type == '1')
										<div class="containerNewMessage">
											<div class="formContainer mg-ajust">
												<div class="item-input select-item-row">
													<div class="item-name"  style="width:60px;">
														<p>ステージ</p>
													</div><!-- /.item-name -->
													@if ($interview->interview_type == '0')
														<div class="selectWrap1 hundred-thirty">
															カジュアル面談<input type="hidden" name="stage" value="99">
														</div>
													@else
														<div class="selectWrap hundred-thirty">
															<select name="stage"  class="select-no">
																<option value="" disabled selected style="display:none;"></option>
																@foreach ($constStage as $st)
																	@if ($st->id != '99')
																		<option value="{{ $st->id }}" @if ($interview->stage_id == $st->id)  selected @endif >{{ $st->name }}</option>
																	@endif
																@endforeach
															</select>
														</div>
													@endif

													<div class="item-name" style="width:80px;">
														<p>ステータス</p>
													</div><!-- /.item-name -->
													<div class="selectWrap hundred-thirty">
														<select name="status" class="select-no">
															<option value="" disabled selected style="display:none;"></option>
																@foreach ($constStatus as $st)
																	<option value="{{ $st->id }}" @if ($interview->status_id == $st->id)  selected @endif >{{ $st->name }}</option>
															@endforeach
														</select>
													</div><!-- /.selectStatus -->

													<div class="item-name" style="width:40px;">
														<p>採用</p>
													</div><!-- /.item-name -->
													<div class="selectWrap hundred-thirty">
														<select name="result" id="result" class="select-no" >
															<option value="0" ></option>
															@foreach ($constResult as $st)
																<option value="{{ $st->id }}" @if ($interview->result_id == $st->id)  selected @endif >{{ $st->name }}</option>
															@endforeach
														</select>
													</div>
												</div><!-- /.item-input -->
											</div><!--formContainer mg-ajust -->
										</div><!-- containerNewMessage -->
									@endif

									<div class="containerNewMessage">
										<h2 class="title-sub">新しいメッセージを送る</h2>

										@if ($interview->interview_type == '1')
											<div class="formContainer mg-ajust" id="changeStatusSelect">
												<div class="item-name">
													<p>面接日程</p>
												</div><!-- /.item-name -->
												<div class="item-input">
 													<input type="date" name="interview_date" value="{{ $interview->interview_date }}" class="harf">
												</div><!-- /.item-input -->
											</div>
										@endif

										<div class="formContainer-message mg-ajust">
											{{ html()->hidden('aprove_flag', $interview->aprove_flag)->id('interview_id') }}
											<div class="formContainer-message-inner">
												<div class="item-name">
													<p>定型メッセージ</p>
												</div><!-- /.item-name -->
												<div class="item-input">
													<div class="selectWrap harf">
														<select name="select" id="mask_select"  class="select-no"  onchange="changeMask(this);">
															<option value=""></option>
															@foreach ($maskMsg as $mask)
																@if ($mask->interview_type == $interview->interview_type)
																	<option value="{{ $mask->id }}">{{ $mask->title }}</option>
																@endif
															@endforeach
														</select>
													</div>
													<div class="messeageBtn">
														<div class="modalContainer">
															<a href="#modal" class="squareBtn btn-medium">編集</a>
														</div><!-- /.modalContainer -->
													</div>
												</div><!-- /.item-input -->
											</div><!-- /.formContainer-message-inner -->
                                                
											<textarea class="form-mt" name="content" id="mask_content" cols="30" rows="10">{{ old('content')  }}</textarea>
											<ul class="oneRow">
												@error('content')
													<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
												@enderror
											</ul>
											添付ファイル：{{ html()->file('up_file') }}<br>
										</div>

										<div class="btnContainer">
											<a href="javascript:postform.submit()" class="squareBtn btn-large">送信</a>
										</div><!-- /.btn-container -->
										{{ html()->form()->close() }}

									</div><!-- /.containerNewMessage -->
								@endif
							@endif

						</div><! END tab_pane -->

                              
<!-- **************** ユーザ情報 タブ ******************* -->
						<div class="tab_panel">
							<div class="containerTblUserInfo mb-ajust">

								<div style="display: flex; justify-content: flex-end;">
									{{ html()->form('POST', "/comp/pdf/base")->attribute('name', "baseform")->open() }}
									{{ html()->hidden('user_id',$interview->user->id) }}
									<a href="javascript:baseform.submit()" class="squareBtn btn-large" style="padding: 5px;">PDFダウンロード</a>
									{{ html()->form()->close() }}
								</div>
								
								<div class="tblCaption">
									<h2 class="tblCaptionTitle">基本情報</h2>
								</div><!-- /.tblCaption -->

								<table class="tblUserInfo">
									<tr><th width="50%">氏名</th><th>メールアドレス</th></tr>
									<tr><td>@if ($interview->aprove_flag == '1' || $interview->interview_type == '1'){{ $interview->user->name }}@else{{ $interview->user->nick_name }}@endif</td><td>@if ($interview->aprove_flag == '1' || $interview->interview_type == '1'){{ $interview->user->email }}@else ************ @endif</td></tr>

									<tr><th>生年月日</th><th>性別</th</tr>
									<tr><td>{{ $interview->user->getBirthday() }}</td><td>{{ $interview->user->getSex() }}</td></tr>

									<tr><th>転職希望時期</th><th>最終学歴</th></tr>
									<tr>
										<td>
											@if ($interview->user->change_time == '1')
												今すぐ
											@elseif ($interview->user->change_time == '2')
												{{ $interview->user->change_year . '/' . $interview->user->change_month  . '/' . $interview->user->change_day  }} 以降
											@else
												未設定
											@endif
										</td>
										<td>{{ $interview->user->graduation }}</td>
									</tr>

									<tr><th>現在の勤務先</th><th>役職</th></tr>
									<tr><td>{{ $interview->user->company }}</td><td>{{ $interview->user->job_title }}</td></tr>

									<tr><th colspan="2">職務内容</th></tr>
									<tr>
										<td colspan="2">
											@if ($interview->user->job == 1)
												IC
											@elseif ($interview->user->job == '2')
												Management　　　　年数 {{ $interview->user->mgr_year }}年 / 人数 {{ $interview->user->mgr_member }}人
											@else
												未設定
											@endif<br>
											{!! nl2br(e($interview->user->job_content)) !!}
										</td>
									</tr>

									<tr><th>過去3年の平均実績（Actual Earnings）</th><th>理論年収（OTE）</th></tr>
									<tr><td>{{ $interview->user->actual_income }} 万円</td><td>{{ $interview->user->ote_income }} 万円</td></tr>

									<tr><th colspan="2">希望勤務地</th></tr>
									<tr><td colspan="2">{{ $interview->user->getLocation() }}</td></tr>

									<tr><th colspan="2">キャリアに関する希望</th></tr>
									<tr><td colspan="2">{!! nl2br(e($interview->user->request_carrier)) !!}</td></tr>

									<tr><th colspan="2">転職を希望する業種</th></tr>
									<tr><td colspan="2">{{ $interview->user->getBusDetail() }}</td></tr>

									<tr><th colspan="2">転職を希望する職種</th></tr>
									<tr><td colspan="2">{{ $interview->user->getCatDetail() }}</td></tr>

								</table><!-- /.tblUserInfo -->
							</div><!-- /.containerTblUserInfo -->
						</div>


<!-- **************** 職務経歴書 タブ ******************* -->
						@if ($interview->interview_type == '0' || $interview->interview_type == '1')
							<div class="tab_panel">
								<div class="containerTblUserInfo">
									<div style="display: flex; justify-content: flex-end;">
										{{ html()->form('POST', "/comp/pdf/cv")->attribute('name', "cvform")->open() }}
										{{ html()->hidden('user_id',$interview->user->id) }}
										<a href="javascript:cvform.submit()" class="squareBtn btn-large" style="padding: 5px;">PDFダウンロード</a>
										{{ html()->form()->close() }}
　　
										{{ html()->form('POST', "/comp/pdf/cv/eng")->attribute('name', "cvengform")->open() }}
										{{ html()->hidden('user_id',$interview->user->id) }}
										<a href="javascript:cvengform.submit()" class="squareBtn btn-large" style="padding: 5px;">英文PDFダウンロード</a>
										{{ html()->form()->close() }}
									</div>

									<div class="tblCaption">
										<h2 class="tblCaptionTitle">職務経歴書</h2>
									</div><!-- /.tblCaption -->
									<table class="tblUserInfo">
										<tr><th>企業名</th></tr>
										<tr><td>{{ $interview->user->company }}</td></tr>

										<tr><th>部門</th></tr>
										<tr><td>{{ $interview->user->unit_name }}</td></tr>

										<tr><th>役職</th></tr>
										<tr><td>{{ $interview->user->job_title }}</td></tr>

										<tr><th>在職期間</th></tr>
										<tr><td>{{ $interview->user->getEnroll() }}</td></tr>

										<tr><th>業務内容・担当業界・取扱商材・プロジェクト</th></tr>
										<tr><td>{!! nl2br(e($interview->user->job_detail)) !!}</td></tr>

										<tr><th>アワード</th></tr>
										<tr><td>{{ $interview->user->award }}</td></tr>

										<tr><th>英語力</th></tr>
										<tr><td>{{ $interview->user->getEngishAbility() }}</td></tr>

										<tr><th>TOEIC</th></tr>
										<tr><td>{{ $interview->user->toeic }} 点</td></tr>

										<tr><th>日本語力</th></tr>
										<tr><td>{{ $interview->user->getJapaneseAbility() }}</td></tr>

									</table><!-- /.tblUserInfo -->
								</div><!-- /.containerTblUserInfo -->
							</div>
						@endif
<!-- **************** END ユーザ情報 タブ ******************* -->

					</div>
				</div>
			</section><!-- /.secContents -->
                    
		</div><!-- /.containerContents -->
	</div><!-- /.mainContentsInner -->
</div><!-- /.mainContents -->



{{-- モーダル --}}

	<div class="remodal" data-remodal-id="modal">
		{{ Form::open(['url' => '/comp/interview/mask', 'name' => 'maskform' , 'id' => 'maskform']) }}
		{{ Form::hidden('interview_type', $interview->interview_type, ['class' => 'form-control', 'id'=>'interview_type' ]) }}
		{{ Form::hidden('interview_id', $interview->interview_id, ['class' => 'form-control', 'id'=>'interview_id' ]) }}
		<div class="modalTitle">
			<h2>定型文編集</h2>
		</div><!-- /.modalTitle -->
		<div class="modalInner">
			<div class="formContainer mg-ajust">
				<div class="item-name">
					<p>編集する定型文</p>
				</div><!-- /.item-name -->
				<div class="item-input">
					<div class="selectWrap seventy">
						<select name="select" id="mod_select" class="select-no" onchange="changeModMask(this);" >
							<option value="">新規作成</option>
							@foreach ($maskMsg as $mask)
								@if ($mask->interview_type == $interview->interview_type)
									<option value="{{ $mask->id }}">{{ $mask->title }}</option>
								@endif
							@endforeach
						</select>
					</div>
				</div><!-- /.item-input -->
			</div>

			<div class="formContainer mg-ajust">
				<div class="item-name">
					<p>タイトル</p>
				</div><!-- /.item-name -->
				<div class="item-input">
					<input type="text" name="title" id="mod_title" value=""  placeholder="カジュアル面談">
				</div><!-- /.item-input -->
			</div>

			<div class="formContainer al-item-none">
				<div class="item-name">
					<p>本文</p>
				</div><!-- /.item-name -->
				<div class="item-input">
					<textarea class="form-mt" name="content" id="mod_content" cols="30" rows="10" placeholder="この度はご連絡をいただきありがとうございます。"></textarea>
				</div><!-- /.item-input -->
			</div>
		</div><!-- /.modalInner -->

		<div class="btnContainer">
					<a href="javascript:maskform.submit()" class="squareBtn btn-large">保存</a>
		</div><!-- /.btn-container -->
		{{ Form::close() }}
	</div>

{{-- END モーダル --}}



<?php
$maskMsgJson = json_encode($maskMsg);
$userName = json_encode($interview->user->name);
$memberName = json_encode(Auth::user()->name);
?>

<script type="text/javascript">

let maskMsg = <?php echo $maskMsgJson; ?>;
let userName = <?php echo $userName; ?>;
let memberName = <?php echo $memberName; ?>;

function changeMask(obj) {

    var idx = obj.selectedIndex;
    var value = obj.options[idx].value; 
	var i;
	var content = '';
	
	if (idx > 0) {
		for (i = 0; i < maskMsg.length; i++) {
		
			if (maskMsg[i]['id'] == value) {
//				content = userName . "　様\n" . maskMsg[i]['content'] . "\n" .  memberName;
				document.getElementById( "mask_content" ).value = userName + "　様\n" + maskMsg[i]['content'] + "\n" +  memberName;
				break;
			}
		}
	}

}


function changeModMask(obj) {

    var idx = obj.selectedIndex;
    var value = obj.options[idx].value; 
	var i;
	
	if (idx > 0) {
		for (i = 0; i < maskMsg.length; i++) {
			if (maskMsg[i]['id'] == value) {
				document.getElementById( "mod_title" ).value = maskMsg[i]['title'];
				document.getElementById( "mod_content" ).value = maskMsg[i]['content'];
				break;
			}
		}
	}

}

//////////////////////////////////////
// status 変更イベント
//////////////////////////////////////
let select = document.querySelector('[name="status"]');

if (!!select) {
	select.onchange = event => { 
		if (select.selectedIndex == 4) {
	 		changeStatusSelect.style.display = "";
		} else{ 
	  		changeStatusSelect.style.display = "none";
		}
	}
}


function lastMsg() {

	location.href = '#last_msg';
	
	// 要素を特定して取得
	const scrollContainer = document.querySelector('.scroll');

	// 要素のスクロールバーを最下部まで移動させる
	scrollContainer.scrollTop = scrollContainer.scrollHeight - scrollContainer.clientHeight;
}


/////////////////////////////////////////////////////////
// 初回起動
/////////////////////////////////////////////////////////
$(document).ready(function() {

	const int_type = '{{$interview->interview_type}}';

	if (int_type == '1') {
		let select = document.querySelector('[name="status"]');

		if (!!select) {
			if (select.selectedIndex == 4) {
		 		changeStatusSelect.style.display = "";
			} else{ 
		  		changeStatusSelect.style.display = "none";
			}
		}
	}

	lastMsg();

});



</script>

@endsection
