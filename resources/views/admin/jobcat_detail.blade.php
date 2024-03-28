@extends('layouts.admin.auth')
<head>
    <title>職種管理 | {{ config('app.name', 'Laravel') }}</title>
</head>

@section('content')

            <div class="mainContentsInner-oneColumn">

                <div class="secTitle">
                    <div class="title-main">
                        <h2>職種詳細管理</h2>
                    </div><!-- /.mainTtl -->
                </div><!-- /.sec-title -->


                <div class="containerContents">

                    <section class="secContents">
                        <div class="secContentsInner">

                            <div class="secBtnHead">

                                <div class="secBtnHead-btn">
                                    <ul class="item-btn">
										<li style="width: auto;" >
											職種カテゴリ
										</li>
                                       <li style="width: auto;" >
                                			<div class="selectWrap">
											{{ Form::open(['url' => '/admin/jobcatdetail', 'name' => 'changeform']) }}
											<select name="jobcat_id"  class="select-no" onchange="this.form.submit()">
												@foreach ($jobCat as $cat)
													<option value="{{ $cat->id }}" @if ( $job_cat->id == $cat->id)  selected @endif>{{ $cat->name }}</option>
												@endforeach
											</select>
											{{ Form::close() }}
											</div>
                                       </li>
										@if (auth()->user()->cat_priv == '1')
                                       <li style="width: 300px;">
	        	                           {{ Form::open(['url' => '/admin/jobcatdetail/add', 'name' => 'addform' , 'id' => 'addform']) }}
			                               {{ Form::hidden('jobcat_id', $job_cat->id) }}
                                       		<input type="text" name="solo_job_name" value="{{ old('solo_job_name') }}" placeholder="職種詳細名">
											{{ html()->form()->close() }}
                                       </li>
                                        <li><a href="javascript:addform.submit()" class="squareBtn">追加</a></li>
										@endif
                                    </ul><!-- /.item -->
									@if (auth()->user()->cat_priv == '1')
 									@error('solo_job_name')
										<div class="item-input" style="text-align: right;" >
											<span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span>
										</div><!-- /.item-input -->
									@enderror
									@endif
                                </div><!-- /.secBtnHead-btn -->
                           </div>

                             <table class="tbl-cat-5th mb-ajust" id="memberTable">
                                <tr>
                                    <th>ID</th>
                                    <th>表示順</th>
                                    <th>職種名</th>
                                    <th>削除</th>
                                    <th></th>
                                </tr>
                                @foreach ($catList as $cat)
                                <tr>
                               {{ Form::open(['url' => '/admin/jobcatdetail/store', 'name' => 'catform' . $cat['id'] ]) }}
	                           {{ Form::hidden('jobcat_id', $job_cat->id) }}
                               {{ Form::hidden('jobcatdetail_id', $cat->id) }}
                                    <td>{{ $cat['id'] }}</td>
                                    <td><input type="text" name="order_num" value="{{ $cat['order_num'] }}" oninput="catChange('{{ 'catsave' . $cat['id'] }}')"></td>
                                    <td><input type="text" name="name" value="{{ $cat['name'] }}" oninput="catChange('{{ 'catsave' . $cat['id'] }}')"></td>
                                    <td><input type="checkbox" name="del_flag" value="1" @if ($cat->del_flag == '1') checked @endif   onchange="catChange('{{ 'catsave' . $cat['id'] }}')"></td>
                                    <td>
                                        <div class="btnContainer"  style="display: none;" id="{{ 'catsave' . $cat['id'] }}">
										@if (auth()->user()->cat_priv == '1')
                                        	<a href="javascript:catform{{ $cat['id'] }}.submit();" class="squareBtn btn-medium">保存</a>
                                        @endif
                                        </div><!-- /.btn-container -->
                                    </td>
								{{ html()->form()->close() }}
                                </tr>
                                @endforeach
                            </table>
 

                        </div><!-- /.secContentsInner -->
                    </section><!-- /.secContents -->
                    
                </div><!-- /.containerContents -->

            </div><!-- /.mainContentsInner -->

<script>

var pre_cat ="";


function catChange(nm) {

	if (pre_cat != nm) {
		document.getElementById(nm).style.display ="block";
		if (pre_cat  != "") {
			document.getElementById(pre_cat).style.display ="none";
		}
		pre_cat = nm;
	}
}

</script>


<style>
#memberTable { cursor: pointer; }
.evenRow { background-color: #F5F5F5; }
.focusRow { background-color: #ffffcc; }

#alreadyTable { cursor: pointer; }
.evenRow { background-color: #F5F5F5; }
.focusRow { background-color: #ffffcc; }

</style>

@endsection
