@extends('layouts.admin.auth')

@section('content')

<head>
	<title>RPA 除外キーワード設定｜{{ config('app.name', 'Laravel') }}</title>
</head>

	<div class="mainContentsInner-oneColumn">

		<div class="mainTtl title-main">
			<h2>RPA 除外キーワード設定</h2>
		</div><!-- /.mainTtl -->
                
		<div class="containerContents">
                    
			<section class="secContents">
				<div class="secContentsInner">

					{{ html()->form('POST', '/admin/unavailable')->id('rpaform')->attribute('name', 'rpaform')->open() }}
					<table class="tbl-3th">
						<tr>
							<th style="width:100px;">更新日</th>
							<th>除外キーワード<font color="red">（半角のカンマでキーワードを区切って下さい。）</font></th>
							<th></th>
						</tr>
						<tr>
							<td>{{ str_replace(' ','/', str_replace('-','/', substr($rpa->updated_at, 0 ,16))) }}</td>
							<td>
								<textarea class="form-mt" name="keyword" cols="140" rows="10" placeholder="キーワード" >{!! $rpa->keyword !!}</textarea>
							</td>
							<td>
								<a href="javascript:rpaform.submit()" class="squareBtn btn-large" style="width:80px;">保存</a>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="text-align:center;">
								@if (session('update_success'))
									<div id="success2"  class="alert alert-success"  style="color:#0000ff;">
								 		{{session('update_success')}}
									</div>
								@endif
							</td>
						</tr>
					</table>
					{{ html()->form()->close() }}


				</div><!-- /.secContentsInner -->
			</section><!-- /.secContents -->
                   
		</div><!-- /.containerContents -->

	</div><!-- /.mainContentsInner -->
            

@endsection
