@extends('layouts.comp.pdf')

@section('content')

        <div class="mainContents">
            <div class="mainContentsInner">
                <div class="containerContents">
                    <section class="secContents">

                                    <div class="containerTblUserInfo">
                                        <div class="tblCaption">
                                            <h2 class="tblCaptionTitle">職務経歴書</h2>
                                            <ul class="tblCaptionList">
                                            </ul>
                                        </div><!-- /.tblCaption -->
                                        <table class="tblUserInfo">

                                            <tr><th>候補者番号</th></tr>
                                            <tr><td>{{ $userInfo['nick_name'] }}</td></tr>
                                            
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
                                            <tr><td>{!! nl2br($userInfo->job_detail) !!}</td></tr>

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

                    </section><!-- /.secContents -->
                </div><!-- /.containerContents -->
            </div><!-- /.mainContentsInner -->
        </div><!-- /.mainContents -->

@endsection
