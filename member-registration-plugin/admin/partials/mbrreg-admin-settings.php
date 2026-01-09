<?php
/**
 * Admin settings template.
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
	<h1><?php esc_html_e( 'Member Registration Settings', 'member-registration-plugin' ); ?></h1>
	<hr class="wp-header-end">

	<form method="post" action="options.php">
		<?php settings_fields( 'mbrreg_settings' ); ?>

		<!-- General Settings -->
		<h2><?php esc_html_e( 'General Settings', 'member-registration-plugin' ); ?></h2>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><?php esc_html_e( 'Allow Registration', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_allow_registration" value="1" <?php checked( get_option( 'mbrreg_allow_registration', true ), 1 ); ?>>
							<?php esc_html_e( 'Allow new members to register', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="mbrreg_registration_page_id"><?php esc_html_e( 'Registration Page', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<select name="mbrreg_registration_page_id" id="mbrreg_registration_page_id">
							<option value="0"><?php esc_html_e( '— Select a page —', 'member-registration-plugin' ); ?></option>
							<?php foreach ( $pages as $page ) : ?>
								<option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( get_option( 'mbrreg_registration_page_id' ), $page->ID ); ?>>
									<?php echo esc_html( $page->post_title ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<p class="description"><?php esc_html_e( 'The page where the member area shortcode is placed.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="mbrreg_login_redirect_page"><?php esc_html_e( 'Login Redirect Page', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<select name="mbrreg_login_redirect_page" id="mbrreg_login_redirect_page">
							<option value="0"><?php esc_html_e( '— Same page —', 'member-registration-plugin' ); ?></option>
							<?php foreach ( $pages as $page ) : ?>
								<option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( get_option( 'mbrreg_login_redirect_page' ), $page->ID ); ?>>
									<?php echo esc_html( $page->post_title ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<p class="description"><?php esc_html_e( 'Where to redirect members after successful login.', 'member-registration-plugin' ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>

		<!-- Required Fields Settings -->
		<h2><?php esc_html_e( 'Required Fields', 'member-registration-plugin' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Select which fields should be required during registration and profile updates.', 'member-registration-plugin' ); ?></p>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_first_name" value="1" <?php checked( get_option( 'mbrreg_require_first_name' ), 1 ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_last_name" value="1" <?php checked( get_option( 'mbrreg_require_last_name' ), 1 ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Address', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_address" value="1" <?php checked( get_option( 'mbrreg_require_address' ), 1 ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Telephone Number', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_telephone" value="1" <?php checked( get_option( 'mbrreg_require_telephone' ), 1 ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Date of Birth', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_date_of_birth" value="1" <?php checked( get_option( 'mbrreg_require_date_of_birth' ), 1 ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Place of Birth', 'member-registration-plugin' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="mbrreg_require_place_of_birth" value="1" <?php checked( get_option( 'mbrreg_require_place_of_birth' ), 1 ); ?>>
							<?php esc_html_e( 'Required', 'member-registration-plugin' ); ?>
						</label>
					</td>
				</tr>
			</tbody>
		</table>

		<!-- Email Settings -->
		<h2><?php esc_html_e( 'Email Settings', 'member-registration-plugin' ); ?></h2>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="mbrreg_email_from_name"><?php esc_html_e( 'From Name', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" name="mbrreg_email_from_name" id="mbrreg_email_from_name" value="<?php echo esc_attr( get_option( 'mbrreg_email_from_name', get_bloginfo( 'name' ) ) ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="mbrreg_email_from_address"><?php esc_html_e( 'From Email Address', 'member-registration-plugin' ); ?></label>
					</th>
					<td>
						<input type="email" name="mbrreg_email_from_address" id="mbrreg_email_from_address" value="<?php echo esc_attr( get_option( 'mbrreg_email_from_address', get_option( 'admin_email' ) ) ); ?>" class="regular-text">
					</td>
				</tr>
			</tbody>
		</table>

		<!-- Shortcode Reference -->
		<h2><?php esc_html_e( 'Shortcode Reference', 'member-registration-plugin' ); ?></h2>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><code>[mbrreg_member_area]</code></th>
					<td>
						<?php esc_html_e( 'Displays the complete member area with login, registration, and dashboard.', 'member-registration-plugin' ); ?>
						<br>
						<small><?php esc_html_e( 'Optional attribute: show_register="no" to hide registration form.', 'member-registration-plugin' ); ?></small>
					</td>
				</tr>
				<tr>
					<th scope="row"><code>[mbrreg_login_form]</code></th>
					<td><?php esc_html_e( 'Displays only the login form.', 'member-registration-plugin' ); ?></td>
				</tr>
				<tr>
					<th scope="row"><code>[mbrreg_register_form]</code></th>
					<td><?php esc_html_e( 'Displays only the registration form.', 'member-registration-plugin' ); ?></td>
				</tr>
				<tr>
					<th scope="row"><code>[mbrreg_member_dashboard]</code></th>
					<td><?php esc_html_e( 'Displays the member dashboard (for logged-in members only).', 'member-registration-plugin' ); ?></td>
				</tr>
			</tbody>
		</table>

		<?php submit_button(); ?>
	</form>
</div>