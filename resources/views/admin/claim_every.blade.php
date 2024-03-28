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
                                <p class="tab_btn active"><a href="/admin/claim/every">都度</a></p>
                                <p class="tab_btn"><a href="/admin/claim/monthly">年間／月間</a></p>
                            </div>
                            <div class="panel_area">
{{-- 都度 --}}
								<table class="tbl-claim-8th">
									<tr>
										<th>処理済</th>
										<th>企業名</th>
										<th>入社日</th>
										<th>氏名</th>
										<th>ジョブ</th>
										<th>ジョブID</th>
										<th>内容</th>
										<th>請求金額(円)</th>
										<th></th>
									</tr>
									@if(!isset($intList[0]))
										<tr>
											<td colspan="3"><di>※データはありません。</di></td>
										</tr>
									@else
										@foreach ($intList as $int)
										<tr>
											{{ Form::open(['url' => '/admin/claim/every/store', 'name' => 'editform' . $int->id ]) }}
											{{ Form::hidden('interview_id', $int->id) }}
											<td align="center"><input type="checkbox" name="complete_flag" value="1" @if ($int->complete_flag == '1') checked="checked" @endif  onchange="contChange('{{ 'contsave' . $int->id }}')"></td>
											<td>{{ $int->company_name }}</td>
											<td>{{ str_replace('-','/', substr($int->entrance_date, 0 ,10)) }}</td>
											<td>{{ $int->user_name }}</td>
											<td>{{ $int->job_name }}</td>
											<td>{{ $int->job_id }}</td>
											<td><input type="text" name="job_note" value="{{ $int->job_note }}" onchange="contChange('{{ 'contsave' . $int->id }}')"></td>
											<td><input type="text" name="amount" value="{{ $int->amount }}" onchange="contChange('{{ 'contsave' . $int->id }}')"></td>
											<td>
												<div class="btnContainer"  style="display: none;" id="{{ 'contsave' . $int->id }}">
													<a href="javascript:editform{{ $int->id }}.submit()" class="squareBtn btn-large">保存</a>
												</div><!-- /.btn-container -->
											</td>
											{{ html()->form()->close() }}
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
