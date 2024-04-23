@extends('layouts.admin.auth')
<head>
    <title>監修者管理 | {{ config('app.name', 'Laravel') }}</title>
</head>

@section('content')

            <div class="mainContentsInner-oneColumn">

                <div class="secTitle">
                    <div class="title-main">
                        <h2>監修者管理</h2>
                    </div><!-- /.mainTtl -->
                </div><!-- /.sec-title -->


                <div class="containerContents">

                    <section class="secContents">
                        <div class="secContentsInner">

							@if (auth()->user()->cat_priv == '1')
                            <div class="secBtnHead">
                                <div class="secBtnHead-btn">
									{{ html()->form('POST', '/admin/supervisor/add')->id('addform')->attribute('name', 'addform')->open() }}
                                    <ul class="item-btn">
                                       <li></li>
                                       <li style="width: 300px;"><input type="text" name="name" value="{{ old('name') }}" placeholder="監修者名"></li>
                                        <li><a href="javascript:addform.submit()" class="squareBtn">追加</a></li>
                                    </ul><!-- /.item -->
                                    <ul class="item-btn">
                                       <li></li>
										@foreach ($errors->all() as $error)
                                             <li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $error }}</span></li>
										@endforeach
										@if (Session::has('message'))
    										<li><p>{{ session('message') }}</p></li>
										@endif
                                     </ul>
									{{ html()->form()->close() }}
                                </div><!-- /.secBtnHead-btn -->
                           </div>
							@endif

                             <table class="tbl-supervisor mb-ajust" id="memberTable">
                                <tr>
                                    <th>ID</th>
                                    <th>監修者</th>
                                    <th>写真</th>
                                    <th>コメント</th>
                                    <th>URL</th>
                                    <th>削除</th>
                                    <th></th>
                                </tr>
                                @foreach ($superList as $super)
                                <tr>
									{{ html()->form('POST', '/admin/supervisor/store')->attribute('name', "catform{$super->id}")->acceptsFiles()->open() }}
									{{ html()->hidden('id', $super->id ) }}
                                    <td>{{ $super->id  }}</td>
                                    <td><input type="text" name="name" value="{{ $super->name }}"></td>
									<td>
										@if ( isset($super->image) )
											<img src="{{ $super->image }}" class="css-class" alt="">
										@endif
										{{ html()->file('image') }}
										<p> ※jpg、png、500KB以内</p>
									</td>
                                    <td>
									<textarea class="form-mt" name="content" cols="30" rows="4" placeholder="監修者　コメント" >{{ old('content' ,$super->content) }}</textarea>
									</td>
                                    <td><input type="text" name="url" value="{{ $super->url }}"></td>
                                    <td><input type="checkbox" name="del_flag" value="1" @if ($super->del_flag == '1') checked @endif></td>
                                    <td>
                                        <div class="btnContainer"  id="{{ 'catsave' .$super->id  }}">
										@if (auth()->user()->cat_priv == '1')
                                        	<a href="javascript:catform{{ $super->id  }}.submit();" class="squareBtn btn-medium">保存</a>
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
