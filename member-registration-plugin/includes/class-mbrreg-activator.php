<?php
/**
 * Fired during plugin activation.
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
 * Class Mbrreg_Activator
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since 1.0.0
 */
class Mbrreg_Activator {

	/**
	 * Activate the plugin.
	 *
	 * Creates necessary database tables and sets default options.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function activate() {
		self::create_tables();
		self::set_default_options();
		self::create_plugin_roles();

		// Set flag for activation redirect.
		set_transient( 'mbrreg_activation_redirect', true, 30 );

		// Flush rewrite rules.
		flush_rewrite_rules();
	}

	/**
	 * Create database tables.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private static function create_tables() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_prefix    = $wpdb->prefix . MBRREG_TABLE_PREFIX;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// Members table.
		$table_members = $table_prefix . 'members';
		$sql_members   = "CREATE TABLE {$table_members} (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			user_id bigint(20) UNSIGNED NOT NULL,
			first_name varchar(100) DEFAULT '' NOT NULL,
			last_name varchar(100) DEFAULT '' NOT NULL,
			address text DEFAULT '' NOT NULL,
			telephone varchar(50) DEFAULT '' NOT NULL,
			date_of_birth date DEFAULT NULL,
			place_of_birth varchar(100) DEFAULT '' NOT NULL,
			status varchar(20) DEFAULT 'pending' NOT NULL,
			is_admin tinyint(1) DEFAULT 0 NOT NULL,
			activation_key varchar(100) DEFAULT '' NOT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
			PRIMARY KEY (id),
			UNIQUE KEY user_id (user_id),
			KEY status (status),
			KEY is_admin (is_admin)
		) {$charset_collate};";

		dbDelta( $sql_members );

		// Custom fields table.
		$table_custom_fields = $table_prefix . 'custom_fields';
		$sql_custom_fields   = "CREATE TABLE {$table_custom_fields} (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			field_name varchar(100) NOT NULL,
			field_label varchar(255) NOT NULL,
			field_type varchar(50) DEFAULT 'text' NOT NULL,
			field_options text DEFAULT '' NOT NULL,
			is_required tinyint(1) DEFAULT 0 NOT NULL,
			field_order int(11) DEFAULT 0 NOT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
			PRIMARY KEY (id),
			UNIQUE KEY field_name (field_name)
		) {$charset_collate};";

		dbDelta( $sql_custom_fields );

		// Member meta table for custom field values.
		$table_member_meta = $table_prefix . 'member_meta';
		$sql_member_meta   = "CREATE TABLE {$table_member_meta} (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			member_id bigint(20) UNSIGNED NOT NULL,
			field_id bigint(20) UNSIGNED NOT NULL,
			meta_value longtext DEFAULT '' NOT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
			PRIMARY KEY (id),
			UNIQUE KEY member_field (member_id, field_id),
			KEY member_id (member_id),
			KEY field_id (field_id)
		) {$charset_collate};";

		dbDelta( $sql_member_meta );

		// Update database version.
		update_option( 'mbrreg_db_version', MBRREG_VERSION );
	}

	/**
	 * Set default plugin options.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private static function set_default_options() {
		$default_options = array(
			'require_first_name'    => false,
			'require_last_name'     => false,
			'require_address'       => false,
			'require_telephone'     => false,
			'require_date_of_birth' => false,
			'require_place_of_birth' => false,
			'email_from_name'       => get_bloginfo( 'name' ),
			'email_from_address'    => get_option( 'admin_email' ),
			'registration_page_id'  => 0,
			'login_redirect_page'   => 0,
			'allow_registration'    => true,
		);

		foreach ( $default_options as $key => $value ) {
			if ( false === get_option( 'mbrreg_' . $key ) ) {
				add_option( 'mbrreg_' . $key, $value );
			}
		}
	}

	/**
	 * Create plugin-specific roles and capabilities.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private static function create_plugin_roles() {
		// Add capabilities to administrator role.
		$admin_role = get_role( 'administrator' );

		if ( $admin_role ) {
			$admin_role->add_cap( 'mbrreg_manage_members' );
			$admin_role->add_cap( 'mbrreg_manage_settings' );
			$admin_role->add_cap( 'mbrreg_manage_custom_fields' );
			$admin_role->add_cap( 'mbrreg_export_members' );
		}
	}
}