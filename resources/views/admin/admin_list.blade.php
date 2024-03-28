@extends('layouts.admin.auth')
<head>
	<title>メンバー管理 | {{ config('app.name', 'Laravel') }}</title>
</head>


@section('content')

	<div class="mainContentsInner-oneColumn">

		<div class="secTitle">
			<div class="title-main">
				<h2>メンバー管理</h2>
			</div><!-- /.mainTtl -->
		</div><!-- /.sec-title -->

		<div class="containerContents">
			<section class="secContents-mb">
				<div class="secContentsInner">

					@if (auth()->user()->account_priv == '1')
						<div class="secBtnHead">
							<div class="secBtnHead-btn">
								{{ html()->form('GET', '/admin/admin/register')->attribute('name', "regform")->open() }}
								{{ html()->hidden('admin_id') }}
								<ul class="item-btn">
									<li><a href="javascript:regform.submit()" class="squareBtn">新規作成</a></li>
								</ul><!-- /.item -->
								{{ html()->form()->close() }}
							</div><!-- /.secBtnHead-btn -->
  						</div>
					@endif

					<table class="tbl-memberList">
						<tr>
							<th>氏名</th>
							<th>メールアドレス</th>
							<th>候補者承認</th>
							<th>企業管理</th>
							<th>請求情報</th>
							<th>設定変更</th>
							<th>お知らせ<br>管理</th>
							<th>ピックアップ<br>管理</th>
							<th>メンバー管理</th>
							<th>クチコミ承認</th>
						</tr>
                               
						@foreach ($adminList as $admin)
							<tr  onclick="javascript:editform{{ $admin->id }}.submit()"  style="cursor: pointer;">
								<td>{{ $admin->name }}</td>
								<td>{{ $admin->email }}</td>
								<td align="center">@if ($admin->aprove_priv == '1')●@endif</td>
								<td align="center">@if ($admin->comp_priv == '1')●@endif</td>
								<td align="center">@if ($admin->bill_priv == '1')●@endif</td>
 								<td align="center">@if ($admin->cat_priv == '1')●@endif</td>
								<td align="center">@if ($admin->info_priv == '1')●@endif</td>
								<td align="center">@if ($admin->pickup_priv == '1')●@endif</td>
								<td align="center">@if ($admin->account_priv == '1')●@endif</td>
								<td align="center">@if ($admin->eval_priv == '1')●@endif</td>
								{{ html()->form('GET', '/admin/admin/edit')->attribute('name', "editform{$admin->id}")->open() }}
								{{ html()->hidden('admin_id', $admin->id) }}
								{{ html()->form()->close() }}
							</tr>
						@endforeach

					</table>

				</div><!-- /.secContentsInner -->
			</section><!-- /.secContents-mb -->
		</div><!-- /.containerContents -->
	</div><!-- /.mainContentsInner-oneColumn -->

@endsection
