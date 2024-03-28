@extends('layouts.comp.auth')

@section('content')

<head>
	<title>マイページ｜{{ config('app.name', 'Laravel') }}</title>
</head>

@include('comp.member_activity')

            <div class="mainContentsInner">

                <div class="mainTtl title-main">
                    <h2>マイページ</h2>
                </div><!-- /.mainTtl -->
                
                <div class="containerContents">

                    <section class="secContents-mb">
                        <div class="secContentsInner">

                            <div class="contentsTitle-container">
                                <h2 class="title">保存した条件の候補者が見つかりました</h2>
                                <ul class="linkList"> 
                                    <li><a href="#modal">&gt;&gt;条件を編集する</a></li>
                                    <li><a href="/comp/user">&gt;&gt;全てみる</a></li>
                                </ul>
                            </div><!-- /.contentsTitle-container -->
                            
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
                                {{ Form::open(['url' => '/comp/user/detail', 'name' => 'userform' . $int->id ]) }}
                                {{ Form::hidden('user_id', $int->id) }}
                                {{ Form::hidden('parent_id', '0') }}
                                    <td>{{ str_replace('-','/', substr($int->created_at, 0 ,10)) }}</td>
                                    <td><a href="javascript:userform{{ $int->id }}.submit()">{{ $int->nick_name }}</td>
                                    <td>{{ $int->age }}</td>
                                    <td>{{ $int->company }}</td>
                                    <td>{{ mb_strimwidth($int->job_content, 0, 40, "...") }}</td>
                                    <td>{{ $int->graduation . ' ' . $int->department }}</td>
                                    <td>
                                    @if ($int->change_time == '1')
                                        今すぐ
                                    @else
                                        {{ $int->change_year . '/' . $int->change_month . '以降' }}
                                    @endif
                                    </td>
                                    <td>{{ $int->location_name }}</td>
                               {{ Form::close() }}
                                </tr>
                              @endforeach
                            </table>

                        </div>
                    </section><!-- /.secContents-mb -->
{{--
                    <section class="secContents">
                        <div class="secContentsInner">
                            <div class="contentsTitle-container">
                                <h2 class="title">お知らせ</h2>
                                <ul class="linkList">
                                    <li><a href="#">&gt;&gt;全てみる</a></li>
                                </ul>
                            </div><!-- /.contentsTitle-container -->

                            @foreach ($information as $info)
                            <div class="inforamtion-container">
                                <p class="date">{{ $info['created_at']->format('Y/m/d') }}</p>
                                <p class="text">{!! nl2br(e($info['content'])) !!}</p>
                            </div><!-- /.inforamtion-container -->
                            @endforeach

                        </div>
                    </section><!-- /.secContents -->
--}}
                </div><!-- /.containerContents -->

            </div><!-- /.mainContentsInner -->


{{-- モーダル --}}

	<div class="remodal" data-remodal-id="modal">
        {{ Form::open(['url' => 'comp/mypage/search', 'name' => 'modalform' ]) }}
        <div class="modalTitle">
            <h2>絞り込み</h2>
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
                            			<label>　<input type="checkbox" value="{{ $detail->id }}"   name="buscat_sel[]" title="{{ $detail->name }}" id="buscat_select"   @if (strpos($search['request_bus_cats'] ,$detail->id) !== false) checked @endif><span>{{ $detail->name }}</span></label>
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
                            			<label>　<input type="checkbox" value="{{ $detail->id }}" name="jobcat_sel[]" title="{{ $detail->name }}"  id="jobcat_select"  @if (strpos($search['request_job_cat_details'] ,$detail->id) !== false) checked @endif><span>{{ $detail->name }}</span></label>
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
								<label><input type="checkbox" value="{{ $loc->id }}" name="location[]"  @if (strpos($search['location'] ,$loc->id) !== false) checked @endif><span>{{$loc->name}}</span></label>
							@endforeach
						</div>
					</div><!-- /.item-input -->
				</div><!-- /.formContainer -->
			</div><!-- /.formContainer -->


		</div><!-- /.modalInner -->
     

		<div class="btnContainer">
			<a href="javascript:modalform.submit()" class="squareBtn btn-large">絞り込む</a>
		</div><!-- /.btn-container -->
		{{ Form::close() }}
	</div>
	
{{-- モーダル END --}}




@endsection