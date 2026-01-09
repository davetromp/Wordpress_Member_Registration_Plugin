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
			$(document).on('submit', '#mbrreg-profile-form', this.handleProfileUpdate);

			// Actions.
			$(document).on('click', '#mbrreg-set-inactive-btn', this.handleSetInactive);
			$(document).on('click', '#mbrreg-logout-btn', this.handleLogout);
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
						if (response.data.redirect_url) {
							window.location.href = response.data.redirect_url;
						} else {
							window.location.reload();
						}
					} else {
						MbrregPublic.showMessage($form, response.data.message, 'error');
						$submitBtn.prop('disabled', false).text(originalText);
					}
				},
				error: function() {
					MbrregPublic.showMessage($form, 'An error occurred. Please try again.', 'error');
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
					if (response.success) {
						MbrregPublic.showMessage($form, response.data.message, 'success');
						$form[0].reset();
					} else {
						MbrregPublic.showMessage($form, response.data.message, 'error');
					}
				},
				error: function() {
					MbrregPublic.showMessage($form, 'An error occurred. Please try again.', 'error');
				},
				complete: function() {
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

			MbrregPublic.clearMessages($form);
			$submitBtn.prop('disabled', true).text(mbrregPublic.processing);

			const formData = new FormData($form[0]);
			formData.append('action', 'mbrreg_update_profile');
			formData.append('nonce', mbrregPublic.updateProfileNonce);

			$.ajax({
				url: mbrregPublic.ajaxUrl,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					if (response.success) {
						MbrregPublic.showMessage($form, response.data.message, 'success');
					} else {
						MbrregPublic.showMessage($form, response.data.message, 'error');
					}
				},
				error: function() {
					MbrregPublic.showMessage($form, 'An error occurred. Please try again.', 'error');
				},
				complete: function() {
					$submitBtn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle set inactive button click.
		 *
		 * @param {Event} e Click event.
		 */
		handleSetInactive: function(e) {
			e.preventDefault();

			if (!confirm(mbrregPublic.confirmInactive)) {
				return;
			}

			const $btn = $(this);
			const originalText = $btn.text();

			$btn.prop('disabled', true).text(mbrregPublic.processing);

			$.ajax({
				url: mbrregPublic.ajaxUrl,
				type: 'POST',
				data: {
					action: 'mbrreg_set_inactive',
					nonce: mbrregPublic.setInactiveNonce
				},
				success: function(response) {
					if (response.success) {
						alert(response.data.message);
						window.location.reload();
					} else {
						alert(response.data.message);
						$btn.prop('disabled', false).text(originalText);
					}
				},
				error: function() {
					alert('An error occurred. Please try again.');
					$btn.prop('disabled', false).text(originalText);
				}
			});
		},

		/**
		 * Handle logout button click.
		 *
		 * @param {Event} e Click event.
		 */
		handleLogout: function(e) {
			e.preventDefault();

			if (!confirm(mbrregPublic.confirmLogout)) {
				return;
			}

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
		 * Show message in form.
		 *
		 * @param {jQuery} $form   Form element.
		 * @param {string} message Message text.
		 * @param {string} type    Message type (success/error).
		 */
		showMessage: function($form, message, type) {
			const $messages = $form.find('.mbrreg-form-messages');
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
		}
	};

	// Initialize on document ready.
	$(document).ready(function() {
		MbrregPublic.init();
	});

})(jQuery);