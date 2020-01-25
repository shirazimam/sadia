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
define('DB_NAME', 'sadia_hyderabadi_jewellery');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'Stranger@4');

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
define('AUTH_KEY',         'N/xWZV_i-R@Ib5QZ#OuCZ>S)z@{pV7ue|]maw6p5_XKxn+bKEIb28RT,/Ce$%O%+');
define('SECURE_AUTH_KEY',  '7>,F[IN~j*l<l3?KIF-AoOmLn<qw8ue;9uPIr shp[OAE]|yQ(r_Dm)d_y;> N?V');
define('LOGGED_IN_KEY',    'yz7WLF[9>w@ HxO|>Cz&6}ZZnql!$XACc(eO2liCXo4^]qNr<*RCw^xv^JlGJYPI');
define('NONCE_KEY',        'GSq7=ZEt?GTzR_ZzosdD%i)a<v71!ID,*_2tXOz^dv)U3nj/[k&OBv#8A.!qQ(N5');
define('AUTH_SALT',        'uxRf}jJd3{Tzj|OPpuU XuRM*`C8X=K}>DF%TkAyeZAwt+q?nMk-fo92/ HB UzQ');
define('SECURE_AUTH_SALT', '&Ajx(KJ*x]w[4;!%aVcin9@ IEz(EeA$/~VFsv-O6o|vU1oc)]<<ba.9NNmoP=ur');
define('LOGGED_IN_SALT',   '<E5-;LYXi.6In~6Vk$Wv$ s,)^r=`t?U7#V+&J#N=Jvpqa:}K{[sP&L8{5A|lLp^');
define('NONCE_SALT',       'Hu+yjl=FBE6. lT]hd+B62X&68hs.lut>Li_}wj-ys6|*y-7g@O;--/-HX]4DcxU');

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

define('FS_METHOD', 'direct');

/* That's all, stop editing! Happy blogging. */

define( 'WP_POST_REVISIONS', 3 );

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

