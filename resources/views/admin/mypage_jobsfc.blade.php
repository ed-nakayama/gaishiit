@extends('layouts.admin.auth')

@section('content')

<head>
	<title>マイページ｜{{ config('app.name', 'Laravel') }}</title>
</head>
 <script type="text/javascript">
  function sfc_search() {

	addform.dl.value = '';
	addform.div.value = '';
	document.addform.submit();
  }

  function sfc_download() {

	addform.dl.value = '1';
	addform.div.value = '';
	document.addform.submit();
  }
  
  function div_download() {

	addform.dl.value = '';
	addform.div.value = '1';
	document.addform.submit();
  }
</script>

<div class="mainContentsInner-oneColumn">

	<div style="display:flex;">
		<div class="mainTtl title-main">
			<h2>マイページ</h2>
		</div><!-- /.mainTtl -->
		<div style="margin-left: 40px;margin-bottom: 10px;"><a href="javascript:void(0);" onClick="openWin({{ Auth::id() }})" class="squareBtn" style="width: 140px;height: 30px;padding: 5px 0;">企業代理ログイン</a></div>
 	</div>
               
	<div class="containerContents">

		<section class="secContents-mb">
                    
			<div class="tab_box_no">
				<div class="btn_area">
					<p class="tab_btn"><a href="/admin/mypage">候補者承認</a></p>
					<p class="tab_btn"><a href="/admin/mypage/progress">面談進捗管理</a></p>
					<p class="tab_btn"><a href="/admin/mypage/enter">採用者一覧</a></p>
					<p class="tab_btn"><a href="/admin/mypage/joblist">ジョブ一覧</a></p>
					<p class="tab_btn active"><a href="/admin/mypage/jobsfc">CSVダウンロード</a></p>
					<p class="tab_btn"><a href="/admin/mypage/eval">クチコミ一覧</a></p>
				</div>

				<div class="secContentsInner">
					<div class="panel_area" style="padding: 0px;">

						{{ html()->form('GET', '/admin/mypage/jobsfc')->id('addform')->attribute('name', 'addform')->open() }}
						{{ html()->hidden('dl') }}
						{{ html()->hidden('div') }}
						<div class="secBtnHead">
							<div class="secBtnHead-btn">
								<ul class="item-btn" style="align-items: center;">
									<li style="width: 180px;margin-left: 0px;">企業名
										{{ html()->text('comp_name',  $comp_name) }}
									</li>
									<li style="margin-top: 20px;"><a href="" class="squareBtn" onclick="sfc_search();return false;">検索</a></li>
									<li style="margin-top: 20px;margin-left: 450px;width: auto;white-space:nowrap;"><a href="" class="squareBtn" onclick="sfc_download();return false;">　検索企業別ダウンロード　</a></li>
									<li style="margin-top: 20px;width: auto;white-space:nowrap;"><a href="" class="squareBtn" onclick="div_download();return false;">　分割ダウンロード　</a></li>
								</ul><!-- /.item -->
							</div><!-- /.secBtnHead-btn -->
						</div>
						{{ html()->form()->close() }}

@if(!isset($jobList[0]))
						<div>※データはありません。</div>
@else
						<p style="text-align: center;">全{{ $jobList->total() }}件中 {{  ($jobList->currentPage() -1) * $jobList->perPage() + 1}}-{{ (($jobList->currentPage() -1) * $jobList->perPage() + 1) + (count($jobList) -1)  }}件</p>
						<div class="pager">
							{{ $jobList->appends(request()->query())->links('pagination.admin') }}
						</div>

						<table class="tbl-jobsfc">
							<tr>
								<th>Client</th>
								<th>企業名</th>
								<th>Job Title</th>
								<th>Job Title名</th>
								<th>JD No.</th>
								<th>部署名</th>
								<th>職種名</th>
								<th>JD Status</th>
								<th>For agent</th>
								<th>外資Job番号</th>
							</tr>
                               
							@foreach ($jobList as $job)
								<tr>
									<td>{{ $job->salesforce_id  }}</td>
									<td>{{ $job->comp_name_english }}</td>
									<td>{{ $job->name }}</td>
									<td>{{ $job->short_name }}</td>
									<td>{{ $job->job_code }}</td>
									<td>{{ $job->unit_name }}</td>
									<td>{{ $job->getJobCategoryName() }}</td>
									<td>@if ($job->open_flag == '1')open @else一般Web情報 @endif</td>
									<td>{{ $job->for_agent }}</td>
									<td>{{ $job->id }}</td>
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
		{{ $jobList->appends(request()->query())->links('pagination.admin') }}
	</div>


</div><!-- /.mainContentsInner -->


@endsection