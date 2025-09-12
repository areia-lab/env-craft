<?php

return [
    'panel' => [
        'title_prefix' => 'Env',
        'title_suffix' => 'Craft',
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
