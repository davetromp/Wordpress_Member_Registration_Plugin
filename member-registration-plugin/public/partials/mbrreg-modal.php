<?php
/**
 * Modal template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/public/partials
 * @since 1.1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<!-- Confirmation Modal -->
<div id="mbrreg-confirm-modal" class="mbrreg-modal-overlay" style="display: none;">
	<div class="mbrreg-modal-dialog">
		<div class="mbrreg-modal-header">
			<h3 class="mbrreg-modal-title"><?php esc_html_e( 'Confirm Action', 'member-registration-plugin' ); ?></h3>
			<button type="button" class="mbrreg-modal-close-btn" data-dismiss="modal">&times;</button>
		</div>
		<div class="mbrreg-modal-body">
			<p class="mbrreg-modal-message"></p>
		</div>
		<div class="mbrreg-modal-footer">
			<button type="button" class="mbrreg-button mbrreg-button-secondary" data-dismiss="modal">
				<?php esc_html_e( 'Cancel', 'member-registration-plugin' ); ?>
			</button>
			<button type="button" class="mbrreg-button mbrreg-button-primary mbrreg-modal-confirm-btn">
				<?php esc_html_e( 'Confirm', 'member-registration-plugin' ); ?>
			</button>
		</div>
	</div>
</div>

<!-- Alert Modal -->
<div id="mbrreg-alert-modal" class="mbrreg-modal-overlay" style="display: none;">
	<div class="mbrreg-modal-dialog">
		<div class="mbrreg-modal-header">
			<h3 class="mbrreg-modal-title"><?php esc_html_e( 'Notice', 'member-registration-plugin' ); ?></h3>
			<button type="button" class="mbrreg-modal-close-btn" data-dismiss="modal">&times;</button>
		</div>
		<div class="mbrreg-modal-body">
			<p class="mbrreg-modal-message"></p>
		</div>
		<div class="mbrreg-modal-footer">
			<button type="button" class="mbrreg-button mbrreg-button-primary" data-dismiss="modal">
				<?php esc_html_e( 'OK', 'member-registration-plugin' ); ?>
			</button>
		</div>
	</div>
</div>