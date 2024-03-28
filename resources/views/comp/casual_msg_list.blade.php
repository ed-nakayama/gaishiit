@extends('layouts.comp.auth')

@section('content')

<head>
	<title>メッセージ一覧｜{{ config('app.name', 'Laravel') }}</title>
</head>

{{--@include('comp.member_activity')--}}

            <div class="mainContentsInner">

            <div class="mainContentsInner">
                <div class="mainTtl title-main">
                    <h2>メッセージ一覧</h2>
                </div><!-- /.mainTtl -->
                
                <div class="containerContents">

                    <section class="secContents-mb">

                        <div class="tab_box_no">
                            <div class="btn_area">
                                <p class="tab_btn active"><a href="/comp/msg/casual/list">カジュアル面談</a></p>
                                <p class="tab_btn"><a href="/comp/msg/formal/list">正式応募</a></p>
                                <p class="tab_btn"><a href="/comp/msg/event/list">イベント</a></p>
                            </div>
                            <div class="panel_area">
								<p style="text-align: center;">全{{ $interviewList->total() }}件中 {{  ($interviewList->currentPage() -1) * $interviewList->perPage() + 1}}-{{ (($interviewList->currentPage() -1) * $interviewList->perPage() + 1) + (count($interviewList) -1)  }}件</p>
<br>
                                <table class="messageBoxTbl" id="msgTable">
                                  @foreach ($interviewList as $int)
                                    <tr>
                                        <th>{{ str_replace(' ','/', str_replace('-','/', substr($int->last_update, 0 ,16))) }}</th>
                                        <th>
                                        	@if ($int->read_flag == '0')
                                        		<span class="unread">未読</span>
                                        	@endif
                                        </th>
                                        <th class="receiveName">
                                            {{ Form::open(['url' => '/comp/interview/flow', 'name' => 'intform'. $int->id , 'id' => 'intform' ,'method' => 'GET' ]) }}
                                            {{ Form::hidden('interview_id', $int->id, ['class' => 'form-control', 'id'=>'interview_id' ]) }}
                                            {{ Form::close() }}
                                            <a href="javascript:intform{{ $int->id }}.submit()">
                   							@if ($int->aprove_flag == '1')
                                            	{{  $int->user_name }}
                                			@else
                                            	{{  $int->user_nick_name }}
                                			@endif
                                            </a>
                                        </th>
                                        <th>
                                        @if ($int->interview_kind == '1')
                                            {{  $int->unit_name }}
                                        @elseif  ($int->interview_kind == '2')
                                            {{  $int->job_name }}
                                        @else
                                            {{  $int->company_name }}
                                        @endif
                                        </th>
                                        <th>@if ($int->aprove_flag == '1')承認@elseif ($int->aprove_flag == '2')否認@endif</th>
                                    </tr>
                                    <tr>
                                        <td class="sendName">From：{{  $int->last_sender }}</td>
                                        <td colspan="3">{{ mb_strimwidth($int->last_msg, 0, 90, "...") }}</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                  @endforeach
                                </table>
                            </div><!-- /.panel_area -->
                        </div><!-- /.tab_box_no -->

                    </section><!-- /.secContents -->

                    <div class="pager">
                         {{ $interviewList->links('pagination.comp') }}
                    </div><!-- /.pager -->
                    
                </div><!-- /.containerContents -->

            </div><!-- /.mainContentsInner -->
            
        </div><!-- /.mainContents -->


<style>
#msgTable { cursor: pointer; }
.evenRow { background-color: #F5F5F5; }
.focusRow { background-color: #ffffcc; }

</style>
@endsection
