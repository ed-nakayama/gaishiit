@extends('layouts.comp.auth')

@section('content')

<head>
	<title>個人設定｜{{ config('app.name', 'Laravel') }}</title>
</head>

{{--@include('comp.member_activity')--}}

            <div class="mainContentsInner">

                <div class="mainTtl title-main">
                    <h2>個人設定</h2>
                </div><!-- /.mainTtl -->

                <div class="containerContents">

                    {{ Form::open(['url' => '/comp/member/setting/store', 'name' => 'changeform' , 'id' => 'changeform']) }}
                    <section class="secContents-mb">
                        <div class="secContentsInner">
                			<div class="mainTtl title-main">
                    			<h2>メール受信設定</h2>
                			</div><!-- /.mainTtl -->
                            
                            	<div class="formContainer mg-ajust-midashi">
                                	<div class="item-name">
                                    	<p>受信する通知内容</p>
                                	</div><!-- /.item-name -->
                                    <div class="item-input">
                                     	<p class="ttl-s">新しいメッセージを受信</p>
                                    </div><!-- /.item-input -->
								</div>

                            	<div class="formContainer mg-ajust-midashi">
                                	<div class="item-name">
                                	</div><!-- /.item-name -->

									<div class="form-wrap">
										<div class="form-block">
											<div class="form-inner">
												<div>
													　<input type="checkbox" id="casual_mail_flag" name="casual_mail_flag" @if ($member->casual_mail_flag == '1') checked @endif>
														<label for="mendan">カジュアル面談</label>
												</div>
<br>
												<div>
													　<input type="checkbox" id="formal_mail_flag" name="formal_mail_flag" @if ($member->formal_mail_flag == '1') checked @endif>
														<label for="oubo">正式応募</label>
												</div>
<br>
												<div>
													　<input type="checkbox" id="event_mail_flag" name="event_mail_flag" @if ($member->event_mail_flag == '1') checked @endif>
														<label for="event">イベント</label>
												</div>
											</div>
										</div>
									</div>
								</div>
<br>
                            <div class="btnContainer">
							                {{-- 更新成功メッセージ --}}
							                @if (session('update_success'))
						                    <div class="alert alert-success"  style="color:#0000ff;">
						                        {{session('update_success')}}
						                    </div>
							                @endif
								<a href="javascript:changeform.submit()" class="squareBtn btn-large">変更</a>
                            </div><!-- /.btn-container -->
                            
                        </div><!-- /.secContentsInner -->
                    </section><!-- /.secContents-mb -->
                    {{ Form::close() }}
                   
				</div><!-- /.containerContents -->
			</div><!-- /.mainContentsInner -->


@endsection
