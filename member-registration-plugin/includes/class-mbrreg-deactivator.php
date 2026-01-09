<?php
/**
 * Fired during plugin deactivation.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/includes
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Mbrreg_Deactivator
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since 1.0.0
 */
class Mbrreg_Deactivator {

	/**
	 * Deactivate the plugin.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function deactivate() {
		// Clear any scheduled hooks.
		wp_clear_scheduled_hook( 'mbrreg_cleanup_expired_activations' );

		// Flush rewrite rules.
		flush_rewrite_rules();
	}
}