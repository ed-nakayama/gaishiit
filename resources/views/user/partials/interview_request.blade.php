<style>
.item-info .button-flex {
  display: flex;
  justify-content: center;
  margin: 32px auto;
}

.item-info .button-flex a {
  display: inline-block;
  font-size: 1.8rem;
  font-weight: 600;
  color: #fff;
  background: #4AA5CE;
  padding: 10px 20px;
  border-radius: 30px;
  max-width: 400px;
  width: 100%;
  text-align: center;
}

.item-info .button-flex a:nth-child(n+2) {
  margin-left: 30px;
}


</style>

{{-- 面談リクエストボタン $job $user_act --}}
			<div class="item-info">
				<div class="button-flex">
					@if ($job->casual_flag == '1')
						@if (Auth::guard('user')->check())
							<a href="javascript:casualform.submit()">カジュアル面談を依頼</a>
						@else
							<a class="openModal button-modal" href="#modalLogin">カジュアル面談を依頼</a>
						@endif
					@endif

					@if (Auth::guard('user')->check())
						@if ($job->backg_flag == '1' && $user_act['cv_comp'] < 1)
							<a style="background-color :lightgrey">正式応募を依頼 </a>
						@elseif ($job->backg_eng_flag == '1' && $user_act['cv_eng_comp'] < 1)
							<a style="background-color :lightgrey">正式応募を依頼</a>
						@elseif ($job->personal_flag == '1' && $user_act['vitae_comp'] < 1)
							<a style="background-color :lightgrey">正式応募を依頼</a>
						@else
							<a href="javascript:formalform.submit()">正式に応募する</a>
						@endif
					@else
						<a class="openModal button-modal" href="#modalLogin">正式応募を依頼</a>
					@endif
				</div>
				<div class="button-flex">
					@if (Auth::guard('user')->check())
						<a href="javascript:agentform.submit()">外資IT特化の転職エージェントに相談する</a>
					@else
						<a class="openModal button-modal" href="#modalLogin">外資IT特化の転職エージェントに相談する</a>
					@endif
				</div>
				@if ($job->backg_flag == '1' && !empty($user_act['cv_comp']))
					<center><font color="red">※「正式に応募する」と「外資IT特化の転職エージェントに相談」は@if ($job->backg_flag == '1')、職務経歴書 @endif @if ($job->backg_eng_flag == '1')、職務経歴書（英文）@endif @if ($job->personal_flag == '1')、履歴書 @endifが必要です。<a href="/setting" style="color:#4AA5CE;text-decoration: underline;">個人設定</a>よりご登録をお願いします。</font></center>
				@elseif ($job->backg_eng_flag == '1' && !empty($user_act['cv_eng_comp']))
					<center><font color="red">※「正式に応募する」と「外資IT特化の転職エージェントに相談」は@if ($job->backg_flag == '1')、職務経歴書 @endif @if ($job->backg_eng_flag == '1')、職務経歴書（英文）@endif @if ($job->personal_flag == '1')、履歴書 @endifが必要です。<a href="/setting" style="color:#4AA5CE;text-decoration: underline;">個人設定</a>よりご登録をお願いします。</font></center>
				@elseif ($job->personal_flag == '1' && !empty($user_act['vitae_comp']))
					<center><font color="red">※「正式に応募する」と「外資IT特化の転職エージェントに相談」は@if ($job->backg_flag == '1')、職務経歴書 @endif @if ($job->backg_eng_flag == '1')、職務経歴書（英文）@endif @if ($job->personal_flag == '1')、履歴書 @endifが必要です。<a href="/setting" style="color:#4AA5CE;text-decoration: underline;">個人設定</a>よりご登録をお願いします。</font></center>
				@endif
			</div>
{{-- END 面談リクエストボタン --}}
