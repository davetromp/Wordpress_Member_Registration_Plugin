<?php
/**
 * Plugin Name: Member Registration Plugin
 * Plugin URI: https://example.com/member-registration-plugin
 * Description: A comprehensive member registration and management system for sports clubs.
 * Version: 1.0.0
 * Author: Sports Club Developer
 * Author URI: https://example.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: member-registration-plugin
 * Domain Path: /languages
 *
 * @package Member_Registration_Plugin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'MBRREG_VERSION', '1.0.0' );

/**
 * Plugin base path.
 */
define( 'MBRREG_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin base URL.
 */
define( 'MBRREG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Plugin basename.
 */
define( 'MBRREG_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Database table prefix for this plugin.
 */
define( 'MBRREG_TABLE_PREFIX', 'mbrreg_' );

/**
 * The code that runs during plugin activation.
 */
function mbrreg_activate() {
	require_once MBRREG_PLUGIN_PATH . 'includes/class-mbrreg-activator.php';
	Mbrreg_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function mbrreg_deactivate() {
	require_once MBRREG_PLUGIN_PATH . 'includes/class-mbrreg-deactivator.php';
	Mbrreg_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'mbrreg_activate' );
register_deactivation_hook( __FILE__, 'mbrreg_deactivate' );

/**
 * Load required files.
 */
require_once MBRREG_PLUGIN_PATH . 'includes/class-mbrreg-database.php';
require_once MBRREG_PLUGIN_PATH . 'includes/class-mbrreg-member.php';
require_once MBRREG_PLUGIN_PATH . 'includes/class-mbrreg-custom-fields.php';
require_once MBRREG_PLUGIN_PATH . 'includes/class-mbrreg-email.php';
require_once MBRREG_PLUGIN_PATH . 'includes/class-mbrreg-ajax.php';
require_once MBRREG_PLUGIN_PATH . 'includes/class-mbrreg-shortcodes.php';
require_once MBRREG_PLUGIN_PATH . 'includes/class-mbrreg-admin.php';
require_once MBRREG_PLUGIN_PATH . 'includes/class-mbrreg-public.php';

/**
 * Initialize the plugin.
 *
 * @since 1.0.0
 * @return void
 */
function mbrreg_init() {
	// Load text domain for translations.
	load_plugin_textdomain(
		'member-registration-plugin',
		false,
		dirname( MBRREG_PLUGIN_BASENAME ) . '/languages/'
	);

	// Initialize components.
	$database      = new Mbrreg_Database();
	$custom_fields = new Mbrreg_Custom_Fields();
	$email         = new Mbrreg_Email();
	$member        = new Mbrreg_Member( $database, $custom_fields, $email );
	$ajax          = new Mbrreg_Ajax( $member, $custom_fields );
	$shortcodes    = new Mbrreg_Shortcodes( $member, $custom_fields );
	$admin         = new Mbrreg_Admin( $member, $custom_fields );
	$public        = new Mbrreg_Public( $member );

	// Initialize hooks.
	$ajax->init();
	$shortcodes->init();
	$admin->init();
	$public->init();
}
add_action( 'plugins_loaded', 'mbrreg_init' );

/**
 * Get global database instance.
 *
 * @since 1.0.0
 * @return Mbrreg_Database
 */
function mbrreg_get_database() {
	static $database = null;
	if ( null === $database ) {
		$database = new Mbrreg_Database();
	}
	return $database;
}