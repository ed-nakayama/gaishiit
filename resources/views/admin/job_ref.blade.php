@extends('layouts.admin.auth')

@section('content')

<head>
	<title>ジョブ管理｜{{ config('app.name', 'Laravel') }}</title>
</head>


<style>
.scroll{
  height: 600px;
  overflow: auto;
}

.subcont {
	border:solid 1px;
	padding:4px;
}

</style>

<div class="mainContentsInner">
	<div class="mainTtl title-main">
		<h2>ジョブ管理 - 参照</h2>
		<h3>{{ $job->getCompanyName() }}</h3>
	</div><!-- /.mainTtl -->

	<div class="containerContents">
		<section class="secContents-mb">
			<div class="secContentsInner">

				<ul class="jobToggleList leftAlign">
					<li>
						<div class="button-radio">
							<input id="c_ch1" class="radiobutton" name="open_flag" type="radio" value="1"  @if ($job->open_flag == '1')  checked="checked" @endif onClick= 'return false;' />
							<label for="c_ch1">表示</label> /
							<input id="c_ch2" class="radiobutton" name="open_flag" type="radio" value="0"  @if ($job->open_flag == '0')  checked="checked" @endif onClick= 'return false;' />
							<label for="c_ch2">非表示</label> 
						</div>
					</li>
					<li>
						<div class="btnContainer">
							ID : {{ $job->id }}
						</div><!-- /.btn-container -->
					</li>
				</ul><!-- /.jobToggle -->
			</div><!-- /.secContentsInner -->
		</section><!-- /.secContents-mb -->

		<section class="secContents">
			<div class="secContentsInner">

				<ul class="jobToggleList">
					<li  style="display:flex;">
							<label for="c_ch1">このジョブ宛にカジュアル面談を受け付ける　</label>
						<div class="button-radio">
							<input id="cas_ch1" class="radiobutton" name="casual_flag" type="radio" value="1"  @if ($job->casual_flag == '1')  checked="checked" @endif onClick= 'return false;' />
							<label for="cas_ch1" style="padding:3px 10px;">はい</label> /
							<input id="cas_ch2" class="radiobutton" name="casual_flag" type="radio" value="0"  @if ($job->casual_flag == '0')  checked="checked" @endif onClick= 'return false;' />
							<label for="cas_ch2" style="padding:3px 10px;">いいえ</label> 
						</div>
					</li>
				</ul><!-- /.jobToggle -->

				<div class="formContainer mg-ajust-midashi">
					<div class="item-name">
						<p>更新日</p>
					</div><!-- /.item-name -->
					<div class="item-input">
						{{ $job->updated_at }}
					</div><!-- /.item-input -->
				</div><!-- END formContainer mg-ajuse -->

				@if ( isset($unitList[0]) )
					<div class="formContainer mg-ajust">
						<div class="item-name">
							<p>部門</p>
						</div><!-- /.item-name -->
						<div class="item-input subcont">
							{{ $job->getUnitName() }}
						</div><!-- /.item-input -->
					</div><!-- END formContainer mg-ajuse -->
				@endif
                                
				<div class="formContainer mg-ajust-midashi">
					<div class="item-name">
						<p>名称</p>
					</div><!-- /.item-name -->
					<div class="item-input subcont">
						{{ $job->name }}
					</div><!-- /.item-input -->
				</div><!-- END formContainer mg-ajuse -->
                                
				<div class="formContainer al-item-none mg-ajust">
					<div class="item-name">
						<p>紹介</p>
					</div><!-- /.item-name -->
					<div class="item-input subcont">
						{!! nl2br($job->intro) !!}
					</div><!-- /.item-input -->
				</div><!-- END formContainer -->

				<div class="formContainer mg-ajust">
					<div class="item-name">
						<p>一般/portal</p>
					</div><!-- /.item-name -->
					<div class="item-input subcont">
						@if ($job->portal_flag == '1') portal @else 一般 @endif
					</div><!-- /.item-input -->
				</div><!-- END formContainer -->

				<div class="formContainer mg-ajust">
					<div class="item-name">
						<p>URL</p>
					</div><!-- /.item-name -->
					<div class="item-input">
						<a href="{{ $job->url }}" style="text-decoration:underline; color:blue;" target="_blank">{{ $job->url }}</a>
					</div><!-- /.item-input -->
				</div><!-- END formContainer -->

				<div class="formContainer mg-ajust">
					<div class="item-name">
						<p>ジョブID</p>
					</div><!-- /.item-name -->
					<div class="item-input subcont">
						{{ $job->job_code }}
					</div><!-- /.item-input -->
				</div><!-- END formContainer -->

				<div class="formContainer mg-ajust">
					<div class="item-name">
						<p>職種</p>
					</div><!-- /.item-name -->
					<div class="item-input subcont">
						@foreach ($jobCat as $cat)
							@if ( in_array($cat->id, $job->getJobCat())  )
								<div style="font-size:16px; font-weight: bold;">{{ $cat->name }}
								</div>
								<div style="display:flex;flex-wrap: wrap; margin-left: 4px;">
									<div style="margin-left: 15px;">
										@foreach ($jobCatDetail as $detail)
											@if ($cat->id == $detail->job_cat_id)
												@if (!empty($job->getJobCategory() ))
													@if ( in_array($detail->id, $job->getJobCategory())  )
														/{{ $detail->name }}
													@endif
												@endif
											@endif
										@endforeach
									</div>
								</div>
							@endif
						@endforeach
					</div><!-- /.item-input -->
				</div>

				<div class="formContainer mg-ajust">
					<div class="item-name">
						<p>担当業界</p>
					</div><!-- /.item-name -->
					<div class="item-input subcont">
						@foreach ($industoryCat as $cat)
							<div style="font-size:16px; font-weight:">
								<div>
									@if (!empty($job->getIndcatCat() ))
										@if ( in_array($cat->id, $job->getIndcatCat())  )
											{{ $cat->name }}
										@endif
									@endif
								</div>
							</div>
						@endforeach
					</div><!-- /.item-input -->
				</div>

				<div class="formContainer mg-ajust">
					<div class="item-name">
						<p>年収</p>
					</div><!-- /.item-name -->
					<div class="item-input subcont">
						{{ $job->getIncome() }}
					</div><!-- /.item-input -->
				</div><!-- END formContainer -->
                                
				<div class="formContainer mg-ajust">
					<div class="item-name">
						<p>補足カテゴリ</p>
					</div><!-- /.item-name -->
					<div class="item-input subcont">
						{{ $job->sub_category }}
					</div><!-- /.item-input -->
				</div><!-- END formContainer -->

				<div class="formContainer mg-ajust-midashi">
					<div class="item-name">
						<p>ロケーション</p>
					</div><!-- /.item-name -->
					<div class="item-input subcont">
						{{ $job->getLocations() }}
					</div><!-- /.item-input -->
				</div><!-- END formContainer -->

				<div class="formContainer mg-ajust" id="changeElseLocation">
					<div class="item-name">
						<p>その他ロケーション</p>
					</div><!-- /.item-name -->
					<div class="item-input subcont">
						{{  $job->else_location }}
					</div><!-- /.item-input -->
				</div><!-- END formContainer -->

				<div class="formContainer al-item-none mg-ajust">
					<div class="item-name">
						<p>勤務地詳細/その他</p>
					</div><!-- /.item-name -->
					<div class="item-input subcont" id="changeWorking_place">
						{!! nl2br($job->working_place) !!}
					</div><!-- /.item-input -->
				</div><!-- END formContainer -->

				<div class="formContainer bb-ajust">
					<div class="item-name">
						<p>正式応募に必要<br>な書類</p>
					</div><!-- /.item-name -->
					<div class="item-input">
						<ul class="checkboxList">
							<li><label>{{ html()->checkbox('backg_flag', $job->backg_flag, "1")->attribute('onClick', 'return false;') }}職務経歴書</label></li>
							<li><label>{{ html()->checkbox('backg_eng_flag', $job->backg_eng_flag, "1")->attribute('onClick','return false;') }}職務経歴書（英文）</label></li>
							<li><label>{{ html()->checkbox('personal_flag', $job->personal_flag, "1")->attribute('onClick','return false;') }}履歴書</label></li>
						</ul><!-- /.checkboxList -->
					</div><!-- /.item-input -->
				</div><!-- END formContainer -->
                                
				<div class="formContainer bb-ajust">
					<div class="item-name">
						<p>担当</p>
					</div><!-- /.item-name -->
					<div class="item-input item-input-row">
						<div class="item-input-btn">

						</div>
						<span id="member_text" class="border border-secondary border-5 bg-white" style="padding-right: 15px;">{{ $job->getPerson() }}</span>
					</div><!-- /.item-input -->
				</div>

					<font color='red'>※ChatGPTで使用</font><br>

					<div class="formContainer al-item-none mg-ajust">
						<div class="item-name">
							<p>仕事内容</p>
						</div><!-- /.item-name -->
						<div class="item-input subcont">
							{{ nl2br($job->app_contents) }}
						</div><!-- /.item-input -->
					</div><!-- END formContainer -->

					<div class="formContainer al-item-none mg-ajust">
						<div class="item-name">
							<p>募集要項</p>
						</div><!-- /.item-name -->
						<div class="item-input subcont">
							{{ nl2br($job->app_details) }}
						</div><!-- /.item-input -->
					</div><!-- END formContainer -->

			</div><!-- /.secContentsInner -->
		</section><!-- /.secContents -->
                   
	</div><!-- /.containerContents -->
</div><!-- /.mainContentsInner -->


@endsection
