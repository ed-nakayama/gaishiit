{!! $comp->name !!}
{!! $member->name !!}様

@if ($member->admin_flag == '1')
外資IT.comサービスの[管理者]として登録が完了しました。
下記URLよりログインし、企業登録、利用者登録をお願い致します。
@else
外資IT.comサービスの[担当者]として登録が完了しました。
下記URLよりログインし、ご利用ください。
@endif

ログイン：{{ url('/comp/login') }}
@if ($member->admin_flag == '1')
企業登録：{{ url('/comp/edit') }}
利用者登録：{{ url('/comp/member') }}
@endif
仮パスワード：　{!! $member->pw_raw !!}
※メールアドレスと仮パスワードでログインした後、パスワード変更をしてください。

どうぞよろしくお願い致します。

@include('mail_templates.sign')