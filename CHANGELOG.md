# Changelog

All notable changes to this project will be documented in this file.

## [1.0.0] - 2025-09-13

### Initial Release

EnvCraft v1.0.0 is the first official release. This package provides a simple UI and Artisan manager for Laravel `.env` files, including backup and restore functionality.

#### Features

- **Web UI:** Access and manage your `.env` variables through a clean Laravel-admin interface at `/env-manager`.
- **Artisan Commands:** Manage `.env` files via Artisan commands for updates, backups, and restores.
- **Backup & Restore:** Automatic backup of `.env` changes with configurable storage path and retention limits.
- **Editable Keys:** Restrict which environment variables can be edited from the panel.
- **Customizable Panel:** Configure panel URL, title, and callback links.
- **Middleware Protection:** Secure the panel with Laravel middleware (e.g., `auth`) for production-ready security.
- **Responsive UI:** Modern light and dark dashboard themes for better user experience.
- **Composer-Friendly:** Fully compatible with Laravel 9.x, 10.x, 11.x, and 12.x.
- **PSR-4 Autoloading:** Clean namespace support for smooth integration in Laravel projects.

#### Configuration

- **Panel URL Prefix:** Customize via `config/env-craft.php` or `.env` variable.
- **Backup Settings:** Configure disk, directory path, and max backup limit.
- **Middleware:** Default `['web', 'auth']` for production security.

#### Benefits

- Safely manage environment variables without manually editing `.env`.
- Avoid accidental loss of configuration with automatic backups.
- Easy installation and integration with Laravel projects.

#### Installation

```bash
composer require areia-lab/env-craft
```
