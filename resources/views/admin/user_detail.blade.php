@extends('layouts.admin.auth')

<style>
/* モーダル用サンプル用 CSS start ================== */
/* モーダルウィンドウの表示非表示や表示される位置を制御する */
.modal_menu {
	position: fixed;
	top: 0;
	left: 0;
	display: flex;
	justify-content: center;
	align-items: center;
	width: 100%;
	height: 100%;
	/* モーダルウィンドウ以外の外側部分、どの程度透過するか */
	background-color: rgba(0,0,0,0.5) !important;
	opacity: 0;
	pointer-events: none;
	visibility: hidden;
	/* モーダルウィンドウを表示するまでの時間 0.3秒に指定 */
	transition: all .3s ease;
	transition-property: opacity, pointer-events, visibility;

	/* クリックされた際にウィンドウ表示、非表示の切り替えを行う部分 */
	&.is-show {
		opacity: 1;
		pointer-events: auto;
		visibility: visible;
	}
}

/* ウィンドウ全体のCSS */
.menu__content {
	width: 100%;
	width: 600px;
	padding: 8px 8px 16px;
	background: #dedede;
	text-align: center;
	border-radius: 5px;
}

/* Yes No 2つのボタンを横並べする */
.inButtonSetting {
	display: flex;
	justify-content: center;
}

/* Yes No ボタン自体の装飾 */
.modal_inButton {
	width: 150px;
	padding: 5px !important;
	margin-right: 10px;
	margin-left: 10px;
	border: solid 1px #aaa;
	background: #efefef;
	border-radius: 3px;
}
/* モーダル用サンプル用 CSS end  ================== */
</style>

<script>

//モーダルウィンドウ表示用
window.addEventListener('DOMContentLoaded', function() {

	let button = document.getElementById('js-button');
	let menu = document.getElementById('js-menu');
	let js_yes = document.getElementById('js-yes');
	let js_no = document.getElementById('js-no');
	
	/* ボタンがクリックされたら、 メニュー表示させる */
	button.addEventListener('click',()=>{
		menu.classList.add('is-show');
		return false;
	});

	/* はい ボタンがクリックされたら、 アラート後画面を閉じる */
	js_yes.addEventListener('click',()=>{
		document.sendform.submit();
		menu.classList.remove('is-show');
		return false;
	});
	
	/* いいえ ボタンを押したら メニューを閉じる */
	js_no.addEventListener('click', (event) => {
		menu.classList.remove('is-show');
		return false;
	});
});	

</script>

<!-- ボタンがクリックされた際に表示される部分、通常は非表示 -->
<div id="js-menu" class="modal_menu" style="z-index: 99;">
	<!-- 表示される中身 -->
	<div class="menu__content">
		{{ html()->form('POST', '/admin/user/send')->attribute('name', 'sendform')->open() }}
		{{ html()->hidden('parent_id', $parent_id) }}
		{{ html()->hidden('user_id', $userInfo->id) }}
		<table style="width:95%;">
			<tr>
				<th style="width:15%;">　</th>
				<th style="width:85%;">　</th>
			</tr>
			<tr>
				<td>From</td>
				<td>{{ html()->text('from_mail', auth()->user()->email) }}</td>
			</tr>
			<tr>
				<td>To</td>
				<td>{{ html()->text('to_mail', $userInfo->email) }}</td>
			</tr>
			<tr>
				<td>件名</td>
				<td>{{ html()->text('title', '【GaishiIT】') }}</td>
			</tr>
			<tr>
				<td>内容</td>
				<td>{{ html()->textarea('content' ,"$userInfo->name 様\n\n")->rows('16')->style('font-family:initial;') }}</td>
			</tr>
		</table>
		<br>
		<!-- Yes No ボタン -->
		<div class="inButtonSetting">
			<div id="js-no" class="modal_inButton">キャンセル</div>
			<div id="js-yes" class="modal_inButton">送信</div>
		</div>
		{{ html()->form()->close() }}
	</div>
</div>
<!-- モーダルウィンドウ用サンプル1 ここまで -->


@section('content')

        <div class="mainContents">

            <div class="mainContentsInner">
                <div class="mainTtl title-main">
                    <h2>候補者詳細</h2>
                </div><!-- /.mainTtl -->
                <div style="margin-bottom: 5px;">
	                <a href="javascript:history.back();" style="text-decoration: underline;">@if ($parent_id == '0')マイページ（候補者承認）@elseif ($parent_id == '1')マイページ（面談進捗管理）@elseif ($parent_id == '2')候補者管理@elseif ($parent_id == '3')オーナーシップ管理理@elseif ($parent_id == '4')新規候補者の承認@elseif ($parent_id == '5')採用者一覧@endif</a> >> 候補者詳細
                </div>
                
                <div class="containerContents">

					<section class="secContents-mb">
                        <div class="secContentsInner">
                            
                            <div class="containerUserDetail">
                                <div class="userDetailInner">

                                    <div class="userDetailMain">
                                        <p class="name">{{ $userInfo->name }}（{{ $userInfo->nick_name }}）</p>
                                        <p class="status"><span class="off">@if ($userInfo->aprove_flag == '1')承認済@elseif ($userInfo->aprove_flag == '2')リジェクト@else未承認@endif</span></p>
                                     </div><!-- /.userDetailMain -->
<br><br>
                                     <div class="secBtnHead">
                                         <div class="secBtnHead-btn">
                                             <ul class="item-btn">
                                                 <li><a href="#" id="js-button" class="squareBtn btn-large">メール</a></li>
                                                 @if ($userInfo->aprove_flag == '0')
                                                     <li>
                                                         {{ Form::open(['url' => '/admin/user/change', 'name' => 'aproveform' ]) }}
                                                         {{ Form::hidden('user_id',  $userInfo->id) }}
                                                         {{ Form::hidden('aprove', '1') }}
                                                         <a href="javascript:aproveform.submit()" class="squareBtn btn-large">承認</a>
														{{ html()->form()->close() }}
                                                     </li>
                                                     <li>
                                                         {{ Form::open(['url' => '/admin/user/change', 'name' => 'rejectform' ]) }}
                                                         {{ Form::hidden('user_id',  $userInfo->id) }}
                                                         {{ Form::hidden('aprove', '2') }}
                                                         <a href="javascript:rejectform.submit()" class="squareBtn btn-large">リジェクト</a>
														{{ html()->form()->close() }}
                                                     </li>
                                                 @endif
                                             </ul><!-- /.item -->
                                        </div><!-- /.secBtnHead-btn -->
                                    </div><!-- /.ecBtnHead -->

送信履歴
<table style="font-size: 12px;">
<tr>
	<th style="padding: 5px 10px;text-align:left;">送信日</th><th style="padding: 5px 10px;text-align:left;">エージェント名</th>
</tr>
@foreach ($agentHist as $hist)
<tr>
	<td style="padding: 5px 10px;">{{ $hist->created_at }}</td><td style="padding: 5px 10px;">{{ $hist->getAgentName() }}</td>
</tr>
@endforeach
</table>
                                </div><!-- /.userDetailInner -->
                            </div><!-- /.containerUserDetail -->

                        </div>
					</section><!-- /.secContents-mb -->

					<section class="secContents">

                        <div class="tab_box">
                            <div class="btn_area">
                                <p class="tab_btn active">基本情報</p>
                                <p class="tab_btn">職務経歴書</p>
                                <p class="tab_btn">履歴書</p>
                                <p class="tab_btn">採用・オーナーシップ</p>
                            </div>
							<div class="panel_area">

{{-- 基本情報 --}}
								<div class="tab_panel active">
                                    <div class="containerTblUserInfo">
                                        <div class="tblCaption">
                                            <h2 class="tblCaptionTitle">候補者情報</h2>
                                        </div><!-- /.tblCaption -->
                                        
                                        <table class="tblUserInfo">
                                        
                                            <tr><th width="50%">お名前</th><th>メールアドレス</th></tr>
                                            <tr><td>{{ $userInfo->name }}</td><td>{{ $userInfo->email }}</td></tr>

                                            <tr><th>生年月日</th><th>性別</th></tr>
                                            <tr><td>{{ str_replace('-','/', substr($userInfo->birthday, 0 ,10)) }}</td><td>@if ($userInfo->sex == '1')男@elseif ($userInfo->sex == '2')女@else選択しない@endif</td>
                                            </tr>
                                        </table>
										<br>
										
                                        <div class="tblCaption">
                                            <h2 class="tblCaptionTitle">キャリア情報</h2>
                                        </div><!-- /.tblCaption -->
                                        
                                        <table class="tblUserInfo">

                                            <tr><th width="50%">最終学歴</th><th>勤務先（現在または在籍していた）</th></tr>
                                            <tr>
                                                <td>{{ $userInfo->graduation }}</td>
                                                <td>{{ $userInfo->company }}</td>
                                            </tr>

                                            <tr><th colspan="2">職種</th></tr>
                                            <tr><td>
                                             	@if ($userInfo->job == 1)
                                             		IC
                                                @elseif ($userInfo->job == '2')
                                                	Management　　　　年数 {{ $userInfo->mgr_year }}年 / 人数 {{ $userInfo->mgr_member }}人
                                                @else
                                                    未設定
                                                @endif
                                            </td>
                                            <td>{{ $userInfo->occupation }}</td>
                                            </tr>

                                            <tr><th>事業部・部門</th><th>役職</th></tr>
                                            <tr><td>{{ $userInfo->section }}</td><td>{{ $userInfo->job_title }}</td></tr>

                                            <tr><th colspan="2">職務内容</th></tr>
                                            <tr><td colspan="2">
                                             	{!! nl2br(e($userInfo->job_content)) !!}
                                            </td></tr>

                                            <tr><th>過去3年の平均実績（Actual Earnings）</th><th>理論年収（OTE）</th></tr>
                                            <tr><td>{{ $userInfo->actual_income }} 万円</td><td>{{ $userInfo->ote_income }} 万円</td></tr>

                                            <tr><th colspan="2">上記以外の過去の勤務先</th></tr>
                                            <tr><td colspan="2">
                                             	{{ $userInfo->old_company }}
                                            </td></tr>

                                            <tr><th colspan="2">キャリアに関する希望</th></tr>
                                            <tr><td colspan="2">{!! nl2br(e($userInfo->request_carrier)) !!}</td></tr>

                                            <tr><th colspan="2">非表示企業</th></tr>
                                            <tr><td colspan="2">{{ $userInfo->getNoCompany() }}</td></tr>
                                        </table>
										<br>
										
                                        <div class="tblCaption">
                                            <h2 class="tblCaptionTitle">転職のご希望</h2>
                                        </div><!-- /.tblCaption -->
                                        <table class="tblUserInfo">

                                            <tr><th width="50%">転職希望時期</th><th>希望勤務地</th></tr>
                                            <tr><td>{{ $userInfo->getChangeTime() }}</td><td>{{ $userInfo->location_name }}　{{ $userInfo->else_location }}</td></tr>

                                            <tr><th colspan="2">転職を希望する業種</th></tr>
                                            <tr><td colspan="2">{{ $userInfo->buscat_name }}</td></tr>

                                            <tr><th colspan="2">転職を希望する職種</th></tr>
                                            <tr><td colspan="2">{{ $userInfo->job_cat_detail_name }}</td></tr>

                                            <tr><th colspan="2">希望年収</th></tr>
                                            <tr><td colspan="2">{{ $userInfo->getIncome() }}</td></tr>

                                        </table><!-- /.tblUserInfo -->
                                    </div><!-- /.containerTblUserInfo -->   
								</div>
{{-- END 基本情報 --}}

{{-- 職務経歴書 --}}
								<div class="tab_panel">
                                    <div class="containerTblUserInfo">
                                        <div class="tblCaption">
                                            <h2 class="tblCaptionTitle">職務経歴書</h2>
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
{{-- END 職務経歴書 --}}

{{-- 履歴書 --}}
								<div class="tab_panel">
                                    <div class="containerTblUserInfo">
                                        <div class="tblCaption">
                                            <h2 class="tblCaptionTitle">履歴書</h2>
                                        </div><!-- /.tblCaption -->
                                        <table class="tblUserInfo">
                                            <tr><th>氏名</th></tr>
                                            <tr><td>{{ $userInfo->name }}</td></tr>

                                            <tr><th>ふりがな</th></tr>
                                            <tr><td>{{ $userInfo->name_kana }}</td></tr>
                                            
                                            <tr><th>役職</th></tr>
                                            <tr><td>{{ $userInfo->job_title }}</td></tr>

                                            <tr><th>性別</th></tr>
                                            <tr><td>@if ($userInfo->sex == '1')男@elseif ($userInfo->sex == '2')女@else選択しない@endif </td></tr>

                                            <tr><th>生年月日</th></tr>
                                            <tr><td>{{ str_replace('-','/', substr($userInfo->birthday, 0 ,10)) }}</td></tr>
                                             
                                             <tr><th>現住所</th></tr>
                                            <tr><td>{{ $userInfo->pref_name }} {{ $userInfo->address }}</td></tr>

                                            <tr><th>メールアドレス</th></tr>
                                            <tr><td>{{ $userInfo->hist_email }}</td></tr>
                                             
                                             <tr><th>学歴・職歴</th></tr>
                                            <tr><td>{!! nl2br(e($userInfo->job_hist)) !!}</td></tr>

                                             <tr><th>志望の動機、自己PR、趣味、特技など</th></tr>
                                            <tr><td>{!! nl2br(e($userInfo->motivation)) !!}</td></tr>

                                             <tr><th>扶養家族</th></tr>
                                            <tr><td>{{ $userInfo->dependents }} 人</td></tr>

                                             <tr><th>配偶者</th></tr>
                                            <tr><td>@if ($userInfo->spouse == '1')あり@elseなし@endif</td></tr>

                                             <tr><th>配偶者の扶養義務</th></tr>
                                            <tr><td>@if ($userInfo->obligation == '1')あり@elseなし@endif</td></tr>

                                        </table><!-- /.tblUserInfo -->
                                    </div><!-- /.containerTblUserInfo -->   
								</div>
{{-- END 履歴書 --}}

{{-- 採用・オーナーシップ --}}
								<div class="tab_panel">
                                    <div class="tblCaption">
                                        <h2 class="tblCaptionTitle">採用</h2>
                                    </div><!-- /.tblCaption -->

                                    <table class="tbl-user-5th">
                                        <tr>
                                            <th>入社日</th>
                                            <th>企業名</th>
                                            <th>部門</th>
                                            <th>ジョブ</th>
                                        </tr>
                                        @foreach ($interviewList as $int)
                                        <tr>
                                            <td>{{ str_replace('-','/', substr($int['entrance_date'], 0 ,10)) }}</td>
                                            <td>{{ $int['company_name'] }}</td>
                                            <td>{{ $int['unit_name'] }}</td>
                                            <td>{{ $int['job_name'] }}</td>
                                        </tr>
                                         @endforeach
                                    </table>
                                    <br><br>
                                    <div class="tblCaption">
                                        <h2 class="tblCaptionTitle">オーナーシップ</h2>
                                    </div><!-- /.tblCaption -->

                                    <table class="tbl-user-5th">
                                        <tr>
                                            <th>発生日</th>
                                            <th>企業名</th>
                                            <th>部門</th>
                                            <th>内容</th>
                                            <th>種別</th>
                                        </tr>
                                        @foreach ($ownerList as $int)
                                        <tr>
                                            <td>{{ str_replace('-','/', substr($int['updated_at'], 0 ,10)) }}</td>
                                            <td>{{ $int['company_name'] }}</td>
                                            <td>{{ $int['unit_name'] }}</td>
                                            <td>@if ($int['interview_type'] == '1'){{ $int['job_name'] }}@elseif ($int['interview_type'] == '2'){{ $int['event_name'] }}@else @endif</td>
                                            <td>@if ($int['interview_type'] == '1')正式応募@elseif ($int['interview_type'] == '2')イベント@elseカジュアル面談@endif</td>
                                        </tr>
                                         @endforeach
                                    </table>
								</div><!-- tab_panel -->
{{-- END 採用・オーナーシップ --}}

							</div><!-- panel_area -->
						</div><!-- class="tab_box -->

					</section><!-- /.secContents -->
                    
				</div><!-- /.containerContents -->
			</div><!-- /.mainContentsInner -->
		</div><!-- /.mainContents -->


@endsection
