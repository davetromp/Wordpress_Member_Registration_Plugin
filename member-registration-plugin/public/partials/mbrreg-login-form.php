<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
/**
 * Login form template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/public/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

// Check for activation messages.
$activation_error = get_transient('mbrreg_activation_error');
$activation_success = get_transient('mbrreg_activation_success');

if ($activation_error) {
	delete_transient('mbrreg_activation_error');
}
if ($activation_success) {
	delete_transient('mbrreg_activation_success');
}
?>

<div class="mbrreg-form-container">
	<?php if ($activation_error): ?>
		<div class="mbrreg-message mbrreg-error"><?php echo esc_html($activation_error); ?></div>
	<?php endif; ?>

	<?php if ($activation_success): ?>
		<div class="mbrreg-message mbrreg-success"><?php echo esc_html($activation_success); ?></div>
	<?php endif; ?>

	<form id="mbrreg-login-form" class="mbrreg-form" method="post">
		<h2 class="mbrreg-form-title"><?php esc_html_e('Login', 'member-registration-plugin'); ?></h2>

		<div class="mbrreg-form-messages"></div>

		<div class="mbrreg-form-row">
			<label for="mbrreg-login-username">
				<?php esc_html_e('Username or Email', 'member-registration-plugin'); ?>
				<span class="required">*</span>
			</label>
			<input type="text" id="mbrreg-login-username" name="username" required>
		</div>

		<div class="mbrreg-form-row">
			<label for="mbrreg-login-password">
				<?php esc_html_e('Password', 'member-registration-plugin'); ?>
				<span class="required">*</span>
			</label>
			<input type="password" id="mbrreg-login-password" name="password" required>
		</div>

		<div class="mbrreg-form-row mbrreg-checkbox-row">
			<label>
				<input type="checkbox" name="remember" value="1">
				<?php esc_html_e('Remember Me', 'member-registration-plugin'); ?>
			</label>
		</div>

		<div class="mbrreg-form-row">
			<button type="submit" class="mbrreg-button mbrreg-button-primary">
				<?php esc_html_e('Log In', 'member-registration-plugin'); ?>
			</button>
		</div>

		<div class="mbrreg-form-links">
			<a href="<?php echo esc_url(wp_lostpassword_url()); ?>">
				<?php esc_html_e('Forgot your password?', 'member-registration-plugin'); ?>
			</a>
		</div>
	</form>
</div>