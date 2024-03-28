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
					<p class="tab_btn"><a href="/admin/mypage">候補者承認</a></p>
					<p class="tab_btn"><a href="/admin/mypage/progress">面談進捗管理</a></p>
					<p class="tab_btn"><a href="/admin/mypage/enter">採用者一覧</a></p>
					<p class="tab_btn"><a href="/admin/mypage/joblist">ジョブ一覧</a></p>
					<p class="tab_btn"><a href="/admin/mypage/jobsfc">CSVダウンロード</a></p>
					<p class="tab_btn active"><a href="/admin/mypage/eval">クチコミ一覧</a></p>
				</div>

				<div class="secContentsInner">
					<div class="panel_area" style="padding: 0px;">

						<div class="secBtnHead" style="margin-bottom: 0px;">
							<div class="secBtnHead-btn">
								{{ html()->form('GET', '/admin/mypage/eval')->attribute('name', 'selform')->open() }}
								<ul class="item-btn">
									<li style="text-align:right;padding:10px 4px;">
										承認ステータス
									</li>
									<li>
										<div class="selectWrap">
											<select name="sel_aprove"  class="select-no" id="sel_aprove">
												{{ html()->option('すべて', 99) }}
												{{ html()->option('編集中', 0, ($sel_aprove == '0')) }}
												{{ html()->option('申請中', 1, ($sel_aprove == '1')) }}
												{{ html()->option('審査中', 2, ($sel_aprove == '2')) }}
												{{ html()->option('承認済', 8, ($sel_aprove == '8')) }}
												{{ html()->option('否決',   9, ($sel_aprove == '9')) }}
											</select>
										</div>
									</li>
									<li style="text-align:right;padding:10px 4px;">
										企業名
									</li>
									<li style="width:200px;">
										{{ html()->text('freeword',  $freeword) }}
									</li>
									<li style="padding:4px;">
										<div><a href="javascript:selform.submit()" class="squareBtn">検索</a></div>
									</li>
								</ul><!-- /.item -->
								{{ html()->form()->close() }}
							</div><!-- /.secBtnHead-btn -->
							<div><a href="/admin/mypage/eval/edit" class="squareBtn" style="width: 140px;height: 30px;padding: 5px 0;">新規作成</a></div>
						</div>

@if(!isset($evalList[0]))
						<div>※データはありません。</div>
@else
						<p style="text-align: center;">全{{ $evalList->total() }}件中 {{  ($evalList->currentPage() -1) * $evalList->perPage() + 1}}-{{ (($evalList->currentPage() -1) * $evalList->perPage() + 1) + (count($evalList) -1)  }}件</p>
						<table class="tbl-evallist">
							<tr>
								<th>評価企業</th>
								<th>候補者名</th>
								<th>性別</th>
								<th>雇用形態</th>
								<th>在籍状況</th>
								<th>入社形態</th>
								<th>入社年</th>
								<th>退社年</th>
								<th>職種</th>
								<th>承認</th>
								<th>更新日</th>
								<th>詳細</th>
							</tr>
                               
							@foreach ($evalList as $eval)
							<tr>
								<td>{{ $eval->comp_name }}</td>
								<td>{{ $eval->user_name }}</td>
								<td>
									@if ($eval->user_sex == '1')男
									@elseif ($eval->user_sex == '2')女
									@elseif ($eval->user_sex == '0')なし
									@else
										@if ($eval->sex == '1')（男）
										@elseif ($eval->sex == '2')（女）
										@elseif ($eval->sex == '0')（なし）
										@endif
									@endif
								</td>
								<td>
									@if ($eval->emp_status == '1')正社員
									@elseif ($eval->emp_status == '2')契約社員
									@elseif ($eval->emp_status == '9')その他
									@endif
								</td>
								<td>
									@if ($eval->tenure_status == '1')現職
									@elseif ($eval->tenure_status == '2')退職済
									@endif
								</td>
								<td>
									@if ($eval->join_status == '1')新卒入社
									@elseif ($eval->join_status == '2')中途入社
									@endif
								</td>
								<td>{{ $eval->join_year }}</td>
								<td>{{ $eval->retire_year }}</td>
								<td>{{ $eval->occupation }}</td>
								<td>
									@if ( $eval->approve_flag == '0')編集中
									@elseif ( $eval->approve_flag == '1')<font color="green">申請中</font>
									@elseif ( $eval->approve_flag == '2')<font color="green">審査中</font>
									@elseif ( $eval->approve_flag == '8')<font color="blue">承認済</font>
									@elseif ( $eval->approve_flag == '9')<font color="red">否決</font>
									@endif
								</td>
								<td>{{ str_replace(' ','/', str_replace('-','/', substr($eval->updated_at, 0 ,16))) }}</td>
								<td>
									{{ html()->form('GET', '/admin/mypage/eval/edit')->attribute('name', 'userform'. $eval->id)->open() }}
									{{ html()->hidden('eval_id', $eval->id) }}
									<a href="javascript:userform{{ $eval->id }}.submit()" style="text-decoration: underline;">詳細</a>
									{{ html()->form()->close() }}
								</td>
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
		{{ $evalList->appends(request()->query())->links('pagination.admin') }}
	</div>


</div><!-- /.mainContentsInner -->

@endsection