@extends('layouts.comp.auth')

@section('content')

<head>
	<title>イベント管理｜{{ config('app.name', 'Laravel') }}</title>
</head>

{{--@include('comp.member_activity')--}}

	<div class="mainContentsInner-oneColumn">

                <div class="mainTtl title-main">
                    <h2>イベント管理</h2>
                </div><!-- /.mainTtl -->
                
                <div class="containerContents">
                    
                    <section class="secContents">
                        <div class="secContentsInner">

                            <div class="secBtnHead">
                                
                                <div class="secBtnHead-btn">
                                    <ul class="item-btn">
                                        <li><a href="/comp/event/register" class="squareBtn">新規作成</a></li>
                                    </ul><!-- /.item -->
                                </div><!-- /.secBtnHead-btn -->
                                <div class="secBtnHead-check">
                                    {{ Form::open(['url' => '/comp/event/list', 'name' => 'listform' , 'id' => 'listform', 'method'=>'GET']) }}
                                        <input type="checkbox" name="only_me" value="1" @if ($param['only_me'] == '1') checked @endif  onchange="this.form.submit()"><label>自分の担当のみ表示</label>
                                    {{ Form::close() }}
                                </div><!-- /.secBtnHead-btn -->

                            </div><!-- /.sec-btn -->

							<p style="text-align: center;">全{{ $eventList->total() }}件中 {{  ($eventList->currentPage() -1) * $eventList->perPage() + 1}}-{{ (($eventList->currentPage() -1) * $eventList->perPage() + 1) + (count($eventList) -1)  }}件</p>
                            <table class="tbl-eventlist mb-ajust" id="eventTable">
                                <tr>
                                    <th>作成日</th>
                                    <th>イベント名称</th>
                                    <th>部門</th>
									<th>担当者</th>
                                    <th></th>
                                </tr>
                                @foreach ($eventList as $event)
                                <tr>
                                    <td>{{ $event->created_at->format('Y/m/d/H:i') }}</td>
                                    <td>{{ $event->name }}</td>
                                    <td>{{ $event->unit_name }}</td>
									<td>{{ $event->person_name }}</td>
                                    <td>
                                        <div class="btnContainer">
                                        {{ Form::open(['url' => '/comp/event/detail', 'name' => 'detailform' . $event->id , 'method'=>'GET' ]) }}
                                        {{ Form::hidden('event_id', $event->id) }}
                                        @if (strpos($event->person ,Auth::user()->id) !== false)
                                            <a href="javascript:detailform{{ $event->id }}.submit()" class="squareBtn btn-large">詳細</a>
                                        @else
                                            <a href="javascript:detailform{{ $event->id }}.submit()" class="squareGrayBtn btn-large">参照</a>
                                        @endif
                                       {{ Form::close() }}
                                        </div><!-- /.btn-container -->
                                    </td>
                                </tr>
                                @endforeach
                            </table>
 
                            <div class="pager">
                               {{ $eventList->appends( $param)->links('pagination.comp') }}
                            </div>
                        </div><!-- /.secContentsInner -->
                    </section><!-- /.secContents -->
                   
                </div><!-- /.containerContents -->

            </div><!-- /.mainContentsInner -->


<script type="text/javascript">


$(document).ready(function(){
  $("#eventTable tr:even").not(':first').addClass("evenRow");
  $("#eventTable tr").not(':first').hover(
    function(){
        $(this).addClass("focusRow");
    },function(){
        $(this).removeClass("focusRow");
 });
});

</script>

<style>
#eventTable { cursor: pointer; }
.evenRow { background-color: #F5F5F5; }
.focusRow { background-color: #ffffcc; }
</style>

@endsection
