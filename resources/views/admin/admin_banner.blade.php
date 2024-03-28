@extends('layouts.admin.auth')

@section('content')

<head>
	<title>バナー管理｜{{ config('app.name', 'Laravel') }}</title>
</head>

			<div class="mainContentsInner-oneColumn">

				<div class="secTitle">
					<div class="title-main">
						<h2>バナー管理</h2>
					</div><!-- /.mainTtl -->
				</div><!-- /.sec-title -->
               
				<div class="containerContents">
	
					@foreach ($bannerList as $banner)
						<section class="secContents-mb">
							<div class="secContentsInner">
								<div class="formContainer-add ajust">
									{{ html()->form('POST', '/admin/banner')->attribute('name','bannerform' . $loop->index)->acceptsFiles()->open() }}
									{{ html()->hidden('company_id', $banner->company_id) }}
									{{ html()->hidden('banner_id', $banner->id) }}
									{{ html()->hidden('event_id', $banner->event_id) }}

									<div class="formContainer mg-ajust-midashi">
										<div class="item-name">
											@if ($loop->first)
												<p>マイページ</p>
											@else
												<p>ナビバナー{{ $loop->index }}</p>
											@endif
										</div><!-- /.item-name -->
										<div class="item-input">
										</div><!-- /.item-input -->
									</div>

									<div class="formContainer mg-ajust-midashi">
										<div class="item-name">
											<p>イメージ</p>
										</div><!-- /.item-name -->
										<div class="item-input">
											@if ( isset($banner->image) )
												<img src="{{ $banner->image }}" class="css-class" alt="image" height="200">
											@endif
										</div><!-- /.item-input -->
									</div>

									<div class="formContainer mg-ajust-midashi">
										<div class="item-input">
											{{ html()->file('image')->class('form-control') }}
										</div>
										<div class="item-input">
											<p> ※jpg、png、500KB以内</p>
										</div>
									</div>

									<div class="formContainer mg-ajust-midashi">
										<div class="item-name">
											<p>イベント</p>
										</div><!-- /.item-name -->
										<div class="item-input">
											<a href="#modal{{ $loop->index }}" class="squareBtn btn-medium"  data-remodal-form="Xbannerform{{ $banner->id }}" >検索</a>
										</div><!-- /.item-input -->
									</div>

{{-- 企業、イベント　モーダル領域   --}}

									<div class="remodal" data-remodal-id="modal{{ $loop->index }}">
										<div class="modalTitle">
										<h2>企業、イベントを選択してください</h2>
										</div><!-- /.modalTitle -->

										<div class="modalInner bb-ajust">
											<div class="item-name">
												<p>企業名</p>
											</div><!-- /.item-name -->
											<div class="selectWrap"  style="text-align:left;">
												<select name="company_id"  id="company_id{{ $loop->index }}" class="select-no">
													<option value=""></option>
													@foreach ($compList as $comp)
														<option value="{{ $comp->id }}">{{ $comp->name }}</option>
													@endforeach
												</select>
											</div>
								
											<div class="item-name">
												<p>イベント</p>
											</div><!-- /.item-name -->
											<div class="selectWrap"  style="text-align:left;">
												<select name="event_id" id="event_id{{ $loop->index }}" class="select-no">
													<option value=""></option>
												</select>
											</div>
										</div><!-- /.modalInner -->

										<div class="btnContainer">
											<a href="javascript:void(0);" onclick="GetEvent({{ $loop->index }})" class="squareBtn btn-large">設定</a>
										</div><!-- /.btn-container -->
									</div>

{{-- END 企業、イベント　モーダル領域   --}}

									<div class="formContainer mg-ajust-midashi">
										<div class="item-name">
											<p>企業名</p>
										</div><!-- /.item-name -->
										<div class="item-input">
											<input type="text" name="company_name" id="company_name" value="{{ $banner->company_name }}" disabled style="background:lightgrey;">
										</div><!-- /.item-input -->
									</div>

									<div class="formContainer mg-ajust-midashi">
										<div class="item-name">
											<p>URL</p>
										</div><!-- /.item-name -->
										<div class="item-input">
											<input type="text" name="url" id="url" value="{{ $banner->url }}">
										</div><!-- /.item-input -->
									</div>

									<div class="formContainer mg-ajust-midashi">
										<div class="item-name">
											<p>メモ</p>
										</div><!-- /.item-name -->
										<div class="item-input">
											<input type="text" name="memo" value="{{ $banner->memo }}">
										</div><!-- /.item-input -->
									</div>
								</div>
								<div class="btnContainer">
									{{-- 更新成功メッセージ --}}
									@if (session('update_success' . $banner->id))
										<div class="alert alert-success"  style="color:#0000ff;">
											{{session('update_success' . $banner->id)}}
										</div>
									@endif
									<a href="javascript:bannerform{{ $loop->index }}.submit()" class="squareBtn btn-large">保存</a>
								</div><!-- /.btn-container -->
								{{ html()->form()->close() }}
							</div><!-- /.secContentsInner -->
						</section><!-- /.secContents -->
					@endforeach
							
				</div><!-- /.containerContents -->
			</div><!-- /.mainContentsInner -->



<script>

/////////////////////////////////////////////////////////
// 企業選択連動イベントリスト設定
/////////////////////////////////////////////////////////
var event_list = <?php echo $eventList; ?>

// マイページ
document.getElementById('company_id0').onchange = function() {
	event_id = document.getElementById("event_id0");
	event_id.options.length = 0

	var comp_id = company_id0.value;

	for (let i = 0; i < event_list.length; i++) {

		if (event_list[i]['company_id'] == comp_id) {


			var op = document.createElement("option");
			op.value = event_list[i]['id'];
			op.text = event_list[i]['name'];
			event_id0.appendChild(op);
		}
	}
}


// ナビバナー1
document.getElementById('company_id1').onchange = function() {
	event_id = document.getElementById("event_id1");
	event_id.options.length = 0

	var comp_id = company_id1.value;

	for (let i = 0; i < event_list.length; i++) {

		if (event_list[i]['company_id'] == comp_id) {


			var op = document.createElement("option");
			op.value = event_list[i]['id'];
			op.text = event_list[i]['name'];
			event_id1.appendChild(op);
		}
	}
}


// ナビバナー2
document.getElementById('company_id2').onchange = function() {
	event_id = document.getElementById("event_id2");
	event_id.options.length = 0

	var comp_id = company_id2.value;

	for (let i = 0; i < event_list.length; i++) {

		if (event_list[i]['company_id'] == comp_id) {


			var op = document.createElement("option");
			op.value = event_list[i]['id'];
			op.text = event_list[i]['name'];
			event_id2.appendChild(op);
		}
	}
}

// ナビバナー3
document.getElementById('company_id3').onchange = function() {
	event_id = document.getElementById("event_id3");
	event_id.options.length = 0

	var comp_id = company_id3.value;

	for (let i = 0; i < event_list.length; i++) {

		if (event_list[i]['company_id'] == comp_id) {

			var op = document.createElement("option");
			op.value = event_list[i]['id'];
			op.text = event_list[i]['name'];
			event_id3.appendChild(op);
		}
	}
}


         
/////////////////////////////////////////////////////////
// メインにイベントURL設定
/////////////////////////////////////////////////////////
function putEvent(frm, compId, evtId) {

	company_id = document.getElementById(compId);
	event_id = document.getElementById(evtId);

	let idx = company_id.selectedIndex;

	var url = "";
	if (company_id.value != "") {
		url = "/company/" + company_id.value + "/event/" + event_id.value;

		document.forms[frm].elements['url'].value = url;
		document.forms[frm].elements['company_id'].value = company_id.value;
		document.forms[frm].elements['company_name'].value = company_id.options[idx].text;
		document.forms[frm].elements['event_id'].value = event_id.value;
	} else {

		document.forms[frm].elements['url'].value = null;
		document.forms[frm].elements['company_id'].value = null;
		document.forms[frm].elements['company_name'].value = null;
		document.forms[frm].elements['event_id'].value = null;
	}


}


/////////////////////////////////////////////////////////
// 業種選択モーダルからの戻り
/////////////////////////////////////////////////////////
function GetEvent(idx) {

	putEvent('bannerform' + idx ,'company_id' + idx ,'event_id' + idx);

	var modal = $.remodal.lookup[$('[data-remodal-id=modal' + idx + ']').data('remodal')];
    modal.close();
}

</script>

@endsection
