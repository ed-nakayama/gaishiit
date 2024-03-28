@extends('layouts.comp.admin')

@section('content')

<head>
	<title>部門設定｜{{ config('app.name', 'Laravel') }}</title>
</head>

            <div class="mainContentsInner-oneColumn">

                <div class="secTitle">
                    <div class="title-main">
                    @if ( !isset($unit->id) )
                        <h2>部門設定 - 新規作成</h2>
                    @else
                        <h2>部門設定 - 編集</h2>
                    @endif
                    </div><!-- /.mainTtl -->
                </div><!-- /.sec-title -->


                <div class="containerContents">

                    {{ Form::open(['url' => '/comp/admin/unit/register', 'name' => 'regform' , 'id' => 'regform', 'files' => true]) }}
                    {{Form::hidden('unit_id', old('unit_id' ,$unit->id), ['class' => 'form-control', 'id'=>'unit_id' ] )}}

                    <section class="secContents">
                        <div class="secContentsInner">
                                
                                <div class="formContainer mg-ajust-midashi">
                                    <div class="item-name">
                                        <p>部門名称<span>*</span></p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input">
                                        <input type="text"  name="name"  value="{{ old('name' ,$unit->name) }}">
                                <ul class="oneRow">
                                    @error('name')
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
                                                <a href="#modal" class="squareBtn btn-medium">選択</a>
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
                                    <a href="javascript:regform.submit()" class="squareBtn btn-large">保存</a>
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


$(document).ready(function() {

	// 担当対応
	ResetPerson();
	putPerson();


});

</script>




@endsection
