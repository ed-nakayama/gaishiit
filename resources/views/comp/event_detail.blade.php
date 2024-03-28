@extends('layouts.comp.auth')

@section('content')

<head>
	<title>イベント管理｜{{ config('app.name', 'Laravel') }}</title>
</head>

{{--@include('comp.member_activity')--}}

            <div class="mainContentsInner">

                <div class="mainTtl title-main">
@if (strpos($event->person ,Auth::user()->id) !== false)
 <!--  修正  -->
                    <h2>イベント管理 - 詳細</h2>
 <!--  修正 END  -->
@else
<!--  参照  -->
                    <h2>イベント管理 - 参照</h2>
<!--  参照 END  -->
@endif
                </div><!-- /.mainTtl -->
                
                <div class="containerContents">

                    @if (strpos($event->person ,Auth::user()->id) !== false)
                    {{ Form::open(['url' => '/comp/event/change', 'name' => 'changeform' , 'id' => 'changeform']) }}
                    {{Form::hidden('event_id', old('event_id' ,$event->id), ['class' => 'form-control', 'id'=>'event_id' ] )}}
                    @endif
                    <section class="secContents-mb">
                        <div class="secContentsInner">
                            
                            <ul class="jobToggleList leftAlign">
                                <li>
									<div class="button-radio">
										<input id="c_ch1" class="radiobutton" name="open_flag" type="radio" value="1"  @if (old('open_flag' ,$event->open_flag) == '1')  checked="checked" @endif   @if (isset($event->id) && strpos($event->person ,Auth::user()->id) === false) disabled="disabled" @endif onchange="checkOpen()"  />
										<label for="c_ch1">公開</label> /
										<input id="c_ch2" class="radiobutton" name="open_flag" type="radio" value="0"  @if (old('open_flag' ,$event->open_flag) == '0')  checked="checked" @endif   @if (isset($event->id) && strpos($event->person ,Auth::user()->id) === false) disabled="disabled" @endif onchange="checkOpen()" />
										<label for="c_ch2">非公開</label> 
                                    </div>
                                </li>
                                <li>
                                    <label id="del_lavel"  for=""><span>削除する</span><input type="checkbox"  name="del_flag" id="del_flag" value="1"  @if (old('dell_flag' ,$event->del_flag) == '1')  checked="checked" @endif  @if (strpos($event->person ,Auth::user()->id) === false) disabled="disabled" @endif/></label>
                                </li>
								<li>
                            		<div class="btnContainer">
                              			@if (strpos($event->person ,Auth::user()->id) !== false)
                                			<a href="javascript:changeform.submit()" class="squareBtn btn-short">保存</a>
										@endif
                            		</div><!-- /.btn-container -->
								</li>
                            </ul><!-- /.jobToggle -->

			              	<div id="success1" class="alert alert-success"  style="color:#0000ff;text-align: center;">
					        	{{-- 更新成功メッセージ --}}
					        	@if (session('option_success'))
				            		<div id="success1" class="alert alert-success"  style="color:#0000ff;">
				             			{{session('option_success')}}
				            		</div>
					       		@endif
                            </div><!-- /.btn-container -->
                            
                        </div><!-- /.secContentsInner -->
                    </section><!-- /.secContents-mb -->

                     @if (strpos($event->person ,Auth::user()->id) !== false)
                    {{ Form::close() }}
                    @endif

                    <section class="secContents-mb">
                        {{ Form::open(['url' => '/comp/event/edit', 'name' => 'editform' ,'method' => 'GET' ]) }}
                        {{ Form::hidden('event_id', $event->id) }}
                        <div class="containerSeminarInfo">
                            <div class="seminarInfoTxt">
                                <p class="seminarLeader">{{ $event->unit_name }}</p>
                                <h2 class="seminarName">{{ $event->name }}</h2>
                                <div class="inner">
                                    <ul>
                                        <li class="venue">@if ($event->online_flag == '1')オンライン@else @if (empty($event->place))オフライン @else{{ $event->place }} @endif @endif</li>
                                        <li class="date">{{str_replace('-','/', substr($event->event_date, 0 ,10)) . '/' . $event->start_hour . ':' . $event->start_min . '〜' . $event->end_hour . ':' . $event->end_min }}</li>
                                    </ul>
                                </div><!-- /.inner -->
                                <p class="note">{!! nl2br(e($event->intro)) !!}</p>
                            </div><!-- /.seminarInfoTxt -->
                            <div class="seminarInfoImage">
                            @if ( isset($event->image) )
                                <img src="{{ $event->image }}">
                            @endif
                            </div><!-- /.seminarInfoImage -->
                        </div><!-- /.containerSeminarInfo -->
                        <div class="seminarInfoBtn">
                            <div class="btnContainer">
                             @if (strpos($event->person ,Auth::user()->id) !== false)
                                  <a href="javascript:editform.submit()" class="squareBtn btn-large">編集</a>
                             @else
                                   <a href="javascript:editform.submit()" class="squareGrayBtn btn-large">参照</a>
                              @endif
                            </div><!-- /.btn-container -->  
                        </div><!-- /.seminarInfoBtn -->
                        {{ Form::close() }}
                    </section><!-- /.secContents-mb -->

                    <section class="secContents">
                        <div class="secContentsInner">

                            <h2 class="contentsTitle">申込者</h2>

                             @if (strpos($event->person ,Auth::user()->id) !== false)
                            {{ Form::open(['url' => '/comp/event/aprove', 'name' => 'aproveform', 'id' => 'aproveform' ]) }}
	                        {{ Form::hidden('event_id', $event->id) }}
                            <div class="containerSelectAll">


                                <ul class="selectAllList mb-ajust">
                                    <li><label><input type="checkbox" name="all"  onClick="checkAll()"  value="1">すべて</label></li>
                                    <li>
                                        <div class="selectWrap">
                                            <select name="sel_aprove"  class="select-no">
                                                <option value=""></option>
                                                <option value="1">承認する</option>   
                                                <option value="2">否認する</option>   
                                                <option value="3">新規メッセージ</option>
                                            </select>
                                        </div>
                                        <ul class="oneRow">
                                        	@error('sel_aprove')
                                        		<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                                        	@enderror
                                        </ul>
                                    </li>

                                    <li>
                                        <div class="btnContainer">
                                            <div class="modalContainer">

                                                <a href="#modal" class="squareBtn btn-medium">保存</a>
{{-- モーダル --}}
                                                <div class="remodal" data-remodal-id="modal">
                                                    <div class="modalTitle">
                                                        <h2>申し込みを承認して<br>
                                                            以下のメッセージを送ります<br>
                                                            よろしいですか？</h2>
                                                    </div><!-- /.modalTitle -->
    
                                                    <div class="modalInner border-container">
                                                        <div class="border-container-text">
                                                            <textarea rows="10" cols="400" name="content" form="aproveform">この度は●●●●●セミナーへの参加申し込み、ありがとうございました。</textarea>
                                                        </div><!-- /.border-container-text -->
                                                    </div><!-- /.modalInner -->
                                                    
                                                    <div class="btnContainer">
                                                        <a href="javascript:aproveform.submit()" class="squareBtn btn-large">送信</a>
                                                    </div><!-- /.btn-container -->
                                                </div>
{{-- ENDモーダル --}}
                                            </div><!-- /.modalContainer -->
                                        </div><!-- /.btn-container -->
                                    </li>
                                </ul>
                            </div><!-- /.containerSelectAll -->
                             @endif


                            <table class="tbl-3th">
                                <tr>
                                    <th>ID</th>
                                    <th>申し込み日時</th>
                                    <th><span class="sortIcon">@sortablelink('aprove_flag', '承認 / 未承認')</span></th>
                                    <th>メッセージ</th>
                                </tr>

                                @foreach ($headList as $int)
                                <tr>
                                    <td><label><input type="checkbox" name="selUser[]" value="{{ $int->id }}"  onClick="DisChecked();">　{{ $int->nick_name }}</label></td>
                                    <td>{{ $int->created_at->format('Y/m/d/H:i') }}</td>
                                    <td>
                                       @if ($int->aprove_flag == '1')
                                            <span class="approval on">承認</span>
                                       @elseif ($int->aprove_flag == '2')
                                            <span class="approval off">否認</span>
                                       @else
                                            <span class="approval off">未承認</span>
                                       @endif
                                    </td>
                                    <td>
                             			@if (strpos($event->person ,Auth::user()->id) !== false)
                                    		@if ($int->aprove_flag == '1')
                                        		<div class="btnContainer">
                                            		<a href="/comp/interview/flow?interview_id={{ $int->id }}" class="squareBtn btn-medium">詳細</a>
                                        		</div><!-- /.btn-container -->
                                       		@endif
                                    	@endif
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            {{ Form::close() }}

						</div><!-- /.secContentsInner -->
					</section><!-- /.secContents -->
                   
				</div><!-- /.containerContents -->
			
			</div><!-- /.mainContentsInner -->


<script language="JavaScript" type="text/javascript">


/////////////////////////////////////////////////////////
// 成功メッセージクリア
/////////////////////////////////////////////////////////
function clearMsg() {

	const p1 = document.getElementById("success1");

	if (p1) p1.style.display ="none";
}


/////////////////////////////////////////////////////////
// 削除　表示／非表示
/////////////////////////////////////////////////////////
function delDisp() {

	var open_flag = document.getElementById("c_ch1");
	var del_flag = document.getElementById("del_flag");

	if (open_flag.checked) {
		del_flag.checked = false;
		del_lavel.style.display = "none";
	} else {
		del_lavel.style.display = "";
	}
}


/////////////////////////////////////////////////////////
// 公開フラグチェック
/////////////////////////////////////////////////////////
function checkOpen() {

	clearMsg();
	delDisp();
}



const checkbox1 = document.getElementsByName("selUser[]")

function checkAll() {

	var all = document.aproveform.all.checked;
    for (var i = 0; i < checkbox1.length; i++){
      checkbox1[i].checked = all;
    }
}


  // 一つでもチェックを外すと「全て選択」のチェック外れる
function　DisChecked(){
	
    var checksCount = 0;
    for (var i = 0; i < checkbox1.length; i++){
		if (checkbox1[i].checked == false) {
        	document.aproveform.all.checked = false;
      	} else {
			checksCount += 1;
        	if(checksCount == checkbox1.length){
          		document.aproveform.all.checked = true;
        	}
      	}
    }
}

/////////////////////////////////////////////////////////
// 初回起動
/////////////////////////////////////////////////////////
$(document).ready(function() {

	delDisp();

});

</script>

@endsection
