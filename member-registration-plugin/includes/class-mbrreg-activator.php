<?php
/**
 * Fired during plugin activation.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/includes
 * @since 1.0.0
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Class Mbrreg_Activator
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since 1.0.0
 */
class Mbrreg_Activator
{

	/**
	 * Activate the plugin.
	 *
	 * Creates necessary database tables and sets default options.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function activate()
	{
		self::create_tables();
		self::set_default_options();
		self::create_plugin_roles();
		self::maybe_migrate_data();

		// Set flag for activation redirect.
		set_transient('mbrreg_activation_redirect', true, 30);

		// Flush rewrite rules.
		flush_rewrite_rules();
	}

	/**
	 * Create database tables.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private static function create_tables()
	{
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_prefix = $wpdb->prefix . MBRREG_TABLE_PREFIX;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// Members table - simplified to only first_name and last_name as default fields.
		$table_members = $table_prefix . 'members';
		$sql_members = "CREATE TABLE {$table_members} (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			user_id bigint(20) UNSIGNED NOT NULL,
			first_name varchar(100) DEFAULT '' NOT NULL,
			last_name varchar(100) DEFAULT '' NOT NULL,
			status varchar(20) DEFAULT 'pending' NOT NULL,
			is_admin tinyint(1) DEFAULT 0 NOT NULL,
			activation_key varchar(100) DEFAULT '' NOT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
			PRIMARY KEY (id),
			KEY user_id (user_id),
			KEY status (status),
			KEY is_admin (is_admin)
		) {$charset_collate};";

		dbDelta($sql_members);

		// Custom fields table.
		$table_custom_fields = $table_prefix . 'custom_fields';
		$sql_custom_fields = "CREATE TABLE {$table_custom_fields} (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			field_name varchar(100) NOT NULL,
			field_label varchar(255) NOT NULL,
			field_type varchar(50) DEFAULT 'text' NOT NULL,
			field_options text DEFAULT '' NOT NULL,
			is_required tinyint(1) DEFAULT 0 NOT NULL,
			is_admin_only tinyint(1) DEFAULT 0 NOT NULL,
			field_order int(11) DEFAULT 0 NOT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
			PRIMARY KEY (id),
			UNIQUE KEY field_name (field_name)
		) {$charset_collate};";

		dbDelta($sql_custom_fields);

		// Member meta table for custom field values.
		$table_member_meta = $table_prefix . 'member_meta';
		$sql_member_meta = "CREATE TABLE {$table_member_meta} (
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

		dbDelta($sql_member_meta);

		// Update database version.
		update_option('mbrreg_db_version', MBRREG_VERSION);
	}

	/**
	 * Set default plugin options.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private static function set_default_options()
	{
		$default_options = array(
			'require_first_name' => false,
			'require_last_name' => false,
			'email_from_name' => get_bloginfo('name'),
			'email_from_address' => get_option('admin_email'),
			'registration_page_id' => 0,
			'login_redirect_page' => 0,
			'allow_registration' => true,
			'allow_multiple_members' => true,
			'date_format' => 'eu', // 'eu' for d/m/Y, 'us' for m/d/Y.
		);

		foreach ($default_options as $key => $value) {
			if (false === get_option('mbrreg_' . $key)) {
				add_option('mbrreg_' . $key, $value);
			}
		}
	}

	/**
	 * Create plugin-specific roles and capabilities.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private static function create_plugin_roles()
	{
		// Add capabilities to administrator role.
		$admin_role = get_role('administrator');

		if ($admin_role) {
			$admin_role->add_cap('mbrreg_manage_members');
			$admin_role->add_cap('mbrreg_manage_settings');
			$admin_role->add_cap('mbrreg_manage_custom_fields');
			$admin_role->add_cap('mbrreg_export_members');
			$admin_role->add_cap('mbrreg_import_members');
		}
	}

	/**
	 * Migrate data from older versions if needed.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	private static function maybe_migrate_data()
	{
		global $wpdb;

		$current_version = get_option('mbrreg_db_version', '1.0.0');
		$table_members = $wpdb->prefix . MBRREG_TABLE_PREFIX . 'members';

		// Check if old columns exist and migrate data to custom fields.
		if (version_compare($current_version, '1.2.0', '<')) {
			// Check if the old columns exist.
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared
			$columns = $wpdb->get_col("DESCRIBE {$table_members}", 0);

			$old_fields = array('address', 'telephone', 'date_of_birth', 'place_of_birth');
			$fields_to_migrate = array_intersect($old_fields, $columns);

			if (!empty($fields_to_migrate)) {
				self::migrate_old_fields_to_custom_fields($fields_to_migrate);
			}
		}
	}

	/**
	 * Migrate old default fields to custom fields.
	 *
	 * @since 1.2.0
	 * @param array $fields Fields to migrate.
	 * @return void
	 */
	private static function migrate_old_fields_to_custom_fields($fields)
	{
		global $wpdb;

		$table_members = $wpdb->prefix . MBRREG_TABLE_PREFIX . 'members';
		$table_custom_fields = $wpdb->prefix . MBRREG_TABLE_PREFIX . 'custom_fields';
		$table_member_meta = $wpdb->prefix . MBRREG_TABLE_PREFIX . 'member_meta';

		$field_config = array(
			'address' => array(
				'label' => __('Address', 'member-registration-plugin'),
				'type' => 'textarea',
			),
			'telephone' => array(
				'label' => __('Telephone', 'member-registration-plugin'),
				'type' => 'text',
			),
			'date_of_birth' => array(
				'label' => __('Date of Birth', 'member-registration-plugin'),
				'type' => 'date',
			),
			'place_of_birth' => array(
				'label' => __('Place of Birth', 'member-registration-plugin'),
				'type' => 'text',
			),
		);

		foreach ($fields as $field_name) {
			if (!isset($field_config[$field_name])) {
				continue;
			}

			// Check if custom field already exists.
			$existing = $wpdb->get_var(
				$wpdb->prepare(
					// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
					"SELECT id FROM {$table_custom_fields} WHERE field_name = %s",
					$field_name
				)
			);

			if ($existing) {
				continue;
			}

			// Create custom field.
			$wpdb->insert(
				$table_custom_fields,
				array(
					'field_name' => $field_name,
					'field_label' => $field_config[$field_name]['label'],
					'field_type' => $field_config[$field_name]['type'],
					'field_options' => '',
					'is_required' => get_option('mbrreg_require_' . $field_name, 0) ? 1 : 0,
					'is_admin_only' => 0,
					'field_order' => 0,
				),
				array('%s', '%s', '%s', '%s', '%d', '%d', '%d')
			);

			$field_id = $wpdb->insert_id;

			if ($field_id) {
				// Migrate existing data.
				$escaped_field_name = esc_sql($field_name);
				$members = $wpdb->get_results(
					// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared
					"SELECT id, {$escaped_field_name} FROM {$table_members} WHERE {$escaped_field_name} IS NOT NULL AND {$escaped_field_name} != ''"
				);

				foreach ($members as $member) {
					$wpdb->insert(
						$table_member_meta,
						array(
							'member_id' => $member->id,
							'field_id' => $field_id,
							'meta_value' => $member->$field_name,
						),
						array('%d', '%d', '%s')
					);
				}
			}
		}

		// Note: We don't drop the old columns to preserve data integrity.
		// They can be removed manually after confirming migration success.
	}
}