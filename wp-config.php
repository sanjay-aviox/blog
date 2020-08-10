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
define( 'DB_NAME', 'test_yogesh' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'aviox123' );

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
define( 'AUTH_KEY',         ';m~{9wv2m[k/Dz-F%<3e3Xe@+Zb[-An[Y%]3hY-rv|n#(l9*<2aIpGpE({PP=fgH' );
define( 'SECURE_AUTH_KEY',  ':oo%VnowJH-TPYSuG`G-/2^`8BJ;YVa#HRD* XyFFmbw/{DqO.ue1QTTX#IJ+8hi' );
define( 'LOGGED_IN_KEY',    ';<V$wkGeyANfk&)va2bZU6hfz1Qgv16D?q$:o$)%`#[xX&^9KPUwIbkBjSm4cG]W' );
define( 'NONCE_KEY',        'Jfj~Hb{tY#e%RM&=9bY|?ck/,Lr;6ZkoM<O7#ke&0GR8qK]QAJG3<TA(WCrDS+~2' );
define( 'AUTH_SALT',        '$%Ok_TY*y2k05JZNKd+E?8C!WNVtzQ5Z8`e.a{P5=%Za<vE^fct3 y(,rU{I@1#E' );
define( 'SECURE_AUTH_SALT', '!MA<6$Q&Lqrg,N(T?Dwa*ELLh/ub}HQw;_lu%Fy.gFIj)x>N?vI({fHjyflH>$?V' );
define( 'LOGGED_IN_SALT',   'WgWnXXDlUZrOVMpfoFv]Is2>o>9|OR):h1< Ej,^Gt5OUUYN)u-zj=60@//E>h`9' );
define( 'NONCE_SALT',       'jy)rW.,xI#Cz%]Sy.!^_h7*DGKo) k gk:-U)&Ri#WR+#@t2vC]KWn_W7IGr@Pt@' );

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
