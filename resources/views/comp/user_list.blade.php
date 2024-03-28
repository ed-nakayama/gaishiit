@extends('layouts.comp.auth')

@section('content')

<head>
	<title>新しい候補者を探す｜{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('comp/assets/css/seek.css') }}" rel="stylesheet">
</head>

@include('comp.member_activity')

            <div class="mainContentsInner">

                <div class="secTitle">
                    <div class="title-main">
                        <h2>新しい候補者を探す</h2>
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
                           <table class="tbl-10th" id="userTable">
                                <tr>
                                    <th>登録日</th>
                                    <th>候補者番号/氏名</th>
                                    <th>年齢</th>
                                    <th>勤務先</th>
                                    <th>現在の職務内容</th>
                                    <th>役職</th>
                                    <th>最終学歴</th>
                                    <th>転職希望時期</th>
                                    <th>希望勤務地</th>
                                    <th>転職を希望するカテゴリ</th>
                                </tr>
                               
                              @foreach ($userList as $int)
                                <tr onclick="javascript:userform{{ $int->id }}.submit()">
                                {{ Form::open(['url' => '/comp/user/detail', 'name' => 'userform' . $int->id ]) }}
                                {{ Form::hidden('user_id', $int->id) }}
                                {{ Form::hidden('parent_id', '1') }}
                                    <td>{{ str_replace('-','/', substr($int->created_at, 0 ,10)) }}</td>
                                    <td>@if ($int->open_flag == '1'){{ $int->name }}@else{{ $int->nick_name }}@endif</td>
                                    <td>{{ $int->age }}</td>
                                    <td>{{ $int->company }}</td>
                                    <td>{{ mb_strimwidth($int->job_content, 0, 40, "...") }}</td>
                                    <td>{{ $int->job_title }}</td>
                                    <td>{{ $int->graduation . ' ' . $int->department }}</td>
                                    <td>
                                    @if ($int->change_time == '1')
                                        今すぐ
                                    @else
                                        {{ $int->change_year . '/' . $int->change_month . '以降' }}
                                    @endif
                                    </td>
                                    <td>{{ $int->location_name }}</td>
                                    <td>{{ $int->cat_names }}</td>
                               </a>
                               {{ Form::close() }}
                                </tr>
                              @endforeach

                            </table>
                            <div class="pager">
                               {{ $userList->appends( $search)->links('pagination.comp') }}
                            </div>
@endif

						</div><!-- /.secContentsInner -->
					</section><!-- /.secContents-mb -->
				</div><!-- /.containerContents -->
            </div><!-- /.mainContentsInner-oneColumn -->


{{-- モーダル --}}
		<div class="remodal" data-remodal-id="modal">
			{{ Form::open(['url' => 'comp/user/list', 'name' => 'modalform' ]) }}
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
               <div>　　　　　　　　　　　　　　　　　　　　　　　　　　　　
               </div>
				</div><!-- /.formContainer -->

{{--
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
--}}

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
            {{ Form::close() }}
	</div>
{{-- モーダル END --}}



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
