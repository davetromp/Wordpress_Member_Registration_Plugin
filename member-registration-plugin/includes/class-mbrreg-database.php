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