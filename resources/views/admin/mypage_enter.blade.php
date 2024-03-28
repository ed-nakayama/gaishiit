@extends('layouts.admin.auth')

@section('content')

<head>
	<title>マイページ｜{{ config('app.name', 'Laravel') }}</title>
</head>

<div class="mainContentsInner-oneColumn">

	<div style="display:flex;justify-content: space-between;">
		<div class="mainTtl title-main">
			<h2>マイページ</h2>
		</div><!-- /.mainTtl -->
		<div style="text-align: right;margin-bottom: 10px;"><a href="javascript:void(0);" onClick="openWin({{ Auth::id() }})" class="squareBtn" style="width: 140px;height: 30px;padding: 5px 0;">企業代理ログイン</a></div>
 	</div>
               
	<div class="containerContents">

		<section class="secContents-mb">
                    
			<div class="tab_box_no">
				<div class="btn_area">
					<p class="tab_btn"><a href="/admin/mypage">候補者承認</a></p>
					<p class="tab_btn"><a href="/admin/mypage/progress">面談進捗管理</a></p>
					<p class="tab_btn active"><a href="/admin/mypage/enter">採用者一覧</a></p>
					<p class="tab_btn"><a href="/admin/mypage/joblist">ジョブ一覧</a></p>
					<p class="tab_btn"><a href="/admin/mypage/jobsfc">CSVダウンロード</a></p>
					<p class="tab_btn"><a href="/admin/mypage/eval">クチコミ一覧</a></p>
				</div>

				<div class="secContentsInner">

@if(!isset($enterList[0]))
					<div>※データはありません。</div>
@else
					<table class="tbl-enter" id="beingTable">
						<tr>
							<th>最終更新日1</th>
							<th>氏名</th>
							<th>企業名</th>
							<th>入社日</th>
							<th>ステージ</th>
							<th>ステータス</th>
							<th>ジョブ / 部門</th>
							<th>メモ</th>
							<th>担当者</th>
							<th></th>
						</tr>
                               
						@foreach ($enterList as $int)
							<tr>
								{{ html()->form('POST', '/admin/user/detail')->attribute('name', 'userform'. $int->id)->open() }}
								{{ html()->hidden('user_id', $int->user_id) }}
								{{ html()->hidden('parent_id', '5') }}
								{{ html()->form()->close() }}

								{{ html()->form('POST', '/admin/mypage/enter/list')->attribute('name', 'enterform'. $int->id)->open() }}
								{{ html()->hidden('interview_id', $int->id) }}
								<td>{{ $int->updated_at->format('Y/m/d/H:i') }}</td>
								<td>
 									<a href="javascript:userform{{ $int->id }}.submit()" style="text-decoration: underline;">{{ $int->user_name }}</a>
								</td>
								<td>{{ $int->company_name }}</td>
								<td>
									<label style="padding: 5px 5px;border: 1px solid #ccc;">
										<input type="date" name="entrance_date" value="{{ $int->entrance_date }}"  oninput="enterChange('{{ 'entersave' . $int->id }}')">
									</label>
								</td>
								<td align="center">
									@if ($int->interview_type == '0')
										カジュアル面談
									@else
										{{ $int->stage_name }}
									@endif
								</td>
								<td align="center">{{ $int->status_name }}</td>
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
									<div class="btnContainer"  style="display: none;" id="{{ 'entersave' . $int->id }}">
										<a href="javascript:enterform{{ $int->id }}.submit()" class="squareBtn btn-medium">保存</a>
									</div><!-- /.btn-container -->
								</td>
								{{ html()->form()->close() }}
							</tr>
						@endforeach

					</table>

					<div class="pager">
						{{ $enterList->links('pagination.admin') }}
					</div>

@endif
				</div><!-- /.secContentsInner -->
			</div><!-- /.tab_box_no -->
		</section><!-- /.secContents-mb -->
	</div><!-- /.containerContents -->
</div><!-- /.mainContentsInner-oneColumn -->


<script type="text/javascript">

var pre_enter ="";


function enterChange(nm) {

	if (pre_enter != nm) {
		document.getElementById(nm).style.display ="block";
		if (pre_enter != "") {
			document.getElementById(pre_enter).style.display ="none";
		}
		pre_enter = nm;
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
