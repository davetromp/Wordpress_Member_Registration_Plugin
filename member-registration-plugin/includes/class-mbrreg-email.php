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