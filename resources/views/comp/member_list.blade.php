@extends('layouts.comp.admin')

@section('content')

<head>
	<title>責任者登録｜{{ config('app.name', 'Laravel') }}</title>
</head>

            <div class="mainContentsInner-oneColumn">

                <div class="secTitle">
                    <div class="title-main">
                        <h2>責任者登録</h2>
                    </div><!-- /.mainTtl -->
                </div><!-- /.sec-title -->


                <div class="containerContents">

                    <section class="secContents">
                        <div class="secContentsInner">
                                
                            <div class="secBtnHead">
                                <div class="secBtnHead-btn">
                                    <ul class="item-btn">
                                       <li>新規メンバー追加</li>
                                        <li>
                                            <div class="modalContainer">
                                                <a href="#modal" class="squareBtn btn-medium">まとめて追加</a>
                                            </div><!-- /.modalContainer -->
                                        </li>
                                    </ul><!-- /.item -->
                                </div><!-- /.secBtnHead-btn -->
                           </div>

                            <div class="secBtnHead">
                                <div class="secBtnHead-btn">
                                   {{ Form::open(['url' => '/comp/member/add', 'name' => 'addform' , 'id' => 'addform']) }}
                                    <ul class="item-btn">
                                       <li><input type="checkbox" name="admin_flag" value="1">管理者</li>
                                       <li style="width:400px;"><input type="text" name="solo_email" value="{{ old('solo_email') }}" placeholder="Mail" ></li>
                                       <li style="width:200px;"><input type="text" name="solo_name" value="{{ old('solo_name') }}" placeholder="氏名"></li>
                                        <li><a href="javascript:addform.submit()" class="squareBtn">追加</a></li>
                                    </ul><!-- /.item -->
                                    <ul class="item-btn">
                                       <li></li>
                                         @error('solo_email')
                                             <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                                         @enderror
                                     </ul>
                                    <ul class="item-btn">
                                       <li></li>
                                         @error('solo_name')
                                             <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                                         @enderror
                                     </ul>
                                   {{ Form::close() }}
                                </div><!-- /.secBtnHead-btn -->
                           </div>

                             <ul class="oneRow">
                             @error('email')
                                 <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                             @enderror
                             @error('name')
                                 <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                             @enderror
                             </ul>

                             <table class="tbl-member-5th" id="memberTable">
                                <tr>
                                    <th>削除</th>
                                    <th>管理者</th>
                                    <th>Mail</th>
                                    <th>氏名</th>
                                    <th></th>
                                </tr>
                                @foreach ($memberList as $mem)
                                <tr>
                               {{ Form::open(['url' => '#', 'name' => 'memberform' . $mem->id ]) }}
                                {{ Form::hidden('member_id', $mem->id) }}
                                    <td><input type="checkbox" name="del_flag" value="1"  onchange="memberChange('{{ 'membersave' . $mem->id }}')" @if ($mem->id == Auth::user()->id) disabled @endif></td>
                                    <td><input type="checkbox" name="admin_flag" value="1"  onchange="memberChange('{{ 'membersave' . $mem->id }}')"  @if ($mem['admin_flag'] == '1') checked @endif  @if ($mem->id == Auth::user()->id) disabled @endif></td>
                                    <td><input type="text" name="email" value="{{ $mem->email }}" oninput="memberChange('{{ 'membersave' . $mem->id }}')"></td>
                                    <td><input type="text" name="name" value="{{ $mem->name }}" oninput="memberChange('{{ 'membersave' . $mem->id }}')"></td>
                                     </td>
                                       
                                    <td>
                                        <div class="btnContainer"  style="display: none;" id="{{ 'membersave' . $mem->id }}">
                                            <a href="javascript:save_data({{ 'memberform' . $mem->id }});" class="squareBtn btn-medium">保存</a>
                                        </div><!-- /.btn-container -->
                                    </td>
                                        </div><!-- /.btn-container -->
                                    </td>
                               {{ Form::close() }}
                                </tr>
                                @endforeach
                            </table>
 

                        </div><!-- /.secContentsInner -->
                    </section><!-- /.secContents -->
                    
                </div><!-- /.containerContents -->

            </div><!-- /.mainContentsInner -->


{{ Form::open(['url' => '/comp/member/list', 'name' => 'memberform']) }}
{{ Form::hidden('member_id', '') }}
{{ Form::hidden('del_flag', '') }}
{{ Form::hidden('admin_flag', '') }}
{{ Form::hidden('email', '') }}
{{ Form::hidden('name', '') }}
{{ Form::close() }}


{{-- モーダル --}}
        <div class="remodal" data-remodal-id="modal">
            {{ Form::open(['url' => 'comp/member/more', 'name' => 'moreform' ]) }}
            <div class="modalTitle">
                <h2>まとめて追加</h2>
            </div><!-- /.modalTitle -->

            <div class="modalInner bb-ajust">
                 <table class="tbl-member-all mb-ajust" id="moreTable">
                  <tr>
                       <th>Mail</th>
                       <th>氏名</th>
                  </tr>
                  @for ($i = 0; $i < 10; $i++)
                  <tr>
                       <td>
                           <input type="text" name="email[{{ $i }}]" value="{{ old("email.$i") }}">
                       </td>
                       <td>
                           <input type="text" name="name[{{ $i }}]" value="{{ old("name.$i") }}" >
                       </td>
                  </tr>
                  <tr>
                       <td colspan="2">
                           <ul class="oneRow">
                           @error("email.$i")
                               <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                           @enderror
                           @error("name.$i")
                               <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                           @enderror
                           </ul>
                       </td>
                  </tr>
                  @endfor
                 </table>
            </div><!-- /.modalInner -->
     
            <div class="btnContainer">
                <a href="javascript:moreform.submit()" class="squareBtn btn-large">追加</a>
            </div><!-- /.btn-container -->
            {{ Form::close() }}
      </div>
{{-- モーダル END --}}



<script>

var pre_member ="";


function memberChange(nm) {

	if (pre_member != nm) {
		document.getElementById(nm).style.display ="block";
		if (pre_member  != "") {
			document.getElementById(pre_member).style.display ="none";
		}
		pre_member = nm;
	}
}


function save_data(frm) {

//console.log(frm);

	document.memberform.elements["member_id"].value = frm.elements["member_id"].value;
	
	 if (frm.elements["del_flag"].checked) {
		document.memberform.elements["del_flag"].value = '1';
	}

	 if (frm.elements["admin_flag"].checked) {
		document.memberform.elements["admin_flag"].value = '1';
	} else {
		document.memberform.elements["admin_flag"].value = '0';
	}

	document.memberform.elements["email"].value = frm.elements["email"].value;
	document.memberform.elements["name"].value = frm.elements["name"].value;

	document.memberform.submit();

}


$(document).ready(function(){
  $("#memberTable tr:even").not(':first').addClass("evenRow");
  $("#memberTable tr").not(':first').hover(
    function(){
        $(this).addClass("focusRow");
    },function(){
        $(this).removeClass("focusRow");
 });
 

@if ($errors->has('email.*') ||  $errors->has('name.*')  )
    var modal = $.remodal.lookup[$('[data-remodal-id=modal]').data('remodal')];
    modal.open();
@endif


});
</script>

<style>
#memberTable { cursor: pointer; }
.evenRow { background-color: #F5F5F5; }
.focusRow { background-color: #ffffcc; }

#alreadyTable { cursor: pointer; }
.evenRow { background-color: #F5F5F5; }
.focusRow { background-color: #ffffcc; }

</style>

@endsection
