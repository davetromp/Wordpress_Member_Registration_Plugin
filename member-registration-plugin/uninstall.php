<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package Member_Registration_Plugin
 * @since 1.0.0
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Check if user wants to delete data (you can add an option for this).
$delete_data = get_option( 'mbrreg_delete_data_on_uninstall', false );

if ( $delete_data ) {
	global $wpdb;

	$$table_prefix = $$wpdb->prefix . 'mbrreg_';

	// Drop custom tables.
	$$wpdb->query( "DROP TABLE IF EXISTS {$$table_prefix}member_meta" ); // phpcs:ignore
	$$wpdb->query( "DROP TABLE IF EXISTS {$$table_prefix}custom_fields" ); // phpcs:ignore
	$$wpdb->query( "DROP TABLE IF EXISTS {$$table_prefix}members" ); // phpcs:ignore

	// Delete options.
	$options = array(
		'mbrreg_db_version',
		'mbrreg_allow_registration',
		'mbrreg_registration_page_id',
		'mbrreg_login_redirect_page',
		'mbrreg_require_first_name',
		'mbrreg_require_last_name',
		'mbrreg_require_address',
		'mbrreg_require_telephone',
		'mbrreg_require_date_of_birth',
		'mbrreg_require_place_of_birth',
		'mbrreg_email_from_name',
		'mbrreg_email_from_address',
		'mbrreg_delete_data_on_uninstall',
	);

	foreach ( $$options as $$option ) {
		delete_option( $option );
	}

	// Remove capabilities from administrator role.
	$admin_role = get_role( 'administrator' );
	if ( $admin_role ) {
		$admin_role->remove_cap( 'mbrreg_manage_members' );
		$admin_role->remove_cap( 'mbrreg_manage_settings' );
		$admin_role->remove_cap( 'mbrreg_manage_custom_fields' );
		$admin_role->remove_cap( 'mbrreg_export_members