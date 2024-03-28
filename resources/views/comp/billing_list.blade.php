@extends('layouts.comp.admin')

@section('content')

<head>
	<title>ご請求予定｜{{ config('app.name', 'Laravel') }}</title>
</head>

            <div class="mainContentsInner-oneColumn">
                <div class="containerContents">
                    
                    <section class="secContents-mb">
                        <div class="secContentsInner">

                            <div class="contentsTitle-container">
                            <h2 class="contentsTitle">ご請求予定</h2>
                                <ul class="linkList">
                                    <li><a href="/comp/billing/hist">&gt;&gt;ご請求履歴</a></li>
                                </ul>
                            </div><!-- /.contentsTitle-container -->

                            <table class="tbl-claim-5th">
                                <tr>
                                    <th>入社日</th>
                                    <th>氏名</th>
                                    <th>ジョブ</th>
                                    <th>担当者</th>
                                    <th>ご請求金額</th>
                                </tr>
                                @foreach ($billingList as $bill)
                                <tr>
                                    <td>{{ str_replace('-','/', substr($bill->entrance_date, 0 ,10)) }}</td>
                                    <td><a href="#">{{ $bill->user_name }}</a></td>
                                    <td>{{ $bill->job_name }}</td>
                                    <td>{{ $bill->member_name }}</td>
                                    <td>\{{ number_format($bill->amount) }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div><!-- /.secContentsInner -->
                    </section><!-- /.secContents -->

                </div><!-- /.containerContents -->

            </div><!-- /.mainContentsInner -->


@endsection
