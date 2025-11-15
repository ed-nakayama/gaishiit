@extends('layouts.admin.auth')
<head>
    <title>候補者一覧 | {{ config('app.name', 'Laravel') }}</title>
</head>

@section('content')

<div class="mainContentsInner-oneColumn">

	<div class="secTitle">
		<div class="title-main">
			<h2>候補者一覧</h2>
		</div><!-- /.mainTtl -->
	</div><!-- /.sec-title -->

	<div class="containerContents">

		<section class="secContents-mb">
			<div class="secContentsInner">

				{{ html()->form('GET', '/admin/candidate/list')->attribute('name', 'modalform')->open() }}

				<div class="formContainer mg-ajust" style="width:90%;">
					<div class="item-name" style="width:40px;">
						<p>年齢</p>
					</div><!-- /.item-name -->

					<div class="item-input" style="display:flex;">
						<div class="selectWrap" style="width:120px;">
							<select name="from_age" class="select-no">
								<option value="">指定しない</option>
								<option value="20" @if ($searchHist->from_age == '20')  selected @endif>20代</option>
								<option value="30" @if ($searchHist->from_age == '30')  selected @endif>30代</option>
								<option value="40" @if ($searchHist->from_age == '40')  selected @endif>40代</option>
								<option value="50" @if ($searchHist->from_age == '50')  selected @endif>50代</option>
							</select>
						</div>
						<div style="margin-top:8px;">　～　</div>
					
						<div class="selectWrap" style="width:120px;">
							<select name="to_age"  class="select-no">
								<option value="">指定しない</option>
								<option value="20" @if ($searchHist->to_age == '20')  selected @endif>20代</option>
								<option value="30" @if ($searchHist->to_age == '30')  selected @endif>30代</option>
								<option value="40" @if ($searchHist->to_age == '40')  selected @endif>40代</option>
								<option value="50" @if ($searchHist->to_age == '50')  selected @endif>50代</option>
							</select>
	 					</div>
					</div><!-- /.item-input -->

					<div class="item-name" style="width:80px;">
						<p>現在の職種</p>
					</div><!-- /.item-name -->

					<div class="item-input">
						<div class="selectWrap">
							<select name="current_job"  class="select-no">
								<option value="">指定しない</option>
								@foreach ($jobCat as $cat)
								<option value="{{ $cat->id }}" @if ($searchHist->current_job == $cat->id)  selected @endif>{{ $cat->name }}</option>
								@endforeach
							</select>
						</div>
					</div><!-- /.item-input -->
				</div><!-- /.formContainer -->


				<div class="formContainer mg-ajust" style="width:90%;">
					<div class="item-name" style="width:80px;">
						<p>希望勤務地</p>
					</div><!-- /.item-name -->

					<div class="item-input">
						<div class="selectWrap harf">
							<select name="location"  class="select-no">
								<option value="">指定しない</option>
								@foreach ($constLocation as $loc)
								<option value="{{ $loc->id }}" @if ($searchHist->location == $loc->id)  selected @endif>{{ $loc->name }}</option>
								@endforeach
							</select>
						</div><!-- /.item-input -->
					</div><!-- /.formContainer -->

					<div class="item-name" style="width:160px;">
						<p>転職を希望するカテゴリ</p>
					</div><!-- /.item-name -->
            
					<div class="item-input">
						<div class="selectWrap">
							<select name="request_cat"  class="select-no">
								<option value="">指定しない</option>
								@foreach ($jobCat as $cat)
								<option value="{{ $cat->id }}" @if ($searchHist->request_cat == $cat->id)  selected @endif>{{ $cat->name }}</option>
								@endforeach
							</select>
						</div>
					</div><!-- /.item-input -->
				</div><!-- /.formContainer -->

				<div class="formContainer mg-ajust" style="width:90%;">
					<div class="item-name" style="width:85px;">
						<p>フリーワード</p>
					</div><!-- /.item-name -->

					<div class="item-input">
						<input type="text" name="freeword" value="{{ $searchHist->freeword }}"  style="width:400px;">
					</div><!-- /.item-input -->

					<div class="btnContainer">
						<a href="javascript:modalform.submit()" class="squareBtn btn-large" style="width:120px; line-height:10px;">検索</a>
					</div><!-- /.btn-container -->
				</div><!-- /.formContainer -->
   
				{{ html()->form()->close() }}

@if(!isset($userList[0]))
				<div>※データはありません。</div>
@else
				<p style="text-align: center;">全{{ $userList->total() }}件中 {{  ($userList->currentPage() -1) * $userList->perPage() + 1}}-{{ (($userList->currentPage() -1) * $userList->perPage() + 1) + (count($userList) -1)  }}件</p>
				<table class="tbl-candidate">
					<tr>
						<th>登録日</th>
						<th>氏名</th>
						<th>年齢</th>
						<th>勤務先</th>
						<th>現在の職務内容</th>
						<th>役職</th>
						<th>最終学歴</th>
						<th>理論年収（OTE）</th>
						<th>希望勤務地</th>
					</tr>
                               
					@foreach ($userList as $int)
						<tr>
							<td>{{ str_replace('-','/', substr($int->created_at, 0 ,10)) }}</td>
							<td>
								{{ html()->form('POST', '/admin/user/detail')->attribute('name', 'userform' . $int->id)->open() }}
								{{ html()->hidden('user_id', $int->id) }}
								{{ html()->hidden('parent_id', '2') }}
								<a href="javascript:userform{{ $int->id }}.submit()" style="text-decoration: underline;">{{ $int->name }}</a>
								{{ html()->form()->close() }}
							</td>
							<td>{{ $int->getAge() }}</td>
							<td>{{ $int->company }}</td>
							<td>{{ mb_strimwidth($int->job_content, 0, 40, "...") }}</td>
							<td>{{ $int->job_title }}</td>
							<td>{{ $int->graduation . ' ' . $int->department }}</td>
							<td align="center">{{ $int->ote_income }} 万円</td>
							<td>{{ $int->getLocation() }}</td>
						</tr>
					@endforeach

				</table>

				<div class="pager">
					{{ $userList->appends($searchHist->toArray())->links('pagination.admin') }}
				</div>
@endif

			</div><!-- /.secContentsInner -->
		</section><!-- /.secContents-mb -->
	</div><!-- /.containerContents -->
</div><!-- /.mainContentsInner-oneColumn -->

@endsection
