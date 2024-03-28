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
								<p class="requesteDate">依頼日：{{ str_replace('-', '/' ,substr($interview->interviews_created_at, 0, 16)) }}</p>
								@if ($interview->aprove_flag == '1' || $interview->interview_type == '1')
									<p class="profileName">{{ $userInfo->name  }}</p>
								@else
									<p class="profileName">{{ $userInfo->nick_name  }}</p>
								@endif
								<ul class="profileList">
								@if ($interview->unit_name != '')
									<li>
										<p class="profileTag">部門</p>
										<p class="profileTagName">{{ $interview->unit_name }}</p>
									</li>
								@endif
								@if (!empty($interview->name))
									<li>
										<div class="profileListInner">
											<p class="profileTag">ジョブ</p>
											<p class="profileTagName">{{ $interview->name }}</p>
										</div>
										<p class="profileJobId">ジョブID：{{ $interview->job_code }}</p>
									</li>
								@endif
								@if (!empty($interview->event_name) )
									<li>
										<p class="profileTag">イベント</p>
										<p class="profileTagName">{{ $interview->event_name }}</p>
									</li>
								@endif
								</ul><!-- /.profileList -->
								@if (!empty($interview->name) )
									<div class="profileTagList">
										<ul class="profileTagListItem">
											@if (!empty($interview->job_cat_details))
											<li class="bg-pattern-b">{{ $interview->getJobCategoryName() }}</li>
											@endif
										</ul><!-- /.profileTagList -->
										<p class="profileMap mapIcon">{{  $interview->getLocations() }} @if (!empty($job->else_location)) ({{ $job->else_location }})@endif</p>
									</div><!-- /.profileTag -->
								@endif
							</div><!-- /.containerProfile -->
						</div>
					</section><!-- /.secContents-mb -->

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
												<p>カジュアル面談を承認すると、候補者の氏名、メールアドレス、職務経歴書（簡易版）を確認できるようになります</p>
											@elseif ($interview->interview_type == '1')
												<h2 class="title-sub">正式応募の申込みが届いています</h2>
												<p>正式応募を承認すると、候補者の氏名、メールアドレス、職務経歴書（簡易版）を確認できるようになります</p>
											@endif
											<br>
											 <ul class="btnContainerList">
												<li>
													{{ Form::open(['url' => '/comp/interview/aprove', 'name' => 'apform' ]) }}
													{{ Form::hidden("interview_id", $interview->interview_id, ['class' => 'form-control'])}}
													{{ Form::hidden("aprove_flag", '1', ['class' => 'form-control'])}}
													{{ Form::close() }}
													<div class="btnContainer">
														<a href="javascript:apform.submit()" class="squareBtn btn-large">承認</a>
													</div><!-- /.btn-container -->
												</li>
												<li>
													{{ Form::open(['url' => '/comp/interview/aprove', 'name' => 'rejform' ]) }}
													{{ Form::hidden("interview_id", $interview->interview_id, ['class' => 'form-control'])}}
													{{ Form::hidden("aprove_flag", '2', ['class' => 'form-control'])}}
													{{ Form::close() }}
													<div class="btnContainer">
														<a href="javascript:rejform.submit()" class="squareBtn btn-large">否認</a>
													</div><!-- /.btn-container -->
												</li>
											</ul>
										</div><!-- /.containerNewMessage -->

									@else
										<p><a href="#last_msg1" class="newMessageLink">&gt;&gt;最新のメッセージに移動</a></p>

										<div class="containerTalk talk-bd-bt">
											<ul class="talkItemList">
												@foreach ($msgList as $msg)
													@if ($loop->last)<a id="last_msg"></a>@endif
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
                                                			</div><!-- /.talkItem -->
                                            			</li>
                                           			@endif
                                        	 	@endforeach
                                        	</ul>
                                    	</div><!-- /.containerTalk -->


                                        {{ Form::open(['url' => '/comp/interview/flowpost', 'name' => 'postform' , 'id' => 'postform']) }}
                                        {{ Form::hidden('interview_id', $interview->interview_id, ['class' => 'form-control', 'id'=>'interview_id' ]) }}

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
                                            
                                        	{{ Form::hidden('aprove_flag', $interview->aprove_flag, ['class' => 'form-control', 'id'=>'interview_id' ]) }}
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
                                            </div>
        
                                            <div class="btnContainer">
                                                <a href="javascript:postform.submit()" class="squareBtn btn-large">送信</a>
                                            </div><!-- /.btn-container -->
                                        {{ Form::close() }}

									</div><!-- /.containerNewMessage -->
									@endif

								</div><! END tab_pane -->

                              
<!-- **************** ユーザ情報 タブ ******************* -->
                                <div class="tab_panel">
                                    <div class="containerTblUserInfo mb-ajust">
                                        <div class="tblCaption">
                                            <h2 class="tblCaptionTitle">基本情報</h2>
                                            <ul class="tblCaptionList">
                                                <li>
                              						{{ Form::open(['url' => '/comp/pdf/base', 'name' => 'baseform' ]) }}
                               						{{ Form::hidden("user_id", $interview->user_id, ['class' => 'form-control'] )}}
                                                 	<a href="javascript:baseform.submit()">&gt;&gt;PDFダウンロード</a>
                               						{{ Form::close() }}
                                                </li>
                                            </ul>
                                        </div><!-- /.tblCaption -->
                                        <table class="tblUserInfo">
                                            <tr><th width="50%">氏名</th><th>メールアドレス</th></tr>
                                            <tr><td>@if ($interview->aprove_flag == '1' || $interview->interview_type == '1'){{ $userInfo->name }}@else{{ $userInfo->nick_name }}@endif</td><td>@if ($interview->aprove_flag == '1' || $interview->interview_type == '1'){{ $userInfo->email }}@else ************ @endif</td></tr>

                                            <tr><th>生年月日</th><th>性別</th</tr>
                                            <tr><td>{{ str_replace('-','/', substr($userInfo->birthday, 0 ,10)) }}</td><td>@if ($userInfo->sex == '1')男@elseif ($userInfo->sex == '2')女@else選択しない@endif</td></tr>

                                            <tr><th>転職希望時期</th><th>最終学歴</th></tr>
                                            <tr>
                                                <td>
                                                @if ($userInfo->change_time == '1')
                                                    今すぐ
                                                @elseif ($userInfo->change_time == '2')
                                                    {{ $userInfo->change_year . '/' . $userInfo->change_month  . '/' . $userInfo->change_day  }} 以降
                                                @else
                                                    未設定
                                                @endif
                                                </td>
                                                <td>{{ $userInfo->graduation }}</td>
                                            </tr>

                                            <tr><th>現在の勤務先</th><th>役職</th></tr>
                                            <tr><td>{{ $userInfo->company }}</td><td>{{ $userInfo->job_title }}</td></tr>

                                            <tr><th colspan="2">職務内容</th></tr>
                                            <tr><td colspan="2">
                                             	@if ($userInfo->job == 1)
                                             		IC
                                                @elseif ($userInfo->job == '2')
                                                	Management　　　　年数 {{ $userInfo->mgr_year }}年 / 人数 {{ $userInfo->mgr_member }}人
                                                @else
                                                    未設定
                                                @endif<br>
                                             	{!! nl2br(e($userInfo->job_content)) !!}
                                            </td></tr>

                                            <tr><th>過去3年の平均実績（Actual Earnings）</th><th>理論年収（OTE）</th></tr>
                                            <tr><td>{{ $userInfo->actual_income }} 万円</td><td>{{ $userInfo->ote_income }} 万円</td></tr>

                                            <tr><th colspan="2">希望勤務地</th></tr>
                                             <tr><td colspan="2">{{ $userInfo->location_name }}</td></tr>

                                            <tr><th colspan="2">キャリアに関する希望</th></tr>
                                            <tr><td colspan="2">{!! nl2br(e($userInfo->request_carrier)) !!}</td></tr>

                                            <tr><th colspan="2">転職を希望する業種</th></tr>
                                            <tr><td colspan="2">{{ $userInfo->buscat_name }}</td></tr>

                                            <tr><th colspan="2">転職を希望する職種</th></tr>
                                            <tr><td colspan="2">{{ $userInfo->jobcat_name }}</td></tr>

                                        </table><!-- /.tblUserInfo -->
                                    </div><!-- /.containerTblUserInfo -->
                                </div>


<!-- **************** 職務経歴書 タブ ******************* -->
								@if ($interview->interview_type == '0' || $interview->interview_type == '1')
                                <div class="tab_panel">
                                    <div class="containerTblUserInfo">
                                        <div class="tblCaption">
                                            <h2 class="tblCaptionTitle">職務経歴書</h2>
                                            	<ul class="tblCaptionList">
                                                	<li>
                              							{{ Form::open(['url' => '/comp/pdf/cv', 'name' => 'cvform' ]) }}
                               							{{ Form::hidden("user_id", $userInfo->id, ['class' => 'form-control'] )}}
                                                 		<a href="javascript:cvform.submit()">&gt;&gt;PDFダウンロード</a>
                               							{{ Form::close() }}
                                                	</li>
                                                	<li>
                              							{{ Form::open(['url' => '/comp/pdf/cv/eng', 'name' => 'cvengform' ]) }}
                               							{{ Form::hidden("user_id", $userInfo->id, ['class' => 'form-control'] )}}
                                                 		<a href="javascript:cvengform.submit()">&gt;&gt;英文PDFダウンロード</a>
                               							{{ Form::close() }}
                                                	</li>
                                            	</ul>
                                        </div><!-- /.tblCaption -->
                                        <table class="tblUserInfo">
                                            <tr><th>企業名</th></tr>
                                            <tr><td>{{ $userInfo->company }}</td></tr>
                                            
                                            <tr><th>部門</th></tr>
                                            <tr><td>{{ $userInfo->unit_name }}</td></tr>
                                            
                                            <tr><th>役職</th></tr>
                                            <tr><td>{{ $userInfo->job_title }}</td></tr>

                                            <tr><th>在職期間</th></tr>
                                            <tr><td>
                                            		{{ $userInfo->enroll_from_year }}年　{{ $userInfo->enroll_from_month }}月　{{ $userInfo->enroll_from_day }}日
                                           		 ～
                                             	@if ($userInfo->enroll_to_year == '0')
                                             		現在
                                             	@elseif ($userInfo->enroll_to_year == '')
                                             	@else
                                             		{{ $userInfo->enroll_to_year }}年　{{ $userInfo->enroll_to_month }}月　{{ $userInfo->enroll_to_yday }}日
												@endif
											</td></tr>
											
                                            <tr><th>業務内容・担当業界・取扱商材・プロジェクト</th></tr>
                                            <tr><td>{!! nl2br(e($userInfo->job_detail)) !!}</td></tr>

                                            <tr><th>アワード</th></tr>
                                            <tr><td>{{ $userInfo->award }}</td></tr>

                                            <tr><th>英語力</th></tr>
                                            <tr><td>{{ $userInfo->english_name }}</td></tr>

                                            <tr><th>TOEIC</th></tr>
                                            <tr><td>{{ $userInfo->toeic }} 点</td></tr>

                                            <tr><th>日本語力</th></tr>
                                            <tr><td>{{ $userInfo->japanese_name }}</td></tr>

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
$userName = json_encode($userInfo->name);
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

	window.location.hash = "last_msg";
	
});



</script>

@endsection
