<?php

use Roots\WPConfig\Config;

function app_init(string $root_dir)
{
    /**
     * Use Dotenv to set required environment variables and load .env file in root.
     */
    $dotenv = Dotenv\Dotenv::createImmutable($root_dir);

    $dotenv->load();
    $dotenv->required([
        'WP_HOME',
        'WP_SITEURL',
        'DB_NAME',
        'DB_USER',
        'DB_PASSWORD',
        'AUTH_KEY',
        'SECURE_AUTH_KEY',
        'LOGGED_IN_KEY',
        'NONCE_KEY',
        'AUTH_SALT',
        'SECURE_AUTH_SALT',
        'LOGGED_IN_SALT',
        'NONCE_SALT',
    ]);

    /*
     * Set up our global environment constant and load its config first
     * Default: production
     */
    \define('WP_ENV', $_ENV['WP_ENV'] ?: 'production');

    /*
     * URLs
     */
    $current_server = \parse_url($_ENV['WP_HOME']);
    Config::define('WP_HOME', $_ENV['WP_HOME']);
    Config::define('WP_SITEURL', $_ENV['WP_SITEURL']);

    /*
     * DB settings
     */
    Config::define('DB_NAME', $_ENV['DB_NAME']);
    Config::define('DB_USER', $_ENV['DB_USER']);
    Config::define('DB_PASSWORD', $_ENV['DB_PASSWORD']);
    Config::define('DB_HOST', ! empty($_ENV['DB_HOST']) ? $_ENV['DB_HOST'] : 'localhost');
    Config::define('DB_CHARSET', ! empty($_ENV['DB_CHARSET']) ? $_ENV['DB_CHARSET'] : 'utf8mb4');
    Config::define('DB_COLLATE', '');

    if (! empty($_ENV['DATABASE_URL'])) {
        $database_dsn = \parse_url($_ENV['DATABASE_URL']);
        $db_name = \substr($database_dsn['path'], 1);
        $db_host = isset($database_dsn['port']) ? $database_dsn['host'].':'.$database_dsn['port'] : $database_dsn['host'];

        Config::define('DB_NAME', $db_name);
        Config::define('DB_USER', $database_dsn['user']);
        Config::define('DB_PASSWORD', $database_dsn['pass'] ?? null);
        Config::define('DB_HOST', $db_host);
    }

    /*
     * Authentication Unique Keys and Salts
     */
    Config::define('AUTH_KEY', $_ENV['AUTH_KEY']);
    Config::define('SECURE_AUTH_KEY', $_ENV['SECURE_AUTH_KEY']);
    Config::define('LOGGED_IN_KEY', $_ENV['LOGGED_IN_KEY']);
    Config::define('NONCE_KEY', $_ENV['NONCE_KEY']);
    Config::define('AUTH_SALT', $_ENV['AUTH_SALT']);
    Config::define('SECURE_AUTH_SALT', $_ENV['SECURE_AUTH_SALT']);
    Config::define('LOGGED_IN_SALT', $_ENV['LOGGED_IN_SALT']);
    Config::define('NONCE_SALT', $_ENV['NONCE_SALT']);

    /*
     * Custom Settings
     */
    Config::define('AUTOMATIC_UPDATER_DISABLED', true);
    Config::define('WP_ALLOW_REPAIR', true);
    Config::define('WP_AUTO_UPDATE_CORE', false);
    Config::define('DISABLE_WP_CRON', ! empty($_ENV['DISABLE_WP_CRON']) ? $_ENV['DISABLE_WP_CRON'] : false);
    Config::define('DISALLOW_FILE_EDIT', true);
    Config::define('DISALLOW_FILE_MODS', true);

    /*
     * Debugging Settings
     */
    Config::define('WP_DEBUG_DISPLAY', true);
    Config::define('SCRIPT_DEBUG', true);
    Config::define('WP_POST_REVISIONS', 2);

    if (\defined('WP_CLI') && WP_CLI) {
        $server_port = ! empty($current_server['port']) ? $current_server['port'] : false;
        $is_https = ! empty($current_server['scheme']) && 'https' === $current_server['scheme'] ? 'on' : false;

        $_SERVER['HTTP_HOST'] = $current_server['host'];
        $_SERVER['SERVER_NAME'] = $current_server['host'];
        $_SERVER['HTTPS'] = $is_https;
        $_SERVER['SERVER_PORT'] = $server_port;
    }

    /*
     * Allow WordPress to detect HTTPS when used behind a reverse proxy or a load balancer
     * See https://codex.wordpress.org/Function_Reference/is_ssl#Notes
     */
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && 'https' === $_SERVER['HTTP_X_FORWARDED_PROTO']) {
        $_SERVER['HTTPS'] = 'on';
    }

    /**
     * HTTP_HOST and SERVER_NAME Security Issues.
     *
     * @see https://expressionengine.com/blog/http-host-and-server-name-security-issues
     */
    $server_host = (string) $_SERVER['HTTP_HOST'];

    $_SERVER['HTTP_HOST'] = $current_server['host'] !== $server_host ? $current_server['host'] : $server_host;

    $env_config = __DIR__.'/environments/'.WP_ENV.'.php';

    if (\is_file($env_config)) {
        require_once $env_config;
    }

    Config::apply();

    /*
     * Bootstrap WordPress
     */
    if (! \defined('ABSPATH')) {
        \define('ABSPATH', $root_dir);
    }
}
