<?php
/**
 * Registration form template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/public/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get custom fields handler for rendering.
$custom_fields_handler = new Mbrreg_Custom_Fields();

// Check for activation messages.
$activation_error   = get_transient( 'mbrreg_activation_error' );
$activation_success = get_transient( 'mbrreg_activation_success' );

if ( $activation_error ) {
	delete_transient( 'mbrreg_activation_error' );
}
if ( $activation_success ) {
	delete_transient( 'mbrreg_activation_success' );
}
?>

<div class="mbrreg-form-container">
	<?php if ( $activation_error ) : ?>
		<div class="mbrreg-message mbrreg-error"><?php echo esc_html( $activation_error ); ?></div>
	<?php endif; ?>

	<?php if ( $activation_success ) : ?>
		<div class="mbrreg-message mbrreg-success"><?php echo esc_html( $activation_success ); ?></div>
	<?php endif; ?>

	<form id="mbrreg-register-form" class="mbrreg-form" method="post">
		<h2 class="mbrreg-form-title"><?php esc_html_e( 'Register', 'member-registration-plugin' ); ?></h2>

		<div class="mbrreg-form-messages"></div>

		<!-- Account Details -->
		<fieldset class="mbrreg-fieldset">
			<legend><?php esc_html_e( 'Account Details', 'member-registration-plugin' ); ?></legend>

			<div class="mbrreg-form-row">
				<label for="mbrreg-email">
					<?php esc_html_e( 'Email Address', 'member-registration-plugin' ); ?>
					<span class="required">*</span>
				</label>
				<input type="email" id="mbrreg-email" name="email" required>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-username">
					<?php esc_html_e( 'Username', 'member-registration-plugin' ); ?>
				</label>
				<input type="text" id="mbrreg-username" name="username">
				<span class="mbrreg-field-note"><?php esc_html_e( 'Leave blank to auto-generate from email.', 'member-registration-plugin' ); ?></span>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-password">
					<?php esc_html_e( 'Password', 'member-registration-plugin' ); ?>
					<span class="required">*</span>
				</label>
				<input type="password" id="mbrreg-password" name="password" required minlength="8">
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-password-confirm">
					<?php esc_html_e( 'Confirm Password', 'member-registration-plugin' ); ?>
					<span class="required">*</span>
				</label>
				<input type="password" id="mbrreg-password-confirm" name="password_confirm" required minlength="8">
			</div>
		</fieldset>

		<!-- Personal Details (only first_name and last_name) -->
		<fieldset class="mbrreg-fieldset">
			<legend><?php esc_html_e( 'Personal Details', 'member-registration-plugin' ); ?></legend>

			<div class="mbrreg-form-row">
				<label for="mbrreg-first-name">
					<?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?>
					<?php if ( get_option( 'mbrreg_require_first_name', false ) ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<input type="text" id="mbrreg-first-name" name="first_name" <?php echo get_option( 'mbrreg_require_first_name', false ) ? 'required' : ''; ?>>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-last-name">
					<?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?>
					<?php if ( get_option( 'mbrreg_require_last_name', false ) ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<input type="text" id="mbrreg-last-name" name="last_name" <?php echo get_option( 'mbrreg_require_last_name', false ) ? 'required' : ''; ?>>
			</div>
		</fieldset>

		<!-- Custom Fields (only user-editable) -->
		<?php if ( ! empty( $custom_fields ) ) : ?>
			<fieldset class="mbrreg-fieldset">
				<legend><?php esc_html_e( 'Additional Information', 'member-registration-plugin' ); ?></legend>

				<?php foreach ( $custom_fields as $field ) : ?>
					<div class="mbrreg-form-row">
						<label for="mbrreg-custom-<?php echo esc_attr( $field->id ); ?>">
							<?php echo esc_html( $field->field_label ); ?>
							<?php if ( $field->is_required ) : ?>
								<span class="required">*</span>
							<?php endif; ?>
						</label>
						<?php echo $custom_fields_handler->render_field_input( $field ); // phpcs:ignore ?>
					</div>
				<?php endforeach; ?>
			</fieldset>
		<?php endif; ?>

		<div class="mbrreg-form-row">
			<button type="submit" class="mbrreg-button mbrreg-button-primary">
				<?php esc_html_e( 'Register', 'member-registration-plugin' ); ?>
			</button>
		</div>
	</form>
</div>