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
define( 'DB_NAME', 'wp_source' );

/** MySQL database username */
define( 'DB_USER', 'wp1' );

/** MySQL database password */
define( 'DB_PASSWORD', '0LoXr2tCtS@Xl+r%' );

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
define( 'AUTH_KEY',         '<L&yq6B;Ad6)&k&seoI`kb~h{),LFtVEQ<ySm}a[6!Spi@|m_7tww@*ZthKv;#WC' );
define( 'SECURE_AUTH_KEY',  '[cxEuuy$Yce_n E8=2:GKOk}:}v*X`]FSdQEN]96#p1o1O> sU~;hQ16&t<B/(O1' );
define( 'LOGGED_IN_KEY',    '~G7bQ#JDBF,hw ;9yJB?r%44SHrWc4@Rf&],TyBv#G}gVHf*2,7d_rgcYHE]8WG5' );
define( 'NONCE_KEY',        'PC4^Tmx8m4iG^<O2xV/~z=;Q0Q$h,?J/%)#c!VevBo:vPs:oHp~&c)y:kfW+5[@>' );
define( 'AUTH_SALT',        '5MMmi$<DM8r+b9u~FQR`n.X!-<h55i1nup*+Qb4o;{s? `o;v6uePR9*IVEdl4HD' );
define( 'SECURE_AUTH_SALT', '<Gp?`yZ;eKLLj0GCo37EsBHB+1/ {c038UTX$&V>99890Qf[4zZ?eY?um.j-Y#W/' );
define( 'LOGGED_IN_SALT',   '!H*zkt~wnBn<B?nbqy(}q1$Eg,06x|,s]46P)014!~e(WSdTEfQIPu<RH0jx3gL?' );
define( 'NONCE_SALT',       'H7%=bT~R_Jgi-0l`^?LCz~<%iq~Q9tSqO]t*zFrING:x#vOir0Q42 w)B03(sy!f' );

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
