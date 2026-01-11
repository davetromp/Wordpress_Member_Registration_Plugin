<?php
/**
 * Admin custom fields page template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/admin/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<div class="wrap mbrreg-admin-wrap">
	<h1><?php esc_html_e( 'Custom Fields', 'member-registration-plugin' ); ?></h1>

	<div class="mbrreg-admin-columns">
		<!-- Fields List -->
		<div class="mbrreg-admin-column mbrreg-admin-column-wide">
			<div class="mbrreg-admin-card">
				<h2><?php esc_html_e( 'Existing Fields', 'member-registration-plugin' ); ?></h2>

				<?php if ( ! empty( $custom_fields ) ) : ?>
					<table class="widefat mbrreg-fields-table">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Order', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Name', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Label', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Type', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Required', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Admin Only', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Actions', 'member-registration-plugin' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $custom_fields as $field ) : ?>
								<tr data-field-id="<?php echo esc_attr( $field->id ); ?>">
									<td><?php echo esc_html( $field->field_order ); ?></td>
									<td><code><?php echo esc_html( $field->field_name ); ?></code></td>
									<td><?php echo esc_html( $field->field_label ); ?></td>
									<td><?php echo esc_html( Mbrreg_Custom_Fields::$field_types[ $field->field_type ] ); ?></td>
									<td>
										<?php if ( $field->is_required ) : ?>
											<span class="dashicons dashicons-yes-alt" style="color: green;"></span>
										<?php else : ?>
											<span class="dashicons dashicons-minus" style="color: #999;"></span>
										<?php endif; ?>
									</td>
									<td>
										<?php if ( $field->is_admin_only ) : ?>
											<span class="dashicons dashicons-lock" style="color: #d63638;" title="<?php esc_attr_e( 'Admin only - users cannot edit', 'member-registration-plugin' ); ?>"></span>
										<?php else : ?>
											<span class="dashicons dashicons-unlock" style="color: #999;" title="<?php esc_attr_e( 'Editable by users', 'member-registration-plugin' ); ?>"></span>
										<?php endif; ?>
									</td>
									<td>
										<button type="button" class="button button-small mbrreg-edit-field" data-field-id="<?php echo esc_attr( $field->id ); ?>">
											<?php esc_html_e( 'Edit', 'member-registration-plugin' ); ?>
										</button>
										<button type="button" class="button button-small button-link-delete mbrreg-delete-field" data-field-id="<?php echo esc_attr( $field->id ); ?>">
											<?php esc_html_e( 'Delete', 'member-registration-plugin' ); ?>
										</button>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php else : ?>
					<p class="mbrreg-empty-message"><?php esc_html_e( 'No custom fields have been created yet.', 'member-registration-plugin' ); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<!-- Add New Field -->
		<div class="mbrreg-admin-column">
			<div class="mbrreg-admin-card">
				<h2><?php esc_html_e( 'Add New Field', 'member-registration-plugin' ); ?></h2>

				<form id="mbrreg-add-field-form" class="mbrreg-admin-form">
					<div class="mbrreg-form-row">
						<label for="field_name"><?php esc_html_e( 'Field Name', 'member-registration-plugin' ); ?></label>
						<input type="text" name="field_name" id="field_name" required pattern="[a-z0-9_]+" title="<?php esc_attr_e( 'Lowercase letters, numbers, and underscores only', 'member-registration-plugin' ); ?>">
						<p class="description"><?php esc_html_e( 'Unique identifier. Lowercase letters, numbers, and underscores only.', 'member-registration-plugin' ); ?></p>
					</div>

					<div class="mbrreg-form-row">
						<label for="field_label"><?php esc_html_e( 'Field Label', 'member-registration-plugin' ); ?></label>
						<input type="text" name="field_label" id="field_label" required>
						<p class="description"><?php esc_html_e( 'The label displayed to users.', 'member-registration-plugin' ); ?></p>
					</div>

					<div class="mbrreg-form-row">
						<label for="field_type"><?php esc_html_e( 'Field Type', 'member-registration-plugin' ); ?></label>
						<select name="field_type" id="field_type">
							<?php foreach ( Mbrreg_Custom_Fields::$field_types as $type => $label ) : ?>
								<option value="<?php echo esc_attr( $type ); ?>"><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="mbrreg-form-row mbrreg-field-options-row" style="display: none;">
						<label for="field_options"><?php esc_html_e( 'Options', 'member-registration-plugin' ); ?></label>
						<textarea name="field_options" id="field_options" rows="4"></textarea>
						<p class="description"><?php esc_html_e( 'Enter one option per line.', 'member-registration-plugin' ); ?></p>
					</div>

					<div class="mbrreg-form-row">
						<label for="field_order"><?php esc_html_e( 'Display Order', 'member-registration-plugin' ); ?></label>
						<input type="number" name="field_order" id="field_order" value="0" min="0">
						<p class="description"><?php esc_html_e( 'Lower numbers display first.', 'member-registration-plugin' ); ?></p>
					</div>

					<div class="mbrreg-form-row">
						<label>
							<input type="checkbox" name="is_required" value="1">
							<?php esc_html_e( 'Required field', 'member-registration-plugin' ); ?>
						</label>
					</div>

					<div class="mbrreg-form-row">
						<label>
							<input type="checkbox" name="is_admin_only" value="1">
							<?php esc_html_e( 'Admin only (users cannot edit)', 'member-registration-plugin' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'If checked, only administrators can view and edit this field. Users will see the value but cannot change it.', 'member-registration-plugin' ); ?></p>
					</div>

					<div class="mbrreg-form-row">
						<button type="submit" class="button button-primary"><?php esc_html_e( 'Add Field', 'member-registration-plugin' ); ?></button>
						<span class="spinner"></span>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Edit Field Modal -->
	<div id="mbrreg-edit-field-modal" class="mbrreg-modal" style="display: none;">
		<div class="mbrreg-modal-content">
			<div class="mbrreg-modal-header">
				<h2><?php esc_html_e( 'Edit Custom Field', 'member-registration-plugin' ); ?></h2>
				<button type="button" class="mbrreg-modal-close">&times;</button>
			</div>
			<div class="mbrreg-modal-body">
				<form id="mbrreg-edit-field-form" class="mbrreg-admin-form">
					<input type="hidden" name="field_id" id="edit_field_id">

					<div class="mbrreg-form-row">
						<label for="edit_field_label"><?php esc_html_e( 'Field Label', 'member-registration-plugin' ); ?></label>
						<input type="text" name="field_label" id="edit_field_label" required>
					</div>

					<div class="mbrreg-form-row">
						<label for="edit_field_type"><?php esc_html_e( 'Field Type', 'member-registration-plugin' ); ?></label>
						<select name="field_type" id="edit_field_type">
							<?php foreach ( Mbrreg_Custom_Fields::$field_types as $type => $label ) : ?>
								<option value="<?php echo esc_attr( $type ); ?>"><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="mbrreg-form-row mbrreg-edit-field-options-row" style="display: none;">
						<label for="edit_field_options"><?php esc_html_e( 'Options', 'member-registration-plugin' ); ?></label>
						<textarea name="field_options" id="edit_field_options" rows="4"></textarea>
						<p class="description"><?php esc_html_e( 'Enter one option per line.', 'member-registration-plugin' ); ?></p>
					</div>

					<div class="mbrreg-form-row">
						<label for="edit_field_order"><?php esc_html_e( 'Display Order', 'member-registration-plugin' ); ?></label>
						<input type="number" name="field_order" id="edit_field_order" value="0" min="0">
					</div>

					<div class="mbrreg-form-row">
						<label>
							<input type="checkbox" name="is_required" id="edit_is_required" value="1">
							<?php esc_html_e( 'Required field', 'member-registration-plugin' ); ?>
						</label>
					</div>

					<div class="mbrreg-form-row">
						<label>
							<input type="checkbox" name="is_admin_only" id="edit_is_admin_only" value="1">
							<?php esc_html_e( 'Admin only (users cannot edit)', 'member-registration-plugin' ); ?>
						</label>
					</div>

					<div class="mbrreg-form-row">
						<button type="submit" class="button button-primary"><?php esc_html_e( 'Update Field', 'member-registration-plugin' ); ?></button>
						<button type="button" class="button mbrreg-modal-close"><?php esc_html_e( 'Cancel', 'member-registration-plugin' ); ?></button>
						<span class="spinner"></span>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
// Store field data for editing.
var mbrregFieldData = <?php echo wp_json_encode( $custom_fields ); ?>;
</script>