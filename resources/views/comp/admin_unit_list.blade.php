@extends('layouts.comp.admin')

@section('content')

<head>
	<title>部門設定 - 部門一覧｜{{ config('app.name', 'Laravel') }}</title>
</head>


            <div class="mainContentsInner-oneColumn">


                <div class="secTitle">
                    <div class="title-main">
                    <h2>部門設定 - 部門一覧</h2>
                    </div><!-- /.mainTtl -->
                </div><!-- /.sec-title -->

                
                <div class="containerContents">
                    
                    <section class="secContents">
                        <div class="secContentsInner">

                            <div class="secBtnHead">
                                
                                <div class="secBtnHead-btn">
                                    <ul class="item-btn">
                                        <li><a href="/comp/admin/unit/register" class="squareBtn">新規作成</a></li>
                                    </ul><!-- /.item -->
                                </div><!-- /.secBtnHead-btn -->
                            </div><!-- /.sec-btn -->

                            <table class="tbl-2th" id="unitTable">
                                <tr>
                                    <th>部門</th>
                                    <th></th>
                                </tr>
                                @foreach ($unitList as $unit)
                                <tr>
                                     <td>{{ $unit['name'] }}</td>
                                    <td>
                                        <div class="btnContainer">
                                        {{ Form::open(['url' => '/comp/admin/unit/edit', 'name' => 'editform' . $unit->id ,'method'=>'GET' ]) }}
                                        {{ Form::hidden('unit_id', $unit->id) }}
                                            <a href="javascript:editform{{ $unit->id }}.submit()" class="squareBtn btn-large">編集</a>
                                       {{ Form::close() }}
                                        </div><!-- /.btn-container -->
                                    </td>
                                </tr>
                                @endforeach
                            </table>
 
                            <div class="pager">
                               {{ $unitList->links('pagination.comp') }}
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
