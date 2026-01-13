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
					<div class="secBtnHead-check">
						{{ html()->form('GET', '/comp/client/enter/list')->id('listform')->attribute('name', 'listform')->open() }}
						<input type="checkbox" id="only_me" name="only_me" value="1" @if ($search['only_me'] == '1') checked @endif  onchange="this.form.submit()"><label for="only_me">自分の担当のみ表示</label>
						{{ html()->form()->close() }}
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
						<th>ジョブ</th>
						<th>メモ</th>
						<th>担当者</th>
						<th></th>
					</tr>

					@foreach ($endList as $int)
						<tr>
							{{ html()->form('POST', '/comp/user/detail')->attribute('name', 'userform' . $int->id)->open() }}
							{{ html()->hidden('user_id', $int->user_id) }}
							{{ html()->hidden('parent_id', '3') }}
							{{ html()->form()->close() }}

							{{ html()->form('POST', '/comp/client/enter/save')->attribute('name', 'progform' . $int->id)->open() }}
							{{ html()->hidden('interview_id', $int->id) }}

							<td>{{ $int->updated_at->format('Y/m/d/H:i') }}</td>
							<td><a href="javascript:userform{{ $int->id }}.submit()" style="text-decoration: underline;">{{ $int->user->name }}{{ $int->user->name2 }}</a></td>
							<td>
								<label style="padding: 5px 5px;border: 1px solid #ccc;"><input type="date" name="entrance_date" value="{{ $int->entrance_date }}"  oninput="progChange('{{ 'progsave' . $int->id }}')"></label>
							</td>
							<td>
								@if ($int->interview_type == '0')
									カジュアル面談
								@else
									{{ $int->stage->name }}
								@endif
							</td>
							<td>{{ $int->status->name }}</td>
							<td>
								@if ( ($int->interview_type == '0') && ($int->interview_kind == '0') )
								@elseif ( ($int->interview_type == '0') && ($int->interview_kind == '1') )
									{{ $int->unit->name }}
								@else
									{{ $int->job->name }}
								@endif
							</td>
							<td>{{ $int->comment }}</td>
							<td>{{ $int->person }}</td>
							<td>
								<div class="btnContainer"  style="display: none;" id="{{ 'progsave' . $int->id }}">
									<a href="javascript:progform{{ $int->id }}.submit()" class="squareBtn btn-medium">保存</a>
								</div><!-- /.btn-container -->
							</td>
							{{ html()->form()->close() }}
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
