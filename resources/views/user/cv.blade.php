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
			<h1>職務経歴書</h1>
		</div>

		<div class="con-wrap">

			<div class="item setting">
				<div class="item-inner">
					{{ html()->form('POST', "/cv/store1")->id('mailform')->attribute('name', 'cv1form')->open() }}
						<div class="setting-list">

							<div class="item-block">
								<p class="ttl">企業名</p>
								<div class="form-inner contact">
									<div class="contact-list">
										<div class="input-wrap"><input type="text" name="company" value="{{ old('company' ,$user->company) }}"  disabled="disabled"  class="long"></div>
									</div>
								</div>
							</div>


							<div class="item-block">
								<p class="ttl">部門 ※</p>
								<div class="form-inner contact">
									<div class="contact-list">
										<div class="input-wrap"><input type="text" name="unit_name" value="{{ old('unit_name' ,$user->unit_name) }}"  class="long"></div>
									</div>
								</div>
							</div>


							<div class="item-block">
								<p class="ttl">役職</p>
								<div class="form-inner contact">
									<div class="contact-list">
										<div class="input-wrap"><input type="text" name="job_title" value="{{ old('job_title' ,$user->job_title) }}"  disabled="disabled"  class="long"></div>
									</div>
								</div>
							</div>

							<div class="item-block">
								<p class="ttl">在職期間 ※</p>
								<div class="form-inner contact">
									<div class="contact-list">
										<div class="select-wrap">
											<label for="">
												<select name="enroll_from_year">
													<option value="">-</option>
								 					@foreach ($yearList as $year)
														<option value="{{$year}}"   @if (old('enroll_from_year' ,$user->enroll_from_year) == $year)  selected @endif>{{$year}}年</option>
								 					@endforeach
												</select>
											</label>
											<label for="">
												<select name="enroll_from_month">
													<option value="">-</option>
								 					@foreach ($monthList as $mon)
														<option value="{{$mon}}"   @if (old('enroll_from_month' ,$user->enroll_from_month) == $mon)  selected @endif>{{$mon}}月</option>
								 					@endforeach
												</select>
											</label>
											<label for="">
												<select name="enroll_from_day">
													<option value="">-</option>
								 					@foreach ($dayList as $dd)
														<option value="{{$dd}}"   @if (old('enroll_from_day' ,$user->enroll_from_day) == $dd)  selected @endif>{{$dd}}日</option>
								 					@endforeach
												</select>
											</label>
											<span>から</span>
										</div>
										<div class="select-wrap">
											<label for="">
												<select name="enroll_to_year" id="enroll_to_year">
													<option value="">-</option>
								 					@foreach ($yearList as $year)
														<option value="{{$year}}"   @if (old('enroll_to_year' ,$user->enroll_to_year) == $year)  selected @endif>{{$year}}年</option>
								 					@endforeach
												</select>
											</label>
											<label for="">
												<select name="enroll_to_month" id="enroll_to_month">
													<option value="0">-</option>
								 					@foreach ($monthList as $mon)
														<option value="{{$mon}}"   @if (old('enroll_to_month' ,$user->enroll_to_month) == $mon)  selected @endif>{{$mon}}月</option>
								 					@endforeach
											   </select>
											</label>
											<label for="">
												<select name="enroll_to_day" id="enroll_to_day">
												<option value="0">-</option>
								 					@foreach ($dayList as $dd)
														<option value="{{$dd}}"   @if (old('enroll_to_day' ,$user->enroll_to_day) == $dd)  selected @endif>{{$dd}}日</option>
								 					@endforeach
												</select>
											</label>
										</div>
									</div>
								</div>
							</div>

							<div class="item-block">
								<p class="ttl">業務内容・担当業界・取扱商材・プロジェクト ※</p>
								<div class="form-inner contact">
									<div class="contact-list">
										<div class="input-wrap">
											<textarea name="job_detail" id="job_detail" cols="60" rows="10" >{{ $user->job_detail }}</textarea>
										</div>
									</div>
								</div>
							</div>

 
							<div class="btn-wrap">
								<button type="submit">登録 / 変更</button>
								@if (session('status') === 'success-update')
									<p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-blue-400 dark:text-blue-400" style="color: blue;">登録／変更が完了しました。</p>
								@endif
							</div>
						
						</div><!-- item setting -->
						{{ html()->form()->close() }}
					</div>
				</div>


				<div class="item setting">
					<div class="item-inner">

						{{ html()->form('GET', "/cv/store3")->attribute('name', "cv3form")->open() }}
						<div class="setting-list">

							<div class="item-block">
								<p class="ttl">アワード</p>
								<div class="form-inner contact">
									<div class="contact-list">
										<div class="input-wrap"><input type="text" name="award" value="{{ old('award' ,$user->award) }}"  class="long"></div>
									</div>
								</div>
							</div>

							<div class="item-block">
								<p class="ttl">英語力 ※</p>
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
											<span>点</span>
										</div>
									</div>
								</div>
							</div>

							<div class="item-block">
								<p class="ttl">日本語力 ※</p>
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
								<button type="submit">登録 / 変更</button>
								@if (session('status') === 'success-update2')
									<p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-blue-400 dark:text-blue-400" style="color: blue;">登録／変更が完了しました。</p>
								@endif
							</div>

						</div>
						{{ html()->form()->close() }}
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
