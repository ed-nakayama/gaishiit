{{-- インダストリ選択モーダル  --}}

<div id="modalAreaIndustory" class="modalAreaIndustory modalSort">
	<div id="modalBg" class="modalBg"></div>
	<div class="modalWrapper">
		<div class="modalContents">
			<h3>担当業界を選ぶ</h3>
			<div class="container">
				<div class="menu">
					@foreach ($industoryCat as $cat)
						<div class="menu-item" data-area="Industory{{ $cat->id }}">{{ $cat->name }}</div>
					@endforeach
				</div>

				<form action="" onsubmit="return false" >

				<div class="content-container">
					
					@foreach ($industoryCat as $cat)
						<div class="content" id="Industory{{ $cat->id }}">
							<label>{{ html()->checkbox("industorycat_parent[]", 0, $cat->id)->id("industorycat_parent")->attribute('title', $cat->name)->class("industorycat_parent{$cat->id}") }}<span style="font-weight:bold;">{{ $cat->name }}全て</span></label>
							<hr>
@php
	$totalCnt = 0;
	foreach ($industoryCatDetail as $detail) {
		if ($detail->industory_cat_id == $cat->id) $totalCnt++;
	}
@endphp
							<div class="split-container">
								<ul class="left-column">
@php
	$cnt = 0;
@endphp
									@foreach ($industoryCatDetail as $detail)
										@if ($detail->industory_cat_id == $cat->id)
											@if ($cnt < $totalCnt / 2)
												<li><label>{{ html()->checkbox("industorycat_sel[]", 0, $detail->id)->id('industorycat_select')->attribute('title', $detail->name)->class("industorycat_sel{$cat->id}") }}{{ $detail->name }}</label></li>
											@endif
@php
	$cnt++;
@endphp
										@endif
									@endforeach
								</ul>
								<ul class="right-column">
@php
	$cnt = 0;
@endphp
									@foreach ($industoryCatDetail as $detail)
										@if ($detail->industory_cat_id == $cat->id)
											@if ($cnt >= $totalCnt / 2)
												<li><label>{{ html()->checkbox("industorycat_sel[]", 0, $detail->id)->id('industorycat_select')->attribute('title', $detail->name)->class("industorycat_sel{$cat->id}") }}{{ $detail->name }}</label></li>
											@endif
@php
	$cnt++;
@endphp
										@endif
									@endforeach
								</ul>
							</div>
						</div>
					@endforeach
				</div>
				<div class="btn-wrap">
					<button type="submit" onclick="GetIndustory()">変更する</button>
				</div>
				</form>
			</div>


		</div>
		<div id="closeModal" class="closeModal">
			×
		</div>
	</div>
</div>

{{-- END インダストリ選択モーダル  --}}


<script>

/////////////////////////////////////////////////////////
// メインにインダストリセット
/////////////////////////////////////////////////////////
function putIndustory() {

    var parents = $("input[id='industorycat_parent']:checked").map(function() {
        return {
			'title': this.title,
			'val': this.value
		}
	});

    var works = $("input[id='industorycat_select']:checked").map(function() {

        return {
			'title': this.title,
			'val': this.value
		}
	});

    var titleList = '';

    var parents_valList = getValList(parents);
    var titleList = getTitle(parents, titleList, 1);

    var works_valList = getValList(works);
    titleList = getTitle(works, titleList, 0);

    if (works_valList == "" && parents_valList == ""){
        $("#industorycat_list").html("<li><span>指定なし</span></li>");
        document.getElementById( "industory_cats" ).value = "";
        document.getElementById( "industory_cat_details" ).value = "";

    }else{
    	$("#industorycat_list").html(titleList);

        document.getElementById( "industory_cats" ).value = parents_valList;
        document.getElementById( "industory_cat_details" ).value = works_valList;
    }
}


/////////////////////////////////////////////////////////
// インダトリ選択モーダルからの戻り
/////////////////////////////////////////////////////////
function GetIndustory() {

	putIndustory();
	ResetIndustory();

     $('body').css('overflow-y','auto');     // 本文の縦スクロールを有効
	$('#modalAreaIndustory').fadeOut();

}



/////////////////////////////////////////////////////////
// インダストリモーダル設定
/////////////////////////////////////////////////////////
function ResetIndustory() {

	var cats = document.getElementById("industory_cats").value;

	boxes = document.getElementsByName("industorycat_parent[]");
	var cnt = boxes.length;
	var inx = 0;

	if (cats != null) {
		var cats_list = cats.split(',');

		for (var i = 0; i < cnt; i++) {
			for (const element of cats_list) {
				if (boxes[i].value == element) {
					boxes[i].checked = true;
				}
			}
		}
    }


	var details = document.getElementById("industory_cat_details").value;

	boxes = document.getElementsByName("industorycat_sel[]");
	var cnt = boxes.length;
	var inx = 0;

	if (details != null) {
		var details_list = details.split(',');

		for (var i = 0; i < cnt; i++) {
			for (const element of details_list) {
				if (boxes[i].value == element) {
					boxes[i].checked = true;
				}
			}
		}
    }
}



/////////////////////////////////////////////////////////
// インダストリ 全チェック
/////////////////////////////////////////////////////////
	@foreach ($industoryCat as $cat)
		//全選択・解除のチェックボックス
		let industorycat_all{{ $cat->id }} = document.querySelector(".industorycat_parent{{ $cat->id }}");
		//チェックボックスのリスト
		let industorycat_list{{ $cat->id }} = document.querySelectorAll(".industorycat_sel{{ $cat->id }}");

		//全選択のチェックボックスイベント
		industorycat_all{{ $cat->id }}.addEventListener('change', industorycat_change_all{{ $cat->id }});

		function industorycat_change_all{{ $cat->id }}() {
			//チェックされているか
			if (industorycat_all{{ $cat->id }}.checked) {
				//全て選択
				for (let i in industorycat_list{{ $cat->id }}) {
					if (industorycat_list{{ $cat->id }}.hasOwnProperty(i)) {
						industorycat_list{{ $cat->id }}[i].checked = false;
						industorycat_list{{ $cat->id }}[i].disabled = true;
					}
				}
				
			} else {
				//全て解除
				for (let i in industorycat_list{{ $cat->id }}) {
					if (industorycat_list{{ $cat->id }}.hasOwnProperty(i)) {
						industorycat_list{{ $cat->id }}[i].checked = false;
						industorycat_list{{ $cat->id }}[i].disabled = false;
					}
				}
			}
		};
	@endforeach




</script>
