@extends('layouts.admin.auth')

@section('content')


<head>
	<title>企業評価｜{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/eval.css') }}" rel="stylesheet">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
</head>

<style>
html {font-size:62.5%;}

.pane-main .inner > .ttl h2 {
  color: #484848;
  font-size: 2.4rem;
  font-family: 'M PLUS 1p';
  letter-spacing: 0.1em;
}
form .select-wrap label {
  position: relative; display: inline-block;
}
form select {
  -webkit-appearance: none;
  appearance: none;
}

form select::-ms-expand {
  display: none;
}

form .select-wrap label::before {
  position: absolute;
  content: "";
  top: 50%;
  right: 10px;
  width:0px;
  height:0px;
  margin: -2px 0 0 0;
  border: 5px solid transparent;
  border-top: 5px solid #707070;
  pointer-events: none; /* T.N */ 
}


.page {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-wrap: wrap;
  margin: 3rem 10px;
}

.page__numbers {
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 4px 4px;
  font-size: 1.4rem;
  cursor: pointer;
}
.page__numbers {
  height: 3.2rem;
  border-radius: 20px;
  padding: 8px 10px;
}
.page__numbers:hover {
  color: #fff;
  background: #4AA5CE;
}
.page__numbers.active {
  color: #fff;
  background: #4AA5CE;
  font-weight: 600;
  border: 1px solid #4AA5CE;
  border-radius: 20px;
}

</style>


<main class="pane-main">

	<div class="inner">
		<div class="ttl" style="display:flex;display: -webkit-flex; -webkit-justify-content: space-between;ustify-content: space-between;">
			<h2>企業評価</h2>
			<div style="font-weight:bold;font-size:1.4em;">
				<a href="/admin/mypage/eval">クチコミ一覧へ</a>
			</div>
		</div>

		{{ Form::open(['url' => '/admin/mypage/eval/store', 'name' => 'storeform' , 'id' => 'storeform' ]) }}
		<input type="hidden" name="input_len" id="input_len" value="0">
		<input type="hidden" name="eval_id" value="{{ $eval->id }}">

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
											<input type="hidden" name="comp_id" id="comp_id" value="{{ old('comp_id', $comp->id) }}">
											<ul id="comp_list">{{ $comp->name }}
											</ul>
											@empty($eval->user_id)
												<a  class="openModalName button-modal" href="#modalAreaName">選択</a>
											@endempty
										</div>
									</div>
								</div>
								@error('comp_id')
									<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span><br>
								@enderror
							</div>
						</div>
					</div>
				</div>
			</div>
{{-- 評価企業選択 END --}}

{{-- 在籍情報 --}}
			<div class="item setting">
				<div class="item-inner">
					<div class="setting-list">

						<div class="item-block">
						<p class="ttl">候補者名</p>
							<div class="form-wrap">
								<label>
									@empty($eval->user_id)
										候補者指定なし
									@else
										{{ $eval->user_name }}
									@endempty
								</label>
							</div>
						</div>

						@empty($eval->user_id)
							<div class="item-block">
								<p class="ttl">性別 <font color="red">*</font></p>
								<div class="form-wrap">
									<div class="form-block">
										<div class="form-inner">
											<div class="check-box-btn">
												<label><input type="checkbox" value="1" name="sex" @if (old('sex', $eval->sex) == '1') checked @endif><span>男性</span></label>
												<label><input type="checkbox" value="2" name="sex" @if (old('sex', $eval->sex) == '2') checked @endif><span>女性</span></label>
												<label><input type="checkbox" value="0" name="sex" @if (old('sex', $eval->sex) == '0') checked @endif><span>選択なし</span></label>
											</div>
											@error('sex')
												<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
											@enderror
										</div>
									</div>
								</div>
							</div>
						@else
							<div class="item-block">
								<p class="ttl">性別</p>
								<div class="form-wrap">
									<label>
										@if ($eval->user_sex == '1')男性
										@elseif ($eval->user_sex == '2')女性
										@elseif ($eval->user_sex == '0')選択なし
										@endif
									</label>
									<input type="hidden"name="sex" value="{{ $eval->user_sex }}">
								</div>
							</div>
						@endempty

						<div class="item-block">
							<p class="ttl">雇用形態 <font color="red">*</font></p>
							<div class="form-wrap">
								<div class="form-block">
									<div class="form-inner">
										<div class="check-box-btn">
											<label><input type="checkbox" value="1" name="emp_status" @if (old('emp_status', $eval->emp_status) == '1') checked @endif><span>正社員</span></label>
											<label><input type="checkbox" value="2" name="emp_status" @if (old('emp_status', $eval->emp_status) == '2') checked @endif><span>契約社員</span></label>
											<label><input type="checkbox" value="9" name="emp_status" @if (old('emp_status', $eval->emp_status) == '9') checked @endif><span>その他</span></label>
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
												<ul>
													<label for="">
														<select name="retire_year" id="retirement_year" class="select-no">
															<option value="" disabled selected style="display:none;"></option>
															@foreach ($leaveYearList as $year)
																<option value="{{$year}}"   @if (old('retire_year', $eval->retire_year) == $year)  selected @endif>{{$year}}年</option>
															@endforeach
																<option value="9999"  @if (old('retire_year', $eval->retire_year) == '9999')  selected @endif>在籍中</option>
														</select>
													</label>
												</ul>
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
									<div class="input-wrap"><input type="text" name="occupation" value="{{ old('occupation', $eval->occupation) }}"  class="long"></div>
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
							<button type="submit" value="save" style="margin:10px 0;">保存する</button>
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
							<button type="submit" value="save" style="margin:10px 0;">保存する</button>　
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
									<div class="input-wrap"><input type="text" name="ote_income" value="{{  old('ote_income', $eval->ote_income) }}"  class="short"> 万円</div>
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

						<div class="item-block">
							<p class="ttl">承認／否決 <font color="red">*</font></p>
							<div class="form-wrap">
								<div class="form-block">
									<div class="form-inner">
										<div class="check-box-btn">
											<label><input type="checkbox" value="0" name="approve_flag" @if (old('approve_flag', $eval->approve_flag) == '0') checked @endif><span>編集中</span></label>
											<label><input type="checkbox" value="1" name="approve_flag" @if (old('approve_flag', $eval->approve_flag) == '1') checked @endif><span>申請中</span></label>
											<label><input type="checkbox" value="2" name="approve_flag" @if (old('approve_flag', $eval->approve_flag) == '2') checked @endif><span>審査中</span></label>　　
											<label><input type="checkbox" value="8" name="approve_flag" @if (old('approve_flag', $eval->approve_flag) == '8') checked @endif><span2>承認</span2></label>
											<label><input type="checkbox" value="9" name="approve_flag" @if (old('approve_flag', $eval->approve_flag) == '9') checked @endif><span3>否決</span3></label>
										</div>
										@error('emp_status')
											<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
										@enderror
									</div>
								</div>
							</div>
						</div>

						<div class="btn-wrap">
							@error('input_len')
								<span class="invalid-feedback" role="alert" style="color:#ff0000;"><p style="transform: rotate(0.03deg);">全項目の合計が、500文字以上になるように入力してください。</p></span>
							@enderror
							@if (session('status') === 'success-store')
			                  	<div id="success3" class="alert alert-success"  style="color:#0000ff;">
									保存しました。
			                  	</div>
							@endif
							<button type="submit" name="save" value="save" style="margin:10px 0;">保存する</button>　
						</div>

					</div>
				</div>
			</div>

{{-- 評価コメント END --}}


		</div>
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
                            @foreach ($comp_XA as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel"  title="{{$cmp['name']}}" value="{{ $cmp['id'] }}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
    
                    <div id="cate-b" class="block">
                        <p class="block-ttl">B</p>
                        <ul class="cate-list">
                            @foreach ($comp_XB as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
    
                    <div id="cate-c" class="block">
                        <p class="block-ttl">C</p>
                        <ul class="cate-list">
                            @foreach ($comp_XC as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-d" class="block">
                        <p class="block-ttl">D</p>
                        <ul class="cate-list">
                            @foreach ($comp_XD as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-e" class="block">
                        <p class="block-ttl">E</p>
                        <ul class="cate-list">
                            @foreach ($comp_XE as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-f" class="block">
                        <p class="block-ttl">F</p>
                        <ul class="cate-list">
                            @foreach ($comp_XF as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-g" class="block">
                        <p class="block-ttl">G</p>
                        <ul class="cate-list">
                            @foreach ($comp_XG as $omp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-h" class="block">
                        <p class="block-ttl">H</p>
                        <ul class="cate-list">
                            @foreach ($comp_XH as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                    </div>
                    <div id="cate-i" class="block">
                        <p class="block-ttl">I</p>
                        <ul class="cate-list">
                            @foreach ($comp_XI as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-j" class="block">
                        <p class="block-ttl">J</p>
                        <ul class="cate-list">
                            @foreach ($comp_XJ as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-k" class="block">
                        <p class="block-ttl">K</p>
                        <ul class="cate-list">
                            @foreach ($comp_XK as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-l" class="block">
                        <p class="block-ttl">L</p>
                        <ul class="cate-list">
                            @foreach ($comp_XL as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-m" class="block">
                        <p class="block-ttl">M</p>
                        <ul class="cate-list">
                            @foreach ($comp_XM as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-n" class="block">
                        <p class="block-ttl">N</p>
                        <ul class="cate-list">
                            @foreach ($comp_XN as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-o" class="block">
                        <p class="block-ttl">O</p>
                        <ul class="cate-list">
                            @foreach ($comp_XO as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-p" class="block">
                        <p class="block-ttl">P</p>
                        <ul class="cate-list">
                            @foreach ($comp_XP as $cmp)
                                 <li>
	                                <label><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-q" class="block">
                        <p class="block-ttl">Q</p>
                        <ul class="cate-list">
                            @foreach ($comp_XQ as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-r" class="block">
                        <p class="block-ttl">R</p>
                        <ul class="cate-list">
                            @foreach ($comp_XR as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-s" class="block">
                        <p class="block-ttl">S</p>
                        <ul class="cate-list">
                            @foreach ($comp_XS as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-t" class="block">
                        <p class="block-ttl">T</p>
                        <ul class="cate-list">
                            @foreach ($comp_XT as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-u" class="block">
                        <p class="block-ttl">U</p>
                        <ul class="cate-list">
                            @foreach ($comp_XU as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-v" class="block">
                        <p class="block-ttl">V</p>
                        <ul class="cate-list">
                            @foreach ($comp_XV as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-w" class="block">
                        <p class="block-ttl">W</p>
                        <ul class="cate-list">
                            @foreach ($comp_XW as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-x" class="block">
                        <p class="block-ttl">X</p>
                        <ul class="cate-list">
                            @foreach ($comp_XX as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                        </ul>
                    </div>
                    <div id="cate-y" class="block">
                        <p class="block-ttl">Y</p>
                        <ul class="cate-list">
                            @foreach ($comp_XY as $cmp)
                                 <li>
	                                <label style="transform: rotate(0.03deg);"><input type="checkbox"  id="comp_select" name="comp_sel" title="{{$cmp['name']}}" value="{{$cmp['id']}}" onclick="GetComp({{ $cmp['id'] }})" @if ($cmp['id'] == $comp->id) checked @endif><span>{{$cmp['name']}}</span></label>
                                 </li>
                             @endforeach
                       </ul>
                    </div>
                    <div id="cate-z" class="block">
                        <p class="block-ttl">Z</p>
                        <ul class="cate-list">
                            @foreach ($comp_XZ as $cmp)
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
// 性別　１つのみ選択
/////////////////////////////////////////////////////////
$("[name='sex']").on("click", function(){
	if ($(this).prop('checked')){
    	$("[name='sex']").prop('checked', false);
        $(this).prop('checked', true);
    }
});


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
// 承認選択　１つのみ選択
/////////////////////////////////////////////////////////
$("[name='approve_flag']").on("click", function(){
	if ($(this).prop('checked')){
    	$("[name='approve_flag']").prop('checked', false);
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
// 企業モーダル設定
/////////////////////////////////////////////////////////
function ResetComp() {

	var comps = document.getElementById("comp_id").value;

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
			
		titleList = "<li><span>" + works[i]['title'] +  "</span></li>\n";
	};


//	console.log(titleList);

    if (valList == ""){
        $("#comp_list").html("<li><span>指定なし</span></li>");
        document.getElementById( "comp_id" ).value = "" ;
    }else{
    	$("#comp_list").html(titleList);
        document.getElementById( "comp_id" ).value = valList;
    }

}


/////////////////////////////////////////////////////////
// 企業選択モーダルからの戻り
/////////////////////////////////////////////////////////
function GetComp(compId) {

	putComp();
	ResetComp();

    $('body').css('overflow-y','auto');     // 本文の縦スクロールを有効
	$('#modalAreaName').fadeOut();
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
