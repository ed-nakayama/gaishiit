@extends('layouts.comp.auth')

@section('content')

<head>
	<title>候補者詳細｜{{ config('app.name', 'Laravel') }}</title>
</head>


{{--@include('comp.member_activity')--}}

            <div class="mainContentsInner">

            <div class="mainContentsInner">
                <div class="mainTtl title-main">
                    <h2>候補者詳細</h2>
                </div><!-- /.mainTtl -->
                <div style="margin-bottom: 5px;">
	                <a href="javascript:history.back();" style="text-decoration: underline;">@if ($parent_id == '0')マイページ@elseif ($parent_id == '1')新しい候補者を探す@elseif ($parent_id == '2')候補者管理@elseif ($parent_id == '3')面談進捗管理 - 採用者@elseif ($parent_id == '4')面談進捗管理 - 終了@elseif ($parent_id == '5')採用者一覧@endif</a> >> 候補者詳細
                </div>
                
                <div class="containerContents">

                    <section class="secContents-mb">
                        <div class="secContentsInner">
                            
                            <div class="containerUserDetail">
                                <div class="userDetailInner">

                                    <div class="userDetailMain">
                                        <p class="name">@if ($userInfo->open_flag == '1'){{ $userInfo->name }}@else{{ $userInfo->nick_name }}@endif</p>
                                        <p class="status">採用：<span class="off">@if (!empty($userComp)){{ $userComp->result_name }}@else未設定@endif</span></p>
                                    </div><!-- /.userDetailMain -->

                                    <div class="userDetailMemo">
                                        <div class="memo-accordion">
                                            <div class="memo-accordion-container">
                                                <h3 class="js-ac-title">メモ</h3>
                                                <div class="memo-accordion-content">
                                                    @foreach ($userCompMemo as $memo)
                                                    
                                                        <div class="memo-container">
                                                            <div class="memo-container-title">
                                                                <p class="name">{{ $memo->member_name }}</p>
                                                                <p class="date">{{ $memo->created_at->format('Y/m/d/H:i') }}</p>
                                                            </div><!-- /.memo-container-title -->
                                                            <p>{!! nl2br(e($memo->content)) !!}</p>
                                                        </div><!-- /.memo-container -->
                                                    @endforeach

                                                    {{ Form::open(['url' => '/comp/user/memo', 'name' => 'memoform' , 'id' => 'memoform']) }}
                                                    {{ Form::hidden('user_id', $userDetail->id, ['class' => 'form-control', 'id'=>'user_id' ] )}}
                                                        <div class="memo-container txtArea">
                                                            <textarea class="form-mt" name="content" id="content" cols="30" rows="5" placeholder="新しいメモを投稿"></textarea>
                                                            <ul class="oneRow">
                                                            @error('content')
                                                               <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                                                            @enderror
                                                            </ul>
                                                        </div><!-- /.memo-container -->

                                                        <div class="btnContainer">
                                                            <a href="javascript:memoform.submit()" class="squareBtn btn-medium">投稿</a>
                                                        </div><!-- /.btn-container -->
                                                    {{ Form::close() }}
                                                    
                                                    
                                                </div><!--/.memo-accordion-content-->
                                            </div><!-- /.memo-accordion-container -->
                                        </div><!-- .memo-accordion-->

                                        <div class="btnContainer">
                                            <ul class="btnContainerList">
                                                <li>
                                                	@if ($comp->in_mail_casual <= $inmail->inmail_casual)
                                                 		<a href="javascript:void(0)" class="squareBtn btn-large" style="background-color: #B1B1B1;">カジュアル面談を打診</a><div style="color:red;">残り打診回数 0 回</div>
                                                	@else
                                                 		<a href="/comp/interview/request?int_type=0&user_id={{ $userDetail->id }}" class="squareBtn btn-large">カジュアル面談を打診</a><div style="color:red;">残り打診回数 {{ $comp->in_mail_casual - $inmail->inmail_casual }} 回</div>
                                                 	@endif
	                                            </li>
                                                <li>
                                                	@if ($comp->in_mail_formal <= $inmail->inmail_formal)
                                                 		<a href="javascript:void(0)" class="squareBtn btn-large" style="background-color: #B1B1B1;">正式応募を打診</a><div style="color:red;">残り打診回数 0 回</div>
                                                	@else
                                                 		<a href="/comp/interview/request?int_type=1&user_id={{ $userDetail->id }}" class="squareBtn btn-large">正式応募を打診</a><div style="color:red;">残り打診回数 {{ $comp->in_mail_formal - $inmail->inmail_formal }} 回</div>
                                                 	@endif
                                                </li>
                                            </ul>
                                        </div><!-- /.btn-container -->

                                    </div><!-- /.userDetailMemo -->

                                </div><!-- /.userDetailInner -->
                            </div><!-- /.containerUserDetail -->

                        </div>
                    </section><!-- /.secContents-mb -->

                    <section class="secContents">

                        <div class="tab_box">
                            <div class="btn_area">
                                <p class="tab_btn active">コンタクト履歴</p>
                                <p class="tab_btn">基本情報</p>
                                <p class="tab_btn">職務経歴書</p>
                                <p class="tab_btn">履歴書</p>
                            </div>
                            <div class="panel_area">

                                <div class="tab_panel active">
                                    <table class="tbl-user-contact">
                                        <tr>
                                            <th style="text-align:left;">最終更新日</th>
                                            <th style="text-align:left;">種別</th>
                                            <th style="text-align:left;">承認</th>
                                            <th style="text-align:left;">ステータス</th>
                                            <th style="text-align:left;">結果</th>
                                            <th style="text-align:left;">ジョブ名称</th>
                                            <th style="text-align:left;">担当</th>
                                        </tr>
                                        @foreach ($interviewList as $int)
                                        <tr>
                                            <td>{{ $int->updated_at->format('Y/m/d/H:i') }}</td>
                                            <td>
                                                @if ($int->interview_type == '2')
                                            	    <a>イベント</a>
                                                @elseif ($int->interview_type == '0')
                                            	    <a>カジュアル面談</a>
                                                @elseif ($int->interview_type == '1')
                                            	    <a>正式応募</a>
                                                @else
                                            	    <a>{{ $int->stage_name }}</a>
                                            	@endif
                                            </td>
                                            <td>
                                                @if ($int->aprove_flag == '1')
                                            	    <a>承認</a>
                                                @elseif ($int->aprove_flag == '2')
                                            	    <a>否認</a>
                                                @else
                                            	    <a></a>
                                            	@endif
                                            </td>
                                            <td>{{ $int->status_name }}</td>
                                            <td>@if ($int->interview_type == '1'){{ $int->result_name }}@endif</td>
                                            <td>
                                                @if ( ($int->interview_type == '0') && ($int->interview_kind == '0') )
                                                @elseif ( ($int->interview_type == '0') && ($int->interview_kind == '1') )
                                                    {{ $int->unit_name }}
                                                @elseif ( ($int->interview_type == '0') && ($int->interview_kind == '2') )
                                                   {{ $int->job_name }}
                                                @elseif ($int->interview_type == '1')
                                                   {{ $int->job_name }}
                                                @elseif ($int->interview_type == '2')
                                                   {{ $int->event_name }}
                                                @endif
                                            </td>
                                            <td>{{ $int->person_name }}</td>
                                        </tr>
                                         @endforeach
                                    </table>
                                </div>



                                <div class="tab_panel">
                                    <div class="containerTblUserInfo">
                                        <div class="tblCaption">
                                            <h2 class="tblCaptionTitle">候補者情報</h2>
                                            <ul class="tblCaptionList">
                                                <li>
                              						{{ Form::open(['url' => '/comp/pdf/base', 'name' => 'baseform' ]) }}
                               						{{ Form::hidden("user_id", $userInfo->id, ['class' => 'form-control'] )}}
                                                 	<a href="javascript:baseform.submit()">&gt;&gt;PDFダウンロード</a>
                               						{{ Form::close() }}
                                                </li>
                                            </ul>
                                        </div><!-- /.tblCaption -->
                                        <table class="tblUserInfo">

                                            <tr><th width="50%">お名前</th><th>メールアドレス</th></tr>
                                            <tr><td>@if ($userInfo->open_flag == '1'){{ $userInfo->name }}@else ************ @endif</td><td>@if ($userInfo->open_flag == '1'){{ $userInfo->email }}@else ************ @endif</td></tr>

                                            <tr><th>生年月日</th><th>性別</th></tr>
                                            <tr><td>{{ str_replace('-','/', substr($userInfo->birthday, 0 ,10)) }}</td><td>@if ($userInfo->sex == '1')男@elseif ($userInfo->sex == '2')女@else選択しない@endif</td></tr>

                                        </table>
										<br>

                                        <div class="tblCaption">
                                          <h2 class="tblCaptionTitle">キャリア情報</h2>
                                        </div><!-- /.tblCaption -->
                                        <table class="tblUserInfo">


                                            <tr><th>最終学歴</th><th>勤務先（現在または在籍していた）</th></tr>
                                            <tr><td>{{ $userInfo->graduation }}</td><td>{{ $userInfo->company }}</td></tr>

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
                                            <tr><td colspan="2">{{ $userInfo->jobcat_name }}</td></tr>
                                         </table><!-- /.tblUserInfo -->
                                    </div><!-- /.containerTblUserInfo -->   
                                </div>



                                <div class="tab_panel">
                                    <div class="containerTblUserInfo">
                                        <div class="tblCaption">
                                            <h2 class="tblCaptionTitle">職務経歴書</h2>
{{--											@if ($userInfo->open_flag == '1')--}}
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
{{--											@endif--}}
                                        </div><!-- /.tblCaption -->
{{--										@if ($userInfo->open_flag == '1')--}}
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
{{--										@endif--}}
                                    </div><!-- /.containerTblUserInfo -->   
                                </div>



                                <div class="tab_panel">
                                    <div class="containerTblUserInfo">
                                        <div class="tblCaption">
                                            <h2 class="tblCaptionTitle">履歴書</h2>
											@if ($userInfo->open_flag == '1')
                                            <ul class="tblCaptionList">
                                                <li>
                              						{{ Form::open(['url' => '/comp/pdf/vitae', 'name' => 'vitaeform' ]) }}
                               						{{ Form::hidden("user_id", $userInfo->id, ['class' => 'form-control'] )}}
                                                 	<a href="javascript:vitaeform.submit()">&gt;&gt;PDFダウンロード</a>
                               						{{ Form::close() }}
                                                </li>
                                            </ul>
											@endif
                                        </div><!-- /.tblCaption -->
										@if ($userInfo->open_flag == '1')
                                        <table class="tblUserInfo">
                                          
                                            <tr><th>氏名</th></tr>
                                            <tr><td>@if ($userInfo->open_flag == '1'){{ $userInfo->name }}@else ************ @endif</td></tr>

                                            <tr><th>ふりがな</th></tr>
                                            <tr><td>@if ($userInfo->open_flag == '1'){{ $userInfo->name_kana }}@else ************ @endif</td></tr>
                                            
                                            <tr><th>役職</th></tr>
                                            <tr><td>{{ $userInfo->job_title }}</td></tr>

                                            <tr><th>性別</th></tr>
                                            <tr><td>@if ($userInfo->sex == '1')男@elseif ($userInfo->sex == '2')女@else選択しない@endif </td></tr>

                                            <tr><th>生年月日</th></tr>
                                            <tr><td>{{ str_replace('-','/', substr($userInfo->birthday, 0 ,10)) }}</td></tr>
                                             
                                             <tr><th>現住所</th></tr>
                                            <tr><td>{{ $userInfo->pref_name }} @if ($userInfo->open_flag == '1'){{ $userInfo->address }}@endif</td></tr>

                                            <tr><th>メールアドレス</th></tr>
                                            <tr><td>@if ($userInfo->open_flag == '1'){{ $userInfo->email }}@else ************ @endif</td></tr>
                                             
                                             <tr><th>学歴・職歴</th></tr>
                                            <tr><td>{!! nl2br(e($userInfo->job_hist)) !!}</td></tr>

                                             <tr><th>志望の動機、自己PR、趣味、特技など</th></tr>
                                            <tr><td>{!! nl2br(e($userInfo->motivation)) !!}</td></tr>

                                             <tr><th>扶養家族</th></tr>
                                            <tr><td>{{ $userInfo->dependents }} 人</td></tr>

                                             <tr><th>配偶者</th></tr>
                                            <tr><td>@if ($userInfo->spouse == '1')あり@elseなし@endif<td></tr>

                                             <tr><th>配偶者の扶養義務</th></tr>
                                            <tr><td>@if ($userInfo->obligation == '1')あり@elseなし@endif</td></tr>

                                        </table><!-- /.tblUserInfo -->
										@endif
                                   </div><!-- /.containerTblUserInfo -->   
                                </div>

                            </div>
                        </div>

                    </section><!-- /.secContents -->
                    
                </div><!-- /.containerContents -->

            </div><!-- /.mainContentsInner -->
            
        </div><!-- /.mainContents -->



<script>

$(document).ready(function() {

	@if ( $errors->has('content') || $memo_open == '1')
		$(".memo-accordion-content").show();
	@endif


});

</script>


@endsection
