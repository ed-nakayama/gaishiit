@extends('layouts.comp.auth')

@section('content')

<head>
	<title>新しい候補者を探す｜{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('comp/assets/css/seek.css') }}" rel="stylesheet">
</head>

<div class="mainContentsInner-oneColumn">

	<div class="mainTtl title-main">
		<h2>マイページ</h2>
	</div><!-- /.mainTtl -->
                
	<div class="containerContents">
		<section class="secContents-mb">
                    
			<div class="tab_box_no">
				<div class="btn_area">
					<p class="tab_btn msgMenu__tab"><a href="/comp/msg/casual/list">カジュアル面談</a><span class="msgMenu__badge">{{ $member_act['user_casual_cnt'] }}</span></p>
					<p class="tab_btn msgMenu__tab"><a href="/comp/msg/formal/list">正式応募</a><span class="msgMenu__badge">{{ $member_act['user_formal_cnt'] }}</span></p>
					<p class="tab_btn msgMenu__tab"><a href="/comp/msg/event/list">イベント</a><span class="msgMenu__badge">{{ $member_act['event_cnt'] }}</span></p>
					<p class="tab_btn"><a href="/comp/mypage/main">保存した条件の候補者</a></p>
					<p class="tab_btn active"><a href="/comp/mypage/newuser">新しい候補者を探す</a></p>
					<p class="tab_btn"><a href="/comp/mypage/progress">面談進捗管理</a></p>
				</div>

				<div class="secContentsInner">

					<div class="panel_area" style="padding: 10px;">
						{{ html()->form('POST', '/comp/mypage/newuser/list')->attribute('name', 'modalform')->open() }}
						<div class="formContainer mg-ajust"  style="display: flex;justify-content:space-between;margin-bottom: 0px;">

							<div style="display: flex;align-items: center;">
								<div class="item-name" style="width:auto;">
									<p>年齢</p>
								</div><!-- /.item-name -->

								<div class="item-input">
									<div class="selectWrap" style="width:120px;margin-left:0px;">
										<select name="from_age"  class="select-no">
											<option value="">指定しない</option>
											<option value="20" @if ($search['from_age'] == '20')  selected @endif>20代</option>
											<option value="30" @if ($search['from_age'] == '30')  selected @endif>30代</option>
											<option value="40" @if ($search['from_age'] == '40')  selected @endif>40代</option>
											<option value="50" @if ($search['from_age'] == '50')  selected @endif>50代</option>
										</select>
									</div>
								</div>
								<div>&nbsp;～&nbsp;
								</div>
								<div class="item-input">
									<div class="selectWrap" style="width:120px;">
										<select name="to_age"  class="select-no">
											<option value="">指定しない</option>
											<option value="20" @if ($search['to_age'] == '20')  selected @endif>20代</option>
											<option value="30" @if ($search['to_age'] == '30')  selected @endif>30代</option>
											<option value="40" @if ($search['to_age'] == '40')  selected @endif>40代</option>
											<option value="50" @if ($search['to_age'] == '50')  selected @endif>50代</option>
										</select>
				 					</div>
								</div><!-- /.item-input -->
							</div>

							<div style="display: flex;align-items: center;">
								<div class="item-name" style="width:auto;">
									<p style="width:70px;">希望勤務地</p>
								</div><!-- /.item-name -->

								<div class="item-input" style="padding:0px;">
									<div class="selectWrap" style="width:120px;padding:0px;">
										<select name="location"  class="select-no">
											<option value="">指定しない</option>
											@foreach ($constLocation as $loc)
												<option value="{{ $loc->id }}" @if ($search['location'] == $loc->id)  selected @endif>{{ $loc->name }}</option>
											@endforeach
										</select>
									</div><!-- /.formContainer -->
								</div><!-- /.item-input -->
							</div>

							<div style="display: flex;align-items: center;">
								<div class="item-name" style="width:auto;">
									<p">フリーワード</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<input type="text" name="freeword" value="{{ $search['freeword'] }}"  style="width:400px;">
								</div><!-- /.item-input -->
							</div>
						</div>

						<div class="formContainer mg-ajust"  style="display: flex;justify-content:space-between;margin-bottom: 0px;">

								<div style="display: flex;align-items: center;">
								<div class="item-name" style="width:auto;">
									<p>転職を希望する職種</p>
								</div><!-- /.item-name -->

								<div class="item-input"> 
									<div class="selectWrap harf">
										<select name="request_cat"  class="select-no">
											<option value="">指定しない</option>

											@foreach ($jobCat as $cat)
												<optgroup label="{{ $cat->name }}">
												@foreach ($jobCatDetail as $detail)
													@if ($detail->job_cat_id == $cat->id) 
													<option value="{{ $detail->id }}" @if ($search['request_cat'] == $cat->id)  selected @endif>{{ $detail->name }}</option>
													@endif
												@endforeach
												</optgroup>
											@endforeach
										</select>
									</div>
								</div>
							</div>

							<div class="secBtnHead">
								<div class="secBtnHead-btn" style="margin-top: 10px;">
									<ul class="item-btn" >
										<li><a href="javascript:modalform.submit()" class="squareBtn">検索</a></li>
									</ul><!-- /.item -->
								</div><!-- /.secBtnHead-btn -->
							</div>

						</div>
						{{ html()->form()->close() }}


@if(!isset($userList[0]))
						<div>※データはありません。</div>
@else
						<table class="tbl-mypage-newuser" id="userTable">
							<tr>
								<th>登録日</th>
								<th>候補者番号</th>
								<th>年齢</th>
								<th>勤務先</th>
								<th>現在の職務内容</th>
								<th>役職</th>
								<th>最終学歴</th>
								<th>転職希望時期</th>
								<th>希望勤務地</th>
								<th>転職を希望する職種</th>
							</tr>

							@foreach ($userList as $int)
								<tr>
									{{ html()->form('POST', '/comp/user/detail')->attribute('name', 'userform' . $int->id)->open() }}
									{{ html()->hidden('user_id', $int->id) }}
									{{ html()->hidden('parent_id', '1') }}

									<td>{{ str_replace('-','/', substr($int->created_at, 0 ,10)) }}</td>
									<td><a href="javascript:userform{{ $int->id }}.submit()">{{ $int->nick_name }}</td>
									<td>{{ $int->getAge() }}</td>
									<td>{{ $int->company }}</td>
									<td>{{ mb_strimwidth($int->job_content, 0, 40, "...") }}</td>
									<td>{{ $int->job_title }}</td>
									<td>{{ $int->graduation . ' ' . $int->department }}</td>
									<td>{{ $int->getChangeTime() }}</td>
									<td>{{ $int->getLocation() }}</td>
									<td>{{ $int->getCatDetail() }}</td>
									{{ html()->form()->close() }}
								</tr>
							@endforeach
						</table>
                            
                            	</div><!-- /.panel_area -->
                            </div><!-- /.secContentsInner -->

						</div><!-- /.tab_box_no -->
					</section><!-- /.secContents-mb -->
				</div><!-- /.containerContents -->
				
				<div class="pager">
					{{ $userList->appends( $search)->links('pagination.comp') }}
				</div>

			</div><!-- /.mainContentsInner -->
                            
@endif




<script type="text/javascript">



$(document).ready(function(){
  $("#userTable tr:even").not(':first').addClass("evenRow");
  $("#userTable tr").not(':first').hover(
    function(){
        $(this).addClass("focusRow");
    },function(){
        $(this).removeClass("focusRow");
 });
 

});
</script>

<style>
#userTable { cursor: pointer; }
.evenRow { background-color: #F5F5F5; }
.focusRow { background-color: #ffffcc; }

</style>
@endsection
