@extends('layouts.admin.auth')
<head>
    <title>お知らせ管理 | {{ config('app.name', 'Laravel') }}</title>
</head>

@section('content')

	<div class="mainContentsInner-oneColumn">

		<div class="mainTtl title-main">
			<h2>お知らせ管理</h2>
		</div><!-- /.sec-title -->

		<div class="containerContents">

			<section class="secContents-mb">
				<div class="secContentsInner">

					<div class="secBtnHead">
						<div class="secBtnHead-btn">
							<ul class="item-btn">
								<li><a href="/admin/info" class="squareBtn">新規作成</a></li>
							</ul><!-- /.item -->
						</div><!-- /.secBtnHead-btn -->
					</div>

@if(!isset($infoList[0]))
  <div>※データはありません。</div>
@else
					<p style="text-align: center;">全{{ $infoList->total() }}件中 {{  ($infoList->currentPage() -1) * $infoList->perPage() + 1}}-{{ (($infoList->currentPage() -1) * $infoList->perPage() + 1) + (count($infoList) -1)  }}件</p>
					<table class="tbl-infoList-7th" id="infoTable">
						<tr>
							<th>更新日</th>
							<th>メッセージ</th>
							<th>公開期限</th>
							<th>公開範囲</th>
							<th>公開</th>
							<th>ヘッダ</th>
							<th></th>
						</tr>
                               
						@foreach ($infoList as $info)
							<tr>
								<td>{{ str_replace(' ','/', str_replace('-','/', substr($info->updated_at, 0 ,16))) }}</td>
								<td>{{ $info->content }}</td>
								<td>{{ str_replace('-','/', substr($info->open_limit, 0 ,10)) }}</td>
								<td>@if ($info->open_type == '1')ユーザのみ@elseif ($info->open_type == '2')企業のみ@elseすべて@endif</td>
								<td>@if ($info->open_flag == '1')公開中@else未公開@endif</td>
								<td>@if ($info->header_flag == '1')表示@else非表示@endif</td>
								<td>
									<div class="btnContainer">
										{{ Form::open(['url' => '/admin/info', 'name' => 'editform' . $info->id   ,'method' => 'GET' ]) }}
										{{ Form::hidden('info_id', $info->id) }}
										<a href="javascript:editform{{ $info->id }}.submit()" class="squareBtn btn-medium">編集</a>
										{{ Form::close() }}
									</div><!-- /.btn-container -->
								</td>
							</tr>
						@endforeach

					</table>
					<div class="pager">
						{{ $infoList->links('pagination.admin') }}
					</div>
@endif

				</div><!-- /.secContentsInner -->
			</section><!-- /.secContents-mb -->
		</div><!-- /.containerContents -->
	</div><!-- /.mainContentsInner-oneColumn -->



<script type="text/javascript">



$(document).ready(function(){
  $("#infoTable tr:even").not(':first').addClass("evenRow");
  $("#infoTable tr").not(':first').hover(
    function(){
        $(this).addClass("focusRow");
    },function(){
        $(this).removeClass("focusRow");
 });
 

});
</script>

<style>
#infoTable { cursor: pointer; }
.evenRow { background-color: #F5F5F5; }
.focusRow { background-color: #ffffcc; }

</style>
@endsection
