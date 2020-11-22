<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_destination' );

/** MySQL database username */
define( 'DB_USER', 'wp2' );

/** MySQL database password */
define( 'DB_PASSWORD', '+5ffcF?lDaan|^@j' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '>%XkLlw,?P*+6>aW12uqY`N,}_tfcxzdp]a~`XP-a[1_*>U|yeHqq.N5yC+MnVmQ' );
define( 'SECURE_AUTH_KEY',  '2IvVM-lRT;Q*hh$W{NtmWu<5/pjc}r(vlr2dNvnN@gjszZwFh;%(j.eIP;.pVq~Q' );
define( 'LOGGED_IN_KEY',    '*A{0a5Qb b+&K0,gaP2j3iZ,MGrruO:;BsP[Tt;d(~JX3zSo8hus]r|A(hu<~TFA' );
define( 'NONCE_KEY',        'YE}zW0.gwJ+&^L<~qDpe&Z<I>9VLR_=]8?L5cgR*v?}3]?pI1kzUloyjHCKqwr{Y' );
define( 'AUTH_SALT',        'uct1^E@F-.~!z*KO8VAWCk!;?Xk$jHseq<?*Qf#rUm6`b3{(c3+SO1<Hmr7&-?]U' );
define( 'SECURE_AUTH_SALT', ' +B|6kkn@U7Z7@uX%8+)8]gFw!D!uzCOPed9i>Rc/7_)}a)i81N48sI;V/naSom:' );
define( 'LOGGED_IN_SALT',   '!|6LRXr&vf}.>-QMgJ{EFYCoh}5JiP@G3lFjp{VFq6GN.D0+P#cqAxGx.&WQw;:;' );
define( 'NONCE_SALT',       '<6Ukir>FVax.>S1Gj~)X|UCT+JpnOu652+_7X[Eukhm,A*WqpAngDE6^S)naMc>X' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
