@extends('layouts.user.auth')


@section('addheader')
	<title>職務経歴書｜{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/career.css') }}" rel="stylesheet">
@endsection


@section('content')


@include('user.user_activity')

<main class="pane-main">

	<div class="inner">
		<div class="ttl">
			<h1>職務経歴書（英語）</h1>
		</div>

			<div class="con-wrap">

				<div class="item setting">
					<div class="item-inner">
						<div class="setting-list">
							{{ html()->form('POST', "/cv/eng/store1")->id('mailform')->attribute('name', 'cv1form')->open() }}

							<div class="item-block">
								<p class="ttl">Company Name ※</p>
								<div class="form-inner contact">
									<div class="contact-list">
										<div class="input-wrap"><input type="text" name="en_company" value="{{ old('en_company' ,$user->en_company) }}" class="long"></div>
									</div>
								</div>
							</div>


							<div class="item-block">
								<p class="ttl">Section Name ※</p>
								<div class="form-inner contact">
									<div class="contact-list">
										<div class="input-wrap"><input type="text" name="en_unit_name" value="{{ old('en_unit_name' ,$user->en_unit_name) }}"  class="long"></div>
									</div>
								</div>
							</div>


							<div class="item-block">
								<p class="ttl">Job Title ※</p>
								<div class="form-inner contact">
									<div class="contact-list">
										<div class="input-wrap"><input type="text" name="en_job_title" value="{{ old('en_job_title' ,$user->en_job_title) }}"  class="long"></div>
									</div>
								</div>
							</div>

							<div class="item-block">
								<p class="ttl">Tenure ※</p>
								<div class="form-inner contact">
									<div class="contact-list">
										<div class="select-wrap">
											<label for="">
												<select name="enroll_from_year">
													<option value="">-</option>
								 					@foreach ($yearList as $year)
														<option value="{{$year}}"   @if (old('enroll_from_year' ,$user->enroll_from_year) == $year)  selected @endif>{{$year}}</option>
								 					@endforeach
												</select>
											</label>
											<label for="">
												<select name="enroll_from_month">
													<option value="">-</option>
								 					@foreach ($monthList as $mon)
														<option value="{{$mon}}"   @if (old('enroll_from_month' ,$user->enroll_from_month) == $mon)  selected @endif>{{$mon}}</option>
								 					@endforeach
												</select>
											</label>
											<label for="">
												<select name="enroll_from_day">
													<option value="">-</option>
								 					@foreach ($dayList as $dd)
														<option value="{{$dd}}"   @if (old('enroll_from_day' ,$user->enroll_from_day) == $dd)  selected @endif>{{$dd}}</option>
								 					@endforeach
												</select>
											</label>
											<span>～</span>
										</div>
										<div class="select-wrap">
											<label for="">
												<select name="enroll_to_year" id="enroll_to_year">
													<option value="">-</option>
								 					@foreach ($yearList as $year)
														<option value="{{$year}}"   @if (old('enroll_to_year' ,$user->enroll_to_year) == $year)  selected @endif>{{$year}}</option>
								 					@endforeach
												</select>
											</label>
											<label for="">
												<select name="enroll_to_month" id="enroll_to_month">
													<option value="">-</option>
								 					@foreach ($monthList as $mon)
														<option value="{{$mon}}"   @if (old('enroll_to_month' ,$user->enroll_to_month) == $mon)  selected @endif>{{$mon}}</option>
								 					@endforeach
											   </select>
											</label>
											<label for="">
												<select name="enroll_to_day" id="enroll_to_day">
												<option value="">-</option>
								 					@foreach ($dayList as $dd)
														<option value="{{$dd}}"   @if (old('enroll_to_day' ,$user->enroll_to_day) == $dd)  selected @endif>{{$dd}}</option>
								 					@endforeach
												</select>
											</label>
										</div>
									</div>
								</div>
							</div>

							<div class="item-block">
								<p class="ttl">Job Contents / Industry Responsibilities / Merchandise handled / Project ※</p>
								<div class="form-inner contact">
									<div class="contact-list">
										<div class="input-wrap">
											<textarea name="en_job_detail" id="en_job_detail" cols="60" rows="10" >{{ $user->en_job_detail }}</textarea>
										</div>
									</div>
								</div>
							</div>

							<div class="btn-wrap">
								<button type="submit">Registration</button>
								@if (session('status') === 'success-update')
									<p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-blue-400 dark:text-blue-400" style="color: blue;">Registration Complete</p>
								@endif
							</div>
							{{ html()->form()->close() }}
						
						</div>
					</div>
				</div>


				<div class="item setting">
					<div class="item-inner">

						<div class="setting-list">

							{{ html()->form('POST', "/cv/eng/store3")->id('mailform')->attribute('name', 'cv3form')->open() }}

							<div class="item-block">
								<p class="ttl">Award</p>
								<div class="form-inner contact">
									<div class="contact-list">
										<div class="input-wrap"><input type="text" name="en_award" value="{{ old('en_award' ,$user->en_award) }}"  class="long"></div>
									</div>
								</div>
							</div>

							<div class="item-block">
								<p class="ttl">English ※</p>
								<div class="form-wrap">
									<div class="form-block">
										<div class="form-inner">
											<div class="check-box-btn">
								 				@foreach ($constEnglish as $eng)
													<label><input type="checkbox" value="{{ $eng->id }}" name="english" @if (old('english' ,$user->english) == $eng->id) checked @endif><span>{{ $eng->name }}</span></label>
												@endforeach
											</div>
									   </div>
									</div>
								</div>
							</div>

							<div class="item-block">
								<p class="ttl">TOEIC</p>
								<div class="form-inner contact">
									<div class="contact-list">
										<div class="input-wrap">
											<input type="text" name="toeic" value="{{ old('toeic' ,$user->toeic) }}" class="short">
											<span>Points</span>
										</div>
									</div>
								</div>
							</div>

							<div class="item-block">
								<p class="ttl">Japanese ※</p>
								<div class="form-wrap">
									<div class="form-block">
										<div class="form-inner">
											<div class="check-box-btn">
								 				@foreach ($constEnglish as $eng)
													<label><input type="checkbox" value="{{ $eng->id }}" name="japanese" @if (old('japanese' ,$user->japanese) == $eng->id) checked @endif><span>{{ $eng->name }}</span></label>
												@endforeach
											</div>
									   </div>
									</div>
								</div>
							</div>

							<div class="btn-wrap">
								<button type="submit">Registration</button>
								@if (session('status') === 'success-update2')
									<p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-blue-400 dark:text-blue-400" style="color: blue;">Registration Complete</p>
								@endif
							</div>

							{{ html()->form()->close() }}
						</div>
					</div>
				</div>
			</div>
	
		</div>
	</div>
</main>





<script>

/////////////////////////////////////////////////////////
// 英語力　１つのみ選択
/////////////////////////////////////////////////////////
$("[name='english']").on("click", function(){
	if ($(this).prop('checked')){
    	$("[name='english']").prop('checked', false);
        $(this).prop('checked', true);
    }
});


/////////////////////////////////////////////////////////
// 転職時期　１つのみ選択
/////////////////////////////////////////////////////////
$("[name='japanese']").on("click", function(){
	if ($(this).prop('checked')){
    	$("[name='japanese']").prop('checked', false);
        $(this).prop('checked', true);
    }
});


/////////////////////////////////////////////////////////
// 現在選択
/////////////////////////////////////////////////////////
let select = document.querySelector('[name="enroll_to_year"]');

select.onchange = event => {

	if (select.value == "0") {
		$("#enroll_to_month").find("option").eq(0).prop('selected', true);
		$("#enroll_to_day").find("option").eq(0).prop('selected', true);
	}
}



</script>


<script src="{{ asset('js/career.js') }}"></script>

@endsection
