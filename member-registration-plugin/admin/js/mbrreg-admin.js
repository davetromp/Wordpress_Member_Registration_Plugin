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
			// Member actions.
			$(document).on('click', '.mbrreg-delete-member', this.handleDeleteMember);
			$(document).on('click', '.mbrreg-resend-activation', this.handleResendActivation);
			$(document).on('click', '.mbrreg-bulk-action-btn', this.handleBulkAction);
			$(document).on('submit', '#mbrreg-edit-member-form', this.handleUpdateMember);

			// Custom field actions.
			$(document).on('submit', '#mbrreg-add-field-form', this.handleAddField);
			$(document).on('submit', '#mbrreg-edit-field-form', this.handleUpdateField);
			$(document).on('click', '.mbrreg-edit-field', this.handleEditFieldClick);
			$(document).on('click', '.mbrreg-delete-field', this.handleDeleteField);
			$(document).on('change', '#field_type, #edit_field_type', this.toggleFieldOptions);

			// Modal.
			$(document).on('click', '.mbrreg-modal-close', this.closeModal);
			$(document).on('click', '.mbrreg-modal', this.closeModalOnOverlay);

			// Select all checkbox.
			$(document).on('change', '#cb-select-all', this.toggleSelectAll);

			// Import form.
			$(document).on('submit', '#mbrreg-import-form', this.handleImport);
		},

		/**
		 * Initialize field type toggle.
		 */
		initFieldTypeToggle: function() {
			this.toggleFieldOptions.call($('#field_type')[0]);
		},

		/**
		 * Toggle field options based on field type.
		 */
		toggleFieldOptions: function() {
			var type = $(this).val();
			var showOptions = ['select', 'radio'].indexOf(type) !== -1;
			var $container = $(this).attr('id') === 'edit_field_type' 
				? $('.mbrreg-edit-field-options-row')
				: $('.mbrreg-field-options-row');

			if (showOptions) {
				$container.show();
			} else {
				$container.hide();
			}
		},

		/**
		 * Handle member deletion.
		 */
		handleDeleteMember: function(e) {
			e.preventDefault();

			if (!confirm(mbrregAdmin.confirmDelete)) {
				return;
			}

			var $btn = $(this);
			var memberId = $btn.data('member-id');

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_admin_delete_member',
					nonce: mbrregAdmin.nonce,
					member_id: memberId,
					delete_wp_user: false
				},
				beforeSend: function() {
					$btn.prop('disabled', true).text(mbrregAdmin.processing);
				},
				success: function(response) {
					if (response.success) {
						MbrregAdmin.showMessage(response.data.message, 'success');
						// Redirect to members list or remove row.
						if ($btn.closest('form').attr('id') === 'mbrreg-edit-member-form') {
							window.location.href = 'admin.php?page=mbrreg-members';
						} else {
							$btn.closest('tr').fadeOut(function() {
								$(this).remove();
							});
						}
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
						$btn.prop('disabled', false).text(mbrregAdmin.error);
					}
				},
				error: function() {
					MbrregAdmin.showMessage(mbrregAdmin.error, 'error');
					$btn.prop('disabled', false);
				}
			});
		},

		/**
		 * Handle resend activation email.
		 */
		handleResendActivation: function(e) {
			e.preventDefault();

			var $btn = $(this);
			var memberId = $btn.data('member-id');
			var originalText = $btn.text();

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_admin_resend_activation',
					nonce: mbrregAdmin.nonce,
					member_id: memberId
				},
				beforeSend: function() {
					$btn.prop('disabled', true).text(mbrregAdmin.processing);
				},
				success: function(response) {
					if (response.success) {
						MbrregAdmin.showMessage(response.data.message, 'success');
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
					}
					$btn.prop('disabled', false).text(originalText);
				},
				error: function() {
					MbrregAdmin.showMessage(mbrregAdmin.error, 'error');
					$btn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle bulk action.
		 */
		handleBulkAction: function(e) {
			e.preventDefault();

			var action = $('#bulk-action-selector').val();
			var memberIds = [];

			$('input[name="member_ids[]"]:checked').each(function() {
				memberIds.push($(this).val());
			});

			if (!action) {
				alert(mbrregAdmin.selectAction);
				return;
			}

			if (memberIds.length === 0) {
				alert(mbrregAdmin.selectMembers);
				return;
			}

			if (!confirm(mbrregAdmin.confirmBulk)) {
				return;
			}

			var $btn = $(this);
			var originalText = $btn.text();

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_admin_bulk_action',
					nonce: mbrregAdmin.nonce,
					bulk_action: action,
					member_ids: memberIds
				},
				beforeSend: function() {
					$btn.prop('disabled', true).text(mbrregAdmin.processing);
				},
				success: function(response) {
					if (response.success) {
						MbrregAdmin.showMessage(response.data.message, 'success');
						location.reload();
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
						$btn.prop('disabled', false).text(originalText);
					}
				},
				error: function() {
					MbrregAdmin.showMessage(mbrregAdmin.error, 'error');
					$btn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle member update.
		 */
		handleUpdateMember: function(e) {
			e.preventDefault();

			var $form = $(this);
			var $btn = $form.find('button[type="submit"]');
			var $spinner = $form.find('.spinner');

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: $form.serialize() + '&action=mbrreg_admin_update_member&nonce=' + mbrregAdmin.nonce,
				beforeSend: function() {
					$btn.prop('disabled', true);
					$spinner.addClass('is-active');
				},
				success: function(response) {
					if (response.success) {
						MbrregAdmin.showMessage(response.data.message, 'success');
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
					}
					$btn.prop('disabled', false);
					$spinner.removeClass('is-active');
				},
				error: function() {
					MbrregAdmin.showMessage(mbrregAdmin.error, 'error');
					$btn.prop('disabled', false);
					$spinner.removeClass('is-active');
				}
			});
		},

		/**
		 * Handle add custom field.
		 */
		handleAddField: function(e) {
			e.preventDefault();

			var $form = $(this);
			var $btn = $form.find('button[type="submit"]');
			var $spinner = $form.find('.spinner');

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: $form.serialize() + '&action=mbrreg_admin_create_field&nonce=' + mbrregAdmin.nonce,
				beforeSend: function() {
					$btn.prop('disabled', true);
					$spinner.addClass('is-active');
				},
				success: function(response) {
					if (response.success) {
						MbrregAdmin.showMessage(response.data.message, 'success');
						location.reload();
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
						$btn.prop('disabled', false);
						$spinner.removeClass('is-active');
					}
				},
				error: function() {
					MbrregAdmin.showMessage(mbrregAdmin.error, 'error');
					$btn.prop('disabled', false);
					$spinner.removeClass('is-active');
				}
			});
		},

		/**
		 * Handle edit field click.
		 */
		handleEditFieldClick: function(e) {
			e.preventDefault();

			var fieldId = $(this).data('field-id');
			var field = mbrregFieldData.find(function(f) {
				return parseInt(f.id) === parseInt(fieldId);
			});

			if (!field) {
				return;
			}

			// Populate form.
			$('#edit_field_id').val(field.id);
			$('#edit_field_label').val(field.field_label);
			$('#edit_field_type').val(field.field_type).trigger('change');
			$('#edit_field_order').val(field.field_order);
			$('#edit_is_required').prop('checked', parseInt(field.is_required) === 1);
			$('#edit_is_admin_only').prop('checked', parseInt(field.is_admin_only) === 1);

			// Handle options.
			var options = field.field_options ? JSON.parse(field.field_options) : [];
			$('#edit_field_options').val(options.join('\n'));

			// Show modal.
			$('#mbrreg-edit-field-modal').show();
		},

		/**
		 * Handle update custom field.
		 */
		handleUpdateField: function(e) {
			e.preventDefault();

			var $form = $(this);
			var $btn = $form.find('button[type="submit"]');
			var $spinner = $form.find('.spinner');

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: $form.serialize() + '&action=mbrreg_admin_update_field&nonce=' + mbrregAdmin.nonce,
				beforeSend: function() {
					$btn.prop('disabled', true);
					$spinner.addClass('is-active');
				},
				success: function(response) {
					if (response.success) {
						MbrregAdmin.showMessage(response.data.message, 'success');
						location.reload();
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
						$btn.prop('disabled', false);
						$spinner.removeClass('is-active');
					}
				},
				error: function() {
					MbrregAdmin.showMessage(mbrregAdmin.error, 'error');
					$btn.prop('disabled', false);
					$spinner.removeClass('is-active');
				}
			});
		},

		/**
		 * Handle delete custom field.
		 */
		handleDeleteField: function(e) {
			e.preventDefault();

			if (!confirm(mbrregAdmin.confirmFieldDelete)) {
				return;
			}

			var $btn = $(this);
			var fieldId = $btn.data('field-id');

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_admin_delete_field',
					nonce: mbrregAdmin.nonce,
					field_id: fieldId
				},
				beforeSend: function() {
					$btn.prop('disabled', true).text(mbrregAdmin.processing);
				},
				success: function(response) {
					if (response.success) {
						MbrregAdmin.showMessage(response.data.message, 'success');
						$btn.closest('tr').fadeOut(function() {
							$(this).remove();
						});
					} else {
						MbrregAdmin.showMessage(response.data.message, 'error');
						$btn.prop('disabled', false);
					}
				},
				error: function() {
					MbrregAdmin.showMessage(mbrregAdmin.error, 'error');
					$btn.prop('disabled', false);
				}
			});
		},

		/**
		 * Handle import form.
		 */
		handleImport: function(e) {
			e.preventDefault();

			var $form = $(this);
			var $btn = $form.find('button[type="submit"]');
			var $spinner = $form.find('.spinner');
			var $results = $form.find('.mbrreg-import-results');
			var $message = $form.find('.mbrreg-import-message');

			var formData = new FormData($form[0]);
			formData.append('action', 'mbrreg_admin_import_csv');
			formData.append('nonce', mbrregAdmin.nonce);

			$.ajax({
				url: mbrregAdmin.ajaxUrl,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function() {
					$btn.prop('disabled', true);
					$spinner.addClass('is-active');
					$results.hide();
				},
				success: function(response) {
					$btn.prop('disabled', false);
					$spinner.removeClass('is-active');
					$results.show();

					if (response.success) {
						$message.html('<div class="notice notice-success"><p>' + response.data.message.replace(/\n/g, '<br>') + '</p></div>');
					} else {
						$message.html('<div class="notice notice-error"><p>' + response.data.message + '</p></div>');
					}
				},
				error: function() {
					$btn.prop('disabled', false);
					$spinner.removeClass('is-active');
					$results.show();
					$message.html('<div class="notice notice-error"><p>' + mbrregAdmin.error + '</p></div>');
				}
			});
		},

		/**
		 * Close modal.
		 */
		closeModal: function(e) {
			e.preventDefault();
			$('.mbrreg-modal').hide();
		},

		/**
		 * Close modal on overlay click.
		 */
		closeModalOnOverlay: function(e) {
			if ($(e.target).hasClass('mbrreg-modal')) {
				$(this).hide();
			}
		},

		/**
		 * Toggle select all checkboxes.
		 */
		toggleSelectAll: function() {
			var isChecked = $(this).prop('checked');
			$('input[name="member_ids[]"]').prop('checked', isChecked);
		},

		/**
		 * Show admin message.
		 */
		showMessage: function(message, type) {
			var $container = $('#mbrreg-admin-messages');
			if (!$container.length) {
				$container = $('<div id="mbrreg-admin-messages"></div>').insertAfter('.wp-heading-inline, h1').first();
			}

			var noticeClass = type === 'success' ? 'notice-success' : 'notice-error';
			var $notice = $('<div class="notice ' + noticeClass + ' is-dismissible"><p>' + message + '</p></div>');

			$container.html($notice);

			// Auto dismiss after 5 seconds.
			setTimeout(function() {
				$notice.fadeOut(function() {
					$(this).remove();
				});
			}, 5000);

			// Scroll to message.
			$('html, body').animate({
				scrollTop: $container.offset().top - 50
			}, 300);
		}
	};

	// Initialize on document ready.
	$(document).ready(function() {
		MbrregAdmin.init();
	});

})(jQuery);