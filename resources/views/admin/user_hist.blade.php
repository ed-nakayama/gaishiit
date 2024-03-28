@extends('layouts.admin.auth')
<head>
    <title>候補者の承認履歴 | {{ config('app.name', 'Laravel') }}</title>
</head>

@section('content')

            <div class="mainContentsInner-oneColumn">

                <div class="secTitle">
                    <div class="title-main">
                        <h2>候補者の承認履歴</h2>
                    </div><!-- /.mainTtl -->
                </div><!-- /.sec-title -->
               
                
                <div class="containerContents">
                    
                    <section class="secContents-mb">
                        <div class="secContentsInner">

                           <div class="secBtnHead">
                                <div class="secBtnHead-btn">
                                    <ul class="item-btn">
                                        <li>
                                            <div class="selectWrap">
                                               {{ Form::open(['url' => '/admin/user/hist', 'name' => 'histform' ]) }}
                                                <select name="sel_aprove"  class="select-no" onchange="changeSel()" id="sel_aprove">
                                                    <option value="1" @if ($sel_aprove == '1')selected @endif>承認</option>
                                                    <option value="2" @if ($sel_aprove == '2')selected @endif>リジェクト</option>
                                                </select>
                                               {{ Form::close() }}
                                            </div>
                                        </li>
                                    </ul><!-- /.item -->
                                </div><!-- /.secBtnHead-btn -->
                           </div>

@if(!isset($userList[0]))
  <div>※データはありません。</div>
@else
							<p style="text-align: center;">全{{ $userList->total() }}件中 {{  ($userList->currentPage() -1) * $userList->perPage() + 1}}-{{ (($userList->currentPage() -1) * $userList->perPage() + 1) + (count($userList) -1)  }}件</p>
                           <table class="tbl-10th" id="userTable">
                                <tr>
                                    <th>登録日</th>
                                    <th>氏名</th>
                                    <th>年齢</th>
                                    <th>勤務先</th>
                                    <th>現在の職務内容</th>
                                    <th>役職</th>
                                    <th>最終学歴</th>
                                    <th>理論年収（OTE）</th>
                                    <th>希望勤務地</th>
                                </tr>
                               
                              @foreach ($userList as $user)
                                <tr>
                                    <td>{{ str_replace('-','/', substr($user->created_at, 0 ,10)) }}</td>
                                    <td>
										{{ Form::open(['url' => '/admin/user/detail', 'name' => 'userform' . $user->id]) }}
										{{ Form::hidden('user_id', $user->id) }}
										{{ Form::hidden('hist_flag', '1') }}
										<a href="javascript:userform{{ $user->id }}.submit()"  style="text-decoration: underline;">{{ $user->name }}</a>
										{{ Form::close() }}
                                   </td>
                                    <td>{{ $user->age }}</td>
                                    <td>{{ $user->company }}</td>
                                    <td>{{ mb_strimwidth($user->job_content, 0, 40, "...") }}</td>
                                    <td>{{ $user->job_title }}</td>
                                    <td>{{ $user->graduation . ' ' . $user->department }}</td>
                                    <td>{{ $user->ote_income }} 万円</td>
                                    <td>{{ $user->location_name }}</td>
                                </tr>
                              @endforeach

                            </table>
                            <div class="pager">
                               {{ $userList->appends(request()->query())->links('pagination.admin') }}
                            </div>
@endif

						</div><!-- /.secContentsInner -->
					</section><!-- /.secContents-mb -->
				</div><!-- /.containerContents -->
            </div><!-- /.mainContentsInner-oneColumn -->


<script type="text/javascript">

function changeSel() {

	document.histform.elements["sel_aprove"].value = document.getElementById( "sel_aprove" ).value;
	document.histform.submit();
}

$(document).ready(function(){
  $("#userTable tr:even").not(':first').addClass("evenRow");
  $("#userTable tr").not(':first').hover(
    function(){
        $(this).addClass("focusRow");
    },function(){
        $(this).removeClass("focusRow");
 });
});


function OnLinkClick($uid) {
	document.userform.elements["user_id"].value = $uid;
	document.userform.submit();
};


</script>

<style>
#userTable { cursor: pointer; }
.evenRow { background-color: #F5F5F5; }
.focusRow { background-color: #ffffcc; }

</style>
@endsection
