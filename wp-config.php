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
if( file_exists( dirname(__FILE__) . '/local.php' ) ) {
	// Local database settings
	define('DB_NAME', 'fictionaluniversity');
	define('DB_USER', 'root');
	define('DB_PASSWORD', 'root');
	define('DB_HOST', 'localhost');
} else {
	// Live database settings
	define('DB_NAME', 'yonik332_universitydata');
	define('DB_USER', 'yonik332_wp586');
	define('DB_PASSWORD', 'universitydata332');
	define('DB_HOST', 'localhost');
}

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
define('AUTH_KEY',         'rM{wy#9OJ}mE.!Ot I`6R;#sY3TeBQz.>^=p.@<iu2i/i$Q/tlE#Ol)Mk<5pb2!w');
define('SECURE_AUTH_KEY',  '@zA;o.XopjD6-:pL&d7dYQ8%1>`wlWiM?hSaB=8tN2_v<Nso+kLxth;Wi^quUY T');
define('LOGGED_IN_KEY',    'ucG!&}[XC2#_wzc-`;4:G_UkYXI}H]wwD2e3znE?Ehgp+,:J$sIv 8QH|qx9fSE6');
define('NONCE_KEY',        '/t!Bm`mZm}03F{7O^|~*$P1L(S5~(hUlZ#cp2^liHV7L^EuZ?@T5{2,c5Ry7M yH');
define('AUTH_SALT',        'N()u2S$I]oS05HQ^FL2/wL?#ww7=98F(U*|5MPxf1EzDZ.L5*NX* 8d$*SXHnyne');
define('SECURE_AUTH_SALT', '|ezx+u ywO1/9;Zm0p(3`?nm3@nAXpN/2l)1Qw! %XDNLlnx3]Cz6T)$B1QuqHwD');
define('LOGGED_IN_SALT',   '=[I]sa:*FACW}p#ZZ!S)=7i2V,>]n^W>Or|>+4J4p&~t]?v,A?7]>ehW-;4_E8JQ');
define('NONCE_SALT',       'hT:e~&Bv:U+P,xvw%Z;m?`]tbWzOvYy4d@Ty2jJWf x|#nF$ReiC|V->Zt9I|K[e');

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
