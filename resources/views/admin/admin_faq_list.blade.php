@extends('layouts.admin.auth')

@section('content')

<head>
	<title>FAQ一覧｜{{ config('app.name', 'Laravel') }}</title>
</head>

	<div class="mainContentsInner-oneColumn">

		<div class="mainTtl title-main">
			<h2>FAQ一覧</h2>
		</div><!-- /.mainTtl -->
                
		<div class="containerContents">
                    
			<section class="secContents">
				<div class="secContentsInner">

					<div class="secBtnHead">
						<div class="secBtnHead-btn">
							<ul class="item-btn">
								<li><a href="/admin/faq" class="squareBtn">新規作成</a></li>
							</ul><!-- /.item -->
						</div><!-- /.secBtnHead-btn -->
					</div><!-- /.sec-btn -->

					<table class="tbl-faqList mb-ajust">
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
									{{ html()->form('GET', '/admin/faq')->attribute('name', "editform{$faq->id}")->open() }}
									{{ html()->hidden('faq_id', $faq->id) }}
									<a href="javascript:editform{{ $faq->id }}.submit()" class="squareBtn btn-large">編集</a>
									{{ html()->form()->close() }}
								</div><!-- /.btn-container -->
							</td>
						</tr>
						@endforeach
					</table>
 
				</div><!-- /.secContentsInner -->
			</section><!-- /.secContents -->
                   
		</div><!-- /.containerContents -->
	</div><!-- /.mainContentsInner -->
            

@endsection
