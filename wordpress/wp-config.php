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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'chase_db');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'server.123');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'owIgyST!TLp =MAi7mrTQhp0^ 0UyZ/y>?F/x!|K[/<ZR8{6BK*EmbLn=4atro[c');
define('SECURE_AUTH_KEY',  '%DXct`J@tWFHT*T{gNv?oN]C.6JC|:l<<kc&_F5&m=^r*E>9}34>8fr5ZTQ~#1Vs');
define('LOGGED_IN_KEY',    'LIO!wc{lIS!8g^8]8ev4Z|]MS@f{B@{UZO.O81lG3_vG(BM,6t<]d@=nMtv4#%m&');
define('NONCE_KEY',        ' w!sA?|gCLUfsbhk2a`5>c[fRO!`4`1rd3e(#NyjN[ZbeQh[K38u`RVvBl@1b{q#');
define('AUTH_SALT',        '*l@&1bX0[6uw1Lv>#!6.NC+}r|A+CS]z7]y_2`%+KMOM*WSh/q+%SYV,jFCI)rj@');
define('SECURE_AUTH_SALT', '/5EjH`g8/{3;NC$S>;?=QOVB0J8T]>/fTcf=|SIH_(N-_=#5_CL?v8S`Uz3Kz2;R');
define('LOGGED_IN_SALT',   'Xsi0TM,#eFQtCapFmXQ&Zwe^>0 iucQm!tI5-C/5u~muViA%]!0P9IxMaZBmj*`+');
define('NONCE_SALT',       '-ka[.@_d>!^PB{WnL#K<8yT9:5]YXJp7*19]:mRL:WZ)1f#Hy^FqgIly|j@d&e27');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
