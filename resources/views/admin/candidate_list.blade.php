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

                           <div class="secBtnHead">
                                <div class="secBtnHead-btn">
                                    <ul class="item-btn">
                                        <li><a href="#modal" class="squareBtn">絞り込み</a></li>
                                    </ul><!-- /.item -->
                                </div><!-- /.secBtnHead-btn -->
                           </div>

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
                                        {{ Form::open(['url' => '/admin/user/detail', 'name' => 'userform' . $int->id ]) }}
                                        {{ Form::hidden('user_id', $int->id) }}
										{{ Form::hidden('parent_id', '2') }}
                                        <a href="javascript:userform{{ $int->id }}.submit()" style="text-decoration: underline;">{{ $int->name }}</a>
                                        {{ Form::close() }}
                                    </td>
                                    <td>{{ $int->age }}</td>
                                    <td>{{ $int->company }}</td>
                                    <td>{{ mb_strimwidth($int->job_content, 0, 40, "...") }}</td>
                                    <td>{{ $int->job_title }}</td>
                                    <td>{{ $int->graduation . ' ' . $int->department }}</td>
                                    <td align="center">{{ $int->ote_income }} 万円</td>
                                    <td>{{ $int->location_name }}</td>
                                </tr>
                              @endforeach

                            </table>
                            <div class="pager">
                               {{ $userList->appends( $search)->links('pagination.admin') }}
                            </div>
@endif

						</div><!-- /.secContentsInner -->
					</section><!-- /.secContents-mb -->
				</div><!-- /.containerContents -->
            </div><!-- /.mainContentsInner-oneColumn -->


<!-- モーダル -->
		<div class="remodal" data-remodal-id="modal">
			{{ Form::open(['url' => 'admin/candidate/list', 'name' => 'modalform' ,'method' => 'GET' ]) }}
			<div class="modalTitle">
				<h2>絞り込み</h2>
			</div><!-- /.modalTitle -->

			<div class="modalInner bb-ajust">
			
				<div class="formContainer mg-ajust">
					<div class="item-name">
						<p>年齢</p>
					</div><!-- /.item-name -->
        
					<div class="item-input">
						<div class="selectWrap">
							<select name="from_age"  class="select-no">
								<option value="">指定しない</option>
								<option value="20" @if ($search['from_age'] == '20')  selected @endif>20代</option>
								<option value="30" @if ($search['from_age'] == '30')  selected @endif>30代</option>
								<option value="40" @if ($search['from_age'] == '40')  selected @endif>40代</option>
								<option value="50" @if ($search['from_age'] == '50')  selected @endif>50代</option>
							</select>
						</div>
					</div>
					<div>　～　
					</div>
					<div class="item-input">
						<div class="selectWrap">
							<select name="to_age"  class="select-no">
								<option value="">指定しない</option>
								<option value="20" @if ($search['to_age'] == '20')  selected @endif>20代</option>
								<option value="30" @if ($search['to_age'] == '30')  selected @endif>30代</option>
								<option value="40" @if ($search['to_age'] == '40')  selected @endif>40代</option>
								<option value="50" @if ($search['to_age'] == '50')  selected @endif>50代</option>
							</select>
	 					</div>
					</div><!-- /.item-input -->
				</div><!-- /.formContainer -->


				<div class="formContainer mg-ajust">
					<div class="item-name">
						<p>現在の職種</p>
					</div><!-- /.item-name -->
        
					<div class="item-input">
						<div class="selectWrap harf">
							<select name="current_job"  class="select-no">
								<option value="">指定しない</option>
								@foreach ($jobCat as $cat)
								<option value="{{ $cat->id }}" @if ($search['current_job'] == $cat->id)  selected @endif>{{ $cat->name }}</option>
								@endforeach
							</select>
						</div>
					</div><!-- /.item-input -->
				</div><!-- /.formContainer -->


				<div class="formContainer mg-ajust">
					<div class="item-name">
						<p>希望勤務地</p>
					</div><!-- /.item-name -->
            

					<div class="item-input">
						<div class="selectWrap harf">
							<select name="location"  class="select-no">
								<option value="">指定しない</option>
								@foreach ($constLocation as $loc)
								<option value="{{ $loc->id }}" @if ($search['location'] == $loc->id)  selected @endif>{{ $loc->name }}</option>
								@endforeach
							</select>
						</div><!-- /.item-input -->
					</div><!-- /.formContainer -->
				</div><!-- /.formContainer -->
	

				<div class="formContainer mg-ajust">
				<div class="item-name">
					<p>転職を希望するカテゴリ</p>
				</div><!-- /.item-name -->
            
				<div class="item-input">
					<div class="selectWrap harf">
						<select name="request_cat"  class="select-no">
							<option value="">指定しない</option>
							@foreach ($jobCat as $cat)
							<option value="{{ $cat->id }}" @if ($search['request_cat'] == $cat->id)  selected @endif>{{ $cat->name }}</option>
							@endforeach
						</select>
					</div>
				</div><!-- /.item-input -->
			</div><!-- /.formContainer -->

			<div class="formContainer mg-ajust">
				<div class="item-name">
					<p>フリーワード</p>
				</div><!-- /.item-name -->
        
				<div class="item-input">
					<input type="text" name="freeword" value="{{ $search['freeword'] }}">
				</div><!-- /.item-input -->
			</div><!-- /.formContainer -->

		</div><!-- /.modalInner -->

     
		<div class="btnContainer">
			<a href="javascript:modalform.submit()" class="squareBtn btn-large">絞り込む</a>
		</div><!-- /.btn-container -->
		{{ html()->form()->close() }}
	</div>
<!-- モーダル END -->


@endsection
