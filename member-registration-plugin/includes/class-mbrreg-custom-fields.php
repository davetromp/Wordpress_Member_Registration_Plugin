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