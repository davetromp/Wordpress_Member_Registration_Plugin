<?php
/**
 * Admin custom fields template.
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
	<hr class="wp-header-end">

	<div class="mbrreg-admin-messages"></div>

	<div class="mbrreg-admin-columns">
		<!-- Add New Field Form -->
		<div class="mbrreg-admin-column mbrreg-add-field-column">
			<div class="mbrreg-admin-box">
				<h2><?php esc_html_e( 'Add New Field', 'member-registration-plugin' ); ?></h2>

				<form id="mbrreg-add-field-form" class="mbrreg-admin-form">
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row">
									<label for="mbrreg-field-name"><?php esc_html_e( 'Field Name', 'member-registration-plugin' ); ?></label>
								</th>
								<td>
									<input type="text" name="field_name" id="mbrreg-field-name" class="regular-text" required pattern="[a-z0-9_]+">
									<p class="description"><?php esc_html_e( 'Lowercase letters, numbers, and underscores only.', 'member-registration-plugin' ); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="mbrreg-field-label"><?php esc_html_e( 'Field Label', 'member-registration-plugin' ); ?></label>
								</th>
								<td>
									<input type="text" name="field_label" id="mbrreg-field-label" class="regular-text" required>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="mbrreg-field-type"><?php esc_html_e( 'Field Type', 'member-registration-plugin' ); ?></label>
								</th>
								<td>
									<select name="field_type" id="mbrreg-field-type">
										<?php foreach ( $field_types as $type_key => $type_label ) : ?>
											<option value="<?php echo esc_attr( $type_key ); ?>">
												<?php echo esc_html( $type_label ); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr class="mbrreg-field-options-row" style="display: none;">
								<th scope="row">
									<label for="mbrreg-field-options"><?php esc_html_e( 'Options', 'member-registration-plugin' ); ?></label>
								</th>
								<td>
									<textarea name="field_options" id="mbrreg-field-options" rows="4" class="large-text"></textarea>
									<p class="description"><?php esc_html_e( 'Enter one option per line.', 'member-registration-plugin' ); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="mbrreg-field-required"><?php esc_html_e( 'Required', 'member-registration-plugin' ); ?></label>
								</th>
								<td>
									<label>
										<input type="checkbox" name="is_required" id="mbrreg-field-required" value="1">
										<?php esc_html_e( 'Make this field required', 'member-registration-plugin' ); ?>
									</label>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="mbrreg-field-order"><?php esc_html_e( 'Display Order', 'member-registration-plugin' ); ?></label>
								</th>
								<td>
									<input type="number" name="field_order" id="mbrreg-field-order" value="0" min="0" class="small-text">
								</td>
							</tr>
						</tbody>
					</table>

					<p class="submit">
						<button type="submit" class="button button-primary">
							<?php esc_html_e( 'Add Field', 'member-registration-plugin' ); ?>
						</button>
					</p>
				</form>
			</div>
		</div>

		<!-- Existing Fields List -->
		<div class="mbrreg-admin-column mbrreg-fields-list-column">
			<div class="mbrreg-admin-box">
				<h2><?php esc_html_e( 'Existing Fields', 'member-registration-plugin' ); ?></h2>

				<?php if ( empty( $custom_fields ) ) : ?>
					<p><?php esc_html_e( 'No custom fields created yet.', 'member-registration-plugin' ); ?></p>
				<?php else : ?>
					<table class="wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Label', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Name', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Type', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Required', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Order', 'member-registration-plugin' ); ?></th>
								<th><?php esc_html_e( 'Actions', 'member-registration-plugin' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $custom_fields as $field ) : ?>
								<tr data-field-id="<?php echo esc_attr( $field->id ); ?>">
									<td>
										<strong><?php echo esc_html( $field->field_label ); ?></strong>
									</td>
									<td>
										<code><?php echo esc_html( $field->field_name ); ?></code>
									</td>
									<td>
										<?php echo esc_html( isset( $field_types[ $field->field_type ] ) ? $field_types[ $field->field_type ] : $field->field_type ); ?>
									</td>
									<td>
										<?php if ( $field->is_required ) : ?>
											<span class="dashicons dashicons-yes-alt" style="color: #46b450;"></span>
										<?php else : ?>
											<span class="dashicons dashicons-minus" style="color: #999;"></span>
										<?php endif; ?>
									</td>
									<td><?php echo esc_html( $field->field_order ); ?></td>
									<td>
										<button type="button" class="button button-small mbrreg-edit-field" 
											data-field-id="<?php echo esc_attr( $field->id ); ?>"
											data-field-label="<?php echo esc_attr( $field->field_label ); ?>"
											data-field-type="<?php echo esc_attr( $field->field_type ); ?>"
											data-field-options="<?php echo esc_attr( $field->field_options ); ?>"
											data-is-required="<?php echo esc_attr( $field->is_required ); ?>"
											data-field-order="<?php echo esc_attr( $field->field_order ); ?>">
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
				<?php endif; ?>
			</div>
		</div>
	</div>

	<!-- Edit Field Modal -->
	<div id="mbrreg-edit-field-modal" class="mbrreg-modal" style="display: none;">
		<div class="mbrreg-modal-content">
			<span class="mbrreg-modal-close">&times;</span>
			<h2><?php esc_html_e( 'Edit Field', 'member-registration-plugin' ); ?></h2>

			<form id="mbrreg-edit-field-form" class="mbrreg-admin-form">
				<input type="hidden" name="field_id" id="mbrreg-edit-field-id">

				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<label for="mbrreg-edit-field-label"><?php esc_html_e( 'Field Label', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<input type="text" name="field_label" id="mbrreg-edit-field-label" class="regular-text" required>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="mbrreg-edit-field-type"><?php esc_html_e( 'Field Type', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<select name="field_type" id="mbrreg-edit-field-type">
									<?php foreach ( $field_types as $type_key => $type_label ) : ?>
										<option value="<?php echo esc_attr( $type_key ); ?>">
											<?php echo esc_html( $type_label ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr class="mbrreg-edit-field-options-row" style="display: none;">
							<th scope="row">
								<label for="mbrreg-edit-field-options"><?php esc_html_e( 'Options', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<textarea name="field_options" id="mbrreg-edit-field-options" rows="4" class="large-text"></textarea>
								<p class="description"><?php esc_html_e( 'Enter one option per line.', 'member-registration-plugin' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="mbrreg-edit-field-required"><?php esc_html_e( 'Required', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<label>
									<input type="checkbox" name="is_required" id="mbrreg-edit-field-required" value="1">
									<?php esc_html_e( 'Make this field required', 'member-registration-plugin' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="mbrreg-edit-field-order"><?php esc_html_e( 'Display Order', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<input type="number" name="field_order" id="mbrreg-edit-field-order" value="0" min="0" class="small-text">
							</td>
						</tr>
					</tbody>
				</table>

				<p class="submit">
					<button type="submit" class="button button-primary">
						<?php esc_html_e( 'Update Field', 'member-registration-plugin' ); ?>
					</button>
					<button type="button" class="button mbrreg-modal-cancel">
						<?php esc_html_e( 'Cancel', 'member-registration-plugin' ); ?>
					</button>
				</p>
			</form>
		</div>
	</div>
</div>