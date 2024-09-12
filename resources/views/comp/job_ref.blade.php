@extends('layouts.comp.auth')

@section('content')

<head>
	<title>求人管理｜{{ config('app.name', 'Laravel') }}</title>
</head>
<fieldset disabled>
            <div class="mainContentsInner">

                <div class="mainTtl title-main">
                   	<h2>求人管理 - 参照</h2>
                </div><!-- /.mainTtl -->

                <div class="containerContents">
                    <section class="secContents-mb">
                        <div class="secContentsInner">
                            
							<ul class="jobToggleList leftAlign">
								<li>
									<div class="button-radio">
										<input id="c_ch1" class="radiobutton" name="open_flag" type="radio" value="1"  @if ($job->open_flag == '1')  checked="checked" @endif />
										<label for="c_ch1">公開</label> /
										<input id="c_ch2" class="radiobutton" name="open_flag" type="radio" value="0"  @if ($job->open_flag == '0')  checked="checked" @endif />
										<label for="c_ch2">非公開</label> 
									</div>
								</li>
							</ul><!-- /.jobToggle -->
                       </div><!-- /.secContentsInner -->
                    </section><!-- /.secContents-mb -->
<hr>

					<section class="secContents">

                        <div class="secContentsInner">

							<ul class="jobToggleList">
								<li  style="display:flex;">
									<label for="c_ch1">このジョブ宛にカジュアル面談を受け付ける　</label>
									<div class="button-radio">
										<input id="cas_ch1" class="radiobutton" name="casual_flag" type="radio" value="1"  @if ($job->casual_flag == '1')  checked="checked" @endif />
										<label for="cas_ch1" style="padding:3px 10px;">はい</label> /
										<input id="cas_ch2" class="radiobutton" name="casual_flag" type="radio" value="0"  @if ($job->casual_flag == '0')  checked="checked" @endif />
										<label for="cas_ch2" style="padding:3px 10px;">いいえ</label> 
									</div>
								</li>
							</ul><!-- /.jobToggle -->

							@if ( isset($unitList[0]) )
								<div class="formContainer mg-ajust">
									<div class="item-name">
										<p>部門</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<div class="selectWrap harf">
											<select name="unit"  class="select-no"  @if (isset($job->id) && strpos($job->person ,Auth::user()->id) === false) disabled="disabled" @endif>
												<option value=""></option>
												@foreach ($unitList as $un)
													<option value="{{ $un->id }}" @if ($job->unit_id == $un->id)  selected @endif>{{ $un->name }}</option>
												@endforeach
											</select>
										</div>
									</div><!-- /.item-input -->
								</div><!-- END formContainer mg-ajuse -->
							@endif
                                
							<div class="formContainer mg-ajust-midashi">
								<div class="item-name">
									<p>Job Title<span>*</span></p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<input type="text"  name="name"  value="{{ $job->name }}" @if (isset($job->id) && strpos($job->person ,Auth::user()->id) === false) disabled="disabled" @endif>
								</div><!-- /.item-input -->
							</div><!-- END formContainer mg-ajuse -->
                                
							<div class="formContainer al-item-none mg-ajust">
								<div class="item-name">
									<p>紹介<span>*</span></p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<textarea class="form-mt" name="intro" id="" cols="30" rows="10" placeholder="テキスト" @if (isset($job->id) && strpos($job->person ,Auth::user()->id) === false) disabled="disabled" @endif>{{ $job->intro }}</textarea>
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->
                                
							<div class="formContainer mg-ajust">
								<div class="item-name">
									<p>ジョブID</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<input class="harf" name="job_code" type="text" value="{{ $job->job_code }}"  @if (isset($job->id) && strpos($job->person ,Auth::user()->id) === false) disabled="disabled" @endif>
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->

							<div class="formContainer mg-ajust">
								<div class="item-name">
									<p>URL</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<input name="url" type="text" value="{{ $job->url }}"  @if (isset($job->id) && strpos($job->person ,Auth::user()->id) === false) disabled="disabled" @endif>
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->


							<div class="formContainer mg-ajust">
								<div class="item-name">
									<p>職種</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<hr>
									@foreach ($jobCat as $cat)
										<div style="font-size:16px; font-weight: bold;">{{ $cat->name }}</div>
										<div style="display:flex;flex-wrap: wrap;">
											@foreach ($jobCatDetail as $detail)
												@if ($cat->id == $detail->job_cat_id)
													<div style="margin-left: 15px;">
														@if (!empty($job->getJobCategory() ))
															{{ html()->checkbox('jobCat[]', (in_array($detail->id, $job->getJobCategory() )), $detail->id) }}{{ $detail->name }}
														@else
															{{ html()->checkbox('jobCat[]', false, $detail->id) }}{{ $detail->name }}
														@endif
													</div>
												@endif
											@endforeach
										</div>
									@endforeach
									<hr>
									<br>
								</div><!-- /.item-input -->
							</div>

							<div class="formContainer mg-ajust">
								<div class="item-name">
									<p>担当業種</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<div style="display:flex;flex-wrap: wrap;">
										@foreach ($industoryCat as $cat)
												<div style="margin-left: 15px;">
													@if (!empty($job->getIndcatCat() ))
														{{ html()->checkbox('indCat[]', (in_array($cat->id, $job->getIndcatCat() )), $cat->id)->attribute('onClick', (Auth::user()->agent_priv == '1') ? 'return false;' : 'return true;') }}{{ $cat->name }}
													@else
														{{ html()->checkbox('indCat[]', false, $cat->id)->attribute('onClick', (Auth::user()->agent_priv == '1') ? 'return false;' : 'return true;') }}{{ $cat->name }}
													@endif
												</div>
										@endforeach
										<br>
									</div>
										<hr>
								</div><!-- /.item-input -->
							</div>


							<div class="formContainer mg-ajust">
								<div class="item-name">
									<p>年収<span>*</span></p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<div class="selectWrap">
										<select name="income_id"  class="select-no">
											<option value=""></option>
											@foreach ($incomeList as $income)
												<option value="{{ $income->id }}" @if ($job->income_id == $income->id)  selected @endif>{{ $income->name }}</option>
											@endforeach
										</select>
									</div>
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->
                                
							<div class="formContainer mg-ajust">
								<div class="item-name">
									<p>補足カテゴリ</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<input class="long"  name="sub_category" type="text" value="{{ $job->sub_category }}">
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->

							<div class="formContainer mg-ajust-midashi">
								<div class="item-name">
									<p>ロケーション<span>*</span></p>
								</div><!-- /.item-name -->
								<div class="item-input">

									<ul class="radioList">
										@foreach ($constLocation as $loc)
											<li><label><input type="checkbox" class="loc_list"  value="{{ $loc->id }}" name="locations[{{$loc->id}}]"  @if ($loc->id == $loc->id) checked @elseif (strpos($job->locations ,$loc->id) !== false) checked @endif><span>{{ $loc->name }}</span></label></li>
										@endforeach
											<li><label>　／　<input type="checkbox" value="1" id="remote" name="remote"    @if ($job->remote_flag == '1') checked @endif><span>リモート</span></label></li>
									</ul><!-- /.radioList -->
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->

                                
							<div class="formContainer mg-ajust" id="changeElseLocation">
								<div class="item-name">
									<p>その他ロケーション</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<input class="harf" type="text" name="else_location" id="else_location" value="{{  $job->else_location }}">
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->

							<div class="formContainer al-item-none mg-ajust">
								<div class="item-name">
									<p>勤務地詳細/その他</p>
								</div><!-- /.item-name -->
								<div class="item-input" id="changeWorking_place">
									<textarea class="form-mt" name="working_place" id="" cols="30" rows="3">{{ $job->working_place }}</textarea>
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->
                                
							<div class="formContainer bb-ajust">
								<div class="item-name">
									<p>正式応募に必要<br>な書類</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<ul class="checkboxList">
										<li><label><input type="checkbox" name="backg" value="1" @if ($job->backg_flag == '1')  checked="checked" @endif>職務経歴書</label></li>
										<li><label><input type="checkbox" name="backg_eng" value="1" @if ($job->backg_eng_flag == '1')  checked="checked" @endif>職務経歴書（英文）</label></li>
										<li><label><input type="checkbox" name="personal" value="1" @if ($job->personal_flag == '1')  checked="checked" @endif>履歴書</label></li>
									</ul><!-- /.checkboxList -->
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->
                                
                                <div class="formContainer bb-ajust">
                                    <div class="item-name">
                                        <p>担当<span>*</span></p>
                                    </div><!-- /.item-name -->
                                    <div class="item-input item-input-row">
                                        <span id="member_text" class="border border-secondary border-5 bg-white" style="padding-right: 15px;">{{ $job->getPerson() }}</span>
                                    </div><!-- /.item-input -->
                                </div>

						</div><!-- /.secContentsInner -->
					</section><!-- /.secContents -->
                   
				</div><!-- /.containerContents -->
			
			</div><!-- /.mainContentsInner -->
</fieldset>


@endsection
