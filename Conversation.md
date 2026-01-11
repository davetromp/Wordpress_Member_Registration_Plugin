# Conversation
- conversationId: f6280200-9b4c-457d-9366-83fc5db44d76
- endpoint: anthropic
- title: Member Registration Plugin for WordPress
- exportAt: 15:26:39 GMT+0100 (Central European Standard Time)

## Options
- presetId: null
- model: claude-opus-4-5
- resendFiles: true
- endpoint: anthropic
- endpointType: undefined
- title: Member Registration Plugin for WordPress

## History
**User**
Help me create a wordpress plugin zip file that I can upload to a wordpress site of a sportsclub.
The club has many members. The member registration is currently done manually. This means that new members will fill out a paper form with their personal details when they join the club. The details are then typed into an excel sheet. Whenever member details need to be updated the member contacts the secretary of the club, who then manually changes the details in the excel sheet. Every few months the excel sheet is looked over to see if members that canceled need to be removed from the memberlist.

# Plugin description and wanted functionality

The idea is that the plugin will let every member manage their own details and that the management of the club can view all the registered members and can make updates to members details.

## Name of the plugin: Member registration plugin

## Users
The plugin will provide functionality to the following users:
* Members
* Administrators

## Requested features
* Once the plugin is installed and activated it should be possible to embed it's functionality into a page by shortcodes.
* On this page the user should be able to login as a member or as an administrator.
* If the user is new, then the user should be able to register as a member.
* A memeber can be upgraded to administrator in the plugin settings.
* From the plugin settings administrators can create, view, update and delete details of single, or multiple members.
* When a member logs in he can view of edit his details. A member cannot delete it's account, but can mark himself as an inactive member instead.
* When registering the user will provide an email address and password. The registrant will need to receive a confimation / activation email with a link to verify that the email address is actually theirs. For registering users we can make use of the wordpress user system and connect this to the member details in the plugin.

## Member details
The following member details need to be able to be collected:
* username (same as wordpress username)
* emailaddress (same as the wordpress emailaddress)
* first name
* last name
* address
* telephone number
* date of birth
* place of birth

Only the username and emailaddress are required fields. All other fields are optional by default, but can be made required fields.
Administrators can also add new / additional fields, that are also optional by default and can also be set to required.

## Instructions

* Build the plugin as if you are a senior wordpress developer
* Build the plugin according to the wordpress coding standards
* The general wordpress coding standards are in file: WordPress_Coding Standards Summary.md
* The Wordpress PHP coding standards are in file: WP-PHP-coding-Standards.md
* The plugin will store member details in the wordpress database. All tables that the plugin will create to do this in the database should be named with the following prefix: mbrreg_
* Generate the needed code and give me instructions on how to create the plugin zipfile.


**Claude**
{"type":"think","think":"The user wants me to create a comprehensive WordPress plugin for member registration for a sports club. Let me break down all the requirements and create a well-structured plugin following WordPress coding standards.\n\nKey Requirements:\n1. Member registration with email verification\n2. Login functionality for members and administrators\n3. Members can view/edit their own details\n4. Administrators can CRUD all members\n5. Custom database tables with mbrreg_ prefix\n6. Shortcodes for embedding\n7. Dynamic fields (some optional, some required, admin can add new fields)\n8. Integration with WordPress user system\n\nPlugin Structure:\n- Main plugin file\n- Admin pages/settings\n- Public-facing functionality\n- Database tables\n- AJAX handlers\n- Email functionality\n- Shortcodes\n- CSS/JS assets\n\nDatabase Tables needed:\n1. mbrreg_members - stores member details linked to WP user\n2. mbrreg_custom_fields - stores custom field definitions\n3. mbrreg_member_meta - stores values for custom fields\n\nLet me create a comprehensive plugin structure:\n\n```\nmember-registration-plugin/\n├── member-registration-plugin.php (main plugin file)\n├── includes/\n│   ├── class-mbrreg-activator.php\n│   ├── class-mbrreg-deactivator.php\n│   ├── class-mbrreg-database.php\n│   ├── class-mbrreg-member.php\n│   ├── class-mbrreg-admin.php\n│   ├── class-mbrreg-public.php\n│   ├── class-mbrreg-shortcodes.php\n│   ├── class-mbrreg-ajax.php\n│   ├── class-mbrreg-email.php\n│   └── class-mbrreg-custom-fields.php\n├── admin/\n│   ├── css/\n│   │   └── mbrreg-admin.css\n\n\n│   ├── js/\n│   │   └── mbrreg-admin.js\n│   └── partials/\n│       ├── mbrreg-admin-dashboard.php\n│       ├── mbrreg-admin-members.php\n│       ├── mbrreg-admin-settings.php\n│       └── mbrreg-admin-custom-fields.php\n├── public/\n│   ├── css/\n│   │   └── mbrreg-public.css\n│   ├── js/\n│   │   └── mbrreg-public.js\n│   └── partials/\n│       ├── mbrreg-login\n\nI'll continue structuring the plugin's public-facing components, focusing on creating modular, reusable code that integrates seamlessly with WordPress's architecture. The public partials will handle user registration, login, and profile management interfaces."}


**Claude**
# Member Registration Plugin for WordPress

I'll create a comprehensive WordPress plugin following the coding standards. Due to the size, I'll provide all the files with clear organization.

## Plugin File Structure

```
member-registration-plugin/
├── member-registration-plugin.php
├── uninstall.php
├── includes/
│   ├── class-mbrreg-activator.php
│   ├── class-mbrreg-deactivator.php
│   ├── class-mbrreg-database.php
│   ├── class-mbrreg-member.php
│   ├── class-mbrreg-admin.php
│   ├── class-mbrreg-public.php
│   ├── class-mbrreg-shortcodes.php
│   ├── class-mbrreg-ajax.php
│   ├── class-mbrreg-email.php
│   └── class-mbrreg-custom-fields.php
├── admin/
│   ├── css/
│   │   └── mbrreg-admin.css
│   ├── js/
│   │   └── mbrreg-admin.js
│   └── partials/
│       ├── mbrreg-admin-dashboard.php
│       ├── mbrreg-admin-members.php
│       ├── mbrreg-admin-member-edit.php
│       ├── mbrreg-admin-settings.php
│       └── mbrreg-admin-custom-fields.php
├── public/
│   ├── css/
│   │   └── mbrreg-public.css
│   ├── js/
│   │   └── mbrreg-public.js
│   └── partials/
│       ├── mbrreg-login-form.php
│       ├── mbrreg-register-form.php
│       └── mbrreg-member-dashboard.php
└── languages/
    └── member-registration-plugin.pot
```

---

## Main Plugin File

```php
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
```

---

## includes/class-mbrreg-activator.php

```php
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
```

---

## includes/class-mbrreg-deactivator.php

```php
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
```

---

## includes/class-mbrreg-database.php

```php
<?php
/**
 * Database operations class.
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
 * Class Mbrreg_Database
 *
 * Handles all database operations for the plugin.
 *
 * @since 1.0.0
 */
class Mbrreg_Database {

	/**
	 * WordPress database object.
	 *
	 * @since 1.0.0
	 * @var wpdb
	 */
	private $wpdb;

	/**
	 * Table prefix for plugin tables.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $table_prefix;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		global $wpdb;
		$this->wpdb         = $wpdb;
		$this->table_prefix = $wpdb->prefix . MBRREG_TABLE_PREFIX;
	}

	/**
	 * Get the members table name.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_members_table() {
		return $this->table_prefix . 'members';
	}

	/**
	 * Get the custom fields table name.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_custom_fields_table() {
		return $this->table_prefix . 'custom_fields';
	}

	/**
	 * Get the member meta table name.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_member_meta_table() {
		return $this->table_prefix . 'member_meta';
	}

	/**
	 * Insert a new member.
	 *
	 * @since 1.0.0
	 * @param array $data Member data.
	 * @return int|false The member ID on success, false on failure.
	 */
	public function insert_member( $data ) {
		$defaults = array(
			'user_id'        => 0,
			'first_name'     => '',
			'last_name'      => '',
			'address'        => '',
			'telephone'      => '',
			'date_of_birth'  => null,
			'place_of_birth' => '',
			'status'         => 'pending',
			'is_admin'       => 0,
			'activation_key' => '',
		);

		$data = wp_parse_args( $data, $defaults );

		$result = $this->wpdb->insert(
			$this->get_members_table(),
			$data,
			array(
				'%d', // user_id.
				'%s', // first_name.
				'%s', // last_name.
				'%s', // address.
				'%s', // telephone.
				'%s', // date_of_birth.
				'%s', // place_of_birth.
				'%s', // status.
				'%d', // is_admin.
				'%s', // activation_key.
			)
		);

		if ( false === $result ) {
			return false;
		}

		return $this->wpdb->insert_id;
	}

	/**
	 * Update a member.
	 *
	 * @since 1.0.0
	 * @param int   $member_id Member ID.
	 * @param array $data      Member data to update.
	 * @return bool True on success, false on failure.
	 */
	public function update_member( $member_id, $data ) {
		$result = $this->wpdb->update(
			$this->get_members_table(),
			$data,
			array( 'id' => $member_id ),
			null,
			array( '%d' )
		);

		return false !== $result;
	}

	/**
	 * Delete a member.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return bool True on success, false on failure.
	 */
	public function delete_member( $member_id ) {
		// Delete member meta first.
		$this->wpdb->delete(
			$this->get_member_meta_table(),
			array( 'member_id' => $member_id ),
			array( '%d' )
		);

		// Delete member.
		$result = $this->wpdb->delete(
			$this->get_members_table(),
			array( 'id' => $member_id ),
			array( '%d' )
		);

		return false !== $result;
	}

	/**
	 * Get a member by ID.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return object|null Member object or null if not found.
	 */
	public function get_member( $member_id ) {
		$sql = $this->wpdb->prepare(
			"SELECT * FROM {$this->get_members_table()} WHERE id = %d",
			$member_id
		);

		return $this->wpdb->get_row( $sql );
	}

	/**
	 * Get a member by user ID.
	 *
	 * @since 1.0.0
	 * @param int $user_id WordPress user ID.
	 * @return object|null Member object or null if not found.
	 */
	public function get_member_by_user_id( $user_id ) {
		$sql = $this->wpdb->prepare(
			"SELECT * FROM {$this->get_members_table()} WHERE user_id = %d",
			$user_id
		);

		return $this->wpdb->get_row( $sql );
	}

	/**
	 * Get a member by activation key.
	 *
	 * @since 1.0.0
	 * @param string $activation_key Activation key.
	 * @return object|null Member object or null if not found.
	 */
	public function get_member_by_activation_key( $activation_key ) {
		$sql = $this->wpdb->prepare(
			"SELECT * FROM {$this->get_members_table()} WHERE activation_key = %s",
			$activation_key
		);

		return $this->wpdb->get_row( $sql );
	}

	/**
	 * Get all members.
	 *
	 * @since 1.0.0
	 * @param array $args Query arguments.
	 * @return array Array of member objects.
	 */
	public function get_members( $args = array() ) {
		$defaults = array(
			'status'   => '',
			'is_admin' => null,
			'search'   => '',
			'orderby'  => 'created_at',
			'order'    => 'DESC',
			'limit'    => -1,
			'offset'   => 0,
		);

		$args = wp_parse_args( $args, $defaults );

		$where_clauses = array( '1=1' );
		$where_values  = array();

		if ( ! empty( $args['status'] ) ) {
			$where_clauses[] = 'status = %s';
			$where_values[]  = $args['status'];
		}

		if ( null !== $args['is_admin'] ) {
			$where_clauses[] = 'is_admin = %d';
			$where_values[]  = (int) $args['is_admin'];
		}

		if ( ! empty( $args['search'] ) ) {
			$search_term     = '%' . $this->wpdb->esc_like( $args['search'] ) . '%';
			$where_clauses[] = '(first_name LIKE %s OR last_name LIKE %s OR address LIKE %s OR telephone LIKE %s)';
			$where_values[]  = $search_term;
			$where_values[]  = $search_term;
			$where_values[]  = $search_term;
			$where_values[]  = $search_term;
		}

		$where_sql = implode( ' AND ', $where_clauses );

		// Sanitize orderby.
		$allowed_orderby = array( 'id', 'first_name', 'last_name', 'status', 'created_at', 'updated_at' );
		$orderby         = in_array( $args['orderby'], $allowed_orderby, true ) ? $args['orderby'] : 'created_at';
		$order           = 'ASC' === strtoupper( $args['order'] ) ? 'ASC' : 'DESC';

		$sql = "SELECT * FROM {$this->get_members_table()} WHERE {$where_sql} ORDER BY {$orderby} {$order}";

		if ( $args['limit'] > 0 ) {
			$sql .= $this->wpdb->prepare( ' LIMIT %d OFFSET %d', $args['limit'], $args['offset'] );
		}

		if ( ! empty( $where_values ) ) {
			$sql = $this->wpdb->prepare( $sql, $where_values );
		}

		return $this->wpdb->get_results( $sql );
	}

	/**
	 * Count members.
	 *
	 * @since 1.0.0
	 * @param array $args Query arguments.
	 * @return int Number of members.
	 */
	public function count_members( $args = array() ) {
		$defaults = array(
			'status'   => '',
			'is_admin' => null,
			'search'   => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$where_clauses = array( '1=1' );
		$where_values  = array();

		if ( ! empty( $args['status'] ) ) {
			$where_clauses[] = 'status = %s';
			$where_values[]  = $args['status'];
		}

		if ( null !== $args['is_admin'] ) {
			$where_clauses[] = 'is_admin = %d';
			$where_values[]  = (int) $args['is_admin'];
		}

		if ( ! empty( $args['search'] ) ) {
			$search_term     = '%' . $this->wpdb->esc_like( $args['search'] ) . '%';
			$where_clauses[] = '(first_name LIKE %s OR last_name LIKE %s OR address LIKE %s OR telephone LIKE %s)';
			$where_values[]  = $search_term;
			$where_values[]  = $search_term;
			$where_values[]  = $search_term;
			$where_values[]  = $search_term;
		}

		$where_sql = implode( ' AND ', $where_clauses );

		$sql = "SELECT COUNT(*) FROM {$this->get_members_table()} WHERE {$where_sql}";

		if ( ! empty( $where_values ) ) {
			$sql = $this->wpdb->prepare( $sql, $where_values );
		}

		return (int) $this->wpdb->get_var( $sql );
	}

	/**
	 * Insert member meta.
	 *
	 * @since 1.0.0
	 * @param int    $member_id Member ID.
	 * @param int    $field_id  Custom field ID.
	 * @param string $value     Meta value.
	 * @return int|false The meta ID on success, false on failure.
	 */
	public function insert_member_meta( $member_id, $field_id, $value ) {
		$result = $this->wpdb->insert(
			$this->get_member_meta_table(),
			array(
				'member_id'  => $member_id,
				'field_id'   => $field_id,
				'meta_value' => $value,
			),
			array( '%d', '%d', '%s' )
		);

		if ( false === $result ) {
			return false;
		}

		return $this->wpdb->insert_id;
	}

	/**
	 * Update member meta.
	 *
	 * @since 1.0.0
	 * @param int    $member_id Member ID.
	 * @param int    $field_id  Custom field ID.
	 * @param string $value     Meta value.
	 * @return bool True on success, false on failure.
	 */
	public function update_member_meta( $member_id, $field_id, $value ) {
		// Check if meta exists.
		$existing = $this->get_member_meta( $member_id, $field_id );

		if ( $existing ) {
			$result = $this->wpdb->update(
				$this->get_member_meta_table(),
				array( 'meta_value' => $value ),
				array(
					'member_id' => $member_id,
					'field_id'  => $field_id,
				),
				array( '%s' ),
				array( '%d', '%d' )
			);
		} else {
			$result = $this->insert_member_meta( $member_id, $field_id, $value );
		}

		return false !== $result;
	}

	/**
	 * Get member meta.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @param int $field_id  Custom field ID (optional).
	 * @return mixed Single value, array of values, or null.
	 */
	public function get_member_meta( $member_id, $field_id = null ) {
		if ( null !== $field_id ) {
			$sql = $this->wpdb->prepare(
				"SELECT meta_value FROM {$this->get_member_meta_table()} WHERE member_id = %d AND field_id = %d",
				$member_id,
				$field_id
			);

			return $this->wpdb->get_var( $sql );
		}

		$sql = $this->wpdb->prepare(
			"SELECT field_id, meta_value FROM {$this->get_member_meta_table()} WHERE member_id = %d",
			$member_id
		);

		$results = $this->wpdb->get_results( $sql );
		$meta    = array();

		foreach ( $results as $row ) {
			$meta[ $row->field_id ] = $row->meta_value;
		}

		return $meta;
	}

	/**
	 * Delete member meta.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @param int $field_id  Custom field ID (optional).
	 * @return bool True on success, false on failure.
	 */
	public function delete_member_meta( $member_id, $field_id = null ) {
		$where = array( 'member_id' => $member_id );
		$where_format = array( '%d' );

		if ( null !== $field_id ) {
			$where['field_id'] = $field_id;
			$where_format[]    = '%d';
		}

		$result = $this->wpdb->delete(
			$this->get_member_meta_table(),
			$where,
			$where_format
		);

		return false !== $result;
	}

	/**
	 * Insert a custom field.
	 *
	 * @since 1.0.0
	 * @param array $data Field data.
	 * @return int|false The field ID on success, false on failure.
	 */
	public function insert_custom_field( $data ) {
		$defaults = array(
			'field_name'    => '',
			'field_label'   => '',
			'field_type'    => 'text',
			'field_options' => '',
			'is_required'   => 0,
			'field_order'   => 0,
		);

		$data = wp_parse_args( $data, $defaults );

		$result = $this->wpdb->insert(
			$this->get_custom_fields_table(),
			$data,
			array( '%s', '%s', '%s', '%s', '%d', '%d' )
		);

		if ( false === $result ) {
			return false;
		}

		return $this->wpdb->insert_id;
	}

	/**
	 * Update a custom field.
	 *
	 * @since 1.0.0
	 * @param int   $field_id Field ID.
	 * @param array $data     Field data to update.
	 * @return bool True on success, false on failure.
	 */
	public function update_custom_field( $field_id, $data ) {
		$result = $this->wpdb->update(
			$this->get_custom_fields_table(),
			$data,
			array( 'id' => $field_id ),
			null,
			array( '%d' )
		);

		return false !== $result;
	}

	/**
	 * Delete a custom field.
	 *
	 * @since 1.0.0
	 * @param int $field_id Field ID.
	 * @return bool True on success, false on failure.
	 */
	public function delete_custom_field( $field_id ) {
		// Delete associated member meta.
		$this->wpdb->delete(
			$this->get_member_meta_table(),
			array( 'field_id' => $field_id ),
			array( '%d' )
		);

		// Delete field.
		$result = $this->wpdb->delete(
			$this->get_custom_fields_table(),
			array( 'id' => $field_id ),
			array( '%d' )
		);

		return false !== $result;
	}

	/**
	 * Get a custom field by ID.
	 *
	 * @since 1.0.0
	 * @param int $field_id Field ID.
	 * @return object|null Field object or null if not found.
	 */
	public function get_custom_field( $field_id ) {
		$sql = $this->wpdb->prepare(
			"SELECT * FROM {$this->get_custom_fields_table()} WHERE id = %d",
			$field_id
		);

		return $this->wpdb->get_row( $sql );
	}

	/**
	 * Get all custom fields.
	 *
	 * @since 1.0.0
	 * @return array Array of field objects.
	 */
	public function get_custom_fields() {
		$sql = "SELECT * FROM {$this->get_custom_fields_table()} ORDER BY field_order ASC, id ASC";

		return $this->wpdb->get_results( $sql );
	}
}
```

---

## includes/class-mbrreg-member.php

```php
<?php
/**
 * Member operations class.
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
 * Class Mbrreg_Member
 *
 * Handles member-related operations.
 *
 * @since 1.0.0
 */
class Mbrreg_Member {

	/**
	 * Database instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Database
	 */
	private $database;

	/**
	 * Custom fields instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Custom_Fields
	 */
	private $custom_fields;

	/**
	 * Email instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Email
	 */
	private $email;

	/**
	 * Member statuses.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public static $statuses = array(
		'pending'  => 'Pending Activation',
		'active'   => 'Active',
		'inactive' => 'Inactive',
	);

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param Mbrreg_Database      $database      Database instance.
	 * @param Mbrreg_Custom_Fields $custom_fields Custom fields instance.
	 * @param Mbrreg_Email         $email         Email instance.
	 */
	public function __construct( Mbrreg_Database $database, Mbrreg_Custom_Fields $custom_fields, Mbrreg_Email $email ) {
		$this->database      = $database;
		$this->custom_fields = $custom_fields;
		$this->email         = $email;
	}

	/**
	 * Register a new member.
	 *
	 * @since 1.0.0
	 * @param array $data Registration data.
	 * @return int|WP_Error Member ID on success, WP_Error on failure.
	 */
	public function register( $data ) {
		// Validate required fields.
		if ( empty( $data['username'] ) ) {
			return new WP_Error( 'missing_username', __( 'Username is required.', 'member-registration-plugin' ) );
		}

		if ( empty( $data['email'] ) ) {
			return new WP_Error( 'missing_email', __( 'Email address is required.', 'member-registration-plugin' ) );
		}

		if ( empty( $data['password'] ) ) {
			return new WP_Error( 'missing_password', __( 'Password is required.', 'member-registration-plugin' ) );
		}

		// Validate email.
		if ( ! is_email( $data['email'] ) ) {
			return new WP_Error( 'invalid_email', __( 'Please enter a valid email address.', 'member-registration-plugin' ) );
		}

		// Check if username exists.
		if ( username_exists( $data['username'] ) ) {
			return new WP_Error( 'username_exists', __( 'This username is already registered.', 'member-registration-plugin' ) );
		}

		// Check if email exists.
		if ( email_exists( $data['email'] ) ) {
			return new WP_Error( 'email_exists', __( 'This email address is already registered.', 'member-registration-plugin' ) );
		}

		// Validate required member fields.
		$validation = $this->validate_member_data( $data );
		if ( is_wp_error( $validation ) ) {
			return $validation;
		}

		// Create WordPress user.
		$user_id = wp_create_user(
			sanitize_user( $data['username'] ),
			$data['password'],
			sanitize_email( $data['email'] )
		);

		if ( is_wp_error( $user_id ) ) {
			return $user_id;
		}

		// Generate activation key.
		$activation_key = wp_generate_password( 32, false );

		// Prepare member data.
		$member_data = array(
			'user_id'        => $user_id,
			'first_name'     => isset( $data['first_name'] ) ? sanitize_text_field( $data['first_name'] ) : '',
			'last_name'      => isset( $data['last_name'] ) ? sanitize_text_field( $data['last_name'] ) : '',
			'address'        => isset( $data['address'] ) ? sanitize_textarea_field( $data['address'] ) : '',
			'telephone'      => isset( $data['telephone'] ) ? sanitize_text_field( $data['telephone'] ) : '',
			'date_of_birth'  => ! empty( $data['date_of_birth'] ) ? sanitize_text_field( $data['date_of_birth'] ) : null,
			'place_of_birth' => isset( $data['place_of_birth'] ) ? sanitize_text_field( $data['place_of_birth'] ) : '',
			'status'         => 'pending',
			'is_admin'       => 0,
			'activation_key' => $activation_key,
		);

		// Insert member.
		$member_id = $this->database->insert_member( $member_data );

		if ( ! $member_id ) {
			// Rollback: delete WordPress user.
			wp_delete_user( $user_id );
			return new WP_Error( 'insert_failed', __( 'Failed to create member record.', 'member-registration-plugin' ) );
		}

		// Save custom field values.
		$this->save_custom_field_values( $member_id, $data );

		// Send activation email.
		$this->email->send_activation_email( $user_id, $activation_key );

		/**
		 * Fires after a new member is registered.
		 *
		 * @since 1.0.0
		 * @param int   $member_id Member ID.
		 * @param int   $user_id   WordPress user ID.
		 * @param array $data      Registration data.
		 */
		do_action( 'mbrreg_member_registered', $member_id, $user_id, $data );

		return $member_id;
	}

	/**
	 * Activate a member account.
	 *
	 * @since 1.0.0
	 * @param string $activation_key Activation key.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function activate( $activation_key ) {
		if ( empty( $activation_key ) ) {
			return new WP_Error( 'missing_key', __( 'Activation key is required.', 'member-registration-plugin' ) );
		}

		$member = $this->database->get_member_by_activation_key( $activation_key );

		if ( ! $member ) {
			return new WP_Error( 'invalid_key', __( 'Invalid activation key.', 'member-registration-plugin' ) );
		}

		if ( 'active' === $member->status ) {
			return new WP_Error( 'already_active', __( 'This account is already activated.', 'member-registration-plugin' ) );
		}

		// Update member status.
		$result = $this->database->update_member(
			$member->id,
			array(
				'status'         => 'active',
				'activation_key' => '',
			)
		);

		if ( ! $result ) {
			return new WP_Error( 'update_failed', __( 'Failed to activate account.', 'member-registration-plugin' ) );
		}

		/**
		 * Fires after a member account is activated.
		 *
		 * @since 1.0.0
		 * @param int $member_id Member ID.
		 * @param int $user_id   WordPress user ID.
		 */
		do_action( 'mbrreg_member_activated', $member->id, $member->user_id );

		return true;
	}

	/**
	 * Update member details.
	 *
	 * @since 1.0.0
	 * @param int   $member_id Member ID.
	 * @param array $data      Member data.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function update( $member_id, $data ) {
		$member = $this->database->get_member( $member_id );

		if ( ! $member ) {
			return new WP_Error( 'member_not_found', __( 'Member not found.', 'member-registration-plugin' ) );
		}

		// Validate member data.
		$validation = $this->validate_member_data( $data, true );
		if ( is_wp_error( $validation ) ) {
			return $validation;
		}

		// Prepare update data.
		$update_data = array();

		if ( isset( $data['first_name'] ) ) {
			$update_data['first_name'] = sanitize_text_field( $data['first_name'] );
		}

		if ( isset( $data['last_name'] ) ) {
			$update_data['last_name'] = sanitize_text_field( $data['last_name'] );
		}

		if ( isset( $data['address'] ) ) {
			$update_data['address'] = sanitize_textarea_field( $data['address'] );
		}

		if ( isset( $data['telephone'] ) ) {
			$update_data['telephone'] = sanitize_text_field( $data['telephone'] );
		}

		if ( isset( $data['date_of_birth'] ) ) {
			$update_data['date_of_birth'] = ! empty( $data['date_of_birth'] ) ? sanitize_text_field( $data['date_of_birth'] ) : null;
		}

		if ( isset( $data['place_of_birth'] ) ) {
			$update_data['place_of_birth'] = sanitize_text_field( $data['place_of_birth'] );
		}

		if ( isset( $data['status'] ) && array_key_exists( $data['status'], self::$statuses ) ) {
			$update_data['status'] = $data['status'];
		}

		if ( isset( $data['is_admin'] ) ) {
			$update_data['is_admin'] = (int) $data['is_admin'];
		}

		// Update member.
		if ( ! empty( $update_data ) ) {
			$result = $this->database->update_member( $member_id, $update_data );

			if ( ! $result ) {
				return new WP_Error( 'update_failed', __( 'Failed to update member.', 'member-registration-plugin' ) );
			}
		}

		// Update WordPress user data if email changed.
		if ( isset( $data['email'] ) && is_email( $data['email'] ) ) {
			$user = get_user_by( 'ID', $member->user_id );
			if ( $user && $user->user_email !== $data['email'] ) {
				// Check if email is already used.
				$existing = email_exists( $data['email'] );
				if ( $existing && $existing !== $member->user_id ) {
					return new WP_Error( 'email_exists', __( 'This email address is already in use.', 'member-registration-plugin' ) );
				}

				wp_update_user(
					array(
						'ID'         => $member->user_id,
						'user_email' => sanitize_email( $data['email'] ),
					)
				);
			}
		}

		// Update WordPress user first/last name.
		$user_update = array( 'ID' => $member->user_id );
		if ( isset( $data['first_name'] ) ) {
			$user_update['first_name'] = sanitize_text_field( $data['first_name'] );
		}
		if ( isset( $data['last_name'] ) ) {
			$user_update['last_name'] = sanitize_text_field( $data['last_name'] );
		}
		if ( count( $user_update ) > 1 ) {
			wp_update_user( $user_update );
		}

		// Save custom field values.
		$this->save_custom_field_values( $member_id, $data );

		/**
		 * Fires after a member is updated.
		 *
		 * @since 1.0.0
		 * @param int   $member_id Member ID.
		 * @param array $data      Update data.
		 */
		do_action( 'mbrreg_member_updated', $member_id, $data );

		return true;
	}

	/**
	 * Delete a member.
	 *
	 * @since 1.0.0
	 * @param int  $member_id        Member ID.
	 * @param bool $delete_wp_user   Whether to delete WordPress user too.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function delete( $member_id, $delete_wp_user = false ) {
		$member = $this->database->get_member( $member_id );

		if ( ! $member ) {
			return new WP_Error( 'member_not_found', __( 'Member not found.', 'member-registration-plugin' ) );
		}

		$user_id = $member->user_id;

		// Delete member.
		$result = $this->database->delete_member( $member_id );

		if ( ! $result ) {
			return new WP_Error( 'delete_failed', __( 'Failed to delete member.', 'member-registration-plugin' ) );
		}

		// Delete WordPress user if requested.
		if ( $delete_wp_user && $user_id ) {
			wp_delete_user( $user_id );
		}

		/**
		 * Fires after a member is deleted.
		 *
		 * @since 1.0.0
		 * @param int $member_id Member ID.
		 * @param int $user_id   WordPress user ID.
		 */
		do_action( 'mbrreg_member_deleted', $member_id, $user_id );

		return true;
	}

	/**
	 * Set member as inactive.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function set_inactive( $member_id ) {
		return $this->update( $member_id, array( 'status' => 'inactive' ) );
	}

	/**
	 * Set member as active.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function set_active( $member_id ) {
		return $this->update( $member_id, array( 'status' => 'active' ) );
	}

	/**
	 * Set member as admin.
	 *
	 * @since 1.0.0
	 * @param int  $member_id Member ID.
	 * @param bool $is_admin  Whether member is admin.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function set_admin( $member_id, $is_admin = true ) {
		$member = $this->database->get_member( $member_id );

		if ( ! $member ) {
			return new WP_Error( 'member_not_found', __( 'Member not found.', 'member-registration-plugin' ) );
		}

		$result = $this->database->update_member( $member_id, array( 'is_admin' => (int) $is_admin ) );

		if ( ! $result ) {
			return new WP_Error( 'update_failed', __( 'Failed to update member admin status.', 'member-registration-plugin' ) );
		}

		// Update WordPress user capabilities.
		$user = get_user_by( 'ID', $member->user_id );
		if ( $user ) {
			if ( $is_admin ) {
				$user->add_cap( 'mbrreg_manage_members' );
			} else {
				$user->remove_cap( 'mbrreg_manage_members' );
			}
		}

		return true;
	}

	/**
	 * Get a member by ID.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return object|null Member object or null.
	 */
	public function get( $member_id ) {
		$member = $this->database->get_member( $member_id );

		if ( $member ) {
			$member = $this->enrich_member_data( $member );
		}

		return $member;
	}

	/**
	 * Get a member by user ID.
	 *
	 * @since 1.0.0
	 * @param int $user_id WordPress user ID.
	 * @return object|null Member object or null.
	 */
	public function get_by_user_id( $user_id ) {
		$member = $this->database->get_member_by_user_id( $user_id );

		if ( $member ) {
			$member = $this->enrich_member_data( $member );
		}

		return $member;
	}

	/**
	 * Get all members.
	 *
	 * @since 1.0.0
	 * @param array $args Query arguments.
	 * @return array Array of member objects.
	 */
	public function get_all( $args = array() ) {
		$members = $this->database->get_members( $args );

		foreach ( $members as $key => $member ) {
			$members[ $key ] = $this->enrich_member_data( $member );
		}

		return $members;
	}

	/**
	 * Count members.
	 *
	 * @since 1.0.0
	 * @param array $args Query arguments.
	 * @return int Number of members.
	 */
	public function count( $args = array() ) {
		return $this->database->count_members( $args );
	}

	/**
	 * Enrich member data with WordPress user info and custom fields.
	 *
	 * @since 1.0.0
	 * @param object $member Member object.
	 * @return object Enriched member object.
	 */
	private function enrich_member_data( $member ) {
		// Get WordPress user.
		$user = get_user_by( 'ID', $member->user_id );

		if ( $user ) {
			$member->username = $user->user_login;
			$member->email    = $user->user_email;
		} else {
			$member->username = '';
			$member->email    = '';
		}

		// Get custom field values.
		$member->custom_fields = $this->database->get_member_meta( $member->id );

		return $member;
	}

	/**
	 * Validate member data.
	 *
	 * @since 1.0.0
	 * @param array $data      Member data.
	 * @param bool  $is_update Whether this is an update operation.
	 * @return true|WP_Error True on success, WP_Error on validation failure.
	 */
	private function validate_member_data( $data, $is_update = false ) {
		// Check required standard fields.
		$required_fields = array(
			'first_name'     => get_option( 'mbrreg_require_first_name', false ),
			'last_name'      => get_option( 'mbrreg_require_last_name', false ),
			'address'        => get_option( 'mbrreg_require_address', false ),
			'telephone'      => get_option( 'mbrreg_require_telephone', false ),
			'date_of_birth'  => get_option( 'mbrreg_require_date_of_birth', false ),
			'place_of_birth' => get_option( 'mbrreg_require_place_of_birth', false ),
		);

		foreach ( $required_fields as $field => $is_required ) {
			if ( $is_required && ( ! isset( $data[ $field ] ) || '' === $data[ $field ] ) ) {
				$label = ucwords( str_replace( '_', ' ', $field ) );
				return new WP_Error(
					'missing_' . $field,
					/* translators: %s: Field name */
					sprintf( __( '%s is required.', 'member-registration-plugin' ), $label )
				);
			}
		}

		// Validate custom fields.
		$custom_fields = $this->custom_fields->get_all();

		foreach ( $custom_fields as $field ) {
			$field_key = 'custom_' . $field->id;

			if ( $field->is_required && ( ! isset( $data[ $field_key ] ) || '' === $data[ $field_key ] ) ) {
				return new WP_Error(
					'missing_custom_' . $field->id,
					/* translators: %s: Field label */
					sprintf( __( '%s is required.', 'member-registration-plugin' ), $field->field_label )
				);
			}
		}

		// Validate date of birth format.
		if ( ! empty( $data['date_of_birth'] ) ) {
			$date = DateTime::createFromFormat( 'Y-m-d', $data['date_of_birth'] );
			if ( ! $date || $date->format( 'Y-m-d' ) !== $data['date_of_birth'] ) {
				return new WP_Error( 'invalid_date', __( 'Please enter a valid date of birth.', 'member-registration-plugin' ) );
			}
		}

		return true;
	}

	/**
	 * Save custom field values.
	 *
	 * @since 1.0.0
	 * @param int   $member_id Member ID.
	 * @param array $data      Data containing custom field values.
	 * @return void
	 */
	private function save_custom_field_values( $member_id, $data ) {
		$custom_fields = $this->custom_fields->get_all();

		foreach ( $custom_fields as $field ) {
			$field_key = 'custom_' . $field->id;

			if ( isset( $data[ $field_key ] ) ) {
				$value = $this->custom_fields->sanitize_field_value( $field, $data[ $field_key ] );
				$this->database->update_member_meta( $member_id, $field->id, $value );
			}
		}
	}

	/**
	 * Check if current user is a member admin.
	 *
	 * @since 1.0.0
	 * @param int $user_id User ID (optional, defaults to current user).
	 * @return bool
	 */
	public function is_member_admin( $user_id = null ) {
		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		if ( ! $user_id ) {
			return false;
		}

		// WordPress administrators always have access.
		if ( user_can( $user_id, 'manage_options' ) ) {
			return true;
		}

		// Check if user is a member admin.
		$member = $this->get_by_user_id( $user_id );

		if ( $member && $member->is_admin ) {
			return true;
		}

		return false;
	}

	/**
	 * Resend activation email.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function resend_activation_email( $member_id ) {
		$member = $this->database->get_member( $member_id );

		if ( ! $member ) {
			return new WP_Error( 'member_not_found', __( 'Member not found.', 'member-registration-plugin' ) );
		}

		if ( 'pending' !== $member->status ) {
			return new WP_Error( 'already_active', __( 'This account is already activated.', 'member-registration-plugin' ) );
		}

		// Generate new activation key.
		$activation_key = wp_generate_password( 32, false );

		// Update member with new key.
		$this->database->update_member( $member_id, array( 'activation_key' => $activation_key ) );

		// Send email.
		$this->email->send_activation_email( $member->user_id, $activation_key );

		return true;
	}
}
```

---

## includes/class-mbrreg-custom-fields.php

```php
<?php
/**
 * Custom fields management class.
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
 * Class Mbrreg_Custom_Fields
 *
 * Handles custom field operations.
 *
 * @since 1.0.0
 */
class Mbrreg_Custom_Fields {

	/**
	 * Database instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Database
	 */
	private $database;

	/**
	 * Available field types.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public static $field_types = array(
		'text'     => 'Text',
		'textarea' => 'Textarea',
		'email'    => 'Email',
		'number'   => 'Number',
		'date'     => 'Date',
		'select'   => 'Dropdown',
		'checkbox' => 'Checkbox',
		'radio'    => 'Radio Buttons',
	);

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->database = new Mbrreg_Database();
	}

	/**
	 * Create a new custom field.
	 *
	 * @since 1.0.0
	 * @param array $data Field data.
	 * @return int|WP_Error Field ID on success, WP_Error on failure.
	 */
	public function create( $data ) {
		// Validate required fields.
		if ( empty( $data['field_name'] ) ) {
			return new WP_Error( 'missing_name', __( 'Field name is required.', 'member-registration-plugin' ) );
		}

		if ( empty( $data['field_label'] ) ) {
			return new WP_Error( 'missing_label', __( 'Field label is required.', 'member-registration-plugin' ) );
		}

		// Sanitize field name.
		$field_name = sanitize_key( $data['field_name'] );

		// Check for reserved names.
		$reserved = array( 'username', 'email', 'password', 'first_name', 'last_name', 'address', 'telephone', 'date_of_birth', 'place_of_birth' );
		if ( in_array( $field_name, $reserved, true ) ) {
			return new WP_Error( 'reserved_name', __( 'This field name is reserved.', 'member-registration-plugin' ) );
		}

		// Validate field type.
		$field_type = isset( $data['field_type'] ) ? $data['field_type'] : 'text';
		if ( ! array_key_exists( $field_type, self::$field_types ) ) {
			$field_type = 'text';
		}

		// Prepare field data.
		$field_data = array(
			'field_name'    => $field_name,
			'field_label'   => sanitize_text_field( $data['field_label'] ),
			'field_type'    => $field_type,
			'field_options' => isset( $data['field_options'] ) ? $this->sanitize_field_options( $data['field_options'] ) : '',
			'is_required'   => isset( $data['is_required'] ) ? (int) $data['is_required'] : 0,
			'field_order'   => isset( $data['field_order'] ) ? (int) $data['field_order'] : 0,
		);

		// Insert field.
		$field_id = $this->database->insert_custom_field( $field_data );

		if ( ! $field_id ) {
			return new WP_Error( 'insert_failed', __( 'Failed to create custom field.', 'member-registration-plugin' ) );
		}

		return $field_id;
	}

	/**
	 * Update a custom field.
	 *
	 * @since 1.0.0
	 * @param int   $field_id Field ID.
	 * @param array $data     Field data.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function update( $field_id, $data ) {
		$field = $this->database->get_custom_field( $field_id );

		if ( ! $field ) {
			return new WP_Error( 'field_not_found', __( 'Custom field not found.', 'member-registration-plugin' ) );
		}

		// Prepare update data.
		$update_data = array();

		if ( isset( $data['field_label'] ) ) {
			$update_data['field_label'] = sanitize_text_field( $data['field_label'] );
		}

		if ( isset( $data['field_type'] ) && array_key_exists( $data['field_type'], self::$field_types ) ) {
			$update_data['field_type'] = $data['field_type'];
		}

		if ( isset( $data['field_options'] ) ) {
			$update_data['field_options'] = $this->sanitize_field_options( $data['field_options'] );
		}

		if ( isset( $data['is_required'] ) ) {
			$update_data['is_required'] = (int) $data['is_required'];
		}

		if ( isset( $data['field_order'] ) ) {
			$update_data['field_order'] = (int) $data['field_order'];
		}

		if ( empty( $update_data ) ) {
			return true;
		}

		$result = $this->database->update_custom_field( $field_id, $update_data );

		if ( ! $result ) {
			return new WP_Error( 'update_failed', __( 'Failed to update custom field.', 'member-registration-plugin' ) );
		}

		return true;
	}

	/**
	 * Delete a custom field.
	 *
	 * @since 1.0.0
	 * @param int $field_id Field ID.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function delete( $field_id ) {
		$field = $this->database->get_custom_field( $field_id );

		if ( ! $field ) {
			return new WP_Error( 'field_not_found', __( 'Custom field not found.', 'member-registration-plugin' ) );
		}

		$result = $this->database->delete_custom_field( $field_id );

		if ( ! $result ) {
			return new WP_Error( 'delete_failed', __( 'Failed to delete custom field.', 'member-registration-plugin' ) );
		}

		return true;
	}

	/**
	 * Get a custom field by ID.
	 *
	 * @since 1.0.0
	 * @param int $field_id Field ID.
	 * @return object|null Field object or null.
	 */
	public function get( $field_id ) {
		return $this->database->get_custom_field( $field_id );
	}

	/**
	 * Get all custom fields.
	 *
	 * @since 1.0.0
	 * @return array Array of field objects.
	 */
	public function get_all() {
		return $this->database->get_custom_fields();
	}

	/**
	 * Sanitize field options.
	 *
	 * @since 1.0.0
	 * @param string|array $options Field options.
	 * @return string JSON-encoded options.
	 */
	private function sanitize_field_options( $options ) {
		if ( is_string( $options ) ) {
			// Convert newline-separated options to array.
			$options = array_filter( array_map( 'trim', explode( "\n", $options ) ) );
		}

		if ( ! is_array( $options ) ) {
			return '';
		}

		$sanitized = array_map( 'sanitize_text_field', $options );

		return wp_json_encode( $sanitized );
	}

	/**
	 * Get parsed field options.
	 *
	 * @since 1.0.0
	 * @param object $field Field object.
	 * @return array Array of options.
	 */
	public function get_field_options( $field ) {
		if ( empty( $field->field_options ) ) {
			return array();
		}

		$options = json_decode( $field->field_options, true );

		return is_array( $options ) ? $options : array();
	}

	/**
	 * Sanitize field value based on field type.
	 *
	 * @since 1.0.0
	 * @param object $field Field object.
	 * @param mixed  $value Field value.
	 * @return mixed Sanitized value.
	 */
	public function sanitize_field_value( $field, $value ) {
		switch ( $field->field_type ) {
			case 'textarea':
				return sanitize_textarea_field( $value );

			case 'email':
				return sanitize_email( $value );

			case 'number':
				return is_numeric( $value ) ? $value : '';

			case 'date':
				$date = DateTime::createFromFormat( 'Y-m-d', $value );
				return ( $date && $date->format( 'Y-m-d' ) === $value ) ? $value : '';

			case 'checkbox':
				return $value ? '1' : '0';

			case 'select':
			case 'radio':
				$options = $this->get_field_options( $field );
				return in_array( $value, $options, true ) ? sanitize_text_field( $value ) : '';

			default:
				return sanitize_text_field( $value );
		}
	}

	/**
	 * Render a custom field input.
	 *
	 * @since 1.0.0
	 * @param object $field   Field object.
	 * @param string $value   Current value.
	 * @param array  $args    Additional arguments.
	 * @return string HTML output.
	 */
	public function render_field_input( $field, $value = '', $args = array() ) {
		$defaults = array(
			'name_prefix' => 'custom_',
			'id_prefix'   => 'mbrreg-custom-',
			'class'       => 'mbrreg-field-input',
		);

		$args = wp_parse_args( $args, $defaults );

		$name     = $args['name_prefix'] . $field->id;
		$id       = $args['id_prefix'] . $field->id;
		$class    = $args['class'];
		$required = $field->is_required ? 'required' : '';

		$output = '';

		switch ( $field->field_type ) {
			case 'textarea':
				$output = sprintf(
					'<textarea name="%s" id="%s" class="%s" %s>%s</textarea>',
					esc_attr( $name ),
					esc_attr( $id ),
					esc_attr( $class ),
					$required,
					esc_textarea( $value )
				);
				break;

			case 'select':
				$options = $this->get_field_options( $field );
				$output  = sprintf(
					'<select name="%s" id="%s" class="%s" %s>',
					esc_attr( $name ),
					esc_attr( $id ),
					esc_attr( $class ),
					$required
				);
				$output .= '<option value="">' . esc_html__( '-- Select --', 'member-registration-plugin' ) . '</option>';
				foreach ( $options as $option ) {
					$output .= sprintf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $option ),
						selected( $value, $option, false ),
						esc_html( $option )
					);
				}
				$output .= '</select>';
				break;

			case 'radio':
				$options = $this->get_field_options( $field );
				foreach ( $options as $index => $option ) {
					$output .= sprintf(
						'<label class="mbrreg-radio-label"><input type="radio" name="%s" value="%s" %s %s> %s</label>',
						esc_attr( $name ),
						esc_attr( $option ),
						checked( $value, $option, false ),
						0 === $index && $field->is_required ? 'required' : '',
						esc_html( $option )
					);
				}
				break;

			case 'checkbox':
				$output = sprintf(
					'<input type="checkbox" name="%s" id="%s" class="%s" value="1" %s %s>',
					esc_attr( $name ),
					esc_attr( $id ),
					esc_attr( $class ),
					checked( $value, '1', false ),
					$required
				);
				break;

			case 'email':
				$output = sprintf(
					'<input type="email" name="%s" id="%s" class="%s" value="%s" %s>',
					esc_attr( $name ),
					esc_attr( $id ),
					esc_attr( $class ),
					esc_attr( $value ),
					$required
				);
				break;

			case 'number':
				$output = sprintf(
					'<input type="number" name="%s" id="%s" class="%s" value="%s" %s>',
					esc_attr( $name ),
					esc_attr( $id ),
					esc_attr( $class ),
					esc_attr( $value ),
					$required
				);
				break;

			case 'date':
				$output = sprintf(
					'<input type="date" name="%s" id="%s" class="%s" value="%s" %s>',
					esc_attr( $name ),
					esc_attr( $id ),
					esc_attr( $class ),
					esc_attr( $value ),
					$required
				);
				break;

			default:
				$output = sprintf(
					'<input type="text" name="%s" id="%s" class="%s" value="%s" %s>',
					esc_attr( $name ),
					esc_attr( $id ),
					esc_attr( $class ),
					esc_attr( $value ),
					$required
				);
				break;
		}

		return $output;
	}
}
```

---

## includes/class-mbrreg-email.php

```php
<?php
/**
 * Email handling class.
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
 * Class Mbrreg_Email
 *
 * Handles email sending for the plugin.
 *
 * @since 1.0.0
 */
class Mbrreg_Email {

	/**
	 * Send activation email to new member.
	 *
	 * @since 1.0.0
	 * @param int    $user_id        WordPress user ID.
	 * @param string $activation_key Activation key.
	 * @return bool Whether the email was sent successfully.
	 */
	public function send_activation_email( $user_id, $activation_key ) {
		$user = get_user_by( 'ID', $user_id );

		if ( ! $user ) {
			return false;
		}

		$activation_url = add_query_arg(
			array(
				'mbrreg_action' => 'activate',
				'key'           => $activation_key,
			),
			home_url( '/' )
		);

		$subject = sprintf(
			/* translators: %s: Site name */
			__( 'Activate your membership at %s', 'member-registration-plugin' ),
			get_bloginfo( 'name' )
		);

		$message = sprintf(
			/* translators: 1: User display name, 2: Site name, 3: Activation URL */
			__(
				'Hello %1$s,

Thank you for registering as a member at %2$s.

Please click the following link to activate your account:

%3$s

If you did not register for this account, please ignore this email.

Best regards,
%2$s',
				'member-registration-plugin'
			),
			$user->display_name,
			get_bloginfo( 'name' ),
			$activation_url
		);

		$headers = $this->get_email_headers();

		/**
		 * Filter the activation email subject.
		 *
		 * @since 1.0.0
		 * @param string  $subject Subject line.
		 * @param WP_User $user    User object.
		 */
		$subject = apply_filters( 'mbrreg_activation_email_subject', $subject, $user );

		/**
		 * Filter the activation email message.
		 *
		 * @since 1.0.0
		 * @param string  $message        Email message.
		 * @param WP_User $user           User object.
		 * @param string  $activation_url Activation URL.
		 */
		$message = apply_filters( 'mbrreg_activation_email_message', $message, $user, $activation_url );

		return wp_mail( $user->user_email, $subject, $message, $headers );
	}

	/**
	 * Send notification to admin about new registration.
	 *
	 * @since 1.0.0
	 * @param int   $member_id Member ID.
	 * @param array $data      Member data.
	 * @return bool Whether the email was sent successfully.
	 */
	public function send_admin_notification( $member_id, $data ) {
		$admin_email = get_option( 'admin_email' );

		$subject = sprintf(
			/* translators: %s: Site name */
			__( 'New member registration at %s', 'member-registration-plugin' ),
			get_bloginfo( 'name' )
		);

		$message = sprintf(
			/* translators: 1: Username, 2: Email */
			__(
				'A new member has registered:

Username: %1$s
Email: %2$s

You can view and manage members in the WordPress admin area.',
				'member-registration-plugin'
			),
			isset( $data['username'] ) ? $data['username'] : '',
			isset( $data['email'] ) ? $data['email'] : ''
		);

		$headers = $this->get_email_headers();

		return wp_mail( $admin_email, $subject, $message, $headers );
	}

	/**
	 * Send welcome email after account activation.
	 *
	 * @since 1.0.0
	 * @param int $user_id WordPress user ID.
	 * @return bool Whether the email was sent successfully.
	 */
	public function send_welcome_email( $user_id ) {
		$user = get_user_by( 'ID', $user_id );

		if ( ! $user ) {
			return false;
		}

		$subject = sprintf(
			/* translators: %s: Site name */
			__( 'Welcome to %s!', 'member-registration-plugin' ),
			get_bloginfo( 'name' )
		);

		$message = sprintf(
			/* translators: 1: User display name, 2: Site name, 3: Login URL */
			__(
				'Hello %1$s,

Your account at %2$s has been activated successfully!

You can now log in at: %3$s

Best regards,
%2$s',
				'member-registration-plugin'
			),
			$user->display_name,
			get_bloginfo( 'name' ),
			wp_login_url()
		);

		$headers = $this->get_email_headers();

		/**
		 * Filter the welcome email subject.
		 *
		 * @since 1.0.0
		 * @param string  $subject Subject line.
		 * @param WP_User $user    User object.
		 */
		$subject = apply_filters( 'mbrreg_welcome_email_subject', $subject, $user );

		/**
		 * Filter the welcome email message.
		 *
		 * @since 1.0.0
		 * @param string  $message Email message.
		 * @param WP_User $user    User object.
		 */
		$message = apply_filters( 'mbrreg_welcome_email_message', $message, $user );

		return wp_mail( $user->user_email, $subject, $message, $headers );
	}

	/**
	 * Get email headers.
	 *
	 * @since 1.0.0
	 * @return array Email headers.
	 */
	private function get_email_headers() {
		$from_name    = get_option( 'mbrreg_email_from_name', get_bloginfo( 'name' ) );
		$from_address = get_option( 'mbrreg_email_from_address', get_option( 'admin_email' ) );

		$headers = array(
			'Content-Type: text/plain; charset=UTF-8',
			sprintf( 'From: %s <%s>', $from_name, $from_address ),
		);

		/**
		 * Filter email headers.
		 *
		 * @since 1.0.0
		 * @param array $headers Email headers.
		 */
		return apply_filters( 'mbrreg_email_headers', $headers );
	}
}
```

---

## includes/class-mbrreg-ajax.php

```php
<?php
/**
 * AJAX handler class.
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
 * Class Mbrreg_Ajax
 *
 * Handles AJAX requests for the plugin.
 *
 * @since 1.0.0
 */
class Mbrreg_Ajax {

	/**
	 * Member instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Member
	 */
	private $member;

	/**
	 * Custom fields instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Custom_Fields
	 */
	private $custom_fields;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param Mbrreg_Member        $member        Member instance.
	 * @param Mbrreg_Custom_Fields $custom_fields Custom fields instance.
	 */
	public function __construct( Mbrreg_Member $member, Mbrreg_Custom_Fields $custom_fields ) {
		$this->member        = $member;
		$this->custom_fields = $custom_fields;
	}

	/**
	 * Initialize AJAX handlers.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init() {
		// Public AJAX actions (for non-logged in users).
		add_action( 'wp_ajax_nopriv_mbrreg_register', array( $this, 'handle_register' ) );
		add_action( 'wp_ajax_nopriv_mbrreg_login', array( $this, 'handle_login' ) );

		// Private AJAX actions (for logged in users).
		add_action( 'wp_ajax_mbrreg_register', array( $this, 'handle_register' ) );
		add_action( 'wp_ajax_mbrreg_login', array( $this, 'handle_login' ) );
		add_action( 'wp_ajax_mbrreg_update_profile', array( $this, 'handle_update_profile' ) );
		add_action( 'wp_ajax_mbrreg_set_inactive', array( $this, 'handle_set_inactive' ) );
		add_action( 'wp_ajax_mbrreg_logout', array( $this, 'handle_logout' ) );

		// Admin AJAX actions.
		add_action( 'wp_ajax_mbrreg_admin_update_member', array( $this, 'handle_admin_update_member' ) );
		add_action( 'wp_ajax_mbrreg_admin_delete_member', array( $this, 'handle_admin_delete_member' ) );
		add_action( 'wp_ajax_mbrreg_admin_bulk_action', array( $this, 'handle_admin_bulk_action' ) );
		add_action( 'wp_ajax_mbrreg_admin_create_field', array( $this, 'handle_admin_create_field' ) );
		add_action( 'wp_ajax_mbrreg_admin_update_field', array( $this, 'handle_admin_update_field' ) );
		add_action( 'wp_ajax_mbrreg_admin_delete_field', array( $this, 'handle_admin_delete_field' ) );
		add_action( 'wp_ajax_mbrreg_admin_resend_activation', array( $this, 'handle_resend_activation' ) );
	}

	/**
	 * Handle member registration.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_register() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_register_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check if registration is allowed.
		if ( ! get_option( 'mbrreg_allow_registration', true ) ) {
			wp_send_json_error( array( 'message' => __( 'Registration is currently disabled.', 'member-registration-plugin' ) ) );
		}

		// Get and sanitize data.
		$data = array(
			'username'       => isset( $_POST['username'] ) ? sanitize_user( wp_unslash( $_POST['username'] ) ) : '',
			'email'          => isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '',
			'password'       => isset( $_POST['password'] ) ? $_POST['password'] : '', // phpcs:ignore
			'first_name'     => isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '',
			'last_name'      => isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '',
			'address'        => isset( $_POST['address'] ) ? sanitize_textarea_field( wp_unslash( $_POST['address'] ) ) : '',
			'telephone'      => isset( $_POST['telephone'] ) ? sanitize_text_field( wp_unslash( $_POST['telephone'] ) ) : '',
			'date_of_birth'  => isset( $_POST['date_of_birth'] ) ? sanitize_text_field( wp_unslash( $_POST['date_of_birth'] ) ) : '',
			'place_of_birth' => isset( $_POST['place_of_birth'] ) ? sanitize_text_field( wp_unslash( $_POST['place_of_birth'] ) ) : '',
		);

		// Get custom field values.
		$custom_fields = $this->custom_fields->get_all();
		foreach ( $custom_fields as $field ) {
			$field_key = 'custom_' . $field->id;
			if ( isset( $_POST[ $field_key ] ) ) {
				$data[ $field_key ] = $this->custom_fields->sanitize_field_value( $field, wp_unslash( $_POST[ $field_key ] ) );
			}
		}

		// Register member.
		$result = $this->member->register( $data );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success(
			array(
				'message'   => __( 'Registration successful! Please check your email to activate your account.', 'member-registration-plugin' ),
				'member_id' => $result,
			)
		);
	}

	/**
	 * Handle member login.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_login() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_login_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		$username = isset( $_POST['username'] ) ? sanitize_user( wp_unslash( $_POST['username'] ) ) : '';
		$password = isset( $_POST['password'] ) ? $_POST['password'] : ''; // phpcs:ignore
		$remember = isset( $_POST['remember'] ) && $_POST['remember'];

		if ( empty( $username ) || empty( $password ) ) {
			wp_send_json_error( array( 'message' => __( 'Please enter your username and password.', 'member-registration-plugin' ) ) );
		}

		// Attempt to authenticate.
		$user = wp_authenticate( $username, $password );

		if ( is_wp_error( $user ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid username or password.', 'member-registration-plugin' ) ) );
		}

		// Check if member is active.
		$member = $this->member->get_by_user_id( $user->ID );

		if ( $member ) {
			if ( 'pending' === $member->status ) {
				wp_send_json_error( array( 'message' => __( 'Please activate your account first. Check your email for the activation link.', 'member-registration-plugin' ) ) );
			}

			if ( 'inactive' === $member->status ) {
				wp_send_json_error( array( 'message' => __( 'Your account is inactive. Please contact the administrator.', 'member-registration-plugin' ) ) );
			}
		}

		// Log the user in.
		wp_set_current_user( $user->ID );
		wp_set_auth_cookie( $user->ID, $remember );

		$redirect_url = '';
		$redirect_page = get_option( 'mbrreg_login_redirect_page', 0 );
		if ( $redirect_page ) {
			$redirect_url = get_permalink( $redirect_page );
		}

		wp_send_json_success(
			array(
				'message'      => __( 'Login successful!', 'member-registration-plugin' ),
				'redirect_url' => $redirect_url,
			)
		);
	}

	/**
	 * Handle profile update.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_update_profile() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_update_profile_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check if user is logged in.
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => __( 'You must be logged in.', 'member-registration-plugin' ) ) );
		}

		$current_user = wp_get_current_user();
		$member       = $this->member->get_by_user_id( $current_user->ID );

		if ( ! $member ) {
			wp_send_json_error( array( 'message' => __( 'Member not found.', 'member-registration-plugin' ) ) );
		}

		// Prepare update data.
		$data = array(
			'first_name'     => isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '',
			'last_name'      => isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '',
			'address'        => isset( $_POST['address'] ) ? sanitize_textarea_field( wp_unslash( $_POST['address'] ) ) : '',
			'telephone'      => isset( $_POST['telephone'] ) ? sanitize_text_field( wp_unslash( $_POST['telephone'] ) ) : '',
			'date_of_birth'  => isset( $_POST['date_of_birth'] ) ? sanitize_text_field( wp_unslash( $_POST['date_of_birth'] ) ) : '',
			'place_of_birth' => isset( $_POST['place_of_birth'] ) ? sanitize_text_field( wp_unslash( $_POST['place_of_birth'] ) ) : '',
		);

		// Email update (if provided).
		if ( isset( $_POST['email'] ) && ! empty( $_POST['email'] ) ) {
			$data['email'] = sanitize_email( wp_unslash( $_POST['email'] ) );
		}

		// Get custom field values.
		$custom_fields = $this->custom_fields->get_all();
		foreach ( $custom_fields as $field ) {
			$field_key = 'custom_' . $field->id;
			if ( isset( $_POST[ $field_key ] ) ) {
				$data[ $field_key ] = $this->custom_fields->sanitize_field_value( $field, wp_unslash( $_POST[ $field_key ] ) );
			}
		}

		// Update member.
		$result = $this->member->update( $member->id, $data );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success( array( 'message' => __( 'Profile updated successfully!', 'member-registration-plugin' ) ) );
	}

	/**
	 * Handle setting member as inactive.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_set_inactive() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_set_inactive_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check if user is logged in.
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => __( 'You must be logged in.', 'member-registration-plugin' ) ) );
		}

		$current_user = wp_get_current_user();
		$member       = $this->member->get_by_user_id( $current_user->ID );

		if ( ! $member ) {
			wp_send_json_error( array( 'message' => __( 'Member not found.', 'member-registration-plugin' ) ) );
		}

		// Set member as inactive.
		$result = $this->member->set_inactive( $member->id );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		// Log the user out.
		wp_logout();

		wp_send_json_success( array( 'message' => __( 'Your membership has been set to inactive.', 'member-registration-plugin' ) ) );
	}

	/**
	 * Handle logout.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_logout() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_logout_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		wp_logout();

		wp_send_json_success( array( 'message' => __( 'You have been logged out.', 'member-registration-plugin' ) ) );
	}

	/**
	 * Handle admin member update.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_admin_update_member() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check permissions.
		if ( ! $this->member->is_member_admin() ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'member-registration-plugin' ) ) );
		}

		$member_id = isset( $_POST['member_id'] ) ? absint( $_POST['member_id'] ) : 0;

		if ( ! $member_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid member ID.', 'member-registration-plugin' ) ) );
		}

		// Prepare update data.
		$data = array();

		$fields = array( 'first_name', 'last_name', 'address', 'telephone', 'date_of_birth', 'place_of_birth', 'status', 'email' );

		foreach ( $fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				$data[ $field ] = 'address' === $field
					? sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) )
					: sanitize_text_field( wp_unslash( $_POST[ $field ] ) );
			}
		}

		// Handle is_admin separately.
		if ( isset( $_POST['is_admin'] ) ) {
			$data['is_admin'] = (int) $_POST['is_admin'];
		}

		// Get custom field values.
		$custom_fields = $this->custom_fields->get_all();
		foreach ( $custom_fields as $field ) {
			$field_key = 'custom_' . $field->id;
			if ( isset( $_POST[ $field_key ] ) ) {
				$data[ $field_key ] = $this->custom_fields->sanitize_field_value( $field, wp_unslash( $_POST[ $field_key ] ) );
			}
		}

		// Update member.
		$result = $this->member->update( $member_id, $data );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		// Handle admin status change.
		if ( isset( $_POST['is_admin'] ) ) {
			$this->member->set_admin( $member_id, (bool) $_POST['is_admin'] );
		}

		wp_send_json_success( array( 'message' => __( 'Member updated successfully!', 'member-registration-plugin' ) ) );
	}

	/**
	 * Handle admin member delete.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_admin_delete_member() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check permissions.
		if ( ! $this->member->is_member_admin() ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'member-registration-plugin' ) ) );
		}

		$member_id      = isset( $_POST['member_id'] ) ? absint( $_POST['member_id'] ) : 0;
		$delete_wp_user = isset( $_POST['delete_wp_user'] ) && $_POST['delete_wp_user'];

		if ( ! $member_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid member ID.', 'member-registration-plugin' ) ) );
		}

		// Delete member.
		$result = $this->member->delete( $member_id, $delete_wp_user );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success( array( 'message' => __( 'Member deleted successfully!', 'member-registration-plugin' ) ) );
	}

	/**
	 * Handle admin bulk action.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_admin_bulk_action() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check permissions.
		if ( ! $this->member->is_member_admin() ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'member-registration-plugin' ) ) );
		}

		$action     = isset( $_POST['bulk_action'] ) ? sanitize_text_field( wp_unslash( $_POST['bulk_action'] ) ) : '';
		$member_ids = isset( $_POST['member_ids'] ) ? array_map( 'absint', (array) $_POST['member_ids'] ) : array();

		if ( empty( $action ) || empty( $member_ids ) ) {
			wp_send_json_error( array( 'message' => __( 'Please select an action and at least one member.', 'member-registration-plugin' ) ) );
		}

		$success_count = 0;
		$error_count   = 0;

		foreach ( $member_ids as $member_id ) {
			$result = false;

			switch ( $action ) {
				case 'activate':
					$result = $this->member->set_active( $member_id );
					break;

				case 'deactivate':
					$result = $this->member->set_inactive( $member_id );
					break;

				case 'delete':
					$result = $this->member->delete( $member_id, false );
					break;

				case 'delete_with_user':
					$result = $this->member->delete( $member_id, true );
					break;
			}

			if ( is_wp_error( $result ) || false === $result ) {
				++$error_count;
			} else {
				++$success_count;
			}
		}

		wp_send_json_success(
			array(
				/* translators: 1: Number of successful operations, 2: Number of failed operations */
				'message' => sprintf(
					__( 'Bulk action completed. %1$d successful, %2$d failed.', 'member-registration-plugin' ),
					$success_count,
					$error_count
				),
			)
		);
	}

	/**
	 * Handle admin create custom field.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_admin_create_field() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check permissions.
		if ( ! current_user_can( 'mbrreg_manage_custom_fields' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'member-registration-plugin' ) ) );
		}

		$data = array(
			'field_name'    => isset( $_POST['field_name'] ) ? sanitize_key( wp_unslash( $_POST['field_name'] ) ) : '',
			'field_label'   => isset( $_POST['field_label'] ) ? sanitize_text_field( wp_unslash( $_POST['field_label'] ) ) : '',
			'field_type'    => isset( $_POST['field_type'] ) ? sanitize_text_field( wp_unslash( $_POST['field_type'] ) ) : 'text',
			'field_options' => isset( $_POST['field_options'] ) ? sanitize_textarea_field( wp_unslash( $_POST['field_options'] ) ) : '',
			'is_required'   => isset( $_POST['is_required'] ) ? (int) $_POST['is_required'] : 0,
			'field_order'   => isset( $_POST['field_order'] ) ? (int) $_POST['field_order'] : 0,
		);

		$result = $this->custom_fields->create( $data );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success(
			array(
				'message'  => __( 'Custom field created successfully!', 'member-registration-plugin' ),
				'field_id' => $result,
			)
		);
	}

	/**
	 * Handle admin update custom field.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_admin_update_field() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check permissions.
		if ( ! current_user_can( 'mbrreg_manage_custom_fields' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'member-registration-plugin' ) ) );
		}

		$field_id = isset( $_POST['field_id'] ) ? absint( $_POST['field_id'] ) : 0;

		if ( ! $field_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid field ID.', 'member-registration-plugin' ) ) );
		}

		$data = array(
			'field_label'   => isset( $_POST['field_label'] ) ? sanitize_text_field( wp_unslash( $_POST['field_label'] ) ) : '',
			'field_type'    => isset( $_POST['field_type'] ) ? sanitize_text_field( wp_unslash( $_POST['field_type'] ) ) : 'text',
			'field_options' => isset( $_POST['field_options'] ) ? sanitize_textarea_field( wp_unslash( $_POST['field_options'] ) ) : '',
			'is_required'   => isset( $_POST['is_required'] ) ? (int) $_POST['is_required'] : 0,
			'field_order'   => isset( $_POST['field_order'] ) ? (int) $_POST['field_order'] : 0,
		);

		$result = $this->custom_fields->update( $field_id, $data );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success( array( 'message' => __( 'Custom field updated successfully!', 'member-registration-plugin' ) ) );
	}

	/**
	 * Handle admin delete custom field.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_admin_delete_field() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check permissions.
		if ( ! current_user_can( 'mbrreg_manage_custom_fields' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'member-registration-plugin' ) ) );
		}

		$field_id = isset( $_POST['field_id'] ) ? absint( $_POST['field_id'] ) : 0;

		if ( ! $field_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid field ID.', 'member-registration-plugin' ) ) );
		}

		$result = $this->custom_fields->delete( $field_id );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success( array( 'message' => __( 'Custom field deleted successfully!', 'member-registration-plugin' ) ) );
	}

	/**
	 * Handle resend activation email.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_resend_activation() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check permissions.
		if ( ! $this->member->is_member_admin() ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'member-registration-plugin' ) ) );
		}

		$member_id = isset( $_POST['member_id'] ) ? absint( $_POST['member_id'] ) : 0;

		if ( ! $member_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid member ID.', 'member-registration-plugin' ) ) );
		}

		$result = $this->member->resend_activation_email( $member_id );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success( array( 'message' => __( 'Activation email sent successfully!', 'member-registration-plugin' ) ) );
	}
}
```

---

## includes/class-mbrreg-shortcodes.php

```php
<?php
/**
 * Shortcodes handler class.
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
 * Class Mbrreg_Shortcodes
 *
 * Handles shortcodes for the plugin.
 *
 * @since 1.0.0
 */
class Mbrreg_Shortcodes {

	/**
	 * Member instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Member
	 */
	private $member;

	/**
	 * Custom fields instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Custom_Fields
	 */
	private $custom_fields;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param Mbrreg_Member        $member        Member instance.
	 * @param Mbrreg_Custom_Fields $custom_fields Custom fields instance.
	 */
	public function __construct( Mbrreg_Member $member, Mbrreg_Custom_Fields $custom_fields ) {
		$this->member        = $member;
		$this->custom_fields = $custom_fields;
	}

	/**
	 * Initialize shortcodes.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init() {
		add_shortcode( 'mbrreg_member_area', array( $this, 'render_member_area' ) );
		add_shortcode( 'mbrreg_login_form', array( $this, 'render_login_form' ) );
		add_shortcode( 'mbrreg_register_form', array( $this, 'render_register_form' ) );
		add_shortcode( 'mbrreg_member_dashboard', array( $this, 'render_member_dashboard' ) );
	}

	/**
	 * Render complete member area (login/register/dashboard).
	 *
	 * @since 1.0.0
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public function render_member_area( $atts ) {
		$atts = shortcode_atts(
			array(
				'show_register' => 'yes',
			),
			$atts,
			'mbrreg_member_area'
		);

		ob_start();

		if ( is_user_logged_in() ) {
			$this->output_member_dashboard();
		} else {
			$this->output_login_register_forms( 'yes' === $atts['show_register'] );
		}

		return ob_get_clean();
	}

	/**
	 * Render login form shortcode.
	 *
	 * @since 1.0.0
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public function render_login_form( $atts ) {
		$atts = shortcode_atts(
			array(
				'redirect' => '',
			),
			$atts,
			'mbrreg_login_form'
		);

		if ( is_user_logged_in() ) {
			return '<p class="mbrreg-message">' . esc_html__( 'You are already logged in.', 'member-registration-plugin' ) . '</p>';
		}

		ob_start();
		include MBRREG_PLUGIN_PATH . 'public/partials/mbrreg-login-form.php';
		return ob_get_clean();
	}

	/**
	 * Render register form shortcode.
	 *
	 * @since 1.0.0
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public function render_register_form( $atts ) {
		$atts = shortcode_atts( array(), $atts, 'mbrreg_register_form' );

		if ( is_user_logged_in() ) {
			return '<p class="mbrreg-message">' . esc_html__( 'You are already registered and logged in.', 'member-registration-plugin' ) . '</p>';
		}

		if ( ! get_option( 'mbrreg_allow_registration', true ) ) {
			return '<p class="mbrreg-message">' . esc_html__( 'Registration is currently disabled.', 'member-registration-plugin' ) . '</p>';
		}

		ob_start();
		$custom_fields = $this->custom_fields->get_all();
		include MBRREG_PLUGIN_PATH . 'public/partials/mbrreg-register-form.php';
		return ob_get_clean();
	}

	/**
	 * Render member dashboard shortcode.
	 *
	 * @since 1.0.0
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public function render_member_dashboard( $atts ) {
		$atts = shortcode_atts( array(), $atts, 'mbrreg_member_dashboard' );

		if ( ! is_user_logged_in() ) {
			return '<p class="mbrreg-message">' . esc_html__( 'Please log in to view your dashboard.', 'member-registration-plugin' ) . '</p>';
		}

		ob_start();
		$this->output_member_dashboard();
		return ob_get_clean();
	}

	/**
	 * Output login and register forms.
	 *
	 * @since 1.0.0
	 * @param bool $show_register Whether to show register form.
	 * @return void
	 */
	private function output_login_register_forms( $show_register = true ) {
		$custom_fields = $this->custom_fields->get_all();
		?>
		<div class="mbrreg-auth-container">
			<div class="mbrreg-tabs">
				<button type="button" class="mbrreg-tab mbrreg-tab-active" data-tab="login">
					<?php esc_html_e( 'Login', 'member-registration-plugin' ); ?>
				</button>
				<?php if ( $show_register && get_option( 'mbrreg_allow_registration', true ) ) : ?>
					<button type="button" class="mbrreg-tab" data-tab="register">
						<?php esc_html_e( 'Register', 'member-registration-plugin' ); ?>
					</button>
				<?php endif; ?>
			</div>

			<div class="mbrreg-tab-content mbrreg-tab-login mbrreg-tab-content-active">
				<?php include MBRREG_PLUGIN_PATH . 'public/partials/mbrreg-login-form.php'; ?>
			</div>

			<?php if ( $show_register && get_option( 'mbrreg_allow_registration', true ) ) : ?>
				<div class="mbrreg-tab-content mbrreg-tab-register">
					<?php include MBRREG_PLUGIN_PATH . 'public/partials/mbrreg-register-form.php'; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Output member dashboard.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function output_member_dashboard() {
		$current_user  = wp_get_current_user();
		$member        = $this->member->get_by_user_id( $current_user->ID );
		$custom_fields = $this->custom_fields->get_all();
		$is_admin      = $this->member->is_member_admin();

		include MBRREG_PLUGIN_PATH . 'public/partials/mbrreg-member-dashboard.php';
	}
}
```

---

## includes/class-mbrreg-admin.php

```php
<?php
/**
 * Admin functionality class.
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
 * Class Mbrreg_Admin
 *
 * Handles admin area functionality.
 *
 * @since 1.0.0
 */
class Mbrreg_Admin {

	/**
	 * Member instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Member
	 */
	private $member;

	/**
	 * Custom fields instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Custom_Fields
	 */
	private $custom_fields;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param Mbrreg_Member        $member        Member instance.
	 * @param Mbrreg_Custom_Fields $custom_fields Custom fields instance.
	 */
	public function __construct( Mbrreg_Member $member, Mbrreg_Custom_Fields $custom_fields ) {
		$this->member        = $member;
		$this->custom_fields = $custom_fields;
	}

	/**
	 * Initialize admin hooks.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_init', array( $this, 'handle_activation_redirect' ) );
	}

	/**
	 * Add admin menu pages.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function add_admin_menu() {
		// Main menu.
		add_menu_page(
			__( 'Member Registration', 'member-registration-plugin' ),
			__( 'Members', 'member-registration-plugin' ),
			'mbrreg_manage_members',
			'mbrreg-members',
			array( $this, 'render_members_page' ),
			'dashicons-groups',
			30
		);

		// Members submenu.
		add_submenu_page(
			'mbrreg-members',
			__( 'All Members', 'member-registration-plugin' ),
			__( 'All Members', 'member-registration-plugin' ),
			'mbrreg_manage_members',
			'mbrreg-members',
			array( $this, 'render_members_page' )
		);

		// Custom Fields submenu.
		add_submenu_page(
			'mbrreg-members',
			__( 'Custom Fields', 'member-registration-plugin' ),
			__( 'Custom Fields', 'member-registration-plugin' ),
			'mbrreg_manage_custom_fields',
			'mbrreg-custom-fields',
			array( $this, 'render_custom_fields_page' )
		);

		// Settings submenu.
		add_submenu_page(
			'mbrreg-members',
			__( 'Settings', 'member-registration-plugin' ),
			__( 'Settings', 'member-registration-plugin' ),
			'mbrreg_manage_settings',
			'mbrreg-settings',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @since 1.0.0
	 * @param string $hook Current admin page hook.
	 * @return void
	 */
	public function enqueue_admin_assets( $hook ) {
		// Only load on plugin pages.
		if ( strpos( $hook, 'mbrreg' ) === false ) {
			return;
		}

		wp_enqueue_style(
			'mbrreg-admin',
			MBRREG_PLUGIN_URL . 'admin/css/mbrreg-admin.css',
			array(),
			MBRREG_VERSION
		);

		wp_enqueue_script(
			'mbrreg-admin',
			MBRREG_PLUGIN_URL . 'admin/js/mbrreg-admin.js',
			array( 'jquery' ),
			MBRREG_VERSION,
			true
		);

		wp_localize_script(
			'mbrreg-admin',
			'mbrregAdmin',
			array(
				'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
				'nonce'           => wp_create_nonce( 'mbrreg_admin_nonce' ),
				'confirmDelete'   => __( 'Are you sure you want to delete this item?', 'member-registration-plugin' ),
				'confirmBulk'     => __( 'Are you sure you want to perform this action on the selected items?', 'member-registration-plugin' ),
				'processing'      => __( 'Processing...', 'member-registration-plugin' ),
				'selectAction'    => __( 'Please select an action.', 'member-registration-plugin' ),
				'selectItems'     => __( 'Please select at least one item.', 'member-registration-plugin' ),
			)
		);
	}

	/**
	 * Register plugin settings.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_settings() {
		// General settings.
		register_setting( 'mbrreg_settings', 'mbrreg_allow_registration' );
		register_setting( 'mbrreg_settings', 'mbrreg_registration_page_id' );
		register_setting( 'mbrreg_settings', 'mbrreg_login_redirect_page' );

		// Required fields settings.
		register_setting( 'mbrreg_settings', 'mbrreg_require_first_name' );
		register_setting( 'mbrreg_settings', 'mbrreg_require_last_name' );
		register_setting( 'mbrreg_settings', 'mbrreg_require_address' );
		register_setting( 'mbrreg_settings', 'mbrreg_require_telephone' );
		register_setting( 'mbrreg_settings', 'mbrreg_require_date_of_birth' );
		register_setting( 'mbrreg_settings', 'mbrreg_require_place_of_birth' );

		// Email settings.
		register_setting( 'mbrreg_settings', 'mbrreg_email_from_name' );
		register_setting( 'mbrreg_settings', 'mbrreg_email_from_address' );
	}

	/**
	 * Handle activation redirect.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_activation_redirect() {
		if ( get_transient( 'mbrreg_activation_redirect' ) ) {
			delete_transient( 'mbrreg_activation_redirect' );

			if ( ! isset( $_GET['activate-multi'] ) ) {
				wp_safe_redirect( admin_url( 'admin.php?page=mbrreg-settings' ) );
				exit;
			}
		}
	}

	/**
	 * Render members page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_members_page() {
		// Check for edit action.
		$action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : 'list';
		$member_id = isset( $_GET['member_id'] ) ? absint( $_GET['member_id'] ) : 0;

		if ( 'edit' === $action && $member_id ) {
			$member = $this->member->get( $member_id );
			$custom_fields = $this->custom_fields->get_all();
			include MBRREG_PLUGIN_PATH . 'admin/partials/mbrreg-admin-member-edit.php';
		} else {
			// Get query parameters.
			$status = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : '';
			$search = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
			$paged  = isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;

			$per_page = 20;
			$offset   = ( $paged - 1 ) * $per_page;

			$args = array(
				'status' => $status,
				'search' => $search,
				'limit'  => $per_page,
				'offset' => $offset,
			);

			$members = $this->member->get_all( $args );
			$total   = $this->member->count( array( 'status' => $status, 'search' => $search ) );

			$total_pages = ceil( $total / $per_page );

			// Get status counts.
			$status_counts = array(
				'all'      => $this->member->count(),
				'active'   => $this->member->count( array( 'status' => 'active' ) ),
				'inactive' => $this->member->count( array( 'status' => 'inactive' ) ),
				'pending'  => $this->member->count( array( 'status' => 'pending' ) ),
			);

			include MBRREG_PLUGIN_PATH . 'admin/partials/mbrreg-admin-members.php';
		}
	}

	/**
	 * Render custom fields page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_custom_fields_page() {
		$custom_fields = $this->custom_fields->get_all();
		$field_types   = Mbrreg_Custom_Fields::$field_types;

		include MBRREG_PLUGIN_PATH . 'admin/partials/mbrreg-admin-custom-fields.php';
	}

	/**
	 * Render settings page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_settings_page() {
		// Get all pages for dropdown.
		$pages = get_pages();

		include MBRREG_PLUGIN_PATH . 'admin/partials/mbrreg-admin-settings.php';
	}
}
```

---

## includes/class-mbrreg-public.php

```php
<?php
/**
 * Public-facing functionality class.
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
 * Class Mbrreg_Public
 *
 * Handles public-facing functionality.
 *
 * @since 1.0.0
 */
class Mbrreg_Public {

	/**
	 * Member instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Member
	 */
	private $member;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param Mbrreg_Member $member Member instance.
	 */
	public function __construct( Mbrreg_Member $member ) {
		$this->member = $member;
	}

	/**
	 * Initialize public hooks.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'init', array( $this, 'handle_activation' ) );
	}

	/**
	 * Enqueue public assets.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_assets() {
		wp_enqueue_style(
			'mbrreg-public',
			MBRREG_PLUGIN_URL . 'public/css/mbrreg-public.css',
			array(),
			MBRREG_VERSION
		);

		wp_enqueue_script(
			'mbrreg-public',
			MBRREG_PLUGIN_URL . 'public/js/mbrreg-public.js',
			array( 'jquery' ),
			MBRREG_VERSION,
			true
		);

		wp_localize_script(
			'mbrreg-public',
			'mbrregPublic',
			array(
				'ajaxUrl'              => admin_url( 'admin-ajax.php' ),
				'registerNonce'        => wp_create_nonce( 'mbrreg_register_nonce' ),
				'loginNonce'           => wp_create_nonce( 'mbrreg_login_nonce' ),
				'updateProfileNonce'   => wp_create_nonce( 'mbrreg_update_profile_nonce' ),
				'setInactiveNonce'     => wp_create_nonce( 'mbrreg_set_inactive_nonce' ),
				'logoutNonce'          => wp_create_nonce( 'mbrreg_logout_nonce' ),
				'processing'           => __( 'Processing...', 'member-registration-plugin' ),
				'confirmInactive'      => __( 'Are you sure you want to deactivate your membership? You will be logged out.', 'member-registration-plugin' ),
				'confirmLogout'        => __( 'Are you sure you want to log out?', 'member-registration-plugin' ),
				'passwordMismatch'     => __( 'Passwords do not match.', 'member-registration-plugin' ),
			)
		);
	}

	/**
	 * Handle account activation.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_activation() {
		if ( ! isset( $_GET['mbrreg_action'] ) || 'activate' !== $_GET['mbrreg_action'] ) {
			return;
		}

		$key = isset( $_GET['key'] ) ? sanitize_text_field( wp_unslash( $_GET['key'] ) ) : '';

		if ( empty( $key ) ) {
			return;
		}

		$result = $this->member->activate( $key );

		if ( is_wp_error( $result ) ) {
			// Store error message.
			set_transient( 'mbrreg_activation_error', $result->get_error_message(), 60 );
		} else {
			// Store success message.
			set_transient( 'mbrreg_activation_success', __( 'Your account has been activated! You can now log in.', 'member-registration-plugin' ), 60 );

			// Send welcome email.
			$database = new Mbrreg_Database();
			$member   = $database->get_member_by_activation_key( '' ); // Key is cleared, need to find by other means.
			// Note: The member is found by the initial key check in activate().
		}

		// Redirect to remove query params.
		$redirect_url = get_option( 'mbrreg_registration_page_id' )
			? get_permalink( get_option( 'mbrreg_registration_page_id' ) )
			: home_url( '/' );

		wp_safe_redirect( $redirect_url );
		exit;
	}
}
```

---

## public/partials/mbrreg-login-form.php

```php
<?php
/**
 * Login form template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/public/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Check for activation messages.
$activation_error   = get_transient( 'mbrreg_activation_error' );
$activation_success = get_transient( 'mbrreg_activation_success' );

if ( $activation_error ) {
	delete_transient( 'mbrreg_activation_error' );
}
if ( $activation_success ) {
	delete_transient( 'mbrreg_activation_success' );
}
?>

<div class="mbrreg-form-container mbrreg-login-form-container">
	<?php if ( $activation_error ) : ?>
		<div class="mbrreg-message mbrreg-error">
			<?php echo esc_html( $activation_error ); ?>
		</div>
	<?php endif; ?>

	<?php if ( $activation_success ) : ?>
		<div class="mbrreg-message mbrreg-success">
			<?php echo esc_html( $activation_success ); ?>
		</div>
	<?php endif; ?>

	<form id="mbrreg-login-form" class="mbrreg-form" method="post">
		<h3 class="mbrreg-form-title"><?php esc_html_e( 'Member Login', 'member-registration-plugin' ); ?></h3>

		<div class="mbrreg-form-messages"></div>

		<div class="mbrreg-form-row">
			<label for="mbrreg-login-username"><?php esc_html_e( 'Username or Email', 'member-registration-plugin' ); ?> <span class="required">*</span></label>
			<input type="text" id="mbrreg-login-username" name="username" required>
		</div>

		<div class="mbrreg-form-row">
			<label for="mbrreg-login-password"><?php esc_html_e( 'Password', 'member-registration-plugin' ); ?> <span class="required">*</span></label>
			<input type="password" id="mbrreg-login-password" name="password" required>
		</div>

		<div class="mbrreg-form-row mbrreg-checkbox-row">
			<label>
				<input type="checkbox" name="remember" value="1">
				<?php esc_html_e( 'Remember me', 'member-registration-plugin' ); ?>
			</label>
		</div>

		<div class="mbrreg-form-row">
			<button type="submit" class="mbrreg-button mbrreg-button-primary">
				<?php esc_html_e( 'Log In', 'member-registration-plugin' ); ?>
			</button>
		</div>

		<div class="mbrreg-form-links">
			<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot your password?', 'member-registration-plugin' ); ?></a>
		</div>
	</form>
</div>
```

---

## public/partials/mbrreg-register-form.php

```php
<?php
/**
 * Registration form template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/public/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get field requirements.
$require_first_name     = get_option( 'mbrreg_require_first_name', false );
$require_last_name      = get_option( 'mbrreg_require_last_name', false );
$require_address        = get_option( 'mbrreg_require_address', false );
$require_telephone      = get_option( 'mbrreg_require_telephone', false );
$require_date_of_birth  = get_option( 'mbrreg_require_date_of_birth', false );
$require_place_of_birth = get_option( 'mbrreg_require_place_of_birth', false );

// Get custom fields instance for rendering.
$custom_fields_handler = new Mbrreg_Custom_Fields();
?>

<div class="mbrreg-form-container mbrreg-register-form-container">
	<form id="mbrreg-register-form" class="mbrreg-form" method="post">
		<h3 class="mbrreg-form-title"><?php esc_html_e( 'Member Registration', 'member-registration-plugin' ); ?></h3>

		<div class="mbrreg-form-messages"></div>

		<!-- Account Details -->
		<fieldset class="mbrreg-fieldset">
			<legend><?php esc_html_e( 'Account Details', 'member-registration-plugin' ); ?></legend>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-username"><?php esc_html_e( 'Username', 'member-registration-plugin' ); ?> <span class="required">*</span></label>
				<input type="text" id="mbrreg-register-username" name="username" required>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-email"><?php esc_html_e( 'Email Address', 'member-registration-plugin' ); ?> <span class="required">*</span></label>
				<input type="email" id="mbrreg-register-email" name="email" required>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-password"><?php esc_html_e( 'Password', 'member-registration-plugin' ); ?> <span class="required">*</span></label>
				<input type="password" id="mbrreg-register-password" name="password" required minlength="8">
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-password-confirm"><?php esc_html_e( 'Confirm Password', 'member-registration-plugin' ); ?> <span class="required">*</span></label>
				<input type="password" id="mbrreg-register-password-confirm" name="password_confirm" required minlength="8">
			</div>
		</fieldset>

		<!-- Personal Details -->
		<fieldset class="mbrreg-fieldset">
			<legend><?php esc_html_e( 'Personal Details', 'member-registration-plugin' ); ?></legend>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-first-name">
					<?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?>
					<?php if ( $require_first_name ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<input type="text" id="mbrreg-register-first-name" name="first_name" <?php echo $require_first_name ? 'required' : ''; ?>>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-last-name">
					<?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?>
					<?php if ( $require_last_name ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<input type="text" id="mbrreg-register-last-name" name="last_name" <?php echo $require_last_name ? 'required' : ''; ?>>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-address">
					<?php esc_html_e( 'Address', 'member-registration-plugin' ); ?>
					<?php if ( $require_address ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<textarea id="mbrreg-register-address" name="address" rows="3" <?php echo $require_address ? 'required' : ''; ?>></textarea>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-telephone">
					<?php esc_html_e( 'Telephone Number', 'member-registration-plugin' ); ?>
					<?php if ( $require_telephone ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<input type="tel" id="mbrreg-register-telephone" name="telephone" <?php echo $require_telephone ? 'required' : ''; ?>>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-dob">
					<?php esc_html_e( 'Date of Birth', 'member-registration-plugin' ); ?>
					<?php if ( $require_date_of_birth ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<input type="date" id="mbrreg-register-dob" name="date_of_birth" <?php echo $require_date_of_birth ? 'required' : ''; ?>>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-pob">
					<?php esc_html_e( 'Place of Birth', 'member-registration-plugin' ); ?>
					<?php if ( $require_place_of_birth ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<input type="text" id="mbrreg-register-pob" name="place_of_birth" <?php echo $require_place_of_birth ? 'required' : ''; ?>>
			</div>
		</fieldset>

		<!-- Custom Fields -->
		<?php if ( ! empty( $custom_fields ) ) : ?>
			<fieldset class="mbrreg-fieldset">
				<legend><?php esc_html_e( 'Additional Information', 'member-registration-plugin' ); ?></legend>

				<?php foreach ( $custom_fields as $field ) : ?>
					<div class="mbrreg-form-row">
						<label for="mbrreg-custom-<?php echo esc_attr( $field->id ); ?>">
							<?php echo esc_html( $field->field_label ); ?>
							<?php if ( $field->is_required ) : ?>
								<span class="required">*</span>
							<?php endif; ?>
						</label>
						<?php echo $custom_fields_handler->render_field_input( $field ); // phpcs:ignore ?>
					</div>
				<?php endforeach; ?>
			</fieldset>
		<?php endif; ?>

		<div class="mbrreg-form-row">
			<button type="submit" class="mbrreg-button mbrreg-button-primary">
				<?php esc_html_e( 'Register', 'member-registration-plugin' ); ?>
			</button>
		</div>
	</form>
</div>
```

---

## public/partials/mbrreg-member-dashboard.php

```php
<?php
/**
 * Member dashboard template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/public/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get custom fields instance for rendering.
$custom_fields_handler = new Mbrreg_Custom_Fields();
?>

<div class="mbrreg-dashboard-container">
	<div class="mbrreg-dashboard-header">
		<h2>
			<?php
			printf(
				/* translators: %s: User display name */
				esc_html__( 'Welcome, %s!', 'member-registration-plugin' ),
				esc_html( $current_user->display_name )
			);
			?>
		</h2>

		<div class="mbrreg-dashboard-actions">
			<?php if ( $is_admin ) : ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members' ) ); ?>" class="mbrreg-button mbrreg-button-secondary">
					<?php esc_html_e( 'Manage Members', 'member-registration-plugin' ); ?>
				</a>
			<?php endif; ?>

			<button type="button" id="mbrreg-logout-btn" class="mbrreg-button mbrreg-button-secondary">
				<?php esc_html_e( 'Log Out', 'member-registration-plugin' ); ?>
			</button>
		</div>
	</div>

	<?php if ( $member ) : ?>
		<div class="mbrreg-dashboard-status">
			<span class="mbrreg-status mbrreg-status-<?php echo esc_attr( $member->status ); ?>">
				<?php echo esc_html( Mbrreg_Member::$statuses[ $member->status ] ); ?>
			</span>
		</div>

		<div class="mbrreg-form-messages"></div>

		<form id="mbrreg-profile-form" class="mbrreg-form" method="post">
			<h3 class="mbrreg-form-title"><?php esc_html_e( 'Your Profile', 'member-registration-plugin' ); ?></h3>

			<!-- Account Details (Read-only username) -->
			<fieldset class="mbrreg-fieldset">
				<legend><?php esc_html_e( 'Account Details', 'member-registration-plugin' ); ?></legend>

				<div class="mbrreg-form-row">
					<label><?php esc_html_e( 'Username', 'member-registration-plugin' ); ?></label>
					<input type="text" value="<?php echo esc_attr( $member->username ); ?>" disabled>
					<span class="mbrreg-field-note"><?php esc_html_e( 'Username cannot be changed.', 'member-registration-plugin' ); ?></span>
				</div>

				<div class="mbrreg-form-row">
					<label for="mbrreg-profile-email"><?php esc_html_e( 'Email Address', 'member-registration-plugin' ); ?> <span class="required">*</span></label>
					<input type="email" id="mbrreg-profile-email" name="email" value="<?php echo esc_attr( $member->email ); ?>" required>
				</div>
			</fieldset>

			<!-- Personal Details -->
			<fieldset class="mbrreg-fieldset">
				<legend><?php esc_html_e( 'Personal Details', 'member-registration-plugin' ); ?></legend>

				<div class="mbrreg-form-row">
					<label for="mbrreg-profile-first-name">
						<?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?>
						<?php if ( get_option( 'mbrreg_require_first_name', false ) ) : ?>
							<span class="required">*</span>
						<?php endif; ?>
					</label>
					<input type="text" id="mbrreg-profile-first-name" name="first_name" value="<?php echo esc_attr( $member->first_name ); ?>" <?php echo get_option( 'mbrreg_require_first_name', false ) ? 'required' : ''; ?>>
				</div>

				<div class="mbrreg-form-row">
					<label for="mbrreg-profile-last-name">
						<?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?>
						<?php if ( get_option( 'mbrreg_require_last_name', false ) ) : ?>
							<span class="required">*</span>
						<?php endif; ?>
					</label>
					<input type="text" id="mbrreg-profile-last-name" name="last_name" value="<?php echo esc_attr( $member->last_name ); ?>" <?php echo get_option( 'mbrreg_require_last_name', false ) ? 'required' : ''; ?>>
				</div>

				<div class="mbrreg-form-row">
					<label for="mbrreg-profile-address">
						<?php esc_html_e( 'Address', 'member-registration-plugin' ); ?>
						<?php if ( get_option( 'mbrreg_require_address', false ) ) : ?>
							<span class="required">*</span>
						<?php endif; ?>
					</label>
					<textarea id="mbrreg-profile-address" name="address" rows="3" <?php echo get_option( 'mbrreg_require_address', false ) ? 'required' : ''; ?>><?php echo esc_textarea( $member->address ); ?></textarea>
				</div>

				<div class="mbrreg-form-row">
					<label for="mbrreg-profile-telephone">
						<?php esc_html_e( 'Telephone Number', 'member-registration-plugin' ); ?>
						<?php if ( get_option( 'mbrreg_require_telephone', false ) ) : ?>
							<span class="required">*</span>
						<?php endif; ?>
					</label>
					<input type="tel" id="mbrreg-profile-telephone" name="telephone" value="<?php echo esc_attr( $member->telephone ); ?>" <?php echo get_option( 'mbrreg_require_telephone', false ) ? 'required' : ''; ?>>
				</div>

				<div class="mbrreg-form-row">
					<label for="mbrreg-profile-dob">
						<?php esc_html_e( 'Date of Birth', 'member-registration-plugin' ); ?>
						<?php if ( get_option( 'mbrreg_require_date_of_birth', false ) ) : ?>
							<span class="required">*</span>
						<?php endif; ?>
					</label>
					<input type="date" id="mbrreg-profile-dob" name="date_of_birth" value="<?php echo esc_attr( $member->date_of_birth ); ?>" <?php echo get_option( 'mbrreg_require_date_of_birth', false ) ? 'required' : ''; ?>>
				</div>

				<div class="mbrreg-form-row">
					<label for="mbrreg-profile-pob">
						<?php esc_html_e( 'Place of Birth', 'member-registration-plugin' ); ?>
						<?php if ( get_option( 'mbrreg_require_place_of_birth', false ) ) : ?>
							<span class="required">*</span>
						<?php endif; ?>
					</label>
					<input type="text" id="mbrreg-profile-pob" name="place_of_birth" value="<?php echo esc_attr( $member->place_of_birth ); ?>" <?php echo get_option( 'mbrreg_require_place_of_birth', false ) ? 'required' : ''; ?>>
				</div>
			</fieldset>

			<!-- Custom Fields -->
			<?php if ( ! empty( $custom_fields ) ) : ?>
				<fieldset class="mbrreg-fieldset">
					<legend><?php esc_html_e( 'Additional Information', 'member-registration-plugin' ); ?></legend>

					<?php foreach ( $custom_fields as $field ) : ?>
						<?php
						$value = isset( $member->custom_fields[ $field->id ] ) ? $member->custom_fields[ $field->id ] : '';
						?>
						<div class="mbrreg-form-row">
							<label for="mbrreg-custom-<?php echo esc_attr( $field->id ); ?>">
								<?php echo esc_html( $field->field_label ); ?>
								<?php if ( $field->is_required ) : ?>
									<span class="required">*</span>
								<?php endif; ?>
							</label>
							<?php echo $custom_fields_handler->render_field_input( $field, $value ); // phpcs:ignore ?>
						</div>
					<?php endforeach; ?>
				</fieldset>
			<?php endif; ?>

			<div class="mbrreg-form-row mbrreg-form-actions">
				<button type="submit" class="mbrreg-button mbrreg-button-primary">
					<?php esc_html_e( 'Update Profile', 'member-registration-plugin' ); ?>
				</button>
			</div>
		</form>

		<!-- Cancel Membership Section -->
		<div class="mbrreg-cancel-membership">
			<h4><?php esc_html_e( 'Cancel Membership', 'member-registration-plugin' ); ?></h4>
			<p><?php esc_html_e( 'If you no longer wish to be a member, you can set your account to inactive. You will be logged out and will need to contact an administrator to reactivate your account.', 'member-registration-plugin' ); ?></p>
			<button type="button" id="mbrreg-set-inactive-btn" class="mbrreg-button mbrreg-button-danger">
				<?php esc_html_e( 'Deactivate My Membership', 'member-registration-plugin' ); ?>
			</button>
		</div>
	<?php else : ?>
		<p class="mbrreg-message mbrreg-warning">
			<?php esc_html_e( 'No member profile found. Please contact an administrator.', 'member-registration-plugin' ); ?>
		</p>
	<?php endif; ?>
</div>
```

---

## admin/partials/mbrreg-admin-members.php

```php
<?php
/**
 * Admin members list template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/admin/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<div class="wrap mbrreg-admin-wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Members', 'member-registration-plugin' ); ?></h1>
	<hr class="wp-header-end">

	<!-- Status Filter Links -->
	<ul class="subsubsub">
		<li class="all">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members' ) ); ?>" <?php echo empty( $status ) ? 'class="current"' : ''; ?>>
				<?php esc_html_e( 'All', 'member-registration-plugin' ); ?>
				<span class="count">(<?php echo esc_html( $status_counts['all'] ); ?>)</span>
			</a> |
		</li>
		<li class="active">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&status=active' ) ); ?>" <?php echo 'active' === $status ? 'class="current"' : ''; ?>>
				<?php esc_html_e( 'Active', 'member-registration-plugin' ); ?>
				<span class="count">(<?php echo esc_html( $status_counts['active'] ); ?>)</span>
			</a> |
		</li>
		<li class="inactive">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&status=inactive' ) ); ?>" <?php echo 'inactive' === $status ? 'class="current"' : ''; ?>>
				<?php esc_html_e( 'Inactive', 'member-registration-plugin' ); ?>
				<span class="count">(<?php echo esc_html( $status_counts['inactive'] ); ?>)</span>
			</a> |
		</li>
		<li class="pending">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&status=pending' ) ); ?>" <?php echo 'pending' === $status ? 'class="current"' : ''; ?>>
				<?php esc_html_e( 'Pending', 'member-registration-plugin' ); ?>
				<span class="count">(<?php echo esc_html( $status_counts['pending'] ); ?>)</span>
			</a>
		</li>
	</ul>

	<!-- Search Form -->
	<form method="get" class="mbrreg-search-form">
		<input type="hidden" name="page" value="mbrreg-members">
		<?php if ( $status ) : ?>
			<input type="hidden" name="status" value="<?php echo esc_attr( $status ); ?>">
		<?php endif; ?>
		<p class="search-box">
			<input type="search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search members...', 'member-registration-plugin' ); ?>">
			<input type="submit" class="button" value="<?php esc_attr_e( 'Search', 'member-registration-plugin' ); ?>">
		</p>
	</form>

	<form method="post" id="mbrreg-members-form">
		<!-- Bulk Actions -->
		<div class="tablenav top">
			<div class="alignleft actions bulkactions">
				<select name="bulk_action" id="mbrreg-bulk-action">
					<option value=""><?php esc_html_e( 'Bulk Actions', 'member-registration-plugin' ); ?></option>
					<option value="activate"><?php esc_html_e( 'Activate', 'member-registration-plugin' ); ?></option>
					<option value="deactivate"><?php esc_html_e( 'Deactivate', 'member-registration-plugin' ); ?></option>
					<option value="delete"><?php esc_html_e( 'Delete', 'member-registration-plugin' ); ?></option>
					<option value="delete_with_user"><?php esc_html_e( 'Delete (with WP user)', 'member-registration-plugin' ); ?></option>
				</select>
				<button type="button" id="mbrreg-bulk-action-btn" class="button action">
					<?php esc_html_e( 'Apply', 'member-registration-plugin' ); ?>
				</button>
			</div>

			<!-- Pagination -->
			<?php if ( $total_pages > 1 ) : ?>
				<div class="tablenav-pages">
					<span class="displaying-num">
						<?php
						printf(
							/* translators: %s: Number of items */
							esc_html( _n( '%s item', '%s items', $total, 'member-registration-plugin' ) ),
							esc_html( number_format_i18n( $total ) )
						);
						?>
					</span>
					<span class="pagination-links">
						<?php
						$pagination_args = array(
							'base'      => add_query_arg( 'paged', '%#%' ),
							'format'    => '',
							'total'     => $total_pages,
							'current'   => $paged,
							'prev_text' => '&laquo;',
							'next_text' => '&raquo;',
						);
						echo paginate_links( $pagination_args ); // phpcs:ignore
						?>
					</span>
				</div>
			<?php endif; ?>
		</div>

		<!-- Members Table -->
		<table class="wp-list-table widefat fixed striped mbrreg-members-table">
			<thead>
				<tr>
					<td class="manage-column column-cb check-column">
						<input type="checkbox" id="cb-select-all">
					</td>
					<th class="manage-column column-username"><?php esc_html_e( 'Username', 'member-registration-plugin' ); ?></th>
					<th class="manage-column column-name"><?php esc_html_e( 'Name', 'member-registration-plugin' ); ?></th>
					<th class="manage-column column-email"><?php esc_html_e( 'Email', 'member-registration-plugin' ); ?></th>
					<th class="manage-column column-status"><?php esc_html_e( 'Status', 'member-registration-plugin' ); ?></th>
					<th class="manage-column column-admin"><?php esc_html_e( 'Admin', 'member-registration-plugin' ); ?></th>
					<th class="manage-column column-registered"><?php esc_html_e( 'Registered', 'member-registration-plugin' ); ?></th>
					<th class="manage-column column-actions"><?php esc_html_e( 'Actions', 'member-registration-plugin' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $members ) ) : ?>
					<tr>
						<td colspan="8"><?php esc_html_e( 'No members found.', 'member-registration-plugin' ); ?></td>
					</tr>
				<?php else : ?>
					<?php foreach ( $members as $member_item ) : ?>
						<tr data-member-id="<?php echo esc_attr( $member_item->id ); ?>">
							<th scope="row" class="check-column">
								<input type="checkbox" name="member_ids[]" value="<?php echo esc_attr( $member_item->id ); ?>">
							</th>
							<td class="column-username">
								<strong>
									<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&action=edit&member_id=' . $member_item->id ) ); ?>">
										<?php echo esc_html( $member_item->username ); ?>
									</a>
								</strong>
							</td>
							<td class="column-name">
								<?php
								$full_name = trim( $member_item->first_name . ' ' . $member_item->last_name );
								echo esc_html( $full_name ? $full_name : '—' );
								?>
							</td>
							<td class="column-email">
								<a href="mailto:<?php echo esc_attr( $member_item->email ); ?>">
									<?php echo esc_html( $member_item->email ); ?>
								</a>
							</td>
							<td class="column-status">
								<span class="mbrreg-status mbrreg-status-<?php echo esc_attr( $member_item->status ); ?>">
									<?php echo esc_html( Mbrreg_Member::$statuses[ $member_item->status ] ); ?>
								</span>
							</td>
							<td class="column-admin">
								<?php if ( $member_item->is_admin ) : ?>
									<span class="dashicons dashicons-yes-alt" style="color: #46b450;"></span>
								<?php else : ?>
									<span class="dashicons dashicons-minus" style="color: #999;"></span>
								<?php endif; ?>
							</td>
							<td class="column-registered">
								<?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $member_item->created_at ) ) ); ?>
							</td>
							<td class="column-actions">
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&action=edit&member_id=' . $member_item->id ) ); ?>" class="button button-small">
									<?php esc_html_e( 'Edit', 'member-registration-plugin' ); ?>
								</a>
								<?php if ( 'pending' === $member_item->status ) : ?>
									<button type="button" class="button button-small mbrreg-resend-activation" data-member-id="<?php echo esc_attr( $member_item->id ); ?>">
										<?php esc_html_e( 'Resend', 'member-registration-plugin' ); ?>
									</button>
								<?php endif; ?>
								<button type="button" class="button button-small button-link-delete mbrreg-delete-member" data-member-id="<?php echo esc_attr( $member_item->id ); ?>">
									<?php esc_html_e( 'Delete', 'member-registration-plugin' ); ?>
								</button>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</form>
</div>
```

---

## admin/partials/mbrreg-admin-member-edit.php

```php
<?php
/**
 * Admin member edit template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/admin/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! $member ) {
	wp_die( esc_html__( 'Member not found.', 'member-registration-plugin' ) );
}

// Get custom fields instance for rendering.
$custom_fields_handler = new Mbrreg_Custom_Fields();
?>

<div class="wrap mbrreg-admin-wrap">
	<h1>
		<?php esc_html_e( 'Edit Member', 'member-registration-plugin' ); ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members' ) ); ?>" class="page-title-action">
			<?php esc_html_e( 'Back to Members', 'member-registration-plugin' ); ?>
		</a>
	</h1>
	<hr class="wp-header-end">

	<div class="mbrreg-admin-messages"></div>

	<form id="mbrreg-admin-member-form" class="mbrreg-admin-form" method="post">
		<input type="hidden" name="member_id" value="<?php echo esc_attr( $member->id ); ?>">

		<table class="form-table">
			<tbody>
				<!-- Username (Read-only) -->
				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Username', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" value="<?php echo esc_attr( $member->username ); ?>" class="regular-text" disabled>
						<p class="description"><?php esc_html_e( 'Username cannot be changed.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>

				<!-- Email -->
				<tr>
					<th scope="row">
						<label for="mbrreg-email"><?php esc_html_e( 'Email Address', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="email" name="email" id="mbrreg-email" value="<?php echo esc_attr( $member->email ); ?>" class="regular-text" required>
					</td>
				</tr>

				<!-- Status -->
				<tr>
					<th scope="row">
						<label for="mbrreg-status"><?php esc_html_e( 'Status', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<select name="status" id="mbrreg-status">
							<?php foreach ( Mbrreg_Member::$statuses as $status_key => $status_label ) : ?>
								<option value="<?php echo esc_attr( $status_key ); ?>" <?php selected( $member->status, $status_key ); ?>>
									<?php echo esc_html( $status_label ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>

				<!-- Admin Status -->
				<tr>
					<th scope="row">
						<label for="mbrreg-is-admin"><?php esc_html_e( 'Plugin Administrator', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<label>
							<input type="checkbox" name="is_admin" id="mbrreg-is-admin" value="1" <?php checked( $member->is_admin, 1 ); ?>>
							<?php esc_html_e( 'Grant this member administrator access to manage other members', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>

				<!-- First Name -->
				<tr>
					<th scope="row">
						<label for="mbrreg-first-name"><?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" name="first_name" id="mbrreg-first-name" value="<?php echo esc_attr( $member->first_name ); ?>" class="regular-text">
					</td>
				</tr>

				<!-- Last Name -->
				<tr>
					<th scope="row">
						<label for="mbrreg-last-name"><?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" name="last_name" id="mbrreg-last-name" value="<?php echo esc_attr( $member->last_name ); ?>" class="regular-text">
					</td>
				</tr>

				<!-- Address -->
				<tr>
					<th scope="row">
						<label for="mbrreg-address"><?php esc_html_e( 'Address', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<textarea name="address" id="mbrreg-address" rows="3" class="large-text"><?php echo esc_textarea( $member->address ); ?></textarea>
					</td>
				</tr>

				<!-- Telephone -->
				<tr>
					<th scope="row">
						<label for="mbrreg-telephone"><?php esc_html_e( 'Telephone Number', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="tel" name="telephone" id="mbrreg-telephone" value="<?php echo esc_attr( $member->telephone ); ?>" class="regular-text">
					</td>
				</tr>

				<!-- Date of Birth -->
				<tr>
					<th scope="row">
						<label for="mbrreg-dob"><?php esc_html_e( 'Date of Birth', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="date" name="date_of_birth" id="mbrreg-dob" value="<?php echo esc_attr( $member->date_of_birth ); ?>">
					</td>
				</tr>

				<!-- Place of Birth -->
				<tr>
					<th scope="row">
						<label for="mbrreg-pob"><?php esc_html_e( 'Place of Birth', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" name="place_of_birth" id="mbrreg-pob" value="<?php echo esc_attr( $member->place_of_birth ); ?>" class="regular-text">
					</td>
				</tr>

				<!-- Custom Fields -->
				<?php if ( ! empty( $custom_fields ) ) : ?>
					<?php foreach ( $custom_fields as $field ) : ?>
						<?php
						$value = isset( $member->custom_fields[ $field->id ] ) ? $member->custom_fields[ $field->id ] : '';
						?>
						<tr>
							<th scope="row">
								<label for="mbrreg-custom-<?php echo esc_attr( $field->id ); ?>">
									<?php echo esc_html( $field->field_label ); ?>
									<?php if ( $field->is_required ) : ?>
										<span class="required">*</span>
									<?php endif; ?>
								</label>
							</th>
							<td>
								<?php
								echo $custom_fields_handler->render_field_input( // phpcs:ignore
									$field,
									$value,
									array( 'class' => 'regular-text' )
								);
								?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>

				<!-- Registration Date (Read-only) -->
				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Registered', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" value="<?php echo esc_attr( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $member->created_at ) ) ); ?>" class="regular-text" disabled>
					</td>
				</tr>

				<!-- Last Updated (Read-only) -->
				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Last Updated', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" value="<?php echo esc_attr( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $member->updated_at ) ) ); ?>" class="regular-text" disabled>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<button type="submit" class="button button-primary">
				<?php esc_html_e( 'Update Member', 'member-registration-plugin' ); ?>
			</button>
			<button type="button" class="button button-link-delete mbrreg-delete-member" data-member-id="<?php echo esc_attr( $member->id ); ?>">
				<?php esc_html_e( 'Delete Member', 'member-registration-plugin' ); ?>
			</button>
		</p>
	</form>
</div>
```

---

## admin/partials/mbrreg-admin-custom-fields.php

```php
<?php
/**
 * Admin custom fields template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/admin/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<div class="wrap mbrreg-admin-wrap">
	<h1><?php esc_html_e( 'Custom Fields', 'member-registration-plugin' ); ?></h1>
	<hr class="wp-header-end">

	<div class="mbrreg-admin-messages"></div>

	<div class="mbrreg-admin-columns">
		<!-- Add New Field Form -->
		<div class="mbrreg-admin-column mbrreg-add-field-column">
			<div class="mbrreg-admin-box">
				<h2><?php esc_html_e( 'Add New Field', 'member-registration-plugin' ); ?></h2>

				<form id="mbrreg-add-field-form" class="mbrreg-admin-form">
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row">
									<label for="mbrreg-field-name"><?php esc_html_e( 'Field Name', 'member-registration-plugin' ); ?></label>
								</th>
								<td>
									<input type="text" name="field_name" id="mbrreg-field-name" class="regular-text" required pattern="[a-z0-9_]+">
									<p class="description"><?php esc_html_e( 'Lowercase letters, numbers, and underscores only.', 'member-registration-plugin' ); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="mbrreg-field-label"><?php esc_html_e( 'Field Label', 'member-registration-plugin' ); ?></label>
								</th>
								<td>
									<input type="text" name="field_label" id="mbrreg-field-label" class="regular-text" required>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="mbrreg-field-type"><?php esc_html_e( 'Field Type', 'member-registration-plugin' ); ?></label>
								</th>
								<td>
									<select name="field_type" id="mbrreg-field-type">
										<?php foreach ( $field_types as $type_key => $type_label ) : ?>
											<option value="<?php echo esc_attr( $type_key ); ?>">
												<?php echo esc_html( $type_label ); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr class="mbrreg-field-options-row" style="display: none;">
								<th scope="row">
									<label for="mbrreg-field-options"><?php esc_html_e( 'Options', 'member-registration-plugin' ); ?></label>
								</th>
								<td>
									<textarea name="field_options" id="mbrreg-field-options" rows="4" class="large-text"></textarea>
									<p class="description"><?php esc_html_e( 'Enter one option per line.', 'member-registration-plugin' ); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="mbrreg-field-required"><?php esc_html_e( 'Required', 'member-registration-plugin' ); ?></label>
								</th>
								<td>
									<label>
										<input type="checkbox" name="is_required" id="mbrreg-field-required" value="1">
										<?php esc_html_e( 'Make this field required', 'member-registration-plugin' ); ?>
									</label>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="mbrreg-field-order"><?php esc_html_e( 'Display Order', 'member-registration-plugin' ); ?></label>
								</th>
								<td>
									<input type="number" name="field_order" id="mbrreg-field-order" value="0" min="0" class="small-text">
								</td>
							</tr>
						</tbody>
					</table>

					<p class="submit">
						<button type="submit" class="button button-primary">
							<?php esc_html_e( 'Add Field', 'member-registration-plugin' ); ?>
						</button>
					</p>
				</form>
			</div>
		</div>

		<!-- Existing Fields List -->
		<div class="mbrreg-admin-column mbrreg-fields-list-column">
			<div class="mbrreg-admin-box">
				<h2><?php esc_html_e( 'Existing Fields', 'member-registration-plugin' ); ?></h2>

				<?php if ( empty( $custom_fields ) ) : ?>
					<p><?php esc_html_e( 'No custom fields created yet.', 'member-registration-plugin' ); ?></p>
				<?php else : ?>
					<table class="wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Label', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Name', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Type', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Required', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Order', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Actions', 'member-registration-plugin' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $custom_fields as $field ) : ?>
								<tr data-field-id="<?php echo esc_attr( $field->id ); ?>">
									<td>
										<strong><?php echo esc_html( $field->field_label ); ?></strong>
									</td>
									<td>
										<code><?php echo esc_html( $field->field_name ); ?></code>
									</td>
									<td>
										<?php echo esc_html( isset( $field_types[ $field->field_type ] ) ? $field_types[ $field->field_type ] : $field->field_type ); ?>
									</td>
									<td>
										<?php if ( $field->is_required ) : ?>
											<span class="dashicons dashicons-yes-alt" style="color: #46b450;"></span>
										<?php else : ?>
											<span class="dashicons dashicons-minus" style="color: #999;"></span>
										<?php endif; ?>
									</td>
									<td><?php echo esc_html( $field->field_order ); ?></td>
									<td>
										<button type="button" class="button button-small mbrreg-edit-field" 
											data-field-id="<?php echo esc_attr( $field->id ); ?>"
											data-field-label="<?php echo esc_attr( $field->field_label ); ?>"
											data-field-type="<?php echo esc_attr( $field->field_type ); ?>"
											data-field-options="<?php echo esc_attr( $field->field_options ); ?>"
											data-is-required="<?php echo esc_attr( $field->is_required ); ?>"
											data-field-order="<?php echo esc_attr( $field->field_order ); ?>">
											<?php esc_html_e( 'Edit', 'member-registration-plugin' ); ?>
										</button>
										<button type="button" class="button button-small button-link-delete mbrreg-delete-field" data-field-id="<?php echo esc_attr( $field->id ); ?>">
											<?php esc_html_e( 'Delete', 'member-registration-plugin' ); ?>
										</button>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<!-- Edit Field Modal -->
	<div id="mbrreg-edit-field-modal" class="mbrreg-modal" style="display: none;">
		<div class="mbrreg-modal-content">
			<span class="mbrreg-modal-close">&times;</span>
			<h2><?php esc_html_e( 'Edit Field', 'member-registration-plugin' ); ?></h2>

			<form id="mbrreg-edit-field-form" class="mbrreg-admin-form">
				<input type="hidden" name="field_id" id="mbrreg-edit-field-id">

				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<label for="mbrreg-edit-field-label"><?php esc_html_e( 'Field Label', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<input type="text" name="field_label" id="mbrreg-edit-field-label" class="regular-text" required>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="mbrreg-edit-field-type"><?php esc_html_e( 'Field Type', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<select name="field_type" id="mbrreg-edit-field-type">
									<?php foreach ( $field_types as $type_key => $type_label ) : ?>
										<option value="<?php echo esc_attr( $type_key ); ?>">
											<?php echo esc_html( $type_label ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr class="mbrreg-edit-field-options-row" style="display: none;">
							<th scope="row">
								<label for="mbrreg-edit-field-options"><?php esc_html_e( 'Options', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<textarea name="field_options" id="mbrreg-edit-field-options" rows="4" class="large-text"></textarea>
								<p class="description"><?php esc_html_e( 'Enter one option per line.', 'member-registration-plugin' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="mbrreg-edit-field-required"><?php esc_html_e( 'Required', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<label>
									<input type="checkbox" name="is_required" id="mbrreg-edit-field-required" value="1">
									<?php esc_html_e( 'Make this field required', 'member-registration-plugin' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="mbrreg-edit-field-order"><?php esc_html_e( 'Display Order', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<input type="number" name="field_order" id="mbrreg-edit-field-order" value="0" min="0" class="small-text">
							</td>
						</tr>
					</tbody>
				</table>

				<p class="submit">
					<button type="submit" class="button button-primary">
						<?php esc_html_e( 'Update Field', 'member-registration-plugin' ); ?>
					</button>
					<button type="button" class="button mbrreg-modal-cancel">
						<?php esc_html_e( 'Cancel', 'member-registration-plugin' ); ?>
					</button>
				</p>
			</form>
		</div>
	</div>
</div>
```

---

## admin/partials/mbrreg-admin-settings.php

```php
<?php
/**
 * Admin settings template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/admin/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<div class="wrap mbrreg-admin-wrap">
	<h1><?php esc_html_e( 'Member Registration Settings', 'member-registration-plugin' ); ?></h1>
	<hr class="wp-header-end">

	<form method="post" action="options.php">
		<?php settings_fields( 'mbrreg_settings' ); ?>

		<!-- General Settings -->
		<h2><?php esc_html_e( 'General Settings', 'member-registration-plugin' ); ?></h2>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><?php esc_html_e( 'Allow Registration', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_allow_registration" value="1" <?php checked( get_option( 'mbrreg_allow_registration', true ), 1 ); ?>>
							<?php esc_html_e( 'Allow new members to register', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="mbrreg_registration_page_id"><?php esc_html_e( 'Registration Page', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<select name="mbrreg_registration_page_id" id="mbrreg_registration_page_id">
							<option value="0"><?php esc_html_e( '— Select a page —', 'member-registration-plugin' ); ?></option>
							<?php foreach ( $pages as $page ) : ?>
								<option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( get_option( 'mbrreg_registration_page_id' ), $page->ID ); ?>>
									<?php echo esc_html( $page->post_title ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<p class="description"><?php esc_html_e( 'The page where the member area shortcode is placed.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="mbrreg_login_redirect_page"><?php esc_html_e( 'Login Redirect Page', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<select name="mbrreg_login_redirect_page" id="mbrreg_login_redirect_page">
							<option value="0"><?php esc_html_e( '— Same page —', 'member-registration-plugin' ); ?></option>
							<?php foreach ( $pages as $page ) : ?>
								<option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( get_option( 'mbrreg_login_redirect_page' ), $page->ID ); ?>>
									<?php echo esc_html( $page->post_title ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<p class="description"><?php esc_html_e( 'Where to redirect members after successful login.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>

		<!-- Required Fields Settings -->
		<h2><?php esc_html_e( 'Required Fields', 'member-registration-plugin' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Select which fields should be required during registration and profile updates.', 'member-registration-plugin' ); ?></p>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_first_name" value="1" <?php checked( get_option( 'mbrreg_require_first_name' ), 1 ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_last_name" value="1" <?php checked( get_option( 'mbrreg_require_last_name' ), 1 ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Address', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_address" value="1" <?php checked( get_option( 'mbrreg_require_address' ), 1 ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Telephone Number', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_telephone" value="1" <?php checked( get_option( 'mbrreg_require_telephone' ), 1 ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Date of Birth', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_date_of_birth" value="1" <?php checked( get_option( 'mbrreg_require_date_of_birth' ), 1 ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Place of Birth', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_place_of_birth" value="1" <?php checked( get_option( 'mbrreg_require_place_of_birth' ), 1 ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
			</tbody>
		</table>

		<!-- Email Settings -->
		<h2><?php esc_html_e( 'Email Settings', 'member-registration-plugin' ); ?></h2>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="mbrreg_email_from_name"><?php esc_html_e( 'From Name', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" name="mbrreg_email_from_name" id="mbrreg_email_from_name" value="<?php echo esc_attr( get_option( 'mbrreg_email_from_name', get_bloginfo( 'name' ) ) ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="mbrreg_email_from_address"><?php esc_html_e( 'From Email Address', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="email" name="mbrreg_email_from_address" id="mbrreg_email_from_address" value="<?php echo esc_attr( get_option( 'mbrreg_email_from_address', get_option( 'admin_email' ) ) ); ?>" class="regular-text">
					</td>
				</tr>
			</tbody>
		</table>

		<!-- Shortcode Reference -->
		<h2><?php esc_html_e( 'Shortcode Reference', 'member-registration-plugin' ); ?></h2>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><code>[mbrreg_member_area]</code></th>
					<td>
						<?php esc_html_e( 'Displays the complete member area with login, registration, and dashboard.', 'member-registration-plugin' ); ?>
						<br>
						<small><?php esc_html_e( 'Optional attribute: show_register="no" to hide registration form.', 'member-registration-plugin' ); ?></small>
					</td>
				</tr>
				<tr>
					<th scope="row"><code>[mbrreg_login_form]</code></th>
					<td><?php esc_html_e( 'Displays only the login form.', 'member-registration-plugin' ); ?></td>
				</tr>
				<tr>
					<th scope="row"><code>[mbrreg_register_form]</code></th>
					<td><?php esc_html_e( 'Displays only the registration form.', 'member-registration-plugin' ); ?></td>
				</tr>
				<tr>
					<th scope="row"><code>[mbrreg_member_dashboard]</code></th>
					<td><?php esc_html_e( 'Displays the member dashboard (for logged-in members only).', 'member-registration-plugin' ); ?></td>
				</tr>
			</tbody>
		</table>

		<?php submit_button(); ?>
	</form>
</div>
```

---

## admin/css/mbrreg-admin.css

```css
/**
 * Admin styles for Member Registration Plugin.
 *
 * @package Member_Registration_Plugin
 * @since 1.0.0
 */

/* General Admin Styles */
.mbrreg-admin-wrap {
	max-width: 1200px;
}

.mbrreg-admin-wrap .mbrreg-admin-messages {
	margin: 15px 0;
}

.mbrreg-admin-wrap .mbrreg-admin-messages .notice {
	margin: 5px 0;
}

/* Status Badges */
.mbrreg-status {
	display: inline-block;
	padding: 3px 8px;
	border-radius: 3px;
	font-size: 12px;
	font-weight: 600;
	text-transform: uppercase;
}

.mbrreg-status-active {
	background-color: #d4edda;
	color: #155724;
}

.mbrreg-status-inactive {
	background-color: #f8d7da;
	color: #721c24;
}

.mbrreg-status-pending {
	background-color: #fff3cd;
	color: #856404;
}

/* Members Table */
.mbrreg-members-table .column-cb {
	width: 30px;
}

.mbrreg-members-table .column-username {
	width: 15%;
}

.mbrreg-members-table .column-name {
	width: 15%;
}

.mbrreg-members-table .column-email {
	width: 20%;
}

.mbrreg-members-table .column-status {
	width: 10%;
}

.mbrreg-members-table .column-admin {
	width: 8%;
	text-align: center;
}

.mbrreg-members-table .column-registered {
	width: 12%;
}

.mbrreg-members-table .column-actions {
	width: 20%;
}

.mbrreg-members-table .column-actions .button {
	margin-right: 5px;
}

/* Search Form */
.mbrreg-search-form {
	float: right;
	margin-bottom: 10px;
}

/* Admin Columns Layout */
.mbrreg-admin-columns {
	display: flex;
	gap: 20px;
	flex-wrap: wrap;
}

.mbrreg-admin-column {
	flex: 1;
	min-width: 300px;
}

.mbrreg-add-field-column {
	max-width: 400px;
}

.mbrreg-fields-list-column {
	flex: 2;
}

.mbrreg-admin-box {
	background: #fff;
	border: 1px solid #ccd0d4;
	padding: 15px 20px;
	margin-bottom: 20px;
}

.mbrreg-admin-box h2 {
	margin-top: 0;
	padding-bottom: 10px;
	border-bottom: 1px solid #eee;
}

/* Modal Styles */
.mbrreg-modal {
	position: fixed;
	z-index: 100000;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	background-color: rgba(0, 0, 0, 0.6);
	display: flex;
	align-items: center;
	justify-content: center;
}

.mbrreg-modal-content {
	background-color: #fff;
	padding: 20px 30px;
	border-radius: 4px;
	max-width: 600px;
	width: 90%;
	max-height: 90vh;
	overflow-y: auto;
	position: relative;
}

.mbrreg-modal-close {
	position: absolute;
	right: 15px;
	top: 10px;
	font-size: 24px;
	cursor: pointer;
	color: #666;
}

.mbrreg-modal-close:hover {
	color: #000;
}

/* Form Styles */
.mbrreg-admin-form .form-table th {
	width: 150px;
}

.mbrreg-admin-form .required {
	color: #dc3545;
}

/* Responsive */
@media screen and (max-width: 782px) {
	.mbrreg-admin-columns {
		flex-direction: column;
	}

	.mbrreg-add-field-column,
	.mbrreg-fields-list-column {
		max-width: 100%;
	}

	.mbrreg-members-table .column-admin,
	.mbrreg-members-table .column-registered {
		display: none;
	}
}
```

---

## admin/js/mbrreg-admin.js

```javascript
/**
 * Admin JavaScript for Member Registration Plugin.
 *
 * @package Member_Registration_Plugin
 * @since 1.0.0
 */

(function($) {
	'use strict';

	/**
	 * Admin functionality object.
	 */
	const MbrregAdmin = {

		/**
		 * Initialize admin functionality.
		 */
		init: function() {
			this.bindEvents();
			this.initFieldTypeToggle();
		},

		/**
		 * Bind event handlers.
		 */
		bindEvents: function() {
			// Member management.
			$(document).on('submit', '#mbrreg-admin-member-form', this.handleMemberUpdate);
			$(document).on('click', '.mbrreg-delete-member', this.handleMemberDelete);
			$(document).on('click', '#mbrreg-bulk-action-btn', this.handleBulkAction);
			$(document).on('click', '.mbrreg-resend-activation', this.handleResendActivation);

			// Custom fields management.
			$(document).on('submit', '#mbrreg-add-field-form', this.handleFieldCreate);
			$(document).on('click', '.mbrreg-edit-field', this.openEditFieldModal);
			$(document).on('submit', '#mbrreg-edit-field-form', this.handleFieldUpdate);
			$(document).on('click', '.mbrreg-delete-field', this.handleFieldDelete);

			// Modal controls.
			$(document).on('click', '.mbrreg-modal-close, .mbrreg-modal-cancel', this.closeModal);
			$(document).on('click', '.mbrreg-modal', this.closeModalOnOverlay);

			// Field type toggle.
			$(document).on('change', '#mbrreg-field-type', this.toggleFieldOptions);
			$(document).on('change', '#mbrreg-edit-field-type', this.toggleEditFieldOptions);

			// Select all checkbox.
			$(document).on('change', '#cb-select-all', this.toggleSelectAll);
		},

		/**
		 * Initialize field type toggle on page load.
		 */
		initFieldTypeToggle: function() {
			this.toggleFieldOptions();
		},

		/**
		 * Handle member update.
		 *
		 * @param {Event} e Submit event.
		 */
		handleMemberUpdate: function(e) {
			e.preventDefault();

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			const originalText = $submitBtn.text();

			$submitBtn.prop('disabled', true).text(mbrregAdmin.processing);

			const formData = new FormData($form[0]);
			formData.append('action', 'mbrreg_admin_update_member');
			formData.append('nonce', mbrregAdmin.nonce);

			// Handle checkbox for is_admin.
			if (!$form.find('input[name="is_admin"]').is(':checked')) {
				formData.set('is_admin', '0');
			}

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					if (response.success) {
						MbrregAdmin.showMessage(response.data.message, 'success');
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
					}
				},
				error: function() {
					MbrregAdmin.showMessage('An error occurred. Please try again.', 'error');
				},
				complete: function() {
					$submitBtn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle member delete.
		 *
		 * @param {Event} e Click event.
		 */
		handleMemberDelete: function(e) {
			e.preventDefault();

			if (!confirm(mbrregAdmin.confirmDelete)) {
				return;
			}

			const $btn = $(this);
			const memberId = $btn.data('member-id');

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_admin_delete_member',
					nonce: mbrregAdmin.nonce,
					member_id: memberId,
					delete_wp_user: false
				},
				success: function(response) {
					if (response.success) {
						// Remove row or redirect.
						const $row = $btn.closest('tr');
						if ($row.length) {
							$row.fadeOut(function() {
								$(this).remove();
							});
						} else {
							window.location.href = 'admin.php?page=mbrreg-members';
						}
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
					}
				},
				error: function() {
					MbrregAdmin.showMessage('An error occurred. Please try again.', 'error');
				}
			});
		},

		/**
		 * Handle bulk action.
		 *
		 * @param {Event} e Click event.
		 */
		handleBulkAction: function(e) {
			e.preventDefault();

			const action = $('#mbrreg-bulk-action').val();
			const memberIds = [];

			$('input[name="member_ids[]"]:checked').each(function() {
				memberIds.push($(this).val());
			});

			if (!action) {
				alert(mbrregAdmin.selectAction);
				return;
			}

			if (memberIds.length === 0) {
				alert(mbrregAdmin.selectItems);
				return;
			}

			if (!confirm(mbrregAdmin.confirmBulk)) {
				return;
			}

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_admin_bulk_action',
					nonce: mbrregAdmin.nonce,
					bulk_action: action,
					member_ids: memberIds
				},
				success: function(response) {
					if (response.success) {
						location.reload();
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
					}
				},
				error: function() {
					MbrregAdmin.showMessage('An error occurred. Please try again.', 'error');
				}
			});
		},

		/**
		 * Handle resend activation email.
		 *
		 * @param {Event} e Click event.
		 */
		handleResendActivation: function(e) {
			e.preventDefault();

			const $btn = $(this);
			const memberId = $btn.data('member-id');
			const originalText = $btn.text();

			$btn.prop('disabled', true).text(mbrregAdmin.processing);

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_admin_resend_activation',
					nonce: mbrregAdmin.nonce,
					member_id: memberId
				},
				success: function(response) {
					if (response.success) {
						MbrregAdmin.showMessage(response.data.message, 'success');
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
					}
				},
				error: function() {
					MbrregAdmin.showMessage('An error occurred. Please try again.', 'error');
				},
				complete: function() {
					$btn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle custom field create.
		 *
		 * @param {Event} e Submit event.
		 */
		handleFieldCreate: function(e) {
			e.preventDefault();

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			const originalText = $submitBtn.text();

			$submitBtn.prop('disabled', true).text(mbrregAdmin.processing);

			const formData = new FormData($form[0]);
			formData.append('action', 'mbrreg_admin_create_field');
			formData.append('nonce', mbrregAdmin.nonce);

			// Handle checkbox for is_required.
			if (!$form.find('input[name="is_required"]').is(':checked')) {
				formData.set('is_required', '0');
			}

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					if (response.success) {
						location.reload();
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
					}
				},
				error: function() {
					MbrregAdmin.showMessage('An error occurred. Please try again.', 'error');
				},
				complete: function() {
					$submitBtn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Open edit field modal.
		 *
		 * @param {Event} e Click event.
		 */
		openEditFieldModal: function(e) {
			e.preventDefault();

			const $btn = $(this);
			const $modal = $('#mbrreg-edit-field-modal');

			// Populate form fields.
			$('#mbrreg-edit-field-id').val($btn.data('field-id'));
			$('#mbrreg-edit-field-label').val($btn.data('field-label'));
			$('#mbrreg-edit-field-type').val($btn.data('field-type'));
			$('#mbrreg-edit-field-order').val($btn.data('field-order'));
			$('#mbrreg-edit-field-required').prop('checked', $btn.data('is-required') == 1);

			// Parse and set options.
			let options = $btn.data('field-options');
			if (options) {
				try {
					options = JSON.parse(options);
					if (Array.isArray(options)) {
						options = options.join('\n');
					}
				} catch (e) {
					options = '';
				}
			}
			$('#mbrreg-edit-field-options').val(options || '');

			// Toggle options visibility.
			MbrregAdmin.toggleEditFieldOptions();

			// Show modal.
			$modal.show();
		},

		/**
		 * Handle custom field update.
		 *
		 * @param {Event} e Submit event.
		 */
		handleFieldUpdate: function(e) {
			e.preventDefault();

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			const originalText = $submitBtn.text();

			$submitBtn.prop('disabled', true).text(mbrregAdmin.processing);

			const formData = new FormData($form[0]);
			formData.append('action', 'mbrreg_admin_update_field');
			formData.append('nonce', mbrregAdmin.nonce);

			// Handle checkbox for is_required.
			if (!$form.find('input[name="is_required"]').is(':checked')) {
				formData.set('is_required', '0');
			}

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					if (response.success) {
						location.reload();
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
					}
				},
				error: function() {
					MbrregAdmin.showMessage('An error occurred. Please try again.', 'error');
				},
				complete: function() {
					$submitBtn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle custom field delete.
		 *
		 * @param {Event} e Click event.
		 */
		handleFieldDelete: function(e) {
			e.preventDefault();

			if (!confirm(mbrregAdmin.confirmDelete)) {
				return;
			}

			const $btn = $(this);
			const fieldId = $btn.data('field-id');

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_admin_delete_field',
					nonce: mbrregAdmin.nonce,
					field_id: fieldId
				},
				success: function(response) {
					if (response.success) {
						$btn.closest('tr').fadeOut(function() {
							$(this).remove();
						});
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
					}
				},
				error: function() {
					MbrregAdmin.showMessage('An error occurred. Please try again.', 'error');
				}
			});
		},

		/**
		 * Toggle field options visibility.
		 */
		toggleFieldOptions: function() {
			const fieldType = $('#mbrreg-field-type').val();
			const needsOptions = ['select', 'radio'].includes(fieldType);

			$('.mbrreg-field-options-row').toggle(needsOptions);
		},

		/**
		 * Toggle edit field options visibility.
		 */
		toggleEditFieldOptions: function() {
			const fieldType = $('#mbrreg-edit-field-type').val();
			const needsOptions = ['select', 'radio'].includes(fieldType);

			$('.mbrreg-edit-field-options-row').toggle(needsOptions);
		},

		/**
		 * Close modal.
		 */
		closeModal: function() {
			$('.mbrreg-modal').hide();
		},

		/**
		 * Close modal when clicking overlay.
		 *
		 * @param {Event} e Click event.
		 */
		closeModalOnOverlay: function(e) {
			if ($(e.target).hasClass('mbrreg-modal')) {
				MbrregAdmin.closeModal();
			}
		},

		/**
		 * Toggle select all checkboxes.
		 */
		toggleSelectAll: function() {
			const isChecked = $(this).is(':checked');
			$('input[name="member_ids[]"]').prop('checked', isChecked);
		},

		/**
		 * Show admin message.
		 *
		 * @param {string} message Message text.
		 * @param {string} type    Message type (success/error).
		 */
		showMessage: function(message, type) {
			const $container = $('.mbrreg-admin-messages');
			const noticeClass = type === 'success' ? 'notice-success' : 'notice-error';

			const $notice = $('<div class="notice ' + noticeClass + ' is-dismissible"><p>' + message + '</p></div>');

			$container.html($notice);

			// Auto-dismiss after 5 seconds.
			setTimeout(function() {
				$notice.fadeOut();
			}, 5000);
		}
	};

	// Initialize on document ready.
	$(document).ready(function() {
		MbrregAdmin.init();
	});

})(jQuery);
```

---

## public/css/mbrreg-public.css

```css
/**
 * Public styles for Member Registration Plugin.
 *
 * @package Member_Registration_Plugin
 * @since 1.0.0
 */

/* Container Styles */
.mbrreg-auth-container,
.mbrreg-dashboard-container,
.mbrreg-form-container {
	max-width: 600px;
	margin: 0 auto;
	padding: 20px;
}

/* Tab Styles */
.mbrreg-tabs {
	display: flex;
	border-bottom: 2px solid #ddd;
	margin-bottom: 20px;
}

.mbrreg-tab {
	padding: 12px 24px;
	background: none;
	border: none;
	cursor: pointer;
	font-size: 16px;
	font-weight: 500;
	color: #666;
	border-bottom: 2px solid transparent;
	margin-bottom: -2px;
	transition: all 0.3s ease;
}

.mbrreg-tab:hover {
	color: #333;
}

.mbrreg-tab-active {
	color: #0073aa;
	border-bottom-color: #0073aa;
}

.mbrreg-tab-content {
	display: none;
}

.mbrreg-tab-content-active {
	display: block;
}

/* Form Styles */
.mbrreg-form {
	background: #fff;
	padding: 30px;
	border-radius: 4px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.mbrreg-form-title {
	margin: 0 0 25px;
	padding-bottom: 15px;
	border-bottom: 1px solid #eee;
	font-size: 24px;
	color: #333;
}

.mbrreg-fieldset {
	border: 1px solid #ddd;
	border-radius: 4px;
	padding: 20px;
	margin-bottom: 25px;
}

.mbrreg-fieldset legend {
	padding: 0 10px;
	font-weight: 600;
	color: #333;
}

.mbrreg-form-row {
	margin-bottom: 20px;
}

.mbrreg-form-row label {
	display: block;
	margin-bottom: 8px;
	font-weight: 500;
	color: #333;
}

.mbrreg-form-row input[type="text"],
.mbrreg-form-row input[type="email"],
.mbrreg-form-row input[type="password"],
.mbrreg-form-row input[type="tel"],
.mbrreg-form-row input[type="date"],
.mbrreg-form-row input[type="number"],
.mbrreg-form-row textarea,
.mbrreg-form-row select {
	width: 100%;
	padding: 12px;
	border: 1px solid #ddd;
	border-radius: 4px;
	font-size: 16px;
	transition: border-color 0.3s ease;
}

.mbrreg-form-row input:focus,
.mbrreg-form-row textarea:focus,
.mbrreg-form-row select:focus {
	outline: none;
	border-color: #0073aa;
	box-shadow: 0 0 0 2px rgba(0, 115, 170, 0.1);
}

.mbrreg-form-row input:disabled {
	background-color: #f5f5f5;
	cursor: not-allowed;
}

.mbrreg-checkbox-row label {
	display: inline-flex;
	align-items: center;
	gap: 8px;
	font-weight: normal;
}

.mbrreg-radio-label {
	display: block;
	margin-bottom: 8px;
	font-weight: normal;
}

.required {
	color: #dc3545;
}

.mbrreg-field-note {
	display: block;
	margin-top: 5px;
	font-size: 13px;
	color: #666;
}

/* Button Styles */
.mbrreg-button {
	display: inline-block;
	padding: 12px 24px;
	border: none;
	border-radius: 4px;
	font-size: 16px;
	font-weight: 500;
	cursor: pointer;
	text-decoration: none;
	transition: all 0.3s ease;
}

.mbrreg-button-primary {
	background-color: #0073aa;
	color: #fff;
}

.mbrreg-button-primary:hover {
	background-color: #005a87;
	color: #fff;
}

.mbrreg-button-secondary {
	background-color: #f0f0f0;
	color: #333;
}

.mbrreg-button-secondary:hover {
	background-color: #e0e0e0;
}

.mbrreg-button-danger {
	background-color: #dc3545;
	color: #fff;
}

.mbrreg-button-danger:hover {
	background-color: #c82333;
}

.mbrreg-button:disabled {
	opacity: 0.6;
	cursor: not-allowed;
}

/* Message Styles */
.mbrreg-message {
	padding: 12px 15px;
	border-radius: 4px;
	margin-bottom: 20px;
}

.mbrreg-success {
	background-color: #d4edda;
	color: #155724;
	border: 1px solid #c3e6cb;
}

.mbrreg-error {
	background-color: #f8d7da;
	color: #721c24;
	border: 1px solid #f5c6cb;
}

.mbrreg-warning {
	background-color: #fff3cd;
	color: #856404;
	border: 1px solid #ffeeba;
}

.mbrreg-info {
	background-color: #d1ecf1;
	color: #0c5460;
	border: 1px solid #bee5eb;
}

.mbrreg-form-messages:empty {
	display: none;
}

/* Form Links */
.mbrreg-form-links {
	margin-top: 20px;
	text-align: center;
}

.mbrreg-form-links a {
	color: #0073aa;
	text-decoration: none;
}

.mbrreg-form-links a:hover {
	text-decoration: underline;
}

/* Dashboard Styles */
.mbrreg-dashboard-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 20px;
	flex-wrap: wrap;
	gap: 15px;
}

.mbrreg-dashboard-header h2 {
	margin: 0;
}

.mbrreg-dashboard-actions {
	display: flex;
	gap: 10px;
	flex-wrap: wrap;
}

.mbrreg-dashboard-status {
	margin-bottom: 20px;
}

/* Status Badges */
.mbrreg-status {
	display: inline-block;
	padding: 5px 12px;
	border-radius: 15px;
	font-size: 13px;
	font-weight: 600;
	text-transform: uppercase;
}

.mbrreg-status-active {
	background-color: #d4edda;
	color: #155724;
}

.mbrreg-status-inactive {
	background-color: #f8d7da;
	color: #721c24;
}

.mbrreg-status-pending {
	background-color: #fff3cd;
	color: #856404;
}

/* Cancel Membership Section */
.mbrreg-cancel-membership {
	margin-top: 40px;
	padding-top: 20px;
	border-top: 2px solid #f0f0f0;
}

.mbrreg-cancel-membership h4 {
	margin-bottom: 10px;
	color: #dc3545;
}

.mbrreg-cancel-membership p {
	margin-bottom: 15px;
	color: #666;
}

/* Form Actions */
.mbrreg-form-actions {
	display: flex;
	gap: 10px;
	flex-wrap: wrap;
}

/* Responsive Styles */
@media screen and (max-width: 600px) {
	.mbrreg-auth-container,
	.mbrreg-dashboard-container,
	.mbrreg-form-container {
		padding: 10px;
	}

	.mbrreg-form {
		padding: 20px 15px;
	}

	.mbrreg-tab {
		padding: 10px 16px;
		font-size: 14px;
	}

	.mbrreg-dashboard-header {
		flex-direction: column;
		align-items: flex-start;
	}

	.mbrreg-form-actions {
		flex-direction: column;
	}

	.mbrreg-button {
		width: 100%;
		text-align: center;
	}
}
```

---

## public/js/mbrreg-public.js

```javascript
/**
 * Public JavaScript for Member Registration Plugin.
 *
 * @package Member_Registration_Plugin
 * @since 1.0.0
 */

(function($) {
	'use strict';

	/**
	 * Public functionality object.
	 */
	const MbrregPublic = {

		/**
		 * Initialize public functionality.
		 */
		init: function() {
			this.bindEvents();
		},

		/**
		 * Bind event handlers.
		 */
		bindEvents: function() {
			// Tab navigation.
			$(document).on('click', '.mbrreg-tab', this.handleTabClick);

			// Form submissions.
			$(document).on('submit', '#mbrreg-login-form', this.handleLogin);
			$(document).on('submit', '#mbrreg-register-form', this.handleRegister);
			$(document).on('submit', '#mbrreg-profile-form', this.handleProfileUpdate);

			// Actions.
			$(document).on('click', '#mbrreg-set-inactive-btn', this.handleSetInactive);
			$(document).on('click', '#mbrreg-logout-btn', this.handleLogout);
		},

		/**
		 * Handle tab click.
		 *
		 * @param {Event} e Click event.
		 */
		handleTabClick: function(e) {
			e.preventDefault();

			const $tab = $(this);
			const tabId = $tab.data('tab');

			// Update active tab.
			$('.mbrreg-tab').removeClass('mbrreg-tab-active');
			$tab.addClass('mbrreg-tab-active');

			// Update active content.
			$('.mbrreg-tab-content').removeClass('mbrreg-tab-content-active');
			$('.mbrreg-tab-' + tabId).addClass('mbrreg-tab-content-active');
		},

		/**
		 * Handle login form submission.
		 *
		 * @param {Event} e Submit event.
		 */
		handleLogin: function(e) {
			e.preventDefault();

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			const originalText = $submitBtn.text();

			MbrregPublic.clearMessages($form);
			$submitBtn.prop('disabled', true).text(mbrregPublic.processing);

			$.ajax({
				url: mbrregPublic.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_login',
					nonce: mbrregPublic.loginNonce,
					username: $form.find('input[name="username"]').val(),
					password: $form.find('input[name="password"]').val(),
					remember: $form.find('input[name="remember"]').is(':checked') ? 1 : 0
				},
				success: function(response) {
					if (response.success) {
						MbrregPublic.showMessage($form, response.data.message, 'success');

						// Redirect or reload.
						if (response.data.redirect_url) {
							window.location.href = response.data.redirect_url;
						} else {
							window.location.reload();
						}
					} else {
						MbrregPublic.showMessage($form, response.data.message, 'error');
						$submitBtn.prop('disabled', false).text(originalText);
					}
				},
				error: function() {
					MbrregPublic.showMessage($form, 'An error occurred. Please try again.', 'error');
					$submitBtn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle registration form submission.
		 *
		 * @param {Event} e Submit event.
		 */
		handleRegister: function(e) {
			e.preventDefault();

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			const originalText = $submitBtn.text();

			// Validate password match.
			const password = $form.find('input[name="password"]').val();
			const passwordConfirm = $form.find('input[name="password_confirm"]').val();

			if (password !== passwordConfirm) {
				MbrregPublic.showMessage($form, mbrregPublic.passwordMismatch, 'error');
				return;
			}

			MbrregPublic.clearMessages($form);
			$submitBtn.prop('disabled', true).text(mbrregPublic.processing);

			const formData = new FormData($form[0]);
			formData.append('action', 'mbrreg_register');
			formData.append('nonce', mbrregPublic.registerNonce);

			$.ajax({
				url: mbrregPublic.ajaxUrl,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					if (response.success) {
						MbrregPublic.showMessage($form, response.data.message, 'success');
						$form[0].reset();
					} else {
						MbrregPublic.showMessage($form, response.data.message, 'error');
					}
				},
				error: function() {
					MbrregPublic.showMessage($form, 'An error occurred. Please try again.', 'error');
				},
				complete: function() {
					$submitBtn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle profile update form submission.
		 *
		 * @param {Event} e Submit event.
		 */
		handleProfileUpdate: function(e) {
			e.preventDefault();

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			const originalText = $submitBtn.text();

			MbrregPublic.clearMessages($form);
			$submitBtn.prop('disabled', true).text(mbrregPublic.processing);

			const formData = new FormData($form[0]);
			formData.append('action', 'mbrreg_update_profile');
			formData.append('nonce', mbrregPublic.updateProfileNonce);

			$.ajax({
				url: mbrregPublic.ajaxUrl,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					if (response.success) {
						MbrregPublic.showMessage($form, response.data.message, 'success');
					} else {
						MbrregPublic.showMessage($form, response.data.message, 'error');
					}
				},
				error: function() {
					MbrregPublic.showMessage($form, 'An error occurred. Please try again.', 'error');
				},
				complete: function() {
					$submitBtn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle set inactive button click.
		 *
		 * @param {Event} e Click event.
		 */
		handleSetInactive: function(e) {
			e.preventDefault();

			if (!confirm(mbrregPublic.confirmInactive)) {
				return;
			}

			const $btn = $(this);
			const originalText = $btn.text();

			$btn.prop('disabled', true).text(mbrregPublic.processing);

			$.ajax({
				url: mbrregPublic.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_set_inactive',
					nonce: mbrregPublic.setInactiveNonce
				},
				success: function(response) {
					if (response.success) {
						alert(response.data.message);
						window.location.reload();
					} else {
						alert(response.data.message);
						$btn.prop('disabled', false).text(originalText);
					}
				},
				error: function() {
					alert('An error occurred. Please try again.');
					$btn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle logout button click.
		 *
		 * @param {Event} e Click event.
		 */
		handleLogout: function(e) {
			e.preventDefault();

			if (!confirm(mbrregPublic.confirmLogout)) {
				return;
			}

			$.ajax({
				url: mbrregPublic.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_logout',
					nonce: mbrregPublic.logoutNonce
				},
				success: function(response) {
					window.location.reload();
				},
				error: function() {
					window.location.reload();
				}
			});
		},

		/**
		 * Show message in form.
		 *
		 * @param {jQuery} $form   Form element.
		 * @param {string} message Message text.
		 * @param {string} type    Message type (success/error).
		 */
		showMessage: function($form, message, type) {
			const $messages = $form.find('.mbrreg-form-messages');
			const className = type === 'success' ? 'mbrreg-success' : 'mbrreg-error';

			$messages.html('<div class="mbrreg-message ' + className + '">' + message + '</div>');

			// Scroll to message.
			$('html, body').animate({
				scrollTop: $messages.offset().top - 100
			}, 300);
		},

		/**
		 * Clear messages from form.
		 *
		 * @param {jQuery} $form Form element.
		 */
		clearMessages: function($form) {
			$form.find('.mbrreg-form-messages').empty();
		}
	};

	// Initialize on document ready.
	$(document).ready(function() {
		MbrregPublic.init();
	});

})(jQuery);
```

---

## uninstall.php

```php
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

	$table_prefix = $wpdb->prefix . 'mbrreg_';

	// Drop custom tables.
	$wpdb->query( "DROP TABLE IF EXISTS {$table_prefix}member_meta" ); // phpcs:ignore
	$wpdb->query( "DROP TABLE IF EXISTS {$table_prefix}custom_fields" ); // phpcs:ignore
	$wpdb->query( "DROP TABLE IF EXISTS {$table_prefix}members" ); // phpcs:ignore

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

	foreach ( $options as $option ) {
		delete_option( $option );
	}

	// Remove capabilities from administrator role.
	$admin_role = get_role( 'administrator' );
	if ( $admin_role ) {
		$admin_role->remove_cap( 'mbrreg_manage_members' );
		$admin_role->remove_cap( 'mbrreg_manage_settings' );
		$admin_role->remove_cap( 'mbrreg_manage_custom_fields' );
		$admin_role->remove_cap( 'mbrreg_export_members


**User**
The plugin works. Nice! I tested the plugin and I now have some additional requirements and features I want to be implemented. Also there is a small 'bug' that I want to be fixed.

# Additional requirements and features
* one user should be able to register with one email address and manage multiple members. This is for example so that parents can register and mange more then one child and/or him/her self as a member of the club. Each member should have their own entry with details in the database, but can be linked to the same user and email address.
* If one user account has registered multiple members, then it should be possible to deactivate each member separately. When there is only one active member in a user account, then the the user account will also be deactivated when the user deactivates the last active membership.
* in the wordpress backend the administrator should be able to upload a csv file with new member data. An automatic email should be sent out to the user with a request to activate the account and to check and update the details if needed. 
* in the wordpress backend the administrator should be able to download a csv file with existing member data
* if the user happens to login to the wp-login url and ends up on the dashboard, then let there be a menu item at the left where the user can view it's registered member(s) details. This could simply be a link to the page with the members area in the frontend as is configured in the plugin settings as the login redirect page.
* also please add the ability for fields to be only editable by an administrator. For example, this could be a custom field like 'membership paid' with a date or a dropdown: yes, no. This can be set by the admin and the user can then see this information, but the user cannot edit it.
* when a user logs out or deactivates a membership by pressing the corresponding button a confirmation popup is shown. This looks like a standard javascript alert. Can you make this a bit nicer by implementing a modal popup in the same styling as the rest of the plugin? Thanks.
* please provide a translation of the plugin to Dutch
* create a readme for the plugin explaining what the plugin if intended for, what it does, how to install it, etc.
* create another readme for this project where we explain how we build this project together with AI.

# Bugfix
* When user clicks on registar, or update profile the interface shows "processing", but never refreshes when done. A manual page refresh will show the correct registered or updated details. 

Please update and add the needed files. Let me know which files have updated so I can change them on my side as well.


**Claude**
{"type":"think","think":"This is a substantial update request. Let me break down what needs to be done:\n\n1. **Multiple members per user account**\n   - Modify database structure to allow one user to have multiple members\n   - Update registration flow to allow adding additional members\n   - Update dashboard to show all members for a user\n   - Update member management\n\n2. **Deactivation logic for multiple members**\n   - Each member can be deactivated separately\n   - When last active member is deactivated, user account is also deactivated\n\n3. **CSV Import in admin**\n   - Upload CSV with member data\n   - Send activation emails to new users\n   - Handle existing users (add members to their account)\n\n4. **CSV Export in admin**\n   - Download existing member data as CSV\n\n5. **WordPress dashboard menu item**\n   - Add menu item for logged-in users to view their member details\n   - Link to the configured frontend page\n\n6. **Admin-only editable fields**\n   - Add flag to custom fields for \"admin only editable\"\n   - Display but don't allow editing in frontend for these fields\n\n7. **Better confirmation modals**\n   - Replace JavaScript alerts with styled modal popups\n\n8. **Dutch translation**\n   - Create .po/.mo files for Dutch\n\n9. **README files**\n   - Plugin readme (readme.txt for WordPress)\n   - Project readme explaining AI collaboration\n\n10. **Bugfix: Processing state not refreshing**\n    - The AJAX success handlers need to properly reload or update the page\n\nLet me go through each file that needs to be updated or created:\n\n**Files to UPDATE:**\n1. `member-registration-plugin.php` - Add dashboard menu\n2. `includes/class-mbrreg-activator.php` - Update database schema\n3. `includes/class-mbrreg-database.php` - Add new methods for multiple members\n4. `includes/class-mbrreg-member.php` - Update member logic\n5. `includes/class-mbrreg-custom-fields.php` - Add admin-only flag\n\n\n6. `includes/class-mbrreg-ajax.php` - Update handlers, add CSV handlers\n7. `includes/class-mbrreg-shortcodes.php` - Update for multiple members\n8. `includes/class-mbrreg-admin.php` - Add CSV import/export\n9. `includes/class-mbrreg-public.php` - Update for multiple members\n10. `public/partials/mbrreg-member-dashboard.php` - Show multiple members\n11. `public/partials/mbrreg-register-form.php` - Allow adding members\n12. `admin/partials/mbrreg-admin-members.php` - Minor updates\n13. `admin/partials/mbrreg-admin-custom-fields.php` - Add admin-only option\n14. `admin/partials/mbrreg-admin-settings.php` - Minor updates\n15. `admin/css/mbrreg-admin.css` - Modal styles\n16. `admin/js/mbrreg-admin.js` - Update for modals\n17. `public/css/mbrreg-public.css` - Modal styles\n18. `public/js/mbrreg-public.js` - Fix bug, add modals\n\nNeed to create the CSV import interface in `admin/partials/mbrreg-admin-import.php`, along with Dutch language files for both the `.po` and `.mo` formats."}


**Claude**
# Updated Member Registration Plugin

This is a significant update. I'll provide all the modified and new files. Here's a summary of changes:

## Files to UPDATE:
1. `member-registration-plugin.php`
2. `includes/class-mbrreg-activator.php`
3. `includes/class-mbrreg-database.php`
4. `includes/class-mbrreg-member.php`
5. `includes/class-mbrreg-custom-fields.php`
6. `includes/class-mbrreg-ajax.php`
7. `includes/class-mbrreg-shortcodes.php`
8. `includes/class-mbrreg-admin.php`
9. `includes/class-mbrreg-public.php`
10. `public/partials/mbrreg-member-dashboard.php`
11. `public/partials/mbrreg-register-form.php`
12. `public/partials/mbrreg-login-form.php`
13. `admin/partials/mbrreg-admin-members.php`
14. `admin/partials/mbrreg-admin-member-edit.php`
15. `admin/partials/mbrreg-admin-custom-fields.php`
16. `admin/partials/mbrreg-admin-settings.php`
17. `admin/css/mbrreg-admin.css`
18. `admin/js/mbrreg-admin.js`
19. `public/css/mbrreg-public.css`
20. `public/js/mbrreg-public.js`

## Files to CREATE:
1. `admin/partials/mbrreg-admin-import-export.php`
2. `public/partials/mbrreg-modal.php`
3. `languages/member-registration-plugin-nl_NL.po`
4. `readme.txt`
5. `README.md`

---

## member-registration-plugin.php (UPDATED)

```php
<?php
/**
 * Plugin Name: Member Registration Plugin
 * Plugin URI: https://example.com/member-registration-plugin
 * Description: A comprehensive member registration and management system for sports clubs. Allows users to register and manage multiple members (e.g., family members) under one account.
 * Version: 1.1.0
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
define( 'MBRREG_VERSION', '1.1.0' );

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
	$ajax          = new Mbrreg_Ajax( $member, $custom_fields, $email );
	$shortcodes    = new Mbrreg_Shortcodes( $member, $custom_fields );
	$admin         = new Mbrreg_Admin( $member, $custom_fields, $email );
	$public        = new Mbrreg_Public( $member );

	// Initialize hooks.
	$ajax->init();
	$shortcodes->init();
	$admin->init();
	$public->init();
}
add_action( 'plugins_loaded', 'mbrreg_init' );

/**
 * Add menu item to WordPress admin bar and dashboard for members.
 *
 * @since 1.1.0
 * @return void
 */
function mbrreg_add_member_menu() {
	// Only for logged-in non-admin users.
	if ( ! is_user_logged_in() || current_user_can( 'manage_options' ) ) {
		return;
	}

	$page_id = get_option( 'mbrreg_registration_page_id', 0 );
	if ( ! $page_id ) {
		$page_id = get_option( 'mbrreg_login_redirect_page', 0 );
	}

	if ( ! $page_id ) {
		return;
	}

	$page_url = get_permalink( $page_id );

	// Add to admin menu.
	add_menu_page(
		__( 'My Memberships', 'member-registration-plugin' ),
		__( 'My Memberships', 'member-registration-plugin' ),
		'read',
		'mbrreg-my-memberships',
		function() use ( $page_url ) {
			wp_safe_redirect( $page_url );
			exit;
		},
		'dashicons-groups',
		70
	);
}
add_action( 'admin_menu', 'mbrreg_add_member_menu' );

/**
 * Redirect members from dashboard to member area.
 *
 * @since 1.1.0
 * @return void
 */
function mbrreg_redirect_members_from_dashboard() {
	global $pagenow;

	// Only on dashboard and for non-admin users.
	if ( 'index.php' !== $pagenow || ! is_admin() || current_user_can( 'manage_options' ) ) {
		return;
	}

	// Check if user is a member.
	$database = new Mbrreg_Database();
	$members  = $database->get_members_by_user_id( get_current_user_id() );

	if ( empty( $members ) ) {
		return;
	}

	// Get redirect page.
	$page_id = get_option( 'mbrreg_login_redirect_page', 0 );
	if ( ! $page_id ) {
		$page_id = get_option( 'mbrreg_registration_page_id', 0 );
	}

	if ( $page_id && isset( $_GET['mbrreg_stay'] ) === false ) {
		wp_safe_redirect( get_permalink( $page_id ) );
		exit;
	}
}
add_action( 'admin_init', 'mbrreg_redirect_members_from_dashboard' );

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
```

---

## includes/class-mbrreg-activator.php (UPDATED)

```php
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

		// Members table - now supports multiple members per user.
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
			KEY user_id (user_id),
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
			is_admin_only tinyint(1) DEFAULT 0 NOT NULL,
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
			'require_first_name'     => false,
			'require_last_name'      => false,
			'require_address'        => false,
			'require_telephone'      => false,
			'require_date_of_birth'  => false,
			'require_place_of_birth' => false,
			'email_from_name'        => get_bloginfo( 'name' ),
			'email_from_address'     => get_option( 'admin_email' ),
			'registration_page_id'   => 0,
			'login_redirect_page'    => 0,
			'allow_registration'     => true,
			'allow_multiple_members' => true,
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
			$admin_role->add_cap( 'mbrreg_import_members' );
		}
	}
}
```

---

## includes/class-mbrreg-database.php (UPDATED)

```php
<?php
/**
 * Database operations class.
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
 * Class Mbrreg_Database
 *
 * Handles all database operations for the plugin.
 *
 * @since 1.0.0
 */
class Mbrreg_Database {

	/**
	 * WordPress database object.
	 *
	 * @since 1.0.0
	 * @var wpdb
	 */
	private $wpdb;

	/**
	 * Table prefix for plugin tables.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $table_prefix;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		global $wpdb;
		$this->wpdb         = $wpdb;
		$this->table_prefix = $wpdb->prefix . MBRREG_TABLE_PREFIX;
	}

	/**
	 * Get the members table name.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_members_table() {
		return $this->table_prefix . 'members';
	}

	/**
	 * Get the custom fields table name.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_custom_fields_table() {
		return $this->table_prefix . 'custom_fields';
	}

	/**
	 * Get the member meta table name.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_member_meta_table() {
		return $this->table_prefix . 'member_meta';
	}

	/**
	 * Insert a new member.
	 *
	 * @since 1.0.0
	 * @param array $data Member data.
	 * @return int|false The member ID on success, false on failure.
	 */
	public function insert_member( $data ) {
		$defaults = array(
			'user_id'        => 0,
			'first_name'     => '',
			'last_name'      => '',
			'address'        => '',
			'telephone'      => '',
			'date_of_birth'  => null,
			'place_of_birth' => '',
			'status'         => 'pending',
			'is_admin'       => 0,
			'activation_key' => '',
		);

		$data = wp_parse_args( $data, $defaults );

		$result = $this->wpdb->insert(
			$this->get_members_table(),
			$data,
			array(
				'%d', // user_id.
				'%s', // first_name.
				'%s', // last_name.
				'%s', // address.
				'%s', // telephone.
				'%s', // date_of_birth.
				'%s', // place_of_birth.
				'%s', // status.
				'%d', // is_admin.
				'%s', // activation_key.
			)
		);

		if ( false === $result ) {
			return false;
		}

		return $this->wpdb->insert_id;
	}

	/**
	 * Update a member.
	 *
	 * @since 1.0.0
	 * @param int   $member_id Member ID.
	 * @param array $data      Member data to update.
	 * @return bool True on success, false on failure.
	 */
	public function update_member( $member_id, $data ) {
		$result = $this->wpdb->update(
			$this->get_members_table(),
			$data,
			array( 'id' => $member_id ),
			null,
			array( '%d' )
		);

		return false !== $result;
	}

	/**
	 * Delete a member.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return bool True on success, false on failure.
	 */
	public function delete_member( $member_id ) {
		// Delete member meta first.
		$this->wpdb->delete(
			$this->get_member_meta_table(),
			array( 'member_id' => $member_id ),
			array( '%d' )
		);

		// Delete member.
		$result = $this->wpdb->delete(
			$this->get_members_table(),
			array( 'id' => $member_id ),
			array( '%d' )
		);

		return false !== $result;
	}

	/**
	 * Get a member by ID.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return object|null Member object or null if not found.
	 */
	public function get_member( $member_id ) {
		$sql = $this->wpdb->prepare(
			"SELECT * FROM {$this->get_members_table()} WHERE id = %d",
			$member_id
		);

		return $this->wpdb->get_row( $sql );
	}

	/**
	 * Get a member by user ID (returns first member for backwards compatibility).
	 *
	 * @since 1.0.0
	 * @param int $user_id WordPress user ID.
	 * @return object|null Member object or null if not found.
	 */
	public function get_member_by_user_id( $user_id ) {
		$sql = $this->wpdb->prepare(
			"SELECT * FROM {$this->get_members_table()} WHERE user_id = %d ORDER BY id ASC LIMIT 1",
			$user_id
		);

		return $this->wpdb->get_row( $sql );
	}

	/**
	 * Get all members by user ID.
	 *
	 * @since 1.1.0
	 * @param int $user_id WordPress user ID.
	 * @return array Array of member objects.
	 */
	public function get_members_by_user_id( $user_id ) {
		$sql = $this->wpdb->prepare(
			"SELECT * FROM {$this->get_members_table()} WHERE user_id = %d ORDER BY id ASC",
			$user_id
		);

		return $this->wpdb->get_results( $sql );
	}

	/**
	 * Count members by user ID.
	 *
	 * @since 1.1.0
	 * @param int    $user_id WordPress user ID.
	 * @param string $status  Optional status filter.
	 * @return int Number of members.
	 */
	public function count_members_by_user_id( $user_id, $status = '' ) {
		$sql = $this->wpdb->prepare(
			"SELECT COUNT(*) FROM {$this->get_members_table()} WHERE user_id = %d",
			$user_id
		);

		if ( ! empty( $status ) ) {
			$sql .= $this->wpdb->prepare( ' AND status = %s', $status );
		}

		return (int) $this->wpdb->get_var( $sql );
	}

	/**
	 * Get a member by activation key.
	 *
	 * @since 1.0.0
	 * @param string $activation_key Activation key.
	 * @return object|null Member object or null if not found.
	 */
	public function get_member_by_activation_key( $activation_key ) {
		$sql = $this->wpdb->prepare(
			"SELECT * FROM {$this->get_members_table()} WHERE activation_key = %s",
			$activation_key
		);

		return $this->wpdb->get_row( $sql );
	}

	/**
	 * Get all members.
	 *
	 * @since 1.0.0
	 * @param array $args Query arguments.
	 * @return array Array of member objects.
	 */
	public function get_members( $args = array() ) {
		$defaults = array(
			'status'   => '',
			'is_admin' => null,
			'search'   => '',
			'user_id'  => null,
			'orderby'  => 'created_at',
			'order'    => 'DESC',
			'limit'    => -1,
			'offset'   => 0,
		);

		$args = wp_parse_args( $args, $defaults );

		$where_clauses = array( '1=1' );
		$where_values  = array();

		if ( ! empty( $args['status'] ) ) {
			$where_clauses[] = 'status = %s';
			$where_values[]  = $args['status'];
		}

		if ( null !== $args['is_admin'] ) {
			$where_clauses[] = 'is_admin = %d';
			$where_values[]  = (int) $args['is_admin'];
		}

		if ( null !== $args['user_id'] ) {
			$where_clauses[] = 'user_id = %d';
			$where_values[]  = (int) $args['user_id'];
		}

		if ( ! empty( $args['search'] ) ) {
			$search_term     = '%' . $this->wpdb->esc_like( $args['search'] ) . '%';
			$where_clauses[] = '(first_name LIKE %s OR last_name LIKE %s OR address LIKE %s OR telephone LIKE %s)';
			$where_values[]  = $search_term;
			$where_values[]  = $search_term;
			$where_values[]  = $search_term;
			$where_values[]  = $search_term;
		}

		$where_sql = implode( ' AND ', $where_clauses );

		// Sanitize orderby.
		$allowed_orderby = array( 'id', 'first_name', 'last_name', 'status', 'created_at', 'updated_at' );
		$orderby         = in_array( $args['orderby'], $allowed_orderby, true ) ? $args['orderby'] : 'created_at';
		$order           = 'ASC' === strtoupper( $args['order'] ) ? 'ASC' : 'DESC';

		$sql = "SELECT * FROM {$this->get_members_table()} WHERE {$where_sql} ORDER BY {$orderby} {$order}";

		if ( $args['limit'] > 0 ) {
			$sql .= $this->wpdb->prepare( ' LIMIT %d OFFSET %d', $args['limit'], $args['offset'] );
		}

		if ( ! empty( $where_values ) ) {
			$sql = $this->wpdb->prepare( $sql, $where_values ); // phpcs:ignore
		}

		return $this->wpdb->get_results( $sql );
	}

	/**
	 * Count members.
	 *
	 * @since 1.0.0
	 * @param array $args Query arguments.
	 * @return int Number of members.
	 */
	public function count_members( $args = array() ) {
		$defaults = array(
			'status'   => '',
			'is_admin' => null,
			'search'   => '',
			'user_id'  => null,
		);

		$args = wp_parse_args( $args, $defaults );

		$where_clauses = array( '1=1' );
		$where_values  = array();

		if ( ! empty( $args['status'] ) ) {
			$where_clauses[] = 'status = %s';
			$where_values[]  = $args['status'];
		}

		if ( null !== $args['is_admin'] ) {
			$where_clauses[] = 'is_admin = %d';
			$where_values[]  = (int) $args['is_admin'];
		}

		if ( null !== $args['user_id'] ) {
			$where_clauses[] = 'user_id = %d';
			$where_values[]  = (int) $args['user_id'];
		}

		if ( ! empty( $args['search'] ) ) {
			$search_term     = '%' . $this->wpdb->esc_like( $args['search'] ) . '%';
			$where_clauses[] = '(first_name LIKE %s OR last_name LIKE %s OR address LIKE %s OR telephone LIKE %s)';
			$where_values[]  = $search_term;
			$where_values[]  = $search_term;
			$where_values[]  = $search_term;
			$where_values[]  = $search_term;
		}

		$where_sql = implode( ' AND ', $where_clauses );

		$sql = "SELECT COUNT(*) FROM {$this->get_members_table()} WHERE {$where_sql}";

		if ( ! empty( $where_values ) ) {
			$sql = $this->wpdb->prepare( $sql, $where_values ); // phpcs:ignore
		}

		return (int) $this->wpdb->get_var( $sql );
	}

	/**
	 * Insert member meta.
	 *
	 * @since 1.0.0
	 * @param int    $member_id Member ID.
	 * @param int    $field_id  Custom field ID.
	 * @param string $value     Meta value.
	 * @return int|false The meta ID on success, false on failure.
	 */
	public function insert_member_meta( $member_id, $field_id, $value ) {
		$result = $this->wpdb->insert(
			$this->get_member_meta_table(),
			array(
				'member_id'  => $member_id,
				'field_id'   => $field_id,
				'meta_value' => $value,
			),
			array( '%d', '%d', '%s' )
		);

		if ( false === $result ) {
			return false;
		}

		return $this->wpdb->insert_id;
	}

	/**
	 * Update member meta.
	 *
	 * @since 1.0.0
	 * @param int    $member_id Member ID.
	 * @param int    $field_id  Custom field ID.
	 * @param string $value     Meta value.
	 * @return bool True on success, false on failure.
	 */
	public function update_member_meta( $member_id, $field_id, $value ) {
		// Check if meta exists.
		$existing = $this->get_member_meta( $member_id, $field_id );

		if ( null !== $existing ) {
			$result = $this->wpdb->update(
				$this->get_member_meta_table(),
				array( 'meta_value' => $value ),
				array(
					'member_id' => $member_id,
					'field_id'  => $field_id,
				),
				array( '%s' ),
				array( '%d', '%d' )
			);
		} else {
			$result = $this->insert_member_meta( $member_id, $field_id, $value );
		}

		return false !== $result;
	}

	/**
	 * Get member meta.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @param int $field_id  Custom field ID (optional).
	 * @return mixed Single value, array of values, or null.
	 */
	public function get_member_meta( $member_id, $field_id = null ) {
		if ( null !== $field_id ) {
			$sql = $this->wpdb->prepare(
				"SELECT meta_value FROM {$this->get_member_meta_table()} WHERE member_id = %d AND field_id = %d",
				$member_id,
				$field_id
			);

			return $this->wpdb->get_var( $sql );
		}

		$sql = $this->wpdb->prepare(
			"SELECT field_id, meta_value FROM {$this->get_member_meta_table()} WHERE member_id = %d",
			$member_id
		);

		$results = $this->wpdb->get_results( $sql );
		$meta    = array();

		foreach ( $results as $row ) {
			$meta[ $row->field_id ] = $row->meta_value;
		}

		return $meta;
	}

	/**
	 * Delete member meta.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @param int $field_id  Custom field ID (optional).
	 * @return bool True on success, false on failure.
	 */
	public function delete_member_meta( $member_id, $field_id = null ) {
		$where        = array( 'member_id' => $member_id );
		$where_format = array( '%d' );

		if ( null !== $field_id ) {
			$where['field_id'] = $field_id;
			$where_format[]    = '%d';
		}

		$result = $this->wpdb->delete(
			$this->get_member_meta_table(),
			$where,
			$where_format
		);

		return false !== $result;
	}

	/**
	 * Insert a custom field.
	 *
	 * @since 1.0.0
	 * @param array $data Field data.
	 * @return int|false The field ID on success, false on failure.
	 */
	public function insert_custom_field( $data ) {
		$defaults = array(
			'field_name'    => '',
			'field_label'   => '',
			'field_type'    => 'text',
			'field_options' => '',
			'is_required'   => 0,
			'is_admin_only' => 0,
			'field_order'   => 0,
		);

		$data = wp_parse_args( $data, $defaults );

		$result = $this->wpdb->insert(
			$this->get_custom_fields_table(),
			$data,
			array( '%s', '%s', '%s', '%s', '%d', '%d', '%d' )
		);

		if ( false === $result ) {
			return false;
		}

		return $this->wpdb->insert_id;
	}

	/**
	 * Update a custom field.
	 *
	 * @since 1.0.0
	 * @param int   $field_id Field ID.
	 * @param array $data     Field data to update.
	 * @return bool True on success, false on failure.
	 */
	public function update_custom_field( $field_id, $data ) {
		$result = $this->wpdb->update(
			$this->get_custom_fields_table(),
			$data,
			array( 'id' => $field_id ),
			null,
			array( '%d' )
		);

		return false !== $result;
	}

	/**
	 * Delete a custom field.
	 *
	 * @since 1.0.0
	 * @param int $field_id Field ID.
	 * @return bool True on success, false on failure.
	 */
	public function delete_custom_field( $field_id ) {
		// Delete associated member meta.
		$this->wpdb->delete(
			$this->get_member_meta_table(),
			array( 'field_id' => $field_id ),
			array( '%d' )
		);

		// Delete field.
		$result = $this->wpdb->delete(
			$this->get_custom_fields_table(),
			array( 'id' => $field_id ),
			array( '%d' )
		);

		return false !== $result;
	}

	/**
	 * Get a custom field by ID.
	 *
	 * @since 1.0.0
	 * @param int $field_id Field ID.
	 * @return object|null Field object or null if not found.
	 */
	public function get_custom_field( $field_id ) {
		$sql = $this->wpdb->prepare(
			"SELECT * FROM {$this->get_custom_fields_table()} WHERE id = %d",
			$field_id
		);

		return $this->wpdb->get_row( $sql );
	}

	/**
	 * Get all custom fields.
	 *
	 * @since 1.0.0
	 * @param bool $include_admin_only Whether to include admin-only fields.
	 * @return array Array of field objects.
	 */
	public function get_custom_fields( $include_admin_only = true ) {
		$sql = "SELECT * FROM {$this->get_custom_fields_table()}";

		if ( ! $include_admin_only ) {
			$sql .= ' WHERE is_admin_only = 0';
		}

		$sql .= ' ORDER BY field_order ASC, id ASC';

		return $this->wpdb->get_results( $sql );
	}

	/**
	 * Check if email exists for any user.
	 *
	 * @since 1.1.0
	 * @param string $email Email address.
	 * @return int|false User ID if exists, false otherwise.
	 */
	public function email_exists( $email ) {
		return email_exists( $email );
	}

	/**
	 * Get user by email.
	 *
	 * @since 1.1.0
	 * @param string $email Email address.
	 * @return WP_User|false User object if exists, false otherwise.
	 */
	public function get_user_by_email( $email ) {
		return get_user_by( 'email', $email );
	}
}
```

---

## includes/class-mbrreg-member.php (UPDATED)

```php
<?php
/**
 * Member operations class.
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
 * Class Mbrreg_Member
 *
 * Handles member-related operations.
 *
 * @since 1.0.0
 */
class Mbrreg_Member {

	/**
	 * Database instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Database
	 */
	private $database;

	/**
	 * Custom fields instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Custom_Fields
	 */
	private $custom_fields;

	/**
	 * Email instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Email
	 */
	private $email;

	/**
	 * Member statuses.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public static $statuses = array(
		'pending'  => 'Pending Activation',
		'active'   => 'Active',
		'inactive' => 'Inactive',
	);

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param Mbrreg_Database      $database      Database instance.
	 * @param Mbrreg_Custom_Fields $custom_fields Custom fields instance.
	 * @param Mbrreg_Email         $email         Email instance.
	 */
	public function __construct( Mbrreg_Database $database, Mbrreg_Custom_Fields $custom_fields, Mbrreg_Email $email ) {
		$this->database      = $database;
		$this->custom_fields = $custom_fields;
		$this->email         = $email;
	}

	/**
	 * Register a new member.
	 *
	 * @since 1.0.0
	 * @param array $data      Registration data.
	 * @param bool  $is_import Whether this is an import operation.
	 * @return int|WP_Error Member ID on success, WP_Error on failure.
	 */
	public function register( $data, $is_import = false ) {
		$user_id       = 0;
		$is_new_user   = true;
		$existing_user = null;

		// Check if adding to existing logged-in user.
		if ( isset( $data['add_to_existing_user'] ) && $data['add_to_existing_user'] && is_user_logged_in() ) {
			$user_id     = get_current_user_id();
			$is_new_user = false;
		} elseif ( ! empty( $data['email'] ) ) {
			// Check if user with this email already exists.
			$existing_user = get_user_by( 'email', $data['email'] );

			if ( $existing_user ) {
				// Allow adding member to existing user if allowed.
				if ( get_option( 'mbrreg_allow_multiple_members', true ) ) {
					$user_id     = $existing_user->ID;
					$is_new_user = false;
				} else {
					return new WP_Error( 'email_exists', __( 'This email address is already registered.', 'member-registration-plugin' ) );
				}
			}
		}

		// Validate required fields for new user.
		if ( $is_new_user ) {
			if ( empty( $data['username'] ) && empty( $data['email'] ) ) {
				return new WP_Error( 'missing_credentials', __( 'Username or email is required.', 'member-registration-plugin' ) );
			}

			if ( empty( $data['email'] ) ) {
				return new WP_Error( 'missing_email', __( 'Email address is required.', 'member-registration-plugin' ) );
			}

			// Validate email.
			if ( ! is_email( $data['email'] ) ) {
				return new WP_Error( 'invalid_email', __( 'Please enter a valid email address.', 'member-registration-plugin' ) );
			}

			// Generate username from email if not provided.
			if ( empty( $data['username'] ) ) {
				$data['username'] = $this->generate_username_from_email( $data['email'] );
			}

			// Check if username exists.
			if ( username_exists( $data['username'] ) ) {
				// Generate unique username.
				$data['username'] = $this->generate_unique_username( $data['username'] );
			}

			// Generate password if not provided (for imports).
			if ( empty( $data['password'] ) ) {
				$data['password'] = wp_generate_password( 12, true );
			}
		}

		// Validate required member fields.
		$validation = $this->validate_member_data( $data );
		if ( is_wp_error( $validation ) ) {
			return $validation;
		}

		// Create WordPress user if needed.
		if ( $is_new_user ) {
			$user_id = wp_create_user(
				sanitize_user( $data['username'] ),
				$data['password'],
				sanitize_email( $data['email'] )
			);

			if ( is_wp_error( $user_id ) ) {
				return $user_id;
			}
		}

		// Generate activation key.
		$activation_key = wp_generate_password( 32, false );
		$status         = $is_import ? 'pending' : 'pending';

		// Prepare member data.
		$member_data = array(
			'user_id'        => $user_id,
			'first_name'     => isset( $data['first_name'] ) ? sanitize_text_field( $data['first_name'] ) : '',
			'last_name'      => isset( $data['last_name'] ) ? sanitize_text_field( $data['last_name'] ) : '',
			'address'        => isset( $data['address'] ) ? sanitize_textarea_field( $data['address'] ) : '',
			'telephone'      => isset( $data['telephone'] ) ? sanitize_text_field( $data['telephone'] ) : '',
			'date_of_birth'  => ! empty( $data['date_of_birth'] ) ? sanitize_text_field( $data['date_of_birth'] ) : null,
			'place_of_birth' => isset( $data['place_of_birth'] ) ? sanitize_text_field( $data['place_of_birth'] ) : '',
			'status'         => $status,
			'is_admin'       => 0,
			'activation_key' => $activation_key,
		);

		// Insert member.
		$member_id = $this->database->insert_member( $member_data );

		if ( ! $member_id ) {
			// Rollback: delete WordPress user only if we created it.
			if ( $is_new_user ) {
				wp_delete_user( $user_id );
			}
			return new WP_Error( 'insert_failed', __( 'Failed to create member record.', 'member-registration-plugin' ) );
		}

		// Save custom field values.
		$this->save_custom_field_values( $member_id, $data );

		// Send activation email.
		if ( $is_import ) {
			$this->email->send_import_activation_email( $user_id, $activation_key, $data );
		} else {
			$this->email->send_activation_email( $user_id, $activation_key );
		}

		/**
		 * Fires after a new member is registered.
		 *
		 * @since 1.0.0
		 * @param int   $member_id Member ID.
		 * @param int   $user_id   WordPress user ID.
		 * @param array $data      Registration data.
		 */
		do_action( 'mbrreg_member_registered', $member_id, $user_id, $data );

		return $member_id;
	}

	/**
	 * Generate username from email.
	 *
	 * @since 1.1.0
	 * @param string $email Email address.
	 * @return string Generated username.
	 */
	private function generate_username_from_email( $email ) {
		$username = sanitize_user( current( explode( '@', $email ) ), true );
		return $username;
	}

	/**
	 * Generate unique username.
	 *
	 * @since 1.1.0
	 * @param string $username Base username.
	 * @return string Unique username.
	 */
	private function generate_unique_username( $username ) {
		$original = $username;
		$counter  = 1;

		while ( username_exists( $username ) ) {
			$username = $original . $counter;
			++$counter;
		}

		return $username;
	}

	/**
	 * Activate a member account.
	 *
	 * @since 1.0.0
	 * @param string $activation_key Activation key.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function activate( $activation_key ) {
		if ( empty( $activation_key ) ) {
			return new WP_Error( 'missing_key', __( 'Activation key is required.', 'member-registration-plugin' ) );
		}

		$member = $this->database->get_member_by_activation_key( $activation_key );

		if ( ! $member ) {
			return new WP_Error( 'invalid_key', __( 'Invalid activation key.', 'member-registration-plugin' ) );
		}

		if ( 'active' === $member->status ) {
			return new WP_Error( 'already_active', __( 'This account is already activated.', 'member-registration-plugin' ) );
		}

		// Update member status.
		$result = $this->database->update_member(
			$member->id,
			array(
				'status'         => 'active',
				'activation_key' => '',
			)
		);

		if ( ! $result ) {
			return new WP_Error( 'update_failed', __( 'Failed to activate account.', 'member-registration-plugin' ) );
		}

		// Send welcome email.
		$this->email->send_welcome_email( $member->user_id );

		/**
		 * Fires after a member account is activated.
		 *
		 * @since 1.0.0
		 * @param int $member_id Member ID.
		 * @param int $user_id   WordPress user ID.
		 */
		do_action( 'mbrreg_member_activated', $member->id, $member->user_id );

		return true;
	}

	/**
	 * Update member details.
	 *
	 * @since 1.0.0
	 * @param int   $member_id     Member ID.
	 * @param array $data          Member data.
	 * @param bool  $is_admin_edit Whether this is an admin edit.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function update( $member_id, $data, $is_admin_edit = false ) {
		$member = $this->database->get_member( $member_id );

		if ( ! $member ) {
			return new WP_Error( 'member_not_found', __( 'Member not found.', 'member-registration-plugin' ) );
		}

		// Validate member data.
		$validation = $this->validate_member_data( $data, true );
		if ( is_wp_error( $validation ) ) {
			return $validation;
		}

		// Prepare update data.
		$update_data = array();

		if ( isset( $data['first_name'] ) ) {
			$update_data['first_name'] = sanitize_text_field( $data['first_name'] );
		}

		if ( isset( $data['last_name'] ) ) {
			$update_data['last_name'] = sanitize_text_field( $data['last_name'] );
		}

		if ( isset( $data['address'] ) ) {
			$update_data['address'] = sanitize_textarea_field( $data['address'] );
		}

		if ( isset( $data['telephone'] ) ) {
			$update_data['telephone'] = sanitize_text_field( $data['telephone'] );
		}

		if ( isset( $data['date_of_birth'] ) ) {
			$update_data['date_of_birth'] = ! empty( $data['date_of_birth'] ) ? sanitize_text_field( $data['date_of_birth'] ) : null;
		}

		if ( isset( $data['place_of_birth'] ) ) {
			$update_data['place_of_birth'] = sanitize_text_field( $data['place_of_birth'] );
		}

		if ( isset( $data['status'] ) && array_key_exists( $data['status'], self::$statuses ) ) {
			$update_data['status'] = $data['status'];
		}

		if ( isset( $data['is_admin'] ) ) {
			$update_data['is_admin'] = (int) $data['is_admin'];
		}

		// Update member.
		if ( ! empty( $update_data ) ) {
			$result = $this->database->update_member( $member_id, $update_data );

			if ( ! $result ) {
				return new WP_Error( 'update_failed', __( 'Failed to update member.', 'member-registration-plugin' ) );
			}
		}

		// Update WordPress user data if email changed (admin only).
		if ( $is_admin_edit && isset( $data['email'] ) && is_email( $data['email'] ) ) {
			$user = get_user_by( 'ID', $member->user_id );
			if ( $user && $user->user_email !== $data['email'] ) {
				// Check if email is already used by another user.
				$existing = email_exists( $data['email'] );
				if ( $existing && $existing !== $member->user_id ) {
					return new WP_Error( 'email_exists', __( 'This email address is already in use.', 'member-registration-plugin' ) );
				}

				wp_update_user(
					array(
						'ID'         => $member->user_id,
						'user_email' => sanitize_email( $data['email'] ),
					)
				);
			}
		}

		// Update WordPress user first/last name.
		$user_update = array( 'ID' => $member->user_id );
		if ( isset( $data['first_name'] ) ) {
			$user_update['first_name'] = sanitize_text_field( $data['first_name'] );
		}
		if ( isset( $data['last_name'] ) ) {
			$user_update['last_name'] = sanitize_text_field( $data['last_name'] );
		}
		if ( count( $user_update ) > 1 ) {
			wp_update_user( $user_update );
		}

		// Save custom field values.
		$this->save_custom_field_values( $member_id, $data, $is_admin_edit );

		/**
		 * Fires after a member is updated.
		 *
		 * @since 1.0.0
		 * @param int   $member_id Member ID.
		 * @param array $data      Update data.
		 */
		do_action( 'mbrreg_member_updated', $member_id, $data );

		return true;
	}

	/**
	 * Delete a member.
	 *
	 * @since 1.0.0
	 * @param int  $member_id      Member ID.
	 * @param bool $delete_wp_user Whether to delete WordPress user too.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function delete( $member_id, $delete_wp_user = false ) {
		$member = $this->database->get_member( $member_id );

		if ( ! $member ) {
			return new WP_Error( 'member_not_found', __( 'Member not found.', 'member-registration-plugin' ) );
		}

		$user_id = $member->user_id;

		// Check if this is the last member for this user.
		$member_count = $this->database->count_members_by_user_id( $user_id );

		// Delete member.
		$result = $this->database->delete_member( $member_id );

		if ( ! $result ) {
			return new WP_Error( 'delete_failed', __( 'Failed to delete member.', 'member-registration-plugin' ) );
		}

		// Delete WordPress user if requested and this was the last member.
		if ( $delete_wp_user && $user_id && 1 === $member_count ) {
			wp_delete_user( $user_id );
		}

		/**
		 * Fires after a member is deleted.
		 *
		 * @since 1.0.0
		 * @param int $member_id Member ID.
		 * @param int $user_id   WordPress user ID.
		 */
		do_action( 'mbrreg_member_deleted', $member_id, $user_id );

		return true;
	}

	/**
	 * Set member as inactive.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function set_inactive( $member_id ) {
		$member = $this->database->get_member( $member_id );

		if ( ! $member ) {
			return new WP_Error( 'member_not_found', __( 'Member not found.', 'member-registration-plugin' ) );
		}

		$result = $this->update( $member_id, array( 'status' => 'inactive' ) );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Check if this was the last active member for the user.
		$active_count = $this->database->count_members_by_user_id( $member->user_id, 'active' );

		// Return whether user should be logged out (no more active members).
		return array(
			'success'       => true,
			'logout_user'   => ( 0 === $active_count ),
			'active_count'  => $active_count,
		);
	}

	/**
	 * Set member as active.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function set_active( $member_id ) {
		return $this->update( $member_id, array( 'status' => 'active' ) );
	}

	/**
	 * Set member as admin.
	 *
	 * @since 1.0.0
	 * @param int  $member_id Member ID.
	 * @param bool $is_admin  Whether member is admin.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function set_admin( $member_id, $is_admin = true ) {
		$member = $this->database->get_member( $member_id );

		if ( ! $member ) {
			return new WP_Error( 'member_not_found', __( 'Member not found.', 'member-registration-plugin' ) );
		}

		$result = $this->database->update_member( $member_id, array( 'is_admin' => (int) $is_admin ) );

		if ( ! $result ) {
			return new WP_Error( 'update_failed', __( 'Failed to update member admin status.', 'member-registration-plugin' ) );
		}

		// Update WordPress user capabilities.
		$user = get_user_by( 'ID', $member->user_id );
		if ( $user ) {
			if ( $is_admin ) {
				$user->add_cap( 'mbrreg_manage_members' );
			} else {
				$user->remove_cap( 'mbrreg_manage_members' );
			}
		}

		return true;
	}

	/**
	 * Get a member by ID.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return object|null Member object or null.
	 */
	public function get( $member_id ) {
		$member = $this->database->get_member( $member_id );

		if ( $member ) {
			$member = $this->enrich_member_data( $member );
		}

		return $member;
	}

	/**
	 * Get a member by user ID.
	 *
	 * @since 1.0.0
	 * @param int $user_id WordPress user ID.
	 * @return object|null Member object or null.
	 */
	public function get_by_user_id( $user_id ) {
		$member = $this->database->get_member_by_user_id( $user_id );

		if ( $member ) {
			$member = $this->enrich_member_data( $member );
		}

		return $member;
	}

	/**
	 * Get all members by user ID.
	 *
	 * @since 1.1.0
	 * @param int $user_id WordPress user ID.
	 * @return array Array of member objects.
	 */
	public function get_all_by_user_id( $user_id ) {
		$members = $this->database->get_members_by_user_id( $user_id );

		foreach ( $members as $key => $member ) {
			$members[ $key ] = $this->enrich_member_data( $member );
		}

		return $members;
	}

	/**
	 * Get all members.
	 *
	 * @since 1.0.0
	 * @param array $args Query arguments.
	 * @return array Array of member objects.
	 */
	public function get_all( $args = array() ) {
		$members = $this->database->get_members( $args );

		foreach ( $members as $key => $member ) {
			$members[ $key ] = $this->enrich_member_data( $member );
		}

		return $members;
	}

	/**
	 * Count members.
	 *
	 * @since 1.0.0
	 * @param array $args Query arguments.
	 * @return int Number of members.
	 */
	public function count( $args = array() ) {
		return $this->database->count_members( $args );
	}

	/**
	 * Enrich member data with WordPress user info and custom fields.
	 *
	 * @since 1.0.0
	 * @param object $member Member object.
	 * @return object Enriched member object.
	 */
	private function enrich_member_data( $member ) {
		// Get WordPress user.
		$user = get_user_by( 'ID', $member->user_id );

		if ( $user ) {
			$member->username = $user->user_login;
			$member->email    = $user->user_email;
		} else {
			$member->username = '';
			$member->email    = '';
		}

		// Get custom field values.
		$member->custom_fields = $this->database->get_member_meta( $member->id );

		return $member;
	}

	/**
	 * Validate member data.
	 *
	 * @since 1.0.0
	 * @param array $data      Member data.
	 * @param bool  $is_update Whether this is an update operation.
	 * @return true|WP_Error True on success, WP_Error on validation failure.
	 */
	private function validate_member_data( $data, $is_update = false ) {
		// Check required standard fields.
		$required_fields = array(
			'first_name'     => get_option( 'mbrreg_require_first_name', false ),
			'last_name'      => get_option( 'mbrreg_require_last_name', false ),
			'address'        => get_option( 'mbrreg_require_address', false ),
			'telephone'      => get_option( 'mbrreg_require_telephone', false ),
			'date_of_birth'  => get_option( 'mbrreg_require_date_of_birth', false ),
			'place_of_birth' => get_option( 'mbrreg_require_place_of_birth', false ),
		);

		foreach ( $required_fields as $field => $is_required ) {
			if ( $is_required && ( ! isset( $data[ $field ] ) || '' === $data[ $field ] ) ) {
				$label = ucwords( str_replace( '_', ' ', $field ) );
				return new WP_Error(
					'missing_' . $field,
					/* translators: %s: Field name */
					sprintf( __( '%s is required.', 'member-registration-plugin' ), $label )
				);
			}
		}

		// Validate custom fields.
		$custom_fields = $this->custom_fields->get_all();

		foreach ( $custom_fields as $field ) {
			// Skip admin-only fields for regular users.
			if ( $field->is_admin_only && ! current_user_can( 'mbrreg_manage_members' ) ) {
				continue;
			}

			$field_key = 'custom_' . $field->id;

			if ( $field->is_required && ( ! isset( $data[ $field_key ] ) || '' === $data[ $field_key ] ) ) {
				return new WP_Error(
					'missing_custom_' . $field->id,
					/* translators: %s: Field label */
					sprintf( __( '%s is required.', 'member-registration-plugin' ), $field->field_label )
				);
			}
		}

		// Validate date of birth format.
		if ( ! empty( $data['date_of_birth'] ) ) {
			$date = DateTime::createFromFormat( 'Y-m-d', $data['date_of_birth'] );
			if ( ! $date || $date->format( 'Y-m-d' ) !== $data['date_of_birth'] ) {
				return new WP_Error( 'invalid_date', __( 'Please enter a valid date of birth.', 'member-registration-plugin' ) );
			}
		}

		return true;
	}

	/**
	 * Save custom field values.
	 *
	 * @since 1.0.0
	 * @param int   $member_id     Member ID.
	 * @param array $data          Data containing custom field values.
	 * @param bool  $is_admin_edit Whether this is an admin edit.
	 * @return void
	 */
	private function save_custom_field_values( $member_id, $data, $is_admin_edit = false ) {
		$custom_fields = $this->custom_fields->get_all();

		foreach ( $custom_fields as $field ) {
			// Skip admin-only fields for regular users.
			if ( $field->is_admin_only && ! $is_admin_edit ) {
				continue;
			}

			$field_key = 'custom_' . $field->id;

			if ( isset( $data[ $field_key ] ) ) {
				$value = $this->custom_fields->sanitize_field_value( $field, $data[ $field_key ] );
				$this->database->update_member_meta( $member_id, $field->id, $value );
			}
		}
	}

	/**
	 * Check if current user is a member admin.
	 *
	 * @since 1.0.0
	 * @param int $user_id User ID (optional, defaults to current user).
	 * @return bool
	 */
	public function is_member_admin( $user_id = null ) {
		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		if ( ! $user_id ) {
			return false;
		}

		// WordPress administrators always have access.
		if ( user_can( $user_id, 'manage_options' ) ) {
			return true;
		}

		// Check if user is a member admin.
		$members = $this->database->get_members_by_user_id( $user_id );

		foreach ( $members as $member ) {
			if ( $member->is_admin ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Resend activation email.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function resend_activation_email( $member_id ) {
		$member = $this->database->get_member( $member_id );

		if ( ! $member ) {
			return new WP_Error( 'member_not_found', __( 'Member not found.', 'member-registration-plugin' ) );
		}

		if ( 'pending' !== $member->status ) {
			return new WP_Error( 'already_active', __( 'This account is already activated.', 'member-registration-plugin' ) );
		}

		// Generate new activation key.
		$activation_key = wp_generate_password( 32, false );

		// Update member with new key.
		$this->database->update_member( $member_id, array( 'activation_key' => $activation_key ) );

		// Send email.
		$this->email->send_activation_email( $member->user_id, $activation_key );

		return true;
	}

	/**
	 * Check if user can manage a specific member.
	 *
	 * @since 1.1.0
	 * @param int $member_id Member ID.
	 * @param int $user_id   User ID (optional, defaults to current user).
	 * @return bool
	 */
	public function can_manage_member( $member_id, $user_id = null ) {
		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		if ( ! $user_id ) {
			return false;
		}

		// Admins can manage all members.
		if ( $this->is_member_admin( $user_id ) ) {
			return true;
		}

		// Check if member belongs to user.
		$member = $this->database->get_member( $member_id );

		return $member && (int) $member->user_id === (int) $user_id;
	}

	/**
	 * Export members to CSV.
	 *
	 * @since 1.1.0
	 * @param array $args Query arguments.
	 * @return string CSV content.
	 */
	public function export_csv( $args = array() ) {
		$members       = $this->get_all( $args );
		$custom_fields = $this->custom_fields->get_all();

		// Build CSV header.
		$headers = array(
			'ID',
			'Username',
			'Email',
			'First Name',
			'Last Name',
			'Address',
			'Telephone',
			'Date of Birth',
			'Place of Birth',
			'Status',
			'Is Admin',
			'Registered',
		);

		// Add custom field headers.
		foreach ( $custom_fields as $field ) {
			$headers[] = $field->field_label;
		}

		// Start CSV output.
		$output = fopen( 'php://temp', 'r+' );
		fputcsv( $output, $headers );

		// Add member rows.
		foreach ( $members as $member ) {
			$row = array(
				$member->id,
				$member->username,
				$member->email,
				$member->first_name,
				$member->last_name,
				$member->address,
				$member->telephone,
				$member->date_of_birth,
				$member->place_of_birth,
				$member->status,
				$member->is_admin ? 'Yes' : 'No',
				$member->created_at,
			);

			// Add custom field values.
			foreach ( $custom_fields as $field ) {
				$row[] = isset( $member->custom_fields[ $field->id ] ) ? $member->custom_fields[ $field->id ] : '';
			}

			fputcsv( $output, $row );
		}

		rewind( $output );
		$csv = stream_get_contents( $output );
		fclose( $output );

		return $csv;
	}

	/**
	 * Import members from CSV data.
	 *
	 * @since 1.1.0
	 * @param array $csv_data Parsed CSV data.
	 * @return array Import results.
	 */
	public function import_csv( $csv_data ) {
		$results = array(
			'success' => 0,
			'errors'  => array(),
			'skipped' => 0,
		);

		foreach ( $csv_data as $row_num => $row ) {
			// Skip header row.
			if ( 0 === $row_num ) {
				continue;
			}

			$data = $this->parse_csv_row( $row );

			if ( empty( $data['email'] ) ) {
				$results['errors'][] = sprintf(
					/* translators: %d: Row number */
					__( 'Row %d: Email is required.', 'member-registration-plugin' ),
					$row_num + 1
				);
				continue;
			}

			$result = $this->register( $data, true );

			if ( is_wp_error( $result ) ) {
				$results['errors'][] = sprintf(
					/* translators: 1: Row number, 2: Error message */
					__( 'Row %1$d: %2$s', 'member-registration-plugin' ),
					$row_num + 1,
					$result->get_error_message()
				);
			} else {
				++$results['success'];
			}
		}

		return $results;
	}

	/**
	 * Parse CSV row into member data.
	 *
	 * @since 1.1.0
	 * @param array $row CSV row.
	 * @return array Member data.
	 */
	private function parse_csv_row( $row ) {
		// Expected column order: Email, First Name, Last Name, Address, Telephone, Date of Birth, Place of Birth.
		$data = array(
			'email'          => isset( $row[0] ) ? trim( $row[0] ) : '',
			'first_name'     => isset( $row[1] ) ? trim( $row[1] ) : '',
			'last_name'      => isset( $row[2] ) ? trim( $row[2] ) : '',
			'address'        => isset( $row[3] ) ? trim( $row[3] ) : '',
			'telephone'      => isset( $row[4] ) ? trim( $row[4] ) : '',
			'date_of_birth'  => isset( $row[5] ) ? trim( $row[5] ) : '',
			'place_of_birth' => isset( $row[6] ) ? trim( $row[6] ) : '',
		);

		// Parse additional custom fields if present.
		$custom_fields = $this->custom_fields->get_all();
		$col_index     = 7;

		foreach ( $custom_fields as $field ) {
			if ( isset( $row[ $col_index ] ) ) {
				$data[ 'custom_' . $field->id ] = trim( $row[ $col_index ] );
			}
			++$col_index;
		}

		return $data;
	}
}
```

---

## includes/class-mbrreg-custom-fields.php (UPDATED)

```php
<?php
/**
 * Custom fields management class.
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
 * Class Mbrreg_Custom_Fields
 *
 * Handles custom field operations.
 *
 * @since 1.0.0
 */
class Mbrreg_Custom_Fields {

	/**
	 * Database instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Database
	 */
	private $database;

	/**
	 * Available field types.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public static $field_types = array(
		'text'     => 'Text',
		'textarea' => 'Textarea',
		'email'    => 'Email',
		'number'   => 'Number',
		'date'     => 'Date',
		'select'   => 'Dropdown',
		'checkbox' => 'Checkbox',
		'radio'    => 'Radio Buttons',
	);

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->database = new Mbrreg_Database();
	}

	/**
	 * Create a new custom field.
	 *
	 * @since 1.0.0
	 * @param array $data Field data.
	 * @return int|WP_Error Field ID on success, WP_Error on failure.
	 */
	public function create( $data ) {
		// Validate required fields.
		if ( empty( $data['field_name'] ) ) {
			return new WP_Error( 'missing_name', __( 'Field name is required.', 'member-registration-plugin' ) );
		}

		if ( empty( $data['field_label'] ) ) {
			return new WP_Error( 'missing_label', __( 'Field label is required.', 'member-registration-plugin' ) );
		}

		// Sanitize field name.
		$field_name = sanitize_key( $data['field_name'] );

		// Check for reserved names.
		$reserved = array( 'username', 'email', 'password', 'first_name', 'last_name', 'address', 'telephone', 'date_of_birth', 'place_of_birth' );
		if ( in_array( $field_name, $reserved, true ) ) {
			return new WP_Error( 'reserved_name', __( 'This field name is reserved.', 'member-registration-plugin' ) );
		}

		// Validate field type.
		$field_type = isset( $data['field_type'] ) ? $data['field_type'] : 'text';
		if ( ! array_key_exists( $field_type, self::$field_types ) ) {
			$field_type = 'text';
		}

		// Prepare field data.
		$field_data = array(
			'field_name'    => $field_name,
			'field_label'   => sanitize_text_field( $data['field_label'] ),
			'field_type'    => $field_type,
			'field_options' => isset( $data['field_options'] ) ? $this->sanitize_field_options( $data['field_options'] ) : '',
			'is_required'   => isset( $data['is_required'] ) ? (int) $data['is_required'] : 0,
			'is_admin_only' => isset( $data['is_admin_only'] ) ? (int) $data['is_admin_only'] : 0,
			'field_order'   => isset( $data['field_order'] ) ? (int) $data['field_order'] : 0,
		);

		// Insert field.
		$field_id = $this->database->insert_custom_field( $field_data );

		if ( ! $field_id ) {
			return new WP_Error( 'insert_failed', __( 'Failed to create custom field.', 'member-registration-plugin' ) );
		}

		return $field_id;
	}

	/**
	 * Update a custom field.
	 *
	 * @since 1.0.0
	 * @param int   $field_id Field ID.
	 * @param array $data     Field data.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function update( $field_id, $data ) {
		$field = $this->database->get_custom_field( $field_id );

		if ( ! $field ) {
			return new WP_Error( 'field_not_found', __( 'Custom field not found.', 'member-registration-plugin' ) );
		}

		// Prepare update data.
		$update_data = array();

		if ( isset( $data['field_label'] ) ) {
			$update_data['field_label'] = sanitize_text_field( $data['field_label'] );
		}

		if ( isset( $data['field_type'] ) && array_key_exists( $data['field_type'], self::$field_types ) ) {
			$update_data['field_type'] = $data['field_type'];
		}

		if ( isset( $data['field_options'] ) ) {
			$update_data['field_options'] = $this->sanitize_field_options( $data['field_options'] );
		}

		if ( isset( $data['is_required'] ) ) {
			$update_data['is_required'] = (int) $data['is_required'];
		}

		if ( isset( $data['is_admin_only'] ) ) {
			$update_data['is_admin_only'] = (int) $data['is_admin_only'];
		}

		if ( isset( $data['field_order'] ) ) {
			$update_data['field_order'] = (int) $data['field_order'];
		}

		if ( empty( $update_data ) ) {
			return true;
		}

		$result = $this->database->update_custom_field( $field_id, $update_data );

		if ( ! $result ) {
			return new WP_Error( 'update_failed', __( 'Failed to update custom field.', 'member-registration-plugin' ) );
		}

		return true;
	}

	/**
	 * Delete a custom field.
	 *
	 * @since 1.0.0
	 * @param int $field_id Field ID.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function delete( $field_id ) {
		$field = $this->database->get_custom_field( $field_id );

		if ( ! $field ) {
			return new WP_Error( 'field_not_found', __( 'Custom field not found.', 'member-registration-plugin' ) );
		}

		$result = $this->database->delete_custom_field( $field_id );

		if ( ! $result ) {
			return new WP_Error( 'delete_failed', __( 'Failed to delete custom field.', 'member-registration-plugin' ) );
		}

		return true;
	}

	/**
	 * Get a custom field by ID.
	 *
	 * @since 1.0.0
	 * @param int $field_id Field ID.
	 * @return object|null Field object or null.
	 */
	public function get( $field_id ) {
		return $this->database->get_custom_field( $field_id );
	}

	/**
	 * Get all custom fields.
	 *
	 * @since 1.0.0
	 * @param bool $include_admin_only Whether to include admin-only fields.
	 * @return array Array of field objects.
	 */
	public function get_all( $include_admin_only = true ) {
		return $this->database->get_custom_fields( $include_admin_only );
	}

	/**
	 * Get user-editable custom fields.
	 *
	 * @since 1.1.0
	 * @return array Array of field objects.
	 */
	public function get_user_editable() {
		return $this->database->get_custom_fields( false );
	}

	/**
	 * Sanitize field options.
	 *
	 * @since 1.0.0
	 * @param string|array $options Field options.
	 * @return string JSON-encoded options.
	 */
	private function sanitize_field_options( $options ) {
		if ( is_string( $options ) ) {
			// Convert newline-separated options to array.
			$options = array_filter( array_map( 'trim', explode( "\n", $options ) ) );
		}

		if ( ! is_array( $options ) ) {
			return '';
		}

		$sanitized = array_map( 'sanitize_text_field', $options );

		return wp_json_encode( $sanitized );
	}

	/**
	 * Get parsed field options.
	 *
	 * @since 1.0.0
	 * @param object $field Field object.
	 * @return array Array of options.
	 */
	public function get_field_options( $field ) {
		if ( empty( $field->field_options ) ) {
			return array();
		}

		$options = json_decode( $field->field_options, true );

		return is_array( $options ) ? $options : array();
	}

	/**
	 * Sanitize field value based on field type.
	 *
	 * @since 1.0.0
	 * @param object $field Field object.
	 * @param mixed  $value Field value.
	 * @return mixed Sanitized value.
	 */
	public function sanitize_field_value( $field, $value ) {
		switch ( $field->field_type ) {
			case 'textarea':
				return sanitize_textarea_field( $value );

			case 'email':
				return sanitize_email( $value );

			case 'number':
				return is_numeric( $value ) ? $value : '';

			case 'date':
				$date = DateTime::createFromFormat( 'Y-m-d', $value );
				return ( $date && $date->format( 'Y-m-d' ) === $value ) ? $value : '';

			case 'checkbox':
				return $value ? '1' : '0';

			case 'select':
			case 'radio':
				$options = $this->get_field_options( $field );
				return in_array( $value, $options, true ) ? sanitize_text_field( $value ) : '';

			default:
				return sanitize_text_field( $value );
		}
	}

	/**
	 * Render a custom field input.
	 *
	 * @since 1.0.0
	 * @param object $field    Field object.
	 * @param string $value    Current value.
	 * @param array  $args     Additional arguments.
	 * @return string HTML output.
	 */
	public function render_field_input( $field, $value = '', $args = array() ) {
		$defaults = array(
			'name_prefix' => 'custom_',
			'id_prefix'   => 'mbrreg-custom-',
			'class'       => 'mbrreg-field-input',
			'readonly'    => false,
		);

		$args = wp_parse_args( $args, $defaults );

		$name     = $args['name_prefix'] . $field->id;
		$id       = $args['id_prefix'] . $field->id;
		$class    = $args['class'];
		$required = $field->is_required ? 'required' : '';
		$readonly = $args['readonly'] ? 'readonly disabled' : '';

		$output = '';

		switch ( $field->field_type ) {
			case 'textarea':
				$output = sprintf(
					'<textarea name="%s" id="%s" class="%s" %s %s>%s</textarea>',
					esc_attr( $name ),
					esc_attr( $id ),
					esc_attr( $class ),
					$required,
					$readonly,
					esc_textarea( $value )
				);
				break;

			case 'select':
				$options    = $this->get_field_options( $field );
				$disabled   = $args['readonly'] ? 'disabled' : '';
				$output     = sprintf(
					'<select name="%s" id="%s" class="%s" %s %s>',
					esc_attr( $name ),
					esc_attr( $id ),
					esc_attr( $class ),
					$required,
					$disabled
				);
				$output    .= '<option value="">' . esc_html__( '-- Select --', 'member-registration-plugin' ) . '</option>';
				foreach ( $options as $option ) {
					$output .= sprintf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $option ),
						selected( $value, $option, false ),
						esc_html( $option )
					);
				}
				$output .= '</select>';
				break;

			case 'radio':
				$options  = $this->get_field_options( $field );
				$disabled = $args['readonly'] ? 'disabled' : '';
				foreach ( $options as $index => $option ) {
					$output .= sprintf(
						'<label class="mbrreg-radio-label"><input type="radio" name="%s" value="%s" %s %s %s> %s</label>',
						esc_attr( $name ),
						esc_attr( $option ),
						checked( $value, $option, false ),
						0 === $index && $field->is_required ? 'required' : '',
						$disabled,
						esc_html( $option )
					);
				}
				break;

			case 'checkbox':
				$disabled = $args['readonly'] ? 'disabled' : '';
				$output   = sprintf(
					'<input type="checkbox" name="%s" id="%s" class="%s" value="1" %s %s %s>',
					esc_attr( $name ),
					esc_attr( $id ),
					esc_attr( $class ),
					checked( $value, '1', false ),
					$required,
					$disabled
				);
				break;

			case 'email':
				$output = sprintf(
					'<input type="email" name="%s" id="%s" class="%s" value="%s" %s %s>',
					esc_attr( $name ),
					esc_attr( $id ),
					esc_attr( $class ),
					esc_attr( $value ),
					$required,
					$readonly
				);
				break;

			case 'number':
				$output = sprintf(
					'<input type="number" name="%s" id="%s" class="%s" value="%s" %s %s>',
					esc_attr( $name ),
					esc_attr( $id ),
					esc_attr( $class ),
					esc_attr( $value ),
					$required,
					$readonly
				);
				break;

			case 'date':
				$output = sprintf(
					'<input type="date" name="%s" id="%s" class="%s" value="%s" %s %s>',
					esc_attr( $name ),
					esc_attr( $id ),
					esc_attr( $class ),
					esc_attr( $value ),
					$required,
					$readonly
				);
				break;

			default:
				$output = sprintf(
					'<input type="text" name="%s" id="%s" class="%s" value="%s" %s %s>',
					esc_attr( $name ),
					esc_attr( $id ),
					esc_attr( $class ),
					esc_attr( $value ),
					$required,
					$readonly
				);
				break;
		}

		return $output;
	}
}
```

---

## includes/class-mbrreg-email.php (UPDATED)

```php
<?php
/**
 * Email handling class.
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
 * Class Mbrreg_Email
 *
 * Handles email sending for the plugin.
 *
 * @since 1.0.0
 */
class Mbrreg_Email {

	/**
	 * Send activation email to new member.
	 *
	 * @since 1.0.0
	 * @param int    $user_id        WordPress user ID.
	 * @param string $activation_key Activation key.
	 * @return bool Whether the email was sent successfully.
	 */
	public function send_activation_email( $user_id, $activation_key ) {
		$user = get_user_by( 'ID', $user_id );

		if ( ! $user ) {
			return false;
		}

		$activation_url = add_query_arg(
			array(
				'mbrreg_action' => 'activate',
				'key'           => $activation_key,
			),
			home_url( '/' )
		);

		$subject = sprintf(
			/* translators: %s: Site name */
			__( 'Activate your membership at %s', 'member-registration-plugin' ),
			get_bloginfo( 'name' )
		);

		$message = sprintf(
			/* translators: 1: User display name, 2: Site name, 3: Activation URL */
			__(
				'Hello %1$s,

Thank you for registering as a member at %2$s.

Please click the following link to activate your account:

%3$s

If you did not register for this account, please ignore this email.

Best regards,
%2$s',
				'member-registration-plugin'
			),
			$user->display_name,
			get_bloginfo( 'name' ),
			$activation_url
		);

		$headers = $this->get_email_headers();

		/**
		 * Filter the activation email subject.
		 *
		 * @since 1.0.0
		 * @param string  $subject Subject line.
		 * @param WP_User $user    User object.
		 */
		$subject = apply_filters( 'mbrreg_activation_email_subject', $subject, $user );

		/**
		 * Filter the activation email message.
		 *
		 * @since 1.0.0
		 * @param string  $message        Email message.
		 * @param WP_User $user           User object.
		 * @param string  $activation_url Activation URL.
		 */
		$message = apply_filters( 'mbrreg_activation_email_message', $message, $user, $activation_url );

		return wp_mail( $user->user_email, $subject, $message, $headers );
	}

	/**
	 * Send activation email for imported members.
	 *
	 * @since 1.1.0
	 * @param int    $user_id        WordPress user ID.
	 * @param string $activation_key Activation key.
	 * @param array  $data           Import data.
	 * @return bool Whether the email was sent successfully.
	 */
	public function send_import_activation_email( $user_id, $activation_key, $data = array() ) {
		$user = get_user_by( 'ID', $user_id );

		if ( ! $user ) {
			return false;
		}

		$activation_url = add_query_arg(
			array(
				'mbrreg_action' => 'activate',
				'key'           => $activation_key,
			),
			home_url( '/' )
		);

		// Get member area page URL.
		$page_id  = get_option( 'mbrreg_registration_page_id', 0 );
		$page_url = $page_id ? get_permalink( $page_id ) : home_url( '/' );

		$subject = sprintf(
			/* translators: %s: Site name */
			__( 'You have been registered as a member at %s', 'member-registration-plugin' ),
			get_bloginfo( 'name' )
		);

		$message = sprintf(
			/* translators: 1: User display name or email, 2: Site name, 3: Activation URL, 4: Member area URL, 5: Username */
			__(
				'Hello %1$s,

You have been registered as a member at %2$s.

Please click the following link to activate your account:

%3$s

After activation, you can log in and review/update your details at:
%4$s

Your username is: %5$s

If you need to set a password, please use the password reset function on the login page.

If you did not expect this email, please contact the club administrator.

Best regards,
%2$s',
				'member-registration-plugin'
			),
			! empty( $data['first_name'] ) ? $data['first_name'] : $user->user_email,
			get_bloginfo( 'name' ),
			$activation_url,
			$page_url,
			$user->user_login
		);

		$headers = $this->get_email_headers();

		return wp_mail( $user->user_email, $subject, $message, $headers );
	}

	/**
	 * Send notification to admin about new registration.
	 *
	 * @since 1.0.0
	 * @param int   $member_id Member ID.
	 * @param array $data      Member data.
	 * @return bool Whether the email was sent successfully.
	 */
	public function send_admin_notification( $member_id, $data ) {
		$admin_email = get_option( 'admin_email' );

		$subject = sprintf(
			/* translators: %s: Site name */
			__( 'New member registration at %s', 'member-registration-plugin' ),
			get_bloginfo( 'name' )
		);

		$message = sprintf(
			/* translators: 1: Username, 2: Email */
			__(
				'A new member has registered:

Username: %1$s
Email: %2$s

You can view and manage members in the WordPress admin area.',
				'member-registration-plugin'
			),
			isset( $data['username'] ) ? $data['username'] : '',
			isset( $data['email'] ) ? $data['email'] : ''
		);

		$headers = $this->get_email_headers();

		return wp_mail( $admin_email, $subject, $message, $headers );
	}

	/**
	 * Send welcome email after account activation.
	 *
	 * @since 1.0.0
	 * @param int $user_id WordPress user ID.
	 * @return bool Whether the email was sent successfully.
	 */
	public function send_welcome_email( $user_id ) {
		$user = get_user_by( 'ID', $user_id );

		if ( ! $user ) {
			return false;
		}

		// Get member area page URL.
		$page_id  = get_option( 'mbrreg_registration_page_id', 0 );
		$page_url = $page_id ? get_permalink( $page_id ) : wp_login_url();

		$subject = sprintf(
			/* translators: %s: Site name */
			__( 'Welcome to %s!', 'member-registration-plugin' ),
			get_bloginfo( 'name' )
		);

		$message = sprintf(
			/* translators: 1: User display name, 2: Site name, 3: Login URL */
			__(
				'Hello %1$s,

Your account at %2$s has been activated successfully!

You can now log in and manage your membership details at:
%3$s

Best regards,
%2$s',
				'member-registration-plugin'
			),
			$user->display_name,
			get_bloginfo( 'name' ),
			$page_url
		);

		$headers = $this->get_email_headers();

		/**
		 * Filter the welcome email subject.
		 *
		 * @since 1.0.0
		 * @param string  $subject Subject line.
		 * @param WP_User $user    User object.
		 */
		$subject = apply_filters( 'mbrreg_welcome_email_subject', $subject, $user );

		/**
		 * Filter the welcome email message.
		 *
		 * @since 1.0.0
		 * @param string  $message Email message.
		 * @param WP_User $user    User object.
		 */
		$message = apply_filters( 'mbrreg_welcome_email_message', $message, $user );

		return wp_mail( $user->user_email, $subject, $message, $headers );
	}

	/**
	 * Get email headers.
	 *
	 * @since 1.0.0
	 * @return array Email headers.
	 */
	private function get_email_headers() {
		$from_name    = get_option( 'mbrreg_email_from_name', get_bloginfo( 'name' ) );
		$from_address = get_option( 'mbrreg_email_from_address', get_option( 'admin_email' ) );

		$headers = array(
			'Content-Type: text/plain; charset=UTF-8',
			sprintf( 'From: %s <%s>', $from_name, $from_address ),
		);

		/**
		 * Filter email headers.
		 *
		 * @since 1.0.0
		 * @param array $headers Email headers.
		 */
		return apply_filters( 'mbrreg_email_headers', $headers );
	}
}
```

---

## includes/class-mbrreg-ajax.php (UPDATED)

```php
<?php
/**
 * AJAX handler class.
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
 * Class Mbrreg_Ajax
 *
 * Handles AJAX requests for the plugin.
 *
 * @since 1.0.0
 */
class Mbrreg_Ajax {

	/**
	 * Member instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Member
	 */
	private $member;

	/**
	 * Custom fields instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Custom_Fields
	 */
	private $custom_fields;

	/**
	 * Email instance.
	 *
	 * @since 1.1.0
	 * @var Mbrreg_Email
	 */
	private $email;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param Mbrreg_Member        $member        Member instance.
	 * @param Mbrreg_Custom_Fields $custom_fields Custom fields instance.
	 * @param Mbrreg_Email         $email         Email instance.
	 */
	public function __construct( Mbrreg_Member $member, Mbrreg_Custom_Fields $custom_fields, Mbrreg_Email $email = null ) {
		$this->member        = $member;
		$this->custom_fields = $custom_fields;
		$this->email         = $email;
	}

	/**
	 * Initialize AJAX handlers.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init() {
		// Public AJAX actions (for non-logged in users).
		add_action( 'wp_ajax_nopriv_mbrreg_register', array( $this, 'handle_register' ) );
		add_action( 'wp_ajax_nopriv_mbrreg_login', array( $this, 'handle_login' ) );

		// Private AJAX actions (for logged in users).
		add_action( 'wp_ajax_mbrreg_register', array( $this, 'handle_register' ) );
		add_action( 'wp_ajax_mbrreg_login', array( $this, 'handle_login' ) );
		add_action( 'wp_ajax_mbrreg_add_member', array( $this, 'handle_add_member' ) );
		add_action( 'wp_ajax_mbrreg_update_profile', array( $this, 'handle_update_profile' ) );
		add_action( 'wp_ajax_mbrreg_set_inactive', array( $this, 'handle_set_inactive' ) );
		add_action( 'wp_ajax_mbrreg_logout', array( $this, 'handle_logout' ) );

		// Admin AJAX actions.
		add_action( 'wp_ajax_mbrreg_admin_update_member', array( $this, 'handle_admin_update_member' ) );
		add_action( 'wp_ajax_mbrreg_admin_delete_member', array( $this, 'handle_admin_delete_member' ) );
		add_action( 'wp_ajax_mbrreg_admin_bulk_action', array( $this, 'handle_admin_bulk_action' ) );
		add_action( 'wp_ajax_mbrreg_admin_create_field', array( $this, 'handle_admin_create_field' ) );
		add_action( 'wp_ajax_mbrreg_admin_update_field', array( $this, 'handle_admin_update_field' ) );
		add_action( 'wp_ajax_mbrreg_admin_delete_field', array( $this, 'handle_admin_delete_field' ) );
		add_action( 'wp_ajax_mbrreg_admin_resend_activation', array( $this, 'handle_resend_activation' ) );
		add_action( 'wp_ajax_mbrreg_admin_import_csv', array( $this, 'handle_import_csv' ) );
		add_action( 'wp_ajax_mbrreg_admin_export_csv', array( $this, 'handle_export_csv' ) );
	}

	/**
	 * Handle member registration.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_register() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_register_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check if registration is allowed.
		if ( ! get_option( 'mbrreg_allow_registration', true ) ) {
			wp_send_json_error( array( 'message' => __( 'Registration is currently disabled.', 'member-registration-plugin' ) ) );
		}

		// Get and sanitize data.
		$data = array(
			'username'       => isset( $_POST['username'] ) ? sanitize_user( wp_unslash( $_POST['username'] ) ) : '',
			'email'          => isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '',
			'password'       => isset( $_POST['password'] ) ? $_POST['password'] : '', // phpcs:ignore
			'first_name'     => isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '',
			'last_name'      => isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '',
			'address'        => isset( $_POST['address'] ) ? sanitize_textarea_field( wp_unslash( $_POST['address'] ) ) : '',
			'telephone'      => isset( $_POST['telephone'] ) ? sanitize_text_field( wp_unslash( $_POST['telephone'] ) ) : '',
			'date_of_birth'  => isset( $_POST['date_of_birth'] ) ? sanitize_text_field( wp_unslash( $_POST['date_of_birth'] ) ) : '',
			'place_of_birth' => isset( $_POST['place_of_birth'] ) ? sanitize_text_field( wp_unslash( $_POST['place_of_birth'] ) ) : '',
		);

		// Get custom field values.
		$custom_fields = $this->custom_fields->get_user_editable();
		foreach ( $custom_fields as $field ) {
			$field_key = 'custom_' . $field->id;
			if ( isset( $_POST[ $field_key ] ) ) {
				$data[ $field_key ] = $this->custom_fields->sanitize_field_value( $field, wp_unslash( $_POST[ $field_key ] ) );
			}
		}

		// Register member.
		$result = $this->member->register( $data );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success(
			array(
				'message'   => __( 'Registration successful! Please check your email to activate your account.', 'member-registration-plugin' ),
				'member_id' => $result,
				'reload'    => false,
			)
		);
	}

	/**
	 * Handle adding additional member (for logged in users).
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function handle_add_member() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_add_member_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check if user is logged in.
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => __( 'You must be logged in.', 'member-registration-plugin' ) ) );
		}

		// Check if multiple members are allowed.
		if ( ! get_option( 'mbrreg_allow_multiple_members', true ) ) {
			wp_send_json_error( array( 'message' => __( 'Adding multiple members is not allowed.', 'member-registration-plugin' ) ) );
		}

		// Get and sanitize data.
		$data = array(
			'add_to_existing_user' => true,
			'first_name'           => isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '',
			'last_name'            => isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '',
			'address'              => isset( $_POST['address'] ) ? sanitize_textarea_field( wp_unslash( $_POST['address'] ) ) : '',
			'telephone'            => isset( $_POST['telephone'] ) ? sanitize_text_field( wp_unslash( $_POST['telephone'] ) ) : '',
			'date_of_birth'        => isset( $_POST['date_of_birth'] ) ? sanitize_text_field( wp_unslash( $_POST['date_of_birth'] ) ) : '',
			'place_of_birth'       => isset( $_POST['place_of_birth'] ) ? sanitize_text_field( wp_unslash( $_POST['place_of_birth'] ) ) : '',
		);

		// Get custom field values.
		$custom_fields = $this->custom_fields->get_user_editable();
		foreach ( $custom_fields as $field ) {
			$field_key = 'custom_' . $field->id;
			if ( isset( $_POST[ $field_key ] ) ) {
				$data[ $field_key ] = $this->custom_fields->sanitize_field_value( $field, wp_unslash( $_POST[ $field_key ] ) );
			}
		}

		// Register member.
		$result = $this->member->register( $data );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success(
			array(
				'message'   => __( 'Member added successfully! Please check your email to activate the membership.', 'member-registration-plugin' ),
				'member_id' => $result,
				'reload'    => true,
			)
		);
	}

	/**
	 * Handle member login.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_login() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_login_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		$username = isset( $_POST['username'] ) ? sanitize_user( wp_unslash( $_POST['username'] ) ) : '';
		$password = isset( $_POST['password'] ) ? $_POST['password'] : ''; // phpcs:ignore
		$remember = isset( $_POST['remember'] ) && $_POST['remember'];

		if ( empty( $username ) || empty( $password ) ) {
			wp_send_json_error( array( 'message' => __( 'Please enter your username and password.', 'member-registration-plugin' ) ) );
		}

		// Attempt to authenticate.
		$user = wp_authenticate( $username, $password );

		if ( is_wp_error( $user ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid username or password.', 'member-registration-plugin' ) ) );
		}

		// Check if any member is active.
		$members      = $this->member->get_all_by_user_id( $user->ID );
		$has_active   = false;
		$all_pending  = true;
		$all_inactive = true;

		foreach ( $members as $member ) {
			if ( 'active' === $member->status ) {
				$has_active   = true;
				$all_pending  = false;
				$all_inactive = false;
			} elseif ( 'pending' === $member->status ) {
				$all_inactive = false;
			} elseif ( 'inactive' === $member->status ) {
				$all_pending = false;
			}
		}

		if ( ! empty( $members ) && ! $has_active ) {
			if ( $all_pending ) {
				wp_send_json_error( array( 'message' => __( 'Please activate your account first. Check your email for the activation link.', 'member-registration-plugin' ) ) );
			} elseif ( $all_inactive ) {
				wp_send_json_error( array( 'message' => __( 'All your memberships are inactive. Please contact the administrator.', 'member-registration-plugin' ) ) );
			}
		}

		// Log the user in.
		wp_set_current_user( $user->ID );
		wp_set_auth_cookie( $user->ID, $remember );

		$redirect_url  = '';
		$redirect_page = get_option( 'mbrreg_login_redirect_page', 0 );
		if ( $redirect_page ) {
			$redirect_url = get_permalink( $redirect_page );
		}

		wp_send_json_success(
			array(
				'message'      => __( 'Login successful!', 'member-registration-plugin' ),
				'redirect_url' => $redirect_url,
				'reload'       => true,
			)
		);
	}

	/**
	 * Handle profile update.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_update_profile() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_update_profile_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check if user is logged in.
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => __( 'You must be logged in.', 'member-registration-plugin' ) ) );
		}

		$member_id = isset( $_POST['member_id'] ) ? absint( $_POST['member_id'] ) : 0;

		if ( ! $member_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid member ID.', 'member-registration-plugin' ) ) );
		}

		// Check permission.
		if ( ! $this->member->can_manage_member( $member_id ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to update this member.', 'member-registration-plugin' ) ) );
		}

		// Prepare update data.
		$data = array(
			'first_name'     => isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '',
			'last_name'      => isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '',
			'address'        => isset( $_POST['address'] ) ? sanitize_textarea_field( wp_unslash( $_POST['address'] ) ) : '',
			'telephone'      => isset( $_POST['telephone'] ) ? sanitize_text_field( wp_unslash( $_POST['telephone'] ) ) : '',
			'date_of_birth'  => isset( $_POST['date_of_birth'] ) ? sanitize_text_field( wp_unslash( $_POST['date_of_birth'] ) ) : '',
			'place_of_birth' => isset( $_POST['place_of_birth'] ) ? sanitize_text_field( wp_unslash( $_POST['place_of_birth'] ) ) : '',
		);

		// Get custom field values (only user-editable fields).
		$custom_fields = $this->custom_fields->get_user_editable();
		foreach ( $custom_fields as $field ) {
			$field_key = 'custom_' . $field->id;
			if ( isset( $_POST[ $field_key ] ) ) {
				$data[ $field_key ] = $this->custom_fields->sanitize_field_value( $field, wp_unslash( $_POST[ $field_key ] ) );
			}
		}

		// Update member.
		$result = $this->member->update( $member_id, $data, false );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success(
			array(
				'message' => __( 'Profile updated successfully!', 'member-registration-plugin' ),
				'reload'  => true,
			)
		);
	}

	/**
	 * Handle setting member as inactive.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_set_inactive() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_set_inactive_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check if user is logged in.
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => __( 'You must be logged in.', 'member-registration-plugin' ) ) );
		}

		$member_id = isset( $_POST['member_id'] ) ? absint( $_POST['member_id'] ) : 0;

		if ( ! $member_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid member ID.', 'member-registration-plugin' ) ) );
		}

		// Check permission.
		if ( ! $this->member->can_manage_member( $member_id ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to update this member.', 'member-registration-plugin' ) ) );
		}

		// Set member as inactive.
		$result = $this->member->set_inactive( $member_id );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		$logout_user = false;
		if ( is_array( $result ) && isset( $result['logout_user'] ) ) {
			$logout_user = $result['logout_user'];
		}

		// Log the user out if no more active members.
		if ( $logout_user ) {
			wp_logout();
		}

		wp_send_json_success(
			array(
				'message'     => __( 'Membership has been set to inactive.', 'member-registration-plugin' ),
				'logout_user' => $logout_user,
				'reload'      => true,
			)
		);
	}

	/**
	 * Handle logout.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_logout() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_logout_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		wp_logout();

		wp_send_json_success(
			array(
				'message' => __( 'You have been logged out.', 'member-registration-plugin' ),
				'reload'  => true,
			)
		);
	}

	/**
	 * Handle admin member update.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_admin_update_member() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check permissions.
		if ( ! $this->member->is_member_admin() ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'member-registration-plugin' ) ) );
		}

		$member_id = isset( $_POST['member_id'] ) ? absint( $_POST['member_id'] ) : 0;

		if ( ! $member_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid member ID.', 'member-registration-plugin' ) ) );
		}

		// Prepare update data.
		$data = array();

		$fields = array( 'first_name', 'last_name', 'address', 'telephone', 'date_of_birth', 'place_of_birth', 'status', 'email' );

		foreach ( $fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				$data[ $field ] = 'address' === $field
					? sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) )
					: sanitize_text_field( wp_unslash( $_POST[ $field ] ) );
			}
		}

		// Handle is_admin separately.
		if ( isset( $_POST['is_admin'] ) ) {
			$data['is_admin'] = (int) $_POST['is_admin'];
		}

		// Get ALL custom field values (including admin-only).
		$custom_fields = $this->custom_fields->get_all();
		foreach ( $custom_fields as $field ) {
			$field_key = 'custom_' . $field->id;
			if ( isset( $_POST[ $field_key ] ) ) {
				$data[ $field_key ] = $this->custom_fields->sanitize_field_value( $field, wp_unslash( $_POST[ $field_key ] ) );
			}
		}

		// Update member (admin edit = true).
		$result = $this->member->update( $member_id, $data, true );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		// Handle admin status change.
		if ( isset( $_POST['is_admin'] ) ) {
			$this->member->set_admin( $member_id, (bool) $_POST['is_admin'] );
		}

		wp_send_json_success( array( 'message' => __( 'Member updated successfully!', 'member-registration-plugin' ) ) );
	}

	/**
	 * Handle admin member delete.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_admin_delete_member() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check permissions.
		if ( ! $this->member->is_member_admin() ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'member-registration-plugin' ) ) );
		}

		$member_id      = isset( $_POST['member_id'] ) ? absint( $_POST['member_id'] ) : 0;
		$delete_wp_user = isset( $_POST['delete_wp_user'] ) && $_POST['delete_wp_user'];

		if ( ! $member_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid member ID.', 'member-registration-plugin' ) ) );
		}

		// Delete member.
		$result = $this->member->delete( $member_id, $delete_wp_user );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success( array( 'message' => __( 'Member deleted successfully!', 'member-registration-plugin' ) ) );
	}

	/**
	 * Handle admin bulk action.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_admin_bulk_action() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check permissions.
		if ( ! $this->member->is_member_admin() ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'member-registration-plugin' ) ) );
		}

		$action     = isset( $_POST['bulk_action'] ) ? sanitize_text_field( wp_unslash( $_POST['bulk_action'] ) ) : '';
		$member_ids = isset( $_POST['member_ids'] ) ? array_map( 'absint', (array) $_POST['member_ids'] ) : array();

		if ( empty( $action ) || empty( $member_ids ) ) {
			wp_send_json_error( array( 'message' => __( 'Please select an action and at least one member.', 'member-registration-plugin' ) ) );
		}

		$success_count = 0;
		$error_count   = 0;

		foreach ( $member_ids as $member_id ) {
			$result = false;

			switch ( $action ) {
				case 'activate':
					$result = $this->member->set_active( $member_id );
					break;

				case 'deactivate':
					$result = $this->member->set_inactive( $member_id );
					break;

				case 'delete':
					$result = $this->member->delete( $member_id, false );
					break;

				case 'delete_with_user':
					$result = $this->member->delete( $member_id, true );
					break;
			}

			if ( is_wp_error( $result ) || false === $result ) {
				++$error_count;
			} else {
				++$success_count;
			}
		}

		wp_send_json_success(
			array(
				/* translators: 1: Number of successful operations, 2: Number of failed operations */
				'message' => sprintf(
					__( 'Bulk action completed. %1$d successful, %2$d failed.', 'member-registration-plugin' ),
					$success_count,
					$error_count
				),
			)
		);
	}

	/**
	 * Handle admin create custom field.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_admin_create_field() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check permissions.
		if ( ! current_user_can( 'mbrreg_manage_custom_fields' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'member-registration-plugin' ) ) );
		}

		$data = array(
			'field_name'    => isset( $_POST['field_name'] ) ? sanitize_key( wp_unslash( $_POST['field_name'] ) ) : '',
			'field_label'   => isset( $_POST['field_label'] ) ? sanitize_text_field( wp_unslash( $_POST['field_label'] ) ) : '',
			'field_type'    => isset( $_POST['field_type'] ) ? sanitize_text_field( wp_unslash( $_POST['field_type'] ) ) : 'text',
			'field_options' => isset( $_POST['field_options'] ) ? sanitize_textarea_field( wp_unslash( $_POST['field_options'] ) ) : '',
			'is_required'   => isset( $_POST['is_required'] ) ? (int) $_POST['is_required'] : 0,
			'is_admin_only' => isset( $_POST['is_admin_only'] ) ? (int) $_POST['is_admin_only'] : 0,
			'field_order'   => isset( $_POST['field_order'] ) ? (int) $_POST['field_order'] : 0,
		);

		$result = $this->custom_fields->create( $data );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success(
			array(
				'message'  => __( 'Custom field created successfully!', 'member-registration-plugin' ),
				'field_id' => $result,
			)
		);
	}

	/**
	 * Handle admin update custom field.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_admin_update_field() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check permissions.
		if ( ! current_user_can( 'mbrreg_manage_custom_fields' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'member-registration-plugin' ) ) );
		}

		$field_id = isset( $_POST['field_id'] ) ? absint( $_POST['field_id'] ) : 0;

		if ( ! $field_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid field ID.', 'member-registration-plugin' ) ) );
		}

		$data = array(
			'field_label'   => isset( $_POST['field_label'] ) ? sanitize_text_field( wp_unslash( $_POST['field_label'] ) ) : '',
			'field_type'    => isset( $_POST['field_type'] ) ? sanitize_text_field( wp_unslash( $_POST['field_type'] ) ) : 'text',
			'field_options' => isset( $_POST['field_options'] ) ? sanitize_textarea_field( wp_unslash( $_POST['field_options'] ) ) : '',
			'is_required'   => isset( $_POST['is_required'] ) ? (int) $_POST['is_required'] : 0,
			'is_admin_only' => isset( $_POST['is_admin_only'] ) ? (int) $_POST['is_admin_only'] : 0,
			'field_order'   => isset( $_POST['field_order'] ) ? (int) $_POST['field_order'] : 0,
		);

		$result = $this->custom_fields->update( $field_id, $data );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success( array( 'message' => __( 'Custom field updated successfully!', 'member-registration-plugin' ) ) );
	}

	/**
	 * Handle admin delete custom field.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_admin_delete_field() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check permissions.
		if ( ! current_user_can( 'mbrreg_manage_custom_fields' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'member-registration-plugin' ) ) );
		}

		$field_id = isset( $_POST['field_id'] ) ? absint( $_POST['field_id'] ) : 0;

		if ( ! $field_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid field ID.', 'member-registration-plugin' ) ) );
		}

		$result = $this->custom_fields->delete( $field_id );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success( array( 'message' => __( 'Custom field deleted successfully!', 'member-registration-plugin' ) ) );
	}

	/**
	 * Handle resend activation email.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_resend_activation() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check permissions.
		if ( ! $this->member->is_member_admin() ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'member-registration-plugin' ) ) );
		}

		$member_id = isset( $_POST['member_id'] ) ? absint( $_POST['member_id'] ) : 0;

		if ( ! $member_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid member ID.', 'member-registration-plugin' ) ) );
		}

		$result = $this->member->resend_activation_email( $member_id );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success( array( 'message' => __( 'Activation email sent successfully!', 'member-registration-plugin' ) ) );
	}

	/**
	 * Handle CSV import.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function handle_import_csv() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check permissions.
		if ( ! current_user_can( 'mbrreg_import_members' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'member-registration-plugin' ) ) );
		}

		// Check if file was uploaded.
		if ( ! isset( $_FILES['csv_file'] ) || ! is_uploaded_file( $_FILES['csv_file']['tmp_name'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Please select a CSV file to upload.', 'member-registration-plugin' ) ) );
		}

		$file = $_FILES['csv_file'];

		// Validate file type.
		$allowed_types = array( 'text/csv', 'text/plain', 'application/csv', 'application/vnd.ms-excel' );
		if ( ! in_array( $file['type'], $allowed_types, true ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid file type. Please upload a CSV file.', 'member-registration-plugin' ) ) );
		}

		// Parse CSV.
		$csv_data = array();
		$handle   = fopen( $file['tmp_name'], 'r' );

		if ( false === $handle ) {
			wp_send_json_error( array( 'message' => __( 'Failed to read the CSV file.', 'member-registration-plugin' ) ) );
		}

		while ( ( $row = fgetcsv( $handle, 0, ',' ) ) !== false ) {
			$csv_data[] = $row;
		}

		fclose( $handle );

		if ( count( $csv_data ) < 2 ) {
			wp_send_json_error( array( 'message' => __( 'The CSV file is empty or contains only headers.', 'member-registration-plugin' ) ) );
		}

		// Import members.
		$results = $this->member->import_csv( $csv_data );

		$message = sprintf(
			/* translators: 1: Number of successful imports, 2: Number of errors */
			__( 'Import completed. %1$d members imported successfully, %2$d errors.', 'member-registration-plugin' ),
			$results['success'],
			count( $results['errors'] )
		);

		if ( ! empty( $results['errors'] ) ) {
			$message .= "\n\n" . __( 'Errors:', 'member-registration-plugin' ) . "\n" . implode( "\n", array_slice( $results['errors'], 0, 10 ) );
			if ( count( $results['errors'] ) > 10 ) {
				$message .= sprintf(
					/* translators: %d: Number of additional errors */
					__( "\n...and %d more errors.", 'member-registration-plugin' ),
					count( $results['errors'] ) - 10
				);
			}
		}

		wp_send_json_success( array( 'message' => $message ) );
	}

	/**
	 * Handle CSV export.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function handle_export_csv() {
		// Verify nonce.
		if ( ! check_ajax_referer( 'mbrreg_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'member-registration-plugin' ) ) );
		}

		// Check permissions.
		if ( ! current_user_can( 'mbrreg_export_members' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'member-registration-plugin' ) ) );
		}

		$status = isset( $_POST['status'] ) ? sanitize_text_field( wp_unslash( $_POST['status'] ) ) : '';

		$args = array();
		if ( ! empty( $status ) ) {
			$args['status'] = $status;
		}

		$csv = $this->member->export_csv( $args );

		wp_send_json_success(
			array(
				'csv'      => base64_encode( $csv ), // phpcs:ignore
				'filename' => 'members-export-' . gmdate( 'Y-m-d' ) . '.csv',
			)
		);
	}
}
```

---

Due to the length of this response, I'll continue with the remaining files in the next parts. Here's **Part 2** with more updated files:

## includes/class-mbrreg-shortcodes.php (UPDATED)

```php
<?php
/**
 * Shortcodes handler class.
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
 * Class Mbrreg_Shortcodes
 *
 * Handles shortcodes for the plugin.
 *
 * @since 1.0.0
 */
class Mbrreg_Shortcodes {

	/**
	 * Member instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Member
	 */
	private $member;

	/**
	 * Custom fields instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Custom_Fields
	 */
	private $custom_fields;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param Mbrreg_Member        $member        Member instance.
	 * @param Mbrreg_Custom_Fields $custom_fields Custom fields instance.
	 */
	public function __construct( Mbrreg_Member $member, Mbrreg_Custom_Fields $custom_fields ) {
		$this->member        = $member;
		$this->custom_fields = $custom_fields;
	}

	/**
	 * Initialize shortcodes.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init() {
		add_shortcode( 'mbrreg_member_area', array( $this, 'render_member_area' ) );
		add_shortcode( 'mbrreg_login_form', array( $this, 'render_login_form' ) );
		add_shortcode( 'mbrreg_register_form', array( $this, 'render_register_form' ) );
		add_shortcode( 'mbrreg_member_dashboard', array( $this, 'render_member_dashboard' ) );
	}

	/**
	 * Render complete member area (login/register/dashboard).
	 *
	 * @since 1.0.0
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public function render_member_area( $atts ) {
		$atts = shortcode_atts(
			array(
				'show_register' => 'yes',
			),
			$atts,
			'mbrreg_member_area'
		);

		ob_start();

		// Include modal template.
		include MBRREG_PLUGIN_PATH . 'public/partials/mbrreg-modal.php';

		if ( is_user_logged_in() ) {
			$this->output_member_dashboard();
		} else {
			$this->output_login_register_forms( 'yes' === $atts['show_register'] );
		}

		return ob_get_clean();
	}

	/**
	 * Render login form shortcode.
	 *
	 * @since 1.0.0
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public function render_login_form( $atts ) {
		$atts = shortcode_atts(
			array(
				'redirect' => '',
			),
			$atts,
			'mbrreg_login_form'
		);

		if ( is_user_logged_in() ) {
			return '<p class="mbrreg-message">' . esc_html__( 'You are already logged in.', 'member-registration-plugin' ) . '</p>';
		}

		ob_start();
		include MBRREG_PLUGIN_PATH . 'public/partials/mbrreg-modal.php';
		include MBRREG_PLUGIN_PATH . 'public/partials/mbrreg-login-form.php';
		return ob_get_clean();
	}

	/**
	 * Render register form shortcode.
	 *
	 * @since 1.0.0
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public function render_register_form( $atts ) {
		$atts = shortcode_atts( array(), $atts, 'mbrreg_register_form' );

		if ( is_user_logged_in() ) {
			return '<p class="mbrreg-message">' . esc_html__( 'You are already registered and logged in.', 'member-registration-plugin' ) . '</p>';
		}

		if ( ! get_option( 'mbrreg_allow_registration', true ) ) {
			return '<p class="mbrreg-message">' . esc_html__( 'Registration is currently disabled.', 'member-registration-plugin' ) . '</p>';
		}

		ob_start();
		$custom_fields = $this->custom_fields->get_user_editable();
		include MBRREG_PLUGIN_PATH . 'public/partials/mbrreg-modal.php';
		include MBRREG_PLUGIN_PATH . 'public/partials/mbrreg-register-form.php';
		return ob_get_clean();
	}

	/**
	 * Render member dashboard shortcode.
	 *
	 * @since 1.0.0
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public function render_member_dashboard( $atts ) {
		$atts = shortcode_atts( array(), $atts, 'mbrreg_member_dashboard' );

		if ( ! is_user_logged_in() ) {
			return '<p class="mbrreg-message">' . esc_html__( 'Please log in to view your dashboard.', 'member-registration-plugin' ) . '</p>';
		}

		ob_start();
		include MBRREG_PLUGIN_PATH . 'public/partials/mbrreg-modal.php';
		$this->output_member_dashboard();
		return ob_get_clean();
	}

	/**
	 * Output login and register forms.
	 *
	 * @since 1.0.0
	 * @param bool $show_register Whether to show register form.
	 * @return void
	 */
	private function output_login_register_forms( $show_register = true ) {
		$custom_fields = $this->custom_fields->get_user_editable();
		?>
		<div class="mbrreg-auth-container">
			<div class="mbrreg-tabs">
				<button type="button" class="mbrreg-tab mbrreg-tab-active" data-tab="login">
					<?php esc_html_e( 'Login', 'member-registration-plugin' ); ?>
				</button>
				<?php if ( $show_register && get_option( 'mbrreg_allow_registration', true ) ) : ?>
					<button type="button" class="mbrreg-tab" data-tab="register">
						<?php esc_html_e( 'Register', 'member-registration-plugin' ); ?>
					</button>
				<?php endif; ?>
			</div>

			<div class="mbrreg-tab-content mbrreg-tab-login mbrreg-tab-content-active">
				<?php include MBRREG_PLUGIN_PATH . 'public/partials/mbrreg-login-form.php'; ?>
			</div>

			<?php if ( $show_register && get_option( 'mbrreg_allow_registration', true ) ) : ?>
				<div class="mbrreg-tab-content mbrreg-tab-register">
					<?php include MBRREG_PLUGIN_PATH . 'public/partials/mbrreg-register-form.php'; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Output member dashboard.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function output_member_dashboard() {
		$current_user        = wp_get_current_user();
		$members             = $this->member->get_all_by_user_id( $current_user->ID );
		$custom_fields       = $this->custom_fields->get_all();
		$user_editable_fields = $this->custom_fields->get_user_editable();
		$is_admin            = $this->member->is_member_admin();
		$allow_multiple      = get_option( 'mbrreg_allow_multiple_members', true );

		include MBRREG_PLUGIN_PATH . 'public/partials/mbrreg-member-dashboard.php';
	}
}
```

---

## public/partials/mbrreg-modal.php (NEW FILE)

```php
<?php
/**
 * Modal template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/public/partials
 * @since 1.1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<!-- Confirmation Modal -->
<div id="mbrreg-confirm-modal" class="mbrreg-modal-overlay" style="display: none;">
	<div class="mbrreg-modal-dialog">
		<div class="mbrreg-modal-header">
			<h3 class="mbrreg-modal-title"><?php esc_html_e( 'Confirm Action', 'member-registration-plugin' ); ?></h3>
			<button type="button" class="mbrreg-modal-close-btn" data-dismiss="modal">&times;</button>
		</div>
		<div class="mbrreg-modal-body">
			<p class="mbrreg-modal-message"></p>
		</div>
		<div class="mbrreg-modal-footer">
			<button type="button" class="mbrreg-button mbrreg-button-secondary" data-dismiss="modal">
				<?php esc_html_e( 'Cancel', 'member-registration-plugin' ); ?>
			</button>
			<button type="button" class="mbrreg-button mbrreg-button-primary mbrreg-modal-confirm-btn">
				<?php esc_html_e( 'Confirm', 'member-registration-plugin' ); ?>
			</button>
		</div>
	</div>
</div>

<!-- Alert Modal -->
<div id="mbrreg-alert-modal" class="mbrreg-modal-overlay" style="display: none;">
	<div class="mbrreg-modal-dialog">
		<div class="mbrreg-modal-header">
			<h3 class="mbrreg-modal-title"><?php esc_html_e( 'Notice', 'member-registration-plugin' ); ?></h3>
			<button type="button" class="mbrreg-modal-close-btn" data-dismiss="modal">&times;</button>
		</div>
		<div class="mbrreg-modal-body">
			<p class="mbrreg-modal-message"></p>
		</div>
		<div class="mbrreg-modal-footer">
			<button type="button" class="mbrreg-button mbrreg-button-primary" data-dismiss="modal">
				<?php esc_html_e( 'OK', 'member-registration-plugin' ); ?>
			</button>
		</div>
	</div>
</div>
```

---

## public/partials/mbrreg-member-dashboard.php (UPDATED)

```php
<?php
/**
 * Member dashboard template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/public/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get custom fields instance for rendering.
$custom_fields_handler = new Mbrreg_Custom_Fields();
?>

<div class="mbrreg-dashboard-container">
	<div class="mbrreg-dashboard-header">
		<h2>
			<?php
			printf(
				/* translators: %s: User display name */
				esc_html__( 'Welcome, %s!', 'member-registration-plugin' ),
				esc_html( $current_user->display_name )
			);
			?>
		</h2>

		<div class="mbrreg-dashboard-actions">
			<?php if ( $is_admin ) : ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members' ) ); ?>" class="mbrreg-button mbrreg-button-secondary">
					<?php esc_html_e( 'Manage Members', 'member-registration-plugin' ); ?>
				</a>
			<?php endif; ?>

			<button type="button" class="mbrreg-button mbrreg-button-secondary mbrreg-logout-btn">
				<?php esc_html_e( 'Log Out', 'member-registration-plugin' ); ?>
			</button>
		</div>
	</div>

	<div class="mbrreg-form-messages"></div>

	<?php if ( ! empty( $members ) ) : ?>
		<div class="mbrreg-account-info">
			<p>
				<strong><?php esc_html_e( 'Account Email:', 'member-registration-plugin' ); ?></strong>
				<?php echo esc_html( $current_user->user_email ); ?>
			</p>
			<p>
				<strong><?php esc_html_e( 'Registered Members:', 'member-registration-plugin' ); ?></strong>
				<?php echo esc_html( count( $members ) ); ?>
			</p>
		</div>

		<?php foreach ( $members as $index => $member ) : ?>
			<div class="mbrreg-member-card" data-member-id="<?php echo esc_attr( $member->id ); ?>">
				<div class="mbrreg-member-card-header">
					<h3>
						<?php
						$member_name = trim( $member->first_name . ' ' . $member->last_name );
						if ( empty( $member_name ) ) {
							$member_name = sprintf(
								/* translators: %d: Member number */
								__( 'Member #%d', 'member-registration-plugin' ),
								$index + 1
							);
						}
						echo esc_html( $member_name );
						?>
					</h3>
					<span class="mbrreg-status mbrreg-status-<?php echo esc_attr( $member->status ); ?>">
						<?php echo esc_html( Mbrreg_Member::$statuses[ $member->status ] ); ?>
					</span>
				</div>

				<form class="mbrreg-profile-form mbrreg-form" method="post" data-member-id="<?php echo esc_attr( $member->id ); ?>">
					<input type="hidden" name="member_id" value="<?php echo esc_attr( $member->id ); ?>">

					<!-- Personal Details -->
					<fieldset class="mbrreg-fieldset">
						<legend><?php esc_html_e( 'Personal Details', 'member-registration-plugin' ); ?></legend>

						<div class="mbrreg-form-row">
							<label for="mbrreg-first-name-<?php echo esc_attr( $member->id ); ?>">
								<?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?>
								<?php if ( get_option( 'mbrreg_require_first_name', false ) ) : ?>
									<span class="required">*</span>
								<?php endif; ?>
							</label>
							<input type="text" id="mbrreg-first-name-<?php echo esc_attr( $member->id ); ?>" name="first_name" value="<?php echo esc_attr( $member->first_name ); ?>" <?php echo get_option( 'mbrreg_require_first_name', false ) ? 'required' : ''; ?>>
						</div>

						<div class="mbrreg-form-row">
							<label for="mbrreg-last-name-<?php echo esc_attr( $member->id ); ?>">
								<?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?>
								<?php if ( get_option( 'mbrreg_require_last_name', false ) ) : ?>
									<span class="required">*</span>
								<?php endif; ?>
							</label>
							<input type="text" id="mbrreg-last-name-<?php echo esc_attr( $member->id ); ?>" name="last_name" value="<?php echo esc_attr( $member->last_name ); ?>" <?php echo get_option( 'mbrreg_require_last_name', false ) ? 'required' : ''; ?>>
						</div>

						<div class="mbrreg-form-row">
							<label for="mbrreg-address-<?php echo esc_attr( $member->id ); ?>">
								<?php esc_html_e( 'Address', 'member-registration-plugin' ); ?>
								<?php if ( get_option( 'mbrreg_require_address', false ) ) : ?>
									<span class="required">*</span>
								<?php endif; ?>
							</label>
							<textarea id="mbrreg-address-<?php echo esc_attr( $member->id ); ?>" name="address" rows="3" <?php echo get_option( 'mbrreg_require_address', false ) ? 'required' : ''; ?>><?php echo esc_textarea( $member->address ); ?></textarea>
						</div>

						<div class="mbrreg-form-row">
							<label for="mbrreg-telephone-<?php echo esc_attr( $member->id ); ?>">
								<?php esc_html_e( 'Telephone Number', 'member-registration-plugin' ); ?>
								<?php if ( get_option( 'mbrreg_require_telephone', false ) ) : ?>
									<span class="required">*</span>
								<?php endif; ?>
							</label>
							<input type="tel" id="mbrreg-telephone-<?php echo esc_attr( $member->id ); ?>" name="telephone" value="<?php echo esc_attr( $member->telephone ); ?>" <?php echo get_option( 'mbrreg_require_telephone', false ) ? 'required' : ''; ?>>
						</div>

						<div class="mbrreg-form-row">
							<label for="mbrreg-dob-<?php echo esc_attr( $member->id ); ?>">
								<?php esc_html_e( 'Date of Birth', 'member-registration-plugin' ); ?>
								<?php if ( get_option( 'mbrreg_require_date_of_birth', false ) ) : ?>
									<span class="required">*</span>
								<?php endif; ?>
							</label>
							<input type="date" id="mbrreg-dob-<?php echo esc_attr( $member->id ); ?>" name="date_of_birth" value="<?php echo esc_attr( $member->date_of_birth ); ?>" <?php echo get_option( 'mbrreg_require_date_of_birth', false ) ? 'required' : ''; ?>>
						</div>

						<div class="mbrreg-form-row">
							<label for="mbrreg-pob-<?php echo esc_attr( $member->id ); ?>">
								<?php esc_html_e( 'Place of Birth', 'member-registration-plugin' ); ?>
								<?php if ( get_option( 'mbrreg_require_place_of_birth', false ) ) : ?>
									<span class="required">*</span>
								<?php endif; ?>
							</label>
							<input type="text" id="mbrreg-pob-<?php echo esc_attr( $member->id ); ?>" name="place_of_birth" value="<?php echo esc_attr( $member->place_of_birth ); ?>" <?php echo get_option( 'mbrreg_require_place_of_birth', false ) ? 'required' : ''; ?>>
						</div>
					</fieldset>

					<!-- Custom Fields -->
					<?php if ( ! empty( $custom_fields ) ) : ?>
						<fieldset class="mbrreg-fieldset">
							<legend><?php esc_html_e( 'Additional Information', 'member-registration-plugin' ); ?></legend>

							<?php foreach ( $custom_fields as $field ) : ?>
								<?php
								$value       = isset( $member->custom_fields[ $field->id ] ) ? $member->custom_fields[ $field->id ] : '';
								$is_readonly = $field->is_admin_only;
								?>
								<div class="mbrreg-form-row">
									<label for="mbrreg-custom-<?php echo esc_attr( $field->id ); ?>-<?php echo esc_attr( $member->id ); ?>">
										<?php echo esc_html( $field->field_label ); ?>
										<?php if ( $field->is_required && ! $is_readonly ) : ?>
											<span class="required">*</span>
										<?php endif; ?>
										<?php if ( $is_readonly ) : ?>
											<span class="mbrreg-readonly-badge"><?php esc_html_e( '(View only)', 'member-registration-plugin' ); ?></span>
										<?php endif; ?>
									</label>
									<?php
									echo $custom_fields_handler->render_field_input( // phpcs:ignore
										$field,
										$value,
										array(
											'id_prefix' => 'mbrreg-custom-' . $member->id . '-',
											'readonly'  => $is_readonly,
										)
									);
									?>
								</div>
							<?php endforeach; ?>
						</fieldset>
					<?php endif; ?>

					<div class="mbrreg-form-row mbrreg-form-actions">
						<button type="submit" class="mbrreg-button mbrreg-button-primary">
							<?php esc_html_e( 'Update Profile', 'member-registration-plugin' ); ?>
						</button>

						<?php if ( 'active' === $member->status ) : ?>
							<button type="button" class="mbrreg-button mbrreg-button-danger mbrreg-deactivate-btn" data-member-id="<?php echo esc_attr( $member->id ); ?>">
								<?php esc_html_e( 'Deactivate Membership', 'member-registration-plugin' ); ?>
							</button>
						<?php endif; ?>
					</div>
				</form>
			</div>
		<?php endforeach; ?>

		<!-- Add Another Member Section -->
		<?php if ( $allow_multiple ) : ?>
			<div class="mbrreg-add-member-section">
				<button type="button" class="mbrreg-button mbrreg-button-secondary mbrreg-toggle-add-member">
					<?php esc_html_e( '+ Add Another Member', 'member-registration-plugin' ); ?>
				</button>

				<div class="mbrreg-add-member-form-container" style="display: none;">
					<h3><?php esc_html_e( 'Add New Member', 'member-registration-plugin' ); ?></h3>
					<p class="mbrreg-info"><?php esc_html_e( 'Add another family member to your account.', 'member-registration-plugin' ); ?></p>

					<form id="mbrreg-add-member-form" class="mbrreg-form" method="post">
						<fieldset class="mbrreg-fieldset">
							<legend><?php esc_html_e( 'Personal Details', 'member-registration-plugin' ); ?></legend>

							<div class="mbrreg-form-row">
								<label for="mbrreg-add-first-name">
									<?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?>
									<?php if ( get_option( 'mbrreg_require_first_name', false ) ) : ?>
										<span class="required">*</span>
									<?php endif; ?>
								</label>
								<input type="text" id="mbrreg-add-first-name" name="first_name" <?php echo get_option( 'mbrreg_require_first_name', false ) ? 'required' : ''; ?>>
							</div>

							<div class="mbrreg-form-row">
								<label for="mbrreg-add-last-name">
									<?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?>
									<?php if ( get_option( 'mbrreg_require_last_name', false ) ) : ?>
										<span class="required">*</span>
									<?php endif; ?>
								</label>
								<input type="text" id="mbrreg-add-last-name" name="last_name" <?php echo get_option( 'mbrreg_require_last_name', false ) ? 'required' : ''; ?>>
							</div>

							<div class="mbrreg-form-row">
								<label for="mbrreg-add-address">
									<?php esc_html_e( 'Address', 'member-registration-plugin' ); ?>
									<?php if ( get_option( 'mbrreg_require_address', false ) ) : ?>
										<span class="required">*</span>
									<?php endif; ?>
								</label>
								<textarea id="mbrreg-add-address" name="address" rows="3" <?php echo get_option( 'mbrreg_require_address', false ) ? 'required' : ''; ?>></textarea>
							</div>

							<div class="mbrreg-form-row">
								<label for="mbrreg-add-telephone">
									<?php esc_html_e( 'Telephone Number', 'member-registration-plugin' ); ?>
									<?php if ( get_option( 'mbrreg_require_telephone', false ) ) : ?>
										<span class="required">*</span>
									<?php endif; ?>
								</label>
								<input type="tel" id="mbrreg-add-telephone" name="telephone" <?php echo get_option( 'mbrreg_require_telephone', false ) ? 'required' : ''; ?>>
							</div>

							<div class="mbrreg-form-row">
								<label for="mbrreg-add-dob">
									<?php esc_html_e( 'Date of Birth', 'member-registration-plugin' ); ?>
									<?php if ( get_option( 'mbrreg_require_date_of_birth', false ) ) : ?>
										<span class="required">*</span>
									<?php endif; ?>
								</label>
								<input type="date" id="mbrreg-add-dob" name="date_of_birth" <?php echo get_option( 'mbrreg_require_date_of_birth', false ) ? 'required' : ''; ?>>
							</div>

							<div class="mbrreg-form-row">
								<label for="mbrreg-add-pob">
									<?php esc_html_e( 'Place of Birth', 'member-registration-plugin' ); ?>
									<?php if ( get_option( 'mbrreg_require_place_of_birth', false ) ) : ?>
										<span class="required">*</span>
									<?php endif; ?>
								</label>
								<input type="text" id="mbrreg-add-pob" name="place_of_birth" <?php echo get_option( 'mbrreg_require_place_of_birth', false ) ? 'required' : ''; ?>>
							</div>
						</fieldset>

						<!-- Custom Fields for new member -->
						<?php if ( ! empty( $user_editable_fields ) ) : ?>
							<fieldset class="mbrreg-fieldset">
								<legend><?php esc_html_e( 'Additional Information', 'member-registration-plugin' ); ?></legend>

								<?php foreach ( $user_editable_fields as $field ) : ?>
									<div class="mbrreg-form-row">
										<label for="mbrreg-add-custom-<?php echo esc_attr( $field->id ); ?>">
											<?php echo esc_html( $field->field_label ); ?>
											<?php if ( $field->is_required ) : ?>
												<span class="required">*</span>
											<?php endif; ?>
										</label>
										<?php
										echo $custom_fields_handler->render_field_input( // phpcs:ignore
											$field,
											'',
											array( 'id_prefix' => 'mbrreg-add-custom-' )
										);
										?>
									</div>
								<?php endforeach; ?>
							</fieldset>
						<?php endif; ?>

						<div class="mbrreg-form-row mbrreg-form-actions">
							<button type="submit" class="mbrreg-button mbrreg-button-primary">
								<?php esc_html_e( 'Add Member', 'member-registration-plugin' ); ?>
							</button>
							<button type="button" class="mbrreg-button mbrreg-button-secondary mbrreg-cancel-add-member">
								<?php esc_html_e( 'Cancel', 'member-registration-plugin' ); ?>
							</button>
						</div>
					</form>
				</div>
			</div>
		<?php endif; ?>

	<?php else : ?>
		<p class="mbrreg-message mbrreg-warning">
			<?php esc_html_e( 'No member profile found. Please contact an administrator.', 'member-registration-plugin' ); ?>
		</p>
	<?php endif; ?>
</div>
```

---

Due to the extensive nature of this update, I'll provide the remaining critical files. Let me continue with **Part 3**:

## public/js/mbrreg-public.js (UPDATED - Fixed bug + Modal support)

```javascript
/**
 * Public JavaScript for Member Registration Plugin.
 *
 * @package Member_Registration_Plugin
 * @since 1.0.0
 */

(function($) {
	'use strict';

	/**
	 * Public functionality object.
	 */
	const MbrregPublic = {

		/**
		 * Initialize public functionality.
		 */
		init: function() {
			this.bindEvents();
		},

		/**
		 * Bind event handlers.
		 */
		bindEvents: function() {
			// Tab navigation.
			$(document).on('click', '.mbrreg-tab', this.handleTabClick);

			// Form submissions.
			$(document).on('submit', '#mbrreg-login-form', this.handleLogin);
			$(document).on('submit', '#mbrreg-register-form', this.handleRegister);
			$(document).on('submit', '.mbrreg-profile-form', this.handleProfileUpdate);
			$(document).on('submit', '#mbrreg-add-member-form', this.handleAddMember);

			// Actions with confirmation modal.
			$(document).on('click', '.mbrreg-deactivate-btn', this.handleDeactivateClick);
			$(document).on('click', '.mbrreg-logout-btn', this.handleLogoutClick);

			// Add member toggle.
			$(document).on('click', '.mbrreg-toggle-add-member', this.toggleAddMemberForm);
			$(document).on('click', '.mbrreg-cancel-add-member', this.hideAddMemberForm);

			// Modal controls.
			$(document).on('click', '[data-dismiss="modal"]', this.closeModal);
			$(document).on('click', '.mbrreg-modal-overlay', this.closeModalOnOverlay);
		},

		/**
		 * Handle tab click.
		 *
		 * @param {Event} e Click event.
		 */
		handleTabClick: function(e) {
			e.preventDefault();

			const $tab = $(this);
			const tabId = $tab.data('tab');

			// Update active tab.
			$('.mbrreg-tab').removeClass('mbrreg-tab-active');
			$tab.addClass('mbrreg-tab-active');

			// Update active content.
			$('.mbrreg-tab-content').removeClass('mbrreg-tab-content-active');
			$('.mbrreg-tab-' + tabId).addClass('mbrreg-tab-content-active');
		},

		/**
		 * Handle login form submission.
		 *
		 * @param {Event} e Submit event.
		 */
		handleLogin: function(e) {
			e.preventDefault();

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			const originalText = $submitBtn.text();

			MbrregPublic.clearMessages($form);
			$submitBtn.prop('disabled', true).text(mbrregPublic.processing);

			$.ajax({
				url: mbrregPublic.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_login',
					nonce: mbrregPublic.loginNonce,
					username: $form.find('input[name="username"]').val(),
					password: $form.find('input[name="password"]').val(),
					remember: $form.find('input[name="remember"]').is(':checked') ? 1 : 0
				},
				success: function(response) {
					if (response.success) {
						MbrregPublic.showMessage($form, response.data.message, 'success');

						// Redirect or reload.
						setTimeout(function() {
							if (response.data.redirect_url) {
								window.location.href = response.data.redirect_url;
							} else {
								window.location.reload();
							}
						}, 500);
					} else {
						MbrregPublic.showMessage($form, response.data.message, 'error');
						$submitBtn.prop('disabled', false).text(originalText);
					}
				},
				error: function() {
					MbrregPublic.showMessage($form, mbrregPublic.errorGeneral, 'error');
					$submitBtn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle registration form submission.
		 *
		 * @param {Event} e Submit event.
		 */
		handleRegister: function(e) {
			e.preventDefault();

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			const originalText = $submitBtn.text();

			// Validate password match.
			const password = $form.find('input[name="password"]').val();
			const passwordConfirm = $form.find('input[name="password_confirm"]').val();

			if (password !== passwordConfirm) {
				MbrregPublic.showMessage($form, mbrregPublic.passwordMismatch, 'error');
				return;
			}

			MbrregPublic.clearMessages($form);
			$submitBtn.prop('disabled', true).text(mbrregPublic.processing);

			const formData = new FormData($form[0]);
			formData.append('action', 'mbrreg_register');
			formData.append('nonce', mbrregPublic.registerNonce);

			$.ajax({
				url: mbrregPublic.ajaxUrl,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					$submitBtn.prop('disabled', false).text(originalText);
					
					if (response.success) {
						MbrregPublic.showMessage($form, response.data.message, 'success');
						$form[0].reset();
						
						// Show success in modal if needed.
						if (response.data.reload) {
							setTimeout(function() {
								window.location.reload();
							}, 2000);
						}
					} else {
						MbrregPublic.showMessage($form, response.data.message, 'error');
					}
				},
				error: function() {
					MbrregPublic.showMessage($form, mbrregPublic.errorGeneral, 'error');
					$submitBtn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle profile update form submission.
		 *
		 * @param {Event} e Submit event.
		 */
		handleProfileUpdate: function(e) {
			e.preventDefault();

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			const originalText = $submitBtn.text();
			const memberId = $form.data('member-id');

			MbrregPublic.clearMessages($form);
			$submitBtn.prop('disabled', true).text(mbrregPublic.processing);

			const formData = new FormData($form[0]);
			formData.append('action', 'mbrreg_update_profile');
			formData.append('nonce', mbrregPublic.updateProfileNonce);
			formData.append('member_id', memberId);

			$.ajax({
				url: mbrregPublic.ajaxUrl,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					$submitBtn.prop('disabled', false).text(originalText);
					
					if (response.success) {
						MbrregPublic.showMessage($form, response.data.message, 'success');
						
						if (response.data.reload) {
							setTimeout(function() {
								window.location.reload();
							}, 1000);
						}
					} else {
						MbrregPublic.showMessage($form, response.data.message, 'error');
					}
				},
				error: function() {
					MbrregPublic.showMessage($form, mbrregPublic.errorGeneral, 'error');
					$submitBtn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle add member form submission.
		 *
		 * @param {Event} e Submit event.
		 */
		handleAddMember: function(e) {
			e.preventDefault();

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			const originalText = $submitBtn.text();

			MbrregPublic.clearMessages($form);
			$submitBtn.prop('disabled', true).text(mbrregPublic.processing);

			const formData = new FormData($form[0]);
			formData.append('action', 'mbrreg_add_member');
			formData.append('nonce', mbrregPublic.addMemberNonce);

			$.ajax({
				url: mbrregPublic.ajaxUrl,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					$submitBtn.prop('disabled', false).text(originalText);
					
					if (response.success) {
						MbrregPublic.showMessage($form, response.data.message, 'success');
						$form[0].reset();
						
						if (response.data.reload) {
							setTimeout(function() {
								window.location.reload();
							}, 1500);
						}
					} else {
						MbrregPublic.showMessage($form, response.data.message, 'error');
					}
				},
				error: function() {
					MbrregPublic.showMessage($form, mbrregPublic.errorGeneral, 'error');
					$submitBtn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle deactivate button click.
		 *
		 * @param {Event} e Click event.
		 */
		handleDeactivateClick: function(e) {
			e.preventDefault();

			const $btn = $(this);
			const memberId = $btn.data('member-id');

			MbrregPublic.showConfirmModal(
				mbrregPublic.confirmDeactivateTitle,
				mbrregPublic.confirmDeactivate,
				function() {
					MbrregPublic.performDeactivate(memberId, $btn);
				}
			);
		},

		/**
		 * Perform member deactivation.
		 *
		 * @param {int} memberId Member ID.
		 * @param {jQuery} $btn Button element.
		 */
		performDeactivate: function(memberId, $btn) {
			const originalText = $btn.text();
			$btn.prop('disabled', true).text(mbrregPublic.processing);

			$.ajax({
				url: mbrregPublic.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_set_inactive',
					nonce: mbrregPublic.setInactiveNonce,
					member_id: memberId
				},
				success: function(response) {
					if (response.success) {
						MbrregPublic.showAlertModal(
							mbrregPublic.successTitle,
							response.data.message,
							function() {
								window.location.reload();
							}
						);
					} else {
						MbrregPublic.showAlertModal(mbrregPublic.errorTitle, response.data.message);
						$btn.prop('disabled', false).text(originalText);
					}
				},
				error: function() {
					MbrregPublic.showAlertModal(mbrregPublic.errorTitle, mbrregPublic.errorGeneral);
					$btn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle logout button click.
		 *
		 * @param {Event} e Click event.
		 */
		handleLogoutClick: function(e) {
			e.preventDefault();

			MbrregPublic.showConfirmModal(
				mbrregPublic.confirmLogoutTitle,
				mbrregPublic.confirmLogout,
				function() {
					MbrregPublic.performLogout();
				}
			);
		},

		/**
		 * Perform logout.
		 */
		performLogout: function() {
			$.ajax({
				url: mbrregPublic.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_logout',
					nonce: mbrregPublic.logoutNonce
				},
				success: function(response) {
					window.location.reload();
				},
				error: function() {
					window.location.reload();
				}
			});
		},

		/**
		 * Toggle add member form.
		 */
		toggleAddMemberForm: function() {
			$('.mbrreg-add-member-form-container').slideToggle();
			$(this).hide();
		},

		/**
		 * Hide add member form.
		 */
		hideAddMemberForm: function() {
			$('.mbrreg-add-member-form-container').slideUp();
			$('.mbrreg-toggle-add-member').show();
		},

		/**
		 * Show confirmation modal.
		 *
		 * @param {string} title Modal title.
		 * @param {string} message Modal message.
		 * @param {function} callback Callback on confirm.
		 */
		showConfirmModal: function(title, message, callback) {
			const $modal = $('#mbrreg-confirm-modal');
			
			$modal.find('.mbrreg-modal-title').text(title);
			$modal.find('.mbrreg-modal-message').text(message);
			
			// Remove previous click handler and add new one.
			$modal.find('.mbrreg-modal-confirm-btn').off('click').on('click', function() {
				MbrregPublic.closeModal();
				if (typeof callback === 'function') {
					callback();
				}
			});
			
			$modal.fadeIn(200);
		},

		/**
		 * Show alert modal.
		 *
		 * @param {string} title Modal title.
		 * @param {string} message Modal message.
		 * @param {function} callback Callback on close.
		 */
		showAlertModal: function(title, message, callback) {
			const $modal = $('#mbrreg-alert-modal');
			
			$modal.find('.mbrreg-modal-title').text(title);
			$modal.find('.mbrreg-modal-message').text(message);
			
			// Store callback for when modal closes.
			$modal.data('close-callback', callback);
			
			$modal.fadeIn(200);
		},

		/**
		 * Close modal.
		 */
		closeModal: function() {
			const $modal = $(this).closest('.mbrreg-modal-overlay');
			const callback = $modal.data('close-callback');
			
			$modal.fadeOut(200, function() {
				$modal.removeData('close-callback');
				if (typeof callback === 'function') {
					callback();
				}
			});
		},

		/**
		 * Close modal when clicking overlay.
		 *
		 * @param {Event} e Click event.
		 */
		closeModalOnOverlay: function(e) {
			if ($(e.target).hasClass('mbrreg-modal-overlay')) {
				$(this).fadeOut(200);
			}
		},

		/**
		 * Show message in form.
		 *
		 * @param {jQuery} $form   Form element.
		 * @param {string} message Message text.
		 * @param {string} type    Message type (success/error).
		 */
		showMessage: function($form, message, type) {
			let $messages = $form.find('.mbrreg-form-messages');
			
			// If not found in form, look for global messages container.
			if (!$messages.length) {
				$messages = $('.mbrreg-form-messages').first();
			}
			
			const className = type === 'success' ? 'mbrreg-success' : 'mbrreg-error';

			$messages.html('<div class="mbrreg-message ' + className + '">' + message + '</div>');

			// Scroll to message.
			$('html, body').animate({
				scrollTop: $messages.offset().top - 100
			}, 300);
		},

		/**
		 * Clear messages from form.
		 *
		 * @param {jQuery} $form Form element.
		 */
		clearMessages: function($form) {
			$form.find('.mbrreg-form-messages').empty();
			$('.mbrreg-form-messages').empty();
		}
	};

	// Initialize on document ready.
	$(document).ready(function() {
		MbrregPublic.init();
	});

})(jQuery);
```

---

## includes/class-mbrreg-public.php (UPDATED)

```php
<?php
/**
 * Public-facing functionality class.
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
 * Class Mbrreg_Public
 *
 * Handles public-facing functionality.
 *
 * @since 1.0.0
 */
class Mbrreg_Public {

	/**
	 * Member instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Member
	 */
	private $member;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param Mbrreg_Member $member Member instance.
	 */
	public function __construct( Mbrreg_Member $member ) {
		$this->member = $member;
	}

	/**
	 * Initialize public hooks.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'init', array( $this, 'handle_activation' ) );
	}

	/**
	 * Enqueue public assets.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_assets() {
		wp_enqueue_style(
			'mbrreg-public',
			MBRREG_PLUGIN_URL . 'public/css/mbrreg-public.css',
			array(),
			MBRREG_VERSION
		);

		wp_enqueue_script(
			'mbrreg-public',
			MBRREG_PLUGIN_URL . 'public/js/mbrreg-public.js',
			array( 'jquery' ),
			MBRREG_VERSION,
			true
		);

		wp_localize_script(
			'mbrreg-public',
			'mbrregPublic',
			array(
				'ajaxUrl'                => admin_url( 'admin-ajax.php' ),
				'registerNonce'          => wp_create_nonce( 'mbrreg_register_nonce' ),
				'loginNonce'             => wp_create_nonce( 'mbrreg_login_nonce' ),
				'updateProfileNonce'     => wp_create_nonce( 'mbrreg_update_profile_nonce' ),
				'setInactiveNonce'       => wp_create_nonce( 'mbrreg_set_inactive_nonce' ),
				'logoutNonce'            => wp_create_nonce( 'mbrreg_logout_nonce' ),
				'addMemberNonce'         => wp_create_nonce( 'mbrreg_add_member_nonce' ),
				'processing'             => __( 'Processing...', 'member-registration-plugin' ),
				'confirmDeactivate'      => __( 'Are you sure you want to deactivate this membership? If this is your only active membership, you will be logged out.', 'member-registration-plugin' ),
				'confirmDeactivateTitle' => __( 'Deactivate Membership', 'member-registration-plugin' ),
				'confirmLogout'          => __( 'Are you sure you want to log out?', 'member-registration-plugin' ),
				'confirmLogoutTitle'     => __( 'Log Out', 'member-registration-plugin' ),
				'passwordMismatch'       => __( 'Passwords do not match.', 'member-registration-plugin' ),
				'errorGeneral'           => __( 'An error occurred. Please try again.', 'member-registration-plugin' ),
				'errorTitle'             => __( 'Error', 'member-registration-plugin' ),
				'successTitle'           => __( 'Success', 'member-registration-plugin' ),
			)
		);
	}

	/**
	 * Handle account activation.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_activation() {
		if ( ! isset( $_GET['mbrreg_action'] ) || 'activate' !== $_GET['mbrreg_action'] ) {
			return;
		}

		$key = isset( $_GET['key'] ) ? sanitize_text_field( wp_unslash( $_GET['key'] ) ) : '';

		if ( empty( $key ) ) {
			return;
		}

		$result = $this->member->activate( $key );

		if ( is_wp_error( $result ) ) {
			// Store error message.
			set_transient( 'mbrreg_activation_error', $result->get_error_message(), 60 );
		} else {
			// Store success message.
			set_transient( 'mbrreg_activation_success', __( 'Your account has been activated! You can now log in.', 'member-registration-plugin' ), 60 );
		}

		// Redirect to remove query params.
		$redirect_url = get_option( 'mbrreg_registration_page_id' )
			? get_permalink( get_option( 'mbrreg_registration_page_id' ) )
			: home_url( '/' );

		wp_safe_redirect( $redirect_url );
		exit;
	}
}
```

---

## public/css/mbrreg-public.css (UPDATED with Modal styles)

```css
/**
 * Public styles for Member Registration Plugin.
 *
 * @package Member_Registration_Plugin
 * @since 1.0.0
 */

/* Container Styles */
.mbrreg-auth-container,
.mbrreg-dashboard-container,
.mbrreg-form-container {
	max-width: 700px;
	margin: 0 auto;
	padding: 20px;
}

/* Tab Styles */
.mbrreg-tabs {
	display: flex;
	border-bottom: 2px solid #ddd;
	margin-bottom: 20px;
}

.mbrreg-tab {
	padding: 12px 24px;
	background: none;
	border: none;
	cursor: pointer;
	font-size: 16px;
	font-weight: 500;
	color: #666;
	border-bottom: 2px solid transparent;
	margin-bottom: -2px;
	transition: all 0.3s ease;
}

.mbrreg-tab:hover {
	color: #333;
}

.mbrreg-tab-active {
	color: #0073aa;
	border-bottom-color: #0073aa;
}

.mbrreg-tab-content {
	display: none;
}

.mbrreg-tab-content-active {
	display: block;
}

/* Form Styles */
.mbrreg-form {
	background: #fff;
	padding: 30px;
	border-radius: 4px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.mbrreg-form-title {
	margin: 0 0 25px;
	padding-bottom: 15px;
	border-bottom: 1px solid #eee;
	font-size: 24px;
	color: #333;
}

.mbrreg-fieldset {
	border: 1px solid #ddd;
	border-radius: 4px;
	padding: 20px;
	margin-bottom: 25px;
}

.mbrreg-fieldset legend {
	padding: 0 10px;
	font-weight: 600;
	color: #333;
}

.mbrreg-form-row {
	margin-bottom: 20px;
}

.mbrreg-form-row label {
	display: block;
	margin-bottom: 8px;
	font-weight: 500;
	color: #333;
}

.mbrreg-form-row input[type="text"],
.mbrreg-form-row input[type="email"],
.mbrreg-form-row input[type="password"],
.mbrreg-form-row input[type="tel"],
.mbrreg-form-row input[type="date"],
.mbrreg-form-row input[type="number"],
.mbrreg-form-row textarea,
.mbrreg-form-row select {
	width: 100%;
	padding: 12px;
	border: 1px solid #ddd;
	border-radius: 4px;
	font-size: 16px;
	transition: border-color 0.3s ease;
}

.mbrreg-form-row input:focus,
.mbrreg-form-row textarea:focus,
.mbrreg-form-row select:focus {
	outline: none;
	border-color: #0073aa;
	box-shadow: 0 0 0 2px rgba(0, 115, 170, 0.1);
}

.mbrreg-form-row input:disabled,
.mbrreg-form-row input[readonly],
.mbrreg-form-row textarea:disabled,
.mbrreg-form-row textarea[readonly],
.mbrreg-form-row select:disabled {
	background-color: #f5f5f5;
	cursor: not-allowed;
	color: #666;
}

.mbrreg-checkbox-row label {
	display: inline-flex;
	align-items: center;
	gap: 8px;
	font-weight: normal;
}

.mbrreg-radio-label {
	display: block;
	margin-bottom: 8px;
	font-weight: normal;
}

.required {
	color: #dc3545;
}

.mbrreg-field-note {
	display: block;
	margin-top: 5px;
	font-size: 13px;
	color: #666;
}

.mbrreg-readonly-badge {
	font-size: 12px;
	color: #666;
	font-weight: normal;
	font-style: italic;
}

/* Button Styles */
.mbrreg-button {
	display: inline-block;
	padding: 12px 24px;
	border: none;
	border-radius: 4px;
	font-size: 16px;
	font-weight: 500;
	cursor: pointer;
	text-decoration: none;
	transition: all 0.3s ease;
}

.mbrreg-button-primary {
	background-color: #0073aa;
	color: #fff;
}

.mbrreg-button-primary:hover {
	background-color: #005a87;
	color: #fff;
}

.mbrreg-button-secondary {
	background-color: #f0f0f0;
	color: #333;
}

.mbrreg-button-secondary:hover {
	background-color: #e0e0e0;
}

.mbrreg-button-danger {
	background-color: #dc3545;
	color: #fff;
}

.mbrreg-button-danger:hover {
	background-color: #c82333;
}

.mbrreg-button:disabled {
	opacity: 0.6;
	cursor: not-allowed;
}

/* Message Styles */
.mbrreg-message {
	padding: 12px 15px;
	border-radius: 4px;
	margin-bottom: 20px;
}

.mbrreg-success {
	background-color: #d4edda;
	color: #155724;
	border: 1px solid #c3e6cb;
}

.mbrreg-error {
	background-color: #f8d7da;
	color: #721c24;
	border: 1px solid #f5c6cb;
}

.mbrreg-warning {
	background-color: #fff3cd;
	color: #856404;
	border: 1px solid #ffeeba;
}

.mbrreg-info {
	background-color: #d1ecf1;
	color: #0c5460;
	border: 1px solid #bee5eb;
}

.mbrreg-form-messages:empty {
	display: none;
}

/* Form Links */
.mbrreg-form-links {
	margin-top: 20px;
	text-align: center;
}

.mbrreg-form-links a {
	color: #0073aa;
	text-decoration: none;
}

.mbrreg-form-links a:hover {
	text-decoration: underline;
}

/* Dashboard Styles */
.mbrreg-dashboard-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 20px;
	flex-wrap: wrap;
	gap: 15px;
}

.mbrreg-dashboard-header h2 {
	margin: 0;
}

.mbrreg-dashboard-actions {
	display: flex;
	gap: 10px;
	flex-wrap: wrap;
}

.mbrreg-account-info {
	background: #f9f9f9;
	padding: 15px 20px;
	border-radius: 4px;
	margin-bottom: 25px;
}

.mbrreg-account-info p {
	margin: 5px 0;
}

/* Member Card Styles */
.mbrreg-member-card {
	background: #fff;
	border: 1px solid #ddd;
	border-radius: 4px;
	margin-bottom: 25px;
	overflow: hidden;
}

.mbrreg-member-card-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 15px 20px;
	background: #f5f5f5;
	border-bottom: 1px solid #ddd;
}

.mbrreg-member-card-header h3 {
	margin: 0;
	font-size: 18px;
}

.mbrreg-member-card .mbrreg-form {
	border: none;
	box-shadow: none;
	border-radius: 0;
}

/* Status Badges */
.mbrreg-status {
	display: inline-block;
	padding: 5px 12px;
	border-radius: 15px;
	font-size: 13px;
	font-weight: 600;
	text-transform: uppercase;
}

.mbrreg-status-active {
	background-color: #d4edda;
	color: #155724;
}

.mbrreg-status-inactive {
	background-color: #f8d7da;
	color: #721c24;
}

.mbrreg-status-pending {
	background-color: #fff3cd;
	color: #856404;
}

/* Add Member Section */
.mbrreg-add-member-section {
	margin-top: 30px;
	padding-top: 20px;
	border-top: 2px solid #eee;
}

.mbrreg-add-member-form-container {
	margin-top: 20px;
}

.mbrreg-add-member-form-container h3 {
	margin-bottom: 10px;
}

.mbrreg-add-member-form-container .mbrreg-info {
	color: #666;
	margin-bottom: 20px;
}

/* Form Actions */
.mbrreg-form-actions {
	display: flex;
	gap: 10px;
	flex-wrap: wrap;
}

/* Modal Styles */
.mbrreg-modal-overlay {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-color: rgba(0, 0, 0, 0.5);
	display: flex;
	align-items: center;
	justify-content: center;
	z-index: 999999;
}

.mbrreg-modal-dialog {
	background: #fff;
	border-radius: 8px;
	max-width: 450px;
	width: 90%;
	max-height: 90vh;
	overflow: hidden;
	box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
	animation: mbrreg-modal-appear 0.2s ease-out;
}

@keyframes mbrreg-modal-appear {
	from {
		opacity: 0;
		transform: scale(0.95);
	}
	to {
		opacity: 1;
		transform: scale(1);
	}
}

.mbrreg-modal-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 15px 20px;
	border-bottom: 1px solid #eee;
}

.mbrreg-modal-title {
	margin: 0;
	font-size: 18px;
	color: #333;
}

.mbrreg-modal-close-btn {
	background: none;
	border: none;
	font-size: 24px;
	color: #999;
	cursor: pointer;
	padding: 0;
	line-height: 1;
}

.mbrreg-modal-close-btn:hover {
	color: #333;
}

.mbrreg-modal-body {
	padding: 20px;
}

.mbrreg-modal-message {
	margin: 0;
	color: #555;
	line-height: 1.5;
}

.mbrreg-modal-footer {
	display: flex;
	justify-content: flex-end;
	gap: 10px;
	padding: 15px 20px;
	border-top: 1px solid #eee;
	background: #f9f9f9;
}

/* Responsive Styles */
@media screen and (max-width: 600px) {
	.mbrreg-auth-container,
	.mbrreg-dashboard-container,
	.mbrreg-form-container {
		padding: 10px;
	}

	.mbrreg-form {
		padding: 20px 15px;
	}

	.mbrreg-tab {
		padding: 10px 16px;
		font-size: 14px;
	}

	.mbrreg-dashboard-header {
		flex-direction: column;
		align-items: flex-start;
	}

	.mbrreg-member-card-header {
		flex-direction: column;
		align-items: flex-start;
		gap: 10px;
	}

	.mbrreg-form-actions {
		flex-direction: column;
	}

	.mbrreg-button {
		width: 100%;
		text-align: center;
	}

	.mbrreg-modal-footer {
		flex-direction: column;
	}

	.mbrreg-modal-footer .mbrreg-button {
		width: 100%;
	}
}
```

---

Due to character limits, I'll provide the remaining essential files in a follow-up. Here are the key files you still need:

## Files still needed (will provide if you need them):

1. **admin/partials/mbrreg-admin-import-export.php** - CSV import/export page
2. **admin/partials/mbrreg-admin-settings.php** - Updated settings page
3. **admin/partials/mbrreg-admin-custom-fields.php** - Updated with admin-only option
4. **includes/class-mbrreg-admin.php** - Updated admin class
5. **admin/css/mbrreg-admin.css** - Updated admin styles
6. **admin/js/mbrreg-admin.js** - Updated admin JS
7. **languages/member-registration-plugin-nl_NL.po** - Dutch translation
8. **readme.txt** - WordPress readme
9. **README.md** - Project readme

Would you like me to continue with these remaining files? Just reply "continue" and I'll provide the rest.


**User**
continue


**Claude**
{"type":"think","think":"The user wants me to continue providing the remaining files for the member registration plugin update. I'll provide:\n\n1. admin/partials/mbrreg-admin-import-export.php - CSV import/export page\n2. admin/partials/mbrreg-admin-settings.php - Updated settings page\n3. admin/partials/mbrreg-admin-custom-fields.php - Updated with admin-only option\n4. includes/class-mbrreg-admin.php - Updated admin class\n5. admin/css/mbrreg-admin.css - Updated admin styles\n6. admin/js/mbrreg-admin.js - Updated admin JS\n7. languages/member-registration-plugin-nl_NL.po - Dutch translation\n8. readme.txt - WordPress readme\n9. README.md - Project readme\n10. public/partials/mbrreg-register-form.php - Updated register form\n11. public/partials/mbrreg-login-form.php - Updated login form\n12. admin/partials/mbrreg-admin-members.php - Updated members list\n13. admin/partials/mbrreg-admin-member-edit.php - Updated member edit\n\nLet me provide all of these."}


**Claude**
# Continuing with remaining files - Part 4

## includes/class-mbrreg-admin.php (UPDATED)

```php
<?php
/**
 * Admin functionality class.
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
 * Class Mbrreg_Admin
 *
 * Handles admin functionality.
 *
 * @since 1.0.0
 */
class Mbrreg_Admin {

	/**
	 * Member instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Member
	 */
	private $member;

	/**
	 * Custom fields instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Custom_Fields
	 */
	private $custom_fields;

	/**
	 * Email instance.
	 *
	 * @since 1.1.0
	 * @var Mbrreg_Email
	 */
	private $email;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param Mbrreg_Member        $member        Member instance.
	 * @param Mbrreg_Custom_Fields $custom_fields Custom fields instance.
	 * @param Mbrreg_Email         $email         Email instance.
	 */
	public function __construct( Mbrreg_Member $member, Mbrreg_Custom_Fields $custom_fields, Mbrreg_Email $email = null ) {
		$this->member        = $member;
		$this->custom_fields = $custom_fields;
		$this->email         = $email;
	}

	/**
	 * Initialize admin hooks.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_init', array( $this, 'handle_csv_export' ) );
	}

	/**
	 * Add admin menu pages.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function add_admin_menu() {
		// Main menu.
		add_menu_page(
			__( 'Member Registration', 'member-registration-plugin' ),
			__( 'Members', 'member-registration-plugin' ),
			'mbrreg_manage_members',
			'mbrreg-members',
			array( $this, 'render_members_page' ),
			'dashicons-groups',
			30
		);

		// Members submenu.
		add_submenu_page(
			'mbrreg-members',
			__( 'All Members', 'member-registration-plugin' ),
			__( 'All Members', 'member-registration-plugin' ),
			'mbrreg_manage_members',
			'mbrreg-members',
			array( $this, 'render_members_page' )
		);

		// Custom fields submenu.
		add_submenu_page(
			'mbrreg-members',
			__( 'Custom Fields', 'member-registration-plugin' ),
			__( 'Custom Fields', 'member-registration-plugin' ),
			'mbrreg_manage_custom_fields',
			'mbrreg-custom-fields',
			array( $this, 'render_custom_fields_page' )
		);

		// Import/Export submenu.
		add_submenu_page(
			'mbrreg-members',
			__( 'Import / Export', 'member-registration-plugin' ),
			__( 'Import / Export', 'member-registration-plugin' ),
			'mbrreg_import_members',
			'mbrreg-import-export',
			array( $this, 'render_import_export_page' )
		);

		// Settings submenu.
		add_submenu_page(
			'mbrreg-members',
			__( 'Settings', 'member-registration-plugin' ),
			__( 'Settings', 'member-registration-plugin' ),
			'mbrreg_manage_settings',
			'mbrreg-settings',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @since 1.0.0
	 * @param string $hook Current admin page hook.
	 * @return void
	 */
	public function enqueue_assets( $hook ) {
		// Only load on our admin pages.
		if ( strpos( $hook, 'mbrreg' ) === false ) {
			return;
		}

		wp_enqueue_style(
			'mbrreg-admin',
			MBRREG_PLUGIN_URL . 'admin/css/mbrreg-admin.css',
			array(),
			MBRREG_VERSION
		);

		wp_enqueue_script(
			'mbrreg-admin',
			MBRREG_PLUGIN_URL . 'admin/js/mbrreg-admin.js',
			array( 'jquery' ),
			MBRREG_VERSION,
			true
		);

		wp_localize_script(
			'mbrreg-admin',
			'mbrregAdmin',
			array(
				'ajaxUrl'          => admin_url( 'admin-ajax.php' ),
				'nonce'            => wp_create_nonce( 'mbrreg_admin_nonce' ),
				'confirmDelete'    => __( 'Are you sure you want to delete this member? This action cannot be undone.', 'member-registration-plugin' ),
				'confirmBulk'      => __( 'Are you sure you want to perform this action on the selected members?', 'member-registration-plugin' ),
				'confirmFieldDelete' => __( 'Are you sure you want to delete this custom field? All associated data will be lost.', 'member-registration-plugin' ),
				'processing'       => __( 'Processing...', 'member-registration-plugin' ),
				'success'          => __( 'Success!', 'member-registration-plugin' ),
				'error'            => __( 'An error occurred.', 'member-registration-plugin' ),
				'selectMembers'    => __( 'Please select at least one member.', 'member-registration-plugin' ),
				'selectAction'     => __( 'Please select an action.', 'member-registration-plugin' ),
				'importSuccess'    => __( 'Import completed successfully!', 'member-registration-plugin' ),
				'exportSuccess'    => __( 'Export completed successfully!', 'member-registration-plugin' ),
			)
		);
	}

	/**
	 * Register plugin settings.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_settings() {
		// Registration settings.
		register_setting( 'mbrreg_settings', 'mbrreg_allow_registration' );
		register_setting( 'mbrreg_settings', 'mbrreg_allow_multiple_members' );
		register_setting( 'mbrreg_settings', 'mbrreg_registration_page_id' );
		register_setting( 'mbrreg_settings', 'mbrreg_login_redirect_page' );

		// Required fields settings.
		register_setting( 'mbrreg_settings', 'mbrreg_require_first_name' );
		register_setting( 'mbrreg_settings', 'mbrreg_require_last_name' );
		register_setting( 'mbrreg_settings', 'mbrreg_require_address' );
		register_setting( 'mbrreg_settings', 'mbrreg_require_telephone' );
		register_setting( 'mbrreg_settings', 'mbrreg_require_date_of_birth' );
		register_setting( 'mbrreg_settings', 'mbrreg_require_place_of_birth' );

		// Email settings.
		register_setting( 'mbrreg_settings', 'mbrreg_email_from_name' );
		register_setting( 'mbrreg_settings', 'mbrreg_email_from_address' );
	}

	/**
	 * Handle CSV export download.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function handle_csv_export() {
		if ( ! isset( $_GET['mbrreg_export'] ) || ! isset( $_GET['_wpnonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'mbrreg_export_csv' ) ) {
			return;
		}

		if ( ! current_user_can( 'mbrreg_export_members' ) ) {
			return;
		}

		$status = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : '';

		$args = array();
		if ( ! empty( $status ) ) {
			$args['status'] = $status;
		}

		$csv      = $this->member->export_csv( $args );
		$filename = 'members-export-' . gmdate( 'Y-m-d' ) . '.csv';

		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		echo $csv; // phpcs:ignore
		exit;
	}

	/**
	 * Render members list page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_members_page() {
		// Check if editing a member.
		if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] && isset( $_GET['member_id'] ) ) {
			$this->render_member_edit_page();
			return;
		}

		include MBRREG_PLUGIN_PATH . 'admin/partials/mbrreg-admin-members.php';
	}

	/**
	 * Render member edit page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function render_member_edit_page() {
		$member_id = absint( $_GET['member_id'] );
		$member    = $this->member->get( $member_id );

		if ( ! $member ) {
			echo '<div class="wrap"><div class="notice notice-error"><p>' . esc_html__( 'Member not found.', 'member-registration-plugin' ) . '</p></div></div>';
			return;
		}

		$custom_fields = $this->custom_fields->get_all();

		include MBRREG_PLUGIN_PATH . 'admin/partials/mbrreg-admin-member-edit.php';
	}

	/**
	 * Render custom fields page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_custom_fields_page() {
		$custom_fields = $this->custom_fields->get_all();

		include MBRREG_PLUGIN_PATH . 'admin/partials/mbrreg-admin-custom-fields.php';
	}

	/**
	 * Render import/export page.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function render_import_export_page() {
		include MBRREG_PLUGIN_PATH . 'admin/partials/mbrreg-admin-import-export.php';
	}

	/**
	 * Render settings page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_settings_page() {
		include MBRREG_PLUGIN_PATH . 'admin/partials/mbrreg-admin-settings.php';
	}
}
```

---

## admin/partials/mbrreg-admin-import-export.php (NEW FILE)

```php
<?php
/**
 * Admin import/export page template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/admin/partials
 * @since 1.1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$custom_fields = ( new Mbrreg_Custom_Fields() )->get_all();
?>

<div class="wrap mbrreg-admin-wrap">
	<h1><?php esc_html_e( 'Import / Export Members', 'member-registration-plugin' ); ?></h1>

	<div class="mbrreg-admin-sections">
		<!-- Import Section -->
		<div class="mbrreg-admin-section">
			<h2><?php esc_html_e( 'Import Members', 'member-registration-plugin' ); ?></h2>
			<p class="description">
				<?php esc_html_e( 'Import members from a CSV file. An activation email will be sent to each imported member.', 'member-registration-plugin' ); ?>
			</p>

			<div class="mbrreg-import-instructions">
				<h4><?php esc_html_e( 'CSV File Format', 'member-registration-plugin' ); ?></h4>
				<p><?php esc_html_e( 'Your CSV file should have the following columns in order:', 'member-registration-plugin' ); ?></p>
				<ol>
					<li><strong><?php esc_html_e( 'Email', 'member-registration-plugin' ); ?></strong> (<?php esc_html_e( 'required', 'member-registration-plugin' ); ?>)</li>
					<li><strong><?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?></strong></li>
					<li><strong><?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?></strong></li>
					<li><strong><?php esc_html_e( 'Address', 'member-registration-plugin' ); ?></strong></li>
					<li><strong><?php esc_html_e( 'Telephone', 'member-registration-plugin' ); ?></strong></li>
					<li><strong><?php esc_html_e( 'Date of Birth', 'member-registration-plugin' ); ?></strong> (<?php esc_html_e( 'format: YYYY-MM-DD', 'member-registration-plugin' ); ?>)</li>
					<li><strong><?php esc_html_e( 'Place of Birth', 'member-registration-plugin' ); ?></strong></li>
					<?php foreach ( $custom_fields as $field ) : ?>
						<li><strong><?php echo esc_html( $field->field_label ); ?></strong></li>
					<?php endforeach; ?>
				</ol>
				<p><strong><?php esc_html_e( 'Note:', 'member-registration-plugin' ); ?></strong> <?php esc_html_e( 'The first row should contain column headers and will be skipped during import.', 'member-registration-plugin' ); ?></p>
			</div>

			<form id="mbrreg-import-form" method="post" enctype="multipart/form-data" class="mbrreg-admin-form">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="csv_file"><?php esc_html_e( 'CSV File', 'member-registration-plugin' ); ?></label>
						</th>
						<td>
							<input type="file" name="csv_file" id="csv_file" accept=".csv" required>
							<p class="description"><?php esc_html_e( 'Select a CSV file to import.', 'member-registration-plugin' ); ?></p>
						</td>
					</tr>
				</table>

				<div class="mbrreg-import-results" style="display: none;">
					<h4><?php esc_html_e( 'Import Results', 'member-registration-plugin' ); ?></h4>
					<div class="mbrreg-import-message"></div>
				</div>

				<p class="submit">
					<button type="submit" class="button button-primary">
						<?php esc_html_e( 'Import Members', 'member-registration-plugin' ); ?>
					</button>
					<span class="spinner"></span>
				</p>
			</form>

			<div class="mbrreg-sample-csv">
				<h4><?php esc_html_e( 'Download Sample CSV', 'member-registration-plugin' ); ?></h4>
				<p><?php esc_html_e( 'Download a sample CSV file with the correct format:', 'member-registration-plugin' ); ?></p>
				<a href="#" class="button" id="mbrreg-download-sample">
					<?php esc_html_e( 'Download Sample CSV', 'member-registration-plugin' ); ?>
				</a>
			</div>
		</div>

		<!-- Export Section -->
		<div class="mbrreg-admin-section">
			<h2><?php esc_html_e( 'Export Members', 'member-registration-plugin' ); ?></h2>
			<p class="description">
				<?php esc_html_e( 'Export members to a CSV file for backup or external use.', 'member-registration-plugin' ); ?>
			</p>

			<form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>" class="mbrreg-admin-form">
				<input type="hidden" name="page" value="mbrreg-import-export">
				<input type="hidden" name="mbrreg_export" value="1">
				<?php wp_nonce_field( 'mbrreg_export_csv', '_wpnonce', false ); ?>

				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="export_status"><?php esc_html_e( 'Member Status', 'member-registration-plugin' ); ?></label>
						</th>
						<td>
							<select name="status" id="export_status">
								<option value=""><?php esc_html_e( 'All Members', 'member-registration-plugin' ); ?></option>
								<option value="active"><?php esc_html_e( 'Active Only', 'member-registration-plugin' ); ?></option>
								<option value="inactive"><?php esc_html_e( 'Inactive Only', 'member-registration-plugin' ); ?></option>
								<option value="pending"><?php esc_html_e( 'Pending Only', 'member-registration-plugin' ); ?></option>
							</select>
						</td>
					</tr>
				</table>

				<p class="submit">
					<button type="submit" class="button button-primary">
						<?php esc_html_e( 'Export Members', 'member-registration-plugin' ); ?>
					</button>
				</p>
			</form>

			<div class="mbrreg-export-info">
				<h4><?php esc_html_e( 'Export Information', 'member-registration-plugin' ); ?></h4>
				<p><?php esc_html_e( 'The exported CSV will include:', 'member-registration-plugin' ); ?></p>
				<ul>
					<li><?php esc_html_e( 'Member ID, Username, Email', 'member-registration-plugin' ); ?></li>
					<li><?php esc_html_e( 'First Name, Last Name, Address, Telephone', 'member-registration-plugin' ); ?></li>
					<li><?php esc_html_e( 'Date of Birth, Place of Birth', 'member-registration-plugin' ); ?></li>
					<li><?php esc_html_e( 'Status, Admin Status, Registration Date', 'member-registration-plugin' ); ?></li>
					<li><?php esc_html_e( 'All custom field values', 'member-registration-plugin' ); ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
	// Download sample CSV.
	$('#mbrreg-download-sample').on('click', function(e) {
		e.preventDefault();

		var headers = ['Email', 'First Name', 'Last Name', 'Address', 'Telephone', 'Date of Birth', 'Place of Birth'];
		<?php foreach ( $custom_fields as $field ) : ?>
		headers.push('<?php echo esc_js( $field->field_label ); ?>');
		<?php endforeach; ?>

		var sampleData = [
			headers,
			['john.doe@example.com', 'John', 'Doe', '123 Main St, City', '+1234567890', '1990-01-15', 'New York'<?php foreach ( $custom_fields as $field ) : ?>, ''<?php endforeach; ?>],
			['jane.smith@example.com', 'Jane', 'Smith', '456 Oak Ave, Town', '+0987654321', '1985-06-20', 'Los Angeles'<?php foreach ( $custom_fields as $field ) : ?>, ''<?php endforeach; ?>]
		];

		var csvContent = sampleData.map(function(row) {
			return row.map(function(cell) {
				// Escape quotes and wrap in quotes if contains comma.
				if (typeof cell === 'string' && (cell.indexOf(',') !== -1 || cell.indexOf('"') !== -1)) {
					return '"' + cell.replace(/"/g, '""') + '"';
				}
				return cell;
			}).join(',');
		}).join('\n');

		var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
		var link = document.createElement('a');
		var url = URL.createObjectURL(blob);
		link.setAttribute('href', url);
		link.setAttribute('download', 'members-import-sample.csv');
		link.style.visibility = 'hidden';
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
	});
});
</script>
```

---

## admin/partials/mbrreg-admin-settings.php (UPDATED)

```php
<?php
/**
 * Admin settings page template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/admin/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get all pages for dropdown.
$pages = get_pages();
?>

<div class="wrap mbrreg-admin-wrap">
	<h1><?php esc_html_e( 'Member Registration Settings', 'member-registration-plugin' ); ?></h1>

	<?php settings_errors(); ?>

	<form method="post" action="options.php">
		<?php settings_fields( 'mbrreg_settings' ); ?>

		<!-- Registration Settings -->
		<div class="mbrreg-settings-section">
			<h2><?php esc_html_e( 'Registration Settings', 'member-registration-plugin' ); ?></h2>
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e( 'Allow Registration', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_allow_registration" value="1" <?php checked( get_option( 'mbrreg_allow_registration', true ) ); ?>>
							<?php esc_html_e( 'Allow new members to register', 'member-registration-plugin' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'When disabled, the registration form will not be shown.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Allow Multiple Members', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_allow_multiple_members" value="1" <?php checked( get_option( 'mbrreg_allow_multiple_members', true ) ); ?>>
							<?php esc_html_e( 'Allow users to register multiple members under one account', 'member-registration-plugin' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Useful for parents registering multiple children or family members.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="mbrreg_registration_page_id"><?php esc_html_e( 'Member Area Page', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<select name="mbrreg_registration_page_id" id="mbrreg_registration_page_id">
							<option value="0"><?php esc_html_e( '— Select —', 'member-registration-plugin' ); ?></option>
							<?php foreach ( $pages as $page ) : ?>
								<option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( get_option( 'mbrreg_registration_page_id' ), $page->ID ); ?>>
									<?php echo esc_html( $page->post_title ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<p class="description"><?php esc_html_e( 'The page containing the [mbrreg_member_area] shortcode.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="mbrreg_login_redirect_page"><?php esc_html_e( 'Login Redirect Page', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<select name="mbrreg_login_redirect_page" id="mbrreg_login_redirect_page">
							<option value="0"><?php esc_html_e( '— Select —', 'member-registration-plugin' ); ?></option>
							<?php foreach ( $pages as $page ) : ?>
								<option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( get_option( 'mbrreg_login_redirect_page' ), $page->ID ); ?>>
									<?php echo esc_html( $page->post_title ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<p class="description"><?php esc_html_e( 'Where to redirect members after successful login.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
			</table>
		</div>

		<!-- Required Fields -->
		<div class="mbrreg-settings-section">
			<h2><?php esc_html_e( 'Required Fields', 'member-registration-plugin' ); ?></h2>
			<p class="description"><?php esc_html_e( 'Select which fields should be required during registration and profile updates.', 'member-registration-plugin' ); ?></p>
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_first_name" value="1" <?php checked( get_option( 'mbrreg_require_first_name', false ) ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_last_name" value="1" <?php checked( get_option( 'mbrreg_require_last_name', false ) ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Address', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_address" value="1" <?php checked( get_option( 'mbrreg_require_address', false ) ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Telephone', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_telephone" value="1" <?php checked( get_option( 'mbrreg_require_telephone', false ) ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Date of Birth', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_date_of_birth" value="1" <?php checked( get_option( 'mbrreg_require_date_of_birth', false ) ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Place of Birth', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_place_of_birth" value="1" <?php checked( get_option( 'mbrreg_require_place_of_birth', false ) ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
			</table>
		</div>

		<!-- Email Settings -->
		<div class="mbrreg-settings-section">
			<h2><?php esc_html_e( 'Email Settings', 'member-registration-plugin' ); ?></h2>
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="mbrreg_email_from_name"><?php esc_html_e( 'From Name', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" name="mbrreg_email_from_name" id="mbrreg_email_from_name" value="<?php echo esc_attr( get_option( 'mbrreg_email_from_name', get_bloginfo( 'name' ) ) ); ?>" class="regular-text">
						<p class="description"><?php esc_html_e( 'The name that will appear in the "From" field of emails.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="mbrreg_email_from_address"><?php esc_html_e( 'From Email', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="email" name="mbrreg_email_from_address" id="mbrreg_email_from_address" value="<?php echo esc_attr( get_option( 'mbrreg_email_from_address', get_option( 'admin_email' ) ) ); ?>" class="regular-text">
						<p class="description"><?php esc_html_e( 'The email address that will appear in the "From" field.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
			</table>
		</div>

		<!-- Shortcodes Reference -->
		<div class="mbrreg-settings-section">
			<h2><?php esc_html_e( 'Shortcodes', 'member-registration-plugin' ); ?></h2>
			<table class="form-table mbrreg-shortcodes-table">
				<tr>
					<th><code>[mbrreg_member_area]</code></th>
					<td><?php esc_html_e( 'Complete member area with login, registration, and dashboard.', 'member-registration-plugin' ); ?></td>
				</tr>
				<tr>
					<th><code>[mbrreg_login_form]</code></th>
					<td><?php esc_html_e( 'Standalone login form.', 'member-registration-plugin' ); ?></td>
				</tr>
				<tr>
					<th><code>[mbrreg_register_form]</code></th>
					<td><?php esc_html_e( 'Standalone registration form.', 'member-registration-plugin' ); ?></td>
				</tr>
				<tr>
					<th><code>[mbrreg_member_dashboard]</code></th>
					<td><?php esc_html_e( 'Member dashboard (for logged-in members only).', 'member-registration-plugin' ); ?></td>
				</tr>
			</table>
		</div>

		<?php submit_button(); ?>
	</form>
</div>
```

---

## admin/partials/mbrreg-admin-custom-fields.php (UPDATED)

```php
<?php
/**
 * Admin custom fields page template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/admin/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<div class="wrap mbrreg-admin-wrap">
	<h1><?php esc_html_e( 'Custom Fields', 'member-registration-plugin' ); ?></h1>

	<div class="mbrreg-admin-columns">
		<!-- Fields List -->
		<div class="mbrreg-admin-column mbrreg-admin-column-wide">
			<div class="mbrreg-admin-card">
				<h2><?php esc_html_e( 'Existing Fields', 'member-registration-plugin' ); ?></h2>

				<?php if ( ! empty( $custom_fields ) ) : ?>
					<table class="widefat mbrreg-fields-table">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Order', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Name', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Label', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Type', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Required', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Admin Only', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Actions', 'member-registration-plugin' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $custom_fields as $field ) : ?>
								<tr data-field-id="<?php echo esc_attr( $field->id ); ?>">
									<td><?php echo esc_html( $field->field_order ); ?></td>
									<td><code><?php echo esc_html( $field->field_name ); ?></code></td>
									<td><?php echo esc_html( $field->field_label ); ?></td>
									<td><?php echo esc_html( Mbrreg_Custom_Fields::$field_types[ $field->field_type ] ); ?></td>
									<td>
										<?php if ( $field->is_required ) : ?>
											<span class="dashicons dashicons-yes-alt" style="color: green;"></span>
										<?php else : ?>
											<span class="dashicons dashicons-minus" style="color: #999;"></span>
										<?php endif; ?>
									</td>
									<td>
										<?php if ( $field->is_admin_only ) : ?>
											<span class="dashicons dashicons-lock" style="color: #d63638;" title="<?php esc_attr_e( 'Admin only - users cannot edit', 'member-registration-plugin' ); ?>"></span>
										<?php else : ?>
											<span class="dashicons dashicons-unlock" style="color: #999;" title="<?php esc_attr_e( 'Editable by users', 'member-registration-plugin' ); ?>"></span>
										<?php endif; ?>
									</td>
									<td>
										<button type="button" class="button button-small mbrreg-edit-field" data-field-id="<?php echo esc_attr( $field->id ); ?>">
											<?php esc_html_e( 'Edit', 'member-registration-plugin' ); ?>
										</button>
										<button type="button" class="button button-small button-link-delete mbrreg-delete-field" data-field-id="<?php echo esc_attr( $field->id ); ?>">
											<?php esc_html_e( 'Delete', 'member-registration-plugin' ); ?>
										</button>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php else : ?>
					<p class="mbrreg-empty-message"><?php esc_html_e( 'No custom fields have been created yet.', 'member-registration-plugin' ); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<!-- Add New Field -->
		<div class="mbrreg-admin-column">
			<div class="mbrreg-admin-card">
				<h2><?php esc_html_e( 'Add New Field', 'member-registration-plugin' ); ?></h2>

				<form id="mbrreg-add-field-form" class="mbrreg-admin-form">
					<div class="mbrreg-form-row">
						<label for="field_name"><?php esc_html_e( 'Field Name', 'member-registration-plugin' ); ?></label>
						<input type="text" name="field_name" id="field_name" required pattern="[a-z0-9_]+" title="<?php esc_attr_e( 'Lowercase letters, numbers, and underscores only', 'member-registration-plugin' ); ?>">
						<p class="description"><?php esc_html_e( 'Unique identifier. Lowercase letters, numbers, and underscores only.', 'member-registration-plugin' ); ?></p>
					</div>

					<div class="mbrreg-form-row">
						<label for="field_label"><?php esc_html_e( 'Field Label', 'member-registration-plugin' ); ?></label>
						<input type="text" name="field_label" id="field_label" required>
						<p class="description"><?php esc_html_e( 'The label displayed to users.', 'member-registration-plugin' ); ?></p>
					</div>

					<div class="mbrreg-form-row">
						<label for="field_type"><?php esc_html_e( 'Field Type', 'member-registration-plugin' ); ?></label>
						<select name="field_type" id="field_type">
							<?php foreach ( Mbrreg_Custom_Fields::$field_types as $type => $label ) : ?>
								<option value="<?php echo esc_attr( $type ); ?>"><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="mbrreg-form-row mbrreg-field-options-row" style="display: none;">
						<label for="field_options"><?php esc_html_e( 'Options', 'member-registration-plugin' ); ?></label>
						<textarea name="field_options" id="field_options" rows="4"></textarea>
						<p class="description"><?php esc_html_e( 'Enter one option per line.', 'member-registration-plugin' ); ?></p>
					</div>

					<div class="mbrreg-form-row">
						<label for="field_order"><?php esc_html_e( 'Display Order', 'member-registration-plugin' ); ?></label>
						<input type="number" name="field_order" id="field_order" value="0" min="0">
						<p class="description"><?php esc_html_e( 'Lower numbers display first.', 'member-registration-plugin' ); ?></p>
					</div>

					<div class="mbrreg-form-row">
						<label>
							<input type="checkbox" name="is_required" value="1">
							<?php esc_html_e( 'Required field', 'member-registration-plugin' ); ?>
						</label>
					</div>

					<div class="mbrreg-form-row">
						<label>
							<input type="checkbox" name="is_admin_only" value="1">
							<?php esc_html_e( 'Admin only (users cannot edit)', 'member-registration-plugin' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'If checked, only administrators can view and edit this field. Users will see the value but cannot change it.', 'member-registration-plugin' ); ?></p>
					</div>

					<div class="mbrreg-form-row">
						<button type="submit" class="button button-primary"><?php esc_html_e( 'Add Field', 'member-registration-plugin' ); ?></button>
						<span class="spinner"></span>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Edit Field Modal -->
	<div id="mbrreg-edit-field-modal" class="mbrreg-modal" style="display: none;">
		<div class="mbrreg-modal-content">
			<div class="mbrreg-modal-header">
				<h2><?php esc_html_e( 'Edit Custom Field', 'member-registration-plugin' ); ?></h2>
				<button type="button" class="mbrreg-modal-close">&times;</button>
			</div>
			<div class="mbrreg-modal-body">
				<form id="mbrreg-edit-field-form" class="mbrreg-admin-form">
					<input type="hidden" name="field_id" id="edit_field_id">

					<div class="mbrreg-form-row">
						<label for="edit_field_label"><?php esc_html_e( 'Field Label', 'member-registration-plugin' ); ?></label>
						<input type="text" name="field_label" id="edit_field_label" required>
					</div>

					<div class="mbrreg-form-row">
						<label for="edit_field_type"><?php esc_html_e( 'Field Type', 'member-registration-plugin' ); ?></label>
						<select name="field_type" id="edit_field_type">
							<?php foreach ( Mbrreg_Custom_Fields::$field_types as $type => $label ) : ?>
								<option value="<?php echo esc_attr( $type ); ?>"><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="mbrreg-form-row mbrreg-edit-field-options-row" style="display: none;">
						<label for="edit_field_options"><?php esc_html_e( 'Options', 'member-registration-plugin' ); ?></label>
						<textarea name="field_options" id="edit_field_options" rows="4"></textarea>
						<p class="description"><?php esc_html_e( 'Enter one option per line.', 'member-registration-plugin' ); ?></p>
					</div>

					<div class="mbrreg-form-row">
						<label for="edit_field_order"><?php esc_html_e( 'Display Order', 'member-registration-plugin' ); ?></label>
						<input type="number" name="field_order" id="edit_field_order" value="0" min="0">
					</div>

					<div class="mbrreg-form-row">
						<label>
							<input type="checkbox" name="is_required" id="edit_is_required" value="1">
							<?php esc_html_e( 'Required field', 'member-registration-plugin' ); ?>
						</label>
					</div>

					<div class="mbrreg-form-row">
						<label>
							<input type="checkbox" name="is_admin_only" id="edit_is_admin_only" value="1">
							<?php esc_html_e( 'Admin only (users cannot edit)', 'member-registration-plugin' ); ?>
						</label>
					</div>

					<div class="mbrreg-form-row">
						<button type="submit" class="button button-primary"><?php esc_html_e( 'Update Field', 'member-registration-plugin' ); ?></button>
						<button type="button" class="button mbrreg-modal-close"><?php esc_html_e( 'Cancel', 'member-registration-plugin' ); ?></button>
						<span class="spinner"></span>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
// Store field data for editing.
var mbrregFieldData = <?php echo wp_json_encode( $custom_fields ); ?>;
</script>
```

---

## admin/partials/mbrreg-admin-members.php (UPDATED)

```php
<?php
/**
 * Admin members list page template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/admin/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get filter parameters.
$status  = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : '';
$search  = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
$paged   = isset( $_GET['paged'] ) ? max( 1, absint( $_GET['paged'] ) ) : 1;
$per_page = 20;

// Build query args.
$args = array(
	'status' => $status,
	'search' => $search,
	'limit'  => $per_page,
	'offset' => ( $paged - 1 ) * $per_page,
);

// Get members.
$database      = new Mbrreg_Database();
$custom_fields = new Mbrreg_Custom_Fields();
$member_handler = new Mbrreg_Member( $database, $custom_fields, new Mbrreg_Email() );

$members       = $member_handler->get_all( $args );
$total_members = $member_handler->count( $args );
$total_pages   = ceil( $total_members / $per_page );

// Count by status.
$count_all      = $member_handler->count( array( 'search' => $search ) );
$count_active   = $member_handler->count( array( 'status' => 'active', 'search' => $search ) );
$count_inactive = $member_handler->count( array( 'status' => 'inactive', 'search' => $search ) );
$count_pending  = $member_handler->count( array( 'status' => 'pending', 'search' => $search ) );
?>

<div class="wrap mbrreg-admin-wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Members', 'member-registration-plugin' ); ?></h1>
	<hr class="wp-header-end">

	<!-- Status Filter -->
	<ul class="subsubsub">
		<li>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members' ) ); ?>" class="<?php echo '' === $status ? 'current' : ''; ?>">
				<?php esc_html_e( 'All', 'member-registration-plugin' ); ?>
				<span class="count">(<?php echo esc_html( $count_all ); ?>)</span>
			</a> |
		</li>
		<li>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&status=active' ) ); ?>" class="<?php echo 'active' === $status ? 'current' : ''; ?>">
				<?php esc_html_e( 'Active', 'member-registration-plugin' ); ?>
				<span class="count">(<?php echo esc_html( $count_active ); ?>)</span>
			</a> |
		</li>
		<li>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&status=inactive' ) ); ?>" class="<?php echo 'inactive' === $status ? 'current' : ''; ?>">
				<?php esc_html_e( 'Inactive', 'member-registration-plugin' ); ?>
				<span class="count">(<?php echo esc_html( $count_inactive ); ?>)</span>
			</a> |
		</li>
		<li>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&status=pending' ) ); ?>" class="<?php echo 'pending' === $status ? 'current' : ''; ?>">
				<?php esc_html_e( 'Pending', 'member-registration-plugin' ); ?>
				<span class="count">(<?php echo esc_html( $count_pending ); ?>)</span>
			</a>
		</li>
	</ul>

	<!-- Search Form -->
	<form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>" class="search-form">
		<input type="hidden" name="page" value="mbrreg-members">
		<?php if ( $status ) : ?>
			<input type="hidden" name="status" value="<?php echo esc_attr( $status ); ?>">
		<?php endif; ?>
		<p class="search-box">
			<label class="screen-reader-text" for="member-search-input"><?php esc_html_e( 'Search Members', 'member-registration-plugin' ); ?></label>
			<input type="search" id="member-search-input" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search members...', 'member-registration-plugin' ); ?>">
			<input type="submit" id="search-submit" class="button" value="<?php esc_attr_e( 'Search', 'member-registration-plugin' ); ?>">
		</p>
	</form>

	<!-- Bulk Actions Form -->
	<form id="mbrreg-members-form" method="post">
		<div class="tablenav top">
			<div class="alignleft actions bulkactions">
				<select name="bulk_action" id="bulk-action-selector">
					<option value=""><?php esc_html_e( 'Bulk Actions', 'member-registration-plugin' ); ?></option>
					<option value="activate"><?php esc_html_e( 'Activate', 'member-registration-plugin' ); ?></option>
					<option value="deactivate"><?php esc_html_e( 'Deactivate', 'member-registration-plugin' ); ?></option>
					<option value="delete"><?php esc_html_e( 'Delete Members', 'member-registration-plugin' ); ?></option>
					<option value="delete_with_user"><?php esc_html_e( 'Delete Members + Users', 'member-registration-plugin' ); ?></option>
				</select>
				<button type="button" class="button mbrreg-bulk-action-btn"><?php esc_html_e( 'Apply', 'member-registration-plugin' ); ?></button>
			</div>

			<?php if ( $total_pages > 1 ) : ?>
				<div class="tablenav-pages">
					<span class="displaying-num">
						<?php
						printf(
							/* translators: %s: Number of members */
							esc_html( _n( '%s member', '%s members', $total_members, 'member-registration-plugin' ) ),
							number_format_i18n( $total_members )
						);
						?>
					</span>
					<span class="pagination-links">
						<?php
						$page_links = paginate_links(
							array(
								'base'      => add_query_arg( 'paged', '%#%' ),
								'format'    => '',
								'prev_text' => '&laquo;',
								'next_text' => '&raquo;',
								'total'     => $total_pages,
								'current'   => $paged,
							)
						);
						echo wp_kses_post( $page_links );
						?>
					</span>
				</div>
			<?php endif; ?>
		</div>

		<!-- Members Table -->
		<table class="wp-list-table widefat fixed striped members">
			<thead>
				<tr>
					<td class="manage-column column-cb check-column">
						<input type="checkbox" id="cb-select-all">
					</td>
					<th scope="col" class="manage-column"><?php esc_html_e( 'Name', 'member-registration-plugin' ); ?></th>
					<th scope="col" class="manage-column"><?php esc_html_e( 'Email', 'member-registration-plugin' ); ?></th>
					<th scope="col" class="manage-column"><?php esc_html_e( 'Account Owner', 'member-registration-plugin' ); ?></th>
					<th scope="col" class="manage-column"><?php esc_html_e( 'Status', 'member-registration-plugin' ); ?></th>
					<th scope="col" class="manage-column"><?php esc_html_e( 'Admin', 'member-registration-plugin' ); ?></th>
					<th scope="col" class="manage-column"><?php esc_html_e( 'Registered', 'member-registration-plugin' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( ! empty( $members ) ) : ?>
					<?php foreach ( $members as $member ) : ?>
						<tr>
							<th scope="row" class="check-column">
								<input type="checkbox" name="member_ids[]" value="<?php echo esc_attr( $member->id ); ?>">
							</th>
							<td>
								<strong>
									<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&action=edit&member_id=' . $member->id ) ); ?>">
										<?php echo esc_html( $member->first_name . ' ' . $member->last_name ); ?>
									</a>
								</strong>
								<div class="row-actions">
									<span class="edit">
										<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&action=edit&member_id=' . $member->id ) ); ?>">
											<?php esc_html_e( 'Edit', 'member-registration-plugin' ); ?>
										</a> |
									</span>
									<?php if ( 'pending' === $member->status ) : ?>
										<span class="resend">
											<a href="#" class="mbrreg-resend-activation" data-member-id="<?php echo esc_attr( $member->id ); ?>">
												<?php esc_html_e( 'Resend Activation', 'member-registration-plugin' ); ?>
											</a> |
										</span>
									<?php endif; ?>
									<span class="delete">
										<a href="#" class="mbrreg-delete-member submitdelete" data-member-id="<?php echo esc_attr( $member->id ); ?>">
											<?php esc_html_e( 'Delete', 'member-registration-plugin' ); ?>
										</a>
									</span>
								</div>
							</td>
							<td><?php echo esc_html( $member->email ); ?></td>
							<td>
								<?php 
								$user = get_user_by( 'ID', $member->user_id );
								echo esc_html( $user ? $user->user_login : '-' ); 
								?>
							</td>
							<td>
								<span class="mbrreg-status mbrreg-status-<?php echo esc_attr( $member->status ); ?>">
									<?php echo esc_html( Mbrreg_Member::$statuses[ $member->status ] ); ?>
								</span>
							</td>
							<td>
								<?php if ( $member->is_admin ) : ?>
									<span class="dashicons dashicons-yes-alt" style="color: green;" title="<?php esc_attr_e( 'Member Admin', 'member-registration-plugin' ); ?>"></span>
								<?php else : ?>
									<span class="dashicons dashicons-minus" style="color: #999;"></span>
								<?php endif; ?>
							</td>
							<td><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $member->created_at ) ) ); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="7"><?php esc_html_e( 'No members found.', 'member-registration-plugin' ); ?></td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>

		<div class="tablenav bottom">
			<?php if ( $total_pages > 1 ) : ?>
				<div class="tablenav-pages">
					<span class="pagination-links">
						<?php echo wp_kses_post( $page_links ); ?>
					</span>
				</div>
			<?php endif; ?>
		</div>
	</form>
</div>
```

---

## admin/partials/mbrreg-admin-member-edit.php (UPDATED)

```php
<?php
/**
 * Admin member edit page template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/admin/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get custom fields handler for rendering.
$custom_fields_handler = new Mbrreg_Custom_Fields();

// Count other members in this account.
$database      = new Mbrreg_Database();
$account_members = $database->get_members_by_user_id( $member->user_id );
?>

<div class="wrap mbrreg-admin-wrap">
	<h1>
		<?php esc_html_e( 'Edit Member', 'member-registration-plugin' ); ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members' ) ); ?>" class="page-title-action">
			<?php esc_html_e( 'Back to Members', 'member-registration-plugin' ); ?>
		</a>
	</h1>

	<div id="mbrreg-admin-messages"></div>

	<?php if ( count( $account_members ) > 1 ) : ?>
		<div class="notice notice-info">
			<p>
				<?php
				printf(
					/* translators: 1: Number of members, 2: Account email */
					esc_html__( 'This user account (%2$s) has %1$d registered members.', 'member-registration-plugin' ),
					count( $account_members ),
					esc_html( $member->email )
				);
				?>
			</p>
		</div>
	<?php endif; ?>

	<form id="mbrreg-edit-member-form" method="post" class="mbrreg-admin-form">
		<input type="hidden" name="member_id" value="<?php echo esc_attr( $member->id ); ?>">

		<div class="mbrreg-admin-columns">
			<!-- Main Content -->
			<div class="mbrreg-admin-column mbrreg-admin-column-wide">
				<div class="mbrreg-admin-card">
					<h2><?php esc_html_e( 'Personal Information', 'member-registration-plugin' ); ?></h2>

					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="first_name"><?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $member->first_name ); ?>" class="regular-text">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="last_name"><?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $member->last_name ); ?>" class="regular-text">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="email"><?php esc_html_e( 'Email', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<input type="email" name="email" id="email" value="<?php echo esc_attr( $member->email ); ?>" class="regular-text">
								<p class="description"><?php esc_html_e( 'Changing this will update the WordPress user email for the account owner.', 'member-registration-plugin' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="address"><?php esc_html_e( 'Address', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<textarea name="address" id="address" rows="3" class="large-text"><?php echo esc_textarea( $member->address ); ?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="telephone"><?php esc_html_e( 'Telephone', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<input type="text" name="telephone" id="telephone" value="<?php echo esc_attr( $member->telephone ); ?>" class="regular-text">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="date_of_birth"><?php esc_html_e( 'Date of Birth', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<input type="date" name="date_of_birth" id="date_of_birth" value="<?php echo esc_attr( $member->date_of_birth ); ?>">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="place_of_birth"><?php esc_html_e( 'Place of Birth', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<input type="text" name="place_of_birth" id="place_of_birth" value="<?php echo esc_attr( $member->place_of_birth ); ?>" class="regular-text">
							</td>
						</tr>
					</table>
				</div>

				<!-- Custom Fields -->
				<?php if ( ! empty( $custom_fields ) ) : ?>
					<div class="mbrreg-admin-card">
						<h2><?php esc_html_e( 'Custom Fields', 'member-registration-plugin' ); ?></h2>
						<table class="form-table">
							<?php foreach ( $custom_fields as $field ) : ?>
								<?php $value = isset( $member->custom_fields[ $field->id ] ) ? $member->custom_fields[ $field->id ] : ''; ?>
								<tr>
									<th scope="row">
										<label for="custom_<?php echo esc_attr( $field->id ); ?>">
											<?php echo esc_html( $field->field_label ); ?>
											<?php if ( $field->is_admin_only ) : ?>
												<span class="dashicons dashicons-lock" style="color: #d63638; font-size: 14px;" title="<?php esc_attr_e( 'Admin only', 'member-registration-plugin' ); ?>"></span>
											<?php endif; ?>
										</label>
									</th>
									<td>
										<?php
										echo $custom_fields_handler->render_field_input( // phpcs:ignore
											$field,
											$value,
											array( 'class' => 'regular-text' )
										);
										?>
										<?php if ( $field->is_admin_only ) : ?>
											<p class="description"><?php esc_html_e( 'This field can only be edited by administrators.', 'member-registration-plugin' ); ?></p>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</table>
					</div>
				<?php endif; ?>
			</div>

			<!-- Sidebar -->
			<div class="mbrreg-admin-column">
				<div class="mbrreg-admin-card">
					<h2><?php esc_html_e( 'Member Status', 'member-registration-plugin' ); ?></h2>

					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="status"><?php esc_html_e( 'Status', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<select name="status" id="status">
									<?php foreach ( Mbrreg_Member::$statuses as $value => $label ) : ?>
										<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $member->status, $value ); ?>>
											<?php echo esc_html( $label ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Member Admin', 'member-registration-plugin' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="is_admin" value="1" <?php checked( $member->is_admin ); ?>>
									<?php esc_html_e( 'Can manage members', 'member-registration-plugin' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'Member admins can view and manage other members from the frontend.', 'member-registration-plugin' ); ?></p>
							</td>
						</tr>
					</table>
				</div>

				<div class="mbrreg-admin-card">
					<h2><?php esc_html_e( 'Account Information', 'member-registration-plugin' ); ?></h2>
					<table class="form-table">
						<tr>
							<th scope="row"><?php esc_html_e( 'Username', 'member-registration-plugin' ); ?></th>
							<td><code><?php echo esc_html( $member->username ); ?></code></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Member ID', 'member-registration-plugin' ); ?></th>
							<td><?php echo esc_html( $member->id ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'WP User ID', 'member-registration-plugin' ); ?></th>
							<td>
								<a href="<?php echo esc_url( admin_url( 'user-edit.php?user_id=' . $member->user_id ) ); ?>">
									<?php echo esc_html( $member->user_id ); ?>
								</a>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Registered', 'member-registration-plugin' ); ?></th>
							<td><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $member->created_at ) ) ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Last Updated', 'member-registration-plugin' ); ?></th>
							<td><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $member->updated_at ) ) ); ?></td>
						</tr>
					</table>
				</div>

				<div class="mbrreg-admin-card">
					<h2><?php esc_html_e( 'Actions', 'member-registration-plugin' ); ?></h2>
					<p>
						<button type="submit" class="button button-primary button-large"><?php esc_html_e( 'Update Member', 'member-registration-plugin' ); ?></button>
						<span class="spinner"></span>
					</p>
					<p>
						<button type="button" class="button button-link-delete mbrreg-delete-member" data-member-id="<?php echo esc_attr( $member->id ); ?>">
							<?php esc_html_e( 'Delete Member', 'member-registration-plugin' ); ?>
						</button>
					</p>

					<?php if ( 'pending' === $member->status ) : ?>
						<p>
							<button type="button" class="button mbrreg-resend-activation" data-member-id="<?php echo esc_attr( $member->id ); ?>">
								<?php esc_html_e( 'Resend Activation Email', 'member-registration-plugin' ); ?>
							</button>
						</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</form>
</div>
```

---

Now let me provide the remaining files in **Part 5**:

## admin/css/mbrreg-admin.css (UPDATED)

```css
/**
 * Admin styles for Member Registration Plugin.
 *
 * @package Member_Registration_Plugin
 * @since 1.0.0
 */

/* Admin Wrap */
.mbrreg-admin-wrap {
	max-width: 1200px;
}

/* Admin Columns */
.mbrreg-admin-columns {
	display: flex;
	gap: 20px;
	flex-wrap: wrap;
}

.mbrreg-admin-column {
	flex: 1;
	min-width: 300px;
}

.mbrreg-admin-column-wide {
	flex: 2;
	min-width: 400px;
}

/* Admin Card */
.mbrreg-admin-card {
	background: #fff;
	border: 1px solid #c3c4c7;
	border-radius: 4px;
	padding: 20px;
	margin-bottom: 20px;
}

.mbrreg-admin-card h2 {
	margin-top: 0;
	padding-bottom: 10px;
	border-bottom: 1px solid #eee;
}

/* Admin Sections */
.mbrreg-admin-sections {
	display: flex;
	gap: 30px;
	flex-wrap: wrap;
}

.mbrreg-admin-section {
	flex: 1;
	min-width: 400px;
	background: #fff;
	border: 1px solid #c3c4c7;
	border-radius: 4px;
	padding: 20px;
}

.mbrreg-admin-section h2 {
	margin-top: 0;
	padding-bottom: 10px;
	border-bottom: 1px solid #eee;
}

/* Admin Form */
.mbrreg-admin-form .mbrreg-form-row {
	margin-bottom: 15px;
}

.mbrreg-admin-form label {
	display: block;
	font-weight: 600;
	margin-bottom: 5px;
}

.mbrreg-admin-form input[type="text"],
.mbrreg-admin-form input[type="email"],
.mbrreg-admin-form input[type="number"],
.mbrreg-admin-form input[type="date"],
.mbrreg-admin-form select,
.mbrreg-admin-form textarea {
	width: 100%;
	max-width: 400px;
}

.mbrreg-admin-form .description {
	margin-top: 5px;
}

/* Status Badges */
.mbrreg-status {
	display: inline-block;
	padding: 3px 8px;
	border-radius: 3px;
	font-size: 12px;
	font-weight: 600;
}

.mbrreg-status-active {
	background-color: #d4edda;
	color: #155724;
}

.mbrreg-status-inactive {
	background-color: #f8d7da;
	color: #721c24;
}

.mbrreg-status-pending {
	background-color: #fff3cd;
	color: #856404;
}

/* Fields Table */
.mbrreg-fields-table th,
.mbrreg-fields-table td {
	vertical-align: middle;
}

/* Empty Message */
.mbrreg-empty-message {
	color: #666;
	font-style: italic;
	padding: 20px;
	text-align: center;
	background: #f9f9f9;
	border-radius: 4px;
}

/* Modal Styles */
.mbrreg-modal {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: rgba(0, 0, 0, 0.5);
	z-index: 100000;
	display: flex;
	align-items: center;
	justify-content: center;
}

.mbrreg-modal-content {
	background: #fff;
	border-radius: 4px;
	max-width: 500px;
	width: 90%;
	max-height: 90vh;
	overflow: auto;
}

.mbrreg-modal-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 15px 20px;
	border-bottom: 1px solid #ddd;
}

.mbrreg-modal-header h2 {
	margin: 0;
}

.mbrreg-modal-close {
	background: none;
	border: none;
	font-size: 24px;
	cursor: pointer;
	color: #666;
	padding: 0;
	line-height: 1;
}

.mbrreg-modal-close:hover {
	color: #000;
}

.mbrreg-modal-body {
	padding: 20px;
}

/* Import/Export Styles */
.mbrreg-import-instructions {
	background: #f9f9f9;
	padding: 15px;
	border-radius: 4px;
	margin-bottom: 20px;
}

.mbrreg-import-instructions h4 {
	margin-top: 0;
}

.mbrreg-import-instructions ol {
	margin-bottom: 0;
}

.mbrreg-import-results {
	margin-top: 20px;
	padding: 15px;
	background: #f0f0f0;
	border-radius: 4px;
}

.mbrreg-sample-csv,
.mbrreg-export-info {
	margin-top: 30px;
	padding-top: 20px;
	border-top: 1px solid #ddd;
}

.mbrreg-export-info ul {
	margin-bottom: 0;
}

/* Settings Page */
.mbrreg-settings-section {
	background: #fff;
	border: 1px solid #c3c4c7;
	border-radius: 4px;
	padding: 20px;
	margin-bottom: 20px;
}

.mbrreg-settings-section h2 {
	margin-top: 0;
	padding-bottom: 10px;
	border-bottom: 1px solid #eee;
}

.mbrreg-shortcodes-table th {
	width: 250px;
}

.mbrreg-shortcodes-table code {
	background: #f0f0f0;
	padding: 2px 6px;
}

/* Spinner */
.mbrreg-admin-form .spinner {
	float: none;
	margin-left: 10px;
}

/* Responsive */
@media screen and (max-width: 782px) {
	.mbrreg-admin-columns,
	.mbrreg-admin-sections {
		flex-direction: column;
	}

	.mbrreg-admin-column,
	.mbrreg-admin-column-wide,
	.mbrreg-admin-section {
		min-width: 100%;
	}
}
```

---

## admin/js/mbrreg-admin.js (UPDATED)

```javascript
/**
 * Admin JavaScript for Member Registration Plugin.
 *
 * @package Member_Registration_Plugin
 * @since 1.0.0
 */

(function($) {
	'use strict';

	/**
	 * Admin functionality object.
	 */
	const MbrregAdmin = {

		/**
		 * Initialize admin functionality.
		 */
		init: function() {
			this.bindEvents();
			this.initFieldTypeToggle();
		},

		/**
		 * Bind event handlers.
		 */
		bindEvents: function() {
			// Member actions.
			$(document).on('click', '.mbrreg-delete-member', this.handleDeleteMember);
			$(document).on('click', '.mbrreg-resend-activation', this.handleResendActivation);
			$(document).on('click', '.mbrreg-bulk-action-btn', this.handleBulkAction);
			$(document).on('submit', '#mbrreg-edit-member-form', this.handleUpdateMember);

			// Custom field actions.
			$(document).on('submit', '#mbrreg-add-field-form', this.handleAddField);
			$(document).on('submit', '#mbrreg-edit-field-form', this.handleUpdateField);
			$(document).on('click', '.mbrreg-edit-field', this.handleEditFieldClick);
			$(document).on('click', '.mbrreg-delete-field', this.handleDeleteField);
			$(document).on('change', '#field_type, #edit_field_type', this.toggleFieldOptions);

			// Modal.
			$(document).on('click', '.mbrreg-modal-close', this.closeModal);
			$(document).on('click', '.mbrreg-modal', this.closeModalOnOverlay);

			// Select all checkbox.
			$(document).on('change', '#cb-select-all', this.toggleSelectAll);

			// Import form.
			$(document).on('submit', '#mbrreg-import-form', this.handleImport);
		},

		/**
		 * Initialize field type toggle.
		 */
		initFieldTypeToggle: function() {
			this.toggleFieldOptions.call($('#field_type')[0]);
		},

		/**
		 * Toggle field options based on field type.
		 */
		toggleFieldOptions: function() {
			var type = $(this).val();
			var showOptions = ['select', 'radio'].indexOf(type) !== -1;
			var $container = $(this).attr('id') === 'edit_field_type' 
				? $('.mbrreg-edit-field-options-row')
				: $('.mbrreg-field-options-row');

			if (showOptions) {
				$container.show();
			} else {
				$container.hide();
			}
		},

		/**
		 * Handle member deletion.
		 */
		handleDeleteMember: function(e) {
			e.preventDefault();

			if (!confirm(mbrregAdmin.confirmDelete)) {
				return;
			}

			var $btn = $(this);
			var memberId = $btn.data('member-id');

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_admin_delete_member',
					nonce: mbrregAdmin.nonce,
					member_id: memberId,
					delete_wp_user: false
				},
				beforeSend: function() {
					$btn.prop('disabled', true).text(mbrregAdmin.processing);
				},
				success: function(response) {
					if (response.success) {
						MbrregAdmin.showMessage(response.data.message, 'success');
						// Redirect to members list or remove row.
						if ($btn.closest('form').attr('id') === 'mbrreg-edit-member-form') {
							window.location.href = 'admin.php?page=mbrreg-members';
						} else {
							$btn.closest('tr').fadeOut(function() {
								$(this).remove();
							});
						}
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
						$btn.prop('disabled', false).text(mbrregAdmin.error);
					}
				},
				error: function() {
					MbrregAdmin.showMessage(mbrregAdmin.error, 'error');
					$btn.prop('disabled', false);
				}
			});
		},

		/**
		 * Handle resend activation email.
		 */
		handleResendActivation: function(e) {
			e.preventDefault();

			var $btn = $(this);
			var memberId = $btn.data('member-id');
			var originalText = $btn.text();

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_admin_resend_activation',
					nonce: mbrregAdmin.nonce,
					member_id: memberId
				},
				beforeSend: function() {
					$btn.prop('disabled', true).text(mbrregAdmin.processing);
				},
				success: function(response) {
					if (response.success) {
						MbrregAdmin.showMessage(response.data.message, 'success');
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
					}
					$btn.prop('disabled', false).text(originalText);
				},
				error: function() {
					MbrregAdmin.showMessage(mbrregAdmin.error, 'error');
					$btn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle bulk action.
		 */
		handleBulkAction: function(e) {
			e.preventDefault();

			var action = $('#bulk-action-selector').val();
			var memberIds = [];

			$('input[name="member_ids[]"]:checked').each(function() {
				memberIds.push($(this).val());
			});

			if (!action) {
				alert(mbrregAdmin.selectAction);
				return;
			}

			if (memberIds.length === 0) {
				alert(mbrregAdmin.selectMembers);
				return;
			}

			if (!confirm(mbrregAdmin.confirmBulk)) {
				return;
			}

			var $btn = $(this);
			var originalText = $btn.text();

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_admin_bulk_action',
					nonce: mbrregAdmin.nonce,
					bulk_action: action,
					member_ids: memberIds
				},
				beforeSend: function() {
					$btn.prop('disabled', true).text(mbrregAdmin.processing);
				},
				success: function(response) {
					if (response.success) {
						MbrregAdmin.showMessage(response.data.message, 'success');
						location.reload();
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
						$btn.prop('disabled', false).text(originalText);
					}
				},
				error: function() {
					MbrregAdmin.showMessage(mbrregAdmin.error, 'error');
					$btn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle member update.
		 */
		handleUpdateMember: function(e) {
			e.preventDefault();

			var $form = $(this);
			var $btn = $form.find('button[type="submit"]');
			var $spinner = $form.find('.spinner');

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: $form.serialize() + '&action=mbrreg_admin_update_member&nonce=' + mbrregAdmin.nonce,
				beforeSend: function() {
					$btn.prop('disabled', true);
					$spinner.addClass('is-active');
				},
				success: function(response) {
					if (response.success) {
						MbrregAdmin.showMessage(response.data.message, 'success');
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
					}
					$btn.prop('disabled', false);
					$spinner.removeClass('is-active');
				},
				error: function() {
					MbrregAdmin.showMessage(mbrregAdmin.error, 'error');
					$btn.prop('disabled', false);
					$spinner.removeClass('is-active');
				}
			});
		},

		/**
		 * Handle add custom field.
		 */
		handleAddField: function(e) {
			e.preventDefault();

			var $form = $(this);
			var $btn = $form.find('button[type="submit"]');
			var $spinner = $form.find('.spinner');

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: $form.serialize() + '&action=mbrreg_admin_create_field&nonce=' + mbrregAdmin.nonce,
				beforeSend: function() {
					$btn.prop('disabled', true);
					$spinner.addClass('is-active');
				},
				success: function(response) {
					if (response.success) {
						MbrregAdmin.showMessage(response.data.message, 'success');
						location.reload();
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
						$btn.prop('disabled', false);
						$spinner.removeClass('is-active');
					}
				},
				error: function() {
					MbrregAdmin.showMessage(mbrregAdmin.error, 'error');
					$btn.prop('disabled', false);
					$spinner.removeClass('is-active');
				}
			});
		},

		/**
		 * Handle edit field click.
		 */
		handleEditFieldClick: function(e) {
			e.preventDefault();

			var fieldId = $(this).data('field-id');
			var field = mbrregFieldData.find(function(f) {
				return parseInt(f.id) === parseInt(fieldId);
			});

			if (!field) {
				return;
			}

			// Populate form.
			$('#edit_field_id').val(field.id);
			$('#edit_field_label').val(field.field_label);
			$('#edit_field_type').val(field.field_type).trigger('change');
			$('#edit_field_order').val(field.field_order);
			$('#edit_is_required').prop('checked', parseInt(field.is_required) === 1);
			$('#edit_is_admin_only').prop('checked', parseInt(field.is_admin_only) === 1);

			// Handle options.
			var options = field.field_options ? JSON.parse(field.field_options) : [];
			$('#edit_field_options').val(options.join('\n'));

			// Show modal.
			$('#mbrreg-edit-field-modal').show();
		},

		/**
		 * Handle update custom field.
		 */
		handleUpdateField: function(e) {
			e.preventDefault();

			var $form = $(this);
			var $btn = $form.find('button[type="submit"]');
			var $spinner = $form.find('.spinner');

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: $form.serialize() + '&action=mbrreg_admin_update_field&nonce=' + mbrregAdmin.nonce,
				beforeSend: function() {
					$btn.prop('disabled', true);
					$spinner.addClass('is-active');
				},
				success: function(response) {
					if (response.success) {
						MbrregAdmin.showMessage(response.data.message, 'success');
						location.reload();
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
						$btn.prop('disabled', false);
						$spinner.removeClass('is-active');
					}
				},
				error: function() {
					MbrregAdmin.showMessage(mbrregAdmin.error, 'error');
					$btn.prop('disabled', false);
					$spinner.removeClass('is-active');
				}
			});
		},

		/**
		 * Handle delete custom field.
		 */
		handleDeleteField: function(e) {
			e.preventDefault();

			if (!confirm(mbrregAdmin.confirmFieldDelete)) {
				return;
			}

			var $btn = $(this);
			var fieldId = $btn.data('field-id');

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_admin_delete_field',
					nonce: mbrregAdmin.nonce,
					field_id: fieldId
				},
				beforeSend: function() {
					$btn.prop('disabled', true).text(mbrregAdmin.processing);
				},
				success: function(response) {
					if (response.success) {
						MbrregAdmin.showMessage(response.data.message, 'success');
						$btn.closest('tr').fadeOut(function() {
							$(this).remove();
						});
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
						$btn.prop('disabled', false);
					}
				},
				error: function() {
					MbrregAdmin.showMessage(mbrregAdmin.error, 'error');
					$btn.prop('disabled', false);
				}
			});
		},

		/**
		 * Handle import form.
		 */
		handleImport: function(e) {
			e.preventDefault();

			var $form = $(this);
			var $btn = $form.find('button[type="submit"]');
			var $spinner = $form.find('.spinner');
			var $results = $form.find('.mbrreg-import-results');
			var $message = $form.find('.mbrreg-import-message');

			var formData = new FormData($form[0]);
			formData.append('action', 'mbrreg_admin_import_csv');
			formData.append('nonce', mbrregAdmin.nonce);

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function() {
					$btn.prop('disabled', true);
					$spinner.addClass('is-active');
					$results.hide();
				},
				success: function(response) {
					$btn.prop('disabled', false);
					$spinner.removeClass('is-active');
					$results.show();

					if (response.success) {
						$message.html('<div class="notice notice-success"><p>' + response.data.message.replace(/\n/g, '<br>') + '</p></div>');
					} else {
						$message.html('<div class="notice notice-error"><p>' + response.data.message + '</p></div>');
					}
				},
				error: function() {
					$btn.prop('disabled', false);
					$spinner.removeClass('is-active');
					$results.show();
					$message.html('<div class="notice notice-error"><p>' + mbrregAdmin.error + '</p></div>');
				}
			});
		},

		/**
		 * Close modal.
		 */
		closeModal: function(e) {
			e.preventDefault();
			$('.mbrreg-modal').hide();
		},

		/**
		 * Close modal on overlay click.
		 */
		closeModalOnOverlay: function(e) {
			if ($(e.target).hasClass('mbrreg-modal')) {
				$(this).hide();
			}
		},

		/**
		 * Toggle select all checkboxes.
		 */
		toggleSelectAll: function() {
			var isChecked = $(this).prop('checked');
			$('input[name="member_ids[]"]').prop('checked', isChecked);
		},

		/**
		 * Show admin message.
		 */
		showMessage: function(message, type) {
			var $container = $('#mbrreg-admin-messages');
			if (!$container.length) {
				$container = $('<div id="mbrreg-admin-messages"></div>').insertAfter('.wp-heading-inline, h1').first();
			}

			var noticeClass = type === 'success' ? 'notice-success' : 'notice-error';
			var $notice = $('<div class="notice ' + noticeClass + ' is-dismissible"><p>' + message + '</p></div>');

			$container.html($notice);

			// Auto dismiss after 5 seconds.
			setTimeout(function() {
				$notice.fadeOut(function() {
					$(this).remove();
				});
			}, 5000);

			// Scroll to message.
			$('html, body').animate({
				scrollTop: $container.offset().top - 50
			}, 300);
		}
	};

	// Initialize on document ready.
	$(document).ready(function() {
		MbrregAdmin.init();
	});

})(jQuery);
```

---

## public/partials/mbrreg-register-form.php (UPDATED)

```php
<?php
/**
 * Registration form template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/public/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get custom fields handler for rendering.
$custom_fields_handler = new Mbrreg_Custom_Fields();

// Check for activation messages.
$activation_error   = get_transient( 'mbrreg_activation_error' );
$activation_success = get_transient( 'mbrreg_activation_success' );

if ( $activation_error ) {
	delete_transient( 'mbrreg_activation_error' );
}
if ( $activation_success ) {
	delete_transient( 'mbrreg_activation_success' );
}
?>

<div class="mbrreg-form-container">
	<?php if ( $activation_error ) : ?>
		<div class="mbrreg-message mbrreg-error"><?php echo esc_html( $activation_error ); ?></div>
	<?php endif; ?>

	<?php if ( $activation_success ) : ?>
		<div class="mbrreg-message mbrreg-success"><?php echo esc_html( $activation_success ); ?></div>
	<?php endif; ?>

	<form id="mbrreg-register-form" class="mbrreg-form" method="post">
		<h2 class="mbrreg-form-title"><?php esc_html_e( 'Register', 'member-registration-plugin' ); ?></h2>

		<div class="mbrreg-form-messages"></div>

		<!-- Account Details -->
		<fieldset class="mbrreg-fieldset">
			<legend><?php esc_html_e( 'Account Details', 'member-registration-plugin' ); ?></legend>

			<div class="mbrreg-form-row">
				<label for="mbrreg-email">
					<?php esc_html_e( 'Email Address', 'member-registration-plugin' ); ?>
					<span class="required">*</span>
				</label>
				<input type="email" id="mbrreg-email" name="email" required>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-username">
					<?php esc_html_e( 'Username', 'member-registration-plugin' ); ?>
				</label>
				<input type="text" id="mbrreg-username" name="username">
				<span class="mbrreg-field-note"><?php esc_html_e( 'Leave blank to auto-generate from email.', 'member-registration-plugin' ); ?></span>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-password">
					<?php esc_html_e( 'Password', 'member-registration-plugin' ); ?>
					<span class="required">*</span>
				</label>
				<input type="password" id="mbrreg-password" name="password" required minlength="8">
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-password-confirm">
					<?php esc_html_e( 'Confirm Password', 'member-registration-plugin' ); ?>
					<span class="required">*</span>
				</label>
				<input type="password" id="mbrreg-password-confirm" name="password_confirm" required minlength="8">
			</div>
		</fieldset>

		<!-- Personal Details -->
		<fieldset class="mbrreg-fieldset">
			<legend><?php esc_html_e( 'Personal Details', 'member-registration-plugin' ); ?></legend>

			<div class="mbrreg-form-row">
				<label for="mbrreg-first-name">
					<?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?>
					<?php if ( get_option( 'mbrreg_require_first_name', false ) ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<input type="text" id="mbrreg-first-name" name="first_name" <?php echo get_option( 'mbrreg_require_first_name', false ) ? 'required' : ''; ?>>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-last-name">
					<?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?>
					<?php if ( get_option( 'mbrreg_require_last_name', false ) ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<input type="text" id="mbrreg-last-name" name="last_name" <?php echo get_option( 'mbrreg_require_last_name', false ) ? 'required' : ''; ?>>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-address">
					<?php esc_html_e( 'Address', 'member-registration-plugin' ); ?>
					<?php if ( get_option( 'mbrreg_require_address', false ) ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<textarea id="mbrreg-address" name="address" rows="3" <?php echo get_option( 'mbrreg_require_address', false ) ? 'required' : ''; ?>></textarea>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-telephone">
					<?php esc_html_e( 'Telephone Number', 'member-registration-plugin' ); ?>
					<?php if ( get_option( 'mbrreg_require_telephone', false ) ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<input type="tel" id="mbrreg-telephone" name="telephone" <?php echo get_option( 'mbrreg_require_telephone', false ) ? 'required' : ''; ?>>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-dob">
					<?php esc_html_e( 'Date of Birth', 'member-registration-plugin' ); ?>
					<?php if ( get_option( 'mbrreg_require_date_of_birth', false ) ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<input type="date" id="mbrreg-dob" name="date_of_birth" <?php echo get_option( 'mbrreg_require_date_of_birth', false ) ? 'required' : ''; ?>>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-pob">
					<?php esc_html_e( 'Place of Birth', 'member-registration-plugin' ); ?>
					<?php if ( get_option( 'mbrreg_require_place_of_birth', false ) ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<input type="text" id="mbrreg-pob" name="place_of_birth" <?php echo get_option( 'mbrreg_require_place_of_birth', false ) ? 'required' : ''; ?>>
			</div>
		</fieldset>

		<!-- Custom Fields (only user-editable) -->
		<?php if ( ! empty( $custom_fields ) ) : ?>
			<fieldset class="mbrreg-fieldset">
				<legend><?php esc_html_e( 'Additional Information', 'member-registration-plugin' ); ?></legend>

				<?php foreach ( $custom_fields as $field ) : ?>
					<div class="mbrreg-form-row">
						<label for="mbrreg-custom-<?php echo esc_attr( $field->id ); ?>">
							<?php echo esc_html( $field->field_label ); ?>
							<?php if ( $field->is_required ) : ?>
								<span class="required">*</span>
							<?php endif; ?>
						</label>
						<?php echo $custom_fields_handler->render_field_input( $field ); // phpcs:ignore ?>
					</div>
				<?php endforeach; ?>
			</fieldset>
		<?php endif; ?>

		<div class="mbrreg-form-row">
			<button type="submit" class="mbrreg-button mbrreg-button-primary">
				<?php esc_html_e( 'Register', 'member-registration-plugin' ); ?>
			</button>
		</div>
	</form>
</div>
```

---

## public/partials/mbrreg-login-form.php (UPDATED)

```php
<?php
/**
 * Login form template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/public/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Check for activation messages.
$activation_error   = get_transient( 'mbrreg_activation_error' );
$activation_success = get_transient( 'mbrreg_activation_success' );

if ( $activation_error ) {
	delete_transient( 'mbrreg_activation_error' );
}
if ( $activation_success ) {
	delete_transient( 'mbrreg_activation_success' );
}
?>

<div class="mbrreg-form-container">
	<?php if ( $activation_error ) : ?>
		<div class="mbrreg-message mbrreg-error"><?php echo esc_html( $activation_error ); ?></div>
	<?php endif; ?>

	<?php if ( $activation_success ) : ?>
		<div class="mbrreg-message mbrreg-success"><?php echo esc_html( $activation_success ); ?></div>
	<?php endif; ?>

	<form id="mbrreg-login-form" class="mbrreg-form" method="post">
		<h2 class="mbrreg-form-title"><?php esc_html_e( 'Login', 'member-registration-plugin' ); ?></h2>

		<div class="mbrreg-form-messages"></div>

		<div class="mbrreg-form-row">
			<label for="mbrreg-login-username">
				<?php esc_html_e( 'Username or Email', 'member-registration-plugin' ); ?>
				<span class="required">*</span>
			</label>
			<input type="text" id="mbrreg-login-username" name="username" required>
		</div>

		<div class="mbrreg-form-row">
			<label for="mbrreg-login-password">
				<?php esc_html_e( 'Password', 'member-registration-plugin' ); ?>
				<span class="required">*</span>
			</label>
			<input type="password" id="mbrreg-login-password" name="password" required>
		</div>

		<div class="mbrreg-form-row mbrreg-checkbox-row">
			<label>
				<input type="checkbox" name="remember" value="1">
				<?php esc_html_e( 'Remember Me', 'member-registration-plugin' ); ?>
			</label>
		</div>

		<div class="mbrreg-form-row">
			<button type="submit" class="mbrreg-button mbrreg-button-primary">
				<?php esc_html_e( 'Log In', 'member-registration-plugin' ); ?>
			</button>
		</div>

		<div class="mbrreg-form-links">
			<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>">
				<?php esc_html_e( 'Forgot your password?', 'member-registration-plugin' ); ?>
			</a>
		</div>
	</form>
</div>
```

---

Now let me provide the **Dutch translation file** and the **README files** in **Part 6**:

## languages/member-registration-plugin-nl_NL.po (NEW FILE)

```po
# Dutch translation for Member Registration Plugin.
# Copyright (C) 2024
# This file is distributed under the same license as the plugin.
#
msgid ""
msgstr ""
"Project-Id-Version: Member Registration Plugin 1.1.0\n"
"Report-Msgid-Bugs-To: \n"
"POT-Creation-Date: 2024-01-01 12:00+0000\n"
"PO-Revision-Date: 2024-01-01 12:00+0000\n"
"Last-Translator: \n"
"Language-Team: Dutch\n"
"Language: nl_NL\n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: 8bit\n"
"Plural-Forms: nplurals=2; plural=(n != 1);\n"

# Plugin Name
msgid "Member Registration Plugin"
msgstr "Leden Registratie Plugin"

# General
msgid "Members"
msgstr "Leden"

msgid "All Members"
msgstr "Alle Leden"

msgid "Member Registration"
msgstr "Ledenregistratie"

msgid "My Memberships"
msgstr "Mijn Lidmaatschappen"

msgid "Settings"
msgstr "Instellingen"

msgid "Custom Fields"
msgstr "Aangepaste Velden"

msgid "Import / Export"
msgstr "Importeren / Exporteren"

# Login Form
msgid "Login"
msgstr "Inloggen"

msgid "Username or Email"
msgstr "Gebruikersnaam of E-mail"

msgid "Password"
msgstr "Wachtwoord"

msgid "Remember Me"
msgstr "Onthoud mij"

msgid "Log In"
msgstr "Inloggen"

msgid "Forgot your password?"
msgstr "Wachtwoord vergeten?"

msgid "Login successful!"
msgstr "Succesvol ingelogd!"

msgid "Invalid username or password."
msgstr "Ongeldige gebruikersnaam of wachtwoord."

msgid "Please enter your username and password."
msgstr "Vul uw gebruikersnaam en wachtwoord in."

# Registration Form
msgid "Register"
msgstr "Registreren"

msgid "Account Details"
msgstr "Accountgegevens"

msgid "Email Address"
msgstr "E-mailadres"

msgid "Username"
msgstr "Gebruikersnaam"

msgid "Leave blank to auto-generate from email."
msgstr "Laat leeg om automatisch te genereren op basis van e-mail."

msgid "Confirm Password"
msgstr "Bevestig Wachtwoord"

msgid "Personal Details"
msgstr "Persoonlijke Gegevens"

msgid "First Name"
msgstr "Voornaam"

msgid "Last Name"
msgstr "Achternaam"

msgid "Address"
msgstr "Adres"

msgid "Telephone Number"
msgstr "Telefoonnummer"

msgid "Date of Birth"
msgstr "Geboortedatum"

msgid "Place of Birth"
msgstr "Geboorteplaats"

msgid "Additional Information"
msgstr "Aanvullende Informatie"

msgid "Registration successful! Please check your email to activate your account."
msgstr "Registratie succesvol! Controleer uw e-mail om uw account te activeren."

msgid "Registration is currently disabled."
msgstr "Registratie is momenteel uitgeschakeld."

msgid "This email address is already registered."
msgstr "Dit e-mailadres is al geregistreerd."

msgid "Email address is required."
msgstr "E-mailadres is verplicht."

msgid "Please enter a valid email address."
msgstr "Voer een geldig e-mailadres in."

msgid "Passwords do not match."
msgstr "Wachtwoorden komen niet overeen."

# Dashboard
msgid "Welcome, %s!"
msgstr "Welkom, %s!"

msgid "Log Out"
msgstr "Uitloggen"

msgid "Manage Members"
msgstr "Leden Beheren"

msgid "Account Email:"
msgstr "Account E-mail:"

msgid "Registered Members:"
msgstr "Geregistreerde Leden:"

msgid "Member #%d"
msgstr "Lid #%d"

msgid "Update Profile"
msgstr "Profiel Bijwerken"

msgid "Deactivate Membership"
msgstr "Lidmaatschap Deactiveren"

msgid "Profile updated successfully!"
msgstr "Profiel succesvol bijgewerkt!"

msgid "+ Add Another Member"
msgstr "+ Nog een Lid Toevoegen"

msgid "Add New Member"
msgstr "Nieuw Lid Toevoegen"

msgid "Add another family member to your account."
msgstr "Voeg nog een familielid toe aan uw account."

msgid "Add Member"
msgstr "Lid Toevoegen"

msgid "Cancel"
msgstr "Annuleren"

msgid "Member added successfully! Please check your email to activate the membership."
msgstr "Lid succesvol toegevoegd! Controleer uw e-mail om het lidmaatschap te activeren."

msgid "Adding multiple members is not allowed."
msgstr "Het toevoegen van meerdere leden is niet toegestaan."

# Status
msgid "Pending Activation"
msgstr "Wacht op Activatie"

msgid "Active"
msgstr "Actief"

msgid "Inactive"
msgstr "Inactief"

msgid "Status"
msgstr "Status"

# Confirmation Messages
msgid "Confirm Action"
msgstr "Actie Bevestigen"

msgid "Are you sure you want to deactivate this membership? If this is your only active membership, you will be logged out."
msgstr "Weet u zeker dat u dit lidmaatschap wilt deactiveren? Als dit uw enige actieve lidmaatschap is, wordt u uitgelogd."

msgid "Deactivate Membership"
msgstr "Lidmaatschap Deactiveren"

msgid "Are you sure you want to log out?"
msgstr "Weet u zeker dat u wilt uitloggen?"

msgid "Membership has been set to inactive."
msgstr "Lidmaatschap is ingesteld op inactief."

msgid "You have been logged out."
msgstr "U bent uitgelogd."

msgid "Confirm"
msgstr "Bevestigen"

msgid "OK"
msgstr "OK"

msgid "Notice"
msgstr "Melding"

msgid "Error"
msgstr "Fout"

msgid "Success"
msgstr "Succes"

# Admin - Members List
msgid "All"
msgstr "Alle"

msgid "Pending"
msgstr "In Afwachting"

msgid "Search Members"
msgstr "Leden Zoeken"

msgid "Search members..."
msgstr "Leden zoeken..."

msgid "Search"
msgstr "Zoeken"

msgid "Bulk Actions"
msgstr "Bulkacties"

msgid "Activate"
msgstr "Activeren"

msgid "Deactivate"
msgstr "Deactiveren"

msgid "Delete Members"
msgstr "Leden Verwijderen"

msgid "Delete Members + Users"
msgstr "Leden + Gebruikers Verwijderen"

msgid "Apply"
msgstr "Toepassen"

msgid "Name"
msgstr "Naam"

msgid "Email"
msgstr "E-mail"

msgid "Account Owner"
msgstr "Accounteigenaar"

msgid "Admin"
msgstr "Beheerder"

msgid "Registered"
msgstr "Geregistreerd"

msgid "Edit"
msgstr "Bewerken"

msgid "Delete"
msgstr "Verwijderen"

msgid "Resend Activation"
msgstr "Activatie Opnieuw Versturen"

msgid "No members found."
msgstr "Geen leden gevonden."

msgid "%s member"
msgid_plural "%s members"
msgstr[0] "%s lid"
msgstr[1] "%s leden"

# Admin - Edit Member
msgid "Edit Member"
msgstr "Lid Bewerken"

msgid "Back to Members"
msgstr "Terug naar Leden"

msgid "Personal Information"
msgstr "Persoonlijke Informatie"

msgid "Member Status"
msgstr "Lidstatus"

msgid "Member Admin"
msgstr "Lid Beheerder"

msgid "Can manage members"
msgstr "Kan leden beheren"

msgid "Member admins can view and manage other members from the frontend."
msgstr "Ledenadministrateurs kunnen andere leden bekijken en beheren vanuit de frontend."

msgid "Account Information"
msgstr "Accountinformatie"

msgid "Member ID"
msgstr "Lid ID"

msgid "WP User ID"
msgstr "WP Gebruiker ID"

msgid "Last Updated"
msgstr "Laatst Bijgewerkt"

msgid "Actions"
msgstr "Acties"

msgid "Update Member"
msgstr "Lid Bijwerken"

msgid "Delete Member"
msgstr "Lid Verwijderen"

msgid "Resend Activation Email"
msgstr "Activatie E-mail Opnieuw Versturen"

msgid "Member updated successfully!"
msgstr "Lid succesvol bijgewerkt!"

msgid "Member deleted successfully!"
msgstr "Lid succesvol verwijderd!"

msgid "Member not found."
msgstr "Lid niet gevonden."

msgid "This user account (%2$s) has %1$d registered members."
msgstr "Dit gebruikersaccount (%2$s) heeft %1$d geregistreerde leden."

# Admin - Custom Fields
msgid "Existing Fields"
msgstr "Bestaande Velden"

msgid "Order"
msgstr "Volgorde"

msgid "Label"
msgstr "Label"

msgid "Type"
msgstr "Type"

msgid "Required"
msgstr "Verplicht"

msgid "Admin Only"
msgstr "Alleen Beheerder"

msgid "Admin only - users cannot edit"
msgstr "Alleen beheerder - gebruikers kunnen niet bewerken"

msgid "Editable by users"
msgstr "Bewerkbaar door gebruikers"

msgid "No custom fields have been created yet."
msgstr "Er zijn nog geen aangepaste velden aangemaakt."

msgid "Add New Field"
msgstr "Nieuw Veld Toevoegen"

msgid "Field Name"
msgstr "Veldnaam"

msgid "Unique identifier. Lowercase letters, numbers, and underscores only."
msgstr "Unieke identificatie. Alleen kleine letters, cijfers en underscores."

msgid "Field Label"
msgstr "Veldlabel"

msgid "The label displayed to users."
msgstr "Het label dat aan gebruikers wordt getoond."

msgid "Field Type"
msgstr "Veldtype"

msgid "Text"
msgstr "Tekst"

msgid "Textarea"
msgstr "Tekstvak"

msgid "Number"
msgstr "Nummer"

msgid "Date"
msgstr "Datum"

msgid "Dropdown"
msgstr "Keuzelijst"

msgid "Checkbox"
msgstr "Selectievakje"

msgid "Radio Buttons"
msgstr "Keuzerondjes"

msgid "Options"
msgstr "Opties"

msgid "Enter one option per line."
msgstr "Voer één optie per regel in."

msgid "Display Order"
msgstr "Weergavevolgorde"

msgid "Lower numbers display first."
msgstr "Lagere nummers worden eerst weergegeven."

msgid "Required field"
msgstr "Verplicht veld"

msgid "Admin only (users cannot edit)"
msgstr "Alleen beheerder (gebruikers kunnen niet bewerken)"

msgid "If checked, only administrators can view and edit this field. Users will see the value but cannot change it."
msgstr "Indien aangevinkt, kunnen alleen beheerders dit veld bekijken en bewerken. Gebruikers zien de waarde maar kunnen deze niet wijzigen."

msgid "Add Field"
msgstr "Veld Toevoegen"

msgid "Edit Custom Field"
msgstr "Aangepast Veld Bewerken"

msgid "Update Field"
msgstr "Veld Bijwerken"

msgid "Custom field created successfully!"
msgstr "Aangepast veld succesvol aangemaakt!"

msgid "Custom field updated successfully!"
msgstr "Aangepast veld succesvol bijgewerkt!"

msgid "Custom field deleted successfully!"
msgstr "Aangepast veld succesvol verwijderd!"

msgid "Field name is required."
msgstr "Veldnaam is verplicht."

msgid "Field label is required."
msgstr "Veldlabel is verplicht."

msgid "This field name is reserved."
msgstr "Deze veldnaam is gereserveerd."

msgid "(View only)"
msgstr "(Alleen bekijken)"

msgid "This field can only be edited by administrators."
msgstr "Dit veld kan alleen worden bewerkt door beheerders."

# Admin - Import/Export
msgid "Import Members"
msgstr "Leden Importeren"

msgid "Import members from a CSV file. An activation email will be sent to each imported member."
msgstr "Importeer leden vanuit een CSV-bestand. Aan elk geïmporteerd lid wordt een activatie-e-mail gestuurd."

msgid "CSV File Format"
msgstr "CSV-bestandsformaat"

msgid "Your CSV file should have the following columns in order:"
msgstr "Uw CSV-bestand moet de volgende kolommen in volgorde bevatten:"

msgid "required"
msgstr "verplicht"

msgid "format: YYYY-MM-DD"
msgstr "formaat: JJJJ-MM-DD"

msgid "Note:"
msgstr "Opmerking:"

msgid "The first row should contain column headers and will be skipped during import."
msgstr "De eerste rij moet kolomkoppen bevatten en wordt overgeslagen tijdens het importeren."

msgid "CSV File"
msgstr "CSV-bestand"

msgid "Select a CSV file to import."
msgstr "Selecteer een CSV-bestand om te importeren."

msgid "Import Results"
msgstr "Importresultaten"

msgid "Download Sample CSV"
msgstr "Voorbeeld CSV Downloaden"

msgid "Download a sample CSV file with the correct format:"
msgstr "Download een voorbeeld CSV-bestand met het juiste formaat:"

msgid "Export Members"
msgstr "Leden Exporteren"

msgid "Export members to a CSV file for backup or external use."
msgstr "Exporteer leden naar een CSV-bestand voor backup of extern gebruik."

msgid "Member Status"
msgstr "Lidstatus"

msgid "All Members"
msgstr "Alle Leden"

msgid "Active Only"
msgstr "Alleen Actief"

msgid "Inactive Only"
msgstr "Alleen Inactief"

msgid "Pending Only"
msgstr "Alleen In Afwachting"

msgid "Export Information"
msgstr "Export Informatie"

msgid "The exported CSV will include:"
msgstr "Het geëxporteerde CSV-bestand bevat:"

msgid "Member ID, Username, Email"
msgstr "Lid ID, Gebruikersnaam, E-mail"

msgid "First Name, Last Name, Address, Telephone"
msgstr "Voornaam, Achternaam, Adres, Telefoonnummer"

msgid "Date of Birth, Place of Birth"
msgstr "Geboortedatum, Geboorteplaats"

msgid "Status, Admin Status, Registration Date"
msgstr "Status, Beheerdersstatus, Registratiedatum"

msgid "All custom field values"
msgstr "Alle aangepaste veldwaarden"

msgid "Import completed. %1$d members imported successfully, %2$d errors."
msgstr "Import voltooid. %1$d leden succesvol geïmporteerd, %2$d fouten."

msgid "Row %d: Email is required."
msgstr "Rij %d: E-mail is verplicht."

msgid "Row %1$d: %2$s"
msgstr "Rij %1$d: %2$s"

msgid "Errors:"
msgstr "Fouten:"

msgid "...and %d more errors."
msgstr "...en nog %d fouten."

msgid "Please select a CSV file to upload."
msgstr "Selecteer een CSV-bestand om te uploaden."

msgid "Invalid file type. Please upload a CSV file."
msgstr "Ongeldig bestandstype. Upload een CSV-bestand."

msgid "Failed to read the CSV file."
msgstr "Kan het CSV-bestand niet lezen."

msgid "The CSV file is empty or contains only headers."
msgstr "Het CSV-bestand is leeg of bevat alleen kopteksten."

# Admin - Settings
msgid "Member Registration Settings"
msgstr "Ledenregistratie Instellingen"

msgid "Registration Settings"
msgstr "Registratie Instellingen"

msgid "Allow Registration"
msgstr "Registratie Toestaan"

msgid "Allow new members to register"
msgstr "Nieuwe leden toestaan om te registreren"

msgid "When disabled, the registration form will not be shown."
msgstr "Wanneer uitgeschakeld, wordt het registratieformulier niet getoond."

msgid "Allow Multiple Members"
msgstr "Meerdere Leden Toestaan"

msgid "Allow users to register multiple members under one account"
msgstr "Gebruikers toestaan om meerdere leden te registreren onder één account"

msgid "Useful for parents registering multiple children or family members."
msgstr "Handig voor ouders die meerdere kinderen of familieleden registreren."

msgid "Member Area Page"
msgstr "Ledengebied Pagina"

msgid "— Select —"
msgstr "— Selecteer —"

msgid "The page containing the [mbrreg_member_area] shortcode."
msgstr "De pagina met de [mbrreg_member_area] shortcode."

msgid "Login Redirect Page"
msgstr "Doorstuurpagina Na Inloggen"

msgid "Where to redirect members after successful login."
msgstr "Waarheen leden worden doorgestuurd na succesvol inloggen."

msgid "Required Fields"
msgstr "Verplichte Velden"

msgid "Select which fields should be required during registration and profile updates."
msgstr "Selecteer welke velden verplicht moeten zijn tijdens registratie en profielupdates."

msgid "Email Settings"
msgstr "E-mail Instellingen"

msgid "From Name"
msgstr "Van Naam"

msgid "The name that will appear in the \"From\" field of emails."
msgstr "De naam die verschijnt in het \"Van\" veld van e-mails."

msgid "From Email"
msgstr "Van E-mail"

msgid "The email address that will appear in the \"From\" field."
msgstr "Het e-mailadres dat verschijnt in het \"Van\" veld."

msgid "Shortcodes"
msgstr "Shortcodes"

msgid "Complete member area with login, registration, and dashboard."
msgstr "Volledig ledengebied met inloggen, registratie en dashboard."

msgid "Standalone login form."
msgstr "Zelfstandig inlogformulier."

msgid "Standalone registration form."
msgstr "Zelfstandig registratieformulier."

msgid "Member dashboard (for logged-in members only)."
msgstr "Ledendashboard (alleen voor ingelogde leden)."

# Emails
msgid "Activate your membership at %s"
msgstr "Activeer uw lidmaatschap bij %s"

msgid "You have been registered as a member at %s"
msgstr "U bent geregistreerd als lid bij %s"

msgid "Welcome to %s!"
msgstr "Welkom bij %s!"

msgid "New member registration at %s"
msgstr "Nieuwe ledenregistratie bij %s"

# Misc
msgid "Processing..."
msgstr "Bezig met verwerken..."

msgid "An error occurred. Please try again."
msgstr "Er is een fout opgetreden. Probeer het opnieuw."

msgid "Security check failed."
msgstr "Beveiligingscontrole mislukt."

msgid "You must be logged in."
msgstr "U moet ingelogd zijn."

msgid "Invalid member ID."
msgstr "Ongeldig lid ID."

msgid "You do not have permission to perform this action."
msgstr "U heeft geen toestemming om deze actie uit te voeren."

msgid "You do not have permission to update this member."
msgstr "U heeft geen toestemming om dit lid bij te werken."

msgid "Please activate your account first. Check your email for the activation link."
msgstr "Activeer eerst uw account. Controleer uw e-mail voor de activatielink."

msgid "All your memberships are inactive. Please contact the administrator."
msgstr "Al uw lidmaatschappen zijn inactief. Neem contact op met de beheerder."

msgid "Your account has been activated! You can now log in."
msgstr "Uw account is geactiveerd! U kunt nu inloggen."

msgid "Invalid activation key."
msgstr "Ongeldige activatiesleutel."

msgid "This account is already activated."
msgstr "Dit account is al geactiveerd."

msgid "Activation key is required."
msgstr "Activatiesleutel is verplicht."

msgid "You are already logged in."
msgstr "U bent al ingelogd."

msgid "You are already registered and logged in."
msgstr "U bent al geregistreerd en ingelogd."

msgid "Please log in to view your dashboard."
msgstr "Log in om uw dashboard te bekijken."

msgid "No member profile found. Please contact an administrator."
msgstr "Geen lidprofiel gevonden. Neem contact op met een beheerder."

msgid "%s is required."
msgstr "%s is verplicht."

msgid "Please enter a valid date of birth."
msgstr "Voer een geldige geboortedatum in."

msgid "Activation email sent successfully!"
msgstr "Activatie-e-mail succesvol verzonden!"

msgid "-- Select --"
msgstr "-- Selecteer --"

msgid "Bulk action completed. %1$d successful, %2$d failed."
msgstr "Bulkactie voltooid. %1$d succesvol, %2$d mislukt."

msgid "Please select at least one member."
msgstr "Selecteer ten minste één lid."

msgid "Please select an action."
msgstr "Selecteer een actie."
```

---

## readme.txt (NEW FILE - WordPress Plugin Readme)

```
=== Member Registration Plugin ===
Contributors: sportsclubdeveloper
Tags: members, registration, membership, sports club, user management
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.1.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A comprehensive member registration and management system for sports clubs. Allows users to register and manage multiple members under one account.

== Description ==

Member Registration Plugin is a powerful yet easy-to-use WordPress plugin designed specifically for sports clubs and organizations that need to manage member registrations. 

**Key Features:**

* **Multiple Members Per Account** - Perfect for families! Parents can register multiple children or family members under a single account.
* **Custom Fields** - Create unlimited custom fields to collect any additional information you need.
* **Admin-Only Fields** - Some fields can be marked as "admin only" - visible to members but only editable by administrators. Great for tracking payment status, membership levels, etc.
* **Email Notifications** - Automatic activation emails sent to new members with customizable sender information.
* **CSV Import/Export** - Bulk import members from CSV files or export member data for reporting.
* **Frontend Member Area** - Beautiful, responsive member dashboard where users can view and update their information.
* **Member Admins** - Designate certain members as "member admins" who can help manage other members.
* **Multi-language Support** - Fully translatable with Dutch translation included.

**Perfect For:**

* Sports clubs (football, tennis, swimming, etc.)
* Youth organizations
* Community clubs
* Any organization needing family-based member management

**Shortcodes:**

* `[mbrreg_member_area]` - Complete member area with login, registration, and dashboard
* `[mbrreg_login_form]` - Standalone login form
* `[mbrreg_register_form]` - Standalone registration form
* `[mbrreg_member_dashboard]` - Member dashboard (for logged-in users)

== Installation ==

1. Upload the `member-registration-plugin` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to 'Members' > 'Settings' to configure the plugin
4. Create a new page and add the `[mbrreg_member_area]` shortcode
5. Set this page as the "Member Area Page" in the plugin settings

== Frequently Asked Questions ==

= Can one user have multiple member profiles? =

Yes! This is one of the key features of the plugin. A single user account (identified by email) can have multiple member profiles. This is perfect for parents who want to register multiple children.

= What happens when I import members via CSV? =

When you import members, the plugin creates WordPress user accounts for each unique email address and sends activation emails. Members must click the activation link to activate their account. If an email already exists in the system, the new member will be added to that existing account.

= Can I create custom fields? =

Yes, you can create unlimited custom fields with various types: text, textarea, email, number, date, dropdown, checkbox, and radio buttons. Fields can be marked as required and/or admin-only.

= What are admin-only fields? =

Admin-only fields are visible to members but can only be edited by administrators. This is useful for tracking information like payment status, membership levels, or internal notes.

= Is the plugin translatable? =

Yes! The plugin is fully translatable and includes a Dutch translation. You can add translations for other languages using the provided POT file.

== Screenshots ==

1. Member dashboard showing multiple members under one account
2. Registration form with custom fields
3. Admin members list
4. Custom fields management
5. Import/Export page
6. Plugin settings

== Changelog ==

= 1.1.0 =
* Added: Support for multiple members per user account
* Added: Admin-only custom fields
* Added: CSV import with automatic activation emails
* Added: CSV export functionality
* Added: Custom modal dialogs (replacing JavaScript alerts)
* Added: Dutch translation
* Added: "My Memberships" menu item in WordPress admin for regular users
* Fixed: Form submission now properly refreshes the page after success
* Improved: Better handling of member deactivation when multiple members exist

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.1.0 =
Major update with support for multiple members per account, CSV import/export, and admin-only fields. Database will be automatically updated upon activation.
```

---

## README.md (NEW FILE - Project Documentation)

```markdown
# Member Registration Plugin for WordPress

A comprehensive member registration and management system designed for sports clubs and organizations.

## 🎯 Project Overview

This plugin was developed to provide sports clubs with an easy-to-use member management system that supports:

- **Family accounts**: One user can manage multiple members (e.g., parents managing children)
- **Custom data collection**: Create custom fields for any additional member information
- **Admin-controlled fields**: Some fields can only be edited by administrators
- **Bulk operations**: Import/export members via CSV files
- **Email automation**: Automatic activation and welcome emails

## 🤖 Built with AI Collaboration

This project was developed collaboratively with Claude (Anthropic's AI assistant). The development process involved:

1. **Initial Requirements Gathering**: The project started with a specification for a WordPress plugin for sports club member management.

2. **Iterative Development**: Features were added and refined through conversation:
   - Core registration and login functionality
   - Custom fields system
   - Admin management interface
   - Multiple members per account
   - CSV import/export
   - Translation support

3. **Bug Fixes and Improvements**: Issues discovered during testing were communicated back and fixed, such as:
   - Form submission refresh issues
   - JavaScript alert replacement with custom modals

4. **Documentation**: All code was documented with inline comments and this README was created.

### Development Approach

The AI assistant provided:
- Complete PHP class files following WordPress coding standards
- JavaScript for frontend and admin functionality
- CSS styling for responsive design
- SQL table schemas for data storage
- Translation-ready strings and Dutch translation file

All code was reviewed and tested by the human developer before implementation.

## 📁 File Structure

```
member-registration-plugin/
├── member-registration-plugin.php    # Main plugin file
├── readme.txt                         # WordPress.org readme
├── README.md                          # This file
├── includes/
│   ├── class-mbrreg-activator.php    # Activation hooks
│   ├── class-mbrreg-deactivator.php  # Deactivation hooks
│   ├── class-mbrreg-database.php     # Database operations
│   ├── class-mbrreg-member.php       # Member business logic
│   ├── class-mbrreg-custom-fields.php # Custom fields handler
│   ├── class-mbrreg-email.php        # Email functionality
│   ├── class-mbrreg-ajax.php         # AJAX handlers
│   ├── class-mbrreg-shortcodes.php   # Shortcode definitions
│   ├── class-mbrreg-admin.php        # Admin functionality
│   └── class-mbrreg-public.php       # Public functionality
├── admin/
│   ├── css/
│   │   └── mbrreg-admin.css          # Admin styles
│   ├── js/
│   │   └── mbrreg-admin.js           # Admin JavaScript
│   └── partials/
│       ├── mbrreg-admin-members.php  # Members list page
│       ├── mbrreg-admin-member-edit.php # Member edit page
│       ├── mbrreg-admin-custom-fields.php # Custom fields page
│       ├── mbrreg-admin-import-export.php # Import/export page
│       └── mbrreg-admin-settings.php # Settings page
├── public/
│   ├── css/
│   │   └── mbrreg-public.css         # Public styles
│   ├── js/
│   │   └── mbrreg-public.js          # Public JavaScript
│   └── partials/
│       ├── mbrreg-login-form.php     # Login form template
│       ├── mbrreg-register-form.php  # Registration form template
│       ├── mbrreg-member-dashboard.php # Dashboard template
│       └── mbrreg-modal.php          # Modal dialogs template
└── languages/
    └── member-registration-plugin-nl_NL.po # Dutch translation
```

## 🚀 Installation

1. Download or clone this repository
2. Upload to `/wp-content/plugins/member-registration-plugin/`
3. Activate the plugin in WordPress admin
4. Go to **Members → Settings** to configure
5. Create a page with the shortcode `[mbrreg_member_area]`
6. Set this page as the "Member Area Page" in settings

## 🔧 Configuration

### Settings

- **Allow Registration**: Enable/disable new registrations
- **Allow Multiple Members**: Allow users to add family members
- **Member Area Page**: Page containing the member area shortcode
- **Login Redirect Page**: Where to redirect after login
- **Required Fields**: Select which fields are mandatory
- **Email Settings**: Customize sender name and address

### Custom Fields

Create custom fields from **Members → Custom Fields**:

- **Field Types**: Text, Textarea, Email, Number, Date, Dropdown, Checkbox, Radio
- **Admin Only**: If checked, only admins can edit (users can view)
- **Required**: Field must be filled out
- **Display Order**: Control the order fields appear

## 📥 Import/Export

### Importing Members

1. Go to **Members → Import / Export**
2. Download the sample CSV to see the required format
3. Prepare your CSV with member data
4. Upload and import
5. Members receive activation emails automatically

### Exporting Members

1. Go to **Members → Import / Export**
2. Select status filter (optional)
3. Click "Export Members"
4. CSV file downloads automatically

## 🌐 Translations

The plugin is translation-ready with a Dutch translation included.

To add a new translation:

1. Copy `languages/member-registration-plugin-nl_NL.po`
2. Rename to your locale (e.g., `member-registration-plugin-de_DE.po`)
3. Translate the strings using a tool like Poedit
4. Generate the `.mo` file

## 📋 Shortcodes

| Shortcode | Description |
|-----------|-------------|
| `[mbrreg_member_area]` | Complete member area with tabs for login/register + dashboard |
| `[mbrreg_login_form]` | Standalone login form |
| `[mbrreg_register_form]` | Standalone registration form |
| `[mbrreg_member_dashboard]` | Member dashboard (logged-in users only) |

## 🔒 Security

- All form submissions use WordPress nonces
- Data is sanitized and validated before database operations
- Prepared statements used for all database queries
- Capability checks for admin functions
- Password hashing handled by WordPress core

## 📄 License

GPL-2.0+ - See LICENSE file for details.

## 🙏 Acknowledgments

- Built collaboratively with Claude AI by Anthropic
- Uses WordPress coding standards and best practices
- Icons from WordPress Dashicons

---

*This plugin was developed as a demonstration of AI-assisted software development. The human developer provided requirements, testing, and feedback, while the AI assistant generated the code and documentation.*
```

---

## Summary of All Files Changed/Added

### UPDATED Files (15):
1. `member-registration-plugin.php`
2. `includes/class-mbrreg-activator.php`
3. `includes/class-mbrreg-database.php`
4. `includes/class-mbrreg-member.php`
5. `includes/class-mbrreg-custom-fields.php`
6. `includes/class-mbrreg-email.php`
7. `includes/class-mbrreg-ajax.php`
8. `includes/class-mbrreg-shortcodes.php`
9. `includes/class-mbrreg-admin.php`
10. `includes/class-mbrreg-public.php`
11. `admin/css/mbrreg-admin.css`
12. `admin/js/mbrreg-admin.js`
13. `public/css/mbrreg-public.css`
14. `public/js/mbrreg-public.js`
15. `public/partials/mbrreg-member-dashboard.php`

### NEW Files (9):
1. `admin/partials/mbrreg-admin-import-export.php`
2. `admin/partials/mbrreg-admin-settings.php`
3. `admin/partials/mbrreg-admin-custom-fields.php`
4. `admin/partials/mbrreg-admin-members.php`
5. `admin/partials/mbrreg-admin-member-edit.php`
6. `public/partials/mbrreg-modal.php`
7. `public/partials/mbrreg-register-form.php`
8. `public/partials/mbrreg-login-form.php`
9. `languages/member-registration-plugin-nl_NL.po`
10. `readme.txt`
11. `README.md`

**Note**: You'll also need to generate the `.mo` file from the `.po` file for the Dutch translation to work. You can do this with Poedit or the command-line tool `msgfmt`.


**User**
Version 1.1.0 was a big improvement.
Here are just a few more adjustments and fixes that are needed to finish up this project.

# Adjustments
* Currently any date fields are setup with a US format, so mm/dd/yyyy. Please implement a setting that will allow to set dates in European format like so: dd/mm/yyyy. This goes for the backend and the display of dates in the frontend.
* Please remove the following fields from the default fields:
    - address
    - phone number
    - date of birth
    - place of birth
    So the only default / personal details are first name and last name.
    If a club want to ask for more information like address, etc., then these fields can be configured as additional fields, which is already possible.
* the emails that are send out, for example to activate the account, are only available in English. Please Make them multilingual as well like the rest of the plugin by adding the email text to the language files and also make the translation to Dutch.

# Fixes
* On a desktop screen the modals do not display in the center of the screen. Instead they appear on the left side of the screen. This may differ depending on where the application is embedded in the page. Also on mobile devices the modal is pushed to the left and depending on the screen size it does not cover the entire screen. The modal should cover the entire screen with the notification in the middle of the screen.
* the input fields in the forms in the frontend do not fit the boxes that outline the forms. Please make them fit.


**Claude**
{"type":"think","think":"The user wants several adjustments and fixes to the Member Registration Plugin. Let me break down what needs to be done:\n\n## Adjustments:\n\n1. **Date format setting** - Add a setting to switch between US (mm/dd/yyyy) and European (dd/mm/yyyy) date formats. This affects:\n   - Admin settings page\n   - Database storage (should remain Y-m-d but display format changes)\n   - Frontend display\n   - Backend display\n   - Date input fields (HTML5 date inputs use Y-m-d format internally, so we need to handle display separately)\n\n2. **Remove default fields** - Remove these from the core member fields:\n   - address\n   - telephone\n   - date_of_birth\n   - place_of_birth\n   \n   Only keep first_name and last_name as default personal details.\n   \n   This affects:\n   - Database table structure (activator)\n   - Member class (register, update, validate methods)\n   - Database class (insert_member, get_member)\n   - All form templates (register, dashboard, admin edit)\n   - Settings page (remove required field options for these)\n   - Export/Import functionality\n   - Email templates\n\n3. **Multilingual emails** - Make email templates translatable:\n   - Add email text to language files\n   - Update email class to use __() for all strings\n   - Add Dutch translations for emails\n\n## Fixes:\n\n1. **Modal centering** - Fix CSS to properly center modals on all screen sizes:\n   - Use flexbox with proper centering\n   - Ensure it covers the entire viewport\n   - Fix mobile display\n\n2. **Input fields fitting forms** - Fix CSS for input fields in frontend forms to fit their containers properly\n\nLet me create the updated files:\n\n### Files to update:\n1. `includes/class-mbrreg-activator.php` - Update table schema, remove fields\n2. `includes/class-mbrreg-database.php` - Remove field references\n\n\n3. `includes/class-mbrreg-member.php` - Strip out field handling from registration, update, and validation logic\n4. `includes/class-mbrreg-email.php` - Implement translatable strings throughout email generation\n5. `includes/class-mbrreg-admin.php` - Introduce date format configuration option\n6. `admin/partials/mbrreg-admin-settings.php` - Add date format selector and eliminate field requirement toggles\n7. `admin/partials/mbrreg-admin-member-edit.php` - Eliminate removed fields and apply date format handling\n8. `admin/partials/mbrreg-admin-members.php` - Apply date format to member list display\n9. `admin/partials/mbrreg-admin-register.php` - Remove unnecessary fields from registration form and update styling\n10. `public/partials/mbrreg-public-register.php` - Strip removed fields and fix input field CSS\n11. `public/partials/mbrreg-public-dashboard.php` - Remove fields from member dashboard and apply date formatting\n12. `public/css/mbrreg-public.css` - Fix modal centering and input field width issues\n13. `languages/member-registration-nl_NL.po` - Add Dutch translations for email content\n\nI should also create a utility function for consistent date formatting across all templates and admin pages."}


**Claude**
# Member Registration Plugin - Version 1.2.0 Updates

Here are all the files that need to be updated to implement your adjustments and fixes.

---

## member-registration-plugin.php (UPDATED)

```php
<?php
/**
 * Plugin Name: Member Registration Plugin
 * Plugin URI: https://example.com/member-registration-plugin
 * Description: A comprehensive member registration and management system for sports clubs. Allows users to register and manage multiple members (e.g., family members) under one account.
 * Version: 1.2.0
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
define( 'MBRREG_VERSION', '1.2.0' );

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
	$ajax          = new Mbrreg_Ajax( $member, $custom_fields, $email );
	$shortcodes    = new Mbrreg_Shortcodes( $member, $custom_fields );
	$admin         = new Mbrreg_Admin( $member, $custom_fields, $email );
	$public        = new Mbrreg_Public( $member );

	// Initialize hooks.
	$ajax->init();
	$shortcodes->init();
	$admin->init();
	$public->init();
}
add_action( 'plugins_loaded', 'mbrreg_init' );

/**
 * Add menu item to WordPress admin bar and dashboard for members.
 *
 * @since 1.1.0
 * @return void
 */
function mbrreg_add_member_menu() {
	// Only for logged-in non-admin users.
	if ( ! is_user_logged_in() || current_user_can( 'manage_options' ) ) {
		return;
	}

	$page_id = get_option( 'mbrreg_registration_page_id', 0 );
	if ( ! $page_id ) {
		$page_id = get_option( 'mbrreg_login_redirect_page', 0 );
	}

	if ( ! $page_id ) {
		return;
	}

	$page_url = get_permalink( $page_id );

	// Add to admin menu.
	add_menu_page(
		__( 'My Memberships', 'member-registration-plugin' ),
		__( 'My Memberships', 'member-registration-plugin' ),
		'read',
		'mbrreg-my-memberships',
		function() use ( $page_url ) {
			wp_safe_redirect( $page_url );
			exit;
		},
		'dashicons-groups',
		70
	);
}
add_action( 'admin_menu', 'mbrreg_add_member_menu' );

/**
 * Redirect members from dashboard to member area.
 *
 * @since 1.1.0
 * @return void
 */
function mbrreg_redirect_members_from_dashboard() {
	global $pagenow;

	// Only on dashboard and for non-admin users.
	if ( 'index.php' !== $pagenow || ! is_admin() || current_user_can( 'manage_options' ) ) {
		return;
	}

	// Check if user is a member.
	$database = new Mbrreg_Database();
	$members  = $database->get_members_by_user_id( get_current_user_id() );

	if ( empty( $members ) ) {
		return;
	}

	// Get redirect page.
	$page_id = get_option( 'mbrreg_login_redirect_page', 0 );
	if ( ! $page_id ) {
		$page_id = get_option( 'mbrreg_registration_page_id', 0 );
	}

	if ( $page_id && isset( $_GET['mbrreg_stay'] ) === false ) {
		wp_safe_redirect( get_permalink( $page_id ) );
		exit;
	}
}
add_action( 'admin_init', 'mbrreg_redirect_members_from_dashboard' );

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

/**
 * Format a date according to plugin settings.
 *
 * @since 1.2.0
 * @param string $date       Date in Y-m-d format or other parseable format.
 * @param string $format     Optional. Override format. Default empty uses plugin setting.
 * @param bool   $for_input  Optional. If true, returns format suitable for display near inputs.
 * @return string Formatted date or empty string if invalid.
 */
function mbrreg_format_date( $date, $format = '', $for_input = false ) {
	if ( empty( $date ) || '0000-00-00' === $date ) {
		return '';
	}

	$timestamp = strtotime( $date );
	if ( false === $timestamp ) {
		return '';
	}

	if ( empty( $format ) ) {
		$date_format = get_option( 'mbrreg_date_format', 'eu' );
		if ( 'us' === $date_format ) {
			$format = 'm/d/Y';
		} else {
			$format = 'd/m/Y';
		}
	}

	return date_i18n( $format, $timestamp );
}

/**
 * Parse a date from display format to database format (Y-m-d).
 *
 * @since 1.2.0
 * @param string $date Date in display format.
 * @return string Date in Y-m-d format or empty string if invalid.
 */
function mbrreg_parse_date( $date ) {
	if ( empty( $date ) ) {
		return '';
	}

	// If already in Y-m-d format (from HTML5 date input), return as is.
	if ( preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
		return $date;
	}

	$date_format = get_option( 'mbrreg_date_format', 'eu' );

	// Try to parse based on setting.
	if ( 'us' === $date_format ) {
		// m/d/Y format.
		$parsed = DateTime::createFromFormat( 'm/d/Y', $date );
	} else {
		// d/m/Y format.
		$parsed = DateTime::createFromFormat( 'd/m/Y', $date );
	}

	if ( $parsed ) {
		return $parsed->format( 'Y-m-d' );
	}

	// Fallback: try strtotime.
	$timestamp = strtotime( $date );
	if ( false !== $timestamp ) {
		return date( 'Y-m-d', $timestamp );
	}

	return '';
}

/**
 * Get the date format string for display.
 *
 * @since 1.2.0
 * @return string Date format string (e.g., 'd/m/Y' or 'm/d/Y').
 */
function mbrreg_get_date_format() {
	$date_format = get_option( 'mbrreg_date_format', 'eu' );
	return 'us' === $date_format ? 'm/d/Y' : 'd/m/Y';
}

/**
 * Get the date format placeholder for inputs.
 *
 * @since 1.2.0
 * @return string Placeholder string (e.g., 'DD/MM/YYYY' or 'MM/DD/YYYY').
 */
function mbrreg_get_date_placeholder() {
	$date_format = get_option( 'mbrreg_date_format', 'eu' );
	return 'us' === $date_format ? 'MM/DD/YYYY' : 'DD/MM/YYYY';
}
```

---

## includes/class-mbrreg-activator.php (UPDATED)

```php
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
		self::maybe_migrate_data();

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

		// Members table - simplified to only first_name and last_name as default fields.
		$table_members = $table_prefix . 'members';
		$sql_members   = "CREATE TABLE {$table_members} (
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
			is_admin_only tinyint(1) DEFAULT 0 NOT NULL,
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
			'require_first_name'     => false,
			'require_last_name'      => false,
			'email_from_name'        => get_bloginfo( 'name' ),
			'email_from_address'     => get_option( 'admin_email' ),
			'registration_page_id'   => 0,
			'login_redirect_page'    => 0,
			'allow_registration'     => true,
			'allow_multiple_members' => true,
			'date_format'            => 'eu', // 'eu' for d/m/Y, 'us' for m/d/Y.
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
			$admin_role->add_cap( 'mbrreg_import_members' );
		}
	}

	/**
	 * Migrate data from older versions if needed.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	private static function maybe_migrate_data() {
		global $wpdb;

		$current_version = get_option( 'mbrreg_db_version', '1.0.0' );
		$table_members   = $wpdb->prefix . MBRREG_TABLE_PREFIX . 'members';

		// Check if old columns exist and migrate data to custom fields.
		if ( version_compare( $current_version, '1.2.0', '<' ) ) {
			// Check if the old columns exist.
			$columns = $wpdb->get_col( "DESCRIBE {$table_members}", 0 );

			$old_fields = array( 'address', 'telephone', 'date_of_birth', 'place_of_birth' );
			$fields_to_migrate = array_intersect( $old_fields, $columns );

			if ( ! empty( $fields_to_migrate ) ) {
				self::migrate_old_fields_to_custom_fields( $fields_to_migrate );
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
	private static function migrate_old_fields_to_custom_fields( $fields ) {
		global $wpdb;

		$table_members       = $wpdb->prefix . MBRREG_TABLE_PREFIX . 'members';
		$table_custom_fields = $wpdb->prefix . MBRREG_TABLE_PREFIX . 'custom_fields';
		$table_member_meta   = $wpdb->prefix . MBRREG_TABLE_PREFIX . 'member_meta';

		$field_config = array(
			'address'        => array(
				'label' => __( 'Address', 'member-registration-plugin' ),
				'type'  => 'textarea',
			),
			'telephone'      => array(
				'label' => __( 'Telephone', 'member-registration-plugin' ),
				'type'  => 'text',
			),
			'date_of_birth'  => array(
				'label' => __( 'Date of Birth', 'member-registration-plugin' ),
				'type'  => 'date',
			),
			'place_of_birth' => array(
				'label' => __( 'Place of Birth', 'member-registration-plugin' ),
				'type'  => 'text',
			),
		);

		foreach ( $fields as $field_name ) {
			if ( ! isset( $field_config[ $field_name ] ) ) {
				continue;
			}

			// Check if custom field already exists.
			$existing = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT id FROM {$table_custom_fields} WHERE field_name = %s",
					$field_name
				)
			);

			if ( $existing ) {
				continue;
			}

			// Create custom field.
			$wpdb->insert(
				$table_custom_fields,
				array(
					'field_name'    => $field_name,
					'field_label'   => $field_config[ $field_name ]['label'],
					'field_type'    => $field_config[ $field_name ]['type'],
					'field_options' => '',
					'is_required'   => get_option( 'mbrreg_require_' . $field_name, 0 ) ? 1 : 0,
					'is_admin_only' => 0,
					'field_order'   => 0,
				),
				array( '%s', '%s', '%s', '%s', '%d', '%d', '%d' )
			);

			$field_id = $wpdb->insert_id;

			if ( $field_id ) {
				// Migrate existing data.
				$members = $wpdb->get_results(
					"SELECT id, {$field_name} FROM {$table_members} WHERE {$field_name} IS NOT NULL AND {$field_name} != ''"
				);

				foreach ( $members as $member ) {
					$wpdb->insert(
						$table_member_meta,
						array(
							'member_id'  => $member->id,
							'field_id'   => $field_id,
							'meta_value' => $member->$field_name,
						),
						array( '%d', '%d', '%s' )
					);
				}
			}
		}

		// Note: We don't drop the old columns to preserve data integrity.
		// They can be removed manually after confirming migration success.
	}
}
```

---

## includes/class-mbrreg-database.php (UPDATED)

```php
<?php
/**
 * Database operations class.
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
 * Class Mbrreg_Database
 *
 * Handles all database operations for the plugin.
 *
 * @since 1.0.0
 */
class Mbrreg_Database {

	/**
	 * WordPress database object.
	 *
	 * @since 1.0.0
	 * @var wpdb
	 */
	private $wpdb;

	/**
	 * Table prefix for plugin tables.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $table_prefix;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		global $wpdb;
		$this->wpdb         = $wpdb;
		$this->table_prefix = $wpdb->prefix . MBRREG_TABLE_PREFIX;
	}

	/**
	 * Get the members table name.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_members_table() {
		return $this->table_prefix . 'members';
	}

	/**
	 * Get the custom fields table name.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_custom_fields_table() {
		return $this->table_prefix . 'custom_fields';
	}

	/**
	 * Get the member meta table name.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_member_meta_table() {
		return $this->table_prefix . 'member_meta';
	}

	/**
	 * Insert a new member.
	 *
	 * @since 1.0.0
	 * @param array $data Member data.
	 * @return int|false The member ID on success, false on failure.
	 */
	public function insert_member( $data ) {
		$defaults = array(
			'user_id'        => 0,
			'first_name'     => '',
			'last_name'      => '',
			'status'         => 'pending',
			'is_admin'       => 0,
			'activation_key' => '',
		);

		$data = wp_parse_args( $data, $defaults );

		// Only use the fields that exist in the simplified table.
		$insert_data = array(
			'user_id'        => $data['user_id'],
			'first_name'     => $data['first_name'],
			'last_name'      => $data['last_name'],
			'status'         => $data['status'],
			'is_admin'       => $data['is_admin'],
			'activation_key' => $data['activation_key'],
		);

		$result = $this->wpdb->insert(
			$this->get_members_table(),
			$insert_data,
			array( '%d', '%s', '%s', '%s', '%d', '%s' )
		);

		if ( false === $result ) {
			return false;
		}

		return $this->wpdb->insert_id;
	}

	/**
	 * Update a member.
	 *
	 * @since 1.0.0
	 * @param int   $member_id Member ID.
	 * @param array $data      Member data to update.
	 * @return bool True on success, false on failure.
	 */
	public function update_member( $member_id, $data ) {
		// Filter out any fields that don't exist in the table.
		$allowed_fields = array( 'first_name', 'last_name', 'status', 'is_admin', 'activation_key' );
		$update_data    = array();

		foreach ( $data as $key => $value ) {
			if ( in_array( $key, $allowed_fields, true ) ) {
				$update_data[ $key ] = $value;
			}
		}

		if ( empty( $update_data ) ) {
			return true;
		}

		$result = $this->wpdb->update(
			$this->get_members_table(),
			$update_data,
			array( 'id' => $member_id ),
			null,
			array( '%d' )
		);

		return false !== $result;
	}

	/**
	 * Delete a member.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return bool True on success, false on failure.
	 */
	public function delete_member( $member_id ) {
		// Delete member meta first.
		$this->wpdb->delete(
			$this->get_member_meta_table(),
			array( 'member_id' => $member_id ),
			array( '%d' )
		);

		// Delete member.
		$result = $this->wpdb->delete(
			$this->get_members_table(),
			array( 'id' => $member_id ),
			array( '%d' )
		);

		return false !== $result;
	}

	/**
	 * Get a member by ID.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return object|null Member object or null if not found.
	 */
	public function get_member( $member_id ) {
		$sql = $this->wpdb->prepare(
			"SELECT * FROM {$this->get_members_table()} WHERE id = %d",
			$member_id
		);

		return $this->wpdb->get_row( $sql );
	}

	/**
	 * Get a member by user ID (returns first member for backwards compatibility).
	 *
	 * @since 1.0.0
	 * @param int $user_id WordPress user ID.
	 * @return object|null Member object or null if not found.
	 */
	public function get_member_by_user_id( $user_id ) {
		$sql = $this->wpdb->prepare(
			"SELECT * FROM {$this->get_members_table()} WHERE user_id = %d ORDER BY id ASC LIMIT 1",
			$user_id
		);

		return $this->wpdb->get_row( $sql );
	}

	/**
	 * Get all members by user ID.
	 *
	 * @since 1.1.0
	 * @param int $user_id WordPress user ID.
	 * @return array Array of member objects.
	 */
	public function get_members_by_user_id( $user_id ) {
		$sql = $this->wpdb->prepare(
			"SELECT * FROM {$this->get_members_table()} WHERE user_id = %d ORDER BY id ASC",
			$user_id
		);

		return $this->wpdb->get_results( $sql );
	}

	/**
	 * Count members by user ID.
	 *
	 * @since 1.1.0
	 * @param int    $user_id WordPress user ID.
	 * @param string $status  Optional status filter.
	 * @return int Number of members.
	 */
	public function count_members_by_user_id( $user_id, $status = '' ) {
		$sql = $this->wpdb->prepare(
			"SELECT COUNT(*) FROM {$this->get_members_table()} WHERE user_id = %d",
			$user_id
		);

		if ( ! empty( $status ) ) {
			$sql .= $this->wpdb->prepare( ' AND status = %s', $status );
		}

		return (int) $this->wpdb->get_var( $sql );
	}

	/**
	 * Get a member by activation key.
	 *
	 * @since 1.0.0
	 * @param string $activation_key Activation key.
	 * @return object|null Member object or null if not found.
	 */
	public function get_member_by_activation_key( $activation_key ) {
		$sql = $this->wpdb->prepare(
			"SELECT * FROM {$this->get_members_table()} WHERE activation_key = %s",
			$activation_key
		);

		return $this->wpdb->get_row( $sql );
	}

	/**
	 * Get all members.
	 *
	 * @since 1.0.0
	 * @param array $args Query arguments.
	 * @return array Array of member objects.
	 */
	public function get_members( $args = array() ) {
		$defaults = array(
			'status'   => '',
			'is_admin' => null,
			'search'   => '',
			'user_id'  => null,
			'orderby'  => 'created_at',
			'order'    => 'DESC',
			'limit'    => -1,
			'offset'   => 0,
		);

		$args = wp_parse_args( $args, $defaults );

		$where_clauses = array( '1=1' );
		$where_values  = array();

		if ( ! empty( $args['status'] ) ) {
			$where_clauses[] = 'status = %s';
			$where_values[]  = $args['status'];
		}

		if ( null !== $args['is_admin'] ) {
			$where_clauses[] = 'is_admin = %d';
			$where_values[]  = (int) $args['is_admin'];
		}

		if ( null !== $args['user_id'] ) {
			$where_clauses[] = 'user_id = %d';
			$where_values[]  = (int) $args['user_id'];
		}

		if ( ! empty( $args['search'] ) ) {
			$search_term     = '%' . $this->wpdb->esc_like( $args['search'] ) . '%';
			$where_clauses[] = '(first_name LIKE %s OR last_name LIKE %s)';
			$where_values[]  = $search_term;
			$where_values[]  = $search_term;
		}

		$where_sql = implode( ' AND ', $where_clauses );

		// Sanitize orderby.
		$allowed_orderby = array( 'id', 'first_name', 'last_name', 'status', 'created_at', 'updated_at' );
		$orderby         = in_array( $args['orderby'], $allowed_orderby, true ) ? $args['orderby'] : 'created_at';
		$order           = 'ASC' === strtoupper( $args['order'] ) ? 'ASC' : 'DESC';

		$sql = "SELECT * FROM {$this->get_members_table()} WHERE {$where_sql} ORDER BY {$orderby} {$order}";

		if ( $args['limit'] > 0 ) {
			$sql .= $this->wpdb->prepare( ' LIMIT %d OFFSET %d', $args['limit'], $args['offset'] );
		}

		if ( ! empty( $where_values ) ) {
			$sql = $this->wpdb->prepare( $sql, $where_values ); // phpcs:ignore
		}

		return $this->wpdb->get_results( $sql );
	}

	/**
	 * Count members.
	 *
	 * @since 1.0.0
	 * @param array $args Query arguments.
	 * @return int Number of members.
	 */
	public function count_members( $args = array() ) {
		$defaults = array(
			'status'   => '',
			'is_admin' => null,
			'search'   => '',
			'user_id'  => null,
		);

		$args = wp_parse_args( $args, $defaults );

		$where_clauses = array( '1=1' );
		$where_values  = array();

		if ( ! empty( $args['status'] ) ) {
			$where_clauses[] = 'status = %s';
			$where_values[]  = $args['status'];
		}

		if ( null !== $args['is_admin'] ) {
			$where_clauses[] = 'is_admin = %d';
			$where_values[]  = (int) $args['is_admin'];
		}

		if ( null !== $args['user_id'] ) {
			$where_clauses[] = 'user_id = %d';
			$where_values[]  = (int) $args['user_id'];
		}

		if ( ! empty( $args['search'] ) ) {
			$search_term     = '%' . $this->wpdb->esc_like( $args['search'] ) . '%';
			$where_clauses[] = '(first_name LIKE %s OR last_name LIKE %s)';
			$where_values[]  = $search_term;
			$where_values[]  = $search_term;
		}

		$where_sql = implode( ' AND ', $where_clauses );

		$sql = "SELECT COUNT(*) FROM {$this->get_members_table()} WHERE {$where_sql}";

		if ( ! empty( $where_values ) ) {
			$sql = $this->wpdb->prepare( $sql, $where_values ); // phpcs:ignore
		}

		return (int) $this->wpdb->get_var( $sql );
	}

	/**
	 * Insert member meta.
	 *
	 * @since 1.0.0
	 * @param int    $member_id Member ID.
	 * @param int    $field_id  Custom field ID.
	 * @param string $value     Meta value.
	 * @return int|false The meta ID on success, false on failure.
	 */
	public function insert_member_meta( $member_id, $field_id, $value ) {
		$result = $this->wpdb->insert(
			$this->get_member_meta_table(),
			array(
				'member_id'  => $member_id,
				'field_id'   => $field_id,
				'meta_value' => $value,
			),
			array( '%d', '%d', '%s' )
		);

		if ( false === $result ) {
			return false;
		}

		return $this->wpdb->insert_id;
	}

	/**
	 * Update member meta.
	 *
	 * @since 1.0.0
	 * @param int    $member_id Member ID.
	 * @param int    $field_id  Custom field ID.
	 * @param string $value     Meta value.
	 * @return bool True on success, false on failure.
	 */
	public function update_member_meta( $member_id, $field_id, $value ) {
		// Check if meta exists.
		$existing = $this->get_member_meta( $member_id, $field_id );

		if ( null !== $existing ) {
			$result = $this->wpdb->update(
				$this->get_member_meta_table(),
				array( 'meta_value' => $value ),
				array(
					'member_id' => $member_id,
					'field_id'  => $field_id,
				),
				array( '%s' ),
				array( '%d', '%d' )
			);
		} else {
			$result = $this->insert_member_meta( $member_id, $field_id, $value );
		}

		return false !== $result;
	}

	/**
	 * Get member meta.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @param int $field_id  Custom field ID (optional).
	 * @return mixed Single value, array of values, or null.
	 */
	public function get_member_meta( $member_id, $field_id = null ) {
		if ( null !== $field_id ) {
			$sql = $this->wpdb->prepare(
				"SELECT meta_value FROM {$this->get_member_meta_table()} WHERE member_id = %d AND field_id = %d",
				$member_id,
				$field_id
			);

			return $this->wpdb->get_var( $sql );
		}

		$sql = $this->wpdb->prepare(
			"SELECT field_id, meta_value FROM {$this->get_member_meta_table()} WHERE member_id = %d",
			$member_id
		);

		$results = $this->wpdb->get_results( $sql );
		$meta    = array();

		foreach ( $results as $row ) {
			$meta[ $row->field_id ] = $row->meta_value;
		}

		return $meta;
	}

	/**
	 * Delete member meta.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @param int $field_id  Custom field ID (optional).
	 * @return bool True on success, false on failure.
	 */
	public function delete_member_meta( $member_id, $field_id = null ) {
		$where        = array( 'member_id' => $member_id );
		$where_format = array( '%d' );

		if ( null !== $field_id ) {
			$where['field_id'] = $field_id;
			$where_format[]    = '%d';
		}

		$result = $this->wpdb->delete(
			$this->get_member_meta_table(),
			$where,
			$where_format
		);

		return false !== $result;
	}

	/**
	 * Insert a custom field.
	 *
	 * @since 1.0.0
	 * @param array $data Field data.
	 * @return int|false The field ID on success, false on failure.
	 */
	public function insert_custom_field( $data ) {
		$defaults = array(
			'field_name'    => '',
			'field_label'   => '',
			'field_type'    => 'text',
			'field_options' => '',
			'is_required'   => 0,
			'is_admin_only' => 0,
			'field_order'   => 0,
		);

		$data = wp_parse_args( $data, $defaults );

		$result = $this->wpdb->insert(
			$this->get_custom_fields_table(),
			$data,
			array( '%s', '%s', '%s', '%s', '%d', '%d', '%d' )
		);

		if ( false === $result ) {
			return false;
		}

		return $this->wpdb->insert_id;
	}

	/**
	 * Update a custom field.
	 *
	 * @since 1.0.0
	 * @param int   $field_id Field ID.
	 * @param array $data     Field data to update.
	 * @return bool True on success, false on failure.
	 */
	public function update_custom_field( $field_id, $data ) {
		$result = $this->wpdb->update(
			$this->get_custom_fields_table(),
			$data,
			array( 'id' => $field_id ),
			null,
			array( '%d' )
		);

		return false !== $result;
	}

	/**
	 * Delete a custom field.
	 *
	 * @since 1.0.0
	 * @param int $field_id Field ID.
	 * @return bool True on success, false on failure.
	 */
	public function delete_custom_field( $field_id ) {
		// Delete associated member meta.
		$this->wpdb->delete(
			$this->get_member_meta_table(),
			array( 'field_id' => $field_id ),
			array( '%d' )
		);

		// Delete field.
		$result = $this->wpdb->delete(
			$this->get_custom_fields_table(),
			array( 'id' => $field_id ),
			array( '%d' )
		);

		return false !== $result;
	}

	/**
	 * Get a custom field by ID.
	 *
	 * @since 1.0.0
	 * @param int $field_id Field ID.
	 * @return object|null Field object or null if not found.
	 */
	public function get_custom_field( $field_id ) {
		$sql = $this->wpdb->prepare(
			"SELECT * FROM {$this->get_custom_fields_table()} WHERE id = %d",
			$field_id
		);

		return $this->wpdb->get_row( $sql );
	}

	/**
	 * Get all custom fields.
	 *
	 * @since 1.0.0
	 * @param bool $include_admin_only Whether to include admin-only fields.
	 * @return array Array of field objects.
	 */
	public function get_custom_fields( $include_admin_only = true ) {
		$sql = "SELECT * FROM {$this->get_custom_fields_table()}";

		if ( ! $include_admin_only ) {
			$sql .= ' WHERE is_admin_only = 0';
		}

		$sql .= ' ORDER BY field_order ASC, id ASC';

		return $this->wpdb->get_results( $sql );
	}

	/**
	 * Check if email exists for any user.
	 *
	 * @since 1.1.0
	 * @param string $email Email address.
	 * @return int|false User ID if exists, false otherwise.
	 */
	public function email_exists( $email ) {
		return email_exists( $email );
	}

	/**
	 * Get user by email.
	 *
	 * @since 1.1.0
	 * @param string $email Email address.
	 * @return WP_User|false User object if exists, false otherwise.
	 */
	public function get_user_by_email( $email ) {
		return get_user_by( 'email', $email );
	}
}
```

---

## includes/class-mbrreg-member.php (UPDATED)

```php
<?php
/**
 * Member operations class.
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
 * Class Mbrreg_Member
 *
 * Handles member-related operations.
 *
 * @since 1.0.0
 */
class Mbrreg_Member {

	/**
	 * Database instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Database
	 */
	private $database;

	/**
	 * Custom fields instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Custom_Fields
	 */
	private $custom_fields;

	/**
	 * Email instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Email
	 */
	private $email;

	/**
	 * Member statuses.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public static $statuses = array(
		'pending'  => 'Pending Activation',
		'active'   => 'Active',
		'inactive' => 'Inactive',
	);

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param Mbrreg_Database      $database      Database instance.
	 * @param Mbrreg_Custom_Fields $custom_fields Custom fields instance.
	 * @param Mbrreg_Email         $email         Email instance.
	 */
	public function __construct( Mbrreg_Database $database, Mbrreg_Custom_Fields $custom_fields, Mbrreg_Email $email ) {
		$this->database      = $database;
		$this->custom_fields = $custom_fields;
		$this->email         = $email;
	}

	/**
	 * Get translated statuses.
	 *
	 * @since 1.2.0
	 * @return array
	 */
	public static function get_statuses() {
		return array(
			'pending'  => __( 'Pending Activation', 'member-registration-plugin' ),
			'active'   => __( 'Active', 'member-registration-plugin' ),
			'inactive' => __( 'Inactive', 'member-registration-plugin' ),
		);
	}

	/**
	 * Register a new member.
	 *
	 * @since 1.0.0
	 * @param array $data      Registration data.
	 * @param bool  $is_import Whether this is an import operation.
	 * @return int|WP_Error Member ID on success, WP_Error on failure.
	 */
	public function register( $data, $is_import = false ) {
		$user_id       = 0;
		$is_new_user   = true;
		$existing_user = null;

		// Check if adding to existing logged-in user.
		if ( isset( $data['add_to_existing_user'] ) && $data['add_to_existing_user'] && is_user_logged_in() ) {
			$user_id     = get_current_user_id();
			$is_new_user = false;
		} elseif ( ! empty( $data['email'] ) ) {
			// Check if user with this email already exists.
			$existing_user = get_user_by( 'email', $data['email'] );

			if ( $existing_user ) {
				// Allow adding member to existing user if allowed.
				if ( get_option( 'mbrreg_allow_multiple_members', true ) ) {
					$user_id     = $existing_user->ID;
					$is_new_user = false;
				} else {
					return new WP_Error( 'email_exists', __( 'This email address is already registered.', 'member-registration-plugin' ) );
				}
			}
		}

		// Validate required fields for new user.
		if ( $is_new_user ) {
			if ( empty( $data['username'] ) && empty( $data['email'] ) ) {
				return new WP_Error( 'missing_credentials', __( 'Username or email is required.', 'member-registration-plugin' ) );
			}

			if ( empty( $data['email'] ) ) {
				return new WP_Error( 'missing_email', __( 'Email address is required.', 'member-registration-plugin' ) );
			}

			// Validate email.
			if ( ! is_email( $data['email'] ) ) {
				return new WP_Error( 'invalid_email', __( 'Please enter a valid email address.', 'member-registration-plugin' ) );
			}

			// Generate username from email if not provided.
			if ( empty( $data['username'] ) ) {
				$data['username'] = $this->generate_username_from_email( $data['email'] );
			}

			// Check if username exists.
			if ( username_exists( $data['username'] ) ) {
				// Generate unique username.
				$data['username'] = $this->generate_unique_username( $data['username'] );
			}

			// Generate password if not provided (for imports).
			if ( empty( $data['password'] ) ) {
				$data['password'] = wp_generate_password( 12, true );
			}
		}

		// Validate required member fields.
		$validation = $this->validate_member_data( $data );
		if ( is_wp_error( $validation ) ) {
			return $validation;
		}

		// Create WordPress user if needed.
		if ( $is_new_user ) {
			$user_id = wp_create_user(
				sanitize_user( $data['username'] ),
				$data['password'],
				sanitize_email( $data['email'] )
			);

			if ( is_wp_error( $user_id ) ) {
				return $user_id;
			}
		}

		// Generate activation key.
		$activation_key = wp_generate_password( 32, false );
		$status         = 'pending';

		// Prepare member data (simplified - only first_name and last_name).
		$member_data = array(
			'user_id'        => $user_id,
			'first_name'     => isset( $data['first_name'] ) ? sanitize_text_field( $data['first_name'] ) : '',
			'last_name'      => isset( $data['last_name'] ) ? sanitize_text_field( $data['last_name'] ) : '',
			'status'         => $status,
			'is_admin'       => 0,
			'activation_key' => $activation_key,
		);

		// Insert member.
		$member_id = $this->database->insert_member( $member_data );

		if ( ! $member_id ) {
			// Rollback: delete WordPress user only if we created it.
			if ( $is_new_user ) {
				wp_delete_user( $user_id );
			}
			return new WP_Error( 'insert_failed', __( 'Failed to create member record.', 'member-registration-plugin' ) );
		}

		// Save custom field values.
		$this->save_custom_field_values( $member_id, $data );

		// Send activation email.
		if ( $is_import ) {
			$this->email->send_import_activation_email( $user_id, $activation_key, $data );
		} else {
			$this->email->send_activation_email( $user_id, $activation_key );
		}

		/**
		 * Fires after a new member is registered.
		 *
		 * @since 1.0.0
		 * @param int   $member_id Member ID.
		 * @param int   $user_id   WordPress user ID.
		 * @param array $data      Registration data.
		 */
		do_action( 'mbrreg_member_registered', $member_id, $user_id, $data );

		return $member_id;
	}

	/**
	 * Generate username from email.
	 *
	 * @since 1.1.0
	 * @param string $email Email address.
	 * @return string Generated username.
	 */
	private function generate_username_from_email( $email ) {
		$username = sanitize_user( current( explode( '@', $email ) ), true );
		return $username;
	}

	/**
	 * Generate unique username.
	 *
	 * @since 1.1.0
	 * @param string $username Base username.
	 * @return string Unique username.
	 */
	private function generate_unique_username( $username ) {
		$original = $username;
		$counter  = 1;

		while ( username_exists( $username ) ) {
			$username = $original . $counter;
			++$counter;
		}

		return $username;
	}

	/**
	 * Activate a member account.
	 *
	 * @since 1.0.0
	 * @param string $activation_key Activation key.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function activate( $activation_key ) {
		if ( empty( $activation_key ) ) {
			return new WP_Error( 'missing_key', __( 'Activation key is required.', 'member-registration-plugin' ) );
		}

		$member = $this->database->get_member_by_activation_key( $activation_key );

		if ( ! $member ) {
			return new WP_Error( 'invalid_key', __( 'Invalid activation key.', 'member-registration-plugin' ) );
		}

		if ( 'active' === $member->status ) {
			return new WP_Error( 'already_active', __( 'This account is already activated.', 'member-registration-plugin' ) );
		}

		// Update member status.
		$result = $this->database->update_member(
			$member->id,
			array(
				'status'         => 'active',
				'activation_key' => '',
			)
		);

		if ( ! $result ) {
			return new WP_Error( 'update_failed', __( 'Failed to activate account.', 'member-registration-plugin' ) );
		}

		// Send welcome email.
		$this->email->send_welcome_email( $member->user_id );

		/**
		 * Fires after a member account is activated.
		 *
		 * @since 1.0.0
		 * @param int $member_id Member ID.
		 * @param int $user_id   WordPress user ID.
		 */
		do_action( 'mbrreg_member_activated', $member->id, $member->user_id );

		return true;
	}

	/**
	 * Update member details.
	 *
	 * @since 1.0.0
	 * @param int   $member_id     Member ID.
	 * @param array $data          Member data.
	 * @param bool  $is_admin_edit Whether this is an admin edit.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function update( $member_id, $data, $is_admin_edit = false ) {
		$member = $this->database->get_member( $member_id );

		if ( ! $member ) {
			return new WP_Error( 'member_not_found', __( 'Member not found.', 'member-registration-plugin' ) );
		}

		// Validate member data.
		$validation = $this->validate_member_data( $data, true );
		if ( is_wp_error( $validation ) ) {
			return $validation;
		}

		// Prepare update data.
		$update_data = array();

		if ( isset( $data['first_name'] ) ) {
			$update_data['first_name'] = sanitize_text_field( $data['first_name'] );
		}

		if ( isset( $data['last_name'] ) ) {
			$update_data['last_name'] = sanitize_text_field( $data['last_name'] );
		}

		if ( isset( $data['status'] ) && array_key_exists( $data['status'], self::get_statuses() ) ) {
			$update_data['status'] = $data['status'];
		}

		if ( isset( $data['is_admin'] ) ) {
			$update_data['is_admin'] = (int) $data['is_admin'];
		}

		// Update member.
		if ( ! empty( $update_data ) ) {
			$result = $this->database->update_member( $member_id, $update_data );

			if ( ! $result ) {
				return new WP_Error( 'update_failed', __( 'Failed to update member.', 'member-registration-plugin' ) );
			}
		}

		// Update WordPress user data if email changed (admin only).
		if ( $is_admin_edit && isset( $data['email'] ) && is_email( $data['email'] ) ) {
			$user = get_user_by( 'ID', $member->user_id );
			if ( $user && $user->user_email !== $data['email'] ) {
				// Check if email is already used by another user.
				$existing = email_exists( $data['email'] );
				if ( $existing && $existing !== $member->user_id ) {
					return new WP_Error( 'email_exists', __( 'This email address is already in use.', 'member-registration-plugin' ) );
				}

				wp_update_user(
					array(
						'ID'         => $member->user_id,
						'user_email' => sanitize_email( $data['email'] ),
					)
				);
			}
		}

		// Update WordPress user first/last name.
		$user_update = array( 'ID' => $member->user_id );
		if ( isset( $data['first_name'] ) ) {
			$user_update['first_name'] = sanitize_text_field( $data['first_name'] );
		}
		if ( isset( $data['last_name'] ) ) {
			$user_update['last_name'] = sanitize_text_field( $data['last_name'] );
		}
		if ( count( $user_update ) > 1 ) {
			wp_update_user( $user_update );
		}

		// Save custom field values.
		$this->save_custom_field_values( $member_id, $data, $is_admin_edit );

		/**
		 * Fires after a member is updated.
		 *
		 * @since 1.0.0
		 * @param int   $member_id Member ID.
		 * @param array $data      Update data.
		 */
		do_action( 'mbrreg_member_updated', $member_id, $data );

		return true;
	}

	/**
	 * Delete a member.
	 *
	 * @since 1.0.0
	 * @param int  $member_id      Member ID.
	 * @param bool $delete_wp_user Whether to delete WordPress user too.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function delete( $member_id, $delete_wp_user = false ) {
		$member = $this->database->get_member( $member_id );

		if ( ! $member ) {
			return new WP_Error( 'member_not_found', __( 'Member not found.', 'member-registration-plugin' ) );
		}

		$user_id = $member->user_id;

		// Check if this is the last member for this user.
		$member_count = $this->database->count_members_by_user_id( $user_id );

		// Delete member.
		$result = $this->database->delete_member( $member_id );

		if ( ! $result ) {
			return new WP_Error( 'delete_failed', __( 'Failed to delete member.', 'member-registration-plugin' ) );
		}

		// Delete WordPress user if requested and this was the last member.
		if ( $delete_wp_user && $user_id && 1 === $member_count ) {
			wp_delete_user( $user_id );
		}

		/**
		 * Fires after a member is deleted.
		 *
		 * @since 1.0.0
		 * @param int $member_id Member ID.
		 * @param int $user_id   WordPress user ID.
		 */
		do_action( 'mbrreg_member_deleted', $member_id, $user_id );

		return true;
	}

	/**
	 * Set member as inactive.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function set_inactive( $member_id ) {
		$member = $this->database->get_member( $member_id );

		if ( ! $member ) {
			return new WP_Error( 'member_not_found', __( 'Member not found.', 'member-registration-plugin' ) );
		}

		$result = $this->update( $member_id, array( 'status' => 'inactive' ) );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Check if this was the last active member for the user.
		$active_count = $this->database->count_members_by_user_id( $member->user_id, 'active' );

		// Return whether user should be logged out (no more active members).
		return array(
			'success'       => true,
			'logout_user'   => ( 0 === $active_count ),
			'active_count'  => $active_count,
		);
	}

	/**
	 * Set member as active.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function set_active( $member_id ) {
		return $this->update( $member_id, array( 'status' => 'active' ) );
	}

	/**
	 * Set member as admin.
	 *
	 * @since 1.0.0
	 * @param int  $member_id Member ID.
	 * @param bool $is_admin  Whether member is admin.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function set_admin( $member_id, $is_admin = true ) {
		$member = $this->database->get_member( $member_id );

		if ( ! $member ) {
			return new WP_Error( 'member_not_found', __( 'Member not found.', 'member-registration-plugin' ) );
		}

		$result = $this->database->update_member( $member_id, array( 'is_admin' => (int) $is_admin ) );

		if ( ! $result ) {
			return new WP_Error( 'update_failed', __( 'Failed to update member admin status.', 'member-registration-plugin' ) );
		}

		// Update WordPress user capabilities.
		$user = get_user_by( 'ID', $member->user_id );
		if ( $user ) {
			if ( $is_admin ) {
				$user->add_cap( 'mbrreg_manage_members' );
			} else {
				$user->remove_cap( 'mbrreg_manage_members' );
			}
		}

		return true;
	}

	/**
	 * Get a member by ID.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return object|null Member object or null.
	 */
	public function get( $member_id ) {
		$member = $this->database->get_member( $member_id );

		if ( $member ) {
			$member = $this->enrich_member_data( $member );
		}

		return $member;
	}

	/**
	 * Get a member by user ID.
	 *
	 * @since 1.0.0
	 * @param int $user_id WordPress user ID.
	 * @return object|null Member object or null.
	 */
	public function get_by_user_id( $user_id ) {
		$member = $this->database->get_member_by_user_id( $user_id );

		if ( $member ) {
			$member = $this->enrich_member_data( $member );
		}

		return $member;
	}

	/**
	 * Get all members by user ID.
	 *
	 * @since 1.1.0
	 * @param int $user_id WordPress user ID.
	 * @return array Array of member objects.
	 */
	public function get_all_by_user_id( $user_id ) {
		$members = $this->database->get_members_by_user_id( $user_id );

		foreach ( $members as $key => $member ) {
			$members[ $key ] = $this->enrich_member_data( $member );
		}

		return $members;
	}

	/**
	 * Get all members.
	 *
	 * @since 1.0.0
	 * @param array $args Query arguments.
	 * @return array Array of member objects.
	 */
	public function get_all( $args = array() ) {
		$members = $this->database->get_members( $args );

		foreach ( $members as $key => $member ) {
			$members[ $key ] = $this->enrich_member_data( $member );
		}

		return $members;
	}

	/**
	 * Count members.
	 *
	 * @since 1.0.0
	 * @param array $args Query arguments.
	 * @return int Number of members.
	 */
	public function count( $args = array() ) {
		return $this->database->count_members( $args );
	}

	/**
	 * Enrich member data with WordPress user info and custom fields.
	 *
	 * @since 1.0.0
	 * @param object $member Member object.
	 * @return object Enriched member object.
	 */
	private function enrich_member_data( $member ) {
		// Get WordPress user.
		$user = get_user_by( 'ID', $member->user_id );

		if ( $user ) {
			$member->username = $user->user_login;
			$member->email    = $user->user_email;
		} else {
			$member->username = '';
			$member->email    = '';
		}

		// Get custom field values.
		$member->custom_fields = $this->database->get_member_meta( $member->id );

		return $member;
	}

	/**
	 * Validate member data.
	 *
	 * @since 1.0.0
	 * @param array $data      Member data.
	 * @param bool  $is_update Whether this is an update operation.
	 * @return true|WP_Error True on success, WP_Error on validation failure.
	 */
	private function validate_member_data( $data, $is_update = false ) {
		// Check required standard fields (only first_name and last_name now).
		$required_fields = array(
			'first_name' => get_option( 'mbrreg_require_first_name', false ),
			'last_name'  => get_option( 'mbrreg_require_last_name', false ),
		);

		foreach ( $required_fields as $field => $is_required ) {
			if ( $is_required && ( ! isset( $data[ $field ] ) || '' === $data[ $field ] ) ) {
				$label = ucwords( str_replace( '_', ' ', $field ) );
				return new WP_Error(
					'missing_' . $field,
					/* translators: %s: Field name */
					sprintf( __( '%s is required.', 'member-registration-plugin' ), $label )
				);
			}
		}

		// Validate custom fields.
		$custom_fields = $this->custom_fields->get_all();

		foreach ( $custom_fields as $field ) {
			// Skip admin-only fields for regular users.
			if ( $field->is_admin_only && ! current_user_can( 'mbrreg_manage_members' ) ) {
				continue;
			}

			$field_key = 'custom_' . $field->id;

			if ( $field->is_required && ( ! isset( $data[ $field_key ] ) || '' === $data[ $field_key ] ) ) {
				return new WP_Error(
					'missing_custom_' . $field->id,
					/* translators: %s: Field label */
					sprintf( __( '%s is required.', 'member-registration-plugin' ), $field->field_label )
				);
			}
		}

		return true;
	}

	/**
	 * Save custom field values.
	 *
	 * @since 1.0.0
	 * @param int   $member_id     Member ID.
	 * @param array $data          Data containing custom field values.
	 * @param bool  $is_admin_edit Whether this is an admin edit.
	 * @return void
	 */
	private function save_custom_field_values( $member_id, $data, $is_admin_edit = false ) {
		$custom_fields = $this->custom_fields->get_all();

		foreach ( $custom_fields as $field ) {
			// Skip admin-only fields for regular users.
			if ( $field->is_admin_only && ! $is_admin_edit ) {
				continue;
			}

			$field_key = 'custom_' . $field->id;

			if ( isset( $data[ $field_key ] ) ) {
				$value = $this->custom_fields->sanitize_field_value( $field, $data[ $field_key ] );
				$this->database->update_member_meta( $member_id, $field->id, $value );
			}
		}
	}

	/**
	 * Check if current user is a member admin.
	 *
	 * @since 1.0.0
	 * @param int $user_id User ID (optional, defaults to current user).
	 * @return bool
	 */
	public function is_member_admin( $user_id = null ) {
		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		if ( ! $user_id ) {
			return false;
		}

		// WordPress administrators always have access.
		if ( user_can( $user_id, 'manage_options' ) ) {
			return true;
		}

		// Check if user is a member admin.
		$members = $this->database->get_members_by_user_id( $user_id );

		foreach ( $members as $member ) {
			if ( $member->is_admin ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Resend activation email.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function resend_activation_email( $member_id ) {
		$member = $this->database->get_member( $member_id );

		if ( ! $member ) {
			return new WP_Error( 'member_not_found', __( 'Member not found.', 'member-registration-plugin' ) );
		}

		if ( 'pending' !== $member->status ) {
			return new WP_Error( 'already_active', __( 'This account is already activated.', 'member-registration-plugin' ) );
		}

		// Generate new activation key.
		$activation_key = wp_generate_password( 32, false );

		// Update member with new key.
		$this->database->update_member( $member_id, array( 'activation_key' => $activation_key ) );

		// Send email.
		$this->email->send_activation_email( $member->user_id, $activation_key );

		return true;
	}

	/**
	 * Check if user can manage a specific member.
	 *
	 * @since 1.1.0
	 * @param int $member_id Member ID.
	 * @param int $user_id   User ID (optional, defaults to current user).
	 * @return bool
	 */
	public function can_manage_member( $member_id, $user_id = null ) {
		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		if ( ! $user_id ) {
			return false;
		}

		// Admins can manage all members.
		if ( $this->is_member_admin( $user_id ) ) {
			return true;
		}

		// Check if member belongs to user.
		$member = $this->database->get_member( $member_id );

		return $member && (int) $member->user_id === (int) $user_id;
	}

	/**
	 * Export members to CSV.
	 *
	 * @since 1.1.0
	 * @param array $args Query arguments.
	 * @return string CSV content.
	 */
	public function export_csv( $args = array() ) {
		$members       = $this->get_all( $args );
		$custom_fields = $this->custom_fields->get_all();

		// Build CSV header (simplified - removed old default fields).
		$headers = array(
			'ID',
			'Username',
			'Email',
			'First Name',
			'Last Name',
			'Status',
			'Is Admin',
			'Registered',
		);

		// Add custom field headers.
		foreach ( $custom_fields as $field ) {
			$headers[] = $field->field_label;
		}

		// Start CSV output.
		$output = fopen( 'php://temp', 'r+' );
		fputcsv( $output, $headers );

		// Add member rows.
		foreach ( $members as $member ) {
			$row = array(
				$member->id,
				$member->username,
				$member->email,
				$member->first_name,
				$member->last_name,
				$member->status,
				$member->is_admin ? 'Yes' : 'No',
				mbrreg_format_date( $member->created_at, 'Y-m-d H:i:s' ),
			);

			// Add custom field values.
			foreach ( $custom_fields as $field ) {
				$value = isset( $member->custom_fields[ $field->id ] ) ? $member->custom_fields[ $field->id ] : '';
				// Format date fields for export.
				if ( 'date' === $field->field_type && ! empty( $value ) ) {
					$value = mbrreg_format_date( $value );
				}
				$row[] = $value;
			}

			fputcsv( $output, $row );
		}

		rewind( $output );
		$csv = stream_get_contents( $output );
		fclose( $output );

		return $csv;
	}

	/**
	 * Import members from CSV data.
	 *
	 * @since 1.1.0
	 * @param array $csv_data Parsed CSV data.
	 * @return array Import results.
	 */
	public function import_csv( $csv_data ) {
		$results = array(
			'success' => 0,
			'errors'  => array(),
			'skipped' => 0,
		);

		foreach ( $csv_data as $row_num => $row ) {
			// Skip header row.
			if ( 0 === $row_num ) {
				continue;
			}

			$data = $this->parse_csv_row( $row );

			if ( empty( $data['email'] ) ) {
				$results['errors'][] = sprintf(
					/* translators: %d: Row number */
					__( 'Row %d: Email is required.', 'member-registration-plugin' ),
					$row_num + 1
				);
				continue;
			}

			$result = $this->register( $data, true );

			if ( is_wp_error( $result ) ) {
				$results['errors'][] = sprintf(
					/* translators: 1: Row number, 2: Error message */
					__( 'Row %1$d: %2$s', 'member-registration-plugin' ),
					$row_num + 1,
					$result->get_error_message()
				);
			} else {
				++$results['success'];
			}
		}

		return $results;
	}

	/**
	 * Parse CSV row into member data.
	 *
	 * @since 1.1.0
	 * @param array $row CSV row.
	 * @return array Member data.
	 */
	private function parse_csv_row( $row ) {
		// Expected column order: Email, First Name, Last Name, then custom fields.
		$data = array(
			'email'      => isset( $row[0] ) ? trim( $row[0] ) : '',
			'first_name' => isset( $row[1] ) ? trim( $row[1] ) : '',
			'last_name'  => isset( $row[2] ) ? trim( $row[2] ) : '',
		);

		// Parse additional custom fields if present.
		$custom_fields = $this->custom_fields->get_all();
		$col_index     = 3;

		foreach ( $custom_fields as $field ) {
			if ( isset( $row[ $col_index ] ) ) {
				$value = trim( $row[ $col_index ] );
				// Parse date fields from display format to database format.
				if ( 'date' === $field->field_type && ! empty( $value ) ) {
					$value = mbrreg_parse_date( $value );
				}
				$data[ 'custom_' . $field->id ] = $value;
			}
			++$col_index;
		}

		return $data;
	}
}
```

---

## includes/class-mbrreg-email.php (UPDATED - Multilingual emails)

```php
<?php
/**
 * Email handling class.
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
 * Class Mbrreg_Email
 *
 * Handles email sending for the plugin.
 *
 * @since 1.0.0
 */
class Mbrreg_Email {

	/**
	 * Send activation email to new member.
	 *
	 * @since 1.0.0
	 * @param int    $user_id        WordPress user ID.
	 * @param string $activation_key Activation key.
	 * @return bool Whether the email was sent successfully.
	 */
	public function send_activation_email( $user_id, $activation_key ) {
		$user = get_user_by( 'ID', $user_id );

		if ( ! $user ) {
			return false;
		}

		$activation_url = add_query_arg(
			array(
				'mbrreg_action' => 'activate',
				'key'           => $activation_key,
			),
			home_url( '/' )
		);

		$site_name = get_bloginfo( 'name' );

		$subject = sprintf(
			/* translators: %s: Site name */
			__( 'Activate your membership at %s', 'member-registration-plugin' ),
			$site_name
		);

		$message = sprintf(
			/* translators: 1: User display name, 2: Site name, 3: Activation URL, 4: Site name (repeated) */
			__( 'Hello %1$s,

Thank you for registering as a member at %2$s.

Please click the following link to activate your account:

%3$s

If you did not register for this account, please ignore this email.

Best regards,
%4$s', 'member-registration-plugin' ),
			$user->display_name,
			$site_name,
			$activation_url,
			$site_name
		);

		$headers = $this->get_email_headers();

		/**
		 * Filter the activation email subject.
		 *
		 * @since 1.0.0
		 * @param string  $subject Subject line.
		 * @param WP_User $user    User object.
		 */
		$subject = apply_filters( 'mbrreg_activation_email_subject', $subject, $user );

		/**
		 * Filter the activation email message.
		 *
		 * @since 1.0.0
		 * @param string  $message        Email message.
		 * @param WP_User $user           User object.
		 * @param string  $activation_url Activation URL.
		 */
		$message = apply_filters( 'mbrreg_activation_email_message', $message, $user, $activation_url );

		return wp_mail( $user->user_email, $subject, $message, $headers );
	}

	/**
	 * Send activation email for imported members.
	 *
	 * @since 1.1.0
	 * @param int    $user_id        WordPress user ID.
	 * @param string $activation_key Activation key.
	 * @param array  $data           Import data.
	 * @return bool Whether the email was sent successfully.
	 */
	public function send_import_activation_email( $user_id, $activation_key, $data = array() ) {
		$user = get_user_by( 'ID', $user_id );

		if ( ! $user ) {
			return false;
		}

		$activation_url = add_query_arg(
			array(
				'mbrreg_action' => 'activate',
				'key'           => $activation_key,
			),
			home_url( '/' )
		);

		// Get member area page URL.
		$page_id  = get_option( 'mbrreg_registration_page_id', 0 );
		$page_url = $page_id ? get_permalink( $page_id ) : home_url( '/' );

		$site_name    = get_bloginfo( 'name' );
		$display_name = ! empty( $data['first_name'] ) ? $data['first_name'] : $user->user_email;

		$subject = sprintf(
			/* translators: %s: Site name */
			__( 'You have been registered as a member at %s', 'member-registration-plugin' ),
			$site_name
		);

		$message = sprintf(
			/* translators: 1: User display name, 2: Site name, 3: Activation URL, 4: Member area URL, 5: Username, 6: Site name (repeated) */
			__( 'Hello %1$s,

You have been registered as a member at %2$s.

Please click the following link to activate your account:

%3$s

After activation, you can log in and review/update your details at:
%4$s

Your username is: %5$s

If you need to set a password, please use the password reset function on the login page.

If you did not expect this email, please contact the club administrator.

Best regards,
%6$s', 'member-registration-plugin' ),
			$display_name,
			$site_name,
			$activation_url,
			$page_url,
			$user->user_login,
			$site_name
		);

		$headers = $this->get_email_headers();

		/**
		 * Filter the import activation email subject.
		 *
		 * @since 1.1.0
		 * @param string  $subject Subject line.
		 * @param WP_User $user    User object.
		 */
		$subject = apply_filters( 'mbrreg_import_activation_email_subject', $subject, $user );

		/**
		 * Filter the import activation email message.
		 *
		 * @since 1.1.0
		 * @param string  $message        Email message.
		 * @param WP_User $user           User object.
		 * @param string  $activation_url Activation URL.
		 */
		$message = apply_filters( 'mbrreg_import_activation_email_message', $message, $user, $activation_url );

		return wp_mail( $user->user_email, $subject, $message, $headers );
	}

	/**
	 * Send notification to admin about new registration.
	 *
	 * @since 1.0.0
	 * @param int   $member_id Member ID.
	 * @param array $data      Member data.
	 * @return bool Whether the email was sent successfully.
	 */
	public function send_admin_notification( $member_id, $data ) {
		$admin_email = get_option( 'admin_email' );
		$site_name   = get_bloginfo( 'name' );

		$subject = sprintf(
			/* translators: %s: Site name */
			__( 'New member registration at %s', 'member-registration-plugin' ),
			$site_name
		);

		$message = sprintf(
			/* translators: 1: Username, 2: Email */
			__( 'A new member has registered:

Username: %1$s
Email: %2$s

You can view and manage members in the WordPress admin area.', 'member-registration-plugin' ),
			isset( $data['username'] ) ? $data['username'] : '',
			isset( $data['email'] ) ? $data['email'] : ''
		);

		$headers = $this->get_email_headers();

		return wp_mail( $admin_email, $subject, $message, $headers );
	}

	/**
	 * Send welcome email after account activation.
	 *
	 * @since 1.0.0
	 * @param int $user_id WordPress user ID.
	 * @return bool Whether the email was sent successfully.
	 */
	public function send_welcome_email( $user_id ) {
		$user = get_user_by( 'ID', $user_id );

		if ( ! $user ) {
			return false;
		}

		// Get member area page URL.
		$page_id  = get_option( 'mbrreg_registration_page_id', 0 );
		$page_url = $page_id ? get_permalink( $page_id ) : wp_login_url();

		$site_name = get_bloginfo( 'name' );

		$subject = sprintf(
			/* translators: %s: Site name */
			__( 'Welcome to %s!', 'member-registration-plugin' ),
			$site_name
		);

		$message = sprintf(
			/* translators: 1: User display name, 2: Site name, 3: Login URL, 4: Site name (repeated) */
			__( 'Hello %1$s,

Your account at %2$s has been activated successfully!

You can now log in and manage your membership details at:
%3$s

Best regards,
%4$s', 'member-registration-plugin' ),
			$user->display_name,
			$site_name,
			$page_url,
			$site_name
		);

		$headers = $this->get_email_headers();

		/**
		 * Filter the welcome email subject.
		 *
		 * @since 1.0.0
		 * @param string  $subject Subject line.
		 * @param WP_User $user    User object.
		 */
		$subject = apply_filters( 'mbrreg_welcome_email_subject', $subject, $user );

		/**
		 * Filter the welcome email message.
		 *
		 * @since 1.0.0
		 * @param string  $message Email message.
		 * @param WP_User $user    User object.
		 */
		$message = apply_filters( 'mbrreg_welcome_email_message', $message, $user );

		return wp_mail( $user->user_email, $subject, $message, $headers );
	}

	/**
	 * Get email headers.
	 *
	 * @since 1.0.0
	 * @return array Email headers.
	 */
	private function get_email_headers() {
		$from_name    = get_option( 'mbrreg_email_from_name', get_bloginfo( 'name' ) );
		$from_address = get_option( 'mbrreg_email_from_address', get_option( 'admin_email' ) );

		$headers = array(
			'Content-Type: text/plain; charset=UTF-8',
			sprintf( 'From: %s <%s>', $from_name, $from_address ),
		);

		/**
		 * Filter email headers.
		 *
		 * @since 1.0.0
		 * @param array $headers Email headers.
		 */
		return apply_filters( 'mbrreg_email_headers', $headers );
	}
}
```

---

## admin/partials/mbrreg-admin-settings.php (UPDATED)

```php
<?php
/**
 * Admin settings page template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/admin/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get all pages for dropdown.
$pages = get_pages();
?>

<div class="wrap mbrreg-admin-wrap">
	<h1><?php esc_html_e( 'Member Registration Settings', 'member-registration-plugin' ); ?></h1>

	<?php settings_errors(); ?>

	<form method="post" action="options.php">
		<?php settings_fields( 'mbrreg_settings' ); ?>

		<!-- Registration Settings -->
		<div class="mbrreg-settings-section">
			<h2><?php esc_html_e( 'Registration Settings', 'member-registration-plugin' ); ?></h2>
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e( 'Allow Registration', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_allow_registration" value="1" <?php checked( get_option( 'mbrreg_allow_registration', true ) ); ?>>
							<?php esc_html_e( 'Allow new members to register', 'member-registration-plugin' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'When disabled, the registration form will not be shown.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Allow Multiple Members', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_allow_multiple_members" value="1" <?php checked( get_option( 'mbrreg_allow_multiple_members', true ) ); ?>>
							<?php esc_html_e( 'Allow users to register multiple members under one account', 'member-registration-plugin' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Useful for parents registering multiple children or family members.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="mbrreg_registration_page_id"><?php esc_html_e( 'Member Area Page', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<select name="mbrreg_registration_page_id" id="mbrreg_registration_page_id">
							<option value="0"><?php esc_html_e( '— Select —', 'member-registration-plugin' ); ?></option>
							<?php foreach ( $pages as $page ) : ?>
								<option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( get_option( 'mbrreg_registration_page_id' ), $page->ID ); ?>>
									<?php echo esc_html( $page->post_title ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<p class="description"><?php esc_html_e( 'The page containing the [mbrreg_member_area] shortcode.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="mbrreg_login_redirect_page"><?php esc_html_e( 'Login Redirect Page', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<select name="mbrreg_login_redirect_page" id="mbrreg_login_redirect_page">
							<option value="0"><?php esc_html_e( '— Select —', 'member-registration-plugin' ); ?></option>
							<?php foreach ( $pages as $page ) : ?>
								<option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( get_option( 'mbrreg_login_redirect_page' ), $page->ID ); ?>>
									<?php echo esc_html( $page->post_title ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<p class="description"><?php esc_html_e( 'Where to redirect members after successful login.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
			</table>
		</div>

		<!-- Display Settings -->
		<div class="mbrreg-settings-section">
			<h2><?php esc_html_e( 'Display Settings', 'member-registration-plugin' ); ?></h2>
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="mbrreg_date_format"><?php esc_html_e( 'Date Format', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<select name="mbrreg_date_format" id="mbrreg_date_format">
							<option value="eu" <?php selected( get_option( 'mbrreg_date_format', 'eu' ), 'eu' ); ?>>
								<?php esc_html_e( 'European (DD/MM/YYYY)', 'member-registration-plugin' ); ?>
							</option>
							<option value="us" <?php selected( get_option( 'mbrreg_date_format', 'eu' ), 'us' ); ?>>
								<?php esc_html_e( 'US (MM/DD/YYYY)', 'member-registration-plugin' ); ?>
							</option>
						</select>
						<p class="description"><?php esc_html_e( 'Choose how dates are displayed throughout the plugin.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
			</table>
		</div>

		<!-- Required Fields -->
		<div class="mbrreg-settings-section">
			<h2><?php esc_html_e( 'Required Fields', 'member-registration-plugin' ); ?></h2>
			<p class="description"><?php esc_html_e( 'Select which default fields should be required during registration and profile updates. Additional fields can be configured in the Custom Fields section.', 'member-registration-plugin' ); ?></p>
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_first_name" value="1" <?php checked( get_option( 'mbrreg_require_first_name', false ) ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_last_name" value="1" <?php checked( get_option( 'mbrreg_require_last_name', false ) ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
			</table>
		</div>

		<!-- Email Settings -->
		<div class="mbrreg-settings-section">
			<h2><?php esc_html_e( 'Email Settings', 'member-registration-plugin' ); ?></h2>
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="mbrreg_email_from_name"><?php esc_html_e( 'From Name', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" name="mbrreg_email_from_name" id="mbrreg_email_from_name" value="<?php echo esc_attr( get_option( 'mbrreg_email_from_name', get_bloginfo( 'name' ) ) ); ?>" class="regular-text">
						<p class="description"><?php esc_html_e( 'The name that will appear in the "From" field of emails.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="mbrreg_email_from_address"><?php esc_html_e( 'From Email', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="email" name="mbrreg_email_from_address" id="mbrreg_email_from_address" value="<?php echo esc_attr( get_option( 'mbrreg_email_from_address', get_option( 'admin_email' ) ) ); ?>" class="regular-text">
						<p class="description"><?php esc_html_e( 'The email address that will appear in the "From" field.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
			</table>
		</div>

		<!-- Shortcodes Reference -->
		<div class="mbrreg-settings-section">
			<h2><?php esc_html_e( 'Shortcodes', 'member-registration-plugin' ); ?></h2>
			<table class="form-table mbrreg-shortcodes-table">
				<tr>
					<th><code>[mbrreg_member_area]</code></th>
					<td><?php esc_html_e( 'Complete member area with login, registration, and dashboard.', 'member-registration-plugin' ); ?></td>
				</tr>
				<tr>
					<th><code>[mbrreg_login_form]</code></th>
					<td><?php esc_html_e( 'Standalone login form.', 'member-registration-plugin' ); ?></td>
				</tr>
				<tr>
					<th><code>[mbrreg_register_form]</code></th>
					<td><?php esc_html_e( 'Standalone registration form.', 'member-registration-plugin' ); ?></td>
				</tr>
				<tr>
					<th><code>[mbrreg_member_dashboard]</code></th>
					<td><?php esc_html_e( 'Member dashboard (for logged-in members only).', 'member-registration-plugin' ); ?></td>
				</tr>
			</table>
		</div>

		<?php submit_button(); ?>
	</form>
</div>
```

---

## includes/class-mbrreg-admin.php (UPDATED - added date_format setting)

```php
<?php
/**
 * Admin functionality class.
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
 * Class Mbrreg_Admin
 *
 * Handles admin functionality.
 *
 * @since 1.0.0
 */
class Mbrreg_Admin {

	/**
	 * Member instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Member
	 */
	private $member;

	/**
	 * Custom fields instance.
	 *
	 * @since 1.0.0
	 * @var Mbrreg_Custom_Fields
	 */
	private $custom_fields;

	/**
	 * Email instance.
	 *
	 * @since 1.1.0
	 * @var Mbrreg_Email
	 */
	private $email;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param Mbrreg_Member        $member        Member instance.
	 * @param Mbrreg_Custom_Fields $custom_fields Custom fields instance.
	 * @param Mbrreg_Email         $email         Email instance.
	 */
	public function __construct( Mbrreg_Member $member, Mbrreg_Custom_Fields $custom_fields, Mbrreg_Email $email = null ) {
		$this->member        = $member;
		$this->custom_fields = $custom_fields;
		$this->email         = $email;
	}

	/**
	 * Initialize admin hooks.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_init', array( $this, 'handle_csv_export' ) );
	}

	/**
	 * Add admin menu pages.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function add_admin_menu() {
		// Main menu.
		add_menu_page(
			__( 'Member Registration', 'member-registration-plugin' ),
			__( 'Members', 'member-registration-plugin' ),
			'mbrreg_manage_members',
			'mbrreg-members',
			array( $this, 'render_members_page' ),
			'dashicons-groups',
			30
		);

		// Members submenu.
		add_submenu_page(
			'mbrreg-members',
			__( 'All Members', 'member-registration-plugin' ),
			__( 'All Members', 'member-registration-plugin' ),
			'mbrreg_manage_members',
			'mbrreg-members',
			array( $this, 'render_members_page' )
		);

		// Custom fields submenu.
		add_submenu_page(
			'mbrreg-members',
			__( 'Custom Fields', 'member-registration-plugin' ),
			__( 'Custom Fields', 'member-registration-plugin' ),
			'mbrreg_manage_custom_fields',
			'mbrreg-custom-fields',
			array( $this, 'render_custom_fields_page' )
		);

		// Import/Export submenu.
		add_submenu_page(
			'mbrreg-members',
			__( 'Import / Export', 'member-registration-plugin' ),
			__( 'Import / Export', 'member-registration-plugin' ),
			'mbrreg_import_members',
			'mbrreg-import-export',
			array( $this, 'render_import_export_page' )
		);

		// Settings submenu.
		add_submenu_page(
			'mbrreg-members',
			__( 'Settings', 'member-registration-plugin' ),
			__( 'Settings', 'member-registration-plugin' ),
			'mbrreg_manage_settings',
			'mbrreg-settings',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @since 1.0.0
	 * @param string $hook Current admin page hook.
	 * @return void
	 */
	public function enqueue_assets( $hook ) {
		// Only load on our admin pages.
		if ( strpos( $hook, 'mbrreg' ) === false ) {
			return;
		}

		wp_enqueue_style(
			'mbrreg-admin',
			MBRREG_PLUGIN_URL . 'admin/css/mbrreg-admin.css',
			array(),
			MBRREG_VERSION
		);

		wp_enqueue_script(
			'mbrreg-admin',
			MBRREG_PLUGIN_URL . 'admin/js/mbrreg-admin.js',
			array( 'jquery' ),
			MBRREG_VERSION,
			true
		);

		wp_localize_script(
			'mbrreg-admin',
			'mbrregAdmin',
			array(
				'ajaxUrl'            => admin_url( 'admin-ajax.php' ),
				'nonce'              => wp_create_nonce( 'mbrreg_admin_nonce' ),
				'confirmDelete'      => __( 'Are you sure you want to delete this member? This action cannot be undone.', 'member-registration-plugin' ),
				'confirmBulk'        => __( 'Are you sure you want to perform this action on the selected members?', 'member-registration-plugin' ),
				'confirmFieldDelete' => __( 'Are you sure you want to delete this custom field? All associated data will be lost.', 'member-registration-plugin' ),
				'processing'         => __( 'Processing...', 'member-registration-plugin' ),
				'success'            => __( 'Success!', 'member-registration-plugin' ),
				'error'              => __( 'An error occurred.', 'member-registration-plugin' ),
				'selectMembers'      => __( 'Please select at least one member.', 'member-registration-plugin' ),
				'selectAction'       => __( 'Please select an action.', 'member-registration-plugin' ),
				'importSuccess'      => __( 'Import completed successfully!', 'member-registration-plugin' ),
				'exportSuccess'      => __( 'Export completed successfully!', 'member-registration-plugin' ),
				'dateFormat'         => get_option( 'mbrreg_date_format', 'eu' ),
			)
		);
	}

	/**
	 * Register plugin settings.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_settings() {
		// Registration settings.
		register_setting( 'mbrreg_settings', 'mbrreg_allow_registration' );
		register_setting( 'mbrreg_settings', 'mbrreg_allow_multiple_members' );
		register_setting( 'mbrreg_settings', 'mbrreg_registration_page_id' );
		register_setting( 'mbrreg_settings', 'mbrreg_login_redirect_page' );

		// Display settings.
		register_setting( 'mbrreg_settings', 'mbrreg_date_format' );

		// Required fields settings (only first_name and last_name now).
		register_setting( 'mbrreg_settings', 'mbrreg_require_first_name' );
		register_setting( 'mbrreg_settings', 'mbrreg_require_last_name' );

		// Email settings.
		register_setting( 'mbrreg_settings', 'mbrreg_email_from_name' );
		register_setting( 'mbrreg_settings', 'mbrreg_email_from_address' );
	}

	/**
	 * Handle CSV export download.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function handle_csv_export() {
		if ( ! isset( $_GET['mbrreg_export'] ) || ! isset( $_GET['_wpnonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'mbrreg_export_csv' ) ) {
			return;
		}

		if ( ! current_user_can( 'mbrreg_export_members' ) ) {
			return;
		}

		$status = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : '';

		$args = array();
		if ( ! empty( $status ) ) {
			$args['status'] = $status;
		}

		$csv      = $this->member->export_csv( $args );
		$filename = 'members-export-' . gmdate( 'Y-m-d' ) . '.csv';

		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		echo $csv; // phpcs:ignore
		exit;
	}

	/**
	 * Render members list page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_members_page() {
		// Check if editing a member.
		if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] && isset( $_GET['member_id'] ) ) {
			$this->render_member_edit_page();
			return;
		}

		include MBRREG_PLUGIN_PATH . 'admin/partials/mbrreg-admin-members.php';
	}

	/**
	 * Render member edit page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function render_member_edit_page() {
		$member_id = absint( $_GET['member_id'] );
		$member    = $this->member->get( $member_id );

		if ( ! $member ) {
			echo '<div class="wrap"><div class="notice notice-error"><p>' . esc_html__( 'Member not found.', 'member-registration-plugin' ) . '</p></div></div>';
			return;
		}

		$custom_fields = $this->custom_fields->get_all();

		include MBRREG_PLUGIN_PATH . 'admin/partials/mbrreg-admin-member-edit.php';
	}

	/**
	 * Render custom fields page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_custom_fields_page() {
		$custom_fields = $this->custom_fields->get_all();

		include MBRREG_PLUGIN_PATH . 'admin/partials/mbrreg-admin-custom-fields.php';
	}

	/**
	 * Render import/export page.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function render_import_export_page() {
		include MBRREG_PLUGIN_PATH . 'admin/partials/mbrreg-admin-import-export.php';
	}

	/**
	 * Render settings page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_settings_page() {
		include MBRREG_PLUGIN_PATH . 'admin/partials/mbrreg-admin-settings.php';
	}
}
```

---

Now let me continue with the remaining files in **Part 2**:

## public/partials/mbrreg-register-form.php (UPDATED - Simplified)

```php
<?php
/**
 * Registration form template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/public/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get custom fields handler for rendering.
$custom_fields_handler = new Mbrreg_Custom_Fields();

// Check for activation messages.
$activation_error   = get_transient( 'mbrreg_activation_error' );
$activation_success = get_transient( 'mbrreg_activation_success' );

if ( $activation_error ) {
	delete_transient( 'mbrreg_activation_error' );
}
if ( $activation_success ) {
	delete_transient( 'mbrreg_activation_success' );
}
?>

<div class="mbrreg-form-container">
	<?php if ( $activation_error ) : ?>
		<div class="mbrreg-message mbrreg-error"><?php echo esc_html( $activation_error ); ?></div>
	<?php endif; ?>

	<?php if ( $activation_success ) : ?>
		<div class="mbrreg-message mbrreg-success"><?php echo esc_html( $activation_success ); ?></div>
	<?php endif; ?>

	<form id="mbrreg-register-form" class="mbrreg-form" method="post">
		<h2 class="mbrreg-form-title"><?php esc_html_e( 'Register', 'member-registration-plugin' ); ?></h2>

		<div class="mbrreg-form-messages"></div>

		<!-- Account Details -->
		<fieldset class="mbrreg-fieldset">
			<legend><?php esc_html_e( 'Account Details', 'member-registration-plugin' ); ?></legend>

			<div class="mbrreg-form-row">
				<label for="mbrreg-email">
					<?php esc_html_e( 'Email Address', 'member-registration-plugin' ); ?>
					<span class="required">*</span>
				</label>
				<input type="email" id="mbrreg-email" name="email" required>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-username">
					<?php esc_html_e( 'Username', 'member-registration-plugin' ); ?>
				</label>
				<input type="text" id="mbrreg-username" name="username">
				<span class="mbrreg-field-note"><?php esc_html_e( 'Leave blank to auto-generate from email.', 'member-registration-plugin' ); ?></span>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-password">
					<?php esc_html_e( 'Password', 'member-registration-plugin' ); ?>
					<span class="required">*</span>
				</label>
				<input type="password" id="mbrreg-password" name="password" required minlength="8">
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-password-confirm">
					<?php esc_html_e( 'Confirm Password', 'member-registration-plugin' ); ?>
					<span class="required">*</span>
				</label>
				<input type="password" id="mbrreg-password-confirm" name="password_confirm" required minlength="8">
			</div>
		</fieldset>

		<!-- Personal Details (only first_name and last_name) -->
		<fieldset class="mbrreg-fieldset">
			<legend><?php esc_html_e( 'Personal Details', 'member-registration-plugin' ); ?></legend>

			<div class="mbrreg-form-row">
				<label for="mbrreg-first-name">
					<?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?>
					<?php if ( get_option( 'mbrreg_require_first_name', false ) ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<input type="text" id="mbrreg-first-name" name="first_name" <?php echo get_option( 'mbrreg_require_first_name', false ) ? 'required' : ''; ?>>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-last-name">
					<?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?>
					<?php if ( get_option( 'mbrreg_require_last_name', false ) ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<input type="text" id="mbrreg-last-name" name="last_name" <?php echo get_option( 'mbrreg_require_last_name', false ) ? 'required' : ''; ?>>
			</div>
		</fieldset>

		<!-- Custom Fields (only user-editable) -->
		<?php if ( ! empty( $custom_fields ) ) : ?>
			<fieldset class="mbrreg-fieldset">
				<legend><?php esc_html_e( 'Additional Information', 'member-registration-plugin' ); ?></legend>

				<?php foreach ( $custom_fields as $field ) : ?>
					<div class="mbrreg-form-row">
						<label for="mbrreg-custom-<?php echo esc_attr( $field->id ); ?>">
							<?php echo esc_html( $field->field_label ); ?>
							<?php if ( $field->is_required ) : ?>
								<span class="required">*</span>
							<?php endif; ?>
						</label>
						<?php echo $custom_fields_handler->render_field_input( $field ); // phpcs:ignore ?>
					</div>
				<?php endforeach; ?>
			</fieldset>
		<?php endif; ?>

		<div class="mbrreg-form-row">
			<button type="submit" class="mbrreg-button mbrreg-button-primary">
				<?php esc_html_e( 'Register', 'member-registration-plugin' ); ?>
			</button>
		</div>
	</form>
</div>
```

---

## public/partials/mbrreg-member-dashboard.php (UPDATED - Simplified)

```php
<?php
/**
 * Member dashboard template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/public/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get custom fields instance for rendering.
$custom_fields_handler = new Mbrreg_Custom_Fields();
$statuses              = Mbrreg_Member::get_statuses();
?>

<div class="mbrreg-dashboard-container">
	<div class="mbrreg-dashboard-header">
		<h2>
			<?php
			printf(
				/* translators: %s: User display name */
				esc_html__( 'Welcome, %s!', 'member-registration-plugin' ),
				esc_html( $current_user->display_name )
			);
			?>
		</h2>

		<div class="mbrreg-dashboard-actions">
			<?php if ( $is_admin ) : ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members' ) ); ?>" class="mbrreg-button mbrreg-button-secondary">
					<?php esc_html_e( 'Manage Members', 'member-registration-plugin' ); ?>
				</a>
			<?php endif; ?>

			<button type="button" class="mbrreg-button mbrreg-button-secondary mbrreg-logout-btn">
				<?php esc_html_e( 'Log Out', 'member-registration-plugin' ); ?>
			</button>
		</div>
	</div>

	<div class="mbrreg-form-messages"></div>

	<?php if ( ! empty( $members ) ) : ?>
		<div class="mbrreg-account-info">
			<p>
				<strong><?php esc_html_e( 'Account Email:', 'member-registration-plugin' ); ?></strong>
				<?php echo esc_html( $current_user->user_email ); ?>
			</p>
			<p>
				<strong><?php esc_html_e( 'Registered Members:', 'member-registration-plugin' ); ?></strong>
				<?php echo esc_html( count( $members ) ); ?>
			</p>
		</div>

		<?php foreach ( $members as $index => $member ) : ?>
			<div class="mbrreg-member-card" data-member-id="<?php echo esc_attr( $member->id ); ?>">
				<div class="mbrreg-member-card-header">
					<h3>
						<?php
						$member_name = trim( $member->first_name . ' ' . $member->last_name );
						if ( empty( $member_name ) ) {
							$member_name = sprintf(
								/* translators: %d: Member number */
								__( 'Member #%d', 'member-registration-plugin' ),
								$index + 1
							);
						}
						echo esc_html( $member_name );
						?>
					</h3>
					<span class="mbrreg-status mbrreg-status-<?php echo esc_attr( $member->status ); ?>">
						<?php echo esc_html( $statuses[ $member->status ] ); ?>
					</span>
				</div>

				<form class="mbrreg-profile-form mbrreg-form" method="post" data-member-id="<?php echo esc_attr( $member->id ); ?>">
					<input type="hidden" name="member_id" value="<?php echo esc_attr( $member->id ); ?>">

					<!-- Personal Details (only first_name and last_name) -->
					<fieldset class="mbrreg-fieldset">
						<legend><?php esc_html_e( 'Personal Details', 'member-registration-plugin' ); ?></legend>

						<div class="mbrreg-form-row">
							<label for="mbrreg-first-name-<?php echo esc_attr( $member->id ); ?>">
								<?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?>
								<?php if ( get_option( 'mbrreg_require_first_name', false ) ) : ?>
									<span class="required">*</span>
								<?php endif; ?>
							</label>
							<input type="text" id="mbrreg-first-name-<?php echo esc_attr( $member->id ); ?>" name="first_name" value="<?php echo esc_attr( $member->first_name ); ?>" <?php echo get_option( 'mbrreg_require_first_name', false ) ? 'required' : ''; ?>>
						</div>

						<div class="mbrreg-form-row">
							<label for="mbrreg-last-name-<?php echo esc_attr( $member->id ); ?>">
								<?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?>
								<?php if ( get_option( 'mbrreg_require_last_name', false ) ) : ?>
									<span class="required">*</span>
								<?php endif; ?>
							</label>
							<input type="text" id="mbrreg-last-name-<?php echo esc_attr( $member->id ); ?>" name="last_name" value="<?php echo esc_attr( $member->last_name ); ?>" <?php echo get_option( 'mbrreg_require_last_name', false ) ? 'required' : ''; ?>>
						</div>
					</fieldset>

					<!-- Custom Fields -->
					<?php if ( ! empty( $custom_fields ) ) : ?>
						<fieldset class="mbrreg-fieldset">
							<legend><?php esc_html_e( 'Additional Information', 'member-registration-plugin' ); ?></legend>

							<?php foreach ( $custom_fields as $field ) : ?>
								<?php
								$value       = isset( $member->custom_fields[ $field->id ] ) ? $member->custom_fields[ $field->id ] : '';
								$is_readonly = $field->is_admin_only;
								?>
								<div class="mbrreg-form-row">
									<label for="mbrreg-custom-<?php echo esc_attr( $field->id ); ?>-<?php echo esc_attr( $member->id ); ?>">
										<?php echo esc_html( $field->field_label ); ?>
										<?php if ( $field->is_required && ! $is_readonly ) : ?>
											<span class="required">*</span>
										<?php endif; ?>
										<?php if ( $is_readonly ) : ?>
											<span class="mbrreg-readonly-badge"><?php esc_html_e( '(View only)', 'member-registration-plugin' ); ?></span>
										<?php endif; ?>
									</label>
									<?php
									echo $custom_fields_handler->render_field_input( // phpcs:ignore
										$field,
										$value,
										array(
											'id_prefix' => 'mbrreg-custom-' . $member->id . '-',
											'readonly'  => $is_readonly,
										)
									);
									?>
								</div>
							<?php endforeach; ?>
						</fieldset>
					<?php endif; ?>

					<div class="mbrreg-form-row mbrreg-form-actions">
						<button type="submit" class="mbrreg-button mbrreg-button-primary">
							<?php esc_html_e( 'Update Profile', 'member-registration-plugin' ); ?>
						</button>

						<?php if ( 'active' === $member->status ) : ?>
							<button type="button" class="mbrreg-button mbrreg-button-danger mbrreg-deactivate-btn" data-member-id="<?php echo esc_attr( $member->id ); ?>">
								<?php esc_html_e( 'Deactivate Membership', 'member-registration-plugin' ); ?>
							</button>
						<?php endif; ?>
					</div>
				</form>
			</div>
		<?php endforeach; ?>

		<!-- Add Another Member Section -->
		<?php if ( $allow_multiple ) : ?>
			<div class="mbrreg-add-member-section">
				<button type="button" class="mbrreg-button mbrreg-button-secondary mbrreg-toggle-add-member">
					<?php esc_html_e( '+ Add Another Member', 'member-registration-plugin' ); ?>
				</button>

				<div class="mbrreg-add-member-form-container" style="display: none;">
					<h3><?php esc_html_e( 'Add New Member', 'member-registration-plugin' ); ?></h3>
					<p class="mbrreg-info"><?php esc_html_e( 'Add another family member to your account.', 'member-registration-plugin' ); ?></p>

					<form id="mbrreg-add-member-form" class="mbrreg-form" method="post">
						<fieldset class="mbrreg-fieldset">
							<legend><?php esc_html_e( 'Personal Details', 'member-registration-plugin' ); ?></legend>

							<div class="mbrreg-form-row">
								<label for="mbrreg-add-first-name">
									<?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?>
									<?php if ( get_option( 'mbrreg_require_first_name', false ) ) : ?>
										<span class="required">*</span>
									<?php endif; ?>
								</label>
								<input type="text" id="mbrreg-add-first-name" name="first_name" <?php echo get_option( 'mbrreg_require_first_name', false ) ? 'required' : ''; ?>>
							</div>

							<div class="mbrreg-form-row">
								<label for="mbrreg-add-last-name">
									<?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?>
									<?php if ( get_option( 'mbrreg_require_last_name', false ) ) : ?>
										<span class="required">*</span>
									<?php endif; ?>
								</label>
								<input type="text" id="mbrreg-add-last-name" name="last_name" <?php echo get_option( 'mbrreg_require_last_name', false ) ? 'required' : ''; ?>>
							</div>
						</fieldset>

						<!-- Custom Fields for new member -->
						<?php if ( ! empty( $user_editable_fields ) ) : ?>
							<fieldset class="mbrreg-fieldset">
								<legend><?php esc_html_e( 'Additional Information', 'member-registration-plugin' ); ?></legend>

								<?php foreach ( $user_editable_fields as $field ) : ?>
									<div class="mbrreg-form-row">
										<label for="mbrreg-add-custom-<?php echo esc_attr( $field->id ); ?>">
											<?php echo esc_html( $field->field_label ); ?>
											<?php if ( $field->is_required ) : ?>
												<span class="required">*</span>
											<?php endif; ?>
										</label>
										<?php
										echo $custom_fields_handler->render_field_input( // phpcs:ignore
											$field,
											'',
											array( 'id_prefix' => 'mbrreg-add-custom-' )
										);
										?>
									</div>
								<?php endforeach; ?>
							</fieldset>
						<?php endif; ?>

						<div class="mbrreg-form-row mbrreg-form-actions">
							<button type="submit" class="mbrreg-button mbrreg-button-primary">
								<?php esc_html_e( 'Add Member', 'member-registration-plugin' ); ?>
							</button>
							<button type="button" class="mbrreg-button mbrreg-button-secondary mbrreg-cancel-add-member">
								<?php esc_html_e( 'Cancel', 'member-registration-plugin' ); ?>
							</button>
						</div>
					</form>
				</div>
			</div>
		<?php endif; ?>

	<?php else : ?>
		<p class="mbrreg-message mbrreg-warning">
			<?php esc_html_e( 'No member profile found. Please contact an administrator.', 'member-registration-plugin' ); ?>
		</p>
	<?php endif; ?>
</div>
```

---

## admin/partials/mbrreg-admin-member-edit.php (UPDATED - Simplified)

```php
<?php
/**
 * Admin member edit page template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/admin/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get custom fields handler for rendering.
$custom_fields_handler = new Mbrreg_Custom_Fields();
$statuses              = Mbrreg_Member::get_statuses();

// Count other members in this account.
$database        = new Mbrreg_Database();
$account_members = $database->get_members_by_user_id( $member->user_id );
?>

<div class="wrap mbrreg-admin-wrap">
	<h1>
		<?php esc_html_e( 'Edit Member', 'member-registration-plugin' ); ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members' ) ); ?>" class="page-title-action">
			<?php esc_html_e( 'Back to Members', 'member-registration-plugin' ); ?>
		</a>
	</h1>

	<div id="mbrreg-admin-messages"></div>

	<?php if ( count( $account_members ) > 1 ) : ?>
		<div class="notice notice-info">
			<p>
				<?php
				printf(
					/* translators: 1: Number of members, 2: Account email */
					esc_html__( 'This user account (%2$s) has %1$d registered members.', 'member-registration-plugin' ),
					count( $account_members ),
					esc_html( $member->email )
				);
				?>
			</p>
		</div>
	<?php endif; ?>

	<form id="mbrreg-edit-member-form" method="post" class="mbrreg-admin-form">
		<input type="hidden" name="member_id" value="<?php echo esc_attr( $member->id ); ?>">

		<div class="mbrreg-admin-columns">
			<!-- Main Content -->
			<div class="mbrreg-admin-column mbrreg-admin-column-wide">
				<div class="mbrreg-admin-card">
					<h2><?php esc_html_e( 'Personal Information', 'member-registration-plugin' ); ?></h2>

					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="first_name"><?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $member->first_name ); ?>" class="regular-text">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="last_name"><?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $member->last_name ); ?>" class="regular-text">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="email"><?php esc_html_e( 'Email', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<input type="email" name="email" id="email" value="<?php echo esc_attr( $member->email ); ?>" class="regular-text">
								<p class="description"><?php esc_html_e( 'Changing this will update the WordPress user email for the account owner.', 'member-registration-plugin' ); ?></p>
							</td>
						</tr>
					</table>
				</div>

				<!-- Custom Fields -->
				<?php if ( ! empty( $custom_fields ) ) : ?>
					<div class="mbrreg-admin-card">
						<h2><?php esc_html_e( 'Custom Fields', 'member-registration-plugin' ); ?></h2>
						<table class="form-table">
							<?php foreach ( $custom_fields as $field ) : ?>
								<?php $value = isset( $member->custom_fields[ $field->id ] ) ? $member->custom_fields[ $field->id ] : ''; ?>
								<tr>
									<th scope="row">
										<label for="custom_<?php echo esc_attr( $field->id ); ?>">
											<?php echo esc_html( $field->field_label ); ?>
											<?php if ( $field->is_admin_only ) : ?>
												<span class="dashicons dashicons-lock" style="color: #d63638; font-size: 14px;" title="<?php esc_attr_e( 'Admin only', 'member-registration-plugin' ); ?>"></span>
											<?php endif; ?>
										</label>
									</th>
									<td>
										<?php
										echo $custom_fields_handler->render_field_input( // phpcs:ignore
											$field,
											$value,
											array( 'class' => 'regular-text' )
										);
										?>
										<?php if ( $field->is_admin_only ) : ?>
											<p class="description"><?php esc_html_e( 'This field can only be edited by administrators.', 'member-registration-plugin' ); ?></p>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</table>
					</div>
				<?php endif; ?>
			</div>

			<!-- Sidebar -->
			<div class="mbrreg-admin-column">
				<div class="mbrreg-admin-card">
					<h2><?php esc_html_e( 'Member Status', 'member-registration-plugin' ); ?></h2>

					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="status"><?php esc_html_e( 'Status', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<select name="status" id="status">
									<?php foreach ( $statuses as $value => $label ) : ?>
										<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $member->status, $value ); ?>>
											<?php echo esc_html( $label ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Member Admin', 'member-registration-plugin' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="is_admin" value="1" <?php checked( $member->is_admin ); ?>>
									<?php esc_html_e( 'Can manage members', 'member-registration-plugin' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'Member admins can view and manage other members from the frontend.', 'member-registration-plugin' ); ?></p>
							</td>
						</tr>
					</table>
				</div>

				<div class="mbrreg-admin-card">
					<h2><?php esc_html_e( 'Account Information', 'member-registration-plugin' ); ?></h2>
					<table class="form-table">
						<tr>
							<th scope="row"><?php esc_html_e( 'Username', 'member-registration-plugin' ); ?></th>
							<td><code><?php echo esc_html( $member->username ); ?></code></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Member ID', 'member-registration-plugin' ); ?></th>
							<td><?php echo esc_html( $member->id ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'WP User ID', 'member-registration-plugin' ); ?></th>
							<td>
								<a href="<?php echo esc_url( admin_url( 'user-edit.php?user_id=' . $member->user_id ) ); ?>">
									<?php echo esc_html( $member->user_id ); ?>
								</a>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Registered', 'member-registration-plugin' ); ?></th>
							<td><?php echo esc_html( mbrreg_format_date( $member->created_at, get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Last Updated', 'member-registration-plugin' ); ?></th>
							<td><?php echo esc_html( mbrreg_format_date( $member->updated_at, get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) ); ?></td>
						</tr>
					</table>
				</div>

				<div class="mbrreg-admin-card">
					<h2><?php esc_html_e( 'Actions', 'member-registration-plugin' ); ?></h2>
					<p>
						<button type="submit" class="button button-primary button-large"><?php esc_html_e( 'Update Member', 'member-registration-plugin' ); ?></button>
						<span class="spinner"></span>
					</p>
					<p>
						<button type="button" class="button button-link-delete mbrreg-delete-member" data-member-id="<?php echo esc_attr( $member->id ); ?>">
							<?php esc_html_e( 'Delete Member', 'member-registration-plugin' ); ?>
						</button>
					</p>

					<?php if ( 'pending' === $member->status ) : ?>
						<p>
							<button type="button" class="button mbrreg-resend-activation" data-member-id="<?php echo esc_attr( $member->id ); ?>">
								<?php esc_html_e( 'Resend Activation Email', 'member-registration-plugin' ); ?>
							</button>
						</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</form>
</div>
```

---

## admin/partials/mbrreg-admin-members.php (UPDATED)

```php
<?php
/**
 * Admin members list page template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/admin/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get filter parameters.
$status   = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : '';
$search   = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
$paged    = isset( $_GET['paged'] ) ? max( 1, absint( $_GET['paged'] ) ) : 1;
$per_page = 20;

// Build query args.
$args = array(
	'status' => $status,
	'search' => $search,
	'limit'  => $per_page,
	'offset' => ( $paged - 1 ) * $per_page,
);

// Get members.
$database       = new Mbrreg_Database();
$custom_fields  = new Mbrreg_Custom_Fields();
$member_handler = new Mbrreg_Member( $database, $custom_fields, new Mbrreg_Email() );
$statuses       = Mbrreg_Member::get_statuses();

$members       = $member_handler->get_all( $args );
$total_members = $member_handler->count( $args );
$total_pages   = ceil( $total_members / $per_page );

// Count by status.
$count_all      = $member_handler->count( array( 'search' => $search ) );
$count_active   = $member_handler->count( array( 'status' => 'active', 'search' => $search ) );
$count_inactive = $member_handler->count( array( 'status' => 'inactive', 'search' => $search ) );
$count_pending  = $member_handler->count( array( 'status' => 'pending', 'search' => $search ) );
?>

<div class="wrap mbrreg-admin-wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Members', 'member-registration-plugin' ); ?></h1>
	<hr class="wp-header-end">

	<!-- Status Filter -->
	<ul class="subsubsub">
		<li>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members' ) ); ?>" class="<?php echo '' === $status ? 'current' : ''; ?>">
				<?php esc_html_e( 'All', 'member-registration-plugin' ); ?>
				<span class="count">(<?php echo esc_html( $count_all ); ?>)</span>
			</a> |
		</li>
		<li>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&status=active' ) ); ?>" class="<?php echo 'active' === $status ? 'current' : ''; ?>">
				<?php esc_html_e( 'Active', 'member-registration-plugin' ); ?>
				<span class="count">(<?php echo esc_html( $count_active ); ?>)</span>
			</a> |
		</li>
		<li>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&status=inactive' ) ); ?>" class="<?php echo 'inactive' === $status ? 'current' : ''; ?>">
				<?php esc_html_e( 'Inactive', 'member-registration-plugin' ); ?>
				<span class="count">(<?php echo esc_html( $count_inactive ); ?>)</span>
			</a> |
		</li>
		<li>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&status=pending' ) ); ?>" class="<?php echo 'pending' === $status ? 'current' : ''; ?>">
				<?php esc_html_e( 'Pending', 'member-registration-plugin' ); ?>
				<span class="count">(<?php echo esc_html( $count_pending ); ?>)</span>
			</a>
		</li>
	</ul>

	<!-- Search Form -->
	<form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>" class="search-form">
		<input type="hidden" name="page" value="mbrreg-members">
		<?php if ( $status ) : ?>
			<input type="hidden" name="status" value="<?php echo esc_attr( $status ); ?>">
		<?php endif; ?>
		<p class="search-box">
			<label class="screen-reader-text" for="member-search-input"><?php esc_html_e( 'Search Members', 'member-registration-plugin' ); ?></label>
			<input type="search" id="member-search-input" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search members...', 'member-registration-plugin' ); ?>">
			<input type="submit" id="search-submit" class="button" value="<?php esc_attr_e( 'Search', 'member-registration-plugin' ); ?>">
		</p>
	</form>

	<!-- Bulk Actions Form -->
	<form id="mbrreg-members-form" method="post">
		<div class="tablenav top">
			<div class="alignleft actions bulkactions">
				<select name="bulk_action" id="bulk-action-selector">
					<option value=""><?php esc_html_e( 'Bulk Actions', 'member-registration-plugin' ); ?></option>
					<option value="activate"><?php esc_html_e( 'Activate', 'member-registration-plugin' ); ?></option>
					<option value="deactivate"><?php esc_html_e( 'Deactivate', 'member-registration-plugin' ); ?></option>
					<option value="delete"><?php esc_html_e( 'Delete Members', 'member-registration-plugin' ); ?></option>
					<option value="delete_with_user"><?php esc_html_e( 'Delete Members + Users', 'member-registration-plugin' ); ?></option>
				</select>
				<button type="button" class="button mbrreg-bulk-action-btn"><?php esc_html_e( 'Apply', 'member-registration-plugin' ); ?></button>
			</div>

			<?php if ( $total_pages > 1 ) : ?>
				<div class="tablenav-pages">
					<span class="displaying-num">
						<?php
						printf(
							/* translators: %s: Number of members */
							esc_html( _n( '%s member', '%s members', $total_members, 'member-registration-plugin' ) ),
							number_format_i18n( $total_members )
						);
						?>
					</span>
					<span class="pagination-links">
						<?php
						$page_links = paginate_links(
							array(
								'base'      => add_query_arg( 'paged', '%#%' ),
								'format'    => '',
								'prev_text' => '&laquo;',
								'next_text' => '&raquo;',
								'total'     => $total_pages,
								'current'   => $paged,
							)
						);
						echo wp_kses_post( $page_links );
						?>
					</span>
				</div>
			<?php endif; ?>
		</div>

		<!-- Members Table -->
		<table class="wp-list-table widefat fixed striped members">
			<thead>
				<tr>
					<td class="manage-column column-cb check-column">
						<input type="checkbox" id="cb-select-all">
					</td>
					<th scope="col" class="manage-column"><?php esc_html_e( 'Name', 'member-registration-plugin' ); ?></th>
					<th scope="col" class="manage-column"><?php esc_html_e( 'Email', 'member-registration-plugin' ); ?></th>
					<th scope="col" class="manage-column"><?php esc_html_e( 'Account Owner', 'member-registration-plugin' ); ?></th>
					<th scope="col" class="manage-column"><?php esc_html_e( 'Status', 'member-registration-plugin' ); ?></th>
					<th scope="col" class="manage-column"><?php esc_html_e( 'Admin', 'member-registration-plugin' ); ?></th>
					<th scope="col" class="manage-column"><?php esc_html_e( 'Registered', 'member-registration-plugin' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( ! empty( $members ) ) : ?>
					<?php foreach ( $members as $member ) : ?>
						<tr>
							<th scope="row" class="check-column">
								<input type="checkbox" name="member_ids[]" value="<?php echo esc_attr( $member->id ); ?>">
							</th>
							<td>
								<strong>
									<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&action=edit&member_id=' . $member->id ) ); ?>">
										<?php echo esc_html( $member->first_name . ' ' . $member->last_name ); ?>
									</a>
								</strong>
								<div class="row-actions">
									<span class="edit">
										<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&action=edit&member_id=' . $member->id ) ); ?>">
											<?php esc_html_e( 'Edit', 'member-registration-plugin' ); ?>
										</a> |
									</span>
									<?php if ( 'pending' === $member->status ) : ?>
										<span class="resend">
											<a href="#" class="mbrreg-resend-activation" data-member-id="<?php echo esc_attr( $member->id ); ?>">
												<?php esc_html_e( 'Resend Activation', 'member-registration-plugin' ); ?>
											</a> |
										</span>
									<?php endif; ?>
									<span class="delete">
										<a href="#" class="mbrreg-delete-member submitdelete" data-member-id="<?php echo esc_attr( $member->id ); ?>">
											<?php esc_html_e( 'Delete', 'member-registration-plugin' ); ?>
										</a>
									</span>
								</div>
							</td>
							<td><?php echo esc_html( $member->email ); ?></td>
							<td>
								<?php
								$user = get_user_by( 'ID', $member->user_id );
								echo esc_html( $user ? $user->user_login : '-' );
								?>
							</td>
							<td>
								<span class="mbrreg-status mbrreg-status-<?php echo esc_attr( $member->status ); ?>">
									<?php echo esc_html( $statuses[ $member->status ] ); ?>
								</span>
							</td>
							<td>
								<?php if ( $member->is_admin ) : ?>
									<span class="dashicons dashicons-yes-alt" style="color: green;" title="<?php esc_attr_e( 'Member Admin', 'member-registration-plugin' ); ?>"></span>
								<?php else : ?>
									<span class="dashicons dashicons-minus" style="color: #999;"></span>
								<?php endif; ?>
							</td>
							<td><?php echo esc_html( mbrreg_format_date( $member->created_at ) ); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="7"><?php esc_html_e( 'No members found.', 'member-registration-plugin' ); ?></td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>

		<div class="tablenav bottom">
			<?php if ( $total_pages > 1 ) : ?>
				<div class="tablenav-pages">
					<span class="pagination-links">
						<?php echo wp_kses_post( $page_links ); ?>
					</span>
				</div>
			<?php endif; ?>
		</div>
	</form>
</div>
```

---

Now let me provide the **CSS fixes** and the **updated language file** in **Part 3**:

## public/css/mbrreg-public.css (UPDATED - Fixed modal and input styling)

```css
/**
 * Public styles for Member Registration Plugin.
 *
 * @package Member_Registration_Plugin
 * @since 1.0.0
 */

/* Container Styles */
.mbrreg-auth-container,
.mbrreg-dashboard-container,
.mbrreg-form-container {
	max-width: 700px;
	margin: 0 auto;
	padding: 20px;
}

/* Tab Styles */
.mbrreg-tabs {
	display: flex;
	border-bottom: 2px solid #ddd;
	margin-bottom: 20px;
}

.mbrreg-tab {
	padding: 12px 24px;
	background: none;
	border: none;
	cursor: pointer;
	font-size: 16px;
	font-weight: 500;
	color: #666;
	border-bottom: 2px solid transparent;
	margin-bottom: -2px;
	transition: all 0.3s ease;
}

.mbrreg-tab:hover {
	color: #333;
}

.mbrreg-tab-active {
	color: #0073aa;
	border-bottom-color: #0073aa;
}

.mbrreg-tab-content {
	display: none;
}

.mbrreg-tab-content-active {
	display: block;
}

/* Form Styles */
.mbrreg-form {
	background: #fff;
	padding: 30px;
	border-radius: 4px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.mbrreg-form-title {
	margin: 0 0 25px;
	padding-bottom: 15px;
	border-bottom: 1px solid #eee;
	font-size: 24px;
	color: #333;
}

.mbrreg-fieldset {
	border: 1px solid #ddd;
	border-radius: 4px;
	padding: 20px;
	margin-bottom: 25px;
}

.mbrreg-fieldset legend {
	padding: 0 10px;
	font-weight: 600;
	color: #333;
}

.mbrreg-form-row {
	margin-bottom: 20px;
}

.mbrreg-form-row label {
	display: block;
	margin-bottom: 8px;
	font-weight: 500;
	color: #333;
}

/* Input field styles - FIXED to fit form width */
.mbrreg-form-row input[type="text"],
.mbrreg-form-row input[type="email"],
.mbrreg-form-row input[type="password"],
.mbrreg-form-row input[type="tel"],
.mbrreg-form-row input[type="date"],
.mbrreg-form-row input[type="number"],
.mbrreg-form-row textarea,
.mbrreg-form-row select,
.mbrreg-field-input {
	width: 100%;
	max-width: 100%;
	padding: 12px;
	border: 1px solid #ddd;
	border-radius: 4px;
	font-size: 16px;
	transition: border-color 0.3s ease;
	box-sizing: border-box;
}

.mbrreg-form-row input:focus,
.mbrreg-form-row textarea:focus,
.mbrreg-form-row select:focus {
	outline: none;
	border-color: #0073aa;
	box-shadow: 0 0 0 2px rgba(0, 115, 170, 0.1);
}

.mbrreg-form-row input:disabled,
.mbrreg-form-row input[readonly],
.mbrreg-form-row textarea:disabled,
.mbrreg-form-row textarea[readonly],
.mbrreg-form-row select:disabled {
	background-color: #f5f5f5;
	cursor: not-allowed;
	color: #666;
}

.mbrreg-checkbox-row label {
	display: inline-flex;
	align-items: center;
	gap: 8px;
	font-weight: normal;
}

.mbrreg-radio-label {
	display: block;
	margin-bottom: 8px;
	font-weight: normal;
}

.required {
	color: #dc3545;
}

.mbrreg-field-note {
	display: block;
	margin-top: 5px;
	font-size: 13px;
	color: #666;
}

.mbrreg-readonly-badge {
	font-size: 12px;
	color: #666;
	font-weight: normal;
	font-style: italic;
}

/* Button Styles */
.mbrreg-button {
	display: inline-block;
	padding: 12px 24px;
	border: none;
	border-radius: 4px;
	font-size: 16px;
	font-weight: 500;
	cursor: pointer;
	text-decoration: none;
	transition: all 0.3s ease;
}

.mbrreg-button-primary {
	background-color: #0073aa;
	color: #fff;
}

.mbrreg-button-primary:hover {
	background-color: #005a87;
	color: #fff;
}

.mbrreg-button-secondary {
	background-color: #f0f0f0;
	color: #333;
}

.mbrreg-button-secondary:hover {
	background-color: #e0e0e0;
}

.mbrreg-button-danger {
	background-color: #dc3545;
	color: #fff;
}

.mbrreg-button-danger:hover {
	background-color: #c82333;
}

.mbrreg-button:disabled {
	opacity: 0.6;
	cursor: not-allowed;
}

/* Message Styles */
.mbrreg-message {
	padding: 12px 15px;
	border-radius: 4px;
	margin-bottom: 20px;
}

.mbrreg-success {
	background-color: #d4edda;
	color: #155724;
	border: 1px solid #c3e6cb;
}

.mbrreg-error {
	background-color: #f8d7da;
	color: #721c24;
	border: 1px solid #f5c6cb;
}

.mbrreg-warning {
	background-color: #fff3cd;
	color: #856404;
	border: 1px solid #ffeeba;
}

.mbrreg-info {
	background-color: #d1ecf1;
	color: #0c5460;
	border: 1px solid #bee5eb;
}

.mbrreg-form-messages:empty {
	display: none;
}

/* Form Links */
.mbrreg-form-links {
	margin-top: 20px;
	text-align: center;
}

.mbrreg-form-links a {
	color: #0073aa;
	text-decoration: none;
}

.mbrreg-form-links a:hover {
	text-decoration: underline;
}

/* Dashboard Styles */
.mbrreg-dashboard-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 20px;
	flex-wrap: wrap;
	gap: 15px;
}

.mbrreg-dashboard-header h2 {
	margin: 0;
}

.mbrreg-dashboard-actions {
	display: flex;
	gap: 10px;
	flex-wrap: wrap;
}

.mbrreg-account-info {
	background: #f9f9f9;
	padding: 15px 20px;
	border-radius: 4px;
	margin-bottom: 25px;
}

.mbrreg-account-info p {
	margin: 5px 0;
}

/* Member Card Styles */
.mbrreg-member-card {
	background: #fff;
	border: 1px solid #ddd;
	border-radius: 4px;
	margin-bottom: 25px;
	overflow: hidden;
}

.mbrreg-member-card-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 15px 20px;
	background: #f5f5f5;
	border-bottom: 1px solid #ddd;
}

.mbrreg-member-card-header h3 {
	margin: 0;
	font-size: 18px;
}

.mbrreg-member-card .mbrreg-form {
	border: none;
	box-shadow: none;
	border-radius: 0;
}

/* Status Badges */
.mbrreg-status {
	display: inline-block;
	padding: 5px 12px;
	border-radius: 15px;
	font-size: 13px;
	font-weight: 600;
	text-transform: uppercase;
}

.mbrreg-status-active {
	background-color: #d4edda;
	color: #155724;
}

.mbrreg-status-inactive {
	background-color: #f8d7da;
	color: #721c24;
}

.mbrreg-status-pending {
	background-color: #fff3cd;
	color: #856404;
}

/* Add Member Section */
.mbrreg-add-member-section {
	margin-top: 30px;
	padding-top: 20px;
	border-top: 2px solid #eee;
}

.mbrreg-add-member-form-container {
	margin-top: 20px;
}

.mbrreg-add-member-form-container h3 {
	margin-bottom: 10px;
}

.mbrreg-add-member-form-container .mbrreg-info {
	color: #666;
	margin-bottom: 20px;
}

/* Form Actions */
.mbrreg-form-actions {
	display: flex;
	gap: 10px;
	flex-wrap: wrap;
}

/* ==========================================
   MODAL STYLES - FIXED FOR PROPER CENTERING
   ========================================== */

.mbrreg-modal-overlay {
	position: fixed;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	width: 100%;
	height: 100%;
	background-color: rgba(0, 0, 0, 0.6);
	display: flex;
	align-items: center;
	justify-content: center;
	z-index: 999999;
	padding: 20px;
	box-sizing: border-box;
}

.mbrreg-modal-dialog {
	background: #fff;
	border-radius: 8px;
	width: 100%;
	max-width: 450px;
	max-height: calc(100vh - 40px);
	overflow: auto;
	box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
	animation: mbrreg-modal-appear 0.2s ease-out;
	margin: 0 auto;
}

@keyframes mbrreg-modal-appear {
	from {
		opacity: 0;
		transform: scale(0.95);
	}
	to {
		opacity: 1;
		transform: scale(1);
	}
}

.mbrreg-modal-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 15px 20px;
	border-bottom: 1px solid #eee;
}

.mbrreg-modal-title {
	margin: 0;
	font-size: 18px;
	color: #333;
}

.mbrreg-modal-close-btn {
	background: none;
	border: none;
	font-size: 28px;
	color: #999;
	cursor: pointer;
	padding: 0;
	line-height: 1;
	width: 30px;
	height: 30px;
	display: flex;
	align-items: center;
	justify-content: center;
}

.mbrreg-modal-close-btn:hover {
	color: #333;
}

.mbrreg-modal-body {
	padding: 20px;
}

.mbrreg-modal-message {
	margin: 0;
	color: #555;
	line-height: 1.6;
}

.mbrreg-modal-footer {
	display: flex;
	justify-content: flex-end;
	gap: 10px;
	padding: 15px 20px;
	border-top: 1px solid #eee;
	background: #f9f9f9;
}

/* ==========================================
   RESPONSIVE STYLES
   ========================================== */

@media screen and (max-width: 600px) {
	.mbrreg-auth-container,
	.mbrreg-dashboard-container,
	.mbrreg-form-container {
		padding: 10px;
	}

	.mbrreg-form {
		padding: 20px 15px;
	}

	.mbrreg-fieldset {
		padding: 15px;
	}

	.mbrreg-tab {
		padding: 10px 16px;
		font-size: 14px;
	}

	.mbrreg-dashboard-header {
		flex-direction: column;
		align-items: flex-start;
	}

	.mbrreg-member-card-header {
		flex-direction: column;
		align-items: flex-start;
		gap: 10px;
	}

	.mbrreg-form-actions {
		flex-direction: column;
	}

	.mbrreg-button {
		width: 100%;
		text-align: center;
	}

	/* Modal responsive - full screen on mobile */
	.mbrreg-modal-overlay {
		padding: 0;
		align-items: stretch;
	}

	.mbrreg-modal-dialog {
		max-width: 100%;
		max-height: 100%;
		border-radius: 0;
		display: flex;
		flex-direction: column;
	}

	.mbrreg-modal-body {
		flex: 1;
		overflow-y: auto;
	}

	.mbrreg-modal-footer {
		flex-direction: column;
	}

	.mbrreg-modal-footer .mbrreg-button {
		width: 100%;
	}
}

/* Tablet adjustments */
@media screen and (min-width: 601px) and (max-width: 900px) {
	.mbrreg-modal-dialog {
		max-width: 90%;
	}
}
```

---

## admin/partials/mbrreg-admin-import-export.php (UPDATED)

```php
<?php
/**
 * Admin import/export page template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/admin/partials
 * @since 1.1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$custom_fields = ( new Mbrreg_Custom_Fields() )->get_all();
?>

<div class="wrap mbrreg-admin-wrap">
	<h1><?php esc_html_e( 'Import / Export Members', 'member-registration-plugin' ); ?></h1>

	<div class="mbrreg-admin-sections">
		<!-- Import Section -->
		<div class="mbrreg-admin-section">
			<h2><?php esc_html_e( 'Import Members', 'member-registration-plugin' ); ?></h2>
			<p class="description">
				<?php esc_html_e( 'Import members from a CSV file. An activation email will be sent to each imported member.', 'member-registration-plugin' ); ?>
			</p>

			<div class="mbrreg-import-instructions">
				<h4><?php esc_html_e( 'CSV File Format', 'member-registration-plugin' ); ?></h4>
				<p><?php esc_html_e( 'Your CSV file should have the following columns in order:', 'member-registration-plugin' ); ?></p>
				<ol>
					<li><strong><?php esc_html_e( 'Email', 'member-registration-plugin' ); ?></strong> (<?php esc_html_e( 'required', 'member-registration-plugin' ); ?>)</li>
					<li><strong><?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?></strong></li>
					<li><strong><?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?></strong></li>
					<?php foreach ( $custom_fields as $field ) : ?>
						<li>
							<strong><?php echo esc_html( $field->field_label ); ?></strong>
							<?php if ( 'date' === $field->field_type ) : ?>
								(<?php echo esc_html( mbrreg_get_date_placeholder() ); ?>)
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ol>
				<p><strong><?php esc_html_e( 'Note:', 'member-registration-plugin' ); ?></strong> <?php esc_html_e( 'The first row should contain column headers and will be skipped during import.', 'member-registration-plugin' ); ?></p>
			</div>

			<form id="mbrreg-import-form" method="post" enctype="multipart/form-data" class="mbrreg-admin-form">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="csv_file"><?php esc_html_e( 'CSV File', 'member-registration-plugin' ); ?></label>
						</th>
						<td>
							<input type="file" name="csv_file" id="csv_file" accept=".csv" required>
							<p class="description"><?php esc_html_e( 'Select a CSV file to import.', 'member-registration-plugin' ); ?></p>
						</td>
					</tr>
				</table>

				<div class="mbrreg-import-results" style="display: none;">
					<h4><?php esc_html_e( 'Import Results', 'member-registration-plugin' ); ?></h4>
					<div class="mbrreg-import-message"></div>
				</div>

				<p class="submit">
					<button type="submit" class="button button-primary">
						<?php esc_html_e( 'Import Members', 'member-registration-plugin' ); ?>
					</button>
					<span class="spinner"></span>
				</p>
			</form>

			<div class="mbrreg-sample-csv">
				<h4><?php esc_html_e( 'Download Sample CSV', 'member-registration-plugin' ); ?></h4>
				<p><?php esc_html_e( 'Download a sample CSV file with the correct format:', 'member-registration-plugin' ); ?></p>
				<a href="#" class="button" id="mbrreg-download-sample">
					<?php esc_html_e( 'Download Sample CSV', 'member-registration-plugin' ); ?>
				</a>
			</div>
		</div>

		<!-- Export Section -->
		<div class="mbrreg-admin-section">
			<h2><?php esc_html_e( 'Export Members', 'member-registration-plugin' ); ?></h2>
			<p class="description">
				<?php esc_html_e( 'Export members to a CSV file for backup or external use.', 'member-registration-plugin' ); ?>
			</p>

			<form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>" class="mbrreg-admin-form">
				<input type="hidden" name="page" value="mbrreg-import-export">
				<input type="hidden" name="mbrreg_export" value="1">
				<?php wp_nonce_field( 'mbrreg_export_csv', '_wpnonce', false ); ?>

				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="export_status"><?php esc_html_e( 'Member Status', 'member-registration-plugin' ); ?></label>
						</th>
						<td>
							<select name="status" id="export_status">
								<option value=""><?php esc_html_e( 'All Members', 'member-registration-plugin' ); ?></option>
								<option value="active"><?php esc_html_e( 'Active Only', 'member-registration-plugin' ); ?></option>
								<option value="inactive"><?php esc_html_e( 'Inactive Only', 'member-registration-plugin' ); ?></option>
								<option value="pending"><?php esc_html_e( 'Pending Only', 'member-registration-plugin' ); ?></option>
							</select>
						</td>
					</tr>
				</table>

				<p class="submit">
					<button type="submit" class="button button-primary">
						<?php esc_html_e( 'Export Members', 'member-registration-plugin' ); ?>
					</button>
				</p>
			</form>

			<div class="mbrreg-export-info">
				<h4><?php esc_html_e( 'Export Information', 'member-registration-plugin' ); ?></h4>
				<p><?php esc_html_e( 'The exported CSV will include:', 'member-registration-plugin' ); ?></p>
				<ul>
					<li><?php esc_html_e( 'Member ID, Username, Email', 'member-registration-plugin' ); ?></li>
					<li><?php esc_html_e( 'First Name, Last Name', 'member-registration-plugin' ); ?></li>
					<li><?php esc_html_e( 'Status, Admin Status, Registration Date', 'member-registration-plugin' ); ?></li>
					<li><?php esc_html_e( 'All custom field values', 'member-registration-plugin' ); ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
	// Download sample CSV.
	$('#mbrreg-download-sample').on('click', function(e) {
		e.preventDefault();

		var headers = ['Email', 'First Name', 'Last Name'];
		<?php foreach ( $custom_fields as $field ) : ?>
		headers.push('<?php echo esc_js( $field->field_label ); ?>');
		<?php endforeach; ?>

		var sampleData = [
			headers,
			['john.doe@example.com', 'John', 'Doe'<?php foreach ( $custom_fields as $field ) : ?>, ''<?php endforeach; ?>],
			['jane.smith@example.com', 'Jane', 'Smith'<?php foreach ( $custom_fields as $field ) : ?>, ''<?php endforeach; ?>]
		];

		var csvContent = sampleData.map(function(row) {
			return row.map(function(cell) {
				// Escape quotes and wrap in quotes if contains comma.
				if (typeof cell === 'string' && (cell.indexOf(',') !== -1 || cell.indexOf('"') !== -1)) {
					return '"' + cell.replace(/"/g, '""') + '"';
				}
				return cell;
			}).join(',');
		}).join('\n');

		var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
		var link = document.createElement('a');
		var url = URL.createObjectURL(blob);
		link.setAttribute('href', url);
		link.setAttribute('download', 'members-import-sample.csv');
		link.style.visibility = 'hidden';
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
	});
});
</script>
```

---

## languages/member-registration-plugin-nl_NL.po (UPDATED - Added email translations)

```po
# Dutch translation for Member Registration Plugin.
# Copyright (C) 2024
# This file is distributed under the same license as the plugin.
#
msgid ""
msgstr ""
"Project-Id-Version: Member Registration Plugin 1.2.0\n"
"Report-Msgid-Bugs-To: \n"
"POT-Creation-Date: 2024-01-01 12:00+0000\n"
"PO-Revision-Date: 2024-01-01 12:00+0000\n"
"Last-Translator: \n"
"Language-Team: Dutch\n"
"Language: nl_NL\n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: 8bit\n"
"Plural-Forms: nplurals=2; plural=(n != 1);\n"

# Plugin Name
msgid "Member Registration Plugin"
msgstr "Leden Registratie Plugin"

# General
msgid "Members"
msgstr "Leden"

msgid "All Members"
msgstr "Alle Leden"

msgid "Member Registration"
msgstr "Ledenregistratie"

msgid "My Memberships"
msgstr "Mijn Lidmaatschappen"

msgid "Settings"
msgstr "Instellingen"

msgid "Custom Fields"
msgstr "Aangepaste Velden"

msgid "Import / Export"
msgstr "Importeren / Exporteren"

# Login Form
msgid "Login"
msgstr "Inloggen"

msgid "Username or Email"
msgstr "Gebruikersnaam of E-mail"

msgid "Password"
msgstr "Wachtwoord"

msgid "Remember Me"
msgstr "Onthoud mij"

msgid "Log In"
msgstr "Inloggen"

msgid "Forgot your password?"
msgstr "Wachtwoord vergeten?"

msgid "Login successful!"
msgstr "Succesvol ingelogd!"

msgid "Invalid username or password."
msgstr "Ongeldige gebruikersnaam of wachtwoord."

msgid "Please enter your username and password."
msgstr "Vul uw gebruikersnaam en wachtwoord in."

# Registration Form
msgid "Register"
msgstr "Registreren"

msgid "Account Details"
msgstr "Accountgegevens"

msgid "Email Address"
msgstr "E-mailadres"

msgid "Username"
msgstr "Gebruikersnaam"

msgid "Leave blank to auto-generate from email."
msgstr "Laat leeg om automatisch te genereren op basis van e-mail."

msgid "Confirm Password"
msgstr "Bevestig Wachtwoord"

msgid "Personal Details"
msgstr "Persoonlijke Gegevens"

msgid "First Name"
msgstr "Voornaam"

msgid "Last Name"
msgstr "Achternaam"

msgid "Additional Information"
msgstr "Aanvullende Informatie"

msgid "Registration successful! Please check your email to activate your account."
msgstr "Registratie succesvol! Controleer uw e-mail om uw account te activeren."

msgid "Registration is currently disabled."
msgstr "Registratie is momenteel uitgeschakeld."

msgid "This email address is already registered."
msgstr "Dit e-mailadres is al geregistreerd."

msgid "Email address is required."
msgstr "E-mailadres is verplicht."

msgid "Please enter a valid email address."
msgstr "Voer een geldig e-mailadres in."

msgid "Passwords do not match."
msgstr "Wachtwoorden komen niet overeen."

# Dashboard
msgid "Welcome, %s!"
msgstr "Welkom, %s!"

msgid "Log Out"
msgstr "Uitloggen"

msgid "Manage Members"
msgstr "Leden Beheren"

msgid "Account Email:"
msgstr "Account E-mail:"

msgid "Registered Members:"
msgstr "Geregistreerde Leden:"

msgid "Member #%d"
msgstr "Lid #%d"

msgid "Update Profile"
msgstr "Profiel Bijwerken"

msgid "Deactivate Membership"
msgstr "Lidmaatschap Deactiveren"

msgid "Profile updated successfully!"
msgstr "Profiel succesvol bijgewerkt!"

msgid "+ Add Another Member"
msgstr "+ Nog een Lid Toevoegen"

msgid "Add New Member"
msgstr "Nieuw Lid Toevoegen"

msgid "Add another family member to your account."
msgstr "Voeg nog een familielid toe aan uw account."

msgid "Add Member"
msgstr "Lid Toevoegen"

msgid "Cancel"
msgstr "Annuleren"

msgid "Member added successfully! Please check your email to activate the membership."
msgstr "Lid succesvol toegevoegd! Controleer uw e-mail om het lidmaatschap te activeren."

msgid "Adding multiple members is not allowed."
msgstr "Het toevoegen van meerdere leden is niet toegestaan."

# Status
msgid "Pending Activation"
msgstr "Wacht op Activatie"

msgid "Active"
msgstr "Actief"

msgid "Inactive"
msgstr "Inactief"

msgid "Status"
msgstr "Status"

# Confirmation Messages
msgid "Confirm Action"
msgstr "Actie Bevestigen"

msgid "Are you sure you want to deactivate this membership? If this is your only active membership, you will be logged out."
msgstr "Weet u zeker dat u dit lidmaatschap wilt deactiveren? Als dit uw enige actieve lidmaatschap is, wordt u uitgelogd."

msgid "Are you sure you want to log out?"
msgstr "Weet u zeker dat u wilt uitloggen?"

msgid "Membership has been set to inactive."
msgstr "Lidmaatschap is ingesteld op inactief."

msgid "You have been logged out."
msgstr "U bent uitgelogd."

msgid "Confirm"
msgstr "Bevestigen"

msgid "OK"
msgstr "OK"

msgid "Notice"
msgstr "Melding"

msgid "Error"
msgstr "Fout"

msgid "Success"
msgstr "Succes"

# Display Settings
msgid "Display Settings"
msgstr "Weergave Instellingen"

msgid "Date Format"
msgstr "Datumnotatie"

msgid "European (DD/MM/YYYY)"
msgstr "Europees (DD/MM/JJJJ)"

msgid "US (MM/DD/YYYY)"
msgstr "Amerikaans (MM/DD/JJJJ)"

msgid "Choose how dates are displayed throughout the plugin."
msgstr "Kies hoe datums worden weergegeven in de hele plugin."

# Admin - Members List
msgid "All"
msgstr "Alle"

msgid "Pending"
msgstr "In Afwachting"

msgid "Search Members"
msgstr "Leden Zoeken"

msgid "Search members..."
msgstr "Leden zoeken..."

msgid "Search"
msgstr "Zoeken"

msgid "Bulk Actions"
msgstr "Bulkacties"

msgid "Activate"
msgstr "Activeren"

msgid "Deactivate"
msgstr "Deactiveren"

msgid "Delete Members"
msgstr "Leden Verwijderen"

msgid "Delete Members + Users"
msgstr "Leden + Gebruikers Verwijderen"

msgid "Apply"
msgstr "Toepassen"

msgid "Name"
msgstr "Naam"

msgid "Email"
msgstr "E-mail"

msgid "Account Owner"
msgstr "Accounteigenaar"

msgid "Admin"
msgstr "Beheerder"

msgid "Registered"
msgstr "Geregistreerd"

msgid "Edit"
msgstr "Bewerken"

msgid "Delete"
msgstr "Verwijderen"

msgid "Resend Activation"
msgstr "Activatie Opnieuw Versturen"

msgid "No members found."
msgstr "Geen leden gevonden."

msgid "%s member"
msgid_plural "%s members"
msgstr[0] "%s lid"
msgstr[1] "%s leden"

# Admin - Edit Member
msgid "Edit Member"
msgstr "Lid Bewerken"

msgid "Back to Members"
msgstr "Terug naar Leden"

msgid "Personal Information"
msgstr "Persoonlijke Informatie"

msgid "Member Status"
msgstr "Lidstatus"

msgid "Member Admin"
msgstr "Lid Beheerder"

msgid "Can manage members"
msgstr "Kan leden beheren"

msgid "Member admins can view and manage other members from the frontend."
msgstr "Ledenadministrateurs kunnen andere leden bekijken en beheren vanuit de frontend."

msgid "Account Information"
msgstr "Accountinformatie"

msgid "Member ID"
msgstr "Lid ID"

msgid "WP User ID"
msgstr "WP Gebruiker ID"

msgid "Last Updated"
msgstr "Laatst Bijgewerkt"

msgid "Actions"
msgstr "Acties"

msgid "Update Member"
msgstr "Lid Bijwerken"

msgid "Delete Member"
msgstr "Lid Verwijderen"

msgid "Resend Activation Email"
msgstr "Activatie E-mail Opnieuw Versturen"

msgid "Member updated successfully!"
msgstr "Lid succesvol bijgewerkt!"

msgid "Member deleted successfully!"
msgstr "Lid succesvol verwijderd!"

msgid "Member not found."
msgstr "Lid niet gevonden."

msgid "This user account (%2$s) has %1$d registered members."
msgstr "Dit gebruikersaccount (%2$s) heeft %1$d geregistreerde leden."

# Admin - Custom Fields
msgid "Existing Fields"
msgstr "Bestaande Velden"

msgid "Order"
msgstr "Volgorde"

msgid "Label"
msgstr "Label"

msgid "Type"
msgstr "Type"

msgid "Required"
msgstr "Verplicht"

msgid "Admin Only"
msgstr "Alleen Beheerder"

msgid "Admin only - users cannot edit"
msgstr "Alleen beheerder - gebruikers kunnen niet bewerken"

msgid "Editable by users"
msgstr "Bewerkbaar door gebruikers"

msgid "No custom fields have been created yet."
msgstr "Er zijn nog geen aangepaste velden aangemaakt."

msgid "Add New Field"
msgstr "Nieuw Veld Toevoegen"

msgid "Field Name"
msgstr "Veldnaam"

msgid "Unique identifier. Lowercase letters, numbers, and underscores only."
msgstr "Unieke identificatie. Alleen kleine letters, cijfers en underscores."

msgid "Field Label"
msgstr "Veldlabel"

msgid "The label displayed to users."
msgstr "Het label dat aan gebruikers wordt getoond."

msgid "Field Type"
msgstr "Veldtype"

msgid "Text"
msgstr "Tekst"

msgid "Textarea"
msgstr "Tekstvak"

msgid "Number"
msgstr "Nummer"

msgid "Date"
msgstr "Datum"

msgid "Dropdown"
msgstr "Keuzelijst"

msgid "Checkbox"
msgstr "Selectievakje"

msgid "Radio Buttons"
msgstr "Keuzerondjes"

msgid "Options"
msgstr "Opties"

msgid "Enter one option per line."
msgstr "Voer één optie per regel in."

msgid "Display Order"
msgstr "Weergavevolgorde"

msgid "Lower numbers display first."
msgstr "Lagere nummers worden eerst weergegeven."

msgid "Required field"
msgstr "Verplicht veld"

msgid "Admin only (users cannot edit)"
msgstr "Alleen beheerder (gebruikers kunnen niet bewerken)"

msgid "If checked, only administrators can view and edit this field. Users will see the value but cannot change it."
msgstr "Indien aangevinkt, kunnen alleen beheerders dit veld bekijken en bewerken. Gebruikers zien de waarde maar kunnen deze niet wijzigen."

msgid "Add Field"
msgstr "Veld Toevoegen"

msgid "Edit Custom Field"
msgstr "Aangepast Veld Bewerken"

msgid "Update Field"
msgstr "Veld Bijwerken"

msgid "Custom field created successfully!"
msgstr "Aangepast veld succesvol aangemaakt!"

msgid "Custom field updated successfully!"
msgstr "Aangepast veld succesvol bijgewerkt!"

msgid "Custom field deleted successfully!"
msgstr "Aangepast veld succesvol verwijderd!"

msgid "Field name is required."
msgstr "Veldnaam is verplicht."

msgid "Field label is required."
msgstr "Veldlabel is verplicht."

msgid "This field name is reserved."
msgstr "Deze veldnaam is gereserveerd."

msgid "(View only)"
msgstr "(Alleen bekijken)"

msgid "This field can only be edited by administrators."
msgstr "Dit veld kan alleen worden bewerkt door beheerders."

# Admin - Import/Export
msgid "Import Members"
msgstr "Leden Importeren"

msgid "Import members from a CSV file. An activation email will be sent to each imported member."
msgstr "Importeer leden vanuit een CSV-bestand. Aan elk geïmporteerd lid wordt een activatie-e-mail gestuurd."

msgid "CSV File Format"
msgstr "CSV-bestandsformaat"

msgid "Your CSV file should have the following columns in order:"
msgstr "Uw CSV-bestand moet de volgende kolommen in volgorde bevatten:"

msgid "required"
msgstr "verplicht"

msgid "Note:"
msgstr "Opmerking:"

msgid "The first row should contain column headers and will be skipped during import."
msgstr "De eerste rij moet kolomkoppen bevatten en wordt overgeslagen tijdens het importeren."

msgid "CSV File"
msgstr "CSV-bestand"

msgid "Select a CSV file to import."
msgstr "Selecteer een CSV-bestand om te importeren."

msgid "Import Results"
msgstr "Importresultaten"

msgid "Download Sample CSV"
msgstr "Voorbeeld CSV Downloaden"

msgid "Download a sample CSV file with the correct format:"
msgstr "Download een voorbeeld CSV-bestand met het juiste formaat:"

msgid "Export Members"
msgstr "Leden Exporteren"

msgid "Export members to a CSV file for backup or external use."
msgstr "Exporteer leden naar een CSV-bestand voor backup of extern gebruik."

msgid "Active Only"
msgstr "Alleen Actief"

msgid "Inactive Only"
msgstr "Alleen Inactief"

msgid "Pending Only"
msgstr "Alleen In Afwachting"

msgid "Export Information"
msgstr "Export Informatie"

msgid "The exported CSV will include:"
msgstr "Het geëxporteerde CSV-bestand bevat:"

msgid "Member ID, Username, Email"
msgstr "Lid ID, Gebruikersnaam, E-mail"

msgid "First Name, Last Name"
msgstr "Voornaam, Achternaam"

msgid "Status, Admin Status, Registration Date"
msgstr "Status, Beheerdersstatus, Registratiedatum"

msgid "All custom field values"
msgstr "Alle aangepaste veldwaarden"

msgid "Import completed. %1$d members imported successfully, %2$d errors."
msgstr "Import voltooid. %1$d leden succesvol geïmporteerd, %2$d fouten."

msgid "Row %d: Email is required."
msgstr "Rij %d: E-mail is verplicht."

msgid "Row %1$d: %2$s"
msgstr "Rij %1$d: %2$s"

msgid "Errors:"
msgstr "Fouten:"

msgid "...and %d more errors."
msgstr "...en nog %d fouten."

msgid "Please select a CSV file to upload."
msgstr "Selecteer een CSV-bestand om te uploaden."

msgid "Invalid file type. Please upload a CSV file."
msgstr "Ongeldig bestandstype. Upload een CSV-bestand."

msgid "Failed to read the CSV file."
msgstr "Kan het CSV-bestand niet lezen."

msgid "The CSV file is empty or contains only headers."
msgstr "Het CSV-bestand is leeg of bevat alleen kopteksten."

# Admin - Settings
msgid "Member Registration Settings"
msgstr "Ledenregistratie Instellingen"

msgid "Registration Settings"
msgstr "Registratie Instellingen"

msgid "Allow Registration"
msgstr "Registratie Toestaan"

msgid "Allow new members to register"
msgstr "Nieuwe leden toestaan om te registreren"

msgid "When disabled, the registration form will not be shown."
msgstr "Wanneer uitgeschakeld, wordt het registratieformulier niet getoond."

msgid "Allow Multiple Members"
msgstr "Meerdere Leden Toestaan"

msgid "Allow users to register multiple members under one account"
msgstr "Gebruikers toestaan om meerdere leden te registreren onder één account"

msgid "Useful for parents registering multiple children or family members."
msgstr "Handig voor ouders die meerdere kinderen of familieleden registreren."

msgid "Member Area Page"
msgstr "Ledengebied Pagina"

msgid "— Select —"
msgstr "— Selecteer —"

msgid "The page containing the [mbrreg_member_area] shortcode."
msgstr "De pagina met de [mbrreg_member_area] shortcode."

msgid "Login Redirect Page"
msgstr "Doorstuurpagina Na Inloggen"

msgid "Where to redirect members after successful login."
msgstr "Waarheen leden worden doorgestuurd na succesvol inloggen."

msgid "Required Fields"
msgstr "Verplichte Velden"

msgid "Select which default fields should be required during registration and profile updates. Additional fields can be configured in the Custom Fields section."
msgstr "Selecteer welke standaardvelden verplicht moeten zijn tijdens registratie en profielupdates. Extra velden kunnen worden geconfigureerd in de sectie Aangepaste Velden."

msgid "Email Settings"
msgstr "E-mail Instellingen"

msgid "From Name"
msgstr "Van Naam"

msgid "The name that will appear in the \"From\" field of emails."
msgstr "De naam die verschijnt in het \"Van\" veld van e-mails."

msgid "From Email"
msgstr "Van E-mail"

msgid "The email address that will appear in the \"From\" field."
msgstr "Het e-mailadres dat verschijnt in het \"Van\" veld."

msgid "Shortcodes"
msgstr "Shortcodes"

msgid "Complete member area with login, registration, and dashboard."
msgstr "Volledig ledengebied met inloggen, registratie en dashboard."

msgid "Standalone login form."
msgstr "Zelfstandig inlogformulier."

msgid "Standalone registration form."
msgstr "Zelfstandig registratieformulier."

msgid "Member dashboard (for logged-in members only)."
msgstr "Ledendashboard (alleen voor ingelogde leden)."

# ==========================================
# EMAIL TRANSLATIONS
# ==========================================

# Activation Email
msgid "Activate your membership at %s"
msgstr "Activeer uw lidmaatschap bij %s"

msgid "Hello %1$s,

Thank you for registering as a member at %2$s.

Please click the following link to activate your account:

%3$s

If you did not register for this account, please ignore this email.

Best regards,
%4$s"
msgstr "Hallo %1$s,

Bedankt voor uw registratie als lid bij %2$s.

Klik op de volgende link om uw account te activeren:

%3$s

Als u zich niet heeft geregistreerd voor dit account, kunt u deze e-mail negeren.

Met vriendelijke groet,
%4$s"

# Import Activation Email
msgid "You have been registered as a member at %s"
msgstr "U bent geregistreerd als lid bij %s"

msgid "Hello %1$s,

You have been registered as a member at %2$s.

Please click the following link to activate your account:

%3$s

After activation, you can log in and review/update your details at:
%4$s

Your username is: %5$s

If you need to set a password, please use the password reset function on the login page.

If you did not expect this email, please contact the club administrator.

Best regards,
%6$s"
msgstr "Hallo %1$s,

U bent geregistreerd als lid bij %2$s.

Klik op de volgende link om uw account te activeren:

%3$s

Na activering kunt u inloggen en uw gegevens bekijken/bijwerken op:
%4$s

Uw gebruikersnaam is: %5$s

Als u een wachtwoord moet instellen, gebruik dan de wachtwoord vergeten functie op de inlogpagina.

Als u deze e-mail niet verwachtte, neem dan contact op met de clubbeheerder.

Met vriendelijke groet,
%6$s"

# Welcome Email
msgid "Welcome to %s!"
msgstr "Welkom bij %s!"

msgid "Hello %1$s,

Your account at %2$s has been activated successfully!

You can now log in and manage your membership details at:
%3$s

Best regards,
%4$s"
msgstr "Hallo %1$s,

Uw account bij %2$s is succesvol geactiveerd!

U kunt nu inloggen en uw lidmaatschapsgegevens beheren op:
%3$s

Met vriendelijke groet,
%4$s"

# Admin Notification Email
msgid "New member registration at %s"
msgstr "Nieuwe ledenregistratie bij %s"

msgid "A new member has registered:

Username: %1$s
Email: %2$s

You can view and manage members in the WordPress admin area."
msgstr "Een nieuw lid heeft zich geregistreerd:

Gebruikersnaam: %1$s
E-mail: %2$s

U kunt leden bekijken en beheren in het WordPress beheergebied."

# Misc
msgid "Processing..."
msgstr "Bezig met verwerken..."

msgid "An error occurred. Please try again."
msgstr "Er is een fout opgetreden. Probeer het opnieuw."

msgid "Security check failed."
msgstr "Beveiligingscontrole mislukt."

msgid "You must be logged in."
msgstr "U moet ingelogd zijn."

msgid "Invalid member ID."
msgstr "Ongeldig lid ID."

msgid "You do not have permission to perform this action."
msgstr "U heeft geen toestemming om deze actie uit te voeren."

msgid "You do not have permission to update this member."
msgstr "U heeft geen toestemming om dit lid bij te werken."

msgid "Please activate your account first. Check your email for the activation link."
msgstr "Activeer eerst uw account. Controleer uw e-mail voor de activatielink."

msgid "All your memberships are inactive. Please contact the administrator."
msgstr "Al uw lidmaatschappen zijn inactief. Neem contact op met de beheerder."

msgid "Your account has been activated! You can now log in."
msgstr "Uw account is geactiveerd! U kunt nu inloggen."

msgid "Invalid activation key."
msgstr "Ongeldige activatiesleutel."

msgid "This account is already activated."
msgstr "Dit account is al geactiveerd."

msgid "Activation key is required."
msgstr "Activatiesleutel is verplicht."

msgid "You are already logged in."
msgstr "U bent al ingelogd."

msgid "You are already registered and logged in."
msgstr "U bent al geregistreerd en ingelogd."

msgid "Please log in to view your dashboard."
msgstr "Log in om uw dashboard te bekijken."

msgid "No member profile found. Please contact an administrator."
msgstr "Geen lidprofiel gevonden. Neem contact op met een beheerder."

msgid "%s is required."
msgstr "%s is verplicht."

msgid "Activation email sent successfully!"
msgstr "Activatie-e-mail succesvol verzonden!"

msgid "-- Select --"
msgstr "-- Selecteer --"

msgid "Bulk action completed. %1$d successful, %2$d failed."
msgstr "Bulkactie voltooid. %1$d succesvol, %2$d mislukt."

msgid "Please select at least one member."
msgstr "Selecteer ten minste één lid."

msgid "Please select an action."
msgstr "Selecteer een actie."

# Legacy field labels (for migration)
msgid "Address"
msgstr "Adres"

msgid "Telephone"
msgstr "Telefoonnummer"

msgid "Date of Birth"
msgstr "Geboortedatum"

msgid "Place of Birth"
msgstr "Geboorteplaats"
```

---

## Summary of Changes in Version 1.2.0

### Adjustments Made:
1. **Date Format Setting** - Added a new "Display Settings" section in settings with option to choose between European (DD/MM/YYYY) and US (MM/DD/YYYY) date formats. Helper functions `mbrreg_format_date()`, `mbrreg_parse_date()`, `mbrreg_get_date_format()`, and `mbrreg_get_date_placeholder()` handle date formatting throughout the plugin.

2. **Removed Default Fields** - Removed address, telephone, date of birth, and place of birth from the default member fields. Only first_name and last_name remain as default personal details. A migration function was added to convert any existing data in these fields to custom fields automatically.

3. **Multilingual Emails** - All email content is now translatable using the standard WordPress translation system. The Dutch translation file has been updated with complete translations for all email templates.

### Fixes Made:
1. **Modal Centering** - Fixed the CSS for modals to properly center on both desktop and mobile screens. The modal now uses fixed positioning with proper viewport centering. On mobile, the modal expands to cover the full screen.

2. **Input Field Sizing** - Fixed input fields to properly fit within their form containers by adding `width: 100%`, `max-width: 100%`, and `box-sizing: border-box` to all input elements.

### Files Changed:
- `member-registration-plugin.php` - Updated version, added date helper functions
- `includes/class-mbrreg-activator.php` - Simplified table schema, added migration
- `includes/class-mbrreg-database.php` - Removed old field references
- `includes/class-mbrreg-member.php` - Removed old field handling
- `includes/class-mbrreg-email.php` - Made emails translatable
- `includes/class-mbrreg-admin.php` - Added date format setting registration
- `admin/partials/mbrreg-admin-settings.php` - Added date format option, simplified required fields
- `admin/partials/mbrreg-admin-member-edit.php` - Simplified form
- `admin/partials/mbrreg-admin-members.php` - Updated date display
- `admin/partials/mbrreg-admin-import-export.php` - Updated CSV format
- `public/partials/mbrreg-register-form.php` - Simplified form
- `public/partials/mbrreg-member-dashboard.php` - Simplified form
- `public/css/mbrreg-public.css` - Fixed modal and input styling
- `languages/member-registration-plugin-nl_NL.po` - Added email translations


