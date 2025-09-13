<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Configuration
    |--------------------------------------------------------------------------
    | Settings for the Env Manager panel, including URL, title, and callbacks.
    */

    'panel' => [
        // URL prefix for admin panel routes (use something obscure in production)
        'url_prefix' => env('ENV_MANAGER_PREFIX', 'admin/env-manager'),

        // Callback settings (optional)
        'call_back_title' => 'Dashboard',   // Friendly title for return links
        'call_back_url'   => env('ENV_MANAGER_CALLBACK_URL', '/'),

        // Panel title customization
        'title_prefix' => 'Env',
        'title_suffix' => 'Craft',
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    | Secure your panel with proper middleware.
    | Add 'auth' in production to prevent unauthorized access.
    */

    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Editable Keys
    |--------------------------------------------------------------------------
    | Only list environment keys that should be editable in the panel.
    | Leave empty to prevent accidental edits to sensitive keys.
    */

    'editable_keys' => [
        // Examples: 'APP_NAME', 'APP_ENV', 'MAIL_MAILER'
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup Settings
    |--------------------------------------------------------------------------
    | Configure automatic backup and storage for .env changes.
    */

    'backup' => [
        'auto_save_when_update' => true, // Enable automatic backup on updates in production

        // Storage disk (make sure 'local' is secure or use 's3' for cloud)
        'disk' => env('ENV_MANAGER_BACKUP_DISK', 'local'),

        // Directory path for backups
        'dir_path' => env('ENV_MANAGER_BACKUP_DIR', 'backup/env-backups'),

        // Maximum number of backup files to keep
        'max_limit' => 50,
    ],

];
