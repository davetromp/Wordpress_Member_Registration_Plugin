/**
 * Public JavaScript for Member Registration Plugin.
 *
 * @package Member_Registration_Plugin
 * @since 1.0.0
 */

(function($) {
	'use strict';

	/**
	 * Public functionality object.
	 */
	const MbrregPublic = {

		/**
		 * Initialize public functionality.
		 */
		init: function() {
			this.bindEvents();
		},

		/**
		 * Bind event handlers.
		 */
		bindEvents: function() {
			// Tab navigation.
			$(document).on('click', '.mbrreg-tab', this.handleTabClick);

			// Form submissions.
			$(document).on('submit', '#mbrreg-login-form', this.handleLogin);
			$(document).on('submit', '#mbrreg-register-form', this.handleRegister);
			$(document).on('submit', '.mbrreg-profile-form', this.handleProfileUpdate);
			$(document).on('submit', '#mbrreg-add-member-form', this.handleAddMember);

			// Actions with confirmation modal.
			$(document).on('click', '.mbrreg-deactivate-btn', this.handleDeactivateClick);
			$(document).on('click', '.mbrreg-logout-btn', this.handleLogoutClick);

			// Add member toggle.
			$(document).on('click', '.mbrreg-toggle-add-member', this.toggleAddMemberForm);
			$(document).on('click', '.mbrreg-cancel-add-member', this.hideAddMemberForm);

			// Modal controls.
			$(document).on('click', '[data-dismiss="modal"]', this.closeModal);
			$(document).on('click', '.mbrreg-modal-overlay', this.closeModalOnOverlay);
		},

		/**
		 * Handle tab click.
		 *
		 * @param {Event} e Click event.
		 */
		handleTabClick: function(e) {
			e.preventDefault();

			const $tab = $(this);
			const tabId = $tab.data('tab');

			// Update active tab.
			$('.mbrreg-tab').removeClass('mbrreg-tab-active');
			$tab.addClass('mbrreg-tab-active');

			// Update active content.
			$('.mbrreg-tab-content').removeClass('mbrreg-tab-content-active');
			$('.mbrreg-tab-' + tabId).addClass('mbrreg-tab-content-active');
		},

		/**
		 * Handle login form submission.
		 *
		 * @param {Event} e Submit event.
		 */
		handleLogin: function(e) {
			e.preventDefault();

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			const originalText = $submitBtn.text();

			MbrregPublic.clearMessages($form);
			$submitBtn.prop('disabled', true).text(mbrregPublic.processing);

			$.ajax({
				url: mbrregPublic.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_login',
					nonce: mbrregPublic.loginNonce,
					username: $form.find('input[name="username"]').val(),
					password: $form.find('input[name="password"]').val(),
					remember: $form.find('input[name="remember"]').is(':checked') ? 1 : 0
				},
				success: function(response) {
					if (response.success) {
						MbrregPublic.showMessage($form, response.data.message, 'success');

						// Redirect or reload.
						setTimeout(function() {
							if (response.data.redirect_url) {
								window.location.href = response.data.redirect_url;
							} else {
								window.location.reload();
							}
						}, 500);
					} else {
						MbrregPublic.showMessage($form, response.data.message, 'error');
						$submitBtn.prop('disabled', false).text(originalText);
					}
				},
				error: function() {
					MbrregPublic.showMessage($form, mbrregPublic.errorGeneral, 'error');
					$submitBtn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle registration form submission.
		 *
		 * @param {Event} e Submit event.
		 */
		handleRegister: function(e) {
			e.preventDefault();

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			const originalText = $submitBtn.text();

			// Validate password match.
			const password = $form.find('input[name="password"]').val();
			const passwordConfirm = $form.find('input[name="password_confirm"]').val();

			if (password !== passwordConfirm) {
				MbrregPublic.showMessage($form, mbrregPublic.passwordMismatch, 'error');
				return;
			}

			MbrregPublic.clearMessages($form);
			$submitBtn.prop('disabled', true).text(mbrregPublic.processing);

			const formData = new FormData($form[0]);
			formData.append('action', 'mbrreg_register');
			formData.append('nonce', mbrregPublic.registerNonce);

			$.ajax({
				url: mbrregPublic.ajaxUrl,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					$submitBtn.prop('disabled', false).text(originalText);
					
					if (response.success) {
						MbrregPublic.showMessage($form, response.data.message, 'success');
						$form[0].reset();
						
						// Show success in modal if needed.
						if (response.data.reload) {
							setTimeout(function() {
								window.location.reload();
							}, 2000);
						}
					} else {
						MbrregPublic.showMessage($form, response.data.message, 'error');
					}
				},
				error: function() {
					MbrregPublic.showMessage($form, mbrregPublic.errorGeneral, 'error');
					$submitBtn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle profile update form submission.
		 *
		 * @param {Event} e Submit event.
		 */
		handleProfileUpdate: function(e) {
			e.preventDefault();

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			const originalText = $submitBtn.text();
			const memberId = $form.data('member-id');

			MbrregPublic.clearMessages($form);
			$submitBtn.prop('disabled', true).text(mbrregPublic.processing);

			const formData = new FormData($form[0]);
			formData.append('action', 'mbrreg_update_profile');
			formData.append('nonce', mbrregPublic.updateProfileNonce);
			formData.append('member_id', memberId);

			$.ajax({
				url: mbrregPublic.ajaxUrl,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					$submitBtn.prop('disabled', false).text(originalText);
					
					if (response.success) {
						MbrregPublic.showMessage($form, response.data.message, 'success');
						
						if (response.data.reload) {
							setTimeout(function() {
								window.location.reload();
							}, 1000);
						}
					} else {
						MbrregPublic.showMessage($form, response.data.message, 'error');
					}
				},
				error: function() {
					MbrregPublic.showMessage($form, mbrregPublic.errorGeneral, 'error');
					$submitBtn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle add member form submission.
		 *
		 * @param {Event} e Submit event.
		 */
		handleAddMember: function(e) {
			e.preventDefault();

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			const originalText = $submitBtn.text();

			MbrregPublic.clearMessages($form);
			$submitBtn.prop('disabled', true).text(mbrregPublic.processing);

			const formData = new FormData($form[0]);
			formData.append('action', 'mbrreg_add_member');
			formData.append('nonce', mbrregPublic.addMemberNonce);

			$.ajax({
				url: mbrregPublic.ajaxUrl,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					$submitBtn.prop('disabled', false).text(originalText);
					
					if (response.success) {
						MbrregPublic.showMessage($form, response.data.message, 'success');
						$form[0].reset();
						
						if (response.data.reload) {
							setTimeout(function() {
								window.location.reload();
							}, 1500);
						}
					} else {
						MbrregPublic.showMessage($form, response.data.message, 'error');
					}
				},
				error: function() {
					MbrregPublic.showMessage($form, mbrregPublic.errorGeneral, 'error');
					$submitBtn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle deactivate button click.
		 *
		 * @param {Event} e Click event.
		 */
		handleDeactivateClick: function(e) {
			e.preventDefault();

			const $btn = $(this);
			const memberId = $btn.data('member-id');

			MbrregPublic.showConfirmModal(
				mbrregPublic.confirmDeactivateTitle,
				mbrregPublic.confirmDeactivate,
				function() {
					MbrregPublic.performDeactivate(memberId, $btn);
				}
			);
		},

		/**
		 * Perform member deactivation.
		 *
		 * @param {int} memberId Member ID.
		 * @param {jQuery} $btn Button element.
		 */
		performDeactivate: function(memberId, $btn) {
			const originalText = $btn.text();
			$btn.prop('disabled', true).text(mbrregPublic.processing);

			$.ajax({
				url: mbrregPublic.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_set_inactive',
					nonce: mbrregPublic.setInactiveNonce,
					member_id: memberId
				},
				success: function(response) {
					if (response.success) {
						MbrregPublic.showAlertModal(
							mbrregPublic.successTitle,
							response.data.message,
							function() {
								window.location.reload();
							}
						);
					} else {
						MbrregPublic.showAlertModal(mbrregPublic.errorTitle, response.data.message);
						$btn.prop('disabled', false).text(originalText);
					}
				},
				error: function() {
					MbrregPublic.showAlertModal(mbrregPublic.errorTitle, mbrregPublic.errorGeneral);
					$btn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle logout button click.
		 *
		 * @param {Event} e Click event.
		 */
		handleLogoutClick: function(e) {
			e.preventDefault();

			MbrregPublic.showConfirmModal(
				mbrregPublic.confirmLogoutTitle,
				mbrregPublic.confirmLogout,
				function() {
					MbrregPublic.performLogout();
				}
			);
		},

		/**
		 * Perform logout.
		 */
		performLogout: function() {
			$.ajax({
				url: mbrregPublic.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_logout',
					nonce: mbrregPublic.logoutNonce
				},
				success: function(response) {
					window.location.reload();
				},
				error: function() {
					window.location.reload();
				}
			});
		},

		/**
		 * Toggle add member form.
		 */
		toggleAddMemberForm: function() {
			$('.mbrreg-add-member-form-container').slideToggle();
			$(this).hide();
		},

		/**
		 * Hide add member form.
		 */
		hideAddMemberForm: function() {
			$('.mbrreg-add-member-form-container').slideUp();
			$('.mbrreg-toggle-add-member').show();
		},

		/**
		 * Show confirmation modal.
		 *
		 * @param {string} title Modal title.
		 * @param {string} message Modal message.
		 * @param {function} callback Callback on confirm.
		 */
		showConfirmModal: function(title, message, callback) {
			const $modal = $('#mbrreg-confirm-modal');
			
			$modal.find('.mbrreg-modal-title').text(title);
			$modal.find('.mbrreg-modal-message').text(message);
			
			// Remove previous click handler and add new one.
			$modal.find('.mbrreg-modal-confirm-btn').off('click').on('click', function() {
				MbrregPublic.closeModal();
				if (typeof callback === 'function') {
					callback();
				}
			});
			
			$modal.fadeIn(200);
		},

		/**
		 * Show alert modal.
		 *
		 * @param {string} title Modal title.
		 * @param {string} message Modal message.
		 * @param {function} callback Callback on close.
		 */
		showAlertModal: function(title, message, callback) {
			const $modal = $('#mbrreg-alert-modal');
			
			$modal.find('.mbrreg-modal-title').text(title);
			$modal.find('.mbrreg-modal-message').text(message);
			
			// Store callback for when modal closes.
			$modal.data('close-callback', callback);
			
			$modal.fadeIn(200);
		},

		/**
		 * Close modal.
		 */
		closeModal: function() {
			const $modal = $(this).closest('.mbrreg-modal-overlay');
			const callback = $modal.data('close-callback');
			
			$modal.fadeOut(200, function() {
				$modal.removeData('close-callback');
				if (typeof callback === 'function') {
					callback();
				}
			});
		},

		/**
		 * Close modal when clicking overlay.
		 *
		 * @param {Event} e Click event.
		 */
		closeModalOnOverlay: function(e) {
			if ($(e.target).hasClass('mbrreg-modal-overlay')) {
				$(this).fadeOut(200);
			}
		},

		/**
		 * Show message in form.
		 *
		 * @param {jQuery} $form   Form element.
		 * @param {string} message Message text.
		 * @param {string} type    Message type (success/error).
		 */
		showMessage: function($form, message, type) {
			let $messages = $form.find('.mbrreg-form-messages');
			
			// If not found in form, look for global messages container.
			if (!$messages.length) {
				$messages = $('.mbrreg-form-messages').first();
			}
			
			const className = type === 'success' ? 'mbrreg-success' : 'mbrreg-error';

			$messages.html('<div class="mbrreg-message ' + className + '">' + message + '</div>');

			// Scroll to message.
			$('html, body').animate({
				scrollTop: $messages.offset().top - 100
			}, 300);
		},

		/**
		 * Clear messages from form.
		 *
		 * @param {jQuery} $form Form element.
		 */
		clearMessages: function($form) {
			$form.find('.mbrreg-form-messages').empty();
			$('.mbrreg-form-messages').empty();
		}
	};

	// Initialize on document ready.
	$(document).ready(function() {
		MbrregPublic.init();
	});

})(jQuery);