@extends('layouts.comp.pdf')

@section('content')

        <div class="mainContents">
            <div class="mainContentsInner">
                <div class="containerContents">
                    <section class="secContents">

                                    <div class="containerTblUserInfo">
                                        <div class="tblCaption">
                                            <h2 class="tblCaptionTitle">履歴書</h2>
                                            <ul class="tblCaptionList">
                                            </ul>
                                        </div><!-- /.tblCaption -->
                                        <table class="tblUserInfo">

                                            <tr><th>候補者番号</th></tr>
                                            <tr><td>{{ $userInfo['nick_name'] }}</td></tr>
                                            

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
                                            <tr><td>{!! nl2br($userInfo->job_hist) !!}</td></tr>

                                             <tr><th>志望の動機、自己PR、趣味、特技など</th></tr>
                                            <tr><td>{!! nl2br($userInfo->motivation) !!}</td></tr>

                                             <tr><th>扶養家族</th></tr>
                                            <tr><td>{{ $userInfo->dependents }} 人</td></tr>

                                             <tr><th>配偶者</th></tr>
                                            <tr><td>@if ($userInfo->spouse == '1')あり@elseなし@endif<td></tr>

                                             <tr><th>配偶者の扶養義務</th></tr>
                                            <tr><td>@if ($userInfo->obligation == '1')あり@elseなし@endif</td></tr>

                                        </table><!-- /.tblUserInfo -->
                                    </div><!-- /.containerTblUserInfo -->   

                    </section><!-- /.secContents -->
                </div><!-- /.containerContents -->
            </div><!-- /.mainContentsInner -->
        </div><!-- /.mainContents -->

@endsection
