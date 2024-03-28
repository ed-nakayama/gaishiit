@extends('layouts.admin.auth')
<head>
    <title>職種管理 | {{ config('app.name', 'Laravel') }}</title>
</head>

@section('content')

            <div class="mainContentsInner-oneColumn">

                <div class="secTitle">
                    <div class="title-main">
                        <h2>職種管理</h2>
                    </div><!-- /.mainTtl -->
                </div><!-- /.sec-title -->


                <div class="containerContents">

                    <section class="secContents">
                        <div class="secContentsInner">

							@if (auth()->user()->cat_priv == '1')
                            <div class="secBtnHead">
                                <div class="secBtnHead-btn">
                                   {{ Form::open(['url' => '/admin/jobcat/add', 'name' => 'addform' , 'id' => 'addform']) }}
                                    <ul class="item-btn">
                                       <li></li>
                                       <li style="width: 300px;"><input type="text" name="solo_job_name" value="{{ old('solo_job_name') }}" placeholder="職種名"></li>
                                        <li><a href="javascript:addform.submit()" class="squareBtn">追加</a></li>
                                    </ul><!-- /.item -->
                                    <ul class="item-btn">
                                       <li></li>
                                         @error('solo_job_name')
                                             <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
                                         @enderror
										@if (Session::has('message'))
    										<li><p>{{ session('message') }}</p></li>
										@endif
                                     </ul>
									{{ html()->form()->close() }}
                                </div><!-- /.secBtnHead-btn -->
                           </div>
							@endif

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
                               {{ Form::open(['url' => '/admin/jobcat/store', 'name' => 'catform' . $cat['id'] ]) }}
                               {{ Form::hidden('cat_id', $cat['id']) }}
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
