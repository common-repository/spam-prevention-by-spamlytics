<?php
/**
Plugin Name: Comment Spam Filter by SpamLytics
Plugin URI: https://spamlytics.com
Description: Block comment spam in WordPress with this plugin. Verify comments automatically with the SpamLytics API, and prevent comment spam. No captcha.
Version: 1.0.2
Author: petervw
Author URI: https://spamlytics.com
Text Domain: spam-prevention-by-spamlytics
Domain Path: /languages
*/

namespace SpamLytics;

require( 'vendor/autoload.php' );

use SpamLytics\Core\Init as Init;

define( 'SPAMLYTICS_VERSION', '1.0.2' );
define( 'SPAMLYTICS_ROOT_PATH', __FILE__ );
define( 'SPAMLYTICS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'SPAMLYTICS_PLUGIN_ROOT', dirname( plugin_basename( __FILE__ ) ) );

$spamlytics = new Init();
$spamlytics->init_plugin();