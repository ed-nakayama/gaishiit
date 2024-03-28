@extends('layouts.admin.auth')
<head>
    <title>オーナーシップ管理 | {{ config('app.name', 'Laravel') }}</title>
</head>

@section('content')

        <div class="mainContents">

            <div class="mainContentsInner-oneColumn">
                <div class="mainTtl title-main">
                    <h2>オーナーシップ管理</h2>
                </div><!-- /.mainTtl -->
                
                <div class="containerContents">

                    <section class="secContents">
                        <div class="secContentsInner">

							<p style="text-align: center;">全{{ $ownerList->total() }}件中 {{  ($ownerList->currentPage() -1) * $ownerList->perPage() + 1}}-{{ (($ownerList->currentPage() -1) * $ownerList->perPage() + 1) + (count($ownerList) -1)  }}件</p>
                            <table class="tbl-ownership-6th">
                                <tr>
                                    <th>氏名</th>
                                    <th>発生日</th>
                                    <th>企業名</th>
                                    <th>部門</th>
                                    <th>内容</th>
                                    <th>種別</th>
                                </tr>
                                @foreach ($ownerList as $int)
                                <tr>
                                    <td>
                                        {{ Form::open(['url' => '/admin/user/detail', 'name' => 'userform' . $int->id ]) }}
                                        {{ Form::hidden('user_id', $int->user_id) }}
										{{ Form::hidden('parent_id', '3') }}
                                        <a href="javascript:userform{{ $int->id }}.submit()" style="text-decoration: underline;">{{ $int->user_name }}</a>
										{{ html()->form()->close() }}
                                    </td>
                                    <td>{{ str_replace('-','/', substr($int['updated_at'], 0 ,10)) }}</td>
                                    <td>{{ $int['company_name'] }}</td>
                                    <td>{{ $int['unit_name'] }}</td>
                                    <td>@if ($int['interview_type'] == '1'){{ $int['job_name'] }}@elseif ($int['interview_type'] == '2'){{ $int['event_name'] }}@else @endif</td>
                                    <td>@if ($int['interview_type'] == '1')正式応募@elseif ($int['interview_type'] == '2')イベント@elseカジュアル面談@endif</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>

                    </section><!-- /.secContents -->
                </div><!-- /.containerContents -->

				<div class="pager">
					{{ $ownerList->appends(request()->query())->links('pagination.admin') }}
				</div>
                
            </div><!-- /.mainContentsInner -->
        </div><!-- /.mainContents -->


@endsection
