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