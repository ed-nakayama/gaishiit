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
							{{ Form::open(['url' => '/comp/unit/list', 'name' => 'listform' , 'id' => 'listform', 'method'=>'GET']) }}
							<ul class="item-btn" style="-webkit-justify-content: space-between;">
								<li></li>
								<li style="white-space:nowrap;"><input type="checkbox" name="only_me" value="1" @if ($param['only_me'] == '1') checked @endif  onchange="this.form.submit()"><label>自分の担当のみ表示</label></li>
							</ul><!-- /.item -->
							{{ Form::close() }}
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
								<td>{{ $unit->person_name }}</td>
								<td>
									<div class="btnContainer">
										{{ Form::open(['url' => '/comp/unit/edit', 'name' => 'editform' . $unit->id , 'method'=>'GET' ]) }}
										{{ Form::hidden('unit_id', $unit->id) }}
										@if ( strpos($unit->person ,Auth::user()->id) !== false)
											<a href="javascript:editform{{ $unit->id }}.submit()" class="squareBtn btn-large">編集</a>
										@else
											<a href="javascript:editform{{ $unit->id }}.submit()" class="squareGrayBtn btn-large">参照</a>
										@endif
										{{ Form::close() }}
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

<style>
#unitTable { cursor: pointer; }
.evenRow { background-color: #F5F5F5; }
.focusRow { background-color: #ffffcc; }
</style>

@endsection
