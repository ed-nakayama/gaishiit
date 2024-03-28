@extends('layouts.admin.auth')
<head>
    <title>新規候補者の承認 | {{ config('app.name', 'Laravel') }}</title>
</head>

@section('content')

            <div class="mainContentsInner-oneColumn">

                <div class="secTitle">
                    <div class="title-main">
                        <h2>新規候補者の承認</h2>
                    </div><!-- /.mainTtl -->
                </div><!-- /.sec-title -->
               
                
                <div class="containerContents">
                    
                    <section class="secContents-mb">
                        <div class="secContentsInner">

                           {{ Form::open(['url' => '/admin/user/aprove', 'name' => 'aproveform' ]) }}

                           <div class="secBtnHead">
                                <div class="secBtnHead-btn">
                                    <ul class="item-btn">
{{--                                    
                                        <li><input type="checkbox" id="sel_all" onchange="change_all()" >全て選択</li>
                                        <li>
                                            <div class="selectWrap">
                                            <select name="aprove"  class="select-no">
                                                <option value="1">承認</option>
                                                <option value="2">リジェクト</option>
                                            </select>
                                            </div>
                                        </li>
                                        <li><a href="javascript:aproveform.submit()" class="squareBtn">保存</a></li>
--}}
                                        <li><a href="/admin/user/hist" class="squareBtn">承認履歴一覧</a></li>
                                    </ul><!-- /.item -->
                                </div><!-- /.secBtnHead-btn -->
                           </div>

@if(!isset($userList[0]))
  <div>※データはありません。</div>
@else
                           <table class="tbl-10th" id="userTable">
                                <tr>
{{--                                
                                    <th></th>
--}}                                    
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
                               
                              @foreach ($userList as $int)
                                <tr>
{{--                                
                                    <td><input type="checkbox" name="sel[]" value="{{ $int->id }}"> </td>
--}}                                    
                                    <td>{{ str_replace('-','/', substr($int->created_at, 0 ,10)) }}</td>
                                    <td>
                                        <a href="javascript:void(0);" onclick="OnLinkClick({{ $int->id }});" style="text-decoration: underline;">{{ $int->name }}</a>
                                    </td>
                                    <td>{{ $int->age }}</td>
                                    <td>{{ $int->company }}</td>
                                    <td>{{ mb_strimwidth($int->job_content, 0, 40, "...") }}</td>
                                    <td>{{ $int->job_title }}</td>
                                    <td>{{ $int->graduation . ' ' . $int->department }}</td>
                                    <td>{{ $int->ote_income }}</td>
                                    <td>{{ $int->location_name }}</td>
                                </tr>
                              @endforeach

                            </table>
                            <div class="pager">
                               {{ $userList->links('pagination.admin') }}
                            </div>
@endif
						{{ html()->form()->close() }}

						</div><!-- /.secContentsInner -->
					</section><!-- /.secContents-mb -->
				</div><!-- /.containerContents -->
            </div><!-- /.mainContentsInner-oneColumn -->

    {{ Form::open(['url' => '/admin/user/detail', 'name' => 'userform']) }}
    {{ Form::hidden('user_id', '') }}
	{{ Form::hidden('parent_id', '4') }}
    {{ Form::close() }}


<script type="text/javascript">

//全選択・解除のチェックボックス
let checkbox_all = document.querySelector("#sel_all");
//チェックボックスのリスト
const checkbox2 = document.getElementsByName("sel[]")

function change_all() {

	//チェックされているか
	if (checkbox_all.checked) {
		//全て選択
		for(i = 0; i < checkbox2.length; i++) {
			checkbox2[i].checked = true
		}
	} else {
		//全て解除
		for(i = 0; i < checkbox2.length; i++) {
			checkbox2[i].checked = false
		}
	}

};


function OnLinkClick($uid) {
	document.userform.elements["user_id"].value = $uid;
	document.userform.submit();
};





$(document).ready(function(){
  $("#userTable tr:even").not(':first').addClass("evenRow");
  $("#userTable tr").not(':first').hover(
    function(){
        $(this).addClass("focusRow");
    },function(){
        $(this).removeClass("focusRow");
 });
 

});
</script>

<style>
#userTable { cursor: pointer; }
.evenRow { background-color: #F5F5F5; }
.focusRow { background-color: #ffffcc; }

</style>
@endsection
