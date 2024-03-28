@extends('layouts.comp.pdf')

@section('content')

        <div class="mainContents">
            <div class="mainContentsInner">
                <div class="containerContents">
                    <section class="secContents">

									<div class="containerTblUserInfo" style="max-width: 85%;">
                                        <div class="tblCaption">
                                            <h2 class="tblCaptionTitle">基本情報</h2>
                                            <ul class="tblCaptionList">
                                            </ul>
                                        </div><!-- /.tblCaption -->
                                        
										<table class="tblUserInfo">

                                            <tr><th width="50%">氏名</th><th>メールアドレス</th></tr>
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


                                            <tr><th>転職希望時期</th><th>最終学歴</th></tr>
                                            <tr>
                                                <td>
                                                @if ($userInfo->change_time == '1')
                                                    今すぐ
                                                @elseif ($userInfo->change_time == '2')
                                                    {{ $userInfo->change_year . '年' . $userInfo->change_month  . '月 頃' }}
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
                                                @endif <br>
                                             	{!! nl2br($userInfo->job_content) !!}
                                            </td></tr>

                                            <tr><th>過去3年の平均実績（Actual Earnings）</th><th>理論年収（OTE）</th></tr>
                                            <tr><td>{{ $userInfo->actual_income }} 万円</td><td>{{ $userInfo->ote_income }} 万円</tr>

                                            <tr><th colspan="2">希望勤務地</th></tr>
                                             <tr><td colspan="2">{{ $userInfo->location_name }}</td></tr>

                                            <tr><th colspan="2">キャリアに関する希望</th></tr>
                                            <tr><td colspan="2">{!! nl2br($userInfo->request_carrier) !!}</td></tr>

                                            <tr><th colspan="2">転職を希望する業種</th></tr>
                                            <tr><td colspan="2">{{ $userInfo->buscat_name }}</td></tr>

                                            <tr><th colspan="2">転職を希望する職種</th></tr>
                                            <tr><td colspan="2">{{ $userInfo->jobcat_name }}</td></tr>

										</table><!-- /.tblUserInfo -->
									</div><!-- /.containerTblUserInfo -->   

                    </section><!-- /.secContents -->
                </div><!-- /.containerContents -->
            </div><!-- /.mainContentsInner -->
        </div><!-- /.mainContents -->

@endsection
