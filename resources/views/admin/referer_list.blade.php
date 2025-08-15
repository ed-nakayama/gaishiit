@extends('layouts.admin.auth')
<head>
    <title>候補者 Referer | {{ config('app.name', 'Laravel') }}</title>
</head>

@section('content')

            <div class="mainContentsInner-oneColumn">

                <div class="secTitle">
                    <div class="title-main">
                        <h2>候補者 Referer</h2>
                    </div><!-- /.mainTtl -->
                </div><!-- /.sec-title -->
               
                
                <div class="containerContents">
                    
                    <section class="secContents-mb">
                        <div class="secContentsInner">

						{{ html()->form('GET', '/admin/candidate/referer')->id('searchform')->attribute('name', 'searchform')->open() }}
						<div class="secBtnHead">
							<div class="secBtnHead-btn">
								<ul class="item-btn" style="align-items: center;">
									<li style="width: 400px;margin-left: 0px;">Referer
										{{ html()->text('referer', $referer) }}
									</li>
									<li style="margin-top: 20px;"><a href="javascript:searchform.submit()" class="squareBtn">検索</a></li>
								</ul><!-- /.item -->
							</div><!-- /.secBtnHead-btn -->
						</div>

						{{ html()->form()->close() }}


@if(!isset($userList[0]))
  <div>※データはありません。</div>
@else
							<p style="text-align: center;">全{{ $userList->total() }}件中 {{  ($userList->currentPage() -1) * $userList->perPage() + 1}}-{{ (($userList->currentPage() -1) * $userList->perPage() + 1) + (count($userList) -1)  }}件</p>
                           <table class="tbl-referer">
                                <tr>
                                    <th>登録日</th>
                                    <th>氏名</th>
                                    <th>状況</th>
                                    <th>年齢</th>
                                    <th>勤務先</th>
                                    <th>Referer</th>
                                </tr>
                               
                              @foreach ($userList as $int)
                                <tr>
                                    <td>{{ str_replace('-','/', substr($int->created_at, 0 ,10)) }}</td>
                                    <td>
                                        {{ Form::open(['url' => '/admin/user/detail', 'name' => 'userform' . $int->id ]) }}
                                        {{ Form::hidden('user_id', $int->id) }}
										{{ Form::hidden('parent_id', '2') }}
                                        <a href="javascript:userform{{ $int->id }}.submit()" style="text-decoration: underline;">{{ $int->name }}</a>
                                        {{ Form::close() }}
                                    </td>
									<td>@if ( $int->aprove_flag == '1')<font color="blue">承認済</font>@elseif ( $int->aprove_flag == '2')<font color="red">リジェクト</font>@endif</td>
                                    <td>{{ $int->age }}</td>
                                    <td>{{ $int->company }}</td>
                                    <td>{{ $int->referer }}</td>
                                </tr>
                              @endforeach

                            </table>
                            <div class="pager">
                               {{ $userList->links('pagination.admin') }}
                            </div>
@endif

						</div><!-- /.secContentsInner -->
					</section><!-- /.secContents-mb -->
				</div><!-- /.containerContents -->
            </div><!-- /.mainContentsInner-oneColumn -->

@endsection
