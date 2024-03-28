@extends('layouts.comp.auth')

@section('content')

<head>
	<title>部門設定｜{{ config('app.name', 'Laravel') }}</title>
</head>

{{--@include('comp.member_activity')--}}

            <div class="mainContentsInner">

				<div class="mainTtl title-main">
					@if ( !isset($unit->id) )
						<h2>部門設定 - 新規作成</h2>
					@elseif ( strpos($unit->person ,Auth::user()->id) !== false)
                    	<h2>部門設定 - 編集</h2>
					@else
                    	<h2>部門設定 - 参照</h2>
					@endif
                </div><!-- /.mainTtl -->

                <div class="containerContents">
@if (!session('comp_admin'))
@if ( isset($unit->id) )
 <!--  修正  -->
                    {{ Form::open(['url' => '/comp/unit/change', 'name' => 'changeform' , 'id' => 'changeform']) }}
                    {{Form::hidden('unit_id', old('unit_id' ,$unit->id), ['class' => 'form-control', 'id'=>'unit_id' ] )}}
                    <section class="secContents-mb">
                        <div class="secContentsInner">
                            
                            <ul class="jobToggleList leftAlign">
                                <li>
									<div class="button-radio">
										<input id="c_ch1" class="radiobutton" name="open_flag" type="radio" value="1"  @if (old('open_flag' ,$unit->open_flag) == '1')  checked="checked" @endif   @if (isset($unit->id) && strpos($unit->person ,Auth::user()->id) === false) disabled="disabled" @endif onchange="checkOpen()"  />
										<label for="c_ch1">公開</label> /
										<input id="c_ch2" class="radiobutton" name="open_flag" type="radio" value="0"  @if (old('open_flag' ,$unit->open_flag) == '0')  checked="checked" @endif   @if (isset($unit->id) && strpos($unit->person ,Auth::user()->id) === false) disabled="disabled" @endif onchange="checkOpen()" />
										<label for="c_ch2">非公開</label> 
									</div>
                                </li>
                                <li>
                                    <label id="del_lavel" for=""><span>削除する</span><input type="checkbox"  name="del_flag" id="del_flag" value="1"  @if (old('del_flag' ,$unit->del_flag) == '1')  checked="checked" @endif  @if (isset($unit->id) &&  strpos($unit->person ,Auth::user()->id) === false) disabled="disabled" @endif onchange="clearMsg()"/></label>
                                </li>
								@if (!isset($unit->id) ||  strpos($unit->person ,Auth::user()->id) !== false)
                                <a href="javascript:changeform.submit()" class="squareBtn btn-short">保存</a>
                              @else
				                @if (session('update_success'))
    	                            <a href="/comp/unit" class="squareBtn btn-large">戻る</a>
                                @else
	                                <a href="javascript:history.back();" class="squareBtn btn-large">戻る</a>
                                @endif
                              @endif
								<li>
                            		<div class="btnContainer">
                            		</div><!-- /.btn-container -->
                                </li>
                            </ul><!-- /.unitToggle -->

                            <div class="btnContainer">
				                {{-- 更新成功メッセージ --}}
				                @if (session('option_success'))
			                    	<div id="success1"  class="alert alert-success"  style="color:#0000ff;text-align: center;">
			                      		{{session('option_success')}}
			                    	</div>
				                @endif
                            </div><!-- /.btn-container -->
                            
                        </div><!-- /.secContentsInner -->
                    </section><!-- /.secContents-mb -->
                    {{ Form::close() }}
<!--  修正 END  -->
@endif
@endif {{-- END (!session('comp_admin')))  --}}
                    {{ Form::open(['url' => '/comp/unit/register', 'name' => 'regform' , 'id' => 'regform', 'files' => true]) }}
                    {{Form::hidden('unit_id', old('unit_id' ,$unit->id), ['class' => 'form-control', 'id'=>'unit_id' ] )}}

                    <section class="secContents">
                        <div class="secContentsInner">

								<div class="formContainer mg-ajust">
									@if (!isset($unit->id) ||  strpos($unit->person ,Auth::user()->id) !== false)
                                    <div class="item-name">
                                        <p></p>
                                    </div><!-- /.item-name -->
				                		{{-- 更新成功メッセージ --}}
				                		@if (session('update_success'))
			                    		<div id="success2" class="alert alert-success"  style="color:#0000ff;">
			                        		{{session('update_success')}}
			                    		</div>
				                		@endif
                              		@endif
								</div>

							<ul class="jobToggleList">
								<li  style="display:flex;">
									<label for="c_ch_cas">この部門宛にカジュアル面談を受け付ける</label>
									<div class="button-radio">
										<input id="cas_ch1" class="radiobutton" name="casual_flag" type="radio" value="1"  @if (old('casual_flag' ,$unit->casual_flag) == '1')  checked="checked" @endif  @if (isset($unit->id) &&  strpos($unit->person ,Auth::user()->id) === false) disabled="disabled" @endif  onchange="clearMsg()" />
										<label for="cas_ch1" style="padding:3px 10px;">はい</label> /
										<input id="cas_ch2" class="radiobutton" name="casual_flag" type="radio" value="0"  @if (old('casual_flag' ,$unit->casual_flag) == '0')  checked="checked" @endif  @if (isset($unit->id) &&  strpos($unit->person ,Auth::user()->id) === false) disabled="disabled" @endif  onchange="clearMsg()"/>
										<label for="cas_ch2" style="padding:3px 10px;">いいえ</label> 
									</div>
								</li>
							</ul><!-- /.jobToggle -->
                        
								@if ( isset($unitList[0]) )
								<div class="formContainer mg-ajust">
									<div class="item-name">
										<p>部門<span>*</span></p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<div class="selectWrap harf">
											<select name="unit"  class="select-no"  @if (isset($unit->id) &&  strpos($unit->person ,Auth::user()->id) !== false) disabled="disabled" @endif onchange="clearMsg()" >
												<option value=""></option>
												@foreach ($unitList as $un)
													<option value="{{ $un->id }}" @if (old('unit' ,$unit->unit_id) == $un->id)  selected @endif>{{ $un->name }}</option>
												@endforeach
											</select>
										</div>
										<ul class="oneRow">
											@error('unit')
											<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
										</ul>
									</div><!-- /.item-input -->
								</div>
								@endif
                                
                                <div class="formContainer mg-ajust-midashi">
                                    <div class="item-name">
                                        <p>部門名<span>*</span></p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input">
                                        <input type="text"  name="name"  value="{{ old('name' ,$unit->name) }}" @if (isset($unit->id) &&  strpos($unit->person ,Auth::user()->id) === false) disabled="disabled" @endif  oninput="clearMsg()" >
                                <ul class="oneRow">
                                    @error('name')
                                        <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                                    @enderror
                                </ul>
                                    </div><!-- /.item-input -->
                                </div>
                                <div class="formContainer al-item-none mg-ajust">
                                    <div class="item-name">
                                        <p>紹介<span>*</span></p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input">
                                        <textarea class="form-mt" name="intro" id="" cols="30" rows="10" placeholder="テキスト" @if (isset($unit->id) &&  strpos($unit->person ,Auth::user()->id) === false) disabled="disabled" @endif oninput="clearMsg()">{{ old('intro' ,$unit->intro) }}</textarea>
                                <ul class="oneRow">
                                    @error('intro')
                                        <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                                    @enderror
                                </ul>
                                    </div><!-- /.item-input -->
                                </div>

                                <div class="formContainer bb-ajust">
                                    <div class="item-name">
                                        <p>担当<span>*</span></p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input item-input-row">
                                        <div class="item-input-btn">
                                            
											<div class="modalContainer">
												@if (!isset($unit->id) ||  strpos($unit->person ,Auth::user()->id) !== false)
                                                	<a href="#modal" class="squareBtn btn-medium">選択</a>
                                            	@endif
											</div><!-- /.modalContainer -->
                                       </div>
                                        {{Form::hidden('person', old('person' ,$unit->person), ['class' => 'form-control', 'id'=>'person' ] )}}
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
									@if (!isset($unit->id) ||  strpos($unit->person ,Auth::user()->id) !== false)
                                    	<a href="javascript:regform.submit()" class="squareBtn btn-large">保存</a>
                              		@else
				                		@if (session('update_success'))
    	                            		<a href="/comp/unit" class="squareBtn btn-large">戻る</a>
                                		@else
	                                		<a href="javascript:history.back();" class="squareBtn btn-large">戻る</a>
                                		@endif
                              		@endif
                                </div><!-- /.btn-container -->
                            {{ Form::close() }}
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

console.log(titleList);

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

});

</script>




@endsection
