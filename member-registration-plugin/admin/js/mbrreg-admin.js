/**
 * Admin JavaScript for Member Registration Plugin.
 *
 * @package Member_Registration_Plugin
 * @since 1.0.0
 */

(function($) {
	'use strict';

	/**
	 * Admin functionality object.
	 */
	const MbrregAdmin = {

		/**
		 * Initialize admin functionality.
		 */
		init: function() {
			this.bindEvents();
			this.initFieldTypeToggle();
		},

		/**
		 * Bind event handlers.
		 */
		bindEvents: function() {
			// Member management.
			$(document).on('submit', '#mbrreg-admin-member-form', this.handleMemberUpdate);
			$(document).on('click', '.mbrreg-delete-member', this.handleMemberDelete);
			$(document).on('click', '#mbrreg-bulk-action-btn', this.handleBulkAction);
			$(document).on('click', '.mbrreg-resend-activation', this.handleResendActivation);

			// Custom fields management.
			$(document).on('submit', '#mbrreg-add-field-form', this.handleFieldCreate);
			$(document).on('click', '.mbrreg-edit-field', this.openEditFieldModal);
			$(document).on('submit', '#mbrreg-edit-field-form', this.handleFieldUpdate);
			$(document).on('click', '.mbrreg-delete-field', this.handleFieldDelete);

			// Modal controls.
			$(document).on('click', '.mbrreg-modal-close, .mbrreg-modal-cancel', this.closeModal);
			$(document).on('click', '.mbrreg-modal', this.closeModalOnOverlay);

			// Field type toggle.
			$(document).on('change', '#mbrreg-field-type', this.toggleFieldOptions);
			$(document).on('change', '#mbrreg-edit-field-type', this.toggleEditFieldOptions);

			// Select all checkbox.
			$(document).on('change', '#cb-select-all', this.toggleSelectAll);
		},

		/**
		 * Initialize field type toggle on page load.
		 */
		initFieldTypeToggle: function() {
			this.toggleFieldOptions();
		},

		/**
		 * Handle member update.
		 *
		 * @param {Event} e Submit event.
		 */
		handleMemberUpdate: function(e) {
			e.preventDefault();

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			const originalText = $submitBtn.text();

			$submitBtn.prop('disabled', true).text(mbrregAdmin.processing);

			const formData = new FormData($form[0]);
			formData.append('action', 'mbrreg_admin_update_member');
			formData.append('nonce', mbrregAdmin.nonce);

			// Handle checkbox for is_admin.
			if (!$form.find('input[name="is_admin"]').is(':checked')) {
				formData.set('is_admin', '0');
			}

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					if (response.success) {
						MbrregAdmin.showMessage(response.data.message, 'success');
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
					}
				},
				error: function() {
					MbrregAdmin.showMessage('An error occurred. Please try again.', 'error');
				},
				complete: function() {
					$submitBtn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle member delete.
		 *
		 * @param {Event} e Click event.
		 */
		handleMemberDelete: function(e) {
			e.preventDefault();

			if (!confirm(mbrregAdmin.confirmDelete)) {
				return;
			}

			const $btn = $(this);
			const memberId = $btn.data('member-id');

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_admin_delete_member',
					nonce: mbrregAdmin.nonce,
					member_id: memberId,
					delete_wp_user: false
				},
				success: function(response) {
					if (response.success) {
						// Remove row or redirect.
						const $row = $btn.closest('tr');
						if ($row.length) {
							$row.fadeOut(function() {
								$(this).remove();
							});
						} else {
							window.location.href = 'admin.php?page=mbrreg-members';
						}
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
					}
				},
				error: function() {
					MbrregAdmin.showMessage('An error occurred. Please try again.', 'error');
				}
			});
		},

		/**
		 * Handle bulk action.
		 *
		 * @param {Event} e Click event.
		 */
		handleBulkAction: function(e) {
			e.preventDefault();

			const action = $('#mbrreg-bulk-action').val();
			const memberIds = [];

			$('input[name="member_ids[]"]:checked').each(function() {
				memberIds.push($(this).val());
			});

			if (!action) {
				alert(mbrregAdmin.selectAction);
				return;
			}

			if (memberIds.length === 0) {
				alert(mbrregAdmin.selectItems);
				return;
			}

			if (!confirm(mbrregAdmin.confirmBulk)) {
				return;
			}

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_admin_bulk_action',
					nonce: mbrregAdmin.nonce,
					bulk_action: action,
					member_ids: memberIds
				},
				success: function(response) {
					if (response.success) {
						location.reload();
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
					}
				},
				error: function() {
					MbrregAdmin.showMessage('An error occurred. Please try again.', 'error');
				}
			});
		},

		/**
		 * Handle resend activation email.
		 *
		 * @param {Event} e Click event.
		 */
		handleResendActivation: function(e) {
			e.preventDefault();

			const $btn = $(this);
			const memberId = $btn.data('member-id');
			const originalText = $btn.text();

			$btn.prop('disabled', true).text(mbrregAdmin.processing);

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_admin_resend_activation',
					nonce: mbrregAdmin.nonce,
					member_id: memberId
				},
				success: function(response) {
					if (response.success) {
						MbrregAdmin.showMessage(response.data.message, 'success');
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
					}
				},
				error: function() {
					MbrregAdmin.showMessage('An error occurred. Please try again.', 'error');
				},
				complete: function() {
					$btn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle custom field create.
		 *
		 * @param {Event} e Submit event.
		 */
		handleFieldCreate: function(e) {
			e.preventDefault();

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			const originalText = $submitBtn.text();

			$submitBtn.prop('disabled', true).text(mbrregAdmin.processing);

			const formData = new FormData($form[0]);
			formData.append('action', 'mbrreg_admin_create_field');
			formData.append('nonce', mbrregAdmin.nonce);

			// Handle checkbox for is_required.
			if (!$form.find('input[name="is_required"]').is(':checked')) {
				formData.set('is_required', '0');
			}

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					if (response.success) {
						location.reload();
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
					}
				},
				error: function() {
					MbrregAdmin.showMessage('An error occurred. Please try again.', 'error');
				},
				complete: function() {
					$submitBtn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Open edit field modal.
		 *
		 * @param {Event} e Click event.
		 */
		openEditFieldModal: function(e) {
			e.preventDefault();

			const $btn = $(this);
			const $modal = $('#mbrreg-edit-field-modal');

			// Populate form fields.
			$('#mbrreg-edit-field-id').val($btn.data('field-id'));
			$('#mbrreg-edit-field-label').val($btn.data('field-label'));
			$('#mbrreg-edit-field-type').val($btn.data('field-type'));
			$('#mbrreg-edit-field-order').val($btn.data('field-order'));
			$('#mbrreg-edit-field-required').prop('checked', $btn.data('is-required') == 1);

			// Parse and set options.
			let options = $btn.data('field-options');
			if (options) {
				try {
					options = JSON.parse(options);
					if (Array.isArray(options)) {
						options = options.join('\n');
					}
				} catch (e) {
					options = '';
				}
			}
			$('#mbrreg-edit-field-options').val(options || '');

			// Toggle options visibility.
			MbrregAdmin.toggleEditFieldOptions();

			// Show modal.
			$modal.show();
		},

		/**
		 * Handle custom field update.
		 *
		 * @param {Event} e Submit event.
		 */
		handleFieldUpdate: function(e) {
			e.preventDefault();

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			const originalText = $submitBtn.text();

			$submitBtn.prop('disabled', true).text(mbrregAdmin.processing);

			const formData = new FormData($form[0]);
			formData.append('action', 'mbrreg_admin_update_field');
			formData.append('nonce', mbrregAdmin.nonce);

			// Handle checkbox for is_required.
			if (!$form.find('input[name="is_required"]').is(':checked')) {
				formData.set('is_required', '0');
			}

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					if (response.success) {
						location.reload();
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
					}
				},
				error: function() {
					MbrregAdmin.showMessage('An error occurred. Please try again.', 'error');
				},
				complete: function() {
					$submitBtn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle custom field delete.
		 *
		 * @param {Event} e Click event.
		 */
		handleFieldDelete: function(e) {
			e.preventDefault();

			if (!confirm(mbrregAdmin.confirmDelete)) {
				return;
			}

			const $btn = $(this);
			const fieldId = $btn.data('field-id');

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_admin_delete_field',
					nonce: mbrregAdmin.nonce,
					field_id: fieldId
				},
				success: function(response) {
					if (response.success) {
						$btn.closest('tr').fadeOut(function() {
							$(this).remove();
						});
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
					}
				},
				error: function() {
					MbrregAdmin.showMessage('An error occurred. Please try again.', 'error');
				}
			});
		},

		/**
		 * Toggle field options visibility.
		 */
		toggleFieldOptions: function() {
			const fieldType = $('#mbrreg-field-type').val();
			const needsOptions = ['select', 'radio'].includes(fieldType);

			$('.mbrreg-field-options-row').toggle(needsOptions);
		},

		/**
		 * Toggle edit field options visibility.
		 */
		toggleEditFieldOptions: function() {
			const fieldType = $('#mbrreg-edit-field-type').val();
			const needsOptions = ['select', 'radio'].includes(fieldType);

			$('.mbrreg-edit-field-options-row').toggle(needsOptions);
		},

		/**
		 * Close modal.
		 */
		closeModal: function() {
			$('.mbrreg-modal').hide();
		},

		/**
		 * Close modal when clicking overlay.
		 *
		 * @param {Event} e Click event.
		 */
		closeModalOnOverlay: function(e) {
			if ($(e.target).hasClass('mbrreg-modal')) {
				MbrregAdmin.closeModal();
			}
		},

		/**
		 * Toggle select all checkboxes.
		 */
		toggleSelectAll: function() {
			const isChecked = $(this).is(':checked');
			$('input[name="member_ids[]"]').prop('checked', isChecked);
		},

		/**
		 * Show admin message.
		 *
		 * @param {string} message Message text.
		 * @param {string} type    Message type (success/error).
		 */
		showMessage: function(message, type) {
			const $container = $('.mbrreg-admin-messages');
			const noticeClass = type === 'success' ? 'notice-success' : 'notice-error';

			const $notice = $('<div class="notice ' + noticeClass + ' is-dismissible"><p>' + message + '</p></div>');

			$container.html($notice);

			// Auto-dismiss after 5 seconds.
			setTimeout(function() {
				$notice.fadeOut();
			}, 5000);
		}
	};

	// Initialize on document ready.
	$(document).ready(function() {
		MbrregAdmin.init();
	});

})(jQuery);