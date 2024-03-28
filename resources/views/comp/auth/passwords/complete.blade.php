@extends('layouts.comp.app')

@section('content')

<head>
    <title>パスワードリセット | {{ config('app.name', 'Laravel') }}</title>
</head>

<div class="mainContentsInner">

	<div class="mainTtl title-main">
		<h2>パスワードリセット</h2>
    </div><!-- /.mainTtl -->

		<div class="containerContents">
                    
			<section class="secContents">
				<div class="secContentsInner">
		
					<div class="card-body">
                    	@if (session('status'))
                        	<div class="alert alert-success" role="alert">
                            	{{ session('status') }}
                        	</div>
                    	@endif
                	</div>
                
 				</div><!-- /.secContentsInner -->
			</section><!-- /.secContents -->
            
        </div>
    </div>
</div>

@endsection
