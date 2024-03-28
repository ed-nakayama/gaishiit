@extends('layouts.admin.auth')
<head>
    <title>企業登録 | {{ config('app.name', 'Laravel') }}</title>
</head>

@section('content')

	<div class="mainContentsInner-oneColumn">

		<div class="secTitle">
			<div class="title-main">
				<h2>企業登録 - 一覧</h2>
			 </div><!-- /.mainTtl -->
		</div><!-- /.sec-title -->

		<div class="containerContents">
                    
			<section class="secContents-mb">
				<div class="secContentsInner">

					@if (auth()->user()->comp_priv == '1')
						<div class="secBtnHead" style="justify-content: flex-start;">
							<div class="secBtnHead-btn">
								{{ Form::open(['url' => '/admin/comp/register', 'name' => 'regform' ,'method' => 'get' ]) }}
								{{ Form::hidden('comp_id', '') }}
								<ul class="item-btn">
									<li><a href="javascript:regform.submit()" class="squareBtn">新規作成</a></li>
								</ul><!-- /.item -->
								{{ Form::close() }}
							</div><!-- /.secBtnHead-btn -->

							<div class="secBtnHead-btn">
								{{ Form::open(['url' => '/admin/comp/list', 'name' => 'searchform','method' => 'get' ]) }}
								<ul class="item-btn" style="align-items: center;">
									<li style="text-align: right;">企業ID</li>
									<li style="width: 100px;margin-left: 0px;"><input type="text" name="comp_id" value="{{ $comp_id }}" placeholder=""></li>
									<li style="width: 50px;text-align: right;">企業名</li>
									<li style="width: 300px;margin-left: 0px;"><input type="text" name="comp_name" value="{{ $comp_name }}" placeholder=""></li>
									<li><a href="javascript:searchform.submit()" class="squareBtn">検索</a></li>
								</ul><!-- /.item -->
								{{ html()->form()->close() }}
							</div><!-- /.secBtnHead-btn -->
						</div>
					@endif

@if(!isset($compList[0]))
					<div>※データはありません。</div>
@else
 					<p style="text-align: center;">全{{ $compList->total() }}件中 {{  ($compList->currentPage() -1) * $compList->perPage() + 1}}-{{ (($compList->currentPage() -1) * $compList->perPage() + 1) + (count($compList) -1)  }}件</p>
					<div class="pager">
						{{ $compList->appends(request()->query())->links('pagination.admin') }}
					</div>
					<table class="tbl-comp-list mb-ajust">
						<tr>
							<th>企業ID</th><th>企業名</th><th>SFID</th><th>紹介</th><th>ARK<br>代理管理</th><th>当月<br>InMail数<br>正式応募</th><th><br>カジュアル</th><th>最大<br>InMail数<br>正式応募</th><th><br>カジュアル</th><th></th>
						</tr>
                               
						@foreach ($compList as $comp)
							<tr>
								{{ Form::open(['url' => '/admin/comp/edit', 'name' => 'editform' . $comp['id'] ,'method' => 'get' ]) }}
								{{ Form::hidden('comp_id', $comp['id']) }}
								{{ html()->form()->close() }}

								<td>{{ $comp->id }}</td>
								<td><a href="javascript:editform{{ $comp['id'] }}.submit()" style="text-decoration: underline;">{{ $comp->name }}</a></td>
								<td style="text-align: right;">{{ $comp->salesforce_id }}</td>
								<td>{{ mb_substr($comp->intro, 0 ,8) }}</td>
								{{ Form::open(['url' => '/admin/comp/inmail', 'name' => 'inmailform' . $comp->id]) }}
								{{ Form::hidden('comp_id', $comp->id) }}
								{{ Form::hidden('comp_name', $comp_name) }}
								{{ Form::hidden('page', $compList->currentPage()) }}
								<td  style="text-align: center;"><input type="checkbox" name="agency_flag" value="1"  onchange="inmailChange('{{ 'inmailsave' . $comp->id }}')"  @if ($comp->agency_flag == '1') checked @endif ></td>
								<td style="text-align: right;">{{ $comp->mon_inmail_formal }}</td>
								<td style="text-align: right;">{{ $comp->mon_inmail_casual }}</td>
								<td><input type="text" name="in_mail_formal"  value="{{ $comp->in_mail_formal }}" style="width: 70px;"  oninput="inmailChange('{{ 'inmailsave' . $comp->id }}')"></td>
								<td><input type="text" name="in_mail_casual"  value="{{ $comp->in_mail_casual }}" style="width: 70px;"  oninput="inmailChange('{{ 'inmailsave' . $comp->id }}')"></td>
								{{ Form::close() }}

								<td>
									<div class="btnContainer"  style="display: none;" id="{{ 'inmailsave' . $comp->id }}">
										<a href="javascript:inmailform{{ $comp->id }}.submit()" class="squareBtn btn-medium" style="width: 70px;">保存</a>
									</div><!-- /.btn-container -->
								</td>
							</tr>
						@endforeach

					</table>
					<div class="pager">
						{{ $compList->appends(request()->query())->links('pagination.admin') }}
					</div>
@endif

				</div><!-- /.secContentsInner -->
			</section><!-- /.secContents-mb -->
		</div><!-- /.containerContents -->
	</div><!-- /.mainContentsInner-oneColumn -->



<script type="text/javascript">

var pre_inmail ="";

function inmailChange(nm) {

	if (pre_inmail != nm) {
		document.getElementById(nm).style.display ="block";
		if (pre_inmail != "") {
			document.getElementById(pre_inmail).style.display ="none";
		}
		pre_inmail = nm;
	}
}

</script>

@endsection
