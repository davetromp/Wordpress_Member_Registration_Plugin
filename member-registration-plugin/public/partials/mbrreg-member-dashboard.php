<?php
/**
 * Member dashboard template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/public/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get custom fields instance for rendering.
$custom_fields_handler = new Mbrreg_Custom_Fields();
?>

<div class="mbrreg-dashboard-container">
	<div class="mbrreg-dashboard-header">
		<h2>
			<?php
			printf(
				/* translators: %s: User display name */
				esc_html__( 'Welcome, %s!', 'member-registration-plugin' ),
				esc_html( $current_user->display_name )
			);
			?>
		</h2>

		<div class="mbrreg-dashboard-actions">
			<?php if ( $is_admin ) : ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members' ) ); ?>" class="mbrreg-button mbrreg-button-secondary">
					<?php esc_html_e( 'Manage Members', 'member-registration-plugin' ); ?>
				</a>
			<?php endif; ?>

			<button type="button" id="mbrreg-logout-btn" class="mbrreg-button mbrreg-button-secondary">
				<?php esc_html_e( 'Log Out', 'member-registration-plugin' ); ?>
			</button>
		</div>
	</div>

	<?php if ( $member ) : ?>
		<div class="mbrreg-dashboard-status">
			<span class="mbrreg-status mbrreg-status-<?php echo esc_attr( $member->status ); ?>">
				<?php echo esc_html( Mbrreg_Member::$statuses[ $member->status ] ); ?>
			</span>
		</div>

		<div class="mbrreg-form-messages"></div>

		<form id="mbrreg-profile-form" class="mbrreg-form" method="post">
			<h3 class="mbrreg-form-title"><?php esc_html_e( 'Your Profile', 'member-registration-plugin' ); ?></h3>

			<!-- Account Details (Read-only username) -->
			<fieldset class="mbrreg-fieldset">
				<legend><?php esc_html_e( 'Account Details', 'member-registration-plugin' ); ?></legend>

				<div class="mbrreg-form-row">
					<label><?php esc_html_e( 'Username', 'member-registration-plugin' ); ?></label>
					<input type="text" value="<?php echo esc_attr( $member->username ); ?>" disabled>
					<span class="mbrreg-field-note"><?php esc_html_e( 'Username cannot be changed.', 'member-registration-plugin' ); ?></span>
				</div>

				<div class="mbrreg-form-row">
					<label for="mbrreg-profile-email"><?php esc_html_e( 'Email Address', 'member-registration-plugin' ); ?> <span class="required">*</span></label>
					<input type="email" id="mbrreg-profile-email" name="email" value="<?php echo esc_attr( $member->email ); ?>" required>
				</div>
			</fieldset>

			<!-- Personal Details -->
			<fieldset class="mbrreg-fieldset">
				<legend><?php esc_html_e( 'Personal Details', 'member-registration-plugin' ); ?></legend>

				<div class="mbrreg-form-row">
					<label for="mbrreg-profile-first-name">
						<?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?>
						<?php if ( get_option( 'mbrreg_require_first_name', false ) ) : ?>
							<span class="required">*</span>
						<?php endif; ?>
					</label>
					<input type="text" id="mbrreg-profile-first-name" name="first_name" value="<?php echo esc_attr( $member->first_name ); ?>" <?php echo get_option( 'mbrreg_require_first_name', false ) ? 'required' : ''; ?>>
				</div>

				<div class="mbrreg-form-row">
					<label for="mbrreg-profile-last-name">
						<?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?>
						<?php if ( get_option( 'mbrreg_require_last_name', false ) ) : ?>
							<span class="required">*</span>
						<?php endif; ?>
					</label>
					<input type="text" id="mbrreg-profile-last-name" name="last_name" value="<?php echo esc_attr( $member->last_name ); ?>" <?php echo get_option( 'mbrreg_require_last_name', false ) ? 'required' : ''; ?>>
				</div>

				<div class="mbrreg-form-row">
					<label for="mbrreg-profile-address">
						<?php esc_html_e( 'Address', 'member-registration-plugin' ); ?>
						<?php if ( get_option( 'mbrreg_require_address', false ) ) : ?>
							<span class="required">*</span>
						<?php endif; ?>
					</label>
					<textarea id="mbrreg-profile-address" name="address" rows="3" <?php echo get_option( 'mbrreg_require_address', false ) ? 'required' : ''; ?>><?php echo esc_textarea( $member->address ); ?></textarea>
				</div>

				<div class="mbrreg-form-row">
					<label for="mbrreg-profile-telephone">
						<?php esc_html_e( 'Telephone Number', 'member-registration-plugin' ); ?>
						<?php if ( get_option( 'mbrreg_require_telephone', false ) ) : ?>
							<span class="required">*</span>
						<?php endif; ?>
					</label>
					<input type="tel" id="mbrreg-profile-telephone" name="telephone" value="<?php echo esc_attr( $member->telephone ); ?>" <?php echo get_option( 'mbrreg_require_telephone', false ) ? 'required' : ''; ?>>
				</div>

				<div class="mbrreg-form-row">
					<label for="mbrreg-profile-dob">
						<?php esc_html_e( 'Date of Birth', 'member-registration-plugin' ); ?>
						<?php if ( get_option( 'mbrreg_require_date_of_birth', false ) ) : ?>
							<span class="required">*</span>
						<?php endif; ?>
					</label>
					<input type="date" id="mbrreg-profile-dob" name="date_of_birth" value="<?php echo esc_attr( $member->date_of_birth ); ?>" <?php echo get_option( 'mbrreg_require_date_of_birth', false ) ? 'required' : ''; ?>>
				</div>

				<div class="mbrreg-form-row">
					<label for="mbrreg-profile-pob">
						<?php esc_html_e( 'Place of Birth', 'member-registration-plugin' ); ?>
						<?php if ( get_option( 'mbrreg_require_place_of_birth', false ) ) : ?>
							<span class="required">*</span>
						<?php endif; ?>
					</label>
					<input type="text" id="mbrreg-profile-pob" name="place_of_birth" value="<?php echo esc_attr( $member->place_of_birth ); ?>" <?php echo get_option( 'mbrreg_require_place_of_birth', false ) ? 'required' : ''; ?>>
				</div>
			</fieldset>

			<!-- Custom Fields -->
			<?php if ( ! empty( $custom_fields ) ) : ?>
				<fieldset class="mbrreg-fieldset">
					<legend><?php esc_html_e( 'Additional Information', 'member-registration-plugin' ); ?></legend>

					<?php foreach ( $custom_fields as $field ) : ?>
						<?php
						$value = isset( $member->custom_fields[ $field->id ] ) ? $member->custom_fields[ $field->id ] : '';
						?>
						<div class="mbrreg-form-row">
							<label for="mbrreg-custom-<?php echo esc_attr( $field->id ); ?>">
								<?php echo esc_html( $field->field_label ); ?>
								<?php if ( $field->is_required ) : ?>
									<span class="required">*</span>
								<?php endif; ?>
							</label>
							<?php echo $custom_fields_handler->render_field_input( $field, $value ); // phpcs:ignore ?>
						</div>
					<?php endforeach; ?>
				</fieldset>
			<?php endif; ?>

			<div class="mbrreg-form-row mbrreg-form-actions">
				<button type="submit" class="mbrreg-button mbrreg-button-primary">
					<?php esc_html_e( 'Update Profile', 'member-registration-plugin' ); ?>
				</button>
			</div>
		</form>

		<!-- Cancel Membership Section -->
		<div class="mbrreg-cancel-membership">
			<h4><?php esc_html_e( 'Cancel Membership', 'member-registration-plugin' ); ?></h4>
			<p><?php esc_html_e( 'If you no longer wish to be a member, you can set your account to inactive. You will be logged out and will need to contact an administrator to reactivate your account.', 'member-registration-plugin' ); ?></p>
			<button type="button" id="mbrreg-set-inactive-btn" class="mbrreg-button mbrreg-button-danger">
				<?php esc_html_e( 'Deactivate My Membership', 'member-registration-plugin' ); ?>
			</button>
		</div>
	<?php else : ?>
		<p class="mbrreg-message mbrreg-warning">
			<?php esc_html_e( 'No member profile found. Please contact an administrator.', 'member-registration-plugin' ); ?>
		</p>
	<?php endif; ?>
</div>