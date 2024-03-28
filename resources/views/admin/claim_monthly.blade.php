@extends('layouts.admin.auth')

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
                                <p class="tab_btn"><a href="/admin/claim/every">都度</a></p>
                                <p class="tab_btn active"><a href="/admin/claim/monthly">年間／月間</a></p>
                            </div>
                            <div class="panel_area">
{{-- 年間／月間 --}}
								<table class="tbl-claim-13th">
									<tr>
										<th>企業名</th>
										<th>{{ substr($title[0] ,5 ,2) }}月</th>
										<th>{{ substr($title[1] ,5 ,2) }}月</th>
										<th>{{ substr($title[2] ,5 ,2) }}月</th>
										<th>{{ substr($title[3] ,5 ,2) }}月</th>
										<th>{{ substr($title[4] ,5 ,2) }}月</th>
										<th>{{ substr($title[5] ,5 ,2) }}月</th>
										<th>{{ substr($title[6] ,5 ,2) }}月</th>
										<th></th>
									</tr>
									<tr>
										<th align="center">合計</th>
										<th align="right">{{ number_format($total['mon0']) }}</th>
										<th align="right">{{ number_format($total['mon1']) }}</th>
										<th align="right">{{ number_format($total['mon2']) }}</th>
										<th align="right">{{ number_format($total['mon3']) }}</th>
										<th align="right">{{ number_format($total['mon4']) }}</th>
										<th align="right">{{ number_format($total['mon5']) }}</th>
										<th align="right">{{ number_format($total['mon6']) }}</th>
										<th></th>
									</tr>
									@foreach ($compList as $comp)
										<tr>
											{{ Form::open(['url' => '/admin/claim/monthly/store', 'name' => 'monform' . $comp->id ]) }}
											{{ Form::hidden('company_id', $comp->id) }}
											{{ Form::hidden('start_date', $start_date) }}
											<td>{{ $comp->name }}</td>
											<td><input type="text" name="mon[0]" value="{{ $comp->mon0 }}" onchange="monChange('{{ 'monsave' .   $comp->id }}')"></td>
											<td><input type="text" name="mon[1]" value="{{ $comp->mon1 }}" onchange="monChange('{{ 'monsave' .   $comp->id }}')"></td>
											<td><input type="text" name="mon[2]" value="{{ $comp->mon2 }}" onchange="monChange('{{ 'monsave' .   $comp->id }}')"></td>
											<td><input type="text" name="mon[3]" value="{{ $comp->mon3 }}" onchange="monChange('{{ 'monsave' .   $comp->id }}')"></td>
											<td><input type="text" name="mon[4]" value="{{ $comp->mon4 }}" onchange="monChange('{{ 'monsave' .   $comp->id }}')"></td>
											<td><input type="text" name="mon[5]" value="{{ $comp->mon5 }}" onchange="monChange('{{ 'monsave' .   $comp->id }}')"></td>
											<td><input type="text" name="mon[6]" value="{{ $comp->mon6 }}" onchange="monChange('{{ 'monsave' .   $comp->id }}')"></td>
											<td>
												<div class="btnContainer"  style="display: none;" id="{{ 'monsave' . $comp->id }}">
													<a href="javascript:monform{{ $comp->id }}.submit();" class="squareBtn btn-medium">保存</a>
												</div><!-- /.btn-container -->
											</td>
											{{ html()->form()->close() }}
										</tr>
									@endforeach
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
