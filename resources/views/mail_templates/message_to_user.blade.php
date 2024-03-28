{!! $user->name !!}　様

{!! $comp->name !!}から新着メッセージがあります。
下記URLよりご確認ください。

{{ url('/') }}/interview/flow?interview_id={{ $interview->id }}

@include('mail_templates.sign')