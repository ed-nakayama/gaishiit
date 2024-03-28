{!! $admin->name !!}様

新規登録されました。

仮パスワードを発行しました。
{!! $admin->pw_raw !!}

ログイン：{{ url('/admin/login') }}

メールアドレスと仮パスワードでログインした後、パスワード変更をしてください。
今後ともよろしくお願いいたします。

@include('mail_templates.sign')