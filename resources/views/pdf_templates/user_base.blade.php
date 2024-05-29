@extends('layouts.comp.pdf')

@section('content')

        <div class="mainContents">
            <div class="mainContentsInner">
                <div class="containerContents">
                    <section class="secContents">

									<div class="containerTblUserInfo" style="max-width: 85%;">
                                        <div class="tblCaption">
                                            <h2 class="tblCaptionTitle">候補者情報</h2>
                                        </div><!-- /.tblCaption -->
                                        
										<table class="tblUserInfo">

                                            <tr><th width="50%">お名前</th><th>メールアドレス</th></tr>
                                            <tr><td>@if ($userInfo->open_flag == '1'){{ $userInfo->name }}@else {{ $userInfo->nick_name }} @endif</td><td>@if ($userInfo->open_flag == '1'){{ $userInfo->email }} @else ************ @endif</td></tr>

                                            <tr><th>生年月日</th><th>性別</th></tr>
                                            <tr><td>{{ str_replace('-','/', substr($userInfo->birthday, 0 ,10)) }}</td>
                                            	<td>
                                                    @if ($userInfo->sex == '1')
                                                        男
                                                    @elseif ($userInfo->sex == '2')
                                                        女
                                                    @else
                                                        選択しない
                                                    @endif
                                            	</td>
                                            </tr>
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
									</div><!-- /.containerTblUserInfo -->   

                    </section><!-- /.secContents -->
                </div><!-- /.containerContents -->
            </div><!-- /.mainContentsInner -->
        </div><!-- /.mainContents -->

@endsection
