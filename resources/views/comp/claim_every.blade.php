@extends('layouts.comp.admin')

@section('content')

<head>
	<title>請求管理｜{{ config('app.name', 'Laravel') }}</title>
</head>


            <div class="mainContentsInner-oneColumn">
                <div class="mainTtl title-main">
                    <h2>請求管理1</h2>
                </div><!-- /.mainTtl -->
                
                <div class="containerContents">

                    <section class="secContents">

                        <div class="tab_box_no2">
                            <div class="btn_area">
                                <p class="tab_btn active"><a href="/comp/claim/every">都度</a></p>
                                <p class="tab_btn"><a href="/comp/claim/monthly">年間／月間</a></p>
                            </div>
                            <div class="panel_area">
{{-- 都度 --}}
								<table class="tbl-claim-every">
									<tr>
										<th>入社日</th>
										<th>氏名</th>
										<th>ジョブ</th>
										<th>ジョブID</th>
										<th>内容</th>
										<th>請求金額</th>
									</tr>
									@if(!isset($intList[0]))
										<tr>
											<td colspan="3"><di>※データはありません。</di></td>
										</tr>
									@else
										@foreach ($intList as $int)
										<tr>
											<td>{{ str_replace('-','/', substr($int->entrance_date, 0 ,10)) }}</td>
											<td>{{ $int->user_name }}</td>
											<td>{{ $int->job_name }}</td>
											<td>{{ $int->job_id }}</td>
											<td>{{ $int->job_note }}</td>
											<td align="right">{{  number_format($int->amount) }} 円</td>
										</tr>
										@endforeach
									@endif
								</table>
{{-- END 都度 --}}
                            </div>
                        </div>

                    </section><!-- /.secContents -->
                    
                </div><!-- /.containerContents -->

            </div><!-- /.mainContentsInner -->
            
        </div><!-- /.mainContents -->

<script type="text/javascript">

var pre_cont ="";

function contChange(nm) {

	if (pre_cont != nm) {
		document.getElementById(nm).style.display ="block";
		if (pre_cont != "") {
			document.getElementById(pre_cont).style.display ="none";
		}
		pre_cont = nm;
	}
}

</script>

@endsection
