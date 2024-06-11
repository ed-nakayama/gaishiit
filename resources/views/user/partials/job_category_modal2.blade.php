{{-- 職種選択モーダル --}}

<div id="modalAreaJob" class="modalAreaJob modalSort">
	<div id="modalBg" class="modalBg"></div>
	<div class="modalWrapper">
		<div class="modalContents">
			<h3>職種を選ぶ</h3>
			<div class="container">
				<div class="menu">
					@foreach ($jobCat as $cat)
						<div class="menu-item" data-area="Job{{ $cat->id }}">{{ $cat->name }}</div>
					@endforeach
				</div>

				<form action="" onsubmit="return false" >

				<div class="content-container">
					
					@foreach ($jobCat as $cat)
						<div class="content" id="Job{{ $cat->id }}">
							<label>{{ html()->checkbox("jobcat_parent[]", 0, $cat->id)->id("jobcat_parent")->attribute('title', $cat->name)->class("jobcat_parent{$cat->id}") }}<span style="font-weight:bold;">{{ $cat->name }}全て</span></label>
							<hr>
@php
	$totalCnt = 0;
	foreach ($jobCatDetail as $detail) {
		if ($detail->job_cat_id == $cat->id) $totalCnt++;
	}
@endphp
							<div class="split-container">
								<ul class="left-column">
@php
	$cnt = 0;
@endphp
									@foreach ($jobCatDetail as $detail)
										@if ($detail->job_cat_id == $cat->id)
											@if ($cnt < $totalCnt / 2)
												<li><label>{{ html()->checkbox("jobcat_sel[]", 0, $detail->id)->id('jobcat_select')->attribute('title', $detail->name)->class("jobcat_sel{$cat->id}") }}{{ $detail->name }}</label></li>
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
									@foreach ($jobCatDetail as $detail)
										@if ($detail->job_cat_id == $cat->id)
											@if ($cnt >= $totalCnt / 2)
												<li><label>{{ html()->checkbox("jobcat_sel[]", 0, $detail->id)->id('jobcat_select')->attribute('title', $detail->name)->class("jobcat_sel{$cat->id}") }}{{ $detail->name }}</label></li>
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
					<button type="submit" onclick="GetJob()">変更する</button>
				</div>
				</form>
			</div>


	
		</div>
		<div id="closeModal" class="closeModal">
			×
		</div>
	</div>
</div>


{{-- END 職種選択モーダル  --}}

<script>

/////////////////////////////////////////////////////////
// メインに職種セット
/////////////////////////////////////////////////////////
function putJob() {

    var parents = $("input[id='jobcat_parent']:checked").map(function() {
        return {
			'title': this.title,
			'val': this.value
		}
	});

    var works = $("input[id='jobcat_select']:checked").map(function() {
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
        $("#jobcat_name_list").html("<li><span>指定なし</span></li>");
        document.getElementById( "job_cats" ).value = "";
        document.getElementById( "job_cat_details" ).value = "";

    } else if (parents_valList != "") {
    	$("#jobcat_name_list").html(titleList);
        document.getElementById( "job_cats" ).value = parents_valList;
        document.getElementById( "job_cat_details" ).value = "";

    } else {
    	$("#jobcat_name_list").html(titleList);
        document.getElementById( "job_cats" ).value = "";
        document.getElementById( "job_cat_details" ).value = works_valList;
    }
}


/////////////////////////////////////////////////////////
// 職種選択モーダルからの戻り
/////////////////////////////////////////////////////////
function GetJob() {

	putJob();
	ResetJob();

     $('body').css('overflow-y','auto');     // 本文の縦スクロールを有効
	$('#modalAreaJob').fadeOut();
}


/////////////////////////////////////////////////////////
// 職種モーダル設定
/////////////////////////////////////////////////////////
function ResetJob() {

	var cats = document.getElementById("job_cats").value;

	boxes = document.getElementsByName("jobcat_parent[]");
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


	var details = document.getElementById("job_cat_details").value;

	boxes = document.getElementsByName("jobcat_sel[]");
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
// 職種 全チェック
/////////////////////////////////////////////////////////
	@foreach ($jobCat as $cat)
		//全選択・解除のチェックボックス
		let jobcat_all{{ $cat->id }} = document.querySelector(".jobcat_parent{{ $cat->id }}");
		//チェックボックスのリスト
		let jobcat_list{{ $cat->id }} = document.querySelectorAll(".jobcat_sel{{ $cat->id }}");

		//全選択のチェックボックスイベント
		jobcat_all{{ $cat->id }}.addEventListener('change', jobcat_change_all{{ $cat->id }});

		function jobcat_change_all{{ $cat->id }}() {
			//チェックされているか
			if (jobcat_all{{ $cat->id }}.checked) {
				//全て選択
				for (let i in jobcat_list{{ $cat->id }}) {
					if (jobcat_list{{ $cat->id }}.hasOwnProperty(i)) {
						jobcat_list{{ $cat->id }}[i].checked = false;
						jobcat_list{{ $cat->id }}[i].disabled = true;
					}
				}
				
			} else {
				//全て解除
				for (let i in jobcat_list{{ $cat->id }}) {
					if (jobcat_list{{ $cat->id }}.hasOwnProperty(i)) {
						jobcat_list{{ $cat->id }}[i].checked = false;
						jobcat_list{{ $cat->id }}[i].disabled = false;
					}
				}
			}
		};

	@endforeach


</script>
