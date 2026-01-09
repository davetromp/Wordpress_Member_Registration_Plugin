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

// Get field requirements.
$require_first_name     = get_option( 'mbrreg_require_first_name', false );
$require_last_name      = get_option( 'mbrreg_require_last_name', false );
$require_address        = get_option( 'mbrreg_require_address', false );
$require_telephone      = get_option( 'mbrreg_require_telephone', false );
$require_date_of_birth  = get_option( 'mbrreg_require_date_of_birth', false );
$require_place_of_birth = get_option( 'mbrreg_require_place_of_birth', false );

// Get custom fields instance for rendering.
$custom_fields_handler = new Mbrreg_Custom_Fields();
?>

<div class="mbrreg-form-container mbrreg-register-form-container">
	<form id="mbrreg-register-form" class="mbrreg-form" method="post">
		<h3 class="mbrreg-form-title"><?php esc_html_e( 'Member Registration', 'member-registration-plugin' ); ?></h3>

		<div class="mbrreg-form-messages"></div>

		<!-- Account Details -->
		<fieldset class="mbrreg-fieldset">
			<legend><?php esc_html_e( 'Account Details', 'member-registration-plugin' ); ?></legend>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-username"><?php esc_html_e( 'Username', 'member-registration-plugin' ); ?> <span class="required">*</span></label>
				<input type="text" id="mbrreg-register-username" name="username" required>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-email"><?php esc_html_e( 'Email Address', 'member-registration-plugin' ); ?> <span class="required">*</span></label>
				<input type="email" id="mbrreg-register-email" name="email" required>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-password"><?php esc_html_e( 'Password', 'member-registration-plugin' ); ?> <span class="required">*</span></label>
				<input type="password" id="mbrreg-register-password" name="password" required minlength="8">
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-password-confirm"><?php esc_html_e( 'Confirm Password', 'member-registration-plugin' ); ?> <span class="required">*</span></label>
				<input type="password" id="mbrreg-register-password-confirm" name="password_confirm" required minlength="8">
			</div>
		</fieldset>

		<!-- Personal Details -->
		<fieldset class="mbrreg-fieldset">
			<legend><?php esc_html_e( 'Personal Details', 'member-registration-plugin' ); ?></legend>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-first-name">
					<?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?>
					<?php if ( $require_first_name ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<input type="text" id="mbrreg-register-first-name" name="first_name" <?php echo $require_first_name ? 'required' : ''; ?>>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-last-name">
					<?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?>
					<?php if ( $require_last_name ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<input type="text" id="mbrreg-register-last-name" name="last_name" <?php echo $require_last_name ? 'required' : ''; ?>>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-address">
					<?php esc_html_e( 'Address', 'member-registration-plugin' ); ?>
					<?php if ( $require_address ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<textarea id="mbrreg-register-address" name="address" rows="3" <?php echo $require_address ? 'required' : ''; ?>></textarea>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-telephone">
					<?php esc_html_e( 'Telephone Number', 'member-registration-plugin' ); ?>
					<?php if ( $require_telephone ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<input type="tel" id="mbrreg-register-telephone" name="telephone" <?php echo $require_telephone ? 'required' : ''; ?>>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-dob">
					<?php esc_html_e( 'Date of Birth', 'member-registration-plugin' ); ?>
					<?php if ( $require_date_of_birth ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<input type="date" id="mbrreg-register-dob" name="date_of_birth" <?php echo $require_date_of_birth ? 'required' : ''; ?>>
			</div>

			<div class="mbrreg-form-row">
				<label for="mbrreg-register-pob">
					<?php esc_html_e( 'Place of Birth', 'member-registration-plugin' ); ?>
					<?php if ( $require_place_of_birth ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<input type="text" id="mbrreg-register-pob" name="place_of_birth" <?php echo $require_place_of_birth ? 'required' : ''; ?>>
			</div>
		</fieldset>

		<!-- Custom Fields -->
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