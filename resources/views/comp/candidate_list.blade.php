@extends('layouts.comp.auth')

@section('content')

<head>
	<title>候補者管理｜{{ config('app.name', 'Laravel') }}</title>
</head>

            <div class="mainContentsInner-oneColumn">

				<div class="secTitle">
					<div class="title-main">
						<h2>候補者管理</h2>
					</div><!-- /.mainTtl -->
				</div><!-- /.sec-title -->
               
				<div class="containerContents">
                    
					<section class="secContents-mb">
						<div class="secContentsInner">
{{--
							<div class="secBtnHead">
								<div class="secBtnHead-btn">
									<ul class="item-btn">
										<li><a href="#modal" class="squareBtn">絞り込み</a></li>
									</ul><!-- /.item -->
								</div><!-- /.secBtnHead-btn -->
							</div>
--}}

							{{ Form::open(['url' => 'comp/candidate/list', 'name' => 'modalform' ]) }}
							<div class="formContainer mg-ajust"  style="display: flex;justify-content: space-between;margin-bottom: 0px;">

								<div class="item-name" style="width:auto;">
									<p style="width:35px;">状況</p>
								</div><!-- /.item-name -->

								<div style="display: flex;align-items: center;">
									<div class="item-input" style="padding:0px;width:auto;">
										<div class="selectWrap" style="width:120px;padding:0px;">
											<select name="result"  class="select-no">
												<option value="">指定しない</option>
												<option value="0">コンタクト中</option>
												@foreach ($constResult as $res)
													<option value="{{ $res->id }}" @if (old('result') == $res->id)  selected @endif>{{ $res->name }}</option>
												@endforeach
											</select>
										</div>
									</div><!-- /.item-input -->
								</div>

								<div style="display: flex;align-items: center;">
									<div class="item-name" style="width:auto;">
										<p style="width:35px;">年齢</p>
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
										<p">フリーワード</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<input type="text" name="freeword" value="{{ $search['freeword'] }}"  style="width:300px;">
									</div><!-- /.item-input -->
								</div>

								<div class="secBtnHead">
									<div class="secBtnHead-btn" style="margin-top: 20px;">
										<ul class="item-btn" >
											<li><a href="javascript:modalform.submit()" class="squareBtn">検索</a></li>
										</ul><!-- /.item -->
									</div><!-- /.secBtnHead-btn -->
								</div>

							</div>
							{{ Form::close() }}



							@if(!isset($userList[0]))
  								<div>※データはありません。</div>
							@else
								<p style="text-align: center;">全{{ $userList->total() }}件中 {{  ($userList->currentPage() -1) * $userList->perPage() + 1}}-{{ (($userList->currentPage() -1) * $userList->perPage() + 1) + (count($userList) -1)  }}件</p>
								<table class="tbl-candidate-9th"" id="userTable">
									<tr>
										<th>最終更新</th>
										<th>氏名</th>
										<th>状況</th>
										<th>年齢</th>
										<th>勤務先</th>
										<th>現在の職種</th>
										<th>役職</th>
										<th>最終学歴</th>
										<th>転職を希望する職種</th>
									</tr>
                               
								@foreach ($userList as $int)
									<tr>
										<td>{{ $int->last_update }}</td>
										<td>
											{{ Form::open(['url' => '/comp/user/detail', 'name' => 'userform' . $int->id ]) }}
											{{ Form::hidden('user_id', $int->id) }}
											{{ Form::hidden('parent_id', '2') }}
											<a href="javascript:userform{{ $int->id }}.submit()" style="text-decoration: underline;">{{ $int->name }}</a>
											{{ Form::close() }}
										</td>
										<td>
											@if ( $int->result_name == '')
												コンタクト中
											@else
												{{ $int->result_name }}
											@endif
										</td>
										<td>{{ $int->age }}</td>
										<td>{{ $int->company }}</td>
										<td>{{-- $int->job_cat --}}</td>
										<td>{{ $int->job_title }}</td>
										<td>{{ $int->graduation . ' ' .$int->department }}</td>
										<td>{{ $int->cat_names }}</td>
									</tr>
								@endforeach

								</table>
								<div class="pager">
									{{ $userList->appends($search)->links('pagination.comp') }}
								</div>
							@endif

						</div><!-- /.secContentsInner -->
					</section><!-- /.secContents-mb -->
				</div><!-- /.containerContents -->
            </div><!-- /.mainContentsInner-oneColumn -->


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
