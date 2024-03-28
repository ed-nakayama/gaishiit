{!! $user->name !!} 　様

{!! $interview->company_name !!}
@if ($interview->interview_type == '0')
@if (!empty($interview->unit_name))　{!! $interview->unit_name !!}
@endif
@if (!empty($interview->job_name))　{!! $interview->job_name !!}
@endif
カジュアル面談にお申込みを頂きましたが
残念ながら企業様が求める募集ラインに達していませんでした。
今後さらにキャリアを重ねられた際、またはご記載頂いた内容に追加がございましたら、
@elseif ($interview->interview_type == '1')
@if (!empty($interview->unit_name))　{!! $interview->unit_name !!}
@endif
@if (!empty($interview->job_name))　{!! $interview->job_name !!}
@endif
の正式応募にお申込みを頂きましたが
残念ながら企業様が求める募集ラインに達していませんでした。
今後さらにキャリアを重ねられた際、またはご記載頂いた内容に追加がございましたら、
@elseif ($interview->interview_type == '2') 
@if (!empty($interview->event_name))　{!! $interview->event_name !!}
@endif
イベントにお申込みを頂きましたが
残念ながら定員オーバーまたは企業様のご都合によりお断りさせていただきました。
@endif
お手数ではございますが、またの機会に改めてご申請頂けますようお願い申し上げます。
どうぞよろしくお願いいたします。

@include('mail_templates.sign')