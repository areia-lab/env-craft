# EnvCraft

A Laravel package to edit `.env` values via a Tailwind-based UI or Artisan commands with backup/restore functionality.

## Installation

Add the repo to composer.json or install via path, then register provider if needed.

## Usage

- Visit `/env-manager` for UI

- Use artisan commands:
  `php artisan env:backup` // Create a backup,
  `php artisan env:backup -d|--details` // Create a backup with backup directory details,

  `php artisan env:backup-list` // list all backups

`php artisan env:show`, // Show all Key value pair list of env
`php artisan env:show --key=APP_KEY |-k APP_KEY`, // Show specify item key value pair list of env

`php artisan env:set`, // prompts for key and value
`php artisan env:set --key="TEST_KEY" --value="Abc123"`, // Set key and value pre-define

`env:restore`,
