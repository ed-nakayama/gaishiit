@extends('layouts.user.auth')


@section('addheader')
	<title>履歴書｜{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/career.css') }}" rel="stylesheet">
@endsection


@section('content')


@include('user.user_activity')

<main class="pane-main">

	<div class="inner">
		<div class="ttl">
			<h1>履歴書</h1>
		</div>

		<div class="con-wrap">
			@if (session('status') === 'success-update')
				<center>
					<p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-blue-400 dark:text-blue-400" style="color: blue;">登録／変更が完了しました。</p>
				</center>
			@endif

			<div class="item setting">
				<div class="item-inner">
					{{ html()->form('POST', "/vitae/store")->attribute('name', 'vitaeform')->open() }}
					<div class="setting-list">

						<div class="item-block">
							<p class="ttl">氏名</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="input-wrap"><input type="text" name="name" value="{{ old('name' ,$user->name) }}"  disabled="disabled"  class="long"></div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">ふりがな ※</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="input-wrap"><input type="text" name="name_kana" value="{{ old('name_kana' ,$user->name_kana) }}"  class="long"></div>
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
							<p class="ttl">性別</p>
							<div class="form-wrap">
								<div class="form-block">
									<div class="form-inner">
										<div class="check-box-btn">
											<label><input type="checkbox" value="1" name="sex" @if (old('sex' ,$user->sex) == '1') checked @endif disabled="disabled"><span @if (old('sex' ,$user->sex) == '1') style="background:#E5AF24;color:#fff;border:1px solid #E5AF24;" @else style="background:#fff;color:#E5AF24;border:1px solid #E5AF24;" @endif>男性</span></label>
											<label><input type="checkbox" value="2" name="sex" @if (old('sex' ,$user->sex) == '2') checked @endif disabled="disabled"><span @if (old('sex' ,$user->sex) == '2') style="background:#E5AF24;color:#fff;border:1px solid #E5AF24;" @else style="background:#fff;color:#E5AF24;border:1px solid #E5AF24;"  @endif>女性</span></label>
											<label><input type="checkbox" value="3" name="sex" @if (old('sex' ,$user->sex) == '0') checked @endif disabled="disabled"><span @if (old('sex' ,$user->sex) == '0') style="background:#E5AF24;color:#fff;border:1px solid #E5AF24;" @else style="background:#fff;color:#E5AF24;border:1px solid #E5AF24;"  @endif>選択しない</span></label>
										</div>
								   </div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">生年月日</p>
							<div class="form-wrap">
								<div class="form-block">
									<div class="form-inner">
										<div class="check-box-btn">
											<div class="input-wrap"><input type="date" name="birthday" value="{{ old('birthday' ,$user->birthday) }}"  disabled="disabled"></div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">現住所 ※</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="select-wrap">
										<label for="">
											<select name="pref" class="middle">
												<option value="">都道府県</option>
												@foreach ($constPref as $pref)
													<option value="{{ $pref->id }}"  @if (old('pref' ,$user->pref) == $pref->id) selected @endif>{{ $pref->name }}</option>
												@endforeach
											</select>
										</label>
									</div>
									<div class="input-wrap"><input type="text" name="address" value="{{ old('address' ,$user->address) }}" class="long"></div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">メールアドレス</p>
							<div class="form-wrap">
								<div class="form-block">
									<div class="form-inner">
										<div class="contact-list">
											<div class="input-wrap"><input type="text" name="hist_email" value="{{ $user->hist_email }}"  class="long"   disabled="disabled"></div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">学歴・職歴 ※</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="input-wrap">
										<textarea name="job_hist" id="job_hist" cols="30" rows="10">{{ old('job_hist' ,$user->job_hist) }}</textarea>
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">志望の動機、<br class="br-pc">自己PR、趣味、<br class="br-pc">特技など ※</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="input-wrap">
										<textarea name="motivation" id="motivation" cols="30" rows="10">{{ old('motivation' ,$user->motivation) }}</textarea>
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">扶養家族 ※</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="input-wrap">
										<input type="text" name="dependents" value="{{ old('dependents' ,$user->dependents) }}" class="short">
										<span>人</span>
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">配偶者 ※</p>
							<div class="form-wrap">
								<div class="form-block">
									<div class="form-inner">
										<div class="check-box-btn">
											<label><input type="checkbox" value="1" name="spouse" @if (old('spouse' ,$user->spouse) == '1') checked @endif><span>あり</span></label>
											<label><input type="checkbox" value="2" name="spouse" @if (old('spouse' ,$user->spouse) == '2') checked @endif><span>なし</span></label>
										</div>
								   </div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">配偶者の扶養義務 ※</p>
							<div class="form-wrap">
								<div class="form-block">
									<div class="form-inner">
										<div class="check-box-btn">
											<label><input type="checkbox" value="1" name="obligation" @if (old('obligation' ,$user->obligation) == '1') checked @endif><span>あり</span></label>
											<label><input type="checkbox" value="2" name="obligation" @if (old('obligation' ,$user->obligation) == '2') checked @endif><span>なし</span></label>
										</div>
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
	
					</div>
					{{ html()->form()->close() }}
				</div>
			</div>

		</div>
	</div>
</main>



<script>

/////////////////////////////////////////////////////////
// 配偶者　１つのみ選択
/////////////////////////////////////////////////////////
$("[name='spouse']").on("click", function(){
	if ($(this).prop('checked')){
    	$("[name='spouse']").prop('checked', false);
        $(this).prop('checked', true);
    }
});


/////////////////////////////////////////////////////////
// 配偶者の扶養義務　１つのみ選択
/////////////////////////////////////////////////////////
$("[name='obligation']").on("click", function(){
	if ($(this).prop('checked')){
    	$("[name='obligation']").prop('checked', false);
        $(this).prop('checked', true);
    }
});



</script>


<script src="{{ asset('js/career.js') }}"></script>

@endsection
