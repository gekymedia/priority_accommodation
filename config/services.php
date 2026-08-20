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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | CUG Admissions API
    |--------------------------------------------------------------------------
    |
    | Configuration for the Catholic University of Ghana (CUG) Admissions
    | system API. Used for looking up student information by phone number.
    |
    */
    'cug_admissions' => [
        'url' => env('CUG_ADMISSIONS_API_URL', 'https://admissions.cug.edu.gh/api'),
        'key' => env('CUG_ADMISSIONS_API_KEY', ''),
    ],

    'priority_bank' => [
        'api_url' => env('PRIORITY_BANK_API_URL', 'https://prioritybank.gekymedia.com'),
        'api_token' => env('PRIORITY_BANK_API_TOKEN'),
        'timeout' => env('PRIORITY_BANK_API_TIMEOUT', 10),
        'max_retries' => env('PRIORITY_BANK_API_MAX_RETRIES', 3),
    ],

    'gekychat' => [
        // Platform API is on api subdomain, not chat subdomain
        // Routes are at: api.gekychat.test/platform/oauth/token
        // So base_url should be just the domain (no /api prefix)
        'base_url' => env('GEKYCHAT_API_URL', env('APP_ENV') === 'local' ? 'http://api.gekychat.test' : 'https://api.gekychat.com'),
        'client_id' => env('GEKYCHAT_CLIENT_ID'),
        'client_secret' => env('GEKYCHAT_CLIENT_SECRET'),
        'system_bot_user_id' => (int) env('GEKYCHAT_SYSTEM_BOT_USER_ID', 0),
    ],


    // Google Drive backups (per-project OAuth — same Google account, separate refresh token)
    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URI'),
        'refresh_token' => env('GOOGLE_REFRESH_TOKEN'),
        'scopes' => [
            'https://www.googleapis.com/auth/drive.file',
        ],
        'access_token_cache_key' => env('GOOGLE_ACCESS_TOKEN_CACHE_KEY', 'google_access_token_accommodations'),
        'drive_backup_folder' => env('GOOGLE_DRIVE_BACKUP_FOLDER', 'Priority Accommodations Backups'),
        'backups_redirect_route' => env('GOOGLE_BACKUPS_REDIRECT_ROUTE', 'admin.backups.index'),
        'backups_dashboard_route' => env('GOOGLE_BACKUPS_DASHBOARD_ROUTE', 'admin.dashboard'),
        'google_auth_route' => env('GOOGLE_AUTH_ROUTE', 'admin.google-auth.start'),
        'backups_status_route' => env('GOOGLE_BACKUPS_STATUS_ROUTE', 'admin.backups.status'),
        'backup_paths' => ['public'],
    ],

];
