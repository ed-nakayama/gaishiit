{{-- こだわり選択モーダル  --}}

<div id="modalAreaCommit" class="modalAreaCommit modalSort">
	<div id="modalBg" class="modalBg"></div>
	<div class="modalWrapper">
		<div class="modalContents">
			<h3>こだわりを選ぶ</h3>    
			<div class="container">
				<div class="menu">
					@foreach ($commitCat as $cat)
						<div class="menu-item" data-area="Job{{ $cat->id }}">{{ $cat->name }}</div>
					@endforeach
				</div>

				<form action="" onsubmit="return false" >

				<div class="content-container">
					
					@foreach ($commitCat as $cat)
						<div class="content" id="Job{{ $cat->id }}">
@php
	$totalCnt = 0;
	foreach ($commitCatDetail as $detail) {
		if ($detail->commit_cat_id == $cat->id) $totalCnt++;
	}
@endphp
							<div class="split-container">
								<ul class="left-column">
@php
	$cnt = 0;
@endphp
									@foreach ($commitCatDetail as $detail)
										@if ($detail->commit_cat_id == $cat->id)
											@if ($cnt < $totalCnt / 2)
												<li><label>{{ html()->checkbox("commitcat_sel[]", 0, $detail->id)->id('commitcat_select')->attribute('title', $detail->name) }}{{ $detail->name }}</label></li>
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
									@foreach ($commitCatDetail as $detail)
										@if ($detail->commit_cat_id == $cat->id)
											@if ($cnt >= $totalCnt / 2)
												<li><label>{{ html()->checkbox("commitcat_sel[]", 0, $detail->id)->id('commitcat_select')->attribute('title', $detail->name) }}{{ $detail->name }}</label></li>
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
					<button type="submit" onclick="GetCommit()">変更する</button>
				</div>
				</form>
			</div>
	
		</div>
		<div id="closeModal" class="closeModal">
			×
		</div>
	</div>
</div>

{{-- END こだわり選択モーダル  --}}

<script>

/////////////////////////////////////////////////////////
// メインにこだわりセット
/////////////////////////////////////////////////////////
function putCommit() {
    var works = $("input[id='commitcat_select']:checked").map(function() {

        return {
			'title': this.title,
			'val': this.value
		}
	});

    var vals = new Array();
    var titles = new Array();

	for (let i = 0; i < works.length; ++i) {
		vals[i] = works[i]['val'];
		titles[i] = works[i]['title'];
	};

    var valList = $.makeArray(vals).join(',');
    var titleList = "";

	for (let i = 0; i < works.length; ++i) {
		vals[i] = works[i]['val'];
		titles[i] = works[i]['title'];
			
		titleList = titleList  +  "<li><span>" + works[i]['title'] +  "</span></li>\n";
	};


    if (valList == ""){
        $("#commitcat_list").html("<li><span>指定なし</span></li>");
        document.getElementById( "commit_cat_details" ).value = "" ;
    }else{
    	$("#commitcat_list").html(titleList);
        document.getElementById( "commit_cat_details" ).value = valList;
    }
}


/////////////////////////////////////////////////////////
// こだわり選択モーダルからの戻り
/////////////////////////////////////////////////////////
function GetCommit() {

	putCommit();
	ResetCommit();

     $('body').css('overflow-y','auto');     // 本文の縦スクロールを有効
	$('#modalAreaCommit').fadeOut();

}



/////////////////////////////////////////////////////////
// こだわりモーダル設定
/////////////////////////////////////////////////////////
function ResetCommit() {

	var commit_cats = document.getElementById("commit_cat_details").value;

	boxes = document.getElementsByName("commitcat_sel[]");
	var cnt = boxes.length;
	var inx = 0;

	if (commit_cats != null) {
		var commitcat_list = commit_cats.split(',');

		for (var i = 0; i < cnt; i++) {
			for (const element of commitcat_list) {
				if (boxes[i].value == element) {
					boxes[i].checked = true;
				}
			}
		}
    }
}


</script>
