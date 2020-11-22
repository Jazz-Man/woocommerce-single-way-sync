<?php

function app_init(string $root_dir){

    /*
     * Expose global env() function from oscarotero/env
     */
    Env::init();

    /**
     * Use Dotenv to set required environment variables and load .env file in root.
     */
    $dotenv = Dotenv\Dotenv::createMutable($root_dir);

    if (file_exists($root_dir.'/.env')) {
        $dotenv->load();
        $dotenv->required(['WP_HOME', 'WP_SITEURL']);
        if (!env('DATABASE_URL')) {
            $dotenv->required(['DB_NAME', 'DB_USER', 'DB_PASSWORD']);
        }
    }

    /*
     * Set up our global environment constant and load its config first
     * Default: production
     */
    define('WP_ENV', env('WP_ENV') ?: 'production');

    /*
     * URLs
     */
    $current_server = parse_url(env('WP_HOME'));
    Config::define('WP_HOME', env('WP_HOME'));
    Config::define('WP_SITEURL', env('WP_SITEURL'));

    /*
     * DB settings
     */
    Config::define('DB_NAME', env('DB_NAME'));
    Config::define('DB_USER', env('DB_USER'));
    Config::define('DB_PASSWORD', env('DB_PASSWORD'));
    Config::define('DB_HOST', env('DB_HOST') ?: 'localhost');
    Config::define('DB_CHARSET', env('DB_CHARSET') ?: 'utf8mb4');
    Config::define('DB_COLLATE', '');
    $table_prefix = env('DB_PREFIX') ?: 'wp_';

    if (env('DATABASE_URL')) {
        $database_dsn = parse_url(env('DATABASE_URL'));
        $db_name = substr($database_dsn['path'], 1);
        $db_host = isset($database_dsn['port']) ? $database_dsn['host'].':'.$database_dsn['port'] : $database_dsn['host'];

        Config::define('DB_NAME', $db_name);
        Config::define('DB_USER', $database_dsn['user']);
        Config::define('DB_PASSWORD', $database_dsn['pass'] ?? null);
        Config::define('DB_HOST', $db_host);
    }

    /*
     * Authentication Unique Keys and Salts
     */
    Config::define('AUTH_KEY', env('AUTH_KEY'));
    Config::define('SECURE_AUTH_KEY', env('SECURE_AUTH_KEY'));
    Config::define('LOGGED_IN_KEY', env('LOGGED_IN_KEY'));
    Config::define('NONCE_KEY', env('NONCE_KEY'));
    Config::define('AUTH_SALT', env('AUTH_SALT'));
    Config::define('SECURE_AUTH_SALT', env('SECURE_AUTH_SALT'));
    Config::define('LOGGED_IN_SALT', env('LOGGED_IN_SALT'));
    Config::define('NONCE_SALT', env('NONCE_SALT'));

    Config::define('WP_REDIS_SERIALIZER', Redis::SERIALIZER_IGBINARY);
    Config::define('WP_REDIS_DISABLE_COMMENT', true);


    /*
     * Custom Settings
     */
    Config::define('AUTOMATIC_UPDATER_DISABLED', true);
    Config::define('WP_ALLOW_REPAIR', true);
    Config::define('WP_AUTO_UPDATE_CORE', false);
    Config::define('DISABLE_WP_CRON', env('DISABLE_WP_CRON') ?: false);
// Disable the plugin and theme file editor in the admin
    Config::define('DISALLOW_FILE_EDIT', true);
// Disable plugin and theme updates and installation from the admin
    Config::define('DISALLOW_FILE_MODS', true);
    Config::define('WP_MAIL_SMTP_URL', env('WP_MAIL_SMTP_URL') ?: false);


    /*
     * Cron Settings
     */

    Config::define('DISABLE_WP_CRON', true);

    /*
     * Debugging Settings
     */
    Config::define('WP_DEBUG_DISPLAY', true);
    Config::define('SCRIPT_DEBUG', true);
    Config::define('WP_POST_REVISIONS', 2);

// Fix WP_CLI error
    if (defined('WP_CLI') && WP_CLI) {
        $server_port = !empty($current_server['port']) ? $current_server['port'] : false;
        $is_https = !empty($current_server['scheme']) && 'https' === $current_server['scheme'] ? 'on' : false;

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

    $cookies_hash = md5($current_server['host']);
    $cookies_prefix = str_replace(['.', ':'], '_', $current_server['host']);

    /**
     * COOKIE
     */

    Config::define('COOKIEHASH', $cookies_hash);
    Config::define('TEST_COOKIE', "{$cookies_prefix}_testcookie_{$cookies_hash}");
    Config::define('AUTH_COOKIE', "{$cookies_prefix}_auth_{$cookies_hash}");
    Config::define('USER_COOKIE', "{$cookies_prefix}_user_{$cookies_hash}");
    Config::define('PASS_COOKIE', "{$cookies_prefix}_pass_{$cookies_hash}");
    Config::define('SECURE_AUTH_COOKIE', "{$cookies_prefix}_sec_{$cookies_hash}");
    Config::define('LOGGED_IN_COOKIE', "{$cookies_prefix}_logged_in_{$cookies_hash}");


    $env_config = __DIR__.'/environments/'.WP_ENV.'.php';

    if (file_exists($env_config)) {
        require_once $env_config;
    }

    /**
     * MULTISITE.
     */
    $domain = trim($current_server['host'], 'www.');

    Config::define('WP_ALLOW_MULTISITE', true);
    Config::define('MULTISITE', true);
    Config::define('SUBDOMAIN_INSTALL', false);
    Config::define('DOMAIN_CURRENT_SITE', $current_server['host']);
    Config::define('PATH_CURRENT_SITE', '/');
    Config::define('SITE_ID_CURRENT_SITE', 1);
    Config::define('BLOG_ID_CURRENT_SITE', 1);
    Config::define('COOKIE_DOMAIN', ".{$domain}");

    Config::apply();

    /*
     * Bootstrap WordPress
     */
    if (!defined('ABSPATH')) {
        define('ABSPATH', $root_dir);
    }
}