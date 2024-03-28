{{-- 企業選択モーダル $comp --}}

<div id="modalArea" class="modalArea">
	<div id="modalBg" class="modalBg"></div>
	<div class="modalWrapper">
		<div class="modalContents">
			<h1>企業名で絞り込む</h1>
		</div>
		<div id="closeModal" class="closeModal">
			×
		</div>
	</div>
</div>

<div id="modalAreaName" class="modalAreaName modalSort">
	<div id="modalBg" class="modalBg"></div>
	<div class="modalWrapper">
	  <div class="modalContents">
		<h3>企業名を選ぶ</h3>    
		<form  onsubmit="return false" >
			<div class="pager sort_name">
				<ul class="page">
					<li class="page__numbers"><a class="openModalSeek button-modal" href="#cate-a">A-G</a></li>
					<li class="page__numbers"><a class="openModalSeek button-modal" href="#cate-h">H-N</a></li>
					<li class="page__numbers"><a class="openModalSeek button-modal" href="#cate-o">O-U</a></li>
					<li class="page__numbers"><a class="openModalSeek button-modal" href="#cate-v">V-Z</a></li>
				</ul>
			</div>

			<div id="cate-a" class="block">
				<p class="block-ttl">A</p>
				<ul class="cate-list">
					@foreach ($comp_A as $comp)
						 <li>
							<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
							</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-b" class="block">
				<p class="block-ttl">B</p>
				<ul class="cate-list">
					@foreach ($comp_B as $comp)
						 <li>
							<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
							</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-c" class="block">
				<p class="block-ttl">C</p>
				<ul class="cate-list">
					@foreach ($comp_C as $comp)
						 <li>
							<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
							</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-d" class="block">
				<p class="block-ttl">D</p>
				<ul class="cate-list">
					@foreach ($comp_D as $comp)
						 <li>
							<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
							</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-e" class="block">
				<p class="block-ttl">E</p>
				<ul class="cate-list">
					@foreach ($comp_E as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-f" class="block">
				<p class="block-ttl">F</p>
				<ul class="cate-list">
					@foreach ($comp_F as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-g" class="block">
				<p class="block-ttl">G</p>
				<ul class="cate-list">
					@foreach ($comp_G as $comp)
						 <li>
				 			<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
				 			</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-h" class="block">
				<p class="block-ttl">H</p>
				<ul class="cate-list">
					@foreach ($comp_H as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
			</div>

			<div id="cate-i" class="block">
				<p class="block-ttl">I</p>
				<ul class="cate-list">
					@foreach ($comp_I as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-j" class="block">
				<p class="block-ttl">J</p>
				<ul class="cate-list">
					@foreach ($comp_J as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-k" class="block">
				<p class="block-ttl">K</p>
				<ul class="cate-list">
					@foreach ($comp_K as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-l" class="block">
				<p class="block-ttl">L</p>
				<ul class="cate-list">
					@foreach ($comp_L as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-m" class="block">
				<p class="block-ttl">M</p>
				<ul class="cate-list">
					@foreach ($comp_M as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-n" class="block">
				<p class="block-ttl">N</p>
				<ul class="cate-list">
					@foreach ($comp_N as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-o" class="block">
				<p class="block-ttl">O</p>
				<ul class="cate-list">
					@foreach ($comp_O as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-p" class="block">
				<p class="block-ttl">P</p>
				<ul class="cate-list">
					@foreach ($comp_P as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-q" class="block">
				<p class="block-ttl">Q</p>
				<ul class="cate-list">
					@foreach ($comp_Q as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-r" class="block">
				<p class="block-ttl">R</p>
				<ul class="cate-list">
					@foreach ($comp_R as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-s" class="block">
				<p class="block-ttl">S</p>
				<ul class="cate-list">
					@foreach ($comp_S as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-t" class="block">
				<p class="block-ttl">T</p>
				<ul class="cate-list">
					@foreach ($comp_T as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-u" class="block">
				<p class="block-ttl">U</p>
				<ul class="cate-list">
					@foreach ($comp_U as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-v" class="block">
				<p class="block-ttl">V</p>
				<ul class="cate-list">
					@foreach ($comp_V as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-w" class="block">
				<p class="block-ttl">W</p>
				<ul class="cate-list">
					@foreach ($comp_W as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-x" class="block">
				<p class="block-ttl">X</p>
				<ul class="cate-list">
					@foreach ($comp_X as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
				</ul>
			</div>

			<div id="cate-y" class="block">
				<p class="block-ttl">Y</p>
				<ul class="cate-list">
					@foreach ($comp_Y as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
			   </ul>
			</div>

			<div id="cate-z" class="block">
				<p class="block-ttl">Z</p>
				<ul class="cate-list">
					@foreach ($comp_Z as $comp)
						 <li>
						 	<label>
								{{ html()->checkbox("comp_sel[]", 0, $comp->id)->id('comp_select')->attribute('title', $comp->name) }}
								<span>{{ $comp->name }}</span>
						 	</label>
						 </li>
					 @endforeach
				</ul>
			</div>
	
			<div class="btn-wrap">
				<button type="button" onclick="GetComp()">変更する</button>
			</div>
		</form>
	
	
	  </div>
	  <div id="closeModal" class="closeModal">
		×
	  </div>
	</div>
</div>

{{-- END 企業選択モーダル  --}}

<script>

/////////////////////////////////////////////////////////
// メインに非表示企業セット
/////////////////////////////////////////////////////////
function putComp() {
    var works = $("input[id='comp_select']:checked").map(function() {

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



//	console.log(titleList);

    if (valList == ""){
        $("#comp_list").html("<li><span>指定なし</span></li>");
        document.getElementById( "comps" ).value = "" ;
    }else{
    	$("#comp_list").html(titleList);
        document.getElementById( "comps" ).value = valList;
    }


}


/////////////////////////////////////////////////////////
// 企業選択モーダルからの戻り
/////////////////////////////////////////////////////////
function GetComp() {

	putComp();
	ResetComp();

     $('body').css('overflow-y','auto');     // 本文の縦スクロールを有効
	$('#modalAreaName').fadeOut();
}



/////////////////////////////////////////////////////////
// 企業モーダル設定
/////////////////////////////////////////////////////////
function ResetComp() {

	var comps = document.getElementById("comps").value;

	var boxes = document.getElementsByName("comp_sel[]");
	var cnt = boxes.length;
	var inx = 0;

	if (comps != null) {
		var comp_list = comps.split(',');

		for (var i = 0; i < cnt; i++) {
			for (const element of comp_list) {
				if (boxes[i].value == element) {
					boxes[i].checked = true;
				}
			}
		}
  }
}


</script>
