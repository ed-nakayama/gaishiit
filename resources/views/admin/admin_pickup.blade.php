@extends('layouts.admin.auth')

@section('content')

<head>
	<title>>ピックアップ管理｜{{ config('app.name', 'Laravel') }}</title>
</head>

			<div class="mainContentsInner-oneColumn">

				<div class="secTitle">
					<div class="title-main">
						<h2>ピックアップ管理</h2>
					</div><!-- /.mainTtl -->
				</div><!-- /.sec-title -->
               
				<div class="containerContents">
	
					@foreach ($pickupList as $pickup)
						<section class="secContents-mb">
							<div class="secContentsInner">
								<div class="formContainer-add ajust">
									{{ html()->form('POST', '/admin/pickup')->id('pickupform')->attribute('name', 'pickupform')->open() }}
									{{ html()->hidden('pickup_id[]', $pickup->id) }}

									<div class="formContainer mg-ajust-midashi">
										<div class="item-name">
											<p>ピックアップ{{ $loop->iteration }}</p>
										</div><!-- /.item-name -->
										<div class="item-input">
										</div><!-- /.item-input -->
									</div>

									<div class="formContainer mg-ajust-midashi">
										<div class="item-name">
											<p>企業名</p>
										</div><!-- /.item-name -->
										<div class="selectWrap harf">
											<select name="company_id[]"  class="select-no">
												<option value="">指定しない</option>
												@foreach ($compList as $comp)
													<option value="{{ $comp->id }}" @if ($pickup->company_id == $comp->id)  selected @endif>{{ $comp->name }}</option>
												@endforeach
											</select>
										</div>
									</div>

									<div class="formContainer mg-ajust-midashi">
										<div class="item-name">
											<p>メモ</p>
										</div><!-- /.item-name -->
										<div class="item-input">
											<input type="text" name="memo[]" value="{{ old('question' ,$pickup->memo) }}">
										</div><!-- /.item-input -->
									</div>
								</div>
							</div><!-- /.secContentsInner -->
						</section><!-- /.secContents -->
					@endforeach
							
					<section class="secContents-mb">
						<div class="secContentsInner">
								<div class="btnContainer">
				                	{{-- 更新成功メッセージ --}}
				                	@if (session('update_success'))
			                    		<div class="alert alert-success"  style="color:#0000ff;">
			                      	 		{{session('update_success')}}
			                    		</div>
				                	@endif
                                    <a href="javascript:pickupform.submit()" class="squareBtn btn-large">保存</a>
                                </div><!-- /.btn-container -->
								{{ html()->form()->close() }}

						</div><!-- /.secContentsInner -->

					</section><!-- /.secContents -->
				</div><!-- /.containerContents -->
			</div><!-- /.mainContentsInner -->


@endsection
