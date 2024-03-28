@extends('layouts.comp.pdf')

@section('content')

        <div class="mainContents">
            <div class="mainContentsInner">
                <div class="containerContents">
                    <section class="secContents">

                                    <div class="containerTblUserInfo">
                                        <div class="tblCaption">
                                            <h2 class="tblCaptionTitle">Resume</h2>
                                            <ul class="tblCaptionList">
                                            </ul>
                                        </div><!-- /.tblCaption -->
                                        <table class="tblUserInfo">

                                            <tr><th>Candidate Number</th></tr>
                                            <tr><td>{{ $userInfo['nick_name'] }}</td></tr>
                                            
                                            <tr><th>Company Name</th></tr>
                                            <tr><td>{{ $userInfo->en_company }}</td></tr>
                                            
                                            <tr><th>Section Name</th></tr>
                                            <tr><td>{{ $userInfo->en_unit_name }}</td></tr>
                                            
                                            <tr><th>Job Title</th></tr>
                                            <tr><td>{{ $userInfo->en_job_title }}</td></tr>

                                            <tr><th>Tenure</th></tr>
                                            <tr><td>
                                             	{{ $userInfo->enroll_from_year }}/{{ $userInfo->enroll_from_month }}/{{ $userInfo->enroll_from_day }}
                                            	 ～
                                             	@if ($userInfo->enroll_to_year == '0')
                                             		Present
                                             	@elseif ($userInfo->enroll_to_year == '')
                                             	@else
                                              		{{ $userInfo->enroll_to_year }}/{{ $userInfo->enroll_to_month }}/{{ $userInfo->enroll_to_yday }}/
												@endif
											</td></tr>
											
                                            <tr><th>Job Contents / Industry Responsibilities / Merchandise handled / Project</th></tr>
                                            <tr><td>{!! nl2br($userInfo->en_job_detail) !!}</td></tr>

                                            <tr><th>Award</th></tr>
                                            <tr><td>{{ $userInfo->en_award }}</td></tr>

                                            <tr><th>English</th></tr>
                                            <tr><td>{{ $userInfo->english_name }}</td></tr>

                                            <tr><th>TOEIC</th></tr>
                                            <tr><td>{{ $userInfo->toeic }} Points</td></tr>

                                            <tr><th>Japanese</th></tr>
                                            <tr><td>{{ $userInfo->japanese_name }}</td></tr>

                                        </table><!-- /.tblUserInfo -->
                                    </div><!-- /.containerTblUserInfo -->   

                    </section><!-- /.secContents -->
                </div><!-- /.containerContents -->
            </div><!-- /.mainContentsInner -->
        </div><!-- /.mainContents -->

@endsection
