@extends('layouts.comp.auth')

@section('content')

<head>
	<title>イベント管理｜{{ config('app.name', 'Laravel') }}</title>
</head>

{{--@include('comp.member_activity')--}}

            <div class="mainContentsInner">

				<div class="mainTtl title-main">
					@if ( !isset($event->id) )
						<h2>イベント管理 - 新規作成</h2>
					@elseif (strpos($event->person ,Auth::user()->id) !== false)
						<h2>イベント管理 - 編集</h2>
					@else
						<h2>イベント管理 - 参照</h2>
					@endif
                </div><!-- /.mainTtl -->

                <div class="containerContents">
{{--
@if ( isset($event->id) )
 <!--  修正  -->
                    {{ Form::open(['url' => '/comp/event/change', 'name' => 'changeform' , 'id' => 'changeform']) }}
                    {{Form::hidden('event_id', old('event_id' ,$event->id), ['class' => 'form-control', 'id'=>'event_id' ] )}}
                    <section class="secContents-mb">
                        <div class="secContentsInner">
                            
                            <ul class="jobToggleList">
                                <li>
                                    <div class="jobToggle">
                                        <label for="c_ch1"></label>
                                    </div>
                                </li>
                                <li>
                                    <div class="jobToggle leftAlign">
                                        <input type="checkbox"  name="open_flag" id="c_ch2"  value="1"  @if (old('open_flag' ,$event->open_flag) == '1')  checked="checked" @endif  @if (isset($event->id) && strpos($event->person ,Auth::user()->id) === false) disabled="disabled" @endif/>
                                        <label for="c_ch2">公開 / 非公開</label>
                                    </div>
                                </li>
                                <li>
                                    <label for=""><span>削除する</span><input type="checkbox"  name="del_flag" id="" value="1"  @if (old('dell_flag' ,$event->del_flag) == '1')  checked="checked" @endif  @if (isset($event->id) && strpos($event->person ,Auth::user()->id) === false) disabled="disabled" @endif/></label>
                                </li>
								<li>
                            		<div class="btnContainer">
                              			@if (!isset($event->id) ||  strpos($event->person ,Auth::user()->id) !== false)
                                			<a href="javascript:changeform.submit()" class="squareBtn  btn-short">保存</a>
                              			@else
				                			@if (session('update_success'))
    	                            			<a href="/comp/event" class="squareBtn btn-large">戻る</a>
                                			@else
                                				<a href="javascript:history.back();" class="squareBtn btn- btn-short">戻る</a>
                              				@endif
                              			@endif
                            		</div><!-- /.btn-container -->
								</li>
                            </ul><!-- /.eventToggle -->
                            
                        </div><!-- /.secContentsInner -->
                    </section><!-- /.secContents-mb -->
                    {{ Form::close() }}
<!--  修正 END  -->
@endif
--}}

                    {{ Form::open(['url' => '/comp/event/register', 'name' => 'regform' , 'id' => 'regform', 'files' => true]) }}
                    {{Form::hidden('event_id', old('event_id' ,$event->id), ['class' => 'form-control', 'id'=>'event_id' ] )}}

                    <section class="secContents">
                        <div class="secContentsInner">
                               @if ( isset($unitList[0]) )
                                <div class="formContainer mg-ajust">
                                    <div class="item-name">
                                        <p>部門</p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input">
                                        <div class="selectWrap harf">
                                            <select name="unit"  class="select-no"  @if (isset($event->id) && strpos($event->person ,Auth::user()->id) === false) disabled="disabled" @endif>
                                                <option value=""></option>
                                                @foreach ($unitList as $un)
                                                    <option value="{{ $un->id }}" @if (old('unit' ,$event->unit_id) == $un->id)  selected @endif>{{ $un->name }}</option>
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
                                        <p>名称<span>*</span></p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input">
                                        <input type="text"  name="name"  value="{{ old('name' ,$event->name) }}" @if (isset($event->id) && strpos($event->person ,Auth::user()->id) === false) disabled="disabled" @endif placeholder="名称">
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
                                        <textarea class="form-mt" name="intro" id="" cols="30" rows="10" placeholder="本文" @if (isset($event->id) && strpos($event->person ,Auth::user()->id) === false) disabled="disabled" @endif>{{ old('intro' ,$event->intro) }}</textarea>
                                <ul class="oneRow">
                                    @error('intro')
                                        <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                                    @enderror
                                </ul>
                                    </div><!-- /.item-input -->
                                </div>

                                <div class="formContainer mg-ajust-midashi">
                                    <div class="item-name">
                                        <p>イメージ</p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input">
                                        {{ Form::file('image', ['class'=>'form-control']) }}
                                    </div>
                                    <div class="item-input">
                                        <p> ※jpg、png、1024KB以内</p>
                                    </div>
                                </div>

                                @error('image')
                                <div class="formContainer mg-ajust-midashi">
                                    <div class="item-name">
                                        <p><span></span></p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input">
                                        <ul class="oneRow">
                                            <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                                        </ul>
                                    </div><!-- /.item-input -->
                                </div>
                                @enderror
                               
                                <div class="formContainer bb-ajust">
                                    <div class="item-name">
                                        <p><span></span></p>
                                    </div><!-- /.item-name -->
                                    @if ( isset($event->image) )
                                    <div class="containerSeminarInfo">
                                        <div class="seminarInfoImage">
                                            <img src="{{ $event->image }}">
                                        </div><!-- /.item-input -->
                                    </div>
                                    @endif
                                </div>


                                <div class="formContainer mg-ajust al-item-none">
                                    <div class="item-name">
                                        <p>日程<span>*</span></p>
                                    </div><!-- /.item-name -->
                                    <div class="select-item-container">

                                        <div class="select-item-column">
                                            <div class="item-input select-item-row">
												<label style="padding: 5px 5px;border: 1px solid #ccc;"><input type="date" name="event_date" value="{{ old('event_date', $event->event_date) }}"></label>
                                            </div><!-- /.item-input -->
                                        </div><!-- /.select-item-columnt -->


                                        <div class="select-item-column">
                                            <div class="item-input select-item-row">
                                                <div class="selectWrap hundred">
                                                    <select name="start_hour" class="select-no">
                                                        <option value="" disabled selected style="display:none;">時</option>
                                                        @foreach ($dayList as $hour)
                                                        <option value="{{$hour}}"   @if (old('start_hour' ,$event->start_hour) == $hour)  selected @endif>{{$hour}}時</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="selectWrap hundred">
                                                    <select name="start_min" class="select-no">
                                                        <option value="" disabled selected style="display:none;">分</option>
                                                        @foreach ($minList as $min)
                                                        <option value="{{$min}}"   @if (old('start_min' ,$event->start_min) == $min)  selected @endif>{{$min}}分</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <span class="icon-ripple">〜</span>

                                                <div class="selectWrap hundred">
                                                    <select name="end_hour" class="select-no">
                                                        <option value="" disabled selected style="display:none;">時</option>
                                                        @foreach ($dayList as $hour)
                                                        <option value="{{$hour}}"   @if (old('end_hour' ,$event->end_hour) == $hour)  selected @endif>{{$hour}}時</option>
                                                        @endforeach
                                                     </select>
                                                </div>

                                                <div class="selectWrap hundred">
                                                    <select name="end_min" class="select-no">
                                                        <option value="" disabled selected style="display:none;">分</option>
                                                        @foreach ($minList as $min)
                                                        <option value="{{$min}}"   @if (old('end_min' ,$event->end_min) == $min)  selected @endif>{{$min}}分</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div><!-- /.item-input -->
                                        </div><!-- /.select-item-columnt -->

                                    </div><!-- /.select-item-container -->
                                </div>

                                <div class="formContainer mg-ajust">
                                    <div class="item-name">
                                        <p>形式<span>*</span></p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input">
                                        <input type="radio" name="online_flag" value="1"  @if (old('online_flag' ,$event->online_flag) == '1')  checked @endif><label for="">オンライン</label>　
                                        <input type="radio" name="online_flag" value="0"  @if (old('online_flag' ,$event->online_flag) == '0')  checked @endif><label for="">オフライン</label>
                                    </div><!-- /.item-input -->
                                </div>

                                <div class="formContainer mg-ajust">
                                    <div class="item-name">
                                        <p>会場</p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input">
                                        <input class="harf" name="place" type="text" value="{{ old('place' ,$event->place) }}"  @if (isset($event->id) && strpos($event->person ,Auth::user()->id) === false) disabled="disabled" @endif  placeholder="開催場所">
                                        <ul class="oneRow">
                                        @error('place')
                                            <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                                        @enderror
                                        </ul>
                                    </div><!-- /.item-input -->
                                </div>
                                

                                <div class="formContainer bb-ajust">
                                    <div class="item-name">
                                        <p>アクセス</p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input">
                                        <input class="harf"  name="access" type="text" value="{{ old('access' ,$event->access) }}" @if (isset($event->id) && strpos($event->person ,Auth::user()->id) === false) disabled="disabled" @endif  placeholder="最寄りの交通機関など">
                                    </div><!-- /.item-input -->
                                </div>
                                                                
                                <div class="formContainer mg-ajust al-item-none">
                                    <div class="item-name">
                                        <p>募集締め切り<br>日時</p>
                                    </div><!-- /.item-name -->
                                    <div class="select-item-container">

                                        <div class="select-item-column">
                                            <div class="item-input select-item-row">
												<label style="padding: 5px 5px;border: 1px solid #ccc;"><input type="date" name="deadline_date" value="{{ old('deadline_date', $event->deadline_date) }}"></label>
                                            </div><!-- /.item-input -->
                                        </div><!-- /.select-item-columnt -->


                                        <div class="select-item-column">
                                            <div class="item-input select-item-row">
                                                <div class="selectWrap hundred">
                                                    <select name="deadline_hour" class="select-no">
                                                        <option value="" disabled selected style="display:none;">時</option>
                                                        @foreach ($hourList as $hour)
                                                        <option value="{{$hour}}"   @if (old('deadline_hour' ,$event->deadline_hour) == $hour)  selected @endif>{{$hour}}時</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="selectWrap hundred">
                                                    <select name="deadline_min" class="select-no">
                                                        <option value="" disabled selected style="display:none;">分</option>
                                                        @foreach ($minList as $min)
                                                        <option value="{{$min}}"   @if (old('deadline_min' ,$event->deadline_min) == $min)  selected @endif>{{$min}}分</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div><!-- /.item-input -->
                                        </div><!-- /.select-item-columnt -->

                                        <ul class="oneRow">
                                            @error('category')
                                            <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                                            @enderror
                                        </ul>

                                    </div><!-- /.select-item-container -->
                                </div>

                                <div class="formContainer mg-ajust">
                                    <div class="item-name">
                                        <p>定員</p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input">
                                        <input class="harf"  name="capacity" type="text" value="{{ old('capacity' ,$event->capacity) }}" @if (isset($event->id) && strpos($event->person ,Auth::user()->id) === false) disabled="disabled" @endif> 人

                                        <ul class="oneRow">
                                            @error('capacity')
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
                                            @if (!isset($event->id) || strpos($event->person ,Auth::user()->id) !== false)
                                                <a href="#modal" class="squareBtn btn-medium">選択</a>
                                            @endif
                                             </div><!-- /.modalContainer -->
                                          
                                        </div>
                                        {{Form::hidden('person', old('person' ,$event->person), ['class' => 'form-control', 'id'=>'person' ] )}}
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
                              		@if (!isset($event->id) || strpos($event->person ,Auth::user()->id) !== false)
                                    	<a href="javascript:regform.submit()" class="squareBtn btn-large">保存</a>
                              		@else
				                		@if (session('update_success'))
    	                            		<a href="/comp/event" class="squareBtn btn-large">戻る</a>
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

});

</script>




@endsection
