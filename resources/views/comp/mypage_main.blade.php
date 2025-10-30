@extends('layouts.comp.auth')

@section('content')

<head>
	<title>マイページ｜{{ config('app.name', 'Laravel') }}</title>
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
					<p class="tab_btn  active"><a href="/comp/mypage/main">保存した条件の候補者</a></p>
					<p class="tab_btn"><a href="/comp/mypage/newuser">新しい候補者を探す</a></p>
					<p class="tab_btn"><a href="/comp/mypage/progress">面談進捗管理</a></p>
				</div>

				<div class="secContentsInner">
					<div class="panel_area" style="padding: 10px;">
						<div class="contentsTitle-container" style="margin-bottom: 5px;">
							<ul class="linkList">
								<li><a href="#modal">&gt;&gt;条件を編集する</a></li>
							</ul>
						</div>

						<table class="tbl-mypage-8th">
							<tr>
								<th>登録日</th>
								<th>候補者番号</th>
								<th>年齢</th>
								<th>勤務先</th>
								<th>現在の職務内容</th>
								<th>学歴</th>
								<th>転職希望時期</th>
								<th>希望勤務地</th>
							</tr>
							@foreach ($userList as $int)
								<tr>
									{{ html()->form('POST', '/comp/user/detail')->attribute('name', 'userform' . $int->id)->open() }}
									{{ html()->hidden('user_id', $int->id) }}
									{{ html()->hidden('parent_id', '0') }}

									<td>{{ str_replace('-','/', substr($int->created_at, 0 ,10)) }}</td>
									<td><a href="javascript:userform{{ $int->id }}.submit()">{{ $int->nick_name }}</td>
									<td>{{ $int->getAge() }}</td>
									<td>{{ $int->company }}</td>
									<td>{{ mb_strimwidth($int->job_content, 0, 40, "...") }}</td>
									<td>{{ $int->graduation . ' ' . $int->department }}</td>
									<td>{{ $int->getChangeTime() }}</td>
									<td>{{ $int->getLocation() }}</td>
									{{ html()->form()->close() }}
								</tr>
							@endforeach
						</table>
					</div><!-- /.panel_area -->
				</div><!-- /.secContentsInner -->

			</div><!-- /.tab_box_no -->
		</section><!-- /.secContents-mb -->
	</div><!-- /.containerContents -->

</div><!-- /.mainContentsInner -->


{{-- モーダル --}}

<div class="remodal" data-remodal-id="modal">
	{{ html()->form('POST', '/comp/mypage/main/list')->attribute('name', 'modalform')->open() }}
	<div class="modalTitle">
		h2>絞り込み</h2>
	</div><!-- /.modalTitle -->

	<div class="modalInner bb-ajust">
		<div class="formContainer  mg-ajust" style="border-bottom: 1px dotted #B1B1B1;">
			<div class="item-name">
				<p>年齢</p>
			</div><!-- /.item-name -->

			<div class="item-input">
				<div class="selectWrap">
					<select name="from_age"  class="select-no">
						<option value="">指定しない</option>
						<option value="20" @if (!empty($search['from_age'])) @if ($search['from_age'] == '20')  selected @endif @endif>20代</option>
						<option value="30" @if (!empty($search['from_age'])) @if ($search['from_age'] == '30')  selected @endif @endif>30代</option>
						<option value="40" @if (!empty($search['from_age'])) @if ($search['from_age'] == '40')  selected @endif @endif>40代</option>
						<option value="50" @if (!empty($search['from_age'])) @if ($search['from_age'] == '50')  selected @endif @endif>50代</option>
					</select>
				</div>
			</div>
			<div>～</div>
			<div class="item-input">
				<div class="selectWrap">
					<select name="to_age"  class="select-no">
						<option value="">指定しない</option>
						<option value="20" @if (!empty($search['to_age'])) @if ($search['to_age'] == '20')  selected @endif @endif>20代</option>
						<option value="30" @if (!empty($search['to_age'])) @if ($search['to_age'] == '30')  selected @endif @endif>30代</option>
						<option value="40" @if (!empty($search['to_age'])) @if ($search['to_age'] == '40')  selected @endif @endif>40代</option>
						<option value="50" @if (!empty($search['to_age'])) @if ($search['to_age'] == '50')  selected @endif @endif>50代</option>
					</select>
				</div>
			</div><!-- /.item-input -->
		</div><!-- /.formContainer -->


		<div class="formContainer mg-ajust" style="border-bottom: 1px dotted #B1B1B1;align-items: start;">
			<div class="item-name">
				<p>希望する業種</p>
			</div><!-- /.item-name -->

			<div class="item-input">
				@foreach ($businessCat as $cat)
					<div id="" class="block">
						<p class="block-ttl"><b>{{ $cat->name }}</b></p>
						<ul class="cate-list">
							@foreach ($businessCatDetail as $detail)
								@if ($detail->business_cat_id == $cat->id)
									<li style="display: inline-block;">
										<label><input type="checkbox" value="{{ $detail->id }}"   name="buscat_sel[]" title="{{ $detail->name }}" id="buscat_select"  @if (!empty($search['request_bus_cats'])) @if (strpos($search['request_bus_cats'] ,$detail->id) !== false) checked @endif @endif><span>{{ $detail->name }}</span></label>
									</li>
								@endif
							@endforeach
						</ul>
					</div>
				 @endforeach
			</div><!-- /.item-input -->
		</div><!-- /.formContainer -->


		<div class="formContainer mg-ajust" style="border-bottom: 1px dotted #B1B1B1;align-items: start;">
			<div class="item-name">
                   <p>希望する職種</p>
			</div><!-- /.item-name -->

			<div class="item-input">
				@foreach ($jobCat as $cat)
					<div id="" class="block">
						<p class="block-ttl"><b>{{ $cat->name }}</b></p>
						<ul class="cate-list">
							@foreach ($jobCatDetail as $detail)
								@if ($detail->job_cat_id == $cat->id)
									<li style="display: inline-block;">
										<label><input type="checkbox" value="{{ $detail->id }}" name="jobcat_sel[]" title="{{ $detail->name }}"  id="jobcat_select"   @if (!empty($search['request_job_cat_details'])) @if (strpos($search['request_job_cat_details'] ,$detail->id) !== false) checked @endif @endif><span>{{ $detail->name }}</span></label>
									</li>
								@endif
							@endforeach
						</ul>
                    	</div>
				@endforeach
			</div><!-- /.item-input -->
		</div><!-- /.formContainer -->

		<div class="formContainer mg-ajust">
			<div class="item-name">
				<p>希望勤務地</p>
			</div><!-- /.item-name -->

			<div class="item-input">
				<div class="form-inner">
					<div class="check-box-btn">
						@foreach ($constLocation as $loc)
							<label><input type="checkbox" value="{{ $loc->id }}" name="location[]"   @if (!empty($search['location'])) @if (strpos($search['location'] ,$loc->id) !== false) checked @endif @endif><span>{{$loc->name}}</span></label>
						@endforeach
					</div>
				</div><!-- /.item-input -->
			</div><!-- /.formContainer -->
		</div><!-- /.formContainer -->


	</div><!-- /.modalInner -->

	<div class="btnContainer">
		<a href="javascript:modalform.submit()" class="squareBtn btn-large">絞り込む</a>
	</div><!-- /.btn-container -->
	{{ html()->form()->close() }}
</div>
	
{{-- モーダル END --}}



@endsection