@extends('layouts.comp.auth')

@section('content')


<head>
	<title>面談進捗管理 - 採用者｜{{ config('app.name', 'Laravel') }}</title>
</head>

            <div class="mainContentsInner-oneColumn">

                <div class="secTitle">
                    <div class="title-main">
                        <h2>面談進捗管理 - 採用者</h2>
                    </div><!-- /.mainTtl -->
                </div><!-- /.sec-title -->
                
                
                <div class="containerContents">
                    
                    <section class="secContents-mb">
                        <div class="secContentsInner">

                           <div class="secBtnHead">
{{--                               
                                <div class="secBtnHead-btn">
                                    <ul class="item-btn">
                                        <li><a href="#modal" class="squareBtn">絞り込み</a></li>
                                    </ul><!-- /.item -->
                                </div><!-- /.secBtnHead-btn -->
--}}
                                <div class="secBtnHead-check">
                                    {{ Form::open(['url' => '/comp/client/enter/list', 'name' => 'listform' , 'id' => 'listform', 'method'=>'GET']) }}
                                        <input type="checkbox" name="only_me" value="1" @if ($search['only_me'] == '1') checked @endif  onchange="this.form.submit()"><label>自分の担当のみ表示</label>
                                    {{ Form::close() }}
                                </div><!-- /.secBtnHead-btn -->

                            </div><!-- /.sec-btn -->

@if(!isset($endList[0]))
  <div>※データはありません。</div>
@else
                           <table class="tbl-user-enter" id="beingTable">
                                <tr>
                                    <th>最終更新日</th>
                                    <th>氏名</th>
                                    <th>入社日</th>
                                    <th>ステージ</th>
                                    <th>ステータス</th>
                                    <th>ジョブ / 部門</th>
                                    <th>メモ</th>
                                    <th>担当者</th>
                                    <th></th>
                                </tr>
                               
                              @foreach ($endList as $int)
                                <tr>
                                	{{ Form::open(['url' => '/comp/user/detail', 'name' => 'userform' . $int->id ]) }}
                                	{{ Form::hidden('user_id', $int->user_id) }}
	                                {{ Form::hidden('parent_id', '3') }}
                                	{{ Form::close() }}

									{{ Form::open(['url' => '/comp/client/enter/save', 'name' => 'progform' . $int->id ]) }}
    	                            {{ Form::hidden('interview_id', $int->id) }}
                                    <td>{{ $int->updated_at->format('Y/m/d/H:i') }}</td>
                                    <td><a href="javascript:userform{{ $int->id }}.submit()" style="text-decoration: underline;">{{ $int->user_name }}</a></td>
                                    <td>
                                    	<label style="padding: 5px 5px;border: 1px solid #ccc;"><input type="date" name="entrance_date" value="{{ $int->entrance_date }}"  oninput="progChange('{{ 'progsave' . $int->id }}')"></label>
                                    </td>
                                    <td>
                                    	@if ($int->interview_type == '0')
	                                    	カジュアル面談
                                    	@else
                                    		{{ $int->stage_name }}
                                    	@endif
                                    </td>
                                    <td>{{ $int->status_name }}</td>
                                    <td>
                                        @if ( ($int->interview_type == '0') && ($int->interview_kind == '0') )
                                        @elseif ( ($int->interview_type == '0') && ($int->interview_kind == '1') )
                                            {{ $int->unit_name }}
                                        @else
                                            {{ $int->job_name }}
                                        @endif
                                    </td>
                                    <td>{{ $int->comment }}</td>
                                    <td>{{ $int->person_name }}</td>
                                    <td>
                                        <div class="btnContainer"  style="display: none;" id="{{ 'progsave' . $int->id }}">
                                           <a href="javascript:progform{{ $int->id }}.submit()" class="squareBtn btn-medium">保存</a>
                                       </div><!-- /.btn-container -->
                                    </td>
									{{ Form::close() }}
								</tr>
                              @endforeach

                            </table>
                            <div class="pager">
                               {{ $endList->appends($search)->links('pagination.comp') }}
                            </div>

@endif
						</div><!-- /.secContentsInner -->
					</section><!-- /.secContents-mb -->
				</div><!-- /.containerContents -->
            </div><!-- /.mainContentsInner-oneColumn -->


<script type="text/javascript">

var pre_prog ="";


function progChange(nm) {

	if (pre_prog != nm) {
		document.getElementById(nm).style.display ="block";
		if (pre_prog != "") {
			document.getElementById(pre_prog).style.display ="none";
		}
		pre_being = nm;
	}
}



$(document).ready(function(){
  $("#beingTable tr:even").not(':first').addClass("evenRow");
  $("#beingTable tr").not(':first').hover(
    function(){
        $(this).addClass("focusRow");
//		$(this)[0].cells[7].style.display ="block";
    },function(){
        $(this).removeClass("focusRow");
//	 	$(this)[0].cells[7].style.display ="none";
 });
 
});
</script>

<style>
#beingTable { cursor: pointer; }
.evenRow { background-color: #F5F5F5; }
.focusRow { background-color: #ffffcc; }

</style>


@endsection
