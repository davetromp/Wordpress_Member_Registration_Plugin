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