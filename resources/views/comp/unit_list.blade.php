@extends('layouts.comp.auth')

@section('content')

<head>
	<title>部門設定｜{{ config('app.name', 'Laravel') }}</title>
</head>

{{--@include('comp.member_activity')--}}

	<div class="mainContentsInner-oneColumn">

		<div class="mainTtl title-main">
			<h2>部門設定</h2>
		</div><!-- /.mainTtl -->
                
		<div class="containerContents">
                    
			<section class="secContents">
				<div class="secContentsInner">

					<div class="secBtnHead">
						<div class="secBtnHead-btn" style="width:95%">
							<ul class="item-btn" style="-webkit-justify-content: space-between;">
								<li class="linkList"><a href="/comp/unit/edit?unit_id=">新規登録</a></li>
								{{ html()->form('GET', '/comp/unit/list')->id('listform')->attribute('name', 'listform')->open() }}
								<li style="white-space:nowrap;"><input type="checkbox" id="only_me" name="only_me" value="1" @if ($param['only_me'] == '1') checked @endif  onchange="this.form.submit()"><label for="only_me">自分の担当のみ表示</label></li>
								{{ html()->form()->close() }}
							</ul><!-- /.item -->
						</div><!-- /.secBtnHead-btn -->
					</div><!-- /.sec-btn -->

					<p style="text-align: center;">全{{ $unitList->total() }}件中 {{  ($unitList->currentPage() -1) * $unitList->perPage() + 1}}-{{ (($unitList->currentPage() -1) * $unitList->perPage() + 1) + (count($unitList) -1)  }}件</p>
					<table class="tbl-unitlist" id="unitTable">
						<tr>
							<th>部門</th>
							<th>紹介</th>
							<th>公開</th>
							<th>担当者</th>
							<th></th>
						</tr>
						@foreach ($unitList as $unit)
							<tr>
								<td>{{ $unit['name'] }}</td>
								<td>{{ mb_strimwidth($unit->intro, 0, 70, "...") }}</td>
								<td>@if ($unit->open_flag == '1')公開@endif</td>
								<td>{{ $unit->persons }}</td>
								<td>
									<div class="btnContainer">
										{{ html()->form('GET', '/comp/unit/edit')->attribute('name', 'editform' . $unit->id)->open() }}
										{{ html()->hidden('unit_id', $unit->id) }}
										@if ( strpos($unit->person ,Auth::user()->id) !== false)
											<a href="javascript:editform{{ $unit->id }}.submit()" class="squareBtn btn-large">編集</a>
										@else
											<a href="javascript:editform{{ $unit->id }}.submit()" class="squareGrayBtn btn-large">参照</a>
										@endif
										{{ html()->form()->close() }}
									</div><!-- /.btn-container -->
								</td>
							</tr>
						@endforeach
					</table>
 
					<div class="pager">
						{{ $unitList->appends( $param)->links('pagination.comp') }}
					</div>
				</div><!-- /.secContentsInner -->
			</section><!-- /.secContents -->
                   
		</div><!-- /.containerContents -->
	</div><!-- /.mainContentsInner -->
            

<script type="text/javascript">

$(document).ready(function(){
  $("#unitTable tr:even").not(':first').addClass("evenRow");
  $("#unitTable tr").not(':first').hover(
    function(){
        $(this).addClass("focusRow");
    },function(){
        $(this).removeClass("focusRow");
 });
});

</script>


@endsection
