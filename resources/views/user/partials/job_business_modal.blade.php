{{-- 業種選択モーダル  --}}

<div id="modalAreaBussiness" class="modalAreaBussiness modalSort">
	<div id="modalBg" class="modalBg"></div>
	<div class="modalWrapper">
		<div class="modalContents">
			<h3>IT業界の業種を選ぶ</h3>    
			<form action="" onsubmit="return false" >
	
		  	@foreach ($businessCat as $cat)
				<div id="" class="block">
					<ul class="cate-parent">
						<li>
							<label>
						{{ html()->checkbox("buscat_parent[]", 0, $cat->id)->id("buscat_parent")->attribute('title', $cat->name)->class("buscat_parent{$cat->id}") }}<span>{{ $cat->name }}</span>
							</label>
						</li>
					</ul>
					<p class="block-ttl"></p>

					<ul class="cate-list">
					@foreach ($businessCatDetail as $detail)
						@if ($detail->business_cat_id == $cat->id)
							<li>
								<label>
									{{ html()->checkbox("buscat_sel[]", 0, $detail->id)->id('buscat_select')->attribute('title', $detail->name)->class("buscat_sel{$cat->id}") }}
									<span>{{ $detail->name }}</span>
								</label>
							</li>
						@endif
					@endforeach
					</ul>
				</div>
				<br>
			@endforeach

			<div class="btn-wrap">
				<button type="submit" onclick="GetBus()">変更する</button>
			</div>

			</form>
	
	  </div>
	  <div id="closeModal" class="closeModal">
		×
	  </div>
	</div>
</div>

{{-- END 業種選択モーダル  --}}

<script>

/////////////////////////////////////////////////////////
// メインに職種セット
/////////////////////////////////////////////////////////
function putBus() {

    var parents = $("input[id='buscat_parent']:checked").map(function() {
        return {
			'title': this.title,
			'val': this.value
		}
	});

    var works = $("input[id='buscat_select']:checked").map(function() {
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


    if (works_valList == "" && parents_valList == "") {
        $("#buscat_list").html("<li><span>指定なし</span></li>");
        document.getElementById( "business_cats" ).value = "";
        document.getElementById( "business_cat_details" ).value = "";

    } else if (parents_valList != "") {
    	$("#buscat_list").html(titleList);
        document.getElementById( "business_cats" ).value = parents_valList;
        document.getElementById( "business_cat_details" ).value = "";

    } else {
    	$("#buscat_list").html(titleList);
        document.getElementById( "business_cats" ).value = "";
        document.getElementById( "business_cat_details" ).value = works_valList;
    }

}


/////////////////////////////////////////////////////////
// 業種選択モーダルからの戻り
/////////////////////////////////////////////////////////
function GetBus() {

	putBus();
	ResetBus();

     $('body').css('overflow-y','auto');     // 本文の縦スクロールを有効
	$('#modalAreaBussiness').fadeOut();

}



/////////////////////////////////////////////////////////
// 業種モーダル設定
/////////////////////////////////////////////////////////
function ResetBus() {

	var cats = document.getElementById("business_cats").value;

	boxes = document.getElementsByName("buscat_parent[]");
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


	var details = document.getElementById("business_cat_details").value;

	boxes = document.getElementsByName("buscat_sel[]");
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
// 業種 全チェック
/////////////////////////////////////////////////////////
	@foreach ($businessCat as $cat)
		//全選択・解除のチェックボックス
		let buscat_all{{ $cat->id }} = document.querySelector(".buscat_parent{{ $cat->id }}");
		//チェックボックスのリスト
		let buscat_list{{ $cat->id }} = document.querySelectorAll(".buscat_sel{{ $cat->id }}");

		//全選択のチェックボックスイベント
		buscat_all{{ $cat->id }}.addEventListener('change', buscat_change_all{{ $cat->id }});

		function buscat_change_all{{ $cat->id }}() {
			//チェックされているか
			if (buscat_all{{ $cat->id }}.checked) {
				//全て選択
				for (let i in buscat_list{{ $cat->id }}) {
					if (buscat_list{{ $cat->id }}.hasOwnProperty(i)) {
						buscat_list{{ $cat->id }}[i].checked = false;
						buscat_list{{ $cat->id }}[i].disabled = true;
					}
				}
				
			} else {
				//全て解除
				for (let i in buscat_list{{ $cat->id }}) {
					if (buscat_list{{ $cat->id }}.hasOwnProperty(i)) {
						buscat_list{{ $cat->id }}[i].checked = false;
						buscat_list{{ $cat->id }}[i].disabled = false;
					}
				}
			}
		};
	@endforeach


</script>
