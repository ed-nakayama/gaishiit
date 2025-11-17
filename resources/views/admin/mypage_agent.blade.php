@extends('layouts.admin.auth')

@section('content')

<head>
	<title>マイページ｜{{ config('app.name', 'Laravel') }}</title>
</head>

<script>
function func_dl() {

	document.addform.dl.value = '1';
	document.addform.bulk.value = '';
	document.addform.submit();
}

function func_nodl() {

	document.addform.dl.value = '';
	document.addform.bulk.value = '';
	document.addform.submit();
}

</script>


<div class="mainContentsInner-oneColumn">

	<div style="display:flex;justify-content: space-between;">
		<div class="mainTtl title-main">
			<h2>ジョブ一覧</h2>
		</div><!-- /.mainTtl -->
 	</div>
               
	<div class="containerContents">

		<section class="secContents-mb">
                    
			<div class="tab_box_no">

				<div class="secContentsInner">
					<div class="panel_area" style="padding: 0px;">

						{{ html()->form('GET', '/admin/mypage/joblist/list')->id('addform')->attribute('name', 'addform')->open() }}
						{{ html()->hidden('dl', '') }}
						{{ html()->hidden('bulk', '') }}
						<div class="secBtnHead">
							<div class="secBtnHead-btn">
								<ul class="item-btn" style="align-items: center;">
									<li style="width: 400px;margin-left: 0px;">全体検索
										{{ html()->text('freeword', $freeword) }}
									</li>
									<li>
										表示/非表示
										<div class="selectWrap">
											{{ html()->select('open_flag', [0 => '非表示', 1 => '表示', 2 => 'すべて'], $open_flag) }}
										</div>
									</li>
									<li>
										職種有無
										<div class="selectWrap">
											{{ html()->select('cat_flag', [0 => '職種なし', 1 => '職種あり', 2 => 'すべて'], $cat_flag) }}
										</div>
									</li>
									<li>
										一般/portal
										<div class="selectWrap">
											{{ html()->select('portal_flag', [0 => '一般', 1 => 'portal', 2 => 'すべて'], $portal_flag) }}
										</div>
									</li>
									<li style="margin-top: 20px;"><input type="button" value="検索" class="squareBtn" onclick="func_nodl()"></li>
									<li style="margin-top: 20px;"><input type="button" value="検索ダウンロード" class="squareBtn" onclick="func_dl()"></li>
								</ul><!-- /.item -->
							</div><!-- /.secBtnHead-btn -->
						</div>
						<div class="secBtnHead">
							<div class="secBtnHead-btn">
								<ul class="item-btn" style="align-items: center;">
									<li style="width: 180px;margin-left: 0px;">企業名
										{{ html()->text('comp_name', $comp_name) }}
									</li>
									<li style="width: 180px;margin-left: 0px;">Job Title
										{{ html()->text('job_title', $job_title) }}
									</li>
									<li style="width: 180px;margin-left: 0px;">補足カテゴリ
										{{ html()->text('sub_category', $sub_category) }}
									</li>
									<li style="width: 180px;margin-left: 0px;">部門
										{{ html()->text('unit_name', $unit_name) }}
									</li>
									<li style="width: 180px;margin-left: 0px;">ロケーション
										<select name="location"  class="select-no">
											{{ html()->option() }}
											@foreach ($constLocation as $loc)
												{{ html()->option($loc->name, $loc->id, ($location == $loc->id)) }}
											@endforeach
											{{ html()->option('設定なし', 99, ($location == 99)) }}
										</select>
									</li>
									<li style="width: 180px;margin-left: 0px;">勤務地詳細/その他
										{{ html()->text('working_place',  $working_place) }}
									</li>
								</ul><!-- /.item -->
							</div><!-- /.secBtnHead-btn -->
						</div>

						<div class="secBtnHead">
							<ul class="item-btn" style="align-items: center;">
								<li style="width: auto;" >
									企業
									<div class="selectWrap aharf">
										<select name="comp_id"  class="select-no">
											<option value=""></option>
											@foreach ($company_list as $comp)
												<option value="{{ $comp->id }}" @if (old('comp_id' ,$comp->id) == $comp_id)  selected @endif>{{ $comp->name }}</option>
											@endforeach
										</select>
									</div>
								</li>
							</ul><!-- /.item -->
						</div>

						{{ html()->form()->close() }}

@if(!isset($jobList[0]))
						<div>※データはありません。</div>
@else
						<p style="text-align: center;">全{{ $jobList->total() }}件中 {{  ($jobList->currentPage() -1) * $jobList->perPage() + 1}}-{{ (($jobList->currentPage() -1) * $jobList->perPage() + 1) + (count($jobList) -1)  }}件</p>
						<div class="pager">
							{{ $jobList->appends(request()->query())->links('pagination.admin') }}
						</div>

						<table class="tbl-joblist-agent">
							<tr>
								<th>更新日</th>
								<th>企業名</th>
								<th>ジョブID</th>
								<th>Job Title</th>
								<th>一般/<br>portal</th>
								<th>表示/<br>非表示</th>
								<th>職種</th>
								<th>部門</th>
								<th>ロケーション</th>
								<th>勤務地詳細<br>/その他</th>
								<th>URL</th>
							</tr>
                               
							@foreach ($jobList as $int)
								<tr>
									<td>{{ str_replace(' ','/', str_replace('-','/', substr($int->updated_at, 0 ,16))) }}</td>
									<td>{{ $int->company_name }}</td>
									<td>{{ $int->job_code }}</td>
									<td>
										{{ html()->form('GET', '/admin/mypage/job/ref')->attribute('name', 'userform'. $int->id)->open() }}
										{{ html()->hidden('company_id', $int->company_id) }}
										{{ html()->hidden('job_id', $int->id) }}
										<a href="javascript:userform{{ $int->id }}.submit()" style="text-decoration: underline;">{{ $int->name }}</a>
										{{ html()->form()->close() }}
									</td>
									<td>@if ($int->portal_flag == '1')　portal @else 一般 @endif</td>
									<td>
										@if ($int->open_flag == '1')表示 @else非表示 @endif
									</td>
									<td>{{ $int->getJobCategoryName() }}</td>
									<td>{{ $int->unit_name }}</td>
									<td>{{ $int->getLocations() }}</td>
									<td>{{ $int->working_place }}</td>
									<td style="width: 20px;word-break:  break-all;">{{ $int->url }}</td>
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