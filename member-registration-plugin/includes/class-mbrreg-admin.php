<?php
/**
 * Admin functionality class.
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
 * Class Mbrreg_Admin
 *
 * Handles admin functionality.
 *
 * @since 1.0.0
 */
class Mbrreg_Admin
{

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
	public function __construct(Mbrreg_Member $member, Mbrreg_Custom_Fields $custom_fields, ?Mbrreg_Email $email = null)
	{
		$this->member = $member;
		$this->custom_fields = $custom_fields;
		$this->email = $email;
	}

	/**
	 * Initialize admin hooks.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init()
	{
		add_action('admin_menu', array($this, 'add_admin_menu'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
		add_action('admin_init', array($this, 'register_settings'));
		add_action('admin_init', array($this, 'handle_csv_export'));
	}

	/**
	 * Add admin menu pages.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function add_admin_menu()
	{
		// Main menu.
		add_menu_page(
			__('Member Registration', 'member-registration-plugin'),
			__('Members', 'member-registration-plugin'),
			'mbrreg_manage_members',
			'mbrreg-members',
			array($this, 'render_members_page'),
			'dashicons-groups',
			30
		);

		// Members submenu.
		add_submenu_page(
			'mbrreg-members',
			__('All Members', 'member-registration-plugin'),
			__('All Members', 'member-registration-plugin'),
			'mbrreg_manage_members',
			'mbrreg-members',
			array($this, 'render_members_page')
		);

		// Custom fields submenu.
		add_submenu_page(
			'mbrreg-members',
			__('Custom Fields', 'member-registration-plugin'),
			__('Custom Fields', 'member-registration-plugin'),
			'mbrreg_manage_custom_fields',
			'mbrreg-custom-fields',
			array($this, 'render_custom_fields_page')
		);

		// Import/Export submenu.
		add_submenu_page(
			'mbrreg-members',
			__('Import / Export', 'member-registration-plugin'),
			__('Import / Export', 'member-registration-plugin'),
			'mbrreg_import_members',
			'mbrreg-import-export',
			array($this, 'render_import_export_page')
		);

		// Settings submenu.
		add_submenu_page(
			'mbrreg-members',
			__('Settings', 'member-registration-plugin'),
			__('Settings', 'member-registration-plugin'),
			'mbrreg_manage_settings',
			'mbrreg-settings',
			array($this, 'render_settings_page')
		);
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @since 1.0.0
	 * @param string $hook Current admin page hook.
	 * @return void
	 */
	public function enqueue_assets($hook)
	{
		// Only load on our admin pages.
		if (strpos($hook, 'mbrreg') === false) {
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
			array('jquery'),
			MBRREG_VERSION,
			true
		);

		wp_localize_script(
			'mbrreg-admin',
			'mbrregAdmin',
			array(
				'ajaxUrl' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('mbrreg_admin_nonce'),
				'confirmDelete' => __('Are you sure you want to delete this member? This action cannot be undone.', 'member-registration-plugin'),
				'confirmBulk' => __('Are you sure you want to perform this action on the selected members?', 'member-registration-plugin'),
				'confirmFieldDelete' => __('Are you sure you want to delete this custom field? All associated data will be lost.', 'member-registration-plugin'),
				'processing' => __('Processing...', 'member-registration-plugin'),
				'success' => __('Success!', 'member-registration-plugin'),
				'error' => __('An error occurred.', 'member-registration-plugin'),
				'selectMembers' => __('Please select at least one member.', 'member-registration-plugin'),
				'selectAction' => __('Please select an action.', 'member-registration-plugin'),
				'importSuccess' => __('Import completed successfully!', 'member-registration-plugin'),
				'exportSuccess' => __('Export completed successfully!', 'member-registration-plugin'),
				'dateFormat' => get_option('mbrreg_date_format', 'eu'),
			)
		);
	}

	/**
	 * Register plugin settings.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_settings()
	{
		// Registration settings.
		register_setting('mbrreg_settings', 'mbrreg_allow_registration', array('sanitize_callback' => 'rest_sanitize_boolean'));
		register_setting('mbrreg_settings', 'mbrreg_allow_multiple_members', array('sanitize_callback' => 'rest_sanitize_boolean'));
		register_setting('mbrreg_settings', 'mbrreg_registration_page_id', array('sanitize_callback' => 'absint'));
		register_setting('mbrreg_settings', 'mbrreg_login_redirect_page', array('sanitize_callback' => 'absint'));

		// Display settings.
		register_setting('mbrreg_settings', 'mbrreg_date_format', array('sanitize_callback' => 'sanitize_text_field'));

		// Required fields settings (only first_name and last_name now).
		register_setting('mbrreg_settings', 'mbrreg_require_first_name', array('sanitize_callback' => 'rest_sanitize_boolean'));
		register_setting('mbrreg_settings', 'mbrreg_require_last_name', array('sanitize_callback' => 'rest_sanitize_boolean'));

		// Email settings.
		register_setting('mbrreg_settings', 'mbrreg_email_from_name', array('sanitize_callback' => 'sanitize_text_field'));
		register_setting('mbrreg_settings', 'mbrreg_email_from_address', array('sanitize_callback' => 'sanitize_email'));
	}

	/**
	 * Handle CSV export download.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function handle_csv_export()
	{
		if (!isset($_GET['mbrreg_export']) || !isset($_GET['_wpnonce'])) {
			return;
		}

		if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'mbrreg_export_csv')) {
			return;
		}

		if (!current_user_can('mbrreg_export_members')) {
			return;
		}

		$status = isset($_GET['status']) ? sanitize_text_field(wp_unslash($_GET['status'])) : '';

		$args = array();
		if (!empty($status)) {
			$args['status'] = $status;
		}

		$csv = $this->member->export_csv($args);
		$filename = 'members-export-' . gmdate('Y-m-d') . '.csv';

		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=' . $filename);
		header('Pragma: no-cache');
		header('Expires: 0');

		echo $csv; // phpcs:ignore
		exit;
	}

	/**
	 * Render members list page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_members_page()
	{
		// Check if editing a member.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (isset($_GET['action']) && 'edit' === $_GET['action'] && isset($_GET['member_id'])) {
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
	private function render_member_edit_page()
	{
		$member_id = absint($_GET['member_id']);
		$member = $this->member->get($member_id);

		if (!$member) {
			echo '<div class="wrap"><div class="notice notice-error"><p>' . esc_html__('Member not found.', 'member-registration-plugin') . '</p></div></div>';
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
	public function render_custom_fields_page()
	{
		$custom_fields = $this->custom_fields->get_all();

		include MBRREG_PLUGIN_PATH . 'admin/partials/mbrreg-admin-custom-fields.php';
	}

	/**
	 * Render import/export page.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function render_import_export_page()
	{
		include MBRREG_PLUGIN_PATH . 'admin/partials/mbrreg-admin-import-export.php';
	}

	/**
	 * Render settings page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_settings_page()
	{
		include MBRREG_PLUGIN_PATH . 'admin/partials/mbrreg-admin-settings.php';
	}
}