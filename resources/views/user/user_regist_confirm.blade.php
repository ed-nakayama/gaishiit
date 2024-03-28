@extends('layouts.user.auth')

@section('breadcrumbs')
	{{ Breadcrumbs::render('register_confirm') }}
@endsection


@section('addheader')
	<title>基本情報（確認）｜{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/career0.css') }}" rel="stylesheet">
@endsection


@section('content')


<main class="pane-main">

	<div class="inner">
		<div class="ttl">
			<h2>基本情報（確認）</h2>
		</div>

		<div class="con-wrap">

			{{ html()->form('POST', '/register')->id('regform')->attributes(['name' => 'regform','onsubmit' => 'return formSubmit();'])->open() }}
			<div class="item setting">
				<div class="item-inner">
					<div class="setting-list">

						 <div class="item-block">
							<p class="ttl">転職を希望する業種 *</p>
							<div class="form-wrap">
								<div class="form-block">
									<div class="form-inner">
										<div class="check-box-btn">
											{{ html()->hidden('business_cats', $reg->business_cats) }}
											<ul id="buscat_list">
											</ul>
										</div>
										@error('business_cats')
											<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
										@enderror
									</div>
								</div>
							</div>
						</div>


						 <div class="item-block">
							<p class="ttl">転職を希望する職種 *</p>
								<div class="form-wrap">
									<div class="form-block">
										<div class="form-inner">
											<div class="check-box-btn">
												{{ html()->hidden('job_cat_details', $reg->job_cat_details) }}
												<ul id="jobcat_list">
												</ul>
											</div>
											@error('job_cat_details')
												<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
											@enderror
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
 			</div><!-- item setting -->

			 <div class="item setting">
				<div class="item-inner">
					<div class="setting-list">
						<div class="item-block">
							<p class="ttl">非表示企業</p>
							<div class="form-wrap">
								<div class="form-block">
									<div class="form-inner">
										<div class="check-box-btn">
											{{ html()->hidden('no_company', $reg->no_company) }}
											<ul id="comp_list">
											</ul>
										</div>
									</div>
								</div>
								<font color="red">※あなたの転職情報を開示したくない企業（現在の在籍企業や、過去に在籍していた企業など）がある場合は選択してください。</font>
							</div>
						</div>
					</div>
				</div>
			</div><!-- item setting -->



			<div class="item setting">
				<div class="item-inner">

					<div class="setting-list">

						<div class="item-block">
							<p class="ttl">氏名 *</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="input-wrap">
										{{ html()->text('user_name', $reg->user_name)->disabled() }}
										{{ html()->hidden('user_name', $reg->user_name) }}
									</div>
								</div>
								@error('user_name')
									<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
								@enderror
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">メールアドレス *</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="input-wrap">
										{{ html()->text('email', $reg->email)->disabled() }}
										{{ html()->hidden('email', $reg->email) }}
									</div>
								</div>
								@error('email')
									<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
								@enderror
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">生年月日 *</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<label for="">
										<div class="input-wrap">
											{{ html()->hidden('birthday', $reg->birthday) }}
											{{ html()->text('birthday', substr($reg->birthday,0,4) . '年' . substr($reg->birthday,5,2) . '月' . substr($reg->birthday,8,2) . '日')->disabled() }}
										</div>
									</label>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">性別 *</p>
							<div class="form-wrap">
								<div class="form-block">
									<div class="form-inner">
										<div class="check-box-btn">
											<label>{{ html()->checkbox('sex',($reg->sex == '1') ,'1')->disabled() }}<span>男性</span></label>
											<label>{{ html()->checkbox('sex',($reg->sex == '2') ,'2')->disabled() }}<span>女性</span></label>
											<label>{{ html()->checkbox('sex',($reg->sex == '0') ,'3')->disabled() }}<span>選択しない</span></label>
											{{ html()->hidden('sex', $reg->sex) }}
										</div>
										@error('sex')
											<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
										@enderror
									</div>
								</div>
							</div>
						</div>


						<div class="item-block">
							<p class="ttl">転職希望時期 *</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="form-block">
											<div class="form-inner">
												<div class="check-box-btn">
													<label>{{ html()->checkbox('change_time', ($reg->change_time == '1'), '1')->disabled() }}<span>今すぐ</span></label>
													<label>{{ html()->checkbox('change_time', ($reg->change_time == '2'), '2')->disabled() }}<span>希望の転職時期がある</span></label>
													{{ html()->hidden('change_time', $reg->change_time) }}
												</div>
												@error('change_time')
													<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
												@enderror
											</div>
										</div>

										@if ($reg->change_time == '2')
											<div class="form-inner contact">
												<div class="contact-list">
													<div class="input-wrap">
														{{ html()->hidden('change_year', $reg->change_year) }}
														{{ html()->hidden('change_month', $reg->change_month) }}
														{{ html()->text('change_year_month', $reg->change_year . '年' . $reg->change_month . '月頃')->disabled() }}
													</div>
													<ul class="oneRow">
														@error('change_time')
															<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
														@enderror
														@error('change_year')
															<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
														@enderror
														@error('change_month')
															<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
														@enderror
														@error('change_day')
															<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
														@enderror
													</ul>
												</div>
											</div>
										@endif
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">最終学歴 *</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="input-wrap">
										{{ html()->text('graduation', $reg->graduation)->disabled() }}
										{{ html()->hidden('graduation', $reg->graduation) }}
									</div>
								</div>
								@error('graduation')
									<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
								@enderror
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">現在の勤務先 *</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="input-wrap">
										{{ html()->text('company', $reg->company)->disabled() }}
										{{ html()->hidden('company', $reg->company) }}
									</div>
								</div>
								@error('company')
									<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
								@enderror
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">役職 *</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="input-wrap">
										{{ html()->text('job_title', $reg->job_title)->disabled() }}
										{{ html()->hidden('job_title', $reg->job_title) }}
									</div>
								</div>
								@error('job_title')
									<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
								@enderror
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">職務内容 *</p>
							<div class="form-inner contact" style="width:100%;">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="form-block">
											<div class="form-inner">
												<div class="check-box-btn">
													<label>{{ html()->checkbox('job', ($reg->job == '1'), '1')->disabled() }}<span>IC</span></label>
													<label>{{ html()->checkbox('job', ($reg->job == '2'), '2')->disabled() }}<span>Management</span></label>
													{{ html()->hidden('job', $reg->job) }}
												</div>
											</div>
										</div>

										<div class="contact-list">
											@if ($reg->job == '2')
												<div class="input-wrap">
													{{ html()->text('job_title', $reg->mgr_year . '年　' . $reg->mgr_member . '人')->disabled() }}
													{{ html()->hidden('mgr_year', $reg->mgr_year) }}
													{{ html()->hidden('mgr_member', $reg->mgr_member) }}
												</div>
											@endif

											<div class="input-wrap">
												{{ html()->textarea('job_content', $reg->job_content)->attributes(['style' => 'width:100%;', 'rows' => '4'])->disabled() }}
												{{ html()->hidden('job_content', $reg->job_content) }}
	
											</div>
										</div>
										<ul class="oneRow">
											@error('job')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
											@error('mgr_year')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
											@error('mgr_member')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
											@error('job_content')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
										</ul>
									</div>
								</div>
							</div>
						</div>


						<div class="item-block">
							<p class="ttl">過去3年の平均実績（Actual Earnings）</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="input-wrap">
										{{ html()->text('actual_income', $reg->actual_income)->class("short")->disabled() }} 万円
										{{ html()->hidden('actual_income', $reg->actual_income) }}
									</div>
								</div>
								@error('actual_income')
									<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
								@enderror
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">理論年収（OTE）</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="input-wrap">
										{{ html()->text('ote_income', $reg->ote_income)->class("short")->disabled() }} 万円
										{{ html()->hidden('ote_income', $reg->ote_income) }}
									</div>
								</div>
								@error('ote_income')
									<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
								@enderror
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">過去の勤務先</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="input-wrap">
										{{ html()->text('old_company', $reg->old_company)->class("long")->disabled() }}
										{{ html()->hidden('old_company', $reg->old_company) }}
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">希望勤務地 *</p>
							<div class="form-wrap">
								<div class="form-block">
									<div class="form-inner">
										<div class="check-box-btn">
											@foreach ($constLocation as $loc)
												<label>
													{{ html()->checkbox('request_location[]', (in_array($loc->id, (array) $reg->request_location)), $loc->id)->disabled() }}<span>{{$loc->name}}</span>
													@if ( in_array($loc->id, (array) $reg->request_location) )
														{{ html()->hidden('request_location[]', $loc->id) }}
													@endif
												</label>
											@endforeach
										</div>
										<div class="input-wrap" id="changeElseLocation">
											{{ html()->text('else_location', $reg->else_location)->class("long")->disabled() }}
											{{ html()->hidden('else_location', $reg->else_location) }}
										</div>
										@error('request_location')
											<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
										@enderror
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">キャリアに関する希望 *</p>
							<div class="form-inner contact" style="width:100%;">
								<div class="contact-list">
									<div class="input-wrap">
										{{ html()->textarea('request_carrier', $reg->request_carrier)->attributes(['style' => 'width:100%;', 'rows' => '10'])->disabled() }}
										{{ html()->hidden('request_carrier', $reg->request_carrier) }}
									</div>
									@error('request_carrier')
										<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
									@enderror
								</div>
							</div>
						</div>

						<div class="btn-wrap">
							<button type="submit" onclick="javascript:history.back();return false;">戻る</button>　　
							<button type="submit" >登録を申し込む</button>
						</div>

					</div>
				</div>
			</div>
			{{ html()->form()->close() }}
		</div>
    
	</div>
</main>


{{-- 職種選択モーダル  --}}
        <div id="modalAreaJob" class="modalAreaJob modalSort">
            <div id="modalBg" class="modalBg"></div>
            <div class="modalWrapper">
              <div class="modalContents">
                <h3>職種を選ぶ</h3>    
                <form action="" onsubmit="return false" >

                    @foreach ($jobCat as $cat)
                    	<div id="" class="block">
                        	<p class="block-ttl">{{ $cat->name }}</p>
                        	<ul class="cate-list">
                            @foreach ($jobCatDetail as $detail)
                            	@if ($detail->job_cat_id == $cat->id)
                            		<li>
                            			<label><input type="checkbox" value="{{ $detail->id }}" name="jobcat_sel[]" title="{{ $detail->name }}"  id="jobcat_select"><span style="word-break: break-word;">{{ $detail->name }}</span></label>
                            		</li>
                            	@endif
			                @endforeach
                        	</ul>
                    	</div>
                    @endforeach

                    <div class="btn-wrap">
                        <button type="submit" onclick="GetJob()">保存する</button>
                    </div>
    
                </form>
    
              </div>
              <div id="closeModal" class="closeModal">
                ×
              </div>
            </div>
        </div>
{{-- END 職種選択モーダル  --}}


{{-- 業種選択モーダル  --}}
        <div id="modalAreaSort" class="modalAreaIndustry modalSort">
            <div id="modalBg" class="modalBg"></div>
            <div class="modalWrapper">
              <div class="modalContents">
                <h3>業種を選ぶ</h3>    
                <form action="" onsubmit="return false" >
    
                  	@foreach ($businessCat as $cat)
                    	<div id="" class="block">
                        	<p class="block-ttl">{{ $cat->name }}</p>
                        	<ul class="cate-list">
                            @foreach ($businessCatDetail as $detail)
                            	@if ($detail->business_cat_id == $cat->id)
                            		<li>
                            			<label><input type="checkbox" value="{{ $detail->id }}"   name="buscat_sel[]" title="{{ $detail->name }}" id="buscat_select"><span style="word-break: break-word;">{{ $detail->name }}</span></label>
                            		</li>
                            	@endif
			                @endforeach
                        	</ul>
                    	</div>
                    @endforeach

                    <div class="btn-wrap">
                        <button type="submit" onclick="GetBus()">保存する</button>
                    </div>
    
                </form>
    
              </div>
              <div id="closeModal" class="closeModal">
                ×
              </div>
            </div>
        </div>
{{-- END 業種選択モーダル  --}}


{{-- 企業選択モーダル  --}}
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
                <h1>あなたの情報を開示する対象企業一覧</h1>
                <h3>非表示先を選択してください</h3>    
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
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]"  title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
    
                    <div id="cate-b" class="block">
                        <p class="block-ttl">B</p>
                        <ul class="cate-list">
                            @foreach ($comp_B as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
    
                    <div id="cate-c" class="block">
                        <p class="block-ttl">C</p>
                        <ul class="cate-list">
                            @foreach ($comp_C as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-d" class="block">
                        <p class="block-ttl">D</p>
                        <ul class="cate-list">
                            @foreach ($comp_D as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-e" class="block">
                        <p class="block-ttl">E</p>
                        <ul class="cate-list">
                            @foreach ($comp_E as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-f" class="block">
                        <p class="block-ttl">F</p>
                        <ul class="cate-list">
                            @foreach ($comp_F as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-g" class="block">
                        <p class="block-ttl">G</p>
                        <ul class="cate-list">
                            @foreach ($comp_G as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-h" class="block">
                        <p class="block-ttl">H</p>
                        <ul class="cate-list">
                            @foreach ($comp_H as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{ $comp['name'] }}" value="{{ $comp['id'] }}"><span>{{ $comp['name'] }}</span></label>
                                 </li>
                             @endforeach
                    </div>
                    <div id="cate-i" class="block">
                        <p class="block-ttl">I</p>
                        <ul class="cate-list">
                            @foreach ($comp_I as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-j" class="block">
                        <p class="block-ttl">J</p>
                        <ul class="cate-list">
                            @foreach ($comp_J as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-k" class="block">
                        <p class="block-ttl">K</p>
                        <ul class="cate-list">
                            @foreach ($comp_K as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-l" class="block">
                        <p class="block-ttl">L</p>
                        <ul class="cate-list">
                            @foreach ($comp_L as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-m" class="block">
                        <p class="block-ttl">M</p>
                        <ul class="cate-list">
                            @foreach ($comp_M as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-n" class="block">
                        <p class="block-ttl">N</p>
                        <ul class="cate-list">
                            @foreach ($comp_N as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-o" class="block">
                        <p class="block-ttl">O</p>
                        <ul class="cate-list">
                            @foreach ($comp_O as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-p" class="block">
                        <p class="block-ttl">P</p>
                        <ul class="cate-list">
                            @foreach ($comp_P as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-q" class="block">
                        <p class="block-ttl">Q</p>
                        <ul class="cate-list">
                            @foreach ($comp_Q as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-r" class="block">
                        <p class="block-ttl">R</p>
                        <ul class="cate-list">
                            @foreach ($comp_R as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-s" class="block">
                        <p class="block-ttl">S</p>
                        <ul class="cate-list">
                            @foreach ($comp_S as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-t" class="block">
                        <p class="block-ttl">T</p>
                        <ul class="cate-list">
                            @foreach ($comp_T as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-u" class="block">
                        <p class="block-ttl">U</p>
                        <ul class="cate-list">
                            @foreach ($comp_U as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-v" class="block">
                        <p class="block-ttl">V</p>
                        <ul class="cate-list">
                            @foreach ($comp_V as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-w" class="block">
                        <p class="block-ttl">W</p>
                        <ul class="cate-list">
                            @foreach ($comp_W as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-x" class="block">
                        <p class="block-ttl">X</p>
                        <ul class="cate-list">
                            @foreach ($comp_X as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-y" class="block">
                        <p class="block-ttl">Y</p>
                        <ul class="cate-list">
                            @foreach ($comp_Y as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                       </ul>
                    </div>
                    <div id="cate-z" class="block">
                        <p class="block-ttl">Z</p>
                        <ul class="cate-list">
                            @foreach ($comp_Z as $comp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel[]" title="{{$comp['name']}}" value="{{$comp['id']}}"><span>{{$comp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
    
                    <div class="btn-wrap">
                        <button type="button" onclick="GetComp()">保存する</button>
                    </div>
				</form>
    
    
              </div>
              <div id="closeModal" class="closeModal">
                ×
              </div>
            </div>
        </div>
{{-- END 企業選択モーダル  --}}


{{--
/**********************************************************
*  Javascript
***********************************************************/
--}}

<script>
/////////////////////////////////////////////////////////
// 性別　１つのみ選択
/////////////////////////////////////////////////////////
$("[name='sex']").on("click", function(){
	if ($(this).prop('checked')){
    	$("[name='sex']").prop('checked', false);
        $(this).prop('checked', true);
    }
});


/////////////////////////////////////////////////////////
// 転職時期　１つのみ選択
/////////////////////////////////////////////////////////
$("[name='change_time']").on("click", function(){
	if ($(this).prop('checked')){
    	$("[name='change_time']").prop('checked', false);
        $(this).prop('checked', true);
    }
});


/////////////////////////////////////////////////////////
// 職務内容　１つのみ選択
/////////////////////////////////////////////////////////
$("[name='job']").on("click", function(){
	if ($(this).prop('checked')){
    	$("[name='job']").prop('checked', false);
        $(this).prop('checked', true);
    }
});


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
        document.getElementById( "no_company" ).value = "" ;
    }else{
    	$("#comp_list").html(titleList);
        document.getElementById( "no_company" ).value = valList;
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
// メインに職種セット
/////////////////////////////////////////////////////////
function putJob() {
    var works = $("input[id='jobcat_select']:checked").map(function() {

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



    if (valList == ""){
        $("#jobcat_list").html("<li><span>選択してください</span></li>");
        document.getElementById( "job_cat_details" ).value = "" ;
    }else{
    	$("#jobcat_list").html(titleList);
        document.getElementById( "job_cat_details" ).value = valList;
    }
}


/////////////////////////////////////////////////////////
// 職種選択モーダルからの戻り
/////////////////////////////////////////////////////////
function GetJob() {

	putJob();
	ResetJob();

    $('body').css('overflow-y','auto');     // 本文の縦スクロールを有効
	$('#modalAreaJob').fadeOut();
}



/////////////////////////////////////////////////////////
// メインに職種セット
/////////////////////////////////////////////////////////
function putBus() {
    var works = $("input[id='buscat_select']:checked").map(function() {

        return {
			'title': this.title,
			'val': this.value
		}
	});

//	console.log("hogehoge");

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
			
		titleList = titleList  +  "<li><span>" + works[i]['title'] +   "</span></li>\n";
	};


    if (valList == ""){
        $("#buscat_list").html("<li><span>選択してください</span></li>");
        document.getElementById( "business_cats" ).value = "" ;
    }else{
    	$("#buscat_list").html(titleList);
        document.getElementById( "business_cats" ).value = valList;
    }
}


/////////////////////////////////////////////////////////
// 業種選択モーダルからの戻り
/////////////////////////////////////////////////////////
function GetBus() {

	putBus();
	ResetBus();

    $('body').css('overflow-y','auto');     // 本文の縦スクロールを有効
	$('#modalAreaSort').fadeOut();
}


/////////////////////////////////////////////////////////
// 企業モーダル設定
/////////////////////////////////////////////////////////
function ResetComp() {

	var comps = document.getElementById("no_company").value;

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


/////////////////////////////////////////////////////////
// 職種モーダル設定
/////////////////////////////////////////////////////////
function ResetJob() {

	var job_cat = document.getElementById("job_cat_details").value;

	boxes = document.getElementsByName("jobcat_sel[]");
	var cnt = boxes.length;
	var inx = 0;

	if (job_cat != null) {
		var job_list = job_cat.split(',');

		for (var i = 0; i < cnt; i++) {
			for (const element of job_list) {
				if (boxes[i].value == element) {
					boxes[i].checked = true;
				}
			}
		}
    }
}






/////////////////////////////////////////////////////////
// 業種モーダル設定
/////////////////////////////////////////////////////////
function ResetBus() {

	var bus_cat = document.getElementById("business_cats").value;

	boxes = document.getElementsByName("buscat_sel[]");
	var cnt = boxes.length;
	var inx = 0;

	if (bus_cat != null) {
		var bus_list = bus_cat.split(',');

		for (var i = 0; i < cnt; i++) {
			for (const element of bus_list) {
				if (boxes[i].value == element) {
					boxes[i].checked = true;
				}
			}
		}
    }
}


/*****************************************************************
* submit時
******************************************************************/
function formSubmit() {

	document.getElementById("birthday").value
		= toBirthday(document.getElementById("js_year").value,
			document.getElementById("js_month").value,
			document.getElementById("js_day").value);

	return true;
}


/////////////////////////////////////////////////////////
// 初回起動
/////////////////////////////////////////////////////////
$(document).ready(function() {

	// 選択企業対応
	ResetComp();
	putComp();

	// 選択職種対応
	ResetJob();
 	putJob();

	// 選択業種対応
	ResetBus();
 	putBus();
 
	
});


</script>


<script src="{{ asset('js/career.js') }}"></script>

@endsection
