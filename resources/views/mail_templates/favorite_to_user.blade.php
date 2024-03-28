{!! $user->name !!}様

{!! $user->name !!}様がお気に入りに設定されたジョブが更新されました。
下記URLよりご確認ください。

@foreach ($jobList as $job)
{{ url('/') }}/company/{{ $job->getCompanyId() }}/{{ $job->id }}
@endforeach

@include('mail_templates.sign')