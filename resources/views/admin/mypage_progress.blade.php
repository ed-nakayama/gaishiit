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
					<p class="tab_btn active"><a href="/admin/mypage/progress">面談進捗管理</a></p>
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
									<li></li>
								</ul><!-- /.item -->
							</div><!-- /.secBtnHead-btn -->
						</div>

						<h2 class="contentsTitle">日程調整中</h2>
@if(!isset($beingList[0]))
						<div>※データはありません。</div>
@else
						<table class="tbl-progress" id="beingTable">
							<tr>
								<th>最終更新</th>
								<th>氏名</th>
								<th>企業名</th>
								<th>ステージ</th>
								<th>ステータス</th>
								<th>採用</th>
								<th>入社日</th>
								<th>ジョブ / 部門</th>
								<th>面接官</th>
								<th>メモ</th>
								<th></th>
							</tr>
                               
							@foreach ($beingList as $int)
								<tr>
									{{ html()->form('POST', '/admin/user/detail')->attribute('name', 'userform'. $int->id)->open() }}
									{{ html()->hidden('user_id',   $int->user_id) }}
									{{ html()->hidden('parent_id', '1') }}
									{{ html()->form()->close() }}

									{{ html()->form('POST', '/admin/mypage/progress/list')->attribute('name', 'beingform' . $int->id)->open() }}
									{{ html()->hidden('interview_id', $int->id) }}
									<td>{{ $int->updated_at->format('Y/m/d/H:i') }}</td>
									<td>
 										<a href="javascript:userform{{ $int->id }}.submit()" style="text-decoration: underline;">{{ $int->user_name }}</a>
									</td>
									<td>{{ $int->company_name }}</td>
									<td>
										@if ($int->interview_type == '0')
											{{ html()->hidden('stage', 99) }}
											カジュアル面談
										@else
											<div class="selectWrap">
												<select name="stage"  class="select-no"  @if ($int->interview_type == '0')disabled="disabled" @endif  onchange="beingChange('{{ 'beingsave' . $int->id }}')">
													{{ html()->option('', 0) }}
													@foreach ($constStage as $stg)
														@if ($int->interview_type == '0')
															@if ($stg->id == '99')
																{{ html()->option($stg->name, $stg->id, ($int->stage_id == $stg->id)) }}
															@endif
														@else
															@if ($stg->id != '99')
																{{ html()->option($stg->name, $stg->id, ($int->stage_id == $stg->id)) }}
															@endif
														@endif
													@endforeach
												</select>
											</div>
										@endif
									</td>
									<td>
										<div class="selectWrap">
											<select name="status" id="status" class="select-no"  onchange="beingChange('{{ 'beingsave' . $int->id }}')">
												{{ html()->option('', 0) }}
												@foreach ($constStatus as $st)
													{{ html()->option($st->name, $st->id, ($int->status_id == $st->id)) }}
												@endforeach
											</select>
										</div><!-- /.selectStatus -->
									</td>
									<td>
										<div class="selectWrap">
											<select name="result" id="result" class="select-no"  onchange="beingChange('{{ 'beingsave' . $int->id }}')">
												{{ html()->option('', 0) }}
												@foreach ($constResult as $st)
													{{ html()->option($st->name, $st->id, ($int->result_id == $st->id)) }}
												@endforeach
											</select>
										</div><!-- /.selectStatus -->
									</td>
									<td>
										<label style="padding: 5px 5px;border: 1px solid #ccc;">
											<input type="date" name="entrance_date" value="{{ $int['entrance_date'] }}"  oninput="alreadyChange('{{ 'beingsave' . $int->id }}')">
										</label>
									</td>
									<td>
										@if ( ($int->interview_type == '0') && ($int->interview_kind == '0') )
										@elseif ( ($int->interview_type == '0') && ($int->interview_kind == '1') )
											{{ $int->unit_name }}
										@else
											{{ $int->job_name }}
										@endif
									</td>
									<td>
										{{ html()->text('interviewer', $int->interviewer)->attribute('oninput', "beingChange('beingsave' . $int->id)") }}
									</td>
									<td>
										{{ html()->text('comment', $int->comment)->attribute('oninput', "beingChange('beingsave' . $int->id)") }}
									</td>
									<td>
										<div class="btnContainer"  style="display: none;" id="{{ 'beingsave' . $int->id }}">
											<a href="javascript:save_data({{ 'beingform' . $int->id }});" class="squareBtn btn-medium">保存</a>
										</div><!-- /.btn-container -->
									</td>
									{{ html()->form()->close() }}
								</tr>
							@endforeach

						</table>
@endif
					</div><!-- /.secContentsInner -->
				</section><!-- /.secContents -->
                    
				<section class="secContents">
					<div class="secContentsInner">

					<h2 class="contentsTitle">調整済み</h2>
@if (!isset($alreadyList[0]))
					<div>※データはありません。</div>
@else
					<table class="tbl-progress" id="alreadyTable">
						<tr>
							<th>面談日</th>
							<th>氏名</th>
							<th>企業名</th>
							<th>ステージ</th>
							<th>ステータス</th>
							<th>採用</th>
							<th>ジョブ / 部門</th>
							<th>面接官</th>
							<th>メモ</th>
							<th></th>
						</tr>
						@foreach ($alreadyList as $int)
							<tr>
								{{ html()->form('POST', '/admin/user/detail')->attribute('name', 'aluserform'. $int->id)->open() }}
								{{ html()->hidden('user_id', $int->user_id) }}
								{{ html()->hidden('parent_id', '1') }}
								{{ html()->form()->close() }}

								{{ html()->form('POST', '/admin/mypage/progress/list')->attribute('name', 'alreadyform'. $int->id)->open() }}
								{{ html()->hidden('interview_id', $int->id) }}
								<td>
									<label style="padding: 5px 5px;border: 1px solid #ccc;">
										<input type="date" name="interview_date" value="{{ $int['interview_date'] }}"  oninput="alreadyChange('{{ 'alreadysave' . $int->id }}')">
									</label>
								</td>
								<td><a href="javascript:aluserform{{ $int->id }}.submit()"style="text-decoration: underline;">{{ $int->user_name }}</a></td>
								<td>{{ $int->company_name }}</td>
								<td>
									@if ($int->interview_type == '0')
										{{ html()->hidden('stage', 99) }}
										カジュアル面談
									@else
										<div class="selectWrap">
											<select name="stage"  class="select-no" onchange="alreadyChange('{{ 'alreadysave' . $int->id }}')">
												{{ html()->option('', 0) }}
												@foreach ($constStage as $stg)
													@if ($int->interview_type == '0')
														@if ($stg->id == '99')
															{{ html()->option($stg->name, $st->id, ($int->stage_id == $stg->id)) }}
														@endif
													@else
														@if ($stg['id'] != '99')
															{{ html()->option($stg->name, $st->id, ($int->stage_id == $stg->id)) }}
														@endif
													@endif
												@endforeach
											</select>
										</div>
									@endif
								</td>
								<td>
									<div class="selectWrap">
										<select name="status" class="select-no" onchange="alreadyChange('{{ 'alreadysave' . $int->id }}')">
											{{ html()->option('', 0) }}
											@foreach ($constStatus as $st)
												{{ html()->option($st->name, $st->id, ($int->status_id == $st->id)) }}
											@endforeach
										</select>
									</div><!-- /.selectStatus -->
								</td>
								<td>
									<div class="selectWrap">
										<select name="result" id="result" class="select-no"  onchange="alreadyChange('{{ 'alreadysave' . $int->id }}')">
											{{ html()->option('', 0) }}
											@foreach ($constResult as $st)
												{{ html()->option($st->name, $st->id, ($int->result_id == $st->id)) }}
											@endforeach
										</select>
									</div><!-- /.selectStatus -->
								</td>
								<td>
									@if ( ($int->interview_type == '0') && ($int->interview_kind == '0') )
									@elseif ( ($int->interview_type == '0') && ($int->interview_kind == '1') )
										{{ $int->unit_name }}
									@else
										{{ $int->job_name }}
									@endif
								</td>
								<td>
									{{ html()->text('interviewer', $int->interviewer)->attribute('oninput', "alreadyChange('alreadysave' . $int->id)") }}
								</td>
								<td>
									{{ html()->text('comment', $int->comment)->attribute('oninput', "alreadyChange('alreadysave' . $int->id)") }}
								</td>
								<td>
									<div class="btnContainer"  style="display: none;" id="{{ 'alreadysave' . $int->id }}">
										<a href="javascript:save_data({{ 'alreadyform' . $int->id }});" class="squareBtn btn-medium">保存</a>
									</div><!-- /.btn-container -->
								</td>
								{{ html()->form()->close() }}
							</tr>
						@endforeach

					</table>
@endif

				</div><!-- /.panel_area -->
			</div><!-- /.secContentsInner -->

		</section><!-- /.secContents-mb -->
	</div><!-- /.containerContents -->
{{--

	<div class="pager">
		{{ $userList->links('pagination.admin') }}
	</div>
--}}


</div><!-- /.mainContentsInner -->

{{-- モーダル --}}
<div class="modalContainer">
	<div class="remodal" data-remodal-id="modal">
		<div class="modalTitle">
			<h2 class="title-bb">ステータスを「終了」にしようとしています</h2>
		</div><!-- /.modalTitle -->

		<div class="modalInner">
			<p class="mb-ajust">ステータスを「終了」に切り替えた候補者は<br>
                                    候補者管理 - 終了一覧に移動し<br>
                                    現在のページでは表示されません。<br>
                                    よろしいですか？</p>
                                  <p>採用が決定した候補者は<br>
                                     必ずステータスを「採用」に変更してください。<br>
                                     ガイシITより後程ご連絡させていただきます。</p>
		</div><!-- /.modalInner -->
                                                    
		<div class="btnContainer">
			<a href="#" data-remodal-action="confirm" class="squareBtn btn-large">確認して変更する</a><br><br>
			<a href="#" data-remodal-action="close" class="squareBtn btn-large">キャンセル</a>
		</div><!-- /.btn-container -->
	</div><!-- /.modalContainer -->
</div>



<script type="text/javascript">

var pre_being ="";
var pre_already ="";


function beingChange(nm) {

	if (pre_being != nm) {
		document.getElementById(nm).style.display ="block";
		if (pre_being != "") {
			document.getElementById(pre_being).style.display ="none";
		}
		pre_being = nm;
	}
}

function alreadyChange(nm) {

	if (pre_already != nm) {
		document.getElementById(nm).style.display ="block";
		if (pre_already != "") {
			document.getElementById(pre_already).style.display ="none";
		}
		pre_already = nm;
	}
}


$(function () {
    // Remodalのオブジェクトを取得
    var remodal = $('[data-remodal-id=modal0]').remodal();

    // OKボタンが押されたときに発火するイベント
    $(document).on('confirmation', remodal, function () {
//        $('#form_id').submit();
        forms.submit();
    });
})

let forms;

function save_data(frm) {

//console.log(frm);
	forms = frm;

	if (frm.elements["status"].value == '9') {
		$("[data-remodal-id=modal]").remodal().open();
	} else {
		frm.submit();
	}


}

</script>

@endsection
