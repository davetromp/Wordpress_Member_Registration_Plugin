<?php
/**
 * Member operations class.
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
 * Class Mbrreg_Member
 *
 * Handles member-related operations.
 *
 * @since 1.0.0
 */
class Mbrreg_Member
{

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
		'pending' => 'Pending Activation',
		'active' => 'Active',
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
	public function __construct(Mbrreg_Database $database, Mbrreg_Custom_Fields $custom_fields, Mbrreg_Email $email)
	{
		$this->database = $database;
		$this->custom_fields = $custom_fields;
		$this->email = $email;
	}

	/**
	 * Get translated statuses.
	 *
	 * @since 1.2.0
	 * @return array
	 */
	public static function get_statuses()
	{
		return array(
			'pending' => __('Pending Activation', 'member-registration-plugin'),
			'active' => __('Active', 'member-registration-plugin'),
			'inactive' => __('Inactive', 'member-registration-plugin'),
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
	public function register($data, $is_import = false)
	{
		$user_id = 0;
		$is_new_user = true;
		$existing_user = null;

		// Check if adding to existing logged-in user.
		if (isset($data['add_to_existing_user']) && $data['add_to_existing_user'] && is_user_logged_in()) {
			$user_id = get_current_user_id();
			$is_new_user = false;
		} elseif (!empty($data['email'])) {
			// Check if user with this email already exists.
			$existing_user = get_user_by('email', $data['email']);

			if ($existing_user) {
				// Allow adding member to existing user if allowed.
				if (get_option('mbrreg_allow_multiple_members', true)) {
					$user_id = $existing_user->ID;
					$is_new_user = false;
				} else {
					return new WP_Error('email_exists', __('This email address is already registered.', 'member-registration-plugin'));
				}
			}
		}

		// Validate required fields for new user.
		if ($is_new_user) {
			if (empty($data['username']) && empty($data['email'])) {
				return new WP_Error('missing_credentials', __('Username or email is required.', 'member-registration-plugin'));
			}

			if (empty($data['email'])) {
				return new WP_Error('missing_email', __('Email address is required.', 'member-registration-plugin'));
			}

			// Validate email.
			if (!is_email($data['email'])) {
				return new WP_Error('invalid_email', __('Please enter a valid email address.', 'member-registration-plugin'));
			}

			// Generate username from email if not provided.
			if (empty($data['username'])) {
				$data['username'] = $this->generate_username_from_email($data['email']);
			}

			// Check if username exists.
			if (username_exists($data['username'])) {
				// Generate unique username.
				$data['username'] = $this->generate_unique_username($data['username']);
			}

			// Generate password if not provided (for imports).
			if (empty($data['password'])) {
				$data['password'] = wp_generate_password(12, true);
			}
		}

		// Validate required member fields.
		$validation = $this->validate_member_data($data);
		if (is_wp_error($validation)) {
			return $validation;
		}

		// Create WordPress user if needed.
		if ($is_new_user) {
			$user_id = wp_create_user(
				sanitize_user($data['username']),
				$data['password'],
				sanitize_email($data['email'])
			);

			if (is_wp_error($user_id)) {
				return $user_id;
			}
		}

		// Generate activation key.
		$activation_key = wp_generate_password(32, false);
		$status = 'pending';

		// Prepare member data (simplified - only first_name and last_name).
		$member_data = array(
			'user_id' => $user_id,
			'first_name' => isset($data['first_name']) ? sanitize_text_field($data['first_name']) : '',
			'last_name' => isset($data['last_name']) ? sanitize_text_field($data['last_name']) : '',
			'status' => $status,
			'is_admin' => 0,
			'activation_key' => $activation_key,
		);

		// Insert member.
		$member_id = $this->database->insert_member($member_data);

		if (!$member_id) {
			// Rollback: delete WordPress user only if we created it.
			if ($is_new_user) {
				wp_delete_user($user_id);
			}
			return new WP_Error('insert_failed', __('Failed to create member record.', 'member-registration-plugin'));
		}

		// Save custom field values.
		$this->save_custom_field_values($member_id, $data);

		// Send activation email.
		if ($is_import) {
			$this->email->send_import_activation_email($user_id, $activation_key, $data);
		} else {
			$this->email->send_activation_email($user_id, $activation_key);
		}

		/**
		 * Fires after a new member is registered.
		 *
		 * @since 1.0.0
		 * @param int   $member_id Member ID.
		 * @param int   $user_id   WordPress user ID.
		 * @param array $data      Registration data.
		 */
		do_action('mbrreg_member_registered', $member_id, $user_id, $data);

		return $member_id;
	}

	/**
	 * Generate username from email.
	 *
	 * @since 1.1.0
	 * @param string $email Email address.
	 * @return string Generated username.
	 */
	private function generate_username_from_email($email)
	{
		$username = sanitize_user(current(explode('@', $email)), true);
		return $username;
	}

	/**
	 * Generate unique username.
	 *
	 * @since 1.1.0
	 * @param string $username Base username.
	 * @return string Unique username.
	 */
	private function generate_unique_username($username)
	{
		$original = $username;
		$counter = 1;

		while (username_exists($username)) {
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
	public function activate($activation_key)
	{
		if (empty($activation_key)) {
			return new WP_Error('missing_key', __('Activation key is required.', 'member-registration-plugin'));
		}

		$member = $this->database->get_member_by_activation_key($activation_key);

		if (!$member) {
			return new WP_Error('invalid_key', __('Invalid activation key.', 'member-registration-plugin'));
		}

		if ('active' === $member->status) {
			return new WP_Error('already_active', __('This account is already activated.', 'member-registration-plugin'));
		}

		// Update member status.
		$result = $this->database->update_member(
			$member->id,
			array(
				'status' => 'active',
				'activation_key' => '',
			)
		);

		if (!$result) {
			return new WP_Error('update_failed', __('Failed to activate account.', 'member-registration-plugin'));
		}

		// Send welcome email.
		$this->email->send_welcome_email($member->user_id);

		/**
		 * Fires after a member account is activated.
		 *
		 * @since 1.0.0
		 * @param int $member_id Member ID.
		 * @param int $user_id   WordPress user ID.
		 */
		do_action('mbrreg_member_activated', $member->id, $member->user_id);

		return true;
	}

	/**
	 * Update member details.
	 *
	 * @since 1.0.0
	 * @param int   $member_id     Member ID.
	 * @param array $data          Member data.
	 * @param bool  $is_admin_edit Whether this is an admin edit.
	 * @param bool  $skip_validation Whether to skip field validation.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function update($member_id, $data, $is_admin_edit = false, $skip_validation = false)
	{
		$member = $this->database->get_member($member_id);

		if (!$member) {
			return new WP_Error('member_not_found', __('Member not found.', 'member-registration-plugin'));
		}

		// Validate member data unless explicitly skipped.
		if (!$skip_validation) {
			$validation = $this->validate_member_data($data, true);
			if (is_wp_error($validation)) {
				return $validation;
			}
		}

		// Prepare update data.
		$update_data = array();

		if (isset($data['first_name'])) {
			$update_data['first_name'] = sanitize_text_field($data['first_name']);
		}

		if (isset($data['last_name'])) {
			$update_data['last_name'] = sanitize_text_field($data['last_name']);
		}

		if (isset($data['status']) && array_key_exists($data['status'], self::get_statuses())) {
			$update_data['status'] = $data['status'];
		}

		// Track admin status change for capability sync.
		$old_admin_status = (int) $member->is_admin;
		$new_admin_status = $old_admin_status; // Default to current value.
		$admin_status_changed = false;

		// Handle is_admin field if it's set in the data.
		if (array_key_exists('is_admin', $data)) {
			$new_admin_status = (int) $data['is_admin'];
			$update_data['is_admin'] = $new_admin_status;
			$admin_status_changed = ($new_admin_status !== $old_admin_status);
		}

		// Update member in database.
		if (!empty($update_data)) {
			$result = $this->database->update_member($member_id, $update_data);

			if (false === $result) {
				return new WP_Error('update_failed', __('Failed to update member.', 'member-registration-plugin'));
			}
		}

		// Sync WordPress capabilities if admin status changed.
		if ($admin_status_changed) {
			$this->sync_user_admin_capability($member->user_id, $new_admin_status);
		}

		// Update WordPress user data if email changed (admin only).
		if ($is_admin_edit && isset($data['email']) && is_email($data['email'])) {
			$user = get_user_by('ID', $member->user_id);
			if ($user && $user->user_email !== $data['email']) {
				// Check if email is already used by another user.
				$existing = email_exists($data['email']);
				if ($existing && $existing !== $member->user_id) {
					return new WP_Error('email_exists', __('This email address is already in use.', 'member-registration-plugin'));
				}

				wp_update_user(
					array(
						'ID' => $member->user_id,
						'user_email' => sanitize_email($data['email']),
					)
				);
			}
		}

		// Update WordPress user first/last name.
		$user_update = array('ID' => $member->user_id);
		if (isset($data['first_name'])) {
			$user_update['first_name'] = sanitize_text_field($data['first_name']);
		}
		if (isset($data['last_name'])) {
			$user_update['last_name'] = sanitize_text_field($data['last_name']);
		}
		if (count($user_update) > 1) {
			wp_update_user($user_update);
		}

		// Save custom field values.
		$this->save_custom_field_values($member_id, $data, $is_admin_edit);

		/**
		 * Fires after a member is updated.
		 *
		 * @since 1.0.0
		 * @param int   $member_id Member ID.
		 * @param array $data      Update data.
		 */
		do_action('mbrreg_member_updated', $member_id, $data);

		return true;
	}

	/**
	 * Sync WordPress user admin capability based on member admin status.
	 *
	 * This method adds or removes the 'mbrreg_manage_members' capability
	 * from a WordPress user based on whether any of their members has
	 * admin status.
	 *
	 * @since 1.2.1
	 * @param int $user_id          WordPress user ID.
	 * @param int $new_admin_value  The new admin value (1 or 0).
	 * @return void
	 */
	private function sync_user_admin_capability($user_id, $new_admin_value)
	{
		$user = get_user_by('ID', $user_id);

		if (!$user) {
			return;
		}

		// If setting to admin, add the capability.
		if ($new_admin_value) {
			$user->add_cap('mbrreg_manage_members');
			return;
		}

		// If removing admin status, check if any other member for this user still has admin.
		// We need to query the database for fresh data since the update has already happened.
		$all_members = $this->database->get_members_by_user_id($user_id);
		$has_other_admin = false;

		foreach ($all_members as $m) {
			if ((int) $m->is_admin === 1) {
				$has_other_admin = true;
				break;
			}
		}

		// Only remove capability if no member has admin status.
		if (!$has_other_admin) {
			$user->remove_cap('mbrreg_manage_members');
		}
	}

	/**
	 * Delete a member.
	 *
	 * @since 1.0.0
	 * @param int  $member_id      Member ID.
	 * @param bool $delete_wp_user Whether to delete WordPress user too.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function delete($member_id, $delete_wp_user = false)
	{
		$member = $this->database->get_member($member_id);

		if (!$member) {
			return new WP_Error('member_not_found', __('Member not found.', 'member-registration-plugin'));
		}

		$user_id = $member->user_id;
		$was_admin = (int) $member->is_admin;

		// Check if this is the last member for this user.
		$member_count = $this->database->count_members_by_user_id($user_id);

		// Delete member from database.
		$result = $this->database->delete_member($member_id);

		if (!$result) {
			return new WP_Error('delete_failed', __('Failed to delete member.', 'member-registration-plugin'));
		}

		// If the deleted member was an admin, sync capabilities.
		// Pass 0 as the new value to trigger the check for other admin members.
		if ($was_admin) {
			$this->sync_user_admin_capability($user_id, 0);
		}

		// Delete WordPress user if requested and this was the last member.
		if ($delete_wp_user && $user_id && 1 === $member_count) {
			wp_delete_user($user_id);
		}

		/**
		 * Fires after a member is deleted.
		 *
		 * @since 1.0.0
		 * @param int $member_id Member ID.
		 * @param int $user_id   WordPress user ID.
		 */
		do_action('mbrreg_member_deleted', $member_id, $user_id);

		return true;
	}

	/**
	 * Set member as inactive.
	 *
	 * This method updates the status directly without validating other fields,
	 * allowing users to deactivate their membership even if required fields
	 * are not filled in for this specific action.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return array|WP_Error Result array on success, WP_Error on failure.
	 */
	public function set_inactive($member_id)
	{
		$member = $this->database->get_member($member_id);

		if (!$member) {
			return new WP_Error('member_not_found', __('Member not found.', 'member-registration-plugin'));
		}

		// Update status directly in database (bypass validation).
		$result = $this->database->update_member($member_id, array('status' => 'inactive'));

		if (false === $result) {
			return new WP_Error('update_failed', __('Failed to deactivate membership.', 'member-registration-plugin'));
		}

		// Check if this was the last active member for the user.
		$active_count = $this->database->count_members_by_user_id($member->user_id, 'active');

		/**
		 * Fires after a member is set to inactive.
		 *
		 * @since 1.2.1
		 * @param int $member_id Member ID.
		 * @param int $user_id   WordPress user ID.
		 */
		do_action('mbrreg_member_deactivated', $member_id, $member->user_id);

		// Return whether user should be logged out (no more active members).
		return array(
			'success' => true,
			'logout_user' => (0 === $active_count),
			'active_count' => $active_count,
		);
	}

	/**
	 * Set member as active.
	 *
	 * This method updates the status directly without validating other fields.
	 *
	 * @since 1.0.0
	 * @param int $member_id Member ID.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function set_active($member_id)
	{
		$member = $this->database->get_member($member_id);

		if (!$member) {
			return new WP_Error('member_not_found', __('Member not found.', 'member-registration-plugin'));
		}

		// Update status directly in database (bypass validation).
		$result = $this->database->update_member($member_id, array('status' => 'active'));

		if (false === $result) {
			return new WP_Error('update_failed', __('Failed to activate membership.', 'member-registration-plugin'));
		}

		/**
		 * Fires after a member is set to active.
		 *
		 * @since 1.2.1
		 * @param int $member_id Member ID.
		 * @param int $user_id   WordPress user ID.
		 */
		do_action('mbrreg_member_reactivated', $member_id, $member->user_id);

		return true;
	}

	/**
	 * Set member as admin.
	 *
	 * @since 1.0.0
	 * @param int  $member_id Member ID.
	 * @param bool $is_admin  Whether member is admin.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function set_admin($member_id, $is_admin = true)
	{
		$member = $this->database->get_member($member_id);

		if (!$member) {
			return new WP_Error('member_not_found', __('Member not found.', 'member-registration-plugin'));
		}

		$new_admin_value = $is_admin ? 1 : 0;
		$old_admin_value = (int) $member->is_admin;

		// Update the database.
		$result = $this->database->update_member($member_id, array('is_admin' => $new_admin_value));

		if (false === $result) {
			return new WP_Error('update_failed', __('Failed to update member admin status.', 'member-registration-plugin'));
		}

		// Sync WordPress user capabilities if the status changed.
		if ($new_admin_value !== $old_admin_value) {
			$this->sync_user_admin_capability($member->user_id, $new_admin_value);
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
	public function get($member_id)
	{
		$member = $this->database->get_member($member_id);

		if ($member) {
			$member = $this->enrich_member_data($member);
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
	public function get_by_user_id($user_id)
	{
		$member = $this->database->get_member_by_user_id($user_id);

		if ($member) {
			$member = $this->enrich_member_data($member);
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
	public function get_all_by_user_id($user_id)
	{
		$members = $this->database->get_members_by_user_id($user_id);

		foreach ($members as $key => $member) {
			$members[$key] = $this->enrich_member_data($member);
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
	public function get_all($args = array())
	{
		$members = $this->database->get_members($args);

		foreach ($members as $key => $member) {
			$members[$key] = $this->enrich_member_data($member);
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
	public function count($args = array())
	{
		return $this->database->count_members($args);
	}

	/**
	 * Enrich member data with WordPress user info and custom fields.
	 *
	 * @since 1.0.0
	 * @param object $member Member object.
	 * @return object Enriched member object.
	 */
	private function enrich_member_data($member)
	{
		// Get WordPress user.
		$user = get_user_by('ID', $member->user_id);

		if ($user) {
			$member->username = $user->user_login;
			$member->email = $user->user_email;
		} else {
			$member->username = '';
			$member->email = '';
		}

		// Get custom field values.
		$member->custom_fields = $this->database->get_member_meta($member->id);

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
	private function validate_member_data($data, $is_update = false)
	{
		// Check required standard fields (only first_name and last_name now).
		$required_fields = array(
			'first_name' => get_option('mbrreg_require_first_name', false),
			'last_name' => get_option('mbrreg_require_last_name', false),
		);

		foreach ($required_fields as $field => $is_required) {
			if ($is_required && (!isset($data[$field]) || '' === $data[$field])) {
				$label = ucwords(str_replace('_', ' ', $field));
				return new WP_Error(
					'missing_' . $field,
					/* translators: %s: Field name */
					sprintf(__('%s is required.', 'member-registration-plugin'), $label)
				);
			}
		}

		// Validate custom fields.
		$custom_fields = $this->custom_fields->get_all();

		foreach ($custom_fields as $field) {
			// Skip admin-only fields for regular users.
			if ($field->is_admin_only && !current_user_can('mbrreg_manage_members')) {
				continue;
			}

			$field_key = 'custom_' . $field->id;

			if ($field->is_required && (!isset($data[$field_key]) || '' === $data[$field_key])) {
				return new WP_Error(
					'missing_custom_' . $field->id,
					/* translators: %s: Field label */
					sprintf(__('%s is required.', 'member-registration-plugin'), $field->field_label)
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
	private function save_custom_field_values($member_id, $data, $is_admin_edit = false)
	{
		$custom_fields = $this->custom_fields->get_all();

		foreach ($custom_fields as $field) {
			// Skip admin-only fields for regular users.
			if ($field->is_admin_only && !$is_admin_edit) {
				continue;
			}

			$field_key = 'custom_' . $field->id;

			if (isset($data[$field_key])) {
				$value = $this->custom_fields->sanitize_field_value($field, $data[$field_key]);
				$this->database->update_member_meta($member_id, $field->id, $value);
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
	public function is_member_admin($user_id = null)
	{
		if (null === $user_id) {
			$user_id = get_current_user_id();
		}

		if (!$user_id) {
			return false;
		}

		// WordPress administrators always have access.
		if (user_can($user_id, 'manage_options')) {
			return true;
		}

		// Check if user has the capability (this is the authoritative check).
		if (user_can($user_id, 'mbrreg_manage_members')) {
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
	public function resend_activation_email($member_id)
	{
		$member = $this->database->get_member($member_id);

		if (!$member) {
			return new WP_Error('member_not_found', __('Member not found.', 'member-registration-plugin'));
		}

		if ('pending' !== $member->status) {
			return new WP_Error('already_active', __('This account is already activated.', 'member-registration-plugin'));
		}

		// Generate new activation key.
		$activation_key = wp_generate_password(32, false);

		// Update member with new key.
		$this->database->update_member($member_id, array('activation_key' => $activation_key));

		// Send email.
		$this->email->send_activation_email($member->user_id, $activation_key);

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
	public function can_manage_member($member_id, $user_id = null)
	{
		if (null === $user_id) {
			$user_id = get_current_user_id();
		}

		if (!$user_id) {
			return false;
		}

		// Admins can manage all members.
		if ($this->is_member_admin($user_id)) {
			return true;
		}

		// Check if member belongs to user.
		$member = $this->database->get_member($member_id);

		return $member && (int) $member->user_id === (int) $user_id;
	}

	/**
	 * Export members to CSV.
	 *
	 * @since 1.1.0
	 * @param array $args Query arguments.
	 * @return string CSV content.
	 */
	public function export_csv($args = array())
	{
		$members = $this->get_all($args);
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
		foreach ($custom_fields as $field) {
			$headers[] = $field->field_label;
		}

		// Start CSV output.
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
		$output = fopen('php://temp', 'r+');
		fputcsv($output, $headers);

		// Add member rows.
		foreach ($members as $member) {
			$row = array(
				$member->id,
				$member->username,
				$member->email,
				$member->first_name,
				$member->last_name,
				$member->status,
				$member->is_admin ? 'Yes' : 'No',
				mbrreg_format_date($member->created_at, 'Y-m-d H:i:s'),
			);

			// Add custom field values.
			foreach ($custom_fields as $field) {
				$value = isset($member->custom_fields[$field->id]) ? $member->custom_fields[$field->id] : '';
				// Format date fields for export.
				if ('date' === $field->field_type && !empty($value)) {
					$value = mbrreg_format_date($value);
				}
				$row[] = $value;
			}

			fputcsv($output, $row);
		}

		rewind($output);
		$csv = stream_get_contents($output);
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		fclose($output);

		return $csv;
	}

	/**
	 * Import members from CSV data.
	 *
	 * @since 1.1.0
	 * @param array $csv_data Parsed CSV data.
	 * @return array Import results.
	 */
	public function import_csv($csv_data)
	{
		$results = array(
			'success' => 0,
			'errors' => array(),
			'skipped' => 0,
		);

		foreach ($csv_data as $row_num => $row) {
			// Skip header row.
			if (0 === $row_num) {
				continue;
			}

			$data = $this->parse_csv_row($row);

			if (empty($data['email'])) {
				$results['errors'][] = sprintf(
					/* translators: %d: Row number */
					__('Row %d: Email is required.', 'member-registration-plugin'),
					$row_num + 1
				);
				continue;
			}

			$result = $this->register($data, true);

			if (is_wp_error($result)) {
				$results['errors'][] = sprintf(
					/* translators: 1: Row number, 2: Error message */
					__('Row %1$d: %2$s', 'member-registration-plugin'),
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
	private function parse_csv_row($row)
	{
		// Expected column order: Email, First Name, Last Name, then custom fields.
		$data = array(
			'email' => isset($row[0]) ? trim($row[0]) : '',
			'first_name' => isset($row[1]) ? trim($row[1]) : '',
			'last_name' => isset($row[2]) ? trim($row[2]) : '',
		);

		// Parse additional custom fields if present.
		$custom_fields = $this->custom_fields->get_all();
		$col_index = 3;

		foreach ($custom_fields as $field) {
			if (isset($row[$col_index])) {
				$value = trim($row[$col_index]);
				// Parse date fields from display format to database format.
				if ('date' === $field->field_type && !empty($value)) {
					$value = mbrreg_parse_date($value);
				}
				$data['custom_' . $field->id] = $value;
			}
			++$col_index;
		}

		return $data;
	}
}