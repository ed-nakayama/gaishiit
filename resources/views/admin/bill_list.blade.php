@extends('layouts.admin.auth')
<head>
    <title>請求情報 | {{ config('app.name', 'Laravel') }}</title>
</head>


@section('content')

            <div class="mainContentsInner-oneColumn">
                <div class="containerContents">

                    <div class="mainTtl title-main">
                        <h2>請求情報</h2>
                    </div><!-- /.mainTtl -->
                
                    <section class="secContents-mb">
                        <div class="secContentsInner">

                            <div class="yearTotalPrice">
                                <div class="selectWrap yearTotalPriceSelect">
									{{ Form::open(['url' => '/admin/bill', 'name' => 'changeform']) }}
										<select name="fiscal_year"  class="select-no" onchange="this.form.submit()">
										@foreach ($fiscalYearList as $year)
											<option value="{{ $year }}" @if ($fiscal_year == $year)  selected @endif>{{ $year }}年度</option>
										@endforeach
										</select>
									{{ html()->form()->close() }}

                                </div>
                                <div class="yearTotalPriceTxt">
                                    <p>合計：￥ {{ number_format($grand_total) }}</p>
                                </div><!-- /.totalPrice -->
                            </div><!-- /.yearTotalPrice -->
                            
                        </div><!-- /.secContentsInner -->
                    </section><!-- /.secContents -->

                    <section class="secContents">
                        <div class="secContentsInner">

                            <div class="tab_box">
                                <div class="btn_area">
                                    <p class="tab_btn active">7月</p>
                                    <p class="tab_btn">8月</p>
                                    <p class="tab_btn">9月</p>
                                    <p class="tab_btn">10月</p>
                                    <p class="tab_btn">11月</p>
                                    <p class="tab_btn">12月</p>
                                    <p class="tab_btn">1月</p>
                                    <p class="tab_btn">2月</p>
                                    <p class="tab_btn">3月</p>
                                    <p class="tab_btn">4月</p>
                                    <p class="tab_btn">5月</p>
                                    <p class="tab_btn">6月</p>
                                </div>
                                <div class="panel_area">

                                    <div class="tab_panel active">
                                        <div class="monthTotalList">
											@foreach ($billTotal as $total)
												@if (substr($total->fiscal_mon, 5 ,2) == '07')
				                                	<div class="month">{{ substr($total->fiscal_mon, 0 ,4) }}年{{ substr($total->fiscal_mon, 5 ,2) }}月</div>
    		    		                            <div class="total">合計：￥@if ($total->sub_total != '') {{ number_format($total->sub_total) }}@endif</div>
												@endif
											@endforeach
                                            <div class="price">
												@foreach ($billList as $bill)
													@if (substr($bill->bill_date, 5 ,2) == '07')
                                                		<ul class="item">
                                                    		<li>
																{{ Form::open(['url' => '/admin/bill/detail', 'name' => 'userform' . $bill->id ]) }}
																{{ Form::hidden('bill_date', $bill->bill_date) }}
																{{ Form::hidden('company_id', $bill->company_id) }}
																<a href="javascript:userform{{ $bill->id }}.submit()">{{ $bill->company_name }}</a>
																{{ Form::close() }}
                                                    			<span>￥ {{ number_format($bill->total_price) }}</span>
                                                    		</li>
                                                		</ul>
													@endif
												@endforeach
                                            </div>
                                        </div><!-- /.monthTotalList -->
                                    </div><!-- /.tab_panel -->
    
                                    <div class="tab_panel">
                                        <div class="monthTotalList">
											@foreach ($billTotal as $total)
												@if (substr($total->fiscal_mon, 5 ,2) == '08')
				                                	<div class="month">{{ substr($total->fiscal_mon, 0 ,4) }}年{{ substr($total->fiscal_mon, 5 ,2) }}月</div>
    		    		                            <div class="total">合計：￥@if ($total->sub_total != '') {{ number_format($total->sub_total) }}@endif</div>
												@endif
											@endforeach
                                            <div class="price">
												@foreach ($billList as $bill)
													@if (substr($bill->bill_date, 5 ,2) == '08')
                                                		<ul class="item">
                                                    		<li>
																{{ Form::open(['url' => '/admin/bill/detail', 'name' => 'userform' . $bill->id ]) }}
																{{ Form::hidden('bill_date', $bill->bill_date) }}
																{{ Form::hidden('company_id', $bill->company_id) }}
																<a href="javascript:userform{{ $bill->id }}.submit()">{{ $bill->company_name }}</a>
																{{ Form::close() }}
                                                    			<span>￥ {{ number_format($bill->total_price) }}</span>
                                                    		</li>
                                                		</ul>
													@endif
												@endforeach
                                            </div>
                                        </div><!-- /.monthTotalList -->
                                    </div><!-- /.tab_panel -->
    
                                    <div class="tab_panel">
                                        <div class="monthTotalList">
											@foreach ($billTotal as $total)
												@if (substr($total->fiscal_mon, 5 ,2) == '09')
				                                	<div class="month">{{ substr($total->fiscal_mon, 0 ,4) }}年{{ substr($total->fiscal_mon, 5 ,2) }}月</div>
    		    		                            <div class="total">合計：￥@if ($total->sub_total != '') {{ number_format($total->sub_total) }}@endif</div>
												@endif
											@endforeach
                                            <div class="price">
												@foreach ($billList as $bill)
													@if (substr($bill->bill_date, 5 ,2) == '09')
                                                		<ul class="item">
                                                    		<li>
																{{ Form::open(['url' => '/admin/bill/detail', 'name' => 'userform' . $bill->id ]) }}
																{{ Form::hidden('bill_date', $bill->bill_date) }}
																{{ Form::hidden('company_id', $bill->company_id) }}
																<a href="javascript:userform{{ $bill->id }}.submit()">{{ $bill->company_name }}</a>
																{{ Form::close() }}
                                                    			<span>￥ {{ number_format($bill->total_price) }}</span>
                                                    		</li>
                                                		</ul>
													@endif
												@endforeach
                                            </div>
                                        </div><!-- /.monthTotalList -->
                                    </div><!-- /.tab_panel -->

    
                                    <div class="tab_panel">
                                        <div class="monthTotalList">
											@foreach ($billTotal as $total)
												@if (substr($total->fiscal_mon, 5 ,2) == '10')
				                                	<div class="month">{{ substr($total->fiscal_mon, 0 ,4) }}年{{ substr($total->fiscal_mon, 5 ,2) }}月</div>
    		    		                            <div class="total">合計：￥@if ($total->sub_total != '') {{ number_format($total->sub_total) }}@endif</div>
												@endif
											@endforeach
                                            <div class="price">
												@foreach ($billList as $bill)
													@if (substr($bill->bill_date, 5 ,2) == '10')
                                                		<ul class="item">
                                                    		<li>
																{{ Form::open(['url' => '/admin/bill/detail', 'name' => 'userform' . $bill->id ]) }}
																{{ Form::hidden('bill_date', $bill->bill_date) }}
																{{ Form::hidden('company_id', $bill->company_id) }}
																<a href="javascript:userform{{ $bill->id }}.submit()">{{ $bill->company_name }}</a>
																{{ Form::close() }}
                                                    			<span>￥ {{ number_format($bill->total_price) }}</span>
                                                    		</li>
                                                		</ul>
													@endif
												@endforeach
                                            </div>
                                        </div><!-- /.monthTotalList -->
                                    </div><!-- /.tab_panel -->

                                    <div class="tab_panel">
                                        <div class="monthTotalList">
											@foreach ($billTotal as $total)
												@if (substr($total->fiscal_mon, 5 ,2) == '11')
				                                	<div class="month">{{ substr($total->fiscal_mon, 0 ,4) }}年{{ substr($total->fiscal_mon, 5 ,2) }}月</div>
    		    		                            <div class="total">合計：￥@if ($total->sub_total != '') {{ number_format($total->sub_total) }}@endif</div>
												@endif
											@endforeach
                                            <div class="price">
												@foreach ($billList as $bill)
													@if (substr($bill->bill_date, 5 ,2) == '11')
                                                		<ul class="item">
                                                    		<li>
																{{ Form::open(['url' => '/admin/bill/detail', 'name' => 'userform' . $bill->id ]) }}
																{{ Form::hidden('bill_date', $bill->bill_date) }}
																{{ Form::hidden('company_id', $bill->company_id) }}
																<a href="javascript:userform{{ $bill->id }}.submit()">{{ $bill->company_name }}</a>
																{{ Form::close() }}
                                                    			<span>￥ {{ number_format($bill->total_price) }}</span>
                                                    		</li>
                                                		</ul>
													@endif
												@endforeach
                                            </div>
                                        </div><!-- /.monthTotalList -->
                                    </div><!-- /.tab_panel -->

                                    <div class="tab_panel">
                                        <div class="monthTotalList">
											@foreach ($billTotal as $total)
												@if (substr($total->fiscal_mon, 5 ,2) == '12')
				                                	<div class="month">{{ substr($total->fiscal_mon, 0 ,4) }}年{{ substr($total->fiscal_mon, 5 ,2) }}月</div>
    		    		                            <div class="total">合計：￥@if ($total->sub_total != '') {{ number_format($total->sub_total) }}@endif</div>
												@endif
											@endforeach
                                            <div class="price">
												@foreach ($billList as $bill)
													@if (substr($bill->bill_date, 5 ,2) == '12')
                                                		<ul class="item">
                                                    		<li>
																{{ Form::open(['url' => '/admin/bill/detail', 'name' => 'userform' . $bill->id ]) }}
																{{ Form::hidden('bill_date', $bill->bill_date) }}
																{{ Form::hidden('company_id', $bill->company_id) }}
																<a href="javascript:userform{{ $bill->id }}.submit()">{{ $bill->company_name }}</a>
																{{ Form::close() }}
                                                    			<span>￥ {{ number_format($bill->total_price) }}</span>
                                                    		</li>
                                                		</ul>
													@endif
												@endforeach
                                            </div>
                                        </div><!-- /.monthTotalList -->
                                    </div><!-- /.tab_panel -->

                                    <div class="tab_panel">
                                        <div class="monthTotalList">
											@foreach ($billTotal as $total)
												@if (substr($total->fiscal_mon, 5 ,2) == '01')
				                                	<div class="month">{{ substr($total->fiscal_mon, 0 ,4) }}年{{ substr($total->fiscal_mon, 5 ,2) }}月</div>
    		    		                            <div class="total">合計：￥@if ($total->sub_total != '') {{ number_format($total->sub_total) }}@endif</div>
												@endif
											@endforeach
                                            <div class="price">
												@foreach ($billList as $bill)
													@if (substr($bill->bill_date, 5 ,2) == '01')
                                                		<ul class="item">
                                                    		<li>
																{{ Form::open(['url' => '/admin/bill/detail', 'name' => 'userform' . $bill->id ]) }}
																{{ Form::hidden('bill_date', $bill->bill_date) }}
																{{ Form::hidden('company_id', $bill->company_id) }}
																<a href="javascript:userform{{ $bill->id }}.submit()">{{ $bill->company_name }}</a>
																{{ Form::close() }}
                                                    			<span>￥ {{ number_format($bill->total_price) }}</span>
                                                    		</li>
                                                		</ul>
													@endif
												@endforeach
                                            </div>
                                        </div><!-- /.monthTotalList -->
                                    </div><!-- /.tab_panel -->

                                    <div class="tab_panel">
                                        <div class="monthTotalList">
											@foreach ($billTotal as $total)
												@if (substr($total->fiscal_mon, 5 ,2) == '02')
				                                	<div class="month">{{ substr($total->fiscal_mon, 0 ,4) }}年{{ substr($total->fiscal_mon, 5 ,2) }}月</div>
    		    		                            <div class="total">合計：￥@if ($total->sub_total != '') {{ number_format($total->sub_total) }}@endif</div>
												@endif
											@endforeach
                                            <div class="price">
												@foreach ($billList as $bill)
													@if (substr($bill->bill_date, 5 ,2) == '02')
                                                		<ul class="item">
                                                    		<li>
																{{ Form::open(['url' => '/admin/bill/detail', 'name' => 'userform' . $bill->id ]) }}
																{{ Form::hidden('bill_date', $bill->bill_date) }}
																{{ Form::hidden('company_id', $bill->company_id) }}
																<a href="javascript:userform{{ $bill->id }}.submit()">{{ $bill->company_name }}</a>
																{{ Form::close() }}
                                                    			<span>￥ {{ number_format($bill->total_price) }}</span>
                                                    		</li>
                                                		</ul>
													@endif
												@endforeach
                                            </div>
                                        </div><!-- /.monthTotalList -->
                                    </div><!-- /.tab_panel -->

                                    <div class="tab_panel">
                                        <div class="monthTotalList">
											@foreach ($billTotal as $total)
												@if (substr($total->fiscal_mon, 5 ,2) == '03')
				                                	<div class="month">{{ substr($total->fiscal_mon, 0 ,4) }}年{{ substr($total->fiscal_mon, 5 ,2) }}月</div>
    		    		                            <div class="total">合計：￥@if ($total->sub_total != '') {{ number_format($total->sub_total) }}@endif</div>
												@endif
											@endforeach
                                            <div class="price">
												@foreach ($billList as $bill)
													@if (substr($bill->bill_date, 5 ,2) == '03')
                                                		<ul class="item">
                                                    		<li>
																{{ Form::open(['url' => '/admin/bill/detail', 'name' => 'userform' . $bill->id ]) }}
																{{ Form::hidden('bill_date', $bill->bill_date) }}
																{{ Form::hidden('company_id', $bill->company_id) }}
																<a href="javascript:userform{{ $bill->id }}.submit()">{{ $bill->company_name }}</a>
																{{ Form::close() }}
                                                    			<span>￥ {{ number_format($bill->total_price) }}</span>
                                                    		</li>
                                                		</ul>
													@endif
												@endforeach
                                            </div>
                                        </div><!-- /.monthTotalList -->
                                    </div><!-- /.tab_panel -->

                                    <div class="tab_panel">
                                        <div class="monthTotalList">
											@foreach ($billTotal as $total)
												@if (substr($total->fiscal_mon, 5 ,2) == '04')
				                                	<div class="month">{{ substr($total->fiscal_mon, 0 ,4) }}年{{ substr($total->fiscal_mon, 5 ,2) }}月</div>
    		    		                            <div class="total">合計：￥@if ($total->sub_total != '') {{ number_format($total->sub_total) }}@endif</div>
												@endif
											@endforeach
                                            <div class="price">
												@foreach ($billList as $bill)
													@if (substr($bill->bill_date, 5 ,2) == '04')
                                                		<ul class="item">
                                                    		<li>
																{{ Form::open(['url' => '/admin/bill/detail', 'name' => 'userform' . $bill->id ]) }}
																{{ Form::hidden('bill_date', $bill->bill_date) }}
																{{ Form::hidden('company_id', $bill->company_id) }}
																<a href="javascript:userform{{ $bill->id }}.submit()">{{ $bill->company_name }}</a>
																{{ Form::close() }}
                                                    			<span>￥ {{ number_format($bill->total_price) }}</span>
                                                    		</li>
                                                		</ul>
													@endif
												@endforeach
                                            </div>
                                        </div><!-- /.monthTotalList -->
                                    </div><!-- /.tab_panel -->

                                    <div class="tab_panel">
                                        <div class="monthTotalList">
											@foreach ($billTotal as $total)
												@if (substr($total->fiscal_mon, 5 ,2) == '05')
				                                	<div class="month">{{ substr($total->fiscal_mon, 0 ,4) }}年{{ substr($total->fiscal_mon, 5 ,2) }}月</div>
    		    		                            <div class="total">合計：￥@if ($total->sub_total != '') {{ number_format($total->sub_total) }}@endif</div>
												@endif
											@endforeach
                                            <div class="price">
												@foreach ($billList as $bill)
													@if (substr($bill->bill_date, 5 ,2) == '05')
                                                		<ul class="item">
                                                    		<li>
																{{ Form::open(['url' => '/admin/bill/detail', 'name' => 'userform' . $bill->id ]) }}
																{{ Form::hidden('bill_date', $bill->bill_date) }}
																{{ Form::hidden('company_id', $bill->company_id) }}
																<a href="javascript:userform{{ $bill->id }}.submit()">{{ $bill->company_name }}</a>
																{{ Form::close() }}
                                                    			<span>￥ {{ number_format($bill->total_price) }}</span>
                                                    		</li>
                                                		</ul>
													@endif
												@endforeach
                                            </div>
                                        </div><!-- /.monthTotalList -->
                                    </div><!-- /.tab_panel -->

                                    <div class="tab_panel">
                                        <div class="monthTotalList">
											@foreach ($billTotal as $total)
												@if (substr($total->fiscal_mon, 5 ,2) == '06')
				                                	<div class="month">{{ substr($total->fiscal_mon, 0 ,4) }}年{{ substr($total->fiscal_mon, 5 ,2) }}月</div>
    		    		                            <div class="total">合計：￥@if ($total->sub_total != '') {{ number_format($total->sub_total) }}@endif</div>
												@endif
											@endforeach
                                            <div class="price">
												@foreach ($billList as $bill)
													@if (substr($bill->bill_date, 5 ,2) == '06')
                                                		<ul class="item">
                                                    		<li>
																{{ Form::open(['url' => '/admin/bill/detail', 'name' => 'userform' . $bill->id ]) }}
																{{ Form::hidden('bill_date', $bill->bill_date) }}
																{{ Form::hidden('company_id', $bill->company_id) }}
																<a href="javascript:userform{{ $bill->id }}.submit()">{{ $bill->company_name }}</a>
																{{ Form::close() }}
                                                    			<span>￥ {{ number_format($bill->total_price) }}</span>
                                                    		</li>
                                                		</ul>
													@endif
												@endforeach
                                            </div>
                                        </div><!-- /.monthTotalList -->
                                    </div><!-- /.tab_panel -->

                                </div>
                            </div><!-- /.tab_box -->
                        </div><!-- /.secContentsInner -->
                    </section><!-- /.secContents -->

                </div><!-- /.containerContents -->
            </div><!-- /.mainContentsInner -->


<script type="text/javascript">


@endsection
