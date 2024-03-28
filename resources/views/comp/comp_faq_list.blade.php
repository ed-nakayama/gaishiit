@extends('layouts.comp.auth')

@section('content')

<head>
	<title>FAQ一覧｜{{ config('app.name', 'Laravel') }}</title>
</head>

{{--@include('comp.member_activity')--}}

            <div class="mainContentsInner">

				<div class="mainTtl title-main">
					<h2>FAQ一覧</h2>
				</div><!-- /.mainTtl -->
                
				<div class="containerContents">
                    
					<section class="secContents">
						<div class="secContentsInner">

							<div class="secBtnHead">                                
                                <div class="secBtnHead-btn">
                                    <ul class="item-btn">
                                        <li><a href="/comp/faq" class="squareBtn">新規作成</a></li>
                                    </ul><!-- /.item -->
                                </div><!-- /.secBtnHead-btn -->
							</div><!-- /.sec-btn -->

							<table class="tbl-faqList" id="unitTable">
								<tr>
									<th>質問</th>
									<th>回答</th>
									<th>公開</th>
									<th></th>
								</tr>
								@foreach ($faqList as $faq)
								<tr>
									<td>{{ mb_strimwidth( $faq->question, 0, 50, "...") }}</td>
									<td>{{ mb_strimwidth( $faq->answer, 0, 50, "...") }}</td>
									<td>@if ($faq->open_flag == '1')公開@endif</td>
									<td>
										<div class="btnContainer">
											{{ Form::open(['url' => '/comp/faq', 'name' => 'editform' . $faq->id , 'method'=>'GET' ]) }}
											{{ Form::hidden('faq_id', $faq->id) }}
											<a href="javascript:editform{{ $faq->id }}.submit()" class="squareBtn btn-large">編集</a>
											{{ Form::close() }}
										</div><!-- /.btn-container -->
									</td>
								</tr>
								@endforeach
							</table>
 
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
