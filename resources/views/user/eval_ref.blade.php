@extends('layouts.user.auth')


@section('addheader')
	<title>企業評価 参照｜{{ config('app.title') }}</title>
	<meta name="description" content="企業評価 参照｜{{ config('app.description') }}">

	<meta property="og:type" content="article" />
	<meta property="og:title" content="企業評価 参照｜{{ config('app.title') }}" />
	<meta property="og:description" content="企業評価 参照｜{{ config('app.description') }}" />
	<meta property="og:image" content="{{ url('/img/h_logo.png') }}" />

    <link href="{{ asset('css/eval.css') }}" rel="stylesheet">
    <link href="{{ asset('css/chart.css') }}" rel="stylesheet">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.js"></script>
@endsection


@section('content')


@include('user.user_activity')

<main class="pane-main">

	<div class="inner">
		<div class="ttl">
			<h2>企業評価 参照</h2>
		</div>

		<div class="con-wrap">

{{-- 評価企業選択 --}}
			<div class="item setting">
				<div class="item-inner">
					<div class="setting-list">
						<div class="item-block">
							<p class="ttl">評価企業</p>
							<div class="form-wrap">
								<div class="form-block">
									<div class="form-inner">
										<div class="check-box-btn">
											<input type="hidden" name="comp_id" id="comp_id" value="{{ old('comp_id', $comp->id) }}">
											<ul id="comp_list">{{ $comp->name }}
											</ul>
										</div>
									</div>
								</div>
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

						<div class="item-block" style="display: flex; flex-wrap: nowrap;">
							<p class="ttl" style="max-width: 180px; width: 100%;">雇用形態</p>
							<div class="form-wrap">
								@if ($eval->emp_status == '1')正社員 @elseif ($eval->emp_status == '2') 契約社員 @elseif ($eval->emp_status == '9')その他 @endif
								<input type="hidden" name="emp_status" value="{{ $eval->emp_status }}">
							</div>
						</div>

						<div class="item-block" style="display: flex; flex-wrap: nowrap;">
							<p class="ttl" style="max-width: 180px; width: 100%;">在籍状況</p>
							<div class="form-wrap">
								@if ($eval->tenure_status == '1')現職 @elseif ($eval->tenure_status == '2') 退職済 @endif
								<input type="hidden" name="tenure_status" value="{{ $eval->tenure_status }}">
							</div>
						</div>

						<div class="item-block" style="display: flex; flex-wrap: nowrap;">
							<p class="ttl" style="max-width: 180px; width: 100%;">入社形態</p>
							<div class="form-wrap">
								@if ($eval->join_status == '1')新卒入社 @elseif ($eval->join_status == '2') 中途入社 @endif
								<input type="hidden" name="join_status" value="{{ $eval->join_status }}">
							</div>
						</div>

						<div class="item-block" style="display: flex; flex-wrap: nowrap;">
							<p class="ttl" style="max-width: 180px; width: 100%;">入社年</p>
							<div class="form-wrap">
								{{ $eval->join_year }} 年
								<input type="hidden" name="join_year" value="{{ $eval->join_year }}">
							</div>
						</div>

						<div class="item-block" style="display: flex; flex-wrap: nowrap;">
							<p class="ttl" style="max-width: 180px; width: 100%;">退社年</p>
							<div class="form-wrap">
								@if ($eval->retire_year == '9999')在籍中 @else {{ $eval->retire_year }} 年 @endif
								<input type="hidden" name="retire_year" value="{{ $eval->retire_year }}">
							</div>
						</div>

						<div class="item-block">
						<p class="ttl" style="max-width: 180px; width: 100%;">職種</p>
							<div class="form-wrap">
								{{ $eval->occupation }}
								<input type="hidden" name="occupation" value="{{ $eval->occupation }}">
							</div>
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
							<p class="ttl">給与</p>
							<p><span class="star5_rating"  style="--rate: {{ $eval->salary_point * 100 / 5  . '%' }};"></span></p>
							<input type="hidden" name="salary_point" value="{{ $eval->salary_point }}">
						</div>

						<div class="item-block" style="margin: 0 0;">
							<p class="ttl">福利厚生</p>
							<p><span class="star5_rating"  style="--rate: {{ $eval->welfare_point * 100 / 5  . '%' }};"></span></p>
							<input type="hidden" name="welfare_point" value="{{ $eval->welfare_point }}">
						</div>

						<div class="item-block" style="margin: 0 0;">
							<p class="ttl">育成</p>
							<p><span class="star5_rating"  style="--rate: {{ $eval->upbring_point * 100 / 5  . '%' }};"></span></p>
							<input type="hidden" name="upbring_point" value="{{ $eval->upbring_point }}">
						</div>

						<div class="item-block" style="margin: 0 0;">
							<p class="ttl">法令遵守の意識</p>
							<p><span class="star5_rating"  style="--rate: {{ $eval->compliance_point * 100 / 5  . '%' }};"></span></p>
							<input type="hidden" name="compliance_point" value="{{ $eval->compliance_point }}">
						</div>

						<div class="item-block" style="margin: 0 0;">
							<p class="ttl">社員のモチベーション</p>
							<p><span class="star5_rating"  style="--rate: {{ $eval->motivation_point * 100 / 5  . '%' }};"></span></p>
							<input type="hidden" name="motivation_point" value="{{ $eval->motivation_point }}">
						</div>

						<div class="item-block" style="margin: 0 0;">
							<p class="ttl">ワークライフバランス</p>
							<p><span class="star5_rating"  style="--rate: {{ $eval->work_life_point * 100 / 5  . '%' }};"></span></p>
							<input type="hidden" name="work_life_point" value="{{ $eval->work_life_point }}">
						</div>

						<div class="item-block" style="margin: 0 0;">
							<p class="ttl">勤務体系</p>
							<p><span class="star5_rating"  style="--rate: {{ $eval->remote_point * 100 / 5  . '%' }};"></span></p>
							<input type="hidden" name="remote_point" value="{{ $eval->remote_point }}">
						</div>

						<div class="item-block" style="margin: 0 0;">
							<p class="ttl">定年</p>
							<p><span class="star5_rating"  style="--rate: {{ $eval->retire_point * 100 / 5  . '%' }};"></span></p>
							<input type="hidden" name="retire_point" value="{{ $eval->retire_point }}">
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
									{{ $eval->ote_income }} 万円
									<input type="hidden" name="ote_income" value="{{ $eval->ote_income }}">
								</div>
							</div>
						</div>

						<div class="item-block" style="align-items: start;">
							<p class="ttl" style="margin-top: 0;">給与</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="contact-list">
											<div class="input-wrap">
												{!! nl2br(e($eval->salary_content)) !!}
												<input type="hidden" name="salary_content" value="{{ $eval->salary_content }}">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">福利厚生</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="contact-list">
											<div class="input-wrap">
												{!! nl2br(e($eval->welfare_content)) !!}
												<input type="hidden" name="welfare_content" value="{{ $eval->welfare_content }}">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">育成</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="contact-list">
											<div class="input-wrap">
												{!! nl2br(e($eval->upbring_content)) !!}
												<input type="hidden" name="upbring_content" value="{{ $eval->upbring_content }}">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">法令遵守の意識</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="contact-list">
											<div class="input-wrap">
												{!! nl2br(e($eval->compliance_content)) !!}
												<input type="hidden" name="compliance_content" value="{{ $eval->compliance_content }}">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">社員のモチベーション</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="contact-list">
											<div class="input-wrap">
												{!! nl2br(e($eval->motivation_content)) !!}
												<input type="hidden" name="motivation_content" value="{{ $eval->motivation_content }}">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">ワークライフバランス</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="contact-list">
											<div class="input-wrap">
												{!! nl2br(e($eval->work_life_content)) !!}
												<input type="hidden" name="work_life_content" value="{{ $eval->work_life_content }}">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">勤務体系<br>(リモートの有無、満足度等)</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="contact-list">
											<div class="input-wrap">
												{!! nl2br(e($eval->remote_content)) !!}
												<input type="hidden" name="remote_content" value="{{ $eval->remote_content }}">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="item-block">
							<p class="ttl">定年</p>
							<div class="form-inner contact">
								<div class="contact-list">
									<div class="form-wrap">
										<div class="contact-list">
											<div class="input-wrap">
												{!! nl2br(e($eval->retire_content)) !!}
												<input type="hidden" name="retire_content" value="{{ $eval->retire_conten }}">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>

{{-- 評価コメント END --}}


		</div>
	</div>
</main>


<script src="{{ asset('js/career.js') }}"></script>

@endsection
