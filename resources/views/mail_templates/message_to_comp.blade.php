{!! $comp->name !!}（ご担当者）様

{!! $user->name !!} 様から新着メッセージがあります。
下記URLよりご確認ください。

{{ url('/') }}/comp/interview/flow?interview_id={{ $interview->id }}

@include('mail_templates.sign')