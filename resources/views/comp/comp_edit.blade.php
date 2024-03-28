@extends('layouts.comp.admin')

@section('content')

<head>
	<title>>企業情報（ユーザに表示する情報）｜{{ config('app.name', 'Laravel') }}</title>
</head>

            <div class="mainContentsInner-oneColumn">

                <div class="secTitle">
                    <div class="title-main">
                    <h2>企業情報（ユーザに表示する情報）</h2>
                    </div><!-- /.mainTtl -->
                </div><!-- /.sec-title -->
               
                <div class="containerContents">

                    {{ Form::open(['url' => '/comp/change', 'name' => 'changeform' , 'id' => 'changeform', "enctype" => "multipart/form-data"]) }}
                    {{ Form::hidden('comp_id', old('comp_id' ,$comp->id), ['class' => 'form-control', 'id'=>'comp_id' ] )}}
                    <section class="secContents-mb">
                        <div class="secContentsInner">
                            
							<ul class="jobToggleList leftAlign">
								<li>
									<div class="button-radio">
										<input id="c_ch1" class="radiobutton" name="open_flag" type="radio" value="1"  @if (old('open_flag' ,$comp->open_flag) == '1')  checked="checked" @endif  />
										<label for="c_ch1">公開</label> /
										<input id="c_ch2" class="radiobutton" name="open_flag" type="radio" value="0"  @if (old('open_flag' ,$comp->open_flag) == '0')  checked="checked" @endif />
										<label for="c_ch2">非公開</label> 
									</div>
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
                    {{ Form::close() }}


                    {{ Form::open(['url' => '/comp/store', 'name' => 'regform' , 'id' => 'regform', 'files' => true]) }}
                    <section class="secContents-mb">
                        <div class="secContentsInner">
                            
                            <ul class="jobToggleList leftAlign">
                                <li>
                                    <div class="jobToggle">
                                        <input type="checkbox" name="casual_flag" id="c_ch_cas"  value="1"  @if ($comp->casual_flag == '1')  checked="checked" @endif/>
                                        <label for="c_ch_cas">企業宛にカジュアル面談を受け付ける</label>
                                    </div>
                                </li>
                            </ul><!-- /.compToggle -->
                           
								<div class="formContainer mg-ajust-midashi">
                                    <div class="item-name">
                                        <p>会社名</p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input">
                                        {{ $comp->name }}
                                    </div><!-- /.item-input -->
                                </div>

								<div class="formContainer mg-ajust-midashi">
                                    <div class="item-name">
                                        <p>会社名（英語）</p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input">
                                        {{ $comp->name_english }}
                                    </div><!-- /.item-input -->
                                </div>


								<div class="formContainer mg-ajust-midashi">
                                    <div class="item-name">
                                        <p>本社所在地</p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input">
                                        <input type="text" name="address" value="{{ old('address' ,$comp->address) }}">
                                    </div><!-- /.item-input -->
                                </div>

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>業種カテゴリ</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										{{ $comp->getBusCatName() }}
										<hr>
										<br>
									</div><!-- /.item-input -->
								</div>

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>業種<span>*</span></p>
									</div><!-- /.item-name -->
									<div class="item-input">
										@foreach ($businessCat as $cat)
											<div style="font-size:16px; font-weight: bold;">{{ $cat->name }}</div>
											<div style="display:flex;flex-wrap: wrap;">
												@foreach ($businessCatDetail as $detail)
													@if ($cat->id == $detail->business_cat_id)
														<div style="margin-left: 15px;">
															@if (!empty($comp->getBusiness() ))
																{{ html()->checkbox('businessCat[]', (in_array($detail->id, $comp->getBusiness() )), $detail->id) }}{{ $detail->name }}
															@else
																{{ html()->checkbox('businessCat[]', false, $detail->id) }}{{ $detail->name }}
															@endif
														</div>
													@endif
												@endforeach
											</div>
										@endforeach
                                <ul class="oneRow">
                                    @error('businessCat[]')
                                        <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                                    @enderror
                                </ul>
<hr>
<br>
									</div><!-- /.item-input -->
								</div>

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>こだわり</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										@foreach ($commitCat as $cat)
											<div style="font-size:16px; font-weight: bold;">{{ $cat->name }}</div>
											<div style="display:flex;flex-wrap: wrap;">
												@foreach ($commitCatDetail as $detail)
													@if ($cat->id == $detail->commit_cat_id)
														<div style="margin-left: 15px;">
															@if (!empty($comp->getCommit() ))
																{{ html()->checkbox('commitCat[]', (in_array($detail->id, $comp->getCommit() )), $detail->id) }}{{ $detail->name }}
															@else
																{{ html()->checkbox('commitCat[]', false, $detail->id) }}{{ $detail->name }}
															@endif
														</div>
													@endif
												@endforeach
											</div>
										@endforeach
<hr>
<br>
                                <ul class="oneRow">
                                    @error('commitCat[]')
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
                                        <textarea class="form-mt" name="intro" id="intro" cols="30" rows="10" placeholder="本文">{{ $comp->intro }}</textarea>
                                <ul class="oneRow">
                                    @error('intro')
                                        <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                                    @enderror
                                </ul>
                                    </div><!-- /.item-input -->
                                </div>
                                
                              
                                <div class="formContainer mg-ajust-midashi">
                                    <div class="item-name">
                                        <p>ロゴ</span></p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input">
                                        @if ( isset($comp->logo_file) )
                                            <img src="{{ $comp->logo_file }}" class="css-class" alt="logo" width="100" border="1">
                                       @endif
                                    </div><!-- /.item-input -->
                                    <div class="item-input">
                                        {{ Form::file('logo', ['class'=>'form-control']) }}
                                    </div>
                                    <div class="item-input">
                                        <p> ※jpg、png、500KB以内</p>
                                    </div>
                                </div>


                                <div class="formContainer mg-ajust-midashi">
                                    <div class="item-name">
                                        <p>イメージ</p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input">
                                        @if ( isset($comp->image_file) )
                                            <img src="{{ $comp->image_file }}" class="css-class" alt="image" width="250">
                                        @endif
                                    </div><!-- /.item-input -->
                                    <div class="item-input">
                                        {{ Form::file('image', ['class'=>'form-control']) }}
                                    </div>
                                    <div class="item-input">
                                        <p> ※jpg、png、500KB以内</p>
                                    </div>
                               </div>
                               <div class="formContainer mg-ajust-midashi">
                                   <div class="item-name">
                                        <p></p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input">
                                        <ul class="oneRow">
                                        @error('image')
                                            <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                                        @enderror
                                        </ul>
                                    </div>
                               </div>

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
                                        {{Form::hidden('person', $comp->person, ['class' => 'form-control', 'id'=>'person' ] )}}
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
			                    			<div class="alert alert-success"  style="color:#0000ff;">
			                      		 		{{session('update_success')}}
			                    			</div>
				                		@endif
                                    <a href="javascript:regform.submit()" class="squareBtn btn-large">保存</a>　<a href="/comp/preview" class="squareBtn btn-large" target="_blank" rel="noopener noreferrer">プレビュー</a>
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


});

</script>




@endsection
