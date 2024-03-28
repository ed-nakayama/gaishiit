@extends('layouts.admin.auth')

@section('content')

<head>
    <title>企業登録 | {{ config('app.name', 'Laravel') }}</title>
</head>

	<div class="mainContentsInner-oneColumn">

		<div style="display:flex;justify-content: space-between;">
			<div class="mainTtl title-main">
				<h2>企業登録</h2>
			</div><!-- /.mainTtl -->
 		</div>

		<div class="containerContents">
			<section class="secContents-mb">

				<div class="tab_box_no2">
					<div class="btn_area">
						<p class="tab_btn">@if (empty($comp_id))<a href="/admin/comp/register?comp_id=">企業登録-新規作成</a>@else<a href="/admin/comp/edit?comp_id={{ $comp_id }}">企業登録-編集@endif</a></p>
						<p class="tab_btn active">@if (empty($comp_id))<a href="javascript:void(0);">企業責任者・管理者登録</a>@else<a href="/admin/member/list?company_id={{ $comp_id }}">企業責任者・管理者登録</a>@endif</p>
					</div>

					<div class="secContentsInner">
						<div class="panel_area" style="padding: 0px;">

							<div style="font-size: 20px;padding-bottom: 10px;">
								<H3>{{ $comp->name }}</H3>
							</div><!-- /.mainTtl -->

							<div style="color: red;padding-bottom: 20px;text-align: center;">
								※代理ログイン先　Mail: g-client@gaishiit.com  氏名: 英文Client名<br>
								管理者:✓　ログイン権限:✓
							</div><!-- /.mainTtl -->

                            <div class="secBtnHead">
                                <div class="secBtnHead-btn">
                                   {{ Form::open(['url' => '/admin/member/add', 'name' => 'addform' , 'id' => 'addform']) }}
                                   {{ Form::hidden('company_id', $comp->id) }}
                                    <ul class="item-btn">
                                       <li style="width: 100px;"><input type="checkbox" name="admin_flag" value="1"> 管理者</li>
                                       <li style="width: 120px;"><input type="checkbox" name="ark_priv" value="1"> ログイン権限</li>
                                       <li style="width: 400px;"><input type="text" name="solo_email" value="{{ old('solo_email') }}" placeholder="example@exsaple.com"></li>
                                       <li style="width: 200px;"><input type="text" name="solo_name" value="{{ old('solo_name') }}" placeholder="氏名"></li>
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

                             <table class="tbl-comp-member mb-ajust" id="memberTable">
                                <tr>
                                    <th>削除</th>
                                    <th>管理者</th>
                                    <th>ログイン権限</th>
                                    <th>Mail</th>
                                    <th>氏名</th>
                                    <th>パスワード</th>
                                    <th></th>
                                </tr>
                                @foreach ($memberList as $mem)
                                <tr>
                               {{ Form::open(['url' => '/admin/member/store', 'name' => 'memberform' . $mem->id ]) }}
                               {{ Form::hidden('company_id', $comp->id) }}
                               {{ Form::hidden('member_id', $mem->id) }}
                                    <td><input type="checkbox" name="del_flag" value="1"  onchange="memberChange('{{ 'membersave' . $mem->id }}')"></td>
                                    <td><input type="checkbox" name="admin_flag" value="1"  onchange="memberChange('{{ 'membersave' . $mem->id }}')"  @if ($mem->admin_flag == '1') checked @endif ></td>
                                    <td><input type="checkbox" name="ark_priv" value="1"  onchange="memberChange('{{ 'membersave' . $mem->id }}')"  @if ($mem->ark_priv == '1') checked @endif ></td>
                                    <td><input type="text" name="email" value="{{ $mem['email'] }}" oninput="memberChange('{{ 'membersave' . $mem->id }}')"></td>
                                    <td><input type="text" name="name" value="{{ $mem['name'] }}" oninput="memberChange('{{ 'membersave' . $mem->id }}')"></td>
                                    <td>{{ $mem->pw_raw }}</td>
                                       
                                    <td>
                                        <div class="btnContainer"  style="display: none;" id="{{ 'membersave' . $mem['id'] }}">
                                            <a href="javascript:memberform{{ $mem['id'] }}.submit();" class="squareBtn btn-medium">保存</a>
                                        </div><!-- /.btn-container -->
                                    </td>
								{{ html()->form()->close() }}
                                </tr>
                                @endforeach
                            </table>
 

						</div><!-- /.panel_area -->
					</div><!-- /.secContentsInner -->
				</div><!-- /.tab_box_no -->

			</section><!-- /.secContents-mb -->
		</div><!-- /.containerContents -->


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
