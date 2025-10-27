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
					<div class="secBtnHead-check">
						{{ html()->form('GET', '/comp/clientend/list')->id('listform')->attribute('name', 'listform')->open() }}
						 <input type="checkbox" id="only_me" name="only_me" value="1" @if ($search['only_me'] == '1') checked @endif  onchange="this.form.submit()"><label for="only_me">自分の担当のみ表示</label>
						{{ html()->form()->close() }}
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

								{{ html()->form('POST', '/comp/user/detail')->attribute('name', 'userform' . $int->id)->open() }}
								{{ html()->hidden('user_id', $int->user_id) }}
								{{ html()->hidden('parent_id', '4') }}
								{{ html()->form()->close() }}

							<td><a href="javascript:userform{{ $int->id }}.submit()" style="text-decoration: underline;">{{ $int->user->name }}</a></td>
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
 
{{--
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
--}}
@endsection
