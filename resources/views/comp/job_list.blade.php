@extends('layouts.comp.auth')

@section('content')

<head>
	<title>ジョブ管理｜{{ config('app.name', 'Laravel') }}</title>
</head>

{{--@include('comp.member_activity')--}}

	<div class="mainContentsInner-oneColumn">

		<div class="mainTtl title-main">
			<h2>ジョブ管理</h2>
		</div><!-- /.mainTtl -->
                
		<div class="containerContents">
                    
			<section class="secContents">
				<div class="secContentsInner">

					<div class="secBtnHead">
						<div class="secBtnHead-btn">
							{{ Form::open(['url' => '/comp/job/list', 'name' => 'listform' , 'id' => 'listform', 'method'=>'GET']) }}
							<ul class="item-btn" style="align-items: center;">
								<li><a href="/comp/job/register" class="squareBtn">新規作成</a></li>
								<li style="text-align: right;">フリーワード</li>
								<li style="width: 200px;margin-left: 0px;"><input type="text" name="freeword" value="{{ $freeword }}" placeholder=""></li>
								<li><a href="javascript:listform.submit()" class="squareBtn">検索</a></li>
								<li style="white-space:nowrap;">　　　<input type="checkbox" name="only_me" value="1" @if ($only_me == '1') checked @endif  onchange="this.form.submit()"><label>自分の担当のみ表示</label></li>
							</ul><!-- /.item -->
							{{ Form::close() }}
						</div><!-- /.secBtnHead-btn -->
					</div><!-- /.sec-btn -->

					<p style="text-align: center;">全{{ $jobList->total() }}件中 {{  ($jobList->currentPage() -1) * $jobList->perPage() + 1}}-{{ (($jobList->currentPage() -1) * $jobList->perPage() + 1) + (count($jobList) -1)  }}件</p>
					<table class="tbl-joblist mb-ajust" id="jobTable">
						<tr>
							<th>作成日</th>
							<th>公開</th>
							<th>ジョブID</th>
							<th>Job Title</th>
							<th>職種</th>
							<th>部門</th>
{{--						<th>作成者</th>--}}
							<th>担当者</th>
							<th></th>
						</tr>
						@foreach ($jobList as $job)
							<tr>
								<td>{{ $job->created_at->format('Y/m/d/H:i') }}</td>
								<td>@if ($job->open_flag == '1')公開@endif</td>
								<td>{{ $job->job_code }}</td>
 								<td>{{ $job->name }}</td>
								<td>{{ $job->getJobCategoryName() }}</td>
								<td>{{ $job->unit_name }}</td>
{{--							<td>{{ $job->member_name }}</td>--}}
								<td>{{ $job->person_name }}</td>
								<td>
									<div class="btnContainer">
										{{ Form::open(['url' => '/comp/job/edit', 'name' => 'editform' . $job->id ,'method'=>'GET' ]) }}
										{{ Form::hidden('job_id', $job->id) }}
										@if (strpos($job->person ,Auth::user()->id) !== false)
											<a href="javascript:editform{{ $job->id }}.submit()" class="squareBtn btn-large">編集</a>
										@else
											<a href="javascript:editform{{ $job->id }}.submit()" class="squareGrayBtn btn-large">参照</a>
										@endif
										{{ Form::close() }}
									</div><!-- /.btn-container -->
								</td>
							</tr>
						@endforeach
					</table>
 
					<div class="pager">
						{{ $jobList->appends(request()->query())->links('pagination.comp') }}
					</div>
				</div><!-- /.secContentsInner -->
			</section><!-- /.secContents -->
                   
		</div><!-- /.containerContents -->
	</div><!-- /.mainContentsInner -->


<script type="text/javascript">


$(document).ready(function(){
  $("#jobTable tr:even").not(':first').addClass("evenRow");
  $("#jobTable tr").not(':first').hover(
    function(){
        $(this).addClass("focusRow");
    },function(){
        $(this).removeClass("focusRow");
 });
});

</script>

<style>
#jobTable { cursor: pointer; }
.evenRow { background-color: #F5F5F5; }
.focusRow { background-color: #ffffcc; }
</style>

@endsection
