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