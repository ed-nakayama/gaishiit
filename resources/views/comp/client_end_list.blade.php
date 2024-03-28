@extends('layouts.comp.auth')

@section('content')

<head>
	<title>面談進捗管理 - 終了｜{{ config('app.name', 'Laravel') }}</title>
</head>

            <div class="mainContentsInner-oneColumn">

                <div class="secTitle">
                    <div class="title-main">
                        <h2>面談進捗管理 - 終了</h2>
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
                                    {{ Form::open(['url' => '/comp/clientend/list', 'name' => 'listform' , 'id' => 'listform', 'method'=>'GET']) }}
                                        <input type="checkbox" name="only_me" value="1" @if ($search['only_me'] == '1') checked @endif  onchange="this.form.submit()"><label>自分の担当のみ表示</label>
                                    {{ Form::close() }}
                                </div><!-- /.secBtnHead-btn -->

                            </div><!-- /.sec-btn -->

@if(!isset($endList[0]))
  <div>※データはありません。</div>
@else
                           <table class="tbl-clientend mb-ajust" id="beingTable">
                                <tr>
                                    <th>最終更新日</th>
                                    <th>氏名</th>
                                    <th>ステージ</th>
                                    <th>ステータス</th>
                                    <th>ジョブ / 部門</th>
                                    <th>メモ</th>
                                    <th>担当者</th>
                                </tr>
                               
                              @foreach ($endList as $int)
                                <tr>
                                    <td>{{ $int->updated_at->format('Y/m/d/H:i') }}</td>
                                	{{ Form::open(['url' => '/comp/user/detail', 'name' => 'userform' . $int->id ]) }}
                                	{{ Form::hidden('user_id', $int->user_id) }}
	                                {{ Form::hidden('parent_id', '4') }}
                                	{{ Form::close() }}
                                    <td><a href="javascript:userform{{ $int->id }}.submit()" style="text-decoration: underline;">{{ $int->user_name }}</a></td>
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
