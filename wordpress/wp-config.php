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
define('AUTH_KEY',         'W7#YKd7=-4x_jeCtY6e8X:c%HKaC0?H8XcJH))$/:Z(TW;?Am|Gw_s^6kbUZArce');
define('SECURE_AUTH_KEY',  'X=Og@dF^}YFEk K1;(_9Q>##KAU{2|(Sb@D;`;pALnE{_1Dy)zAMi/j`>5verbY=');
define('LOGGED_IN_KEY',    '&.n:&sQ zE}e2W/YbbGhs8hkWlJ]BC?dM$HH&)GE[sS;;DNTQ)ih!mWNfOR(Dq`7');
define('NONCE_KEY',        '.@*)D54SZ!;$U;HmmK^?S KP-vIys|1nP<V6(VeI-dNTNeM9$~}9O^/z2^ 0k<=/');
define('AUTH_SALT',        '1VU%~)#M sR[ (~e=6n~?|W?M-^kxLv#j>A S-nYu3blj{`:@`Hun#Og;*IF;k1A');
define('SECURE_AUTH_SALT', 'yt2CNYu<_1+*6u?Ylm<5g>BB)<2OB.,V`b#)2Y2)WTY Gf!_P&6 1j1]#Az.UwWH');
define('LOGGED_IN_SALT',   'a)Af!H1Gx6$6?xs?iN#lZO@k//BEiz)B>q*46[,3Zi0M32)CZDCf7[#uOgzUDX#6');
define('NONCE_SALT',       '9ezg>e]]C-,!wuR0I]aCUx|R37IL?^YsL9.Z8#c6S2K6xQ3?V(Ww^{-,M.-iuZQ6');

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
