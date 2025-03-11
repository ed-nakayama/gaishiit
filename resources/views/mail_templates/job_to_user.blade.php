{!! $user->name !!}様

{!! $user->name !!}様が設定された条件に一致したジョブが見つかりました。
詳細は下記URLよりご確認ください。

@foreach ($jobList as $job)
{{ url('/') }}/company/{{ $job->getCompanyId() }}/{{ $job->id }}
@endforeach

配信が不要な方はこちら
{{ url('/') }}/setting
からメール受信設定の変更をお願いします。

@include('mail_templates.sign')