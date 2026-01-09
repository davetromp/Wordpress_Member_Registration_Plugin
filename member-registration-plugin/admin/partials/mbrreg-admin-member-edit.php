<?php
/**
 * Admin member edit template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/admin/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! $member ) {
	wp_die( esc_html__( 'Member not found.', 'member-registration-plugin' ) );
}

// Get custom fields instance for rendering.
$custom_fields_handler = new Mbrreg_Custom_Fields();
?>

<div class="wrap mbrreg-admin-wrap">
	<h1>
		<?php esc_html_e( 'Edit Member', 'member-registration-plugin' ); ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members' ) ); ?>" class="page-title-action">
			<?php esc_html_e( 'Back to Members', 'member-registration-plugin' ); ?>
		</a>
	</h1>
	<hr class="wp-header-end">

	<div class="mbrreg-admin-messages"></div>

	<form id="mbrreg-admin-member-form" class="mbrreg-admin-form" method="post">
		<input type="hidden" name="member_id" value="<?php echo esc_attr( $member->id ); ?>">

		<table class="form-table">
			<tbody>
				<!-- Username (Read-only) -->
				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Username', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" value="<?php echo esc_attr( $member->username ); ?>" class="regular-text" disabled>
						<p class="description"><?php esc_html_e( 'Username cannot be changed.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>

				<!-- Email -->
				<tr>
					<th scope="row">
						<label for="mbrreg-email"><?php esc_html_e( 'Email Address', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="email" name="email" id="mbrreg-email" value="<?php echo esc_attr( $member->email ); ?>" class="regular-text" required>
					</td>
				</tr>

				<!-- Status -->
				<tr>
					<th scope="row">
						<label for="mbrreg-status"><?php esc_html_e( 'Status', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<select name="status" id="mbrreg-status">
							<?php foreach ( Mbrreg_Member::$statuses as $status_key => $status_label ) : ?>
								<option value="<?php echo esc_attr( $status_key ); ?>" <?php selected( $member->status, $status_key ); ?>>
									<?php echo esc_html( $status_label ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>

				<!-- Admin Status -->
				<tr>
					<th scope="row">
						<label for="mbrreg-is-admin"><?php esc_html_e( 'Plugin Administrator', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<label>
							<input type="checkbox" name="is_admin" id="mbrreg-is-admin" value="1" <?php checked( $member->is_admin, 1 ); ?>>
							<?php esc_html_e( 'Grant this member administrator access to manage other members', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>

				<!-- First Name -->
				<tr>
					<th scope="row">
						<label for="mbrreg-first-name"><?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" name="first_name" id="mbrreg-first-name" value="<?php echo esc_attr( $member->first_name ); ?>" class="regular-text">
					</td>
				</tr>

				<!-- Last Name -->
				<tr>
					<th scope="row">
						<label for="mbrreg-last-name"><?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" name="last_name" id="mbrreg-last-name" value="<?php echo esc_attr( $member->last_name ); ?>" class="regular-text">
					</td>
				</tr>

				<!-- Address -->
				<tr>
					<th scope="row">
						<label for="mbrreg-address"><?php esc_html_e( 'Address', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<textarea name="address" id="mbrreg-address" rows="3" class="large-text"><?php echo esc_textarea( $member->address ); ?></textarea>
					</td>
				</tr>

				<!-- Telephone -->
				<tr>
					<th scope="row">
						<label for="mbrreg-telephone"><?php esc_html_e( 'Telephone Number', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="tel" name="telephone" id="mbrreg-telephone" value="<?php echo esc_attr( $member->telephone ); ?>" class="regular-text">
					</td>
				</tr>

				<!-- Date of Birth -->
				<tr>
					<th scope="row">
						<label for="mbrreg-dob"><?php esc_html_e( 'Date of Birth', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="date" name="date_of_birth" id="mbrreg-dob" value="<?php echo esc_attr( $member->date_of_birth ); ?>">
					</td>
				</tr>

				<!-- Place of Birth -->
				<tr>
					<th scope="row">
						<label for="mbrreg-pob"><?php esc_html_e( 'Place of Birth', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" name="place_of_birth" id="mbrreg-pob" value="<?php echo esc_attr( $member->place_of_birth ); ?>" class="regular-text">
					</td>
				</tr>

				<!-- Custom Fields -->
				<?php if ( ! empty( $custom_fields ) ) : ?>
					<?php foreach ( $custom_fields as $field ) : ?>
						<?php
						$value = isset( $member->custom_fields[ $field->id ] ) ? $member->custom_fields[ $field->id ] : '';
						?>
						<tr>
							<th scope="row">
								<label for="mbrreg-custom-<?php echo esc_attr( $field->id ); ?>">
									<?php echo esc_html( $field->field_label ); ?>
									<?php if ( $field->is_required ) : ?>
										<span class="required">*</span>
									<?php endif; ?>
								</label>
							</th>
							<td>
								<?php
								echo $custom_fields_handler->render_field_input( // phpcs:ignore
									$field,
									$value,
									array( 'class' => 'regular-text' )
								);
								?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>

				<!-- Registration Date (Read-only) -->
				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Registered', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" value="<?php echo esc_attr( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $member->created_at ) ) ); ?>" class="regular-text" disabled>
					</td>
				</tr>

				<!-- Last Updated (Read-only) -->
				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Last Updated', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" value="<?php echo esc_attr( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $member->updated_at ) ) ); ?>" class="regular-text" disabled>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<button type="submit" class="button button-primary">
				<?php esc_html_e( 'Update Member', 'member-registration-plugin' ); ?>
			</button>
			<button type="button" class="button button-link-delete mbrreg-delete-member" data-member-id="<?php echo esc_attr( $member->id ); ?>">
				<?php esc_html_e( 'Delete Member', 'member-registration-plugin' ); ?>
			</button>
		</p>
	</form>
</div>