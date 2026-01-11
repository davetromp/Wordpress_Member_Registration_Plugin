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