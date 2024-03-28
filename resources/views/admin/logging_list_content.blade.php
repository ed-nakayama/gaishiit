@section('content')
<div class="container">
{{Form::open(['url' => '/admin/log/list', 'method' => 'POST'])}}
<div class="form-row align-items-center">
<div class="col-auto">
        {{Form::label('', __('Log From Date'), ['class' => 'col-form-label'] )}}
        {{Form::date('from_date', $param['from_date'], ['class' => 'form-control'])}}
</div>
<div class="col-auto">
        {{Form::label('', __('Log To Date'), ['class' => 'col-form-label'] )}}
        {{Form::date('to_date', $param['to_date'], ['class' => 'form-control'])}}
</div>

<div class="col-auto">
   {{ Form::select('mode_type', $formCUserType, '', ['class' => 'form-control']) }}
</div>

<div class="col-md-2">
        {{Form::label('',__('Log Id'), ['class' => 'col-form-label'])}}
        {{Form::text('login_id', $param['login_id'], ['class' => 'form-control'])}}
</div>

<div class="col-md-2">
        {{Form::label('',__('Log Name'), ['class' => 'col-form-label'])}}
        {{Form::text('name', $param['name'], ['class' => 'form-control'])}}
</div>

<div class="form-row align-items-center">
<div class="col-md-2">
        {{Form::label('',__('Log Sub Id'), ['class' => 'col-form-label'])}}
        {{Form::text('sub_id', $param['sub_id'], ['class' => 'form-control'])}}
</div>
<div class="col-md-2">
        {{Form::label('',__('Log Sub Name'), ['class' => 'col-form-label'])}}
        {{Form::text('sub_name', $param['sub_name'], ['class' => 'form-control'])}}
</div>
<div class="col-auto">
  {{Form::label('',__('Log Ip'), ['class' => 'col-form-label'])}}
  {{Form::text('ip', $param['ip'], ['class' => 'form-control'])}}
</div>
<div class="col-auto">
        {{Form::label('',__('Log User Agent'), ['class' => 'col-form-label'])}}
        {{Form::text('user_agent', $param['user_agent'], ['class' => 'form-control'])}}
</div>
<div class="col-auto">
       {{Form::submit( __('Search'), ['class'=>'btn btn-primary'])}}
</div>
</div>
<br>

<div class="col-auto">
{{ $logList->appends( $param)->links('vendor.pagination.sample-pagination') }}
</div>
<div class="col-auto text-center">
{{ $logList->firstItem() }}- {{ $logList->lastItem() }}件 / {{ $logList->total() }}件中
</div>

<table  class="table table-striped">
 <thead class="table-dark">
    <tr>
        <th nowrap>{{ __('Log Date') }}</th>
        <th nowrap>{{ __('Log Mode') }}</th>
        <th nowrap>{{ __('Log Id') }}</th>
        <th nowrap>{{ __('Log Name') }}</th>
        <th nowrap>{{ __('Log Sub Id') }}</th>
        <th nowrap>{{ __('Log Sub Name') }}</th>
        <th nowrap>{{ __('Log Ip') }}</th>
        <th nowrap>{{ __('Log User Agent') }}</th>
    </tr>
 </thead>
    @foreach ($logList as $log)
    <tr>
        <td>{{ $log->created_at }}</td>
        <td>{{ $log->mode_type }}</td>
        <td>{{ $log->login_id }}</td>
        <td>{{ $log->name }}</td>
        <td>{{ $log->sub_id }}</td>
        <td>{{ $log->sub_name }}</td>
        <td>{{ $log->ip }}</td>
        <td>{{ $log->user_agent }}</td>
    </tr>
    @endforeach
</table>
</div>

@endsection
