@extends('layouts.comp.auth')

@section('content')

<head>
	<title>面談進捗管理｜{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('comp/assets/css/seek.css') }}" rel="stylesheet">
</head>

	<div class="mainContentsInner-oneColumn">

		<div class="mainTtl title-main">
			<h2>マイページ</h2>
		</div><!-- /.mainTtl -->


                <div class="containerContents">
                    
                    <section class="secContents-mb">
                    
						<div class="tab_box_no">
                            <div class="btn_area">
                                <p class="tab_btn"><a href="/comp/mypage/main">保存した条件の候補者</a></p>
                                <p class="tab_btn"><a href="/comp/mypage/newuser">新しい候補者を探す</a></p>
                                <p class="tab_btn active"><a href="/comp/mypage/progress">面談進捗管理</a></p>
                            </div>

                        	<div class="secContentsInner">

                            	<div class="panel_area" style="padding: 10px;">
                            	
								<div style="display: flex;justify-content: space-between;">
									<h2 class="contentsTitle">日程調整中</h2>
									<div class="secTitle-btn"">
										<ul class="item-btn">
											<li><a href="/comp/clientend" class="squareBtn btn-medium">終了した候補者一覧</a></li>
											<li><a href="/comp/client/enter" class="squareBtn btn-medium">採用者一覧</a></li>
										</ul><!-- /.item -->
									</div><!-- /.secBtnHead-btn -->
								</div>
							
@if(!isset($beingList[0]))
  <div>※データはありません。</div>
@else
                           <table class="tbl-mypage-progress" id="beingTable">
                                <tr>
                                    <th>最終更新日</th>
                                    <th>氏名</th>
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
                                {{ Form::open(['url' => '/comp/interview/flow', 'name' => 'userform' . $int->id ,'method '=> 'GET']) }}
                                {{ Form::hidden('interview_id', $int->id) }}
                                {{ Form::close() }}

								{{ Form::open(['url' => '/comp/mypage/progress/list', 'name' => 'beingform' . $int->id ]) }}
                                {{ Form::hidden('interview_id', $int->id) }}
                                    <td>{{ $int->updated_at->format('Y/m/d/H:i') }}</td>
                                    <td><a href="javascript:userform{{ $int->id }}.submit()" style="text-decoration: underline;">{{ $int->user_name }}</a></td>
                                    <td>
                                        @if ($int->interview_type == '0')
                                            {{ Form::hidden('stage', 99) }}
                                            カジュアル面談
                                        @else
                                        <div class="selectWrap">
                                            <select name="stage"  class="select-no"  @if ($int->interview_type == '0')disabled="disabled" @endif  onchange="beingChange('{{ 'beingsave' . $int->id }}')">
                                                <option value="0"></option>
                                            @foreach ($constStage as $stg)
                                                 @if ($int->interview_type == '0')
                                                   @if ($stg->id == '99')
                                                    <option value="{{ $stg->id }}" @if ($int->stage_id == $stg->id)  selected @endif >{{ $stg->name }}</option>
                                                   @endif
                                                 @else
                                                   @if ($stg->id != '99')
                                                      <option value="{{ $stg->id }}" @if ($int->stage_id == $stg->id)  selected @endif >{{ $stg->name }}</option>
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
                                                <option value="0"></option>
                                             @foreach ($constStatus as $st)
                                                <option value="{{ $st->id }}" @if ($int->status_id == $st->id)  selected @endif >{{ $st->name }}</option>
                                             @endforeach
                                            </select>
                                        </div><!-- /.selectStatus -->
                                    </td>
                                    <td>
                                        <div class="selectWrap">
                                            <select name="result" id="result" class="select-no"  onchange="beingChange('{{ 'beingsave' . $int->id }}')">
                                                <option value="0"></option>
                                             @foreach ($constResult as $st)
                                                <option value="{{ $st->id }}" @if ($int->result_id == $st->id)  selected @endif >{{ $st->name }}</option>
                                             @endforeach
                                            </select>
                                        </div><!-- /.selectStatus -->
                                    </td>
                                    <td>
                                    	<label style="padding: 5px 5px;border: 1px solid #ccc;"><input type="date" name="entrance_date" value="{{ $int['entrance_date'] }}"  oninput="alreadyChange('{{ 'beingsave' . $int->id }}')"></label>
                                    </td>
                                    <td>
                                        @if ( ($int->interview_type == '0') && ($int->interview_kind == '0') )
                                        @elseif ( ($int->interview_type == '0') && ($int->interview_kind == '1') )
                                            {{ $int->unit_name }}
                                        @else
                                            {{ $int->job_name }}
                                        @endif
                                    </td>
                                    <td><input type="text" name="interviewer" value="{{ $int->interviewer }}"   oninput="beingChange('{{ 'beingsave' . $int->id }}')"></td>
                                    <td><input type="text" name="comment" value="{{ $int->comment }}"   oninput="beingChange('{{ 'beingsave' . $int->id }}')"></td>
                                    <td>
                                        <div class="btnContainer"  style="display: none;" id="{{ 'beingsave' . $int->id }}">
                                           <a href="javascript:save_data({{ 'beingform' . $int->id }});" class="squareBtn btn-medium">保存</a>
                                       </div><!-- /.btn-container -->
                                    </td>
                               {{ Form::close() }}
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
                            <table class="tbl-mypage-progress2 mb-ajust" id="alreadyTable">
                                <tr>
                                    <th>面談日</th>
                                    <th>氏名</th>
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
                                {{ Form::open(['url' => '/comp/interview/flow', 'name' => 'aluserform' . $int->id ]) }}
                                {{ Form::hidden('interview_id', $int->id) }}
                                {{ Form::close() }}

                                {{ Form::open(['url' => '/comp/mypage/progress/list', 'name' => 'alreadyform' . $int->id ]) }}
                                {{ Form::hidden('interview_id', $int->id) }}
                                    <td>
                                    	<label  style="padding: 5px 5px;border: 1px solid #ccc;"><input type="date" name="interview_date" value="{{ $int['interview_date'] }}"  oninput="alreadyChange('{{ 'alreadysave' . $int->id }}')"></label>
                                    </td>
                                    <td><a href="javascript:aluserform{{ $int->id }}.submit()" style="text-decoration: underline;">{{ $int->user_name }}</a></td>
                                    <td>
                                        @if ($int->interview_type == '0')
                                            {{ Form::hidden('stage', 99) }}
                                            カジュアル面談
                                        @else
                                        <div class="selectWrap">
                                            <select name="stage"  class="select-no" onchange="alreadyChange('{{ 'alreadysave' . $int->id }}')">
                                                <option value="0"></option>
                                             @foreach ($constStage as $stg)
                                             @if ($int->interview_type == '0')
                                               @if ($stg->id == '99')
                                                <option value="{{ $stg->id }}" @if ($int->stage_id == $stg->id)  selected @endif >{{ $stg->name }}</option>
                                               @endif
                                             @else
                                               @if ($stg['id'] != '99')
                                                  <option value="{{ $stg->id }}" @if ($int->stage_id == $stg->id)  selected @endif >{{ $stg->name }}</option>
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
                                                <option value="0"></option>
                                             @foreach ($constStatus as $st)
                                                <option value="{{ $st->id }}" @if ($int->status_id == $st->id)  selected @endif >{{ $st->name }}</option>
                                             @endforeach
                                            </select>
                                        </div><!-- /.selectStatus -->
                                    </td>
                                    <td>
                                        <div class="selectWrap">
                                            <select name="result" id="result" class="select-no"  onchange="alreadyChange('{{ 'alreadysave' . $int->id }}')">
                                                <option value="0"></option>
                                             @foreach ($constResult as $st)
                                                <option value="{{ $st->id }}" @if ($int->result_id == $st->id)  selected @endif >{{ $st->name }}</option>
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
                                    <td><input type="text" name="interviewer" value="{{ $int->interviewer }}"  oninput="alreadyChange('{{ 'alreadysave' . $int->id }}')"></td>
                                    <td><input type="text" name="comment" value="{{ $int->comment }}"  oninput="alreadyChange('{{ 'alreadysave' . $int->id }}')"></td>
                                    <td>
                                        <div class="btnContainer"  style="display: none;" id="{{ 'alreadysave' . $int->id }}">
                                            <a href="javascript:save_data({{ 'alreadyform' . $int->id }});" class="squareBtn btn-medium">保存</a>
                                        </div><!-- /.btn-container -->
                                    </td>
                               {{ Form::close() }}
                                </tr>
                              @endforeach

                            </table>
@endif



                            	</div><!-- /.panel_area -->
                            </div><!-- /.secContentsInner -->

						</div><!-- /.tab_box_no -->
					</section><!-- /.secContents-mb -->
				</div><!-- /.containerContents -->
			</div><!-- /.mainContentsInner -->


{{-- モーダル --}}
		<div class="remodal" data-remodal-id="modal">

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

{{-- モーダル END --}}



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


$(document).ready(function(){
  $("#beingTable tr:even").not(':first').addClass("evenRow");
  $("#beingTable tr").not(':first').hover(
    function(){
        $(this).addClass("focusRow");
//		$(this)[0].cells[7].style.display ="block";
    },function(){
        $(this).removeClass("focusRow");
//	 	$(this)[0].cells[7].style.display ="none";
 });
 
  $("#alreadyTable tr:even").not(':first').addClass("evenRow");
  $("#alreadyTable tr").not(':first').hover(
    function(){
        $(this).addClass("focusRow");
//		$(this)[0].cells[7].style.display ="block";
    },function(){
        $(this).removeClass("focusRow");
//	 	$(this)[0].cells[7].style.display ="none";
 });

});
</script>

<style>
#beingTable { cursor: pointer; }
.evenRow { background-color: #F5F5F5; }
.focusRow { background-color: #ffffcc; }

#alreadyTable { cursor: pointer; }
.evenRow { background-color: #F5F5F5; }
.focusRow { background-color: #ffffcc; }

</style>
@endsection
