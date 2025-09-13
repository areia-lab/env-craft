<?php

return [
    'panel' => [
        // URL prefix for the admin panel routes
        'url_prefix' => 'admin/env-manager',

        // Callback settings (optional)
        'call_back_title' => 'Home', // Title for callback links
        'call_back_url'   => '/', // URL for callback actions

        // Title customization for the panel
        'title_prefix' => 'Env',   // Text to prepend to the panel title
        'title_suffix' => 'Craft', // Text to append to the panel title
    ],

    'middleware' => ['web'],

    'editable_keys' => [],

    'backup' => [
        'auto_save_when_update' => false, // Disable automatic backup on updates

        'dir_path' => 'backup/env-backups', // Directory path where backups will be stored
    ],

    'backup_disk' => 'local',
    'max_backups' => 50,
];
