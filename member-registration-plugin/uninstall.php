<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best/worst case: administrator, editor, author, etc.
 *
 * @package Member_Registration_Plugin
 * @since 1.0.0
 */

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

/**
 * Main uninstall function.
 *
 * Removes all plugin data from the database.
 *
 * @since 1.0.0
 * @return void
 */
function mbrreg_uninstall()
{
	global $wpdb;

	// Define table prefix (same as in main plugin file).
	$table_prefix = $wpdb->prefix . 'mbrreg_';

	// =============================================
	// 1. DROP CUSTOM DATABASE TABLES
	// =============================================

// Drop member meta table.
	$table_member_meta = $table_prefix . 'member_meta';
	$wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS `{$table_member_meta}`" ) );

	// Drop custom fields table.
	$table_custom_fields = $table_prefix . 'custom_fields';
	$wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS `{$table_custom_fields}`" ) );

	// Drop members table.
	$table_members = $table_prefix . 'members';
	$wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS `{$table_members}`" ) );

	// =============================================
	// 2. DELETE ALL PLUGIN OPTIONS
	// =============================================

	$options_to_delete = array(
		// Registration settings.
		'mbrreg_allow_registration',
		'mbrreg_allow_multiple_members',
		'mbrreg_registration_page_id',
		'mbrreg_login_redirect_page_id',

		// Display settings.
		'mbrreg_date_format',

		// Required fields settings (current).
		'mbrreg_require_first_name',
		'mbrreg_require_last_name',

		// Legacy required fields settings (pre-1.2.0).
		'mbrreg_require_address',
		'mbrreg_require_telephone',
		'mbrreg_require_date_of_birth',
		'mbrreg_require_place_of_birth',

		// Email settings.
		'mbrreg_email_from_name',
		'mbrreg_email_from_address',

		// Database version.
		'mbrreg_db_version',

		// Any other plugin options.
		'mbrreg_plugin_version',
	);

	foreach ($options_to_delete as $option) {
		delete_option($option);
	}

	// =============================================
	// 3. DELETE TRANSIENTS
	// =============================================

	// Delete known transients.
	delete_transient('mbrreg_activation_redirect');
	delete_transient('mbrreg_activation_error');
	delete_transient('mbrreg_activation_success');

	// Delete any transients with our prefix (for dynamically created ones).
	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM `{$wpdb->options}` WHERE option_name LIKE %s OR option_name LIKE %s",
			'_transient_mbrreg_%',
			'_transient_timeout_mbrreg_%'
		)
	);

	// =============================================
	// 4. REMOVE CUSTOM CAPABILITIES FROM ROLES
	// =============================================

	$capabilities_to_remove = array(
		'mbrreg_manage_members',
		'mbrreg_manage_settings',
		'mbrreg_manage_custom_fields',
		'mbrreg_export_members',
		'mbrreg_import_members',
	);

	// Get all roles.
	global $wp_roles;

	if (!isset($wp_roles)) {
		$wp_roles = new WP_Roles(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}

	foreach ($wp_roles->role_names as $role_name => $display_name) {
		$role = get_role($role_name);
		if ($role) {
			foreach ($capabilities_to_remove as $cap) {
				$role->remove_cap($cap);
			}
		}
	}

	// Also remove capabilities from individual users who might have them.
	$users_with_caps = get_users(
		array(
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => $wpdb->prefix . 'capabilities',
					'value' => 'mbrreg_',
					'compare' => 'LIKE',
				),
			),
		)
	);

	foreach ($users_with_caps as $user) {
		foreach ($capabilities_to_remove as $cap) {
			$user->remove_cap($cap);
		}
	}

	// =============================================
	// 5. CLEAN UP USER META (if any)
	// =============================================

	// Delete any user meta created by the plugin.
	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM `{$wpdb->usermeta}` WHERE meta_key LIKE %s",
			'mbrreg_%'
		)
	);

	// =============================================
	// 6. OPTIONAL: DELETE WORDPRESS USERS
	// =============================================

	// By default, we do NOT delete WordPress users that were created
	// through the plugin, as they may have other content on the site.
	// 
	// If you want to enable this feature, uncomment the code below
	// and add a setting to allow admins to choose this option.
	//
	// WARNING: This will permanently delete user accounts!
	//
	/*
	// Get all user IDs that were registered through the plugin.
	// This would require storing user IDs in a separate option or
	// identifying them through some other means.

$member_user_ids = $wpdb->get_col(
		$wpdb->prepare( "SELECT DISTINCT user_id FROM `{$table_members}`" )
	);

	if ( ! empty( $member_user_ids ) ) {
		require_once ABSPATH . 'wp-admin/includes/user.php';

		foreach ( $member_user_ids as $user_id ) {
			// Skip administrators.
			if ( user_can( $user_id, 'manage_options' ) ) {
				continue;
			}

			// Delete the user (reassign their content to admin).
			wp_delete_user( $user_id, 1 );
		}
	}
	*/

	// =============================================
	// 7. CLEAR ANY CACHED DATA
	// =============================================

	// Clear object cache.
	wp_cache_flush();

	// Explicitly clear our custom keys if flush is restricted.
	wp_cache_delete('mbrreg_table_members_columns', 'member_registration_plugin');

	// =============================================
	// 8. CLEAN UP SCHEDULED EVENTS (if any)
	// =============================================

	// If the plugin registered any cron events, clear them.
	$cron_hooks = array(
		'mbrreg_daily_cleanup',
		'mbrreg_send_reminders',
		// Add any other cron hooks your plugin might use.
	);

	foreach ($cron_hooks as $hook) {
		$timestamp = wp_next_scheduled($hook);
		if ($timestamp) {
			wp_unschedule_event($timestamp, $hook);
		}
		// Clear all events for this hook.
		wp_clear_scheduled_hook($hook);
	}
}

// Run the uninstall function.
mbrreg_uninstall();