<?php

use Illuminate\Support\Str;

return[
    // ���������ѤΥ��å���̾�Ρ����å����ơ��֥�̾
    'session_cookie_admin' => env('SESSION_COOKIE_ADMIN', Str::slug(env('APP_NAME', 'laravel'), '_').'_session'),
    'ssession_table_admin' => env('SESSION_TABLE_ADMIN'),

    'session_cookie_comp' => env('SESSION_COOKIE_COMP', Str::slug(env('APP_NAME', 'laravel'), '_').'_session'),
    'ssession_table_comp' => env('SESSION_TABLE_COMP'),
];
