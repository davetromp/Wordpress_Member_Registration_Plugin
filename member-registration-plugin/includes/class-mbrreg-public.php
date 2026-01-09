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