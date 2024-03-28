@extends('layouts.admin.auth')

@section('content')

<head>
    <title>企業登録 | {{ config('app.name', 'Laravel') }}</title>
</head>

	<div class="mainContentsInner-oneColumn">

		<div style="display:flex;justify-content: space-between;">
			<div class="mainTtl title-main">
				<h2>企業登録</h2>
			</div><!-- /.mainTtl -->
 		</div>

		<div class="containerContents">
			<section class="secContents-mb">
                    
				<div class="tab_box_no2">
					<div class="btn_area">
						<p class="tab_btn active">@if (empty($comp_id))<a href="/admin/comp/register?comp_id=">企業登録-新規作成</a>@else<a href="/admin/comp/edit?comp_id={{ $comp_id }}">企業登録-編集@endif</a></p>
						<p class="tab_btn">@if (empty($comp_id))<a href="javascript:void(0);"></a>@else<a href="/admin/member/list?company_id={{ $comp_id }}">企業責任者・管理者登録</a>@endif</p>
					</div>

					<div class="secContentsInner">
						<div class="panel_area" style="padding: 0px;">

							{{ Form::open(['url' => '/admin/comp/register', 'name' => 'regform' , 'id' => 'regform', "enctype" => "multipart/form-data"]) }}
							{{ Form::hidden('comp_id', old('comp_id' ,$comp->id)) }}
							<div class="secContentsInner">

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>公開</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<input type="checkbox" name="open_flag" value="1" @if ($comp->open_flag == '1')checked @endif }}">
									</div><!-- /.item-input -->
								</div>
                            
								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>企業名称（日本語）<span>*</span></p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<input type="text" name="name" style="width:800px;" value="{{ old('name' ,$comp->name) }}"><label>　ID:{{ $comp->id }}</label>
										<ul class="oneRow">
											@error('name')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
										</ul>
									</div><!-- /.item-input -->
								</div>

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>企業名称（カナ）</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<input type="text" name="name_kana" value="{{ old('name_kana' ,$comp->name_kana) }}">
										<ul class="oneRow">
											@error('name_kana')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
										</ul>
									</div><!-- /.item-input -->
								</div>

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>企業名称（英語）<span>*</span></p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<input type="text" name="name_english" value="{{ old('name_english' ,$comp->name_english) }}">
										<ul class="oneRow">
											@error('name_english')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
										</ul>
									</div><!-- /.item-input -->
								</div>

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>本社所在地</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<input type="text" name="address"  value="{{ old('address' ,$comp->address) }}">
									</div><!-- /.item-input -->
								</div>
								<br>

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>業種カテゴリ</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										{{ $comp->getBusCatName() }}
										<hr>
										<br>
									</div><!-- /.item-input -->
								</div>

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>業種</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										@foreach ($businessCat as $cat)
											<div style="font-size:16px; font-weight: bold;">{{ $cat->name }}</div>
											<div style="display:flex;flex-wrap: wrap;">
												@foreach ($businessCatDetail as $detail)
													@if ($cat->id == $detail->business_cat_id)
														<div style="margin-left: 15px;">
															@if (!empty($comp->getBusiness() ))
																{{ html()->checkbox('businessCat[]', (in_array($detail->id, $comp->getBusiness() )), $detail->id) }}{{ $detail->name }}
															@else
																{{ html()->checkbox('businessCat[]', false, $detail->id) }}{{ $detail->name }}
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

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>こだわり</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										@foreach ($commitCat as $cat)
											<div style="font-size:16px; font-weight: bold;">{{ $cat->name }}</div>
											<div style="display:flex;flex-wrap: wrap;">
												@foreach ($commitCatDetail as $detail)
													@if ($cat->id == $detail->commit_cat_id)
														<div style="margin-left: 15px;">
															@if (!empty($comp->getCommit() ))
																{{ html()->checkbox('commitCat[]', (in_array($detail->id, $comp->getCommit() )), $detail->id) }}{{ $detail->name }}
															@else
																{{ html()->checkbox('commitCat[]', false, $detail->id) }}{{ $detail->name }}
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

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>URL</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<input type="text" name="url"  value="{{ old('url' ,$comp->url) }}">
									</div><!-- /.item-input -->
								</div>

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>代表者</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<input type="text" name="ceo"  value="{{ old('ceo' ,$comp->ceo) }}">
									</div><!-- /.item-input -->

									<div style="margin: 0 10px 0 20px;">
										<p>従業員数（人）</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<input type="text" name="employ_num"  value="{{ old('employ_num' ,$comp->employ_num) }}">
									</div><!-- /.item-input -->

									<div style="margin: 0 10px 0 20px;">
										<p>設立年（西暦）</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<input type="text" name="establish_year"  value="{{ old('establish_year' ,$comp->establish_year) }}">
									</div><!-- /.item-input -->

									<div style="margin: 0 10px 0 20px;">
										<p>資本金（百万円）</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<input type="text" name="capital"  value="{{ old('capital' ,$comp->capital) }}">
									</div><!-- /.item-input -->
								</div>

								<div class="formContainer al-item-none mg-ajust">
									<div class="item-name">
										<p>紹介<span>*</span></p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<textarea class="form-mt" name="intro" id="intro" cols="30" rows="10" placeholder="本文">{{ $comp->intro }}</textarea>
										<ul class="oneRow">
											@error('intro')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
										</ul>
                                    </div><!-- /.item-input -->
								</div>

								<div class="formContainer al-item-none mg-ajust">
									<div class="item-name">
										<p>Memo</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<textarea class="form-mt" name="memo" id="memo" cols="30" rows="2" >{{ $comp->memo }}</textarea>
                                    </div><!-- /.item-input -->
								</div>

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>支払い担当者名<span>*</span></p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<input type="text" name="cost_person_name"  value="{{ old('cost_person_name' ,$comp->cost_person_name) }}">
										<ul class="oneRow">
											@error('cost_person_name')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
										</ul>
									</div><!-- /.item-input -->
								</div>

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>支払い担当者メール<span>*</span></p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<input type="text" name="cost_person_mail"  value="{{ old('cost_person_mail' ,$comp->cost_person_mail) }}">
										<ul class="oneRow">
											@error('cost_person_mail')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
										</ul>
									</div><!-- /.item-input -->
								</div>

{{--
								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>管理者名<span>*</span></p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<input type="text" name="personnel_name"  value="{{ old('personnel_name' ,$comp->personnel_name) }}">
										<ul class="oneRow">
											@error('personnel_name')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
										</ul>
									</div><!-- /.item-input -->
								</div>

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>管理者メール<span>*</span></p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<input type="text" name="personnel_mail"  value="{{ old('personnel_mail' ,$comp->personnel_mail) }}">
										<ul class="oneRow">
											@error('personnel_mail')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
										</ul>
									</div><!-- /.item-input -->
								</div>
--}}

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>都度払い契約</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<table>
											<tr>
												<td><label style="padding: 5px 5px;border: 1px solid #ccc;"><input type="date" name="every_start_date" value="{{ old('every_start_date' ,$comp->every_start_date) }}"></label></td>
												<td>～</td>
												<td><label style="padding: 5px 5px;border: 1px solid #ccc;"><input type="date" name="every_end_date" value="{{ old('every_end_date' ,$comp->every_end_date) }}"</label></td>
											</tr>
										</table>
										<ul class="oneRow">
											@error('every_start_date')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
											@error('every_end_date')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
										</ul>
									</div><!-- /.item-input -->
								</div>

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>月払い契約</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<table>
											<tr>
												<td><label style="padding: 5px 5px;border: 1px solid #ccc;"><input type="date" name="monthly_start_date" value="{{ old('monthly_start_date' ,$comp->monthly_start_date) }}"></label></td>
												<td>～</td>
												<td><label style="padding: 5px 5px;border: 1px solid #ccc;"><input type="date" name="monthly_end_date" value="{{ old('monthly_end_date' ,$comp->monthly_end_date) }}"></label></td>
												<td style="padding: 0px 20px;"><input type="text" name="monthly_price" value="{{ old('monthly_price' ,$comp->monthly_price) }}"></td>
												<td> 円</td>
											</tr>
										</table>
										<ul class="oneRow">
											@error('monthly_start_date')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
											@error('monthly_end_date')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
											@error('monthly_price')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
										</ul>
									</div><!-- /.item-input -->
								</div>

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>年払い契約</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<table>
											<tr>
												<td><label style="padding: 5px 5px;border: 1px solid #ccc;"><input type="date" name="yearly_start_date" value="{{ old('yearly_start_date' ,$comp->yearly_start_date) }}"></label></td>
												<td>～</td>
												<td><label style="padding: 5px 5px;border: 1px solid #ccc;"><input type="date" name="yearly_end_date" value="{{ old('yearly_end_date' ,$comp->yearly_end_date) }}"></label></td>
												<td style="padding: 0px 20px;"><input type="text" name="yearly_price" value="{{ old('yearly_price' ,$comp->yearly_price) }}"></td>
												<td> 円</td>
											</tr>
										</table>
										<ul class="oneRow">
											@error('yearly_start_date')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
											@error('yearly_end_date')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
											@error('yearly_price')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
										</ul>
									</div><!-- /.item-input -->
								</div>

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>手数料についてのメモ<span>*</span></p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<input type="text" name="fee_memo"  value="{{ old('fee_memo' ,$comp->fee_memo) }}">
										<ul class="oneRow">
											@error('fee_memo')
												<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
											@enderror
										</ul>
									</div><!-- /.item-input -->
								</div>

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>ARK代理管理</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<input type="checkbox" name="agency_flag" value="1" @if ($comp->agency_flag == '1')checked @endif }}">
									</div><!-- /.item-input -->
								</div>

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>正式応募に必要<br>な書類の初期値</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<ul class="checkboxList">
											<li><label><input type="checkbox" name="backg_flag" value="1" @if (old('backg_flag' ,$comp->backg_flag) == '1')  checked="checked" @endif>職務経歴書</label></li>
											<li><label><input type="checkbox" name="backg_eng_flag" value="1" @if (old('backg_eng_flag',$comp->backg_eng_flag) == '1')  checked="checked" @endif>職務経歴書（英文）</label></li>
											<li><label><input type="checkbox" name="personal_flag" value="1" @if (old('personal_flag' ,$comp->personal_flag) == '1')  checked="checked" @endif>履歴書</label></li>
										</ul><!-- /.checkboxList -->
									</div><!-- /.item-input -->
								</div>



								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>最大InMail数</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										正式応募　<input type="text" name="in_mail_formal" value="{{$comp->in_mail_formal }}" style="width: 100px;">
										　　カジュアル面談　<input type="text" name="in_mail_casual" value="{{$comp->in_mail_casual }}" style="width: 100px;">
									</div><!-- /.item-input -->
								</div>
								
								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>SalesforceID</p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<input type="text" name="salesforce_id"  value="{{ old('salesforce_id' ,$comp->salesforce_id) }}" style="width: 300px;">
									</div><!-- /.item-input -->
								</div>

								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>ロゴ</span></p>
									</div><!-- /.item-name -->
									<div class="item-input">
										@if ( isset($comp->logo_file) )
											<img src="{{ $comp->logo_file }}" class="css-class" alt="logo" border="1" width="100px">　{{ basename($comp->logo_file) }}
										@endif
									</div><!-- /.item-input -->
									<div class="item-input">
										{{ Form::file('logo', ['class'=>'form-control']) }}
									</div>
									<div class="item-input">
										<p> ※jpg、png、500KB以内</p>
									</div>
								</div>


								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p>イメージ<span>*</span></p>
									</div><!-- /.item-name -->
									<div class="item-input">
										@if ( isset($comp->image_file) )
											<img src="{{ $comp->image_file }}" class="css-class" alt="image" width="250">
										@endif
									</div><!-- /.item-input -->
									<div class="item-input">
										{{ Form::file('image', ['class'=>'form-control']) }}
									</div>
									<div class="item-input">
										<p> ※jpg、png、500KB以内</p>
									</div>
								</div>
								<div class="formContainer mg-ajust-midashi">
									<div class="item-name">
										<p></p>
									</div><!-- /.item-name -->
									<div class="item-input">
										<ul class="oneRow">
										@error('image')
											<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
										@enderror
										</ul>
									</div>
								</div>



								<br/>
								<div class="btnContainer">
									@if (auth()->user()->comp_priv == '1')
										<a href="javascript:regform.submit()" class="squareBtn btn-large">保存</a>
									@endif
								</div><!-- /.btn-container -->

							</div><!-- /.secContentsInner -->
						{{ html()->form()->close() }}

						</div><!-- /.panel_area -->
					</div><!-- /.secContentsInner -->
				</div><!-- /.tab_box_no -->

			</section><!-- /.secContents-mb -->
		</div><!-- /.containerContents -->


@endsection
