<?php
/**
 * Plugin Name:       AI Readable
 * Plugin URI:        https://github.com/ahmetalkan/ai-readable
 * Description:       Adds llms.txt, llms-full.txt and ai.txt endpoints plus AI crawler signals (robots.txt rules, alternate link tags and JSON-LD) so AI engines and AI crawlers can discover and understand your site. Developed by Urfa Yöresi.
 * Version:           1.0.0
 * Requires at least: 5.9
 * Requires PHP:      7.4
 * Author:            AI Readable
 * Author URI:        https://sleepclinicdirectory.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ai-readable
 *
 * @package AIReadable
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AIR_VERSION', '1.0.0' );
define( 'AIR_PLUGIN_FILE', __FILE__ );
define( 'AIR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'AIR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once AIR_PLUGIN_DIR . 'includes/class-air-llms.php';
require_once AIR_PLUGIN_DIR . 'includes/class-air-crawlers.php';
require_once AIR_PLUGIN_DIR . 'includes/class-air-footer.php';
require_once AIR_PLUGIN_DIR . 'includes/class-air-settings.php';

/**
 * Boot the plugin.
 */
function air_boot() {
	AIR_LLMS::instance();
	AIR_Crawlers::instance();
	AIR_Footer::instance();
	AIR_Settings::instance();
}
add_action( 'plugins_loaded', 'air_boot' );

/**
 * Register activation hook: flush rewrite rules so /llms.txt routes resolve.
 */
function air_activate() {
	AIR_LLMS::instance()->register_routes();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'air_activate' );

/**
 * Register deactivation hook.
 */
function air_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'air_deactivate' );
