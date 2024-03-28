{!! $user->name !!} 　様

{!! $interview->company_name !!}
@if ($interview->interview_type == '0')
@if (!empty($interview->unit_name))　{!! $interview->unit_name !!}
@endif
@if (!empty($interview->job_name))　{!! $interview->job_name !!}
@endif
カジュアル面談へのお申込みが承認されました。
@elseif ($interview->interview_type == '1')
@if (!empty($interview->unit_name))　{!! $interview->unit_name !!}
@endif
@if (!empty($interview->job_name))　{!! $interview->job_name !!}
@endif
正式応募へのお申込みが承認されました。
@elseif ($interview->interview_type == '2') 
@if (!empty($interview->event_name))　{!! $interview->event_name !!}
@endif
イベントへのお申込みが承認されました。
@endif

後ほど企業様からのご連絡がございますので少々お待ちいただけますよう
よろしくお願いいたします。

@include('mail_templates.sign')