@extends('layouts.admin.auth')

@section('content')

<head>
	<title>ジョブ管理｜{{ config('app.name', 'Laravel') }}</title>
</head>


            <div class="mainContentsInner">

                <div class="mainTtl title-main">
                    <h2>ジョブ管理 - 編集</h2>
                </div><!-- /.mainTtl -->

                <div class="containerContents">

                    {{ Form::open(['url' => '/admin/mypage/job/change', 'name' => 'changeform' , 'id' => 'changeform']) }}
					{{ Form::hidden('company_id', old('company_id' ,$job->company_id), ['class' => 'form-control', 'id'=>'company_id_id' ] )}}
                    {{ Form::hidden('job_id', old('job_id' ,$job->id), ['class' => 'form-control', 'id'=>'job_id' ] )}}
                    <section class="secContents-mb">
                        <div class="secContentsInner">
                            
							<ul class="jobToggleList leftAlign">
								<li>
									<div class="button-radio">
										<input id="c_ch1" class="radiobutton" name="open_flag" type="radio" value="1"  @if (old('open_flag' ,$job->open_flag) == '1')  checked="checked" @endif  onchange="checkOpen()"  />
										<label for="c_ch1">公開</label> /
										<input id="c_ch2" class="radiobutton" name="open_flag" type="radio" value="0"  @if (old('open_flag' ,$job->open_flag) == '0')  checked="checked" @endif  onchange="checkOpen()" />
										<label for="c_ch2">非公開</label> 
									</div>
								</li>
								<li>
                                    <label id="del_lavel"  for=""><span>削除する</span><input type="checkbox"  name="del_flag" id="del_flag" value="1"  @if (old('dell_flag' ,$job->del_flag) == '1')  checked="checked" @endif  onchange="clearMsg()" /></label>
								</li>
								<li>
                            		<div class="btnContainer">
									<a href="javascript:changeform.submit()" class="squareBtn btn-short">保存</a>
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
 		                   	</div>
                       </div><!-- /.secContentsInner -->
                    </section><!-- /.secContents-mb -->
					{{ html()->form()->close() }}

					{{ Form::open(['url' => '/admin/mypage/job/post', 'name' => 'regform' , 'id' => 'regform']) }}
					{{ Form::hidden('company_id', old('company_id' ,$job->company_id), ['class' => 'form-control', 'id'=>'company_id_id' ] )}}
					{{ Form::hidden('job_id', old('job_id' ,$job->id), ['class' => 'form-control', 'id'=>'job_id' ] )}}
					<section class="secContents">

                        <div class="secContentsInner">

							@if (!isset($job->id) || strpos($job->person ,Auth::user()->id) !== false)
								{{-- 更新成功メッセージ --}}
								@if (session('update_success'))
									<div class="formContainer mg-ajust">
										<div class="item-name">
											<p></p>
										</div><!-- /.item-name -->

										<div id="success2" class="alert alert-success"  style="color:#0000ff;">
									 		{{session('update_success')}}
										</div>
									</div><!-- END formContainer mg-ajuse -->
								@endif
							@endif

							<ul class="jobToggleList">
								<li  style="display:flex;">
										<label for="c_ch1">このジョブ宛にカジュアル面談を受け付ける　</label>
									<div class="button-radio">
										<input id="cas_ch1" class="radiobutton" name="casual_flag" type="radio" value="1"  @if (old('casual_flag' ,$job->casual_flag) == '1')  checked="checked" @endif  onchange="clearMsg()" />
										<label for="cas_ch1" style="padding:3px 10px;">はい</label> /
										<input id="cas_ch2" class="radiobutton" name="casual_flag" type="radio" value="0"  @if (old('casual_flag' ,$job->casual_flag) == '0')  checked="checked" @endif  onchange="clearMsg()" />
										<label for="cas_ch2" style="padding:3px 10px;">いいえ</label> 
									</div>
								</li>
							</ul><!-- /.jobToggle -->

							@if ( isset($unitList[0]) )
								<div class="formContainer mg-ajust">
									<div class="item-name">
										<p>部門</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<div class="selectWrap harf">
											<select name="unit"  class="select-no"  onchange="clearMsg()">
												<option value=""></option>
												@foreach ($unitList as $un)
													<option value="{{ $un->id }}" @if (old('unit' ,$job->unit_id) == $un->id)  selected @endif>{{ $un->name }}</option>
												@endforeach
											</select>
										</div>
									<ul class="oneRow">
										@error('unit')
											<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
										@enderror
										</ul>
									</div><!-- /.item-input -->
								</div><!-- END formContainer mg-ajuse -->
							@endif
                                
							<div class="formContainer mg-ajust-midashi">
								<div class="item-name">
									<p>名称<span>*</span></p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<input type="text"  name="name"  value="{{ old('name' ,$job->name) }}"  oninput="clearMsg()">
									<ul class="oneRow">
										@error('name')
											<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
										@enderror
									</ul>
								</div><!-- /.item-input -->
							</div><!-- END formContainer mg-ajuse -->
                                
							<div class="formContainer al-item-none mg-ajust">
								<div class="item-name">
									<p>紹介<span>*</span></p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<textarea class="form-mt" name="intro" id="" cols="30" rows="10" placeholder="テキスト"  oninput="clearMsg()">{{ old('intro' ,$job->intro) }}</textarea>
									<ul class="oneRow">
										@error('intro')
										<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
										@enderror
									</ul>
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->
                                
							<div class="formContainer mg-ajust">
								<div class="item-name">
									<p>ジョブID</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<input class="harf" name="job_code" type="text" value="{{ old('job_code' ,$job->job_code) }}"   oninput="clearMsg()">
									<ul class="oneRow">
										@error('job_code')
											<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
										@enderror
									</ul>
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>職種カテゴリ</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										{{ $job->getJobCatName() }}
										<hr>
										<br>
									</div><!-- /.item-input -->
								</div>

							<div class="formContainer mg-ajust">
								<div class="item-name">
									<p>職種</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									@foreach ($jobCat as $cat)
										<div style="font-size:16px; font-weight: bold;">{{ $cat->name }}</div>
										<div style="display:flex;flex-wrap: wrap;">
											@foreach ($jobCatDetail as $detail)
												@if ($cat->id == $detail->job_cat_id)
													<div style="margin-left: 15px;">
														@if (!empty($job->getJobCategory() ))
															{{ html()->checkbox('jobCat[]', (in_array($detail->id, $job->getJobCategory() )), $detail->id) }}{{ $detail->name }}
														@else
															{{ html()->checkbox('jobCat[]', false, $detail->id) }}{{ $detail->name }}
														@endif
													</div>
												@endif
											@endforeach
										</div>
									@endforeach
									<ul class="oneRow">
										@error('jobCat[]')
											<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
										@enderror
									</ul>
									<hr>
									<br>
								</div><!-- /.item-input -->
							</div>

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>担当業種カテゴリ</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										{{ $job->getIndCatName() }}
										<hr>
										<br>
									</div><!-- /.item-input -->
								</div>

							<div class="formContainer mg-ajust">
								<div class="item-name">
									<p>担当業界</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									@foreach ($industoryCat as $cat)
										<div style="font-size:16px; font-weight: bold;">{{ $cat->name }}</div>
										<div style="display:flex;flex-wrap: wrap;">
											@foreach ($industoryCatDetail as $detail)
												@if ($cat->id == $detail->industory_cat_id)
													<div style="margin-left: 15px;">
														@if (!empty($job->getIndustory() ))
															{{ html()->checkbox('indCat[]', (in_array($detail->id, $job->getIndustory() )), $detail->id) }}{{ $detail->name }}
														@else
															{{ html()->checkbox('indCat[]', false, $detail->id) }}{{ $detail->name }}
														@endif
													</div>
												@endif
											@endforeach
										</div>
									@endforeach
									<ul class="oneRow">
										@error('jobCat[]')
											<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
										@enderror
									</ul>
									<hr>
									<br>
								</div><!-- /.item-input -->
							</div>

							<div class="formContainer mg-ajust">
								<div class="item-name">
									<p>年収</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<div class="selectWrap">
										<select name="income_id"  class="select-no"  onchange="clearMsg()">
											<option value=""></option>
											@foreach ($incomeList as $income)
												<option value="{{ $income->id }}" @if (old('income_id' ,$job->income_id) == $income->id)  selected @endif>{{ $income->name }}</option>
											@endforeach
										</select>
									</div>
									<ul class="oneRow">
										@error('income_id')
											<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
										@enderror
									</ul>
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->
                                
							<div class="formContainer mg-ajust">
								<div class="item-name">
									<p>補足カテゴリ</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<input class="long"  name="sub_category" type="text" value="{{ old('sub_category' ,$job->sub_category) }}" oninput="clearMsg()">
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->
                                
							<div class="formContainer mg-ajust-midashi">
								<div class="item-name">
									<p>ロケーション<span>*</span></p>
								</div><!-- /.item-name -->
								<div class="item-input">

									<ul class="radioList">
										@foreach ($constLocation as $loc)
											<li><label><input type="checkbox" value="{{ $loc->id }}" name="locations[]"  @if (strpos($job->locations ,$loc->id) !== false) checked @endif  onchange="elseChange()"><span>{{ $loc->name }}</span></label></li>
										@endforeach
											<li><label>　／　<input type="checkbox" value="1" id="remote" name="remote"  @if ($job->remote_flag == '1') checked @endif onchange="locChange()"><span>リモート</span></label></li>
											<li><label>　：　<input type="checkbox" value="1" id="no_auto_flag" name="no_auto_flag"  @if ($job->no_auto_flag == '1') checked @endif"><span>自動修正対象外</span></label></li>
									</ul><!-- /.radioList -->

									<ul class="oneRow">
										@error('locations')
											<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
										@enderror
									</ul>
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->
                                
							<div class="formContainer mg-ajust" id="changeElseLocation">
								<div class="item-name">
									<p>その他ロケーション</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<input type="text" name="else_location" id="else_location" value="{{  old('else_location',$job->else_location) }}"  class="long">
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->

							<div class="formContainer al-item-none mg-ajust">
								<div class="item-name">
									<p>勤務地詳細/その他</p>
								</div><!-- /.item-name -->
								<div class="item-input" id="changeWorking_place">
									<textarea class="form-mt" name="working_place" id="" cols="30" rows="3">{{ old('working_place' ,$job->working_place) }}</textarea>
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->
                                
							<div class="formContainer bb-ajust">
								<div class="item-name">
									<p>正式応募に必要<br>な書類</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<ul class="checkboxList">
										<li><label><input type="checkbox" name="backg_flag" value="1" @if (old('backg_flag' ,$job->backg_flag) == '1')  checked="checked" @endif  onchange="clearMsg()">職務経歴書</label></li>
										<li><label><input type="checkbox" name="backg_eng_flag" value="1" @if (old('backg_eng_flag',$job->backg_eng_flag) == '1')  checked="checked" @endif onchange="clearMsg()">職務経歴書（英文）</label></li>
										<li><label><input type="checkbox" name="personal_flag" value="1" @if (old('personal_flag' ,$job->personal_flag) == '1')  checked="checked" @endif  onchange="clearMsg()">履歴書</label></li>
									</ul><!-- /.checkboxList -->
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->
                                
                                <div class="formContainer bb-ajust">
                                    <div class="item-name">
                                        <p>担当<span>*</span></p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input item-input-row">
                                        <div class="item-input-btn">
                                            
                                            <div class="modalContainer">
                                                <a href="#modal" class="squareBtn btn-medium">選択</a>
                                            </div><!-- /.modalContainer -->
                                        </div>
                                        {{Form::hidden('person', old('person' ,$job->person), ['class' => 'form-control', 'id'=>'person' ] )}}
                                        <span id="member_text" class="border border-secondary border-5 bg-white" style="padding-right: 15px;"></span>
                                        <ul class="oneRow">
                                            @error('person')
                                                <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                                            @enderror
                                        </ul>
                                    </div><!-- /.item-input -->
                                </div>

                                <div class="btnContainer">
			                		{{-- 更新成功メッセージ --}}
			                		@if (session('update_success'))
		                    			<div id="success3" class="alert alert-success"  style="color:#0000ff;">
		                      		 		{{session('update_success')}}
		                    			</div>
			                		@endif
                                    <a href="javascript:regform.submit()" class="squareBtn btn-large">保存</a>
                                </div><!-- /.btn-container -->
							{{ html()->form()->close() }}
						</div><!-- /.secContentsInner -->
					</section><!-- /.secContents -->
                   
				</div><!-- /.containerContents -->
			
			</div><!-- /.mainContentsInner -->


{{-- 担当　モーダル領域   --}}

	<div class="remodal" data-remodal-id="modal">
		<div class="modalTitle">
			<h2>担当者を選択してください</h2>
		</div><!-- /.modalTitle -->
    
		<div class="modalInner bb-ajust">
			<ul class="list">
				@foreach ($memberList as $mem)
					<li>
						<input type="checkbox" id="mem_select" name="mem_sel[]"  title="{{$mem['name']}}" value="{{$mem['id']}}"><label> {{$mem['name']}}<span> {{$mem['email']}}</span></label>
					</li>
				@endforeach
			</ul><!-- /.list -->
		</div><!-- /.modalInner -->

		<div class="btnContainer">
			<a href="javascript:void(0);" onclick="GetPerson()" class="squareBtn btn-large">設定</a>
		</div><!-- /.btn-container -->
	</div>

{{-- END担当　モーダル領域   --}}

<script>


/////////////////////////////////////////////////////////
// その他ロケーション表示
/////////////////////////////////////////////////////////
function elseChange() {

	clearMsg();

	elseChangeCont();
}


/////////////////////////////////////////////////////////
// その他ロケーション表示
/////////////////////////////////////////////////////////
function elseChangeCont() {

	var else_flag = '0';
	boxes = document.getElementsByName("locations[]");
	var cnt = boxes.length;

	for (var i = 0; i < cnt; i++) {
		if (boxes[i].checked) {
			if (boxes[i].value == 99) else_flag = '1';
		}
	}

	if (else_flag == '0') {
		document.getElementById( "else_location" ).value = "";
		changeElseLocation.style.display = "none";
	} else {
		changeElseLocation.style.display = "";
	}

}


/////////////////////////////////////////////////////////
// リモート選択
/////////////////////////////////////////////////////////
function locChange() {

	clearMsg();

	let remote = document.getElementById("remote");
	boxes = document.getElementsByName("locations[]");
	var cnt = boxes.length;

	if (remote.checked) {
		for (var i = 0; i < cnt; i++) {
			boxes[i].checked = true;
		}
		changeElseLocation.style.display = "";
    }

}


/////////////////////////////////////////////////////////
// 成功メッセージクリア
/////////////////////////////////////////////////////////
function clearMsg() {

	const p1 = document.getElementById("success1");
	const p2 = document.getElementById("success2");
	const p3 = document.getElementById("success3");

	if (p1) p1.style.display ="none";
	if (p2) p2.style.display ="none";
	if (p3) p3.style.display ="none";

}


/////////////////////////////////////////////////////////
// 削除　表示／非表示
/////////////////////////////////////////////////////////
function delDisp() {

	var open_flag = document.getElementById("c_ch1");
	var del_flag = document.getElementById("del_flag");

	if (del_flag) {
		if (open_flag.checked) {
			del_flag.checked = false;
			del_lavel.style.display = "none";
		} else {
			del_lavel.style.display = "";
		}
	}
}


/////////////////////////////////////////////////////////
// 公開フラグチェック
/////////////////////////////////////////////////////////
function checkOpen() {

	clearMsg();
	delDisp();
}



/////////////////////////////////////////////////////////
// メインに担当セット
/////////////////////////////////////////////////////////
function putPerson() {


    var works = $("input[id='mem_select']:checked").map(function() {

        return {
			'title': this.title,
			'val': this.value
		}
	});

//	console.log("hogehoge");

    var vals = new Array();
    var titles = new Array();

	for (let i = 0; i < works.length; ++i) {
		vals[i] = works[i]['val'];
		titles[i] = works[i]['title'];
	};

    var valList = $.makeArray(vals).join(',');
    var titleList = $.makeArray(titles).join('／');


    if (valList == ""){
        $("#member_text").html("");
        document.getElementById( "person" ).value = "" ;
    }else{
        $("#member_text").text(titleList);
        document.getElementById( "person" ).value = valList;
    }
}


/////////////////////////////////////////////////////////
// 業種選択モーダルからの戻り
/////////////////////////////////////////////////////////
function GetPerson() {

	clearMsg();
	putPerson();
	ResetPerson();

	var modal = $.remodal.lookup[$('[data-remodal-id=modal]').data('remodal')];
    modal.close();
}


/////////////////////////////////////////////////////////
// 担当モーダル設定
/////////////////////////////////////////////////////////
function ResetPerson() {

	var persons = document.getElementById("person").value;

	boxes = document.getElementsByName("mem_sel[]");
	var cnt = boxes.length;
	var inx = 0;

	if (persons != null) {
		var bus_list = persons.split(',');

		for (var i = 0; i < cnt; i++) {
			for (const element of bus_list) {
				if (boxes[i].value == element) {
					boxes[i].checked = true;
				}
			}
		}
    }
}


/////////////////////////////////////////////////////////
// 初回起動
/////////////////////////////////////////////////////////
$(document).ready(function() {

	// 担当対応
	ResetPerson();
	putPerson();
	delDisp();
	elseChangeCont();
 
});

</script>

@endsection
