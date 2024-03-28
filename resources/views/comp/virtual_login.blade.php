@extends('layouts.comp.app')
<head>
    <title>企業代理ログイン | {{ config('app.name', 'Laravel') }}</title>
</head>

@section('content')

			<div class="mainContentsInner">

				<div class="mainTtl title-main">
                        <h2>企業代理ログイン</h2>
                </div><!-- /.mainTtl -->

                <div class="containerContents">

                    <section class="secContents">
                        <div class="secContentsInner">

							<form method="POST" name ="form1" action="{{ route('comp.login') }}">
								@csrf
								<input id="email" type="hidden" name="email" value="">
								<input id="password" type="hidden"  name="password" value="">
                        	{{ Form::close() }}

							<table id="userTable">
								@foreach ($memberList as $mem)
									<tr data-email="{{ $mem->id }}" data-password="{{ $mem->pw_raw }}">
										<td>
												[ {{ $mem->company_name }} ]　
										</td>
										<td>
												{{ $mem->name }}
										</td>
									</tr>
								@endforeach
							</table>

						</div><!-- /.secContentsInner -->
					</section><!-- /.secContents-mb -->
				</div><!-- /.containerContents -->
            </div><!-- /.mainContentsInner-oneColumn -->



	
<script>

$(document).ready(function(){
    $('table tr').click(function(){

		document.getElementById('email').value = $(this).attr('data-email');
		document.getElementById('password').value = $(this).attr('data-password');
		document.querySelector('form').submit();

		return false;
	});
});


</script>

<style>
table td {
	background: #eee;
}
table tr:nth-child(odd) td {
	background: #fff;
}

#userTable tr:hover td {
  background-color: #ffffcc;
}

</style>

@endsection


