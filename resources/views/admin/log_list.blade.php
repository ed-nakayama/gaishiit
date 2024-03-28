@extends('layouts.admin.auth')
<head>
    <title>ログイン履歴 | {{ config('app.name', 'Laravel') }}</title>
</head>

@section('content')

            <div class="mainContentsInner-oneColumn">

                <div class="secTitle">
                    <div class="title-main">
                        <h2>ログイン履歴</h2>
                    </div><!-- /.mainTtl -->
                </div><!-- /.sec-title -->

                <div class="containerContents">
                    
                    <section class="secContents-mb">
                        <div class="secContentsInner">


                           {{Form::open(['url' => '/admin/log/list', 'method' => 'POST'])}}
  
                                <div class="formContainer mg-ajust-midashi">
                                    <div class="item-name">
                                        <p>ログイン日時</p>
                                    </div><!-- /.item-name -->

                                    <div class="select-item-container">
                                       <div class="select-item-column">
                                            <div class="item-input select-item-row">
                                                <div class="selectWrap full">
                                                    {{ Form::date('from_date', $param['from_date'], ['class' => 'form-control' , 'id'=>'from_date']) }}
                                                </div>
                                                 <span class="icon-ripple">　〜　</span>
                                                <div class="selectWrap full">
                                                    {{ Form::date('to_date', $param['to_date'], ['class' => 'form-control', 'id'=>'to_date']) }}
                                                </div>
                                            </div>
                                       </div>
                                    </div>
                                 </div>


                                <div class="formContainer mg-ajust-midashi">
                                    <div class="item-name">
                                        <p>{{  __('Log Mode') }}</p>
                                    </div><!-- /.item-name -->

                                    <div class="item-input">
                                        <div class="selectWrap harf">
                                            <select name="mode_type" class="form-control"  id="mode_type">
                                                <option value=""></option>
                                                <option value="U">候補者</option>
                                                <option value="C">企業</option>
                                                <option value="A">運営者</option>
                                            </select>
                                       </div>
                                   </div>
                                </div>

                                <div class="formContainer mg-ajust-midashi">
                                    <div class="item-name">
                                        {{ Form::label('',__('Log Id'), ['class' => 'col-form-label'])}}
                                    </div><!-- /.item-name -->

                                    <div class="item-input">
                                        {{ Form::text('login_id', $param['login_id'], ['class' => 'form-control', 'id'=>'login_id'] )}}
                                   </div>
                                </div>

                                <div class="formContainer mg-ajust-midashi">
                                    <div class="item-name">
                                        {{ Form::label('',__('Log Name'), ['class' => 'col-form-label']) }}
                                    </div><!-- /.item-name -->

                                    <div class="item-input">
                                        {{ Form::text('name', $param['name'], ['class' => 'form-control', 'id'=>'name']) }}
                                   </div>
                                </div>

                                <div class="formContainer mg-ajust-midashi">
                                    <div class="item-name">
                                        {{ Form::label('',__('Log Sub Id'), ['class' => 'col-form-label']) }}
                                    </div><!-- /.item-name -->

                                    <div class="item-input">
                                        {{ Form::text('sub_id', $param['sub_id'], ['class' => 'form-control', 'id'=>'sub_id']) }}
                                   </div>
                                </div>
                                
                                <div class="formContainer mg-ajust-midashi">
                                    <div class="item-name">
                                        {{ Form::label('',__('Log Sub Name'), ['class' => 'col-form-label']) }}
                                    </div><!-- /.item-name -->

                                    <div class="item-input">
                                        {{ Form::text('sub_name', $param['sub_name'], ['class' => 'form-control', 'id'=>'sub_name']) }}
                                   </div>
                                </div>

                                <div class="formContainer mg-ajust-midashi">
                                    <div class="item-name">
                                        {{ Form::label('',__('Log Ip'), ['class' => 'col-form-label']) }}
                                    </div><!-- /.item-name -->

                                    <div class="item-input">
                                        {{ Form::text('ip', $param['ip'], ['class' => 'form-control', 'id'=>'ip']) }}
                                   </div>
                                </div>

                                <div class="formContainer mg-ajust-midashi">
                                    <div class="item-name">
                                        {{ Form::label('',__('Log User Agent'), ['class' => 'col-form-label']) }}
                                    </div><!-- /.item-name -->

                                    <div class="item-input">
                                        {{ Form::text('user_agent', $param['user_agent'], ['class' => 'form-control', 'id'=>'user_agent']) }}
                                   </div>
                                </div>

    <div class="col-auto">
      {{ Form::submit( __('Search'), ['id'=>'btn_log_search', 'class'=>'btn btn-primary']) }}
    </div>
    <div class="col-auto">
      {{ Form::button( __('Clear'), ['id'=>'btn_log_clear','class'=>'btn btn-primary' ]) }}
    </div>
  </div>
	{{ html()->form()->close() }}

  <div class="col-auto text-center">
   {{ $logList->firstItem() }}- {{ $logList->lastItem() }}件 / {{ $logList->total() }}件中
  </div>

                          <table class="tbl-8th" id="infoTable">
                          <thead class="table-dark">
                             <tr>
                                <th nowrap>{{ __('Log Date') }}</th>
                                 <th nowrap>{{ __('Log Mode') }}</th>
                                 <th nowrap>{{ __('Log Id') }}</th>
                                 <th nowrap>{{ __('Log Name') }}</th>
                                 <th nowrap>{{ __('担当者ID') }}</th>
                                 <th nowrap>{{ __('担当者名') }}</th>
                                 <th nowrap>{{ __('Log Ip') }}</th>
                                 <th nowrap>{{ __('Log User Agent') }}</th>
                              </tr>
                          </thead>
                          @foreach ($logList as $log)
                               <tr>
                                   <td nowrap>{{ $log->created_at }}</td>
                                   <td>{{ $log->mode_type }}</td>
                                   <td nowrap align='right'>{{ $log->login_id }}</td>
                                   <td>{{ $log->name }}</td>
                                   <td>{{ $log->sub_id }}</td>
                                   <td>{{ $log->sub_name }}</td>
                                   <td nowrap>{{ $log->ip }}</td>
                                   <td>{{ $log->user_agent }}</td>
                               </tr>
                           @endforeach
                           </table>
                           <div class="pager">
                              {{ $logList->appends( $param)->links('pagination.admin') }}
                           </div>


						</div><!-- /.secContentsInner -->
					</section><!-- /.secContents-mb -->
				</div><!-- /.containerContents -->
            </div><!-- /.mainContentsInner-oneColumn -->



<script type="text/javascript">

/**************************************
* クリアボタン　イベント
***************************************/
$(function() {
	$('button#btn_log_clear').click(function(){

		var dt = new Date();
		dt.setMonth(dt.getMonth() - 1);

		document.getElementById("from_date" ).value = formatDate(dt);
		document.getElementById("to_date" ).value = '';
		document.getElementById("mode_type" ).value = '';
		document.getElementById("login_id" ).value = '';
		document.getElementById("name" ).value = '';
		document.getElementById("sub_id" ).value = '';
		document.getElementById("sub_name" ).value = '';
		document.getElementById("ip" ).value = '';
		document.getElementById("user_agent" ).value = '';



	});
});


function formatDate(dt) {
	var y = dt.getFullYear();
	var m = ('00' + (dt.getMonth()+1)).slice(-2);
	var d = ('00' + dt.getDate()).slice(-2);
	return (y + '-' + m + '-' + d);
}


</script> 

@endsection
