<?php

use Illuminate\Support\Str;

return[
    // 管理画面用のクッキー名称、セッションテーブル名
    'session_cookie_admin' => env('SESSION_COOKIE_ADMIN', Str::slug(env('APP_NAME', 'laravel'), '_').'_session'),
    'ssession_table_admin' => env('SESSION_TABLE_ADMIN'),

    'session_cookie_comp' => env('SESSION_COOKIE_COMP', Str::slug(env('APP_NAME', 'laravel'), '_').'_session'),
    'ssession_table_comp' => env('SESSION_TABLE_COMP'),

    'event_disp' => env('EVENT_DISP', 'false'),

    'OPENAI_API_KEY' => env('OPENAI_API_KEY', ''),

	'SALESFORCE_API_TOKEN' => env('SALESFORCE_API_TOKEN'),

];
