<?php

return [
    'panel' => [
        'title_prefix' => 'Env',
        'title_suffix' => 'Craft',
    ],

    'middleware' => ['web'],

    'editable_keys' => [],
    'backup_disk' => 'local',
    'backup_path' => 'env-manager-backups',
    'max_backups' => 50,
];
