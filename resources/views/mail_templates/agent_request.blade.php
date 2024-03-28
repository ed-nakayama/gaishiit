ご担当者様

{!! $user->name !!} 様から下記の件に関して転職エージェントに相談の申請がありました。

企業名：{{ $comp->name }}
@if (!empty($job->name))
部署名：{{ $unit->name }}
@endif
@if (!empty($job->name))
求人：{{ $job->name }}
@endif

候補者情報：
{{ url('/') }}/admin/user/detail?user_id={{ $user->id }}

内容をご確認の上、対応をお願いします。

@include('mail_templates.sign')