{!! $user->name !!}様

{!! $user->name !!}様が設定された条件に一致したジョブが見つかりました。
下記URLよりご確認ください。

@foreach ($jobList as $job)
{{ url('/') }}/company/{{ $job->getCompanyId() }}/{{ $job->id }}
@endforeach

@include('mail_templates.sign')