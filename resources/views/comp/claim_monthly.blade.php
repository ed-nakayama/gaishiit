@extends('layouts.comp.admin')

@section('content')

<head>
	<title>請求管理｜{{ config('app.name', 'Laravel') }}</title>
</head>

            <div class="mainContentsInner-oneColumn">
                <div class="mainTtl title-main">
                    <h2>請求管理</h2>
                </div><!-- /.mainTtl -->
                
                <div class="containerContents">

                    <section class="secContents">

                        <div class="tab_box_no2">
                            <div class="btn_area">
                                <p class="tab_btn"><a href="/comp/claim/every">都度</a></p>
                                <p class="tab_btn active"><a href="/comp/claim/monthly">年間／月間</a></p>
                            </div>
                            <div class="panel_area">
{{-- 年間／月間 --}}
								<table class="tbl-claim-7th">
									<tr>
										<th>年/月</th>
										<th>{{ substr($title[0] ,0 ,7) }}</th>
										<th>{{ substr($title[1] ,0 ,7) }}</th>
										<th>{{ substr($title[2] ,0 ,7) }}</th>
										<th>{{ substr($title[3] ,0 ,7) }}</th>
										<th>{{ substr($title[4] ,0 ,7) }}</th>
										<th>{{ substr($title[5] ,0 ,7) }}</th>
										<th>{{ substr($title[6] ,0 ,7) }}</th>
									</tr>
									<tr>
										<td align="center">金額（円）</td>
										<td align="center">{{ number_format( $comp->mon0 ) }}</td>
										<td align="center">{{ number_format( $comp->mon1 ) }}</td>
										<td align="center">{{ number_format( $comp->mon2 ) }}</td>
										<td align="center">{{ number_format( $comp->mon3 ) }}</td>
										<td align="center">{{ number_format( $comp->mon4 ) }}</td>
										<td align="center">{{ number_format( $comp->mon5 ) }}</td>
										<td align="center">{{ number_format( $comp->mon6 ) }}</td>
									</tr>
								</table>
{{-- END 年間／月間 --}}

                            </div>
                        </div>

                    </section><!-- /.secContents -->
                    
                </div><!-- /.containerContents -->

            </div><!-- /.mainContentsInner -->
            
        </div><!-- /.mainContents -->



<script>

var pre_mon ="";

function monChange(nm) {

	if (pre_mon != nm) {
		document.getElementById(nm).style.display ="block";
		if (pre_member  != "") {
			document.getElementById(pre_mon).style.display ="none";
		}
		pre_mon = nm;
	}
}
</script>

@endsection
