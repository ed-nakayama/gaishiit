@extends('layouts.admin.auth')
<head>
    <title>請求情報 - 請求 | {{ config('app.name', 'Laravel') }}</title>
</head>

@section('content')

            <di class="mainContentsInner-oneColumn">

                <di class="containerContents">

                    <div class="companyTtl title-company">
                        <h2>{{ $comp->name }}</h2>
                    </div><!-- /.mainTtl -->
                    

                    <div class="mainTtl title-main">
                        <h2>企業管理 - 請求</h2>
                    </div><!-- /.mainTtl -->

                    <section class="secContents-mb">
                        <div class="secContentsInner">

                            <h2 class="contentsTitle">現在の設定：@if ($bill_type  == '0')都度払い@elseif ($bill_type  == '1')月払い@elseif ($bill_type  == '2')年払い@endif</h2>


                            <div class="secContentsContainer">
                                <ul class="settingListItem">
                                    <li>手数料についてのメモ：{{ $comp->fee_memo }}</li>
                                    <li>支払い担当：{{ $comp->cost_person_name }}</li>
                                    <li>メールアドレス：{{ $comp->cost_person_mail }}</li>
                                </ul><!-- /.listItem -->
                            </div><!-- /.secContentsContainer -->
                        </div><!-- /.secContentsInner -->
                    </section><!-- /.secContents-mb -->


                    <section class="secContents">
                        <div class="secContentsInner">

                            <h2 class="contentsTitle">未処理の請求</h2>

                            <table class="tbl-8th-Claim mb-ajust">
								<tr>
									<th>処理済</th>
									<th>入社日</th>
									<th>氏名</th>
									<th>ジョブ</th>
									<th>ジョブID</th>
									<th>内容</th>
									<th>請求金額</th>
									<th></th>
								</tr>
							@if(!isset($intList[0]))
                                <tr>
									<td colspan="3"><di>※データはありません。</di></td>
                                </tr>
							@else
								@foreach ($intList as $int)
								{{ html()->form('POST', '/admin/nobill')->attribute('name', "editform{$int->id}")->open() }}
								{{ html()->hidden('company_id', $comp->id) }}
								{{ html()->hidden('interview_id', $int->id) }}
								<tr>
									<td><input type="checkbox" name="complete_flag" value="1" @if ($int->complete_flag == '1') checked="checked" @endif  onchange="contChange('{{ 'contsave' . $int->id }}')"></td>
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
								</tr>
								{{ html()->form()->close() }}
								@endforeach
							@endif
                            </table>


						</di><!-- /.secContentsInner -->
					</section><!-- /.secContents-mb -->
				</di><!-- /.containerContents -->
            </di><!-- /.mainContentsInner-oneColumn -->

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
