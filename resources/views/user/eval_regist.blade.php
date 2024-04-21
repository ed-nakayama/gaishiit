@extends('layouts.user.auth')


@section('addheader')
	<title>口コミ投稿｜{{ config('app.title') }}</title>
	<meta name="description" content="口コミ投稿｜{{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="口コミ投稿｜{{ config('app.title') }}" />
	<meta property="og:description" content="口コミ投稿｜{{ config('app.description') }}" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

    <link href="{{ asset('css/eval.css') }}" rel="stylesheet">
@endsection


@section('content')


@include('user.user_activity')

<main class="pane-main">

	<div class="inner">
		<div class="ttl">
			<h2>企業評価</h2>
		</div>
		@error('input_len')
			<span class="invalid-feedback" role="alert" style="color:#ff0000;"><p style="transform: rotate(0.03deg);">全項目の合計が、500文字以上になるように入力してください。</p></span>
		@enderror

		{{ html()->form('POST', '/eval/store')->id('storeform')->attribute('name', 'storeform')->open() }}
		{{ html()->hidden('input_len', '0')->id('input_len') }}

		<div class="con-wrap">

{{-- 評価企業選択 --}}
			<div class="item setting">
				<div class="item-inner">
					<div class="setting-list">
						<div class="item-block">
							<p class="ttl">評価企業選択</p>
							<div class="form-wrap">
								<div class="form-block">
									<div class="form-inner">
										<div class="check-box-btn">
											{{ html()->hidden('comp_id', $comp->id)->id('comp_id') }}
											<ul id="comp_list">{{ $comp->name }}
											</ul>
											<a  class="openModalName button-modal" href="#modalAreaName">選択</a>
										</div>
									</div>
								</div>
								@error('comp_id')
									<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span><br>
								@enderror
								<font color="red">※在籍中もしくは退職済みの企業（{{ \Carbon\Carbon::today()->format('Y') - 10 }}年1月以降に1年以上在籍）をご指定ください。</font>
							</div>
						</div>
					</div>
				</div>
			</div>
{{-- 評価企業選択 END --}}

@if ($eval->approve_flag == '1' || $eval->approve_flag == '2')
	<center>
	<br>
	<p style="color:#0000ff;transform: rotate(0.03deg);">現在審査中です。数日中に審査が完了しますのでもうしばらくお待ちください。</p>
	</center>
	<fieldset style="border:0px; padding:0;" disabled>
@elseif ($eval->approve_flag == '9')
	<center>
	<br>
	<p style="color:#ff0000;transform: rotate(0.03deg);">不適切な内容があったため否認されました。</p>
	</center>
	<fieldset style="border:0px; "padding:0;" disabled>
@else
	<fieldset style="border:0px; "padding:0;;">
@endif

{{-- 在籍情報 --}}
			<div class="item setting">
				<div class="item-inner">
					<div class="setting-list">

						<div class="item-block">
							<p class="ttl">雇用形態 <font color="red">*</font></p>
							<div class="form-wrap">
								<div class="form-block">
									<div class="form-inner">
										<div class="check-box-btn">
											<label>
												<input type="checkbox" value="1" name="emp_status" @if (old('emp_status', $eval->emp_status) == '1') checked @endif>
												<span>正社員</span>
											</label>
											<label>
												<input type="checkbox" value="2" name="emp_status" @if (old('emp_status', $eval->emp_status) == '2') checked @endif>
												<span>契約社員</span>
											</label>
											<label>
												<input type="checkbox" value="9" name="emp_status" @if (old('emp_status', $eval->emp_status) == '9') checked @endif>
												<span>その他</span>
											</label>
										</div>
										@error('emp_status')
											<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
										@enderror
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">在籍状況 <font color="red">*</font></p>
							<div class="form-wrap">
								<div class="form-block">
									<div class="form-inner">
										<div class="check-box-btn">
											<label><input type="checkbox" value="1" name="tenure_status" @if (old('tenure_status', $eval->tenure_status) == '1') checked @endif><span>現職</span></label>
											<label><input type="checkbox" value="2" name="tenure_status" @if (old('tenure_status', $eval->tenure_status) == '2') checked @endif><span>退職済</span></label>
										</div>
										@error('tenure_status')
											<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
										@enderror
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">入社形態 <font color="red">*</font></p>
							<div class="form-wrap">
								<div class="form-block">
									<div class="form-inner">
										<div class="check-box-btn">
											<label><input type="checkbox" value="1" name="join_status" @if (old('join_status', $eval->join_status) == '1') checked @endif><span>新卒入社</span></label>
											<label><input type="checkbox" value="2" name="join_status" @if (old('join_status', $eval->join_status) == '2') checked @endif><span>中途入社</span></label>
										</div>
										@error('join_status')
											<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
										@enderror
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">入社年 <font color="red">*</font></p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="contact-list">
											<div class="select-wrap" id="changeTimeSelect">
												<ul>
												<li>
													<label for="">
														<select name="join_year" id="join_year" class="select-no">
															<option value="" disabled selected style="display:none;"></option>
															@foreach ($joinYearList as $year)
																<option value="{{$year}}"   @if (old('join_year', $eval->join_year) == $year)  selected @endif>{{$year}}年</option>
															@endforeach
														</select>
													</label>
												</li>
												</ul>
												<ul class="oneRow">
													@error('join_year')
														<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
													@enderror
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">退社年 <font color="red">*</font></p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="contact-list">
											<div class="select-wrap" id="changeTimeSelect">
													<label for="">
														<select name="retire_year" id="retirement_year" class="select-no">
															<option value="" disabled selected style="display:none;"></option>
															@foreach ($leaveYearList as $year)
																<option value="{{$year}}"   @if (old('retire_year', $eval->retire_year) == $year)  selected @endif>{{$year}}年</option>
															@endforeach
																<option value="9999"  @if (old('retire_year', $eval->retire_year) == '9999')  selected @endif>在籍中</option>
														</select>
													</label>
												<ul class="oneRow">
													@error('retire_year')
														<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
													@enderror
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
						<p class="ttl">職種 <font color="red">*</font></p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="input-wrap">
										{{ html()->text('occupation', $eval->occupation)->class('long') }}
									</div>
								</div>
								@error('occupation')
									<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
								@enderror
							</div>
						</div>


						<div class="btn-wrap">
							@if (session('status') === 'success-store')
			                  	<div id="success1" class="alert alert-success"  style="color:#0000ff;">
									保存しました。
			                  	</div>
							@endif

							@if ($eval->approve_flag != '1' && $eval->approve_flag != '2' && $eval->approve_flag != '9')
								<button type="submit" value="save" style="margin:10px 0;">保存する</button>
							@endif
						</div>

					</div>
				</div>
			</div>
{{-- 在籍情報 END --}}


{{-- 評価 --}}
			<div class="item setting">
				<div class="item-inner">
					<div class="setting-list">

						<h4 style="margin: 0 0;">評価ポイント</h4>

						<div class="item-block" style="margin: 0 0;">
							<p class="ttl" style="padding-top: 10px;">給与</p>
							<div class="stars">
								<span>
									<input id="review1_5" type="radio" value="5" name="salary_point" @if (old('salary_point' ,$eval->salary_point) == '5') checked @endif><label for="review1_5">★</label>
									<input id="review1_4" type="radio" value="4" name="salary_point" @if (old('salary_point' ,$eval->salary_point) == '4') checked @endif><label for="review1_4">★</label>
									<input id="review1_3" type="radio" value="3" name="salary_point" @if (old('salary_point' ,$eval->salary_point) == '3' || old('salary_point' ,$eval->salary_point) == '') checked @endif><label for="review1_3">★</label>
									<input id="review1_2" type="radio" value="2" name="salary_point" @if (old('salary_point' ,$eval->salary_point) == '2') checked @endif><label for="review1_2">★</label>
									<input id="review1_1" type="radio" value="1" name="salary_point" @if (old('salary_point' ,$eval->salary_point) == '1') checked @endif><label for="review1_1">★</label>
								</span>
							</div>
						</div>

						<div class="item-block" style="margin: 0 0;">
							<p class="ttl" style="padding-top: 10px;">福利厚生</p>
							<div class="stars">
								<span>
									<input id="review2_5" type="radio" value="5" name="welfare_point" @if (old('welfare_point' ,$eval->welfare_point) == '5') checked @endif><label for="review2_5">★</label>
									<input id="review2_4" type="radio" value="4" name="welfare_point" @if (old('welfare_point' ,$eval->welfare_point) == '4') checked @endif><label for="review2_4">★</label>
									<input id="review2_3" type="radio" value="3" name="welfare_point" @if (old('welfare_point' ,$eval->welfare_point) == '3' || old('welfare_point' ,$eval->welfare_point) == '') checked @endif><label for="review2_3">★</label>
									<input id="review2_2" type="radio" value="2" name="welfare_point" @if (old('welfare_point' ,$eval->welfare_point) == '2') checked @endif><label for="review2_2">★</label>
									<input id="review2_1" type="radio" value="1" name="welfare_point" @if (old('welfare_point' ,$eval->welfare_point) == '1') checked @endif><label for="review2_1">★</label>
								</span>
							</div>
						</div>

						<div class="item-block" style="margin: 0 0;">
							<p class="ttl" style="padding-top: 10px;">育成</p>
							<div class="stars">
								<span>
									<input id="review3_5" type="radio" value="5" name="upbring_point" @if (old('upbring_point' ,$eval->upbring_point) == '5') checked @endif><label for="review3_5">★</label>
									<input id="review3_4" type="radio" value="4" name="upbring_point" @if (old('upbring_point' ,$eval->upbring_point) == '4') checked @endif><label for="review3_4">★</label>
									<input id="review3_3" type="radio" value="3" name="upbring_point" @if (old('upbring_point' ,$eval->upbring_point) == '3' || old('upbring_point' ,$eval->upbring_point) == '') checked @endif><label for="review3_3">★</label>
									<input id="review3_2" type="radio" value="2" name="upbring_point" @if (old('upbring_point' ,$eval->upbring_point) == '2') checked @endif><label for="review3_2">★</label>
									<input id="review3_1" type="radio" value="1" name="upbring_point" @if (old('upbring_point' ,$eval->upbring_point) == '1') checked @endif><label for="review3_1">★</label>
								</span>
							</div>
						</div>

						<div class="item-block" style="margin: 0 0;">
							<p class="ttl" style="padding-top: 10px;">法令遵守の意識</p>
							<div class="stars">
								<span>
									<input id="review4_5" type="radio" value="5" name="compliance_point" @if (old('compliance_point' ,$eval->compliance_point) == '5') checked @endif><label for="review4_5">★</label>
									<input id="review4_4" type="radio" value="4" name="compliance_point" @if (old('compliance_point' ,$eval->compliance_point) == '4') checked @endif><label for="review4_4">★</label>
									<input id="review4_3" type="radio" value="3" name="compliance_point" @if (old('compliance_point' ,$eval->compliance_point) == '3' || old('compliance_point' ,$eval->compliance_point) == '') checked @endif><label for="review4_3">★</label>
									<input id="review4_2" type="radio" value="2" name="compliance_point" @if (old('compliance_point' ,$eval->compliance_point) == '2') checked @endif><label for="review4_2">★</label>
									<input id="review4_1" type="radio" value="1" name="compliance_point" @if (old('compliance_point' ,$eval->compliance_point) == '1') checked @endif><label for="review4_1">★</label>
								</span>
							</div>
						</div>

						<div class="item-block" style="margin: 0 0;">
							<p class="ttl" style="padding-top: 10px;">社員のモチベーション</p>
							<div class="stars">
								<span>
									<input id="review5_5" type="radio" value="5" name="motivation_point" @if (old('motivation_point' ,$eval->motivation_point) == '5') checked @endif><label for="review5_5">★</label>
									<input id="review5_4" type="radio" value="4" name="motivation_point" @if (old('motivation_point' ,$eval->motivation_point) == '4') checked @endif><label for="review5_4">★</label>
									<input id="review5_3" type="radio" value="3" name="motivation_point" @if (old('motivation_point' ,$eval->motivation_point) == '3' || old('motivation_point' ,$eval->cmotivation_point) == '') checked @endif><label for="review5_3">★</label>
									<input id="review5_2" type="radio" value="2" name="motivation_point" @if (old('motivation_point' ,$eval->motivation_point) == '2') checked @endif><label for="review5_2">★</label>
									<input id="review5_1" type="radio" value="1" name="motivation_point" @if (old('motivation_point' ,$eval->motivation_point) == '1') checked @endif><label for="review5_1">★</label>
								</span>
							</div>
						</div>

						<div class="item-block" style="margin: 0 0;">
							<p class="ttl" style="padding-top: 10px;">ワークライフバランス</p>
							<div class="stars">
								<span>
									<input id="review6_5" type="radio" value="5" name="work_life_point" @if (old('work_life_point' ,$eval->work_life_point) == '5') checked @endif><label for="review6_5">★</label>
									<input id="review6_4" type="radio" value="4" name="work_life_point" @if (old('work_life_point' ,$eval->work_life_point) == '4') checked @endif><label for="review6_4">★</label>
									<input id="review6_3" type="radio" value="3" name="work_life_point" @if (old('work_life_point' ,$eval->work_life_point) == '3' || old('work_life_point' ,$eval->work_life_point) == '') checked @endif><label for="review6_3">★</label>
									<input id="review6_2" type="radio" value="2" name="work_life_point" @if (old('work_life_point' ,$eval->work_life_point) == '2') checked @endif><label for="review6_2">★</label>
									<input id="review6_1" type="radio" value="1" name="work_life_point" @if (old('work_life_point' ,$eval->work_life_point) == '1') checked @endif><label for="review6_1">★</label>
								</span>
							</div>
						</div>

						<div class="item-block" style="margin: 0 0;">
							<p class="ttl" style="padding-top: 10px;">リモート勤務</p>
							<div class="stars">
								<span>
									<input id="review7_5" type="radio" value="5" name="remote_point" @if (old('remote_point' ,$eval->remote_point) == '5') checked @endif><label for="review7_5">★</label>
									<input id="review7_4" type="radio" value="4" name="remote_point" @if (old('remote_point' ,$eval->remote_point) == '4') checked @endif><label for="review7_4">★</label>
									<input id="review7_3" type="radio" value="3" name="remote_point" @if (old('remote_point' ,$eval->remote_point) == '3' || old('remote_point' ,$eval->remote_point) == '') checked @endif><label for="review7_3">★</label>
									<input id="review7_2" type="radio" value="2" name="remote_point" @if (old('remote_point' ,$eval->remote_point) == '2') checked @endif><label for="review7_2">★</label>
									<input id="review7_1" type="radio" value="1" name="remote_point" @if (old('remote_point' ,$eval->remote_point) == '1') checked @endif><label for="review7_1">★</label>
								</span>
							</div>
						</div>

						<div class="item-block" style="margin: 0 0;">
							<p class="ttl" style="padding-top: 10px;">定年</p>
							<div class="stars">
								<span>
									<input id="review8_5" type="radio" value="5" name="retire_point" @if (old('retire_point' ,$eval->retire_point) == '5') checked @endif><label for="review8_5">★</label>
									<input id="review8_4" type="radio" value="4" name="retire_point" @if (old('retire_point' ,$eval->retire_point) == '4') checked @endif><label for="review8_4">★</label>
									<input id="review8_3" type="radio" value="3" name="retire_point" @if (old('retire_point' ,$eval->retire_point) == '3' || old('retire_point' ,$eval->retire_point) == '') checked @endif><label for="review8_3">★</label>
									<input id="review8_2" type="radio" value="2" name="retire_point" @if (old('retire_point' ,$eval->retire_point) == '2') checked @endif><label for="review8_2">★</label>
									<input id="review8_1" type="radio" value="1" name="retire_point" @if (old('retire_point' ,$eval->retire_point) == '1') checked @endif><label for="review8_1">★</label>
								</span>
							</div>
						</div>

						<div class="btn-wrap">
							@if (session('status') === 'success-store')
			                  	<div id="success2" class="alert alert-success"  style="color:#0000ff;">
									保存しました。
			                  	</div>
							@endif

							@if ($eval->approve_flag != '1' && $eval->approve_flag != '2' && $eval->approve_flag != '9')
								<button type="submit" value="save" style="margin:10px 0;">保存する</button>　
							@endif
						</div>

					</div>
				</div>
			</div>

{{-- 評価 END --}}


{{-- 評価コメント --}}
			<div class="item setting">
				<div class="item-inner">
					<div class="setting-list">

						<div class="item-block">
							<p class="ttl">理論年収（OTE）</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="input-wrap">
										{{ html()->text('ote_income', $eval->ote_income)->class('short') }}
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">給与</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="contact-list">
											<div class="input-wrap">
												<textarea name="salary_content" id="salary" cols="160" rows="4" onkeyup="ShowLength();">{{ old('salary_content', $eval->salary_content) }}</textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="inputlength"><p id="inputlength1">全項目の合計：現在 0文字</p></div>

						<div class="item-block">
							<p class="ttl">福利厚生</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="contact-list">
											<div class="input-wrap">
												<textarea name="welfare_content" id="welfare" cols="160" rows="4" onkeyup="ShowLength();">{{ old('welfare_content', $eval->welfare_content) }}</textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="inputlength"><p id="inputlength2">全項目の合計：現在 0文字</p></div>

						<div class="item-block">
							<p class="ttl">育成</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="contact-list">
											<div class="input-wrap">
												<textarea name="upbring_content" id="upbring" cols="160" rows="4" onkeyup="ShowLength();">{{ old('upbring_content', $eval->upbring_content) }}</textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="inputlength"><p id="inputlength3">全項目の合計：現在 0文字</p></div>

						<div class="item-block">
							<p class="ttl">法令遵守の意識</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="contact-list">
											<div class="input-wrap">
												<textarea name="compliance_content" id="compliance" cols="160" rows="4" onkeyup="ShowLength();">{{ old('compliance_content', $eval->compliance_content) }}</textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="inputlength"><p id="inputlength4">全項目の合計：現在 0文字</p></div>

						<div class="item-block">
							<p class="ttl">社員のモチベーション</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="contact-list">
											<div class="input-wrap">
												<textarea name="motivation_content" id="motivation" cols="160" rows="4" onkeyup="ShowLength();">{{ old('motivation_content', $eval->motivation_content) }}</textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="inputlength"><p id="inputlength5">全項目の合計：現在 0文字</p></div>

						<div class="item-block">
							<p class="ttl">ワークライフバランス</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="contact-list">
											<div class="input-wrap">
												<textarea name="work_life_content" id="work_life" cols="160" rows="4" style="width:100%;" onkeyup="ShowLength();">{{ old('work_life_content', $eval->work_life_content) }}</textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="inputlength"><p id="inputlength6">全項目の合計：現在 0文字</p></div>

						<div class="item-block">
							<p class="ttl">リモート勤務</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="contact-list">
											<div class="input-wrap">
												<textarea name="remote_content" id="remote" cols="160" rows="4" style="width:100%;" onkeyup="ShowLength();">{{ old('remote_content', $eval->remote_content) }}</textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="inputlength"><p id="inputlength7">全項目の合計：現在 0文字</p></div>

						<div class="item-block">
							<p class="ttl">定年</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="contact-list">
											<div class="input-wrap">
												<textarea name="retire_content" id="retire" cols="160" rows="4" style="width:100%;" onkeyup="ShowLength();">{{ old('retire_content', $eval->retire_content) }}</textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="inputlength"><p id="inputlength8">全項目の合計：現在 0文字</p></div>

						<div class="btn-wrap">
							@error('input_len')
								<span class="invalid-feedback" role="alert" style="color:#ff0000;"><p style="transform: rotate(0.03deg);">全項目の合計が、500文字以上になるように入力してください。</p></span>
							@enderror
							@if (session('status') === 'success-store')
			                  	<div id="success3" class="alert alert-success"  style="color:#0000ff;">
									保存しました。
			                  	</div>
							@endif

							@if ($eval->approve_flag != '1' && $eval->approve_flag != '2' && $eval->approve_flag != '9')
								<button type="submit" name="save" value="save" style="margin:10px 0;">保存する</button>　
								<button type="submit" name="next" value="next" formaction="/eval/confirm" style="margin:10px 0;">確認画面へ進む</button>
							@endif
						</div>

					</div>
				</div>
			</div>
{{-- 評価コメント END --}}


		</div>
	</fieldset>
		{{ html()->form()->close() }}
	</div>
</main>





{{-- 企業選択モーダル  --}}

        <div id="modalAreaName" class="modalAreaName modalSort">
            <div id="modalBg" class="modalBg"></div>
            <div class="modalWrapper">
              <div class="modalContents">
                <h1>評価対象企業一覧</h1>
                <h3>評価対象企業を選択してください</h3>    
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
                            @foreach ($comp_A as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel"  title="{{$cmp['name']}}" value="{{ $cmp['id'] }}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
    
                    <div id="cate-b" class="block">
                        <p class="block-ttl">B</p>
                        <ul class="cate-list">
                            @foreach ($comp_B as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
    
                    <div id="cate-c" class="block">
                        <p class="block-ttl">C</p>
                        <ul class="cate-list">
                            @foreach ($comp_C as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-d" class="block">
                        <p class="block-ttl">D</p>
                        <ul class="cate-list">
                            @foreach ($comp_D as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-e" class="block">
                        <p class="block-ttl">E</p>
                        <ul class="cate-list">
                            @foreach ($comp_E as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-f" class="block">
                        <p class="block-ttl">F</p>
                        <ul class="cate-list">
                            @foreach ($comp_F as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-g" class="block">
                        <p class="block-ttl">G</p>
                        <ul class="cate-list">
                            @foreach ($comp_G as $omp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-h" class="block">
                        <p class="block-ttl">H</p>
                        <ul class="cate-list">
                            @foreach ($comp_H as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                    </div>
                    <div id="cate-i" class="block">
                        <p class="block-ttl">I</p>
                        <ul class="cate-list">
                            @foreach ($comp_I as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-j" class="block">
                        <p class="block-ttl">J</p>
                        <ul class="cate-list">
                            @foreach ($comp_J as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-k" class="block">
                        <p class="block-ttl">K</p>
                        <ul class="cate-list">
                            @foreach ($comp_K as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-l" class="block">
                        <p class="block-ttl">L</p>
                        <ul class="cate-list">
                            @foreach ($comp_L as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-m" class="block">
                        <p class="block-ttl">M</p>
                        <ul class="cate-list">
                            @foreach ($comp_M as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-n" class="block">
                        <p class="block-ttl">N</p>
                        <ul class="cate-list">
                            @foreach ($comp_N as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-o" class="block">
                        <p class="block-ttl">O</p>
                        <ul class="cate-list">
                            @foreach ($comp_O as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-p" class="block">
                        <p class="block-ttl">P</p>
                        <ul class="cate-list">
                            @foreach ($comp_P as $cmp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-q" class="block">
                        <p class="block-ttl">Q</p>
                        <ul class="cate-list">
                            @foreach ($comp_Q as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-r" class="block">
                        <p class="block-ttl">R</p>
                        <ul class="cate-list">
                            @foreach ($comp_R as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-s" class="block">
                        <p class="block-ttl">S</p>
                        <ul class="cate-list">
                            @foreach ($comp_S as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-t" class="block">
                        <p class="block-ttl">T</p>
                        <ul class="cate-list">
                            @foreach ($comp_T as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-u" class="block">
                        <p class="block-ttl">U</p>
                        <ul class="cate-list">
                            @foreach ($comp_U as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-v" class="block">
                        <p class="block-ttl">V</p>
                        <ul class="cate-list">
                            @foreach ($comp_V as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-w" class="block">
                        <p class="block-ttl">W</p>
                        <ul class="cate-list">
                            @foreach ($comp_W as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-x" class="block">
                        <p class="block-ttl">X</p>
                        <ul class="cate-list">
                            @foreach ($comp_X as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-y" class="block">
                        <p class="block-ttl">Y</p>
                        <ul class="cate-list">
                            @foreach ($comp_Y as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                       </ul>
                    </div>
                    <div id="cate-z" class="block">
                        <p class="block-ttl">Z</p>
                        <ul class="cate-list">
                            @foreach ($comp_Z as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
    
				</form>
    
    
              </div>
              <div id="closeModal" class="closeModal">
                ×
              </div>
            </div>
        </div>

{{-- END 企業選択モーダル  --}}



<script>

/////////////////////////////////////////////////////////
// 文字列長さチェック
/////////////////////////////////////////////////////////
function ShowLength() {

	var salary = document.getElementById('salary').value;
	var welfare = document.getElementById('welfare').value;
	var upbring = document.getElementById('upbring').value;
	var compliance = document.getElementById('compliance').value;
	var motivation = document.getElementById('motivation').value;
	var work_life = document.getElementById('work_life').value;
	var remote = document.getElementById('remote').value;
	var retire = document.getElementById('retire').value;

	var total = salary.length
			 + welfare.length
			 + upbring.length
			 + compliance.length
			 + motivation.length
			 + work_life.length
			 + remote.length
			 + retire.length;

	let element = document.getElementById('input_len');
	element.value = total;

	var total_str = "全項目の合計：現在 " + total + "文字";

   document.getElementById('inputlength1').innerHTML = total_str;
   document.getElementById('inputlength2').innerHTML = total_str;
   document.getElementById('inputlength3').innerHTML = total_str;
   document.getElementById('inputlength4').innerHTML = total_str;
   document.getElementById('inputlength5').innerHTML = total_str;
   document.getElementById('inputlength6').innerHTML = total_str;
   document.getElementById('inputlength7').innerHTML = total_str;
   document.getElementById('inputlength8').innerHTML = total_str;
}


/////////////////////////////////////////////////////////
// 雇用形態　１つのみ選択
/////////////////////////////////////////////////////////
$("[name='emp_status']").on("click", function(){
	if ($(this).prop('checked')){
    	$("[name='emp_status']").prop('checked', false);
        $(this).prop('checked', true);
    }
});


/////////////////////////////////////////////////////////
// 在籍状況　１つのみ選択
/////////////////////////////////////////////////////////
$("[name='tenure_status']").on("click", function(){
	if ($(this).prop('checked')){
    	$("[name='tenure_status']").prop('checked', false);
        $(this).prop('checked', true);
    }
});


/////////////////////////////////////////////////////////
// 入社形態　１つのみ選択
/////////////////////////////////////////////////////////
$("[name='join_status']").on("click", function(){
	if ($(this).prop('checked')){
    	$("[name='join_status']").prop('checked', false);
        $(this).prop('checked', true);
    }
});


/////////////////////////////////////////////////////////
// 企業選択　１つのみ選択
/////////////////////////////////////////////////////////
$("[name='comp_sel']").on("click", function(){
	if ($(this).prop('checked')){
    	$("[name='comp_sel']").prop('checked', false);
        $(this).prop('checked', true);
    }
});


/////////////////////////////////////////////////////////
// 成功メッセージクリア
/////////////////////////////////////////////////////////
function clearMsg() {

	window.setTimeout(function(){
		const p1 = document.getElementById("success1");
		const p2 = document.getElementById("success2");
		const p3 = document.getElementById("success3");

		if (p1) p1.style.display ="none";
		if (p2) p2.style.display ="none";
		if (p3) p3.style.display ="none";

    }, 3000);

}


/////////////////////////////////////////////////////////
// 企業選択モーダルからの戻り
/////////////////////////////////////////////////////////
function GetComp(compId) {


    $('body').css('overflow-y','auto');     // 本文の縦スクロールを有効
	$('#modalAreaName').fadeOut();

	location.href = '/eval/regist?comp_id=' + compId;
}


/////////////////////////////////////////////////////////
// 初回起動
/////////////////////////////////////////////////////////
$(document).ready(function() {

	clearMsg();
	ShowLength();

});


</script>

<script src="{{ asset('js/career.js') }}"></script>

@endsection
