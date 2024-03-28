@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('interview_list') }}
@endsection


@section('addheader')
	<title>メッセージ一覧｜外資IT企業の口コミ評価・求人なら外資IT.com</title>
    <link href="{{ asset('css/message.css') }}" rel="stylesheet">
@endsection


@section('content')


@include('user.user_activity')

            <main class="pane-main">
                <div class="inner">
                    <div class="ttl">
                        <h2>@if ($intType == 0)カジュアル面談 @elseif ($intType == 1)正式応募 @elseif ($intType == 2)イベント@endif メッセージ一覧</h2>
                        <!-- <div class="button-list">
                            <button class="sort">並べ替え</button>
                        </div> -->
                    </div>
                    <div class="con-wrap">

@if (empty($interviewList[0]))
	@if ($intType == 0)現在、進行中のカジュアル面接はありません
	@elseif ($intType == 1)現在、進行中の応募はありません
	@elseif ($intType == 2)現在申し込み中のイベントはありません
	@else現在、進行中のカジュアル面接／応募または、申し込み中のベントはありません
	@endif
@else

                        <div class="item info">
                            <div class="item-inner">
                                @foreach ($interviewList as $int)
                                <div class="item-block">
                                   	<div class="ttl">
                                    	<span class="date">{{ str_replace(' ','/', str_replace('-','/', substr($int->last_update, 0 ,16))) }}</span>
                                        @if ($int->read_flag == '0')
                                        	<span class="unread">未読</span>
                                        @else
                                        	<span class="unread">　　</span>
                                        @endif
										{{ html()->form('GET', '/interview/flow')->attribute('name', 'intform' . $int->id)->open() }}
										{{ html()->hidden('interview_id', $int->id) }}
                                        <a href="javascript:intform{{ $int->id }}.submit()">{{ $int->company_name }} {{ $int->unit_name }} {{ $int->job_name }}</a>
										{{ html()->form()->close() }}
                                	</div>
                                    <p class="txt">
                                        {{ mb_strimwidth($int->last_msg, 0, 100, "...") }}
                                    </p>
                                </div>
                                
                                @endforeach
                            	<div class="pager">
                                	<ul class="page">
			                        	{{ $interviewList->appends($params)->links('pagination.user') }}
                                	</ul>
                            	</div>

                            </div>
                        </div>
@endif

                    </div>
                </div>
            </main>
		</div>

@endsection
