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
$statuses              = Mbrreg_Member::get_statuses();
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

			<button type="button" class="mbrreg-button mbrreg-button-secondary mbrreg-logout-btn">
				<?php esc_html_e( 'Log Out', 'member-registration-plugin' ); ?>
			</button>
		</div>
	</div>

	<div class="mbrreg-form-messages"></div>

	<?php if ( ! empty( $members ) ) : ?>
		<div class="mbrreg-account-info">
			<p>
				<strong><?php esc_html_e( 'Account Email:', 'member-registration-plugin' ); ?></strong>
				<?php echo esc_html( $current_user->user_email ); ?>
			</p>
			<p>
				<strong><?php esc_html_e( 'Registered Members:', 'member-registration-plugin' ); ?></strong>
				<?php echo esc_html( count( $members ) ); ?>
			</p>
		</div>

		<?php foreach ( $members as $index => $member ) : ?>
			<div class="mbrreg-member-card" data-member-id="<?php echo esc_attr( $member->id ); ?>">
				<div class="mbrreg-member-card-header">
					<h3>
						<?php
						$member_name = trim( $member->first_name . ' ' . $member->last_name );
						if ( empty( $member_name ) ) {
							$member_name = sprintf(
								/* translators: %d: Member number */
								__( 'Member #%d', 'member-registration-plugin' ),
								$index + 1
							);
						}
						echo esc_html( $member_name );
						?>
					</h3>
					<span class="mbrreg-status mbrreg-status-<?php echo esc_attr( $member->status ); ?>">
						<?php echo esc_html( $statuses[ $member->status ] ); ?>
					</span>
				</div>

				<form class="mbrreg-profile-form mbrreg-form" method="post" data-member-id="<?php echo esc_attr( $member->id ); ?>">
					<input type="hidden" name="member_id" value="<?php echo esc_attr( $member->id ); ?>">

					<!-- Personal Details (only first_name and last_name) -->
					<fieldset class="mbrreg-fieldset">
						<legend><?php esc_html_e( 'Personal Details', 'member-registration-plugin' ); ?></legend>

						<div class="mbrreg-form-row">
							<label for="mbrreg-first-name-<?php echo esc_attr( $member->id ); ?>">
								<?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?>
								<?php if ( get_option( 'mbrreg_require_first_name', false ) ) : ?>
									<span class="required">*</span>
								<?php endif; ?>
							</label>
							<input type="text" id="mbrreg-first-name-<?php echo esc_attr( $member->id ); ?>" name="first_name" value="<?php echo esc_attr( $member->first_name ); ?>" <?php echo get_option( 'mbrreg_require_first_name', false ) ? 'required' : ''; ?>>
						</div>

						<div class="mbrreg-form-row">
							<label for="mbrreg-last-name-<?php echo esc_attr( $member->id ); ?>">
								<?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?>
								<?php if ( get_option( 'mbrreg_require_last_name', false ) ) : ?>
									<span class="required">*</span>
								<?php endif; ?>
							</label>
							<input type="text" id="mbrreg-last-name-<?php echo esc_attr( $member->id ); ?>" name="last_name" value="<?php echo esc_attr( $member->last_name ); ?>" <?php echo get_option( 'mbrreg_require_last_name', false ) ? 'required' : ''; ?>>
						</div>
					</fieldset>

					<!-- Custom Fields -->
					<?php if ( ! empty( $custom_fields ) ) : ?>
						<fieldset class="mbrreg-fieldset">
							<legend><?php esc_html_e( 'Additional Information', 'member-registration-plugin' ); ?></legend>

							<?php foreach ( $custom_fields as $field ) : ?>
								<?php
								$value       = isset( $member->custom_fields[ $field->id ] ) ? $member->custom_fields[ $field->id ] : '';
								$is_readonly = $field->is_admin_only;
								?>
								<div class="mbrreg-form-row">
									<label for="mbrreg-custom-<?php echo esc_attr( $field->id ); ?>-<?php echo esc_attr( $member->id ); ?>">
										<?php echo esc_html( $field->field_label ); ?>
										<?php if ( $field->is_required && ! $is_readonly ) : ?>
											<span class="required">*</span>
										<?php endif; ?>
										<?php if ( $is_readonly ) : ?>
											<span class="mbrreg-readonly-badge"><?php esc_html_e( '(View only)', 'member-registration-plugin' ); ?></span>
										<?php endif; ?>
									</label>
									<?php
									echo $custom_fields_handler->render_field_input( // phpcs:ignore
										$field,
										$value,
										array(
											'id_prefix' => 'mbrreg-custom-' . $member->id . '-',
											'readonly'  => $is_readonly,
										)
									);
									?>
								</div>
							<?php endforeach; ?>
						</fieldset>
					<?php endif; ?>

					<div class="mbrreg-form-row mbrreg-form-actions">
						<button type="submit" class="mbrreg-button mbrreg-button-primary">
							<?php esc_html_e( 'Update Profile', 'member-registration-plugin' ); ?>
						</button>

						<?php if ( 'active' === $member->status ) : ?>
							<button type="button" class="mbrreg-button mbrreg-button-danger mbrreg-deactivate-btn" data-member-id="<?php echo esc_attr( $member->id ); ?>">
								<?php esc_html_e( 'Deactivate Membership', 'member-registration-plugin' ); ?>
							</button>
						<?php endif; ?>
					</div>
				</form>
			</div>
		<?php endforeach; ?>

		<!-- Add Another Member Section -->
		<?php if ( $allow_multiple ) : ?>
			<div class="mbrreg-add-member-section">
				<button type="button" class="mbrreg-button mbrreg-button-secondary mbrreg-toggle-add-member">
					<?php esc_html_e( '+ Add Another Member', 'member-registration-plugin' ); ?>
				</button>

				<div class="mbrreg-add-member-form-container" style="display: none;">
					<h3><?php esc_html_e( 'Add New Member', 'member-registration-plugin' ); ?></h3>
					<p class="mbrreg-info"><?php esc_html_e( 'Add another family member to your account.', 'member-registration-plugin' ); ?></p>

					<form id="mbrreg-add-member-form" class="mbrreg-form" method="post">
						<fieldset class="mbrreg-fieldset">
							<legend><?php esc_html_e( 'Personal Details', 'member-registration-plugin' ); ?></legend>

							<div class="mbrreg-form-row">
								<label for="mbrreg-add-first-name">
									<?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?>
									<?php if ( get_option( 'mbrreg_require_first_name', false ) ) : ?>
										<span class="required">*</span>
									<?php endif; ?>
								</label>
								<input type="text" id="mbrreg-add-first-name" name="first_name" <?php echo get_option( 'mbrreg_require_first_name', false ) ? 'required' : ''; ?>>
							</div>

							<div class="mbrreg-form-row">
								<label for="mbrreg-add-last-name">
									<?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?>
									<?php if ( get_option( 'mbrreg_require_last_name', false ) ) : ?>
										<span class="required">*</span>
									<?php endif; ?>
								</label>
								<input type="text" id="mbrreg-add-last-name" name="last_name" <?php echo get_option( 'mbrreg_require_last_name', false ) ? 'required' : ''; ?>>
							</div>
						</fieldset>

						<!-- Custom Fields for new member -->
						<?php if ( ! empty( $user_editable_fields ) ) : ?>
							<fieldset class="mbrreg-fieldset">
								<legend><?php esc_html_e( 'Additional Information', 'member-registration-plugin' ); ?></legend>

								<?php foreach ( $user_editable_fields as $field ) : ?>
									<div class="mbrreg-form-row">
										<label for="mbrreg-add-custom-<?php echo esc_attr( $field->id ); ?>">
											<?php echo esc_html( $field->field_label ); ?>
											<?php if ( $field->is_required ) : ?>
												<span class="required">*</span>
											<?php endif; ?>
										</label>
										<?php
										echo $custom_fields_handler->render_field_input( // phpcs:ignore
											$field,
											'',
											array( 'id_prefix' => 'mbrreg-add-custom-' )
										);
										?>
									</div>
								<?php endforeach; ?>
							</fieldset>
						<?php endif; ?>

						<div class="mbrreg-form-row mbrreg-form-actions">
							<button type="submit" class="mbrreg-button mbrreg-button-primary">
								<?php esc_html_e( 'Add Member', 'member-registration-plugin' ); ?>
							</button>
							<button type="button" class="mbrreg-button mbrreg-button-secondary mbrreg-cancel-add-member">
								<?php esc_html_e( 'Cancel', 'member-registration-plugin' ); ?>
							</button>
						</div>
					</form>
				</div>
			</div>
		<?php endif; ?>

	<?php else : ?>
		<p class="mbrreg-message mbrreg-warning">
			<?php esc_html_e( 'No member profile found. Please contact an administrator.', 'member-registration-plugin' ); ?>
		</p>
	<?php endif; ?>
</div>