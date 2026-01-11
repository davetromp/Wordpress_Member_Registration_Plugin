<?php
/**
 * Admin settings page template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/admin/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get all pages for dropdown.
$pages = get_pages();
?>

<div class="wrap mbrreg-admin-wrap">
	<h1><?php esc_html_e( 'Member Registration Settings', 'member-registration-plugin' ); ?></h1>

	<?php settings_errors(); ?>

	<form method="post" action="options.php">
		<?php settings_fields( 'mbrreg_settings' ); ?>

		<!-- Registration Settings -->
		<div class="mbrreg-settings-section">
			<h2><?php esc_html_e( 'Registration Settings', 'member-registration-plugin' ); ?></h2>
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e( 'Allow Registration', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_allow_registration" value="1" <?php checked( get_option( 'mbrreg_allow_registration', true ) ); ?>>
							<?php esc_html_e( 'Allow new members to register', 'member-registration-plugin' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'When disabled, the registration form will not be shown.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Allow Multiple Members', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_allow_multiple_members" value="1" <?php checked( get_option( 'mbrreg_allow_multiple_members', true ) ); ?>>
							<?php esc_html_e( 'Allow users to register multiple members under one account', 'member-registration-plugin' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Useful for parents registering multiple children or family members.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="mbrreg_registration_page_id"><?php esc_html_e( 'Member Area Page', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<select name="mbrreg_registration_page_id" id="mbrreg_registration_page_id">
							<option value="0"><?php esc_html_e( '— Select —', 'member-registration-plugin' ); ?></option>
							<?php foreach ( $pages as $page ) : ?>
								<option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( get_option( 'mbrreg_registration_page_id' ), $page->ID ); ?>>
									<?php echo esc_html( $page->post_title ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<p class="description"><?php esc_html_e( 'The page containing the [mbrreg_member_area] shortcode.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="mbrreg_login_redirect_page"><?php esc_html_e( 'Login Redirect Page', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<select name="mbrreg_login_redirect_page" id="mbrreg_login_redirect_page">
							<option value="0"><?php esc_html_e( '— Select —', 'member-registration-plugin' ); ?></option>
							<?php foreach ( $pages as $page ) : ?>
								<option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( get_option( 'mbrreg_login_redirect_page' ), $page->ID ); ?>>
									<?php echo esc_html( $page->post_title ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<p class="description"><?php esc_html_e( 'Where to redirect members after successful login.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
			</table>
		</div>

		<!-- Display Settings -->
		<div class="mbrreg-settings-section">
			<h2><?php esc_html_e( 'Display Settings', 'member-registration-plugin' ); ?></h2>
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="mbrreg_date_format"><?php esc_html_e( 'Date Format', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<select name="mbrreg_date_format" id="mbrreg_date_format">
							<option value="eu" <?php selected( get_option( 'mbrreg_date_format', 'eu' ), 'eu' ); ?>>
								<?php esc_html_e( 'European (DD/MM/YYYY)', 'member-registration-plugin' ); ?>
							</option>
							<option value="us" <?php selected( get_option( 'mbrreg_date_format', 'eu' ), 'us' ); ?>>
								<?php esc_html_e( 'US (MM/DD/YYYY)', 'member-registration-plugin' ); ?>
							</option>
						</select>
						<p class="description"><?php esc_html_e( 'Choose how dates are displayed throughout the plugin.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
			</table>
		</div>

		<!-- Required Fields -->
		<div class="mbrreg-settings-section">
			<h2><?php esc_html_e( 'Required Fields', 'member-registration-plugin' ); ?></h2>
			<p class="description"><?php esc_html_e( 'Select which default fields should be required during registration and profile updates. Additional fields can be configured in the Custom Fields section.', 'member-registration-plugin' ); ?></p>
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_first_name" value="1" <?php checked( get_option( 'mbrreg_require_first_name', false ) ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_last_name" value="1" <?php checked( get_option( 'mbrreg_require_last_name', false ) ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
			</table>
		</div>

		<!-- Email Settings -->
		<div class="mbrreg-settings-section">
			<h2><?php esc_html_e( 'Email Settings', 'member-registration-plugin' ); ?></h2>
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="mbrreg_email_from_name"><?php esc_html_e( 'From Name', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" name="mbrreg_email_from_name" id="mbrreg_email_from_name" value="<?php echo esc_attr( get_option( 'mbrreg_email_from_name', get_bloginfo( 'name' ) ) ); ?>" class="regular-text">
						<p class="description"><?php esc_html_e( 'The name that will appear in the "From" field of emails.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="mbrreg_email_from_address"><?php esc_html_e( 'From Email', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="email" name="mbrreg_email_from_address" id="mbrreg_email_from_address" value="<?php echo esc_attr( get_option( 'mbrreg_email_from_address', get_option( 'admin_email' ) ) ); ?>" class="regular-text">
						<p class="description"><?php esc_html_e( 'The email address that will appear in the "From" field.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
			</table>
		</div>

		<!-- Shortcodes Reference -->
		<div class="mbrreg-settings-section">
			<h2><?php esc_html_e( 'Shortcodes', 'member-registration-plugin' ); ?></h2>
			<table class="form-table mbrreg-shortcodes-table">
				<tr>
					<th><code>[mbrreg_member_area]</code></th>
					<td><?php esc_html_e( 'Complete member area with login, registration, and dashboard.', 'member-registration-plugin' ); ?></td>
				</tr>
				<tr>
					<th><code>[mbrreg_login_form]</code></th>
					<td><?php esc_html_e( 'Standalone login form.', 'member-registration-plugin' ); ?></td>
				</tr>
				<tr>
					<th><code>[mbrreg_register_form]</code></th>
					<td><?php esc_html_e( 'Standalone registration form.', 'member-registration-plugin' ); ?></td>
				</tr>
				<tr>
					<th><code>[mbrreg_member_dashboard]</code></th>
					<td><?php esc_html_e( 'Member dashboard (for logged-in members only).', 'member-registration-plugin' ); ?></td>
				</tr>
			</table>
		</div>

		<?php submit_button(); ?>
	</form>
</div>