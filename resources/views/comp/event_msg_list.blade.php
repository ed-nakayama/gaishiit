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
							<p class="tab_btn msgMenu__tab"><a href="/comp/msg/casual/list">カジュアル面談</a><span class="msgMenu__badge">{{ $member_act['user_casual_cnt'] }}</span></p>
							<p class="tab_btn msgMenu__tab"><a href="/comp/msg/formal/list">正式応募</a><span class="msgMenu__badge">{{ $member_act['user_formal_cnt'] }}</span></p>
							<p class="tab_btn msgMenu__tab active"><a href="/comp/msg/event/list">イベント</a><span class="msgMenu__badge">{{ $member_act['event_cnt'] }}</span></p>
							<p class="tab_btn"><a href="/comp/mypage/main">保存した条件の候補者</a></p>
							<p class="tab_btn"><a href="/comp/mypage/newuser">新しい候補者を探す</a></p>
							<p class="tab_btn"><a href="/comp/mypage/progress">面談進捗管理</a></p>
						</div>
						<div class="panel_area">
							<p style="text-align: center;">全{{ $eventList->total() }}件中 @if ($eventList->total() == 0)0 @else{{  ($eventList->currentPage() -1) * $eventList->perPage() + 1}}@endif-{{ (($eventList->currentPage() -1) * $eventList->perPage() + 1) + (count($eventList) -1)  }}件</p>
							<br>
							<table class="messageBoxTbl">
								@foreach ($eventList as $interview)
									<tr>
										<th>{{ str_replace(' ','/', str_replace('-','/', substr($interview->last_update, 0 ,16))) }}　　　No.{{$interview->id }}</th>
										<th>
											@if (!empty($interview->status) && $interview->status->read_flag == '0')
												<span class="unread">未読</span>
											@endif
										</th>
										<th class="receiveName">
											<a href="/comp/interview/flow?interview_id={{ $interview->id }}">@if ($interview->aprove_flag == '1'){{  $interview->user->name }}@else{{  $interview->user->nick_name }}@endif</a>
										<th>
											@if ($interview->event_kind == '1')
												{{ $interview->unit->name }}
											@else
												{{ $interview->company->name }}
											@endif
										</th>
										<th>@if ($interview->aprove_flag == '1')承認@elseif ($interview->aprove_flag == '2')否認@endif</th>
									</tr>
									<tr>
										<td class="sendName" style="display:flex;">From：{{ $interview->last_sender }}</td>
										<td colspan="3">{{ mb_strimwidth($interview->last_msg, 0, 90, "...") }}</td>
										<td>&nbsp;</td>
									</tr>
								@endforeach
							</table>
						</div><!-- /.panel_area -->
					</div><!-- /.tab_box_no -->

				</section><!-- /.secContents -->

				<div class="pager">
					{{ $eventList->links('pagination.comp') }}
				</div><!-- /.pager -->
                    
			</div><!-- /.containerContents -->
		</div><!-- /.mainContentsInner -->
	</div><!-- /.mainContents -->

@endsection
