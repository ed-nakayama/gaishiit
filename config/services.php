<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

	'salesforce' => [
    	'client_id' => env('SF_CLIENT_ID'),
    	'username' => env('SF_USERNAME'),
    	'login_url' => env('SF_LOGIN_URL'),
    	'private_key_path' => env('SF_PRIVATE_KEY_PATH'),
    	'api_version' => env('SF_API_VERSION', 'v67.0'),
    	'notify_email' => env('SALESFORCE_SYNC_NOTIFY_EMAIL'),
	],

	'company_api' => [
    	'key' => env('COMPANY_API_KEY'),
	],

];
