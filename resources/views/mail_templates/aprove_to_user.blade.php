{!! e($user->name) !!}　様

審査が完了しました。

下記URLよりログインをお願い致します。
ログイン：{{ url('/') }}

仮パスワード
{!! $user->pw_raw !!}

※メールアドレスと仮パスワードでログインした後、パスワード変更をしてください。

ご自身のご経験がある企業様の評価をご投稿いただくことで
全てのクチコミの閲覧が可能となります。

どうぞよろしくお願いいたします。

@include('mail_templates.sign')