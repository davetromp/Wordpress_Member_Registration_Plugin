<?php
/**
 * Admin import/export page template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/admin/partials
 * @since 1.1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$custom_fields = ( new Mbrreg_Custom_Fields() )->get_all();
?>

<div class="wrap mbrreg-admin-wrap">
	<h1><?php esc_html_e( 'Import / Export Members', 'member-registration-plugin' ); ?></h1>

	<div class="mbrreg-admin-sections">
		<!-- Import Section -->
		<div class="mbrreg-admin-section">
			<h2><?php esc_html_e( 'Import Members', 'member-registration-plugin' ); ?></h2>
			<p class="description">
				<?php esc_html_e( 'Import members from a CSV file. An activation email will be sent to each imported member.', 'member-registration-plugin' ); ?>
			</p>

			<div class="mbrreg-import-instructions">
				<h4><?php esc_html_e( 'CSV File Format', 'member-registration-plugin' ); ?></h4>
				<p><?php esc_html_e( 'Your CSV file should have the following columns in order:', 'member-registration-plugin' ); ?></p>
				<ol>
					<li><strong><?php esc_html_e( 'Email', 'member-registration-plugin' ); ?></strong> (<?php esc_html_e( 'required', 'member-registration-plugin' ); ?>)</li>
					<li><strong><?php esc_html_e( 'First Name', 'member-registration-plugin' ); ?></strong></li>
					<li><strong><?php esc_html_e( 'Last Name', 'member-registration-plugin' ); ?></strong></li>
					<?php foreach ( $custom_fields as $field ) : ?>
						<li>
							<strong><?php echo esc_html( $field->field_label ); ?></strong>
							<?php if ( 'date' === $field->field_type ) : ?>
								(<?php echo esc_html( mbrreg_get_date_placeholder() ); ?>)
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ol>
				<p><strong><?php esc_html_e( 'Note:', 'member-registration-plugin' ); ?></strong> <?php esc_html_e( 'The first row should contain column headers and will be skipped during import.', 'member-registration-plugin' ); ?></p>
			</div>

			<form id="mbrreg-import-form" method="post" enctype="multipart/form-data" class="mbrreg-admin-form">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="csv_file"><?php esc_html_e( 'CSV File', 'member-registration-plugin' ); ?></label>
						</th>
						<td>
							<input type="file" name="csv_file" id="csv_file" accept=".csv" required>
							<p class="description"><?php esc_html_e( 'Select a CSV file to import.', 'member-registration-plugin' ); ?></p>
						</td>
					</tr>
				</table>

				<div class="mbrreg-import-results" style="display: none;">
					<h4><?php esc_html_e( 'Import Results', 'member-registration-plugin' ); ?></h4>
					<div class="mbrreg-import-message"></div>
				</div>

				<p class="submit">
					<button type="submit" class="button button-primary">
						<?php esc_html_e( 'Import Members', 'member-registration-plugin' ); ?>
					</button>
					<span class="spinner"></span>
				</p>
			</form>

			<div class="mbrreg-sample-csv">
				<h4><?php esc_html_e( 'Download Sample CSV', 'member-registration-plugin' ); ?></h4>
				<p><?php esc_html_e( 'Download a sample CSV file with the correct format:', 'member-registration-plugin' ); ?></p>
				<a href="#" class="button" id="mbrreg-download-sample">
					<?php esc_html_e( 'Download Sample CSV', 'member-registration-plugin' ); ?>
				</a>
			</div>
		</div>

		<!-- Export Section -->
		<div class="mbrreg-admin-section">
			<h2><?php esc_html_e( 'Export Members', 'member-registration-plugin' ); ?></h2>
			<p class="description">
				<?php esc_html_e( 'Export members to a CSV file for backup or external use.', 'member-registration-plugin' ); ?>
			</p>

			<form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>" class="mbrreg-admin-form">
				<input type="hidden" name="page" value="mbrreg-import-export">
				<input type="hidden" name="mbrreg_export" value="1">
				<?php wp_nonce_field( 'mbrreg_export_csv', '_wpnonce', false ); ?>

				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="export_status"><?php esc_html_e( 'Member Status', 'member-registration-plugin' ); ?></label>
						</th>
						<td>
							<select name="status" id="export_status">
								<option value=""><?php esc_html_e( 'All Members', 'member-registration-plugin' ); ?></option>
								<option value="active"><?php esc_html_e( 'Active Only', 'member-registration-plugin' ); ?></option>
								<option value="inactive"><?php esc_html_e( 'Inactive Only', 'member-registration-plugin' ); ?></option>
								<option value="pending"><?php esc_html_e( 'Pending Only', 'member-registration-plugin' ); ?></option>
							</select>
						</td>
					</tr>
				</table>

				<p class="submit">
					<button type="submit" class="button button-primary">
						<?php esc_html_e( 'Export Members', 'member-registration-plugin' ); ?>
					</button>
				</p>
			</form>

			<div class="mbrreg-export-info">
				<h4><?php esc_html_e( 'Export Information', 'member-registration-plugin' ); ?></h4>
				<p><?php esc_html_e( 'The exported CSV will include:', 'member-registration-plugin' ); ?></p>
				<ul>
					<li><?php esc_html_e( 'Member ID, Username, Email', 'member-registration-plugin' ); ?></li>
					<li><?php esc_html_e( 'First Name, Last Name', 'member-registration-plugin' ); ?></li>
					<li><?php esc_html_e( 'Status, Admin Status, Registration Date', 'member-registration-plugin' ); ?></li>
					<li><?php esc_html_e( 'All custom field values', 'member-registration-plugin' ); ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
	// Download sample CSV.
	$('#mbrreg-download-sample').on('click', function(e) {
		e.preventDefault();

		var headers = ['Email', 'First Name', 'Last Name'];
		<?php foreach ( $custom_fields as $field ) : ?>
		headers.push('<?php echo esc_js( $field->field_label ); ?>');
		<?php endforeach; ?>

		var sampleData = [
			headers,
			['john.doe@example.com', 'John', 'Doe'<?php foreach ( $custom_fields as $field ) : ?>, ''<?php endforeach; ?>],
			['jane.smith@example.com', 'Jane', 'Smith'<?php foreach ( $custom_fields as $field ) : ?>, ''<?php endforeach; ?>]
		];

		var csvContent = sampleData.map(function(row) {
			return row.map(function(cell) {
				// Escape quotes and wrap in quotes if contains comma.
				if (typeof cell === 'string' && (cell.indexOf(',') !== -1 || cell.indexOf('"') !== -1)) {
					return '"' + cell.replace(/"/g, '""') + '"';
				}
				return cell;
			}).join(',');
		}).join('\n');

		var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
		var link = document.createElement('a');
		var url = URL.createObjectURL(blob);
		link.setAttribute('href', url);
		link.setAttribute('download', 'members-import-sample.csv');
		link.style.visibility = 'hidden';
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
	});
});
</script>