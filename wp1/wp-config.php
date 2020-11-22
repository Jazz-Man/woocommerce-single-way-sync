<?php

require_once dirname(__DIR__).'/vendor/autoload.php';
require_once dirname(__DIR__).'/config/application.php';
app_init(__DIR__);

$table_prefix = 'wp_';
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
