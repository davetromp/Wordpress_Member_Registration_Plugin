<?php
/**
 * Admin member edit page template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/admin/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get custom fields handler for rendering.
$custom_fields_handler = new Mbrreg_Custom_Fields();

// Count other members in this account.
$database      = new Mbrreg_Database();
$account_members = $database->get_members_by_user_id( $member->user_id );
?>

<div class="wrap mbrreg-admin-wrap">
	<h1>
		<?php esc_html_e( 'Edit Member', 'member-registration-plugin' ); ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members' ) ); ?>" class="page-title-action">
			<?php esc_html_e( 'Back to Members', 'member-registration-plugin' ); ?>
		</a>
	</h1>

	<div id="mbrreg-admin-messages"></div>

	<?php if ( count( $account_members ) > 1 ) : ?>
		<div class="notice notice-info">
			<p>
				<?php
				printf(
					/* translators: 1: Number of members, 2: Account email */
					esc_html__( 'This user account (%2$s) has %1$d registered members.', 'member-registration-plugin' ),
					count( $account_members ),
					esc_html( $member->email )
				);
				?>
			</p>
		</div>
	<?php endif; ?>

	<form id="mbrreg-edit-member-form" method="post" class="mbrreg-admin-form">
		<input type="hidden" name="member_id" value="<?php echo esc_attr( $member->id ); ?>">

		<div class="mbrreg-admin-columns">
			<!-- Main Content -->
			<div class="mbrreg-admin-column mbrreg-admin-column-wide">
				<div class="mbrreg-admin-card">
					<h2><?php esc_html_e( 'Personal Information', 'member-registration-plugin' ); ?></h2>

					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="first_name"><?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $member->first_name ); ?>" class="regular-text">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="last_name"><?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $member->last_name ); ?>" class="regular-text">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="email"><?php esc_html_e( 'Email', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<input type="email" name="email" id="email" value="<?php echo esc_attr( $member->email ); ?>" class="regular-text">
								<p class="description"><?php esc_html_e( 'Changing this will update the WordPress user email for the account owner.', 'member-registration-plugin' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="address"><?php esc_html_e( 'Address', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<textarea name="address" id="address" rows="3" class="large-text"><?php echo esc_textarea( $member->address ); ?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="telephone"><?php esc_html_e( 'Telephone', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<input type="text" name="telephone" id="telephone" value="<?php echo esc_attr( $member->telephone ); ?>" class="regular-text">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="date_of_birth"><?php esc_html_e( 'Date of Birth', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<input type="date" name="date_of_birth" id="date_of_birth" value="<?php echo esc_attr( $member->date_of_birth ); ?>">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="place_of_birth"><?php esc_html_e( 'Place of Birth', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<input type="text" name="place_of_birth" id="place_of_birth" value="<?php echo esc_attr( $member->place_of_birth ); ?>" class="regular-text">
							</td>
						</tr>
					</table>
				</div>

				<!-- Custom Fields -->
				<?php if ( ! empty( $custom_fields ) ) : ?>
					<div class="mbrreg-admin-card">
						<h2><?php esc_html_e( 'Custom Fields', 'member-registration-plugin' ); ?></h2>
						<table class="form-table">
							<?php foreach ( $custom_fields as $field ) : ?>
								<?php $value = isset( $member->custom_fields[ $field->id ] ) ? $member->custom_fields[ $field->id ] : ''; ?>
								<tr>
									<th scope="row">
										<label for="custom_<?php echo esc_attr( $field->id ); ?>">
											<?php echo esc_html( $field->field_label ); ?>
											<?php if ( $field->is_admin_only ) : ?>
												<span class="dashicons dashicons-lock" style="color: #d63638; font-size: 14px;" title="<?php esc_attr_e( 'Admin only', 'member-registration-plugin' ); ?>"></span>
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
										<?php if ( $field->is_admin_only ) : ?>
											<p class="description"><?php esc_html_e( 'This field can only be edited by administrators.', 'member-registration-plugin' ); ?></p>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</table>
					</div>
				<?php endif; ?>
			</div>

			<!-- Sidebar -->
			<div class="mbrreg-admin-column">
				<div class="mbrreg-admin-card">
					<h2><?php esc_html_e( 'Member Status', 'member-registration-plugin' ); ?></h2>

					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="status"><?php esc_html_e( 'Status', 'member-registration-plugin' ); ?></label>
							</th>
							<td>
								<select name="status" id="status">
									<?php foreach ( Mbrreg_Member::$statuses as $value => $label ) : ?>
										<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $member->status, $value ); ?>>
											<?php echo esc_html( $label ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Member Admin', 'member-registration-plugin' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="is_admin" value="1" <?php checked( $member->is_admin ); ?>>
									<?php esc_html_e( 'Can manage members', 'member-registration-plugin' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'Member admins can view and manage other members from the frontend.', 'member-registration-plugin' ); ?></p>
							</td>
						</tr>
					</table>
				</div>

				<div class="mbrreg-admin-card">
					<h2><?php esc_html_e( 'Account Information', 'member-registration-plugin' ); ?></h2>
					<table class="form-table">
						<tr>
							<th scope="row"><?php esc_html_e( 'Username', 'member-registration-plugin' ); ?></th>
							<td><code><?php echo esc_html( $member->username ); ?></code></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Member ID', 'member-registration-plugin' ); ?></th>
							<td><?php echo esc_html( $member->id ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'WP User ID', 'member-registration-plugin' ); ?></th>
							<td>
								<a href="<?php echo esc_url( admin_url( 'user-edit.php?user_id=' . $member->user_id ) ); ?>">
									<?php echo esc_html( $member->user_id ); ?>
								</a>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Registered', 'member-registration-plugin' ); ?></th>
							<td><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $member->created_at ) ) ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Last Updated', 'member-registration-plugin' ); ?></th>
							<td><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $member->updated_at ) ) ); ?></td>
						</tr>
					</table>
				</div>

				<div class="mbrreg-admin-card">
					<h2><?php esc_html_e( 'Actions', 'member-registration-plugin' ); ?></h2>
					<p>
						<button type="submit" class="button button-primary button-large"><?php esc_html_e( 'Update Member', 'member-registration-plugin' ); ?></button>
						<span class="spinner"></span>
					</p>
					<p>
						<button type="button" class="button button-link-delete mbrreg-delete-member" data-member-id="<?php echo esc_attr( $member->id ); ?>">
							<?php esc_html_e( 'Delete Member', 'member-registration-plugin' ); ?>
						</button>
					</p>

					<?php if ( 'pending' === $member->status ) : ?>
						<p>
							<button type="button" class="button mbrreg-resend-activation" data-member-id="<?php echo esc_attr( $member->id ); ?>">
								<?php esc_html_e( 'Resend Activation Email', 'member-registration-plugin' ); ?>
							</button>
						</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</form>
</div>