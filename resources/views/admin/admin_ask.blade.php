@extends('layouts.admin.auth')

@section('content')

<head>
	<title>お問合せ一覧｜{{ config('app.name', 'Laravel') }}</title>
</head>

	<div class="mainContentsInner-oneColumn">

		<div class="mainTtl title-main">
			<h2>お問合せ一覧</h2>
		</div><!-- /.mainTtl -->
                
		<div class="containerContents">
                    
			<section class="secContents">
				<div class="secContentsInner">

					<table class="tbl-askList mb-ajust">
						<tr>
							<th>作成日</th>
							<th>候補者名</th>
							<th>未登録ユーザ</th>
							<th>メールアドレス</th>
							<th>お問合せ内容</th>
						</tr>
						@foreach ($askList as $ask)
						<tr>
							<td>{{ str_replace(' ','/', str_replace('-','/', substr($ask->created_at, 0 ,16))) }}</td>
							<td>{{ $ask->candidate_name }}</td>
							<td>{{ $ask->user_name }}</td>
							<td>{{ $ask->email }}</td>
							<td>{!! nl2br(e($ask->content)) !!}</td>
						</tr>
						@endforeach
					</table>
 
				</div><!-- /.secContentsInner -->
			</section><!-- /.secContents -->
                   
		</div><!-- /.containerContents -->

		<div class="pager">
			{{ $askList->appends(request()->query())->links('pagination.admin') }}
		</div>

	</div><!-- /.mainContentsInner -->
            

@endsection
