@extends('layouts.admin.auth')

@section('content')

<head>
	<title>ジョブ管理｜{{ config('app.name', 'Laravel') }}</title>
</head>


<style>
.scroll{
  height: 600px;
  overflow: auto;
}
</style>

<div class="mainContentsInner">
	<div class="mainTtl title-main">
		<h2>ジョブ管理 - 編集</h2>
		<h3>{{ $job->getCompanyName() }}</h3>
	</div><!-- /.mainTtl -->

	<div class="containerContents">
					{{ html()->form('POST', '/admin/mypage/job/change')->id('changeform')->attribute('name', 'changeform')->open() }}
					{{ html()->hidden('company_id', old('company_id' ,$job->company_id)) }}
					{{ html()->hidden('job_id', old('job_id' ,$job->id)) }}
                    <section class="secContents-mb">
                        <div class="secContentsInner">
                            
							<ul class="jobToggleList leftAlign">
								<li>
									<div class="button-radio">
										<input id="c_ch1" class="radiobutton" name="open_flag" type="radio" value="1"  @if (old('open_flag' ,$job->open_flag) == '1')  checked="checked" @endif  onchange="checkOpen()"  />
										<label for="c_ch1">表示</label> /
										<input id="c_ch2" class="radiobutton" name="open_flag" type="radio" value="0"  @if (old('open_flag' ,$job->open_flag) == '0')  checked="checked" @endif  onchange="checkOpen()" />
										<label for="c_ch2">非表示</label> 
									</div>
								</li>
								<li>
                                    <label id="del_lavel"  for=""><span>削除する</span><input type="checkbox"  name="del_flag" id="del_flag" value="1"  @if (old('dell_flag' ,$job->del_flag) == '1')  checked="checked" @endif /></label>
								</li>
								<li>
                            		<div class="btnContainer">
										<a href="javascript:changeform.submit()" class="squareBtn btn-short">表示設定保存</a>
                            		</div><!-- /.btn-container -->
								</li>
								<li>
                            		<div class="btnContainer">
										ID : {{ $job->id }}
                            		</div><!-- /.btn-container -->
								</li>
							</ul><!-- /.jobToggle -->
			              	<div id="success1" class="alert alert-success"  style="color:#0000ff;text-align: center;">
			               	{{-- 更新成功メッセージ --}}
			               	@if (session('option_success'))
								<p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-blue-400 dark:text-blue-400" style="color: blue;">{{session('option_success')}}</p>
			               	@endif
 		                   	</div>
                       </div><!-- /.secContentsInner -->
                    </section><!-- /.secContents-mb -->
					{{ html()->form()->close() }}

					{{ html()->form('POST', '/admin/mypage/job/post')->id('regform')->attribute('name', 'regform')->open() }}
					{{ html()->hidden('company_id', old('company_id' ,$job->company_id)) }}
					{{ html()->hidden('job_id', old('job_id' ,$job->id)) }}
					<section class="secContents">

                        <div class="secContentsInner">

								{{-- 更新成功メッセージ --}}
								@if (session('update_success'))
									<div class="formContainer mg-ajust">
										<div class="item-name">
											<p></p>
										</div><!-- /.item-name -->
										<p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-blue-400 dark:text-blue-400" style="color: blue;">{{session('update_success')}}</p>
									</div><!-- END formContainer mg-ajuse -->
								@endif

							<ul class="jobToggleList">
								<li  style="display:flex;">
										<label for="c_ch1">このジョブ宛にカジュアル面談を受け付ける　</label>
									<div class="button-radio">
										<input id="cas_ch1" class="radiobutton" name="casual_flag" type="radio" value="1"  @if (old('casual_flag' ,$job->casual_flag) == '1')  checked="checked" @endif />
										<label for="cas_ch1" style="padding:3px 10px;">はい</label> /
										<input id="cas_ch2" class="radiobutton" name="casual_flag" type="radio" value="0"  @if (old('casual_flag' ,$job->casual_flag) == '0')  checked="checked" @endif />
										<label for="cas_ch2" style="padding:3px 10px;">いいえ</label> 
									</div>
								</li>
							</ul><!-- /.jobToggle -->

							<div class="formContainer mg-ajust-midashi">
								<div class="item-name">
									<p>更新日</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									{{ $job->updated_at }}
								</div><!-- /.item-input -->
							</div><!-- END formContainer mg-ajuse -->

							@if ( isset($unitList[0]) )
								<div class="formContainer mg-ajust">
									<div class="item-name">
										<p>部門</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<div class="selectWrap harf">
											<select name="unit"  class="select-no">
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
									<input type="text"  name="name"  value="{{ old('name' ,$job->name) }}">
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
									<textarea class="form-mt" name="intro" id="" cols="30" rows="10" placeholder="テキスト">{{ old('intro' ,$job->intro) }}</textarea>
									<ul class="oneRow">
										@error('intro')
										<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
										@enderror
									</ul>
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->

<div class="scroll">
							<div class="formContainer mg-ajust">
								<div class="item-name">
									<p>一般/portal</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									@if ($job->portal_flag == '1') portal @else 一般 @endif
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->

							<div class="formContainer mg-ajust">
								<div class="item-name">
									<p>URL</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<a href="{{ $job->url }}" style="text-decoration:underline; color:blue;" target="_blank">{{ $job->url }}</a>
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->

							<div class="formContainer mg-ajust">
								<div class="item-name">
									<p>ジョブID</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<input class="harf" name="job_code" type="text" value="{{ old('job_code' ,$job->job_code) }}" >
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
										<div style="font-size:16px; font-weight: bold;">{{ $cat->name }}　
											<label>{{ html()->checkbox("jobcat_parent[]", 0, $cat->id)->id("jobcat_parent")->class("jobcat_parent{$cat->id}") }}<span style="font-weight:bold;">全て</span></label>
										</div>
										<div style="display:flex;flex-wrap: wrap;">
											@foreach ($jobCatDetail as $detail)
												@if ($cat->id == $detail->job_cat_id)
													<div style="margin-left: 15px;">
														@if (!empty($job->getJobCategory() ))
															{{ html()->checkbox('jobCat[]', (in_array($detail->id, old("jobCat", $job->getJobCategory()) )), $detail->id)->class("jobCat{$cat->id}")->attribute('onClick', (Auth::user()->agent_priv == '1') ? 'return false;' : 'return true;') }}{{ $detail->name }}
														@else
															@if (!empty(old("jobCat")))
																{{ html()->checkbox('jobCat[]', (in_array($detail->id, old("jobCat") )), $detail->id)->class("jobCat{$cat->id}")->attribute('onClick', (Auth::user()->agent_priv == '1') ? 'return false;' : 'return true;') }}{{ $detail->name }}
															@else
																{{ html()->checkbox('jobCat[]', false, $detail->id)->class("jobCat{$cat->id}")->attribute('onClick', (Auth::user()->agent_priv == '1') ? 'return false;' : 'return true;') }}{{ $detail->name }}
															@endif
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
										<p>担当業界カテゴリ</p>

									</div><!-- /.item-name -->
									<div class="item-input">
										{{ $job->getIndCatName() }}
										<hr>
										<label>{{ html()->checkbox("industory_parent", 0)->id("industory_parent") }}<span style="font-weight:bold;">全て</span></label>
										<br>
									</div><!-- /.item-input -->
								</div>

							<div class="formContainer mg-ajust">
								<div class="item-name">
									<p>担当業界</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									@foreach ($industoryCat as $cat)
										<div style="font-size:16px; font-weight: bold;">
											<div style="margin-left: 15px;">
												@if (!empty($job->getIndcatCat() ))
													{{ html()->checkbox('indCat[]', (in_array($cat->id, old("indCat", $job->getIndcatCat()) )), $cat->id)->class('industory_checks')->attribute('onClick', (Auth::user()->agent_priv == '1') ? 'return false;' : 'return true;') }}{{ $cat->name }}
												@else
													@if (!empty(old("indCat")))
														{{ html()->checkbox('indCat[]', (in_array($detail->id, old("indCat") )), $cat->id)->class('industory_checks')->attribute('onClick', (Auth::user()->agent_priv == '1') ? 'return false;' : 'return true;') }}{{ $cat->name }}
													@else
														{{ html()->checkbox('indCat[]', false, $cat->id)->class('industory_checks')->attribute('onClick', (Auth::user()->agent_priv == '1') ? 'return false;' : 'return true;') }}{{ $cat->name }}
													@endif
												@endif
											</div>
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
										<select name="income_id"  class="select-no">
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
									<input class="long"  name="sub_category" type="text" value="{{ old('sub_category' ,$job->sub_category) }}">
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->

							<div class="formContainer mg-ajust-midashi">
								<div class="item-name">
									<p>ロケーション<span>*</span></p>
								</div><!-- /.item-name -->
								<div class="item-input">

									<ul class="radioList">
										@foreach ($constLocation as $loc)
											<li><label>
												@if (!empty($job->getLocationArray() ))
													{{ html()->checkbox('locations[]', (in_array($loc->id, old("locations", $job->getLocationArray()) )), $loc->id)->attribute('onchange', "elseChange()")->attribute('onClick', (Auth::user()->agent_priv == '1') ? 'return false;' : 'return true;') }}<span>{{ $loc->name }}</span>
												@else
													@if (!empty(old("locations")))
														{{ html()->checkbox('locations[]', (in_array($loc->id, old("locations") )), $loc->id)->attribute('onchange', "elseChange()")->attribute('onClick', (Auth::user()->agent_priv == '1') ? 'return false;' : 'return true;') }}<span>{{ $loc->name }}</span>
													@else
														{{ html()->checkbox('locations[]', false, $loc->id)->attribute('onchange', "elseChange()")->attribute('onClick', (Auth::user()->agent_priv == '1') ? 'return false;' : 'return true;') }}<span>{{ $loc->name }}</span>
													@endif
												@endif
											</label></li>
										@endforeach
											<li><label>　／　{{ html()->checkbox('remote', old('remote' ,$job->remote_flag), "1")->attribute('onchange', "locChange()")->attribute('onClick', (Auth::user()->agent_priv == '1') ? 'return false;' : 'return true;') }}<span>リモート</span></label></li>
											<li><label>　／　{{ html()->checkbox('no_auto_flag', old('no_auto_flag' ,$job->no_auto_flag), "1")->attribute('onClick', (Auth::user()->agent_priv == '1') ? 'return false;' : 'return true;') }}<span>自動修正対象外</span></label></li>
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
									<input type="text" name="else_location" id="else_location" value="{{  old('else_location',$job->else_location) }}"  class="long" >
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
										<li><label>{{ html()->checkbox('backg_flag', old('backg_flag' ,$job->backg_flag), "1")->attribute('onClick', (Auth::user()->agent_priv == '1') ? 'return false;' : 'return true;') }}職務経歴書</label></li>
										<li><label>{{ html()->checkbox('backg_eng_flag', old('backg_eng_flag' ,$job->backg_eng_flag), "1")->attribute('onClick', (Auth::user()->agent_priv == '1') ? 'return false;' : 'return true;') }}職務経歴書（英文）</label></li>
										<li><label>{{ html()->checkbox('personal_flag', old('personal_flag' ,$job->personal_flag), "1")->attribute('onClick', (Auth::user()->agent_priv == '1') ? 'return false;' : 'return true;') }}履歴書</label></li>
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
									{{ html()->hidden('person', old('person' ,$job->person)) }}
									<span id="member_text" class="border border-secondary border-5 bg-white" style="padding-right: 15px;"></span>
									<ul class="oneRow">
										@error('person')
											<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
										@enderror
									</ul>
								</div><!-- /.item-input -->
							</div>

								<font color='red'>※ChatGPTで使用</font><br>

								<div class="formContainer al-item-none mg-ajust">
									<div class="item-name">
										<p>仕事内容</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<textarea class="form-mt" name="app_contents" id="" cols="30" rows="10" placeholder="テキスト">{{ old('app_contents' ,$job->app_contents) }}</textarea>
									</div><!-- /.item-input -->
								</div><!-- END formContainer -->

								<div class="formContainer al-item-none mg-ajust">
									<div class="item-name">
										<p>募集要項</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<textarea class="form-mt" name="app_details" id="" cols="30" rows="10" placeholder="テキスト">{{ old('app_details' ,$job->app_details) }}</textarea>
									</div><!-- /.item-input -->
								</div><!-- END formContainer -->


</div>{{-- END scroll --}}
								<div class="btnContainer">
								{{-- 更新成功メッセージ --}}
									@if (session('update_success'))
										<p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-blue-400 dark:text-blue-400" style="color: blue;">{{session('update_success')}}</p>
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

/*********************************
/* 職種チェックボックス制御  *
**********************************/
	@foreach ($jobCat as $cat)
		//全選択・解除のチェックボックス
		let jobcat_all{{ $cat->id }} = document.querySelector(".jobcat_parent{{ $cat->id }}");
		//チェックボックスのリスト
		let jobcat_list{{ $cat->id }} = document.querySelectorAll(".jobCat{{ $cat->id }}");

		//全選択のチェックボックスイベント
		jobcat_all{{ $cat->id }}.addEventListener('change', jobcat_change_all{{ $cat->id }});

		function jobcat_change_all{{ $cat->id }}() {
			//チェックされているか
			if (jobcat_all{{ $cat->id }}.checked) {
				//全て選択
				for (let i in jobcat_list{{ $cat->id }}) {
					if (jobcat_list{{ $cat->id }}.hasOwnProperty(i)) {
						jobcat_list{{ $cat->id }}[i].checked = true;
					}
				}
				
			} else {
				//全て解除
				for (let i in jobcat_list{{ $cat->id }}) {
					if (jobcat_list{{ $cat->id }}.hasOwnProperty(i)) {
						jobcat_list{{ $cat->id }}[i].checked = false;
					}
				}
			}
		};

	@endforeach


/*********************************
/* 担当業界チェックボックス制御  *
**********************************/
	const industory_parent = document.getElementById("industory_parent");
	const industory_checks = document.querySelectorAll(".industory_checks");
	// 全て選択のチェックボックスがクリックされた時
	industory_parent.addEventListener('click', () => {
		for (val of industory_checks) {
			industory_parent.checked == true ? val.checked = true : val.checked = false;
		}
	});

	// 個別のチェックボックスがクリックされた時
	industory_checks.forEach(element => {
		element.addEventListener('click', () => {
			// チェックが1つでも外された時
			if (element.checked == false) {
				industory_parent.checked = false;
			}
			// 全てにチェックがされた時
			if (document.querySelectorAll(".industory_checks:checked").length == industory_checks.length) {
				industory_parent.checked = true;
			}
		});
	});


/////////////////////////////////////////////////////////
// その他ロケーション表示
/////////////////////////////////////////////////////////
function elseChange() {

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
// 削除　表示／非表示
/////////////////////////////////////////////////////////
/*
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
*/

/////////////////////////////////////////////////////////
// 公開フラグチェック
/////////////////////////////////////////////////////////
function checkOpen() {

//	delDisp();
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
//	delDisp();
	elseChangeCont();
 });

</script>

@endsection
