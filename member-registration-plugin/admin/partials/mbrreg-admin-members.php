<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
/**
 * Admin members list page template.
 *
 * @package Member_Registration_Plugin
 * @subpackage Member_Registration_Plugin/admin/partials
 * @since 1.0.0
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

// Get filter parameters.
$status = isset($_GET['status']) ? sanitize_text_field(wp_unslash($_GET['status'])) : '';
$search = isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : '';
$paged = isset($_GET['paged']) ? max(1, absint($_GET['paged'])) : 1;
$per_page = 20;

// Build query args.
$args = array(
	'status' => $status,
	'search' => $search,
	'limit' => $per_page,
	'offset' => ($paged - 1) * $per_page,
);

// Get members.
$database = new Mbrreg_Database();
$custom_fields = new Mbrreg_Custom_Fields();
$member_handler = new Mbrreg_Member($database, $custom_fields, new Mbrreg_Email());
$statuses = Mbrreg_Member::get_statuses();

$members = $member_handler->get_all($args);
$total_members = $member_handler->count($args);
$total_pages = ceil($total_members / $per_page);

// Count by status.
$count_all = $member_handler->count(array('search' => $search));
$count_active = $member_handler->count(array('status' => 'active', 'search' => $search));
$count_inactive = $member_handler->count(array('status' => 'inactive', 'search' => $search));
$count_pending = $member_handler->count(array('status' => 'pending', 'search' => $search));
?>

<div class="wrap mbrreg-admin-wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e('Members', 'member-registration-plugin'); ?></h1>
	<hr class="wp-header-end">

	<!-- Status Filter -->
	<ul class="subsubsub">
		<li>
			<a href="<?php echo esc_url(admin_url('admin.php?page=mbrreg-members')); ?>"
				class="<?php echo '' === $status ? 'current' : ''; ?>">
				<?php esc_html_e('All', 'member-registration-plugin'); ?>
				<span class="count">(<?php echo esc_html($count_all); ?>)</span>
			</a> |
		</li>
		<li>
			<a href="<?php echo esc_url(admin_url('admin.php?page=mbrreg-members&status=active')); ?>"
				class="<?php echo 'active' === $status ? 'current' : ''; ?>">
				<?php esc_html_e('Active', 'member-registration-plugin'); ?>
				<span class="count">(<?php echo esc_html($count_active); ?>)</span>
			</a> |
		</li>
		<li>
			<a href="<?php echo esc_url(admin_url('admin.php?page=mbrreg-members&status=inactive')); ?>"
				class="<?php echo 'inactive' === $status ? 'current' : ''; ?>">
				<?php esc_html_e('Inactive', 'member-registration-plugin'); ?>
				<span class="count">(<?php echo esc_html($count_inactive); ?>)</span>
			</a> |
		</li>
		<li>
			<a href="<?php echo esc_url(admin_url('admin.php?page=mbrreg-members&status=pending')); ?>"
				class="<?php echo 'pending' === $status ? 'current' : ''; ?>">
				<?php esc_html_e('Pending', 'member-registration-plugin'); ?>
				<span class="count">(<?php echo esc_html($count_pending); ?>)</span>
			</a>
		</li>
	</ul>

	<!-- Search Form -->
	<form method="get" action="<?php echo esc_url(admin_url('admin.php')); ?>" class="search-form">
		<input type="hidden" name="page" value="mbrreg-members">
		<?php if ($status): ?>
			<input type="hidden" name="status" value="<?php echo esc_attr($status); ?>">
		<?php endif; ?>
		<p class="search-box">
			<label class="screen-reader-text"
				for="member-search-input"><?php esc_html_e('Search Members', 'member-registration-plugin'); ?></label>
			<input type="search" id="member-search-input" name="s" value="<?php echo esc_attr($search); ?>"
				placeholder="<?php esc_attr_e('Search members...', 'member-registration-plugin'); ?>">
			<input type="submit" id="search-submit" class="button"
				value="<?php esc_attr_e('Search', 'member-registration-plugin'); ?>">
		</p>
	</form>

	<!-- Bulk Actions Form -->
	<form id="mbrreg-members-form" method="post">
		<div class="tablenav top">
			<div class="alignleft actions bulkactions">
				<select name="bulk_action" id="bulk-action-selector">
					<option value=""><?php esc_html_e('Bulk Actions', 'member-registration-plugin'); ?></option>
					<option value="activate"><?php esc_html_e('Activate', 'member-registration-plugin'); ?></option>
					<option value="deactivate"><?php esc_html_e('Deactivate', 'member-registration-plugin'); ?>
					</option>
					<option value="delete"><?php esc_html_e('Delete Members', 'member-registration-plugin'); ?>
					</option>
					<option value="delete_with_user">
						<?php esc_html_e('Delete Members + Users', 'member-registration-plugin'); ?>
					</option>
				</select>
				<button type="button"
					class="button mbrreg-bulk-action-btn"><?php esc_html_e('Apply', 'member-registration-plugin'); ?></button>
			</div>

			<?php if ($total_pages > 1): ?>
				<div class="tablenav-pages">
					<span class="displaying-num">
						<?php
						printf(
							/* translators: %s: Number of members */
							esc_html(_n('%s member', '%s members', $total_members, 'member-registration-plugin')),
							esc_html(number_format_i18n($total_members))
						);
						?>
					</span>
					<span class="pagination-links">
						<?php
						$page_links = paginate_links(
							array(
								'base' => add_query_arg('paged', '%#%'),
								'format' => '',
								'prev_text' => '&laquo;',
								'next_text' => '&raquo;',
								'total' => $total_pages,
								'current' => $paged,
							)
						);
						echo wp_kses_post($page_links);
						?>
					</span>
				</div>
			<?php endif; ?>
		</div>

		<!-- Members Table -->
		<table class="wp-list-table widefat fixed striped members">
			<thead>
				<tr>
					<td class="manage-column column-cb check-column">
						<input type="checkbox" id="cb-select-all">
					</td>
					<th scope="col" class="manage-column"><?php esc_html_e('Name', 'member-registration-plugin'); ?>
					</th>
					<th scope="col" class="manage-column"><?php esc_html_e('Email', 'member-registration-plugin'); ?>
					</th>
					<th scope="col" class="manage-column">
						<?php esc_html_e('Account Owner', 'member-registration-plugin'); ?>
					</th>
					<th scope="col" class="manage-column"><?php esc_html_e('Status', 'member-registration-plugin'); ?>
					</th>
					<th scope="col" class="manage-column"><?php esc_html_e('Admin', 'member-registration-plugin'); ?>
					</th>
					<th scope="col" class="manage-column">
						<?php esc_html_e('Registered', 'member-registration-plugin'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($members)): ?>
					<?php foreach ($members as $member): ?>
						<tr>
							<th scope="row" class="check-column">
								<input type="checkbox" name="member_ids[]" value="<?php echo esc_attr($member->id); ?>">
							</th>
							<td>
								<strong>
									<a
										href="<?php echo esc_url(admin_url('admin.php?page=mbrreg-members&action=edit&member_id=' . $member->id)); ?>">
										<?php echo esc_html($member->first_name . ' ' . $member->last_name); ?>
									</a>
								</strong>
								<div class="row-actions">
									<span class="edit">
										<a
											href="<?php echo esc_url(admin_url('admin.php?page=mbrreg-members&action=edit&member_id=' . $member->id)); ?>">
											<?php esc_html_e('Edit', 'member-registration-plugin'); ?>
										</a> |
									</span>
									<?php if ('pending' === $member->status): ?>
										<span class="resend">
											<a href="#" class="mbrreg-resend-activation"
												data-member-id="<?php echo esc_attr($member->id); ?>">
												<?php esc_html_e('Resend Activation', 'member-registration-plugin'); ?>
											</a> |
										</span>
									<?php endif; ?>
									<span class="delete">
										<a href="#" class="mbrreg-delete-member submitdelete"
											data-member-id="<?php echo esc_attr($member->id); ?>">
											<?php esc_html_e('Delete', 'member-registration-plugin'); ?>
										</a>
									</span>
								</div>
							</td>
							<td><?php echo esc_html($member->email); ?></td>
							<td>
								<?php
								$user = get_user_by('ID', $member->user_id);
								echo esc_html($user ? $user->user_login : '-');
								?>
							</td>
							<td>
								<span class="mbrreg-status mbrreg-status-<?php echo esc_attr($member->status); ?>">
									<?php echo esc_html($statuses[$member->status]); ?>
								</span>
							</td>
							<td>
								<?php if ($member->is_admin): ?>
									<span class="dashicons dashicons-yes-alt" style="color: green;"
										title="<?php esc_attr_e('Member Admin', 'member-registration-plugin'); ?>"></span>
								<?php else: ?>
									<span class="dashicons dashicons-minus" style="color: #999;"></span>
								<?php endif; ?>
							</td>
							<td><?php echo esc_html(mbrreg_format_date($member->created_at)); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="7"><?php esc_html_e('No members found.', 'member-registration-plugin'); ?></td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>

		<div class="tablenav bottom">
			<?php if ($total_pages > 1): ?>
				<div class="tablenav-pages">
					<span class="pagination-links">
						<?php echo wp_kses_post($page_links); ?>
					</span>
				</div>
			<?php endif; ?>
		</div>
	</form>
</div>