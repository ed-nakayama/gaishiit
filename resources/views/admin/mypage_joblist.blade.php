@extends('layouts.admin.auth')

@section('content')

<head>
	<title>マイページ｜{{ config('app.name', 'Laravel') }}</title>
</head>

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
					<p class="tab_btn active"><a href="/admin/mypage/joblist">ジョブ一覧</a></p>
					<p class="tab_btn"><a href="/admin/mypage/jobsfc">CSVダウンロード</a></p>
					<p class="tab_btn"><a href="/admin/mypage/eval">クチコミ一覧</a></p>
				</div>

				<div class="secContentsInner">
					<div class="panel_area" style="padding: 0px;">

						{{ html()->form('GET', '/admin/mypage/joblist/list')->id('addform')->attribute('name', 'addform')->open() }}
						<div class="secBtnHead">
							<div class="secBtnHead-btn">
								<ul class="item-btn" style="align-items: center;">
									<li style="width: 400px;margin-left: 0px;">全体検索
										{{ html()->text('freeword', $freeword) }}
									</li>
									<li>
										公開/非公開
										<div class="selectWrap">
											{{ html()->select('open_flag', [0 => '非公開', 1 => '公開', 2 => 'すべて'], $open_flag)->class('select-no') }}
										</div>
									</li>
									<li>
										職種有無
										<div class="selectWrap">
											{{ html()->select('cat_flag', [0 => '職種なし', 1 => '職種あり', 2 => 'すべて'], $cat_flag)->class('select-no') }}
										</div>
									</li>
									<li style="margin-top: 20px;"><a href="javascript:addform.submit()" class="squareBtn">検索</a></li>
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
						{{ html()->form()->close() }}
						<div class="secBtnHead" style="display: flex;justify-content: flex-end;;margin-right: 10px;">
							<div class="secBtnHead-btn">
								<ul  class="item-btn" style="align-items: center;">

									<li style="width: auto;white-space:nowrap;">
										{{ html()->form('POST', '/admin/mypage/joblist/upload')->attribute('name', 'upform')->acceptsFiles()->open() }}
										{{ html()->file('file')->class('form-control') }}
										{{ html()->form()->close() }}
									</li>
									<li style="width: auto;white-space:nowrap;"><a href="javascript:upform.submit()" class="squareBtn">　更新用 CSVアップロード　</a></li>

									<li style="width: auto;white-space:nowrap;"><a href="/admin/mypage/joblist/download" class="squareBtn">　一括ダウンロード　</a></li>
								</ul><!-- /.item -->
								{{-- 更新成功メッセージ --}}
								@if (session('upload_success'))
									<div id="success1" class="alert alert-success"  style="color:#0000ff;">
										{{session('upload_success')}}
									</div>
								@endif
							</div><!-- /.secBtnHead-btn -->
						</div>

@if(!isset($jobList[0]))
						<div>※データはありません。</div>
@else
						<p style="text-align: center;">全{{ $jobList->total() }}件中 {{  ($jobList->currentPage() -1) * $jobList->perPage() + 1}}-{{ (($jobList->currentPage() -1) * $jobList->perPage() + 1) + (count($jobList) -1)  }}件</p>
						<div class="pager">
							{{ $jobList->appends(request()->query())->links('pagination.admin') }}
						</div>

						<table class="tbl-joblist">
							<tr>
								<th>更新日</th>
								<th>企業名</th>
								<th>ジョブID</th>
								<th>Job Title</th>
								<th>公開/<br>非公開</th>
								<th>Close</th>
								<th>職種</th>
								<th>補足カテゴリ</th>
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
										{{ html()->form('GET', '/admin/mypage/job/edit')->attribute('name', 'userform'. $int->id)->open() }}
										{{ html()->hidden('company_id', $int->company_id) }}
										{{ html()->hidden('job_id', $int->id) }}
										<a href="javascript:userform{{ $int->id }}.submit()" style="text-decoration: underline;">{{ $int->name }}</a>
										{{ html()->form()->close() }}
									</td>
									<td>@if ($int->open_flag == '1')公開 @else非公開 @endif</td>
									<td  style="text-align: center;">
										{{ html()->checkbox('',false ,$int->id)->id('close' . $int->id)->attribute('onchange', "modalConfirm($int->id);") }}
									</td>
									<td>{{ $int->getJobCategoryName() }}</td>
									<td>{{ $int->sub_category }}</td>
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


{{-- 確認　モーダル領域   --}}

	<div class="remodal" data-remodal-id="modal" style="width: 400px;">
		<div class="modalTitle">
			<h2>変更を実行しますか？</h2>
		</div><!-- /.modalTitle -->
    
		<div style="display: flex; justify-content: space-around;">
			<a href="javascript: modalSubmit();"  class="squareBtn btn-short">はい</a>
			<a href="javascript:modalClose();"  class="squareBtn btn-short">いいえ</a>
		</div><!-- /.btn-container -->
	</div>

{{-- END確認　モーダル領域   --}}

{{-- javascript用変数 --}}
<div id="save_id" value="" />

<script>

/////////////////////////////////////////////////////////
//モーダルが閉じた後に実行する処理
/////////////////////////////////////////////////////////
$(document).on('closed', '.remodal', function() {

	let id = document.getElementById( 'save_id' ).value;
	let checkbox = document.getElementById('close' + id);
	checkbox.checked = false;

});


/////////////////////////////////////////////////////////
// モーダルオープン
/////////////////////////////////////////////////////////
function modalConfirm(id) {

	document.getElementById( 'save_id' ).value = id;

	var modal = $.remodal.lookup[$('[data-remodal-id=modal]').data('remodal')];
    modal.open();
}


/////////////////////////////////////////////////////////
// モーダルクローズ
/////////////////////////////////////////////////////////
function modalClose() {

	var modal = $.remodal.lookup[$('[data-remodal-id=modal]').data('remodal')];
    modal.close();
}


/////////////////////////////////////////////////////////
// モーダル更新実行
/////////////////////////////////////////////////////////
function modalSubmit() {

	let id = document.getElementById( 'save_id' ).value;
	console.log(id);
	
	modalClose();
	
	$.ajax({
		headers: {
			"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
		},
        type: "GET",
        url: "/admin/api/joblist",
        data: { 'id' : id },
        dataType: 'text',
	}).done(function(event) {
    	console.log('URLにアクセス成功');
		location.reload();

	}).fail(function(event) {
		console.log('URLにアクセス失敗')
	});

	
}

</script>


@endsection