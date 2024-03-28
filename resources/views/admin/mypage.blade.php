@extends('layouts.admin.auth')

@section('content')

<head>
	<title>マイページ｜{{ config('app.name', 'Laravel') }}</title>
</head>

<div class="mainContentsInner-oneColumn">

	<div style="display:flex;justify-content: space-between;">
		<div class="mainTtl title-main">
			<h2>マイページ</h2>
		</div><!-- /.mainTtl -->
		<div style="text-align: right;margin-bottom: 10px;"><a href="javascript:void(0);" onClick="openWin({{ Auth::id() }})" class="squareBtn" style="width: 140px;height: 30px;padding: 5px 0;">企業代理ログイン</a></div>
 	</div>
                
	<div class="containerContents">

		<section class="secContents-mb">
                    
			<div class="tab_box_no">
				<div class="btn_area">
					<p class="tab_btn active"><a href="/admin/mypage">候補者承認</a></p>
					<p class="tab_btn"><a href="/admin/mypage/progress">面談進捗管理</a></p>
					<p class="tab_btn"><a href="/admin/mypage/enter">採用者一覧</a></p>
					<p class="tab_btn"><a href="/admin/mypage/joblist">ジョブ一覧</a></p>
					<p class="tab_btn"><a href="/admin/mypage/jobsfc">CSVダウンロード</a></p>
					<p class="tab_btn"><a href="/admin/mypage/eval">クチコミ一覧</a></p>
				</div>

				<div class="secContentsInner">
					<div class="panel_area" style="padding: 0px;">

						<div class="secBtnHead">
							<div class="secBtnHead-btn">
								<ul class="item-btn">
									<li>
										<div class="selectWrap">
											{{ html()->form('GET', '/admin/mypage/list')->attribute('name', 'selform')->open() }}
											<select name="sel_aprove"  class="select-no" onchange="changeSel()" id="sel_aprove">
												{{ html()->option('すべて') }}
												{{ html()->option('未承認'    , '0' , ($sel_aprove == '0')) }}
												{{ html()->option('承認'      , '1' , ($sel_aprove == '1')) }}
												{{ html()->option('リジェクト', '2' , ($sel_aprove == '2')) }}
											</select>
											{{ html()->form()->close() }}
										</div>
									</li>
								</ul><!-- /.item -->
							</div><!-- /.secBtnHead-btn -->
						</div>

@if(!isset($userList[0]))
						<div>※データはありません。</div>
@else
						<p style="text-align: center;">全{{ $userList->total() }}件中 {{  ($userList->currentPage() -1) * $userList->perPage() + 1}}-{{ (($userList->currentPage() -1) * $userList->perPage() + 1) + (count($userList) -1)  }}件</p>
						<table class="tbl-aprove">
							<tr>
								<th>登録日</th>
								<th>氏名</th>
								<th>状況</th>
								<th>年齢</th>
								<th>勤務先</th>
								<th>現在の職務内容</th>
								<th>役職</th>
								<th>最終学歴</th>
								<th>理論年収（OTE）</th>
								<th>希望勤務地</th>
							</tr>
                               
							@foreach ($userList as $int)
								<tr>
									<td>{{ str_replace(' ','/', str_replace('-','/', substr($int->created_at, 0 ,16))) }}</td>
									<td>
										{{ html()->form('POST', '/admin/user/detail')->attribute('name', 'userform'. $int->id)->open() }}
										{{ html()->hidden('user_id',   $int->id) }}
										{{ html()->hidden('parent_id', '0') }}
										<a href="javascript:userform{{ $int->id }}.submit()" style="text-decoration: underline;">{{ $int->name }}</a>
										{{ html()->form()->close() }}
									</td>
									<td>@if ( $int->aprove_flag == '1')<font color="blue">承認済</font>@elseif ( $int->aprove_flag == '2')<font color="red">リジェクト</font>@endif</td>
									<td>{{ $int->age }}</td>
									<td>{{ $int->company }}</td>
									<td>{{ mb_strimwidth($int->job_content, 0, 38, "...") }}</td>
									<td>{{ $int->job_title }}</td>
									<td>{{ $int->graduation . ' ' . $int->department }}</td>
									<td>{{ $int->ote_income }}</td>
									<td>{{ $int->location_name }}</td>
								</tr>
							@endforeach

						</table>
@endif

					</div><!-- /.panel_area -->
				</div><!-- /.secContentsInner -->

			</div><!-- /.tab_box_no -->
		</section><!-- /.secContents-mb -->
	</div><!-- /.containerContents -->

	<div class="pager">
		{{ $userList->appends(request()->query())->links('pagination.admin') }}
	</div>


</div><!-- /.mainContentsInner -->


<script type="text/javascript">

function changeSel() {

	document.selform.elements["sel_aprove"].value = document.getElementById( "sel_aprove" ).value;
	document.selform.submit();
}

</script>

@endsection