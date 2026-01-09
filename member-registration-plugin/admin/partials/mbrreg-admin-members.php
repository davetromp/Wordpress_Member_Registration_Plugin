<?php
/**
 * Admin members list template.
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
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Members', 'member-registration-plugin' ); ?></h1>
	<hr class="wp-header-end">

	<!-- Status Filter Links -->
	<ul class="subsubsub">
		<li class="all">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members' ) ); ?>" <?php echo empty( $status ) ? 'class="current"' : ''; ?>>
				<?php esc_html_e( 'All', 'member-registration-plugin' ); ?>
				<span class="count">(<?php echo esc_html( $status_counts['all'] ); ?>)</span>
			</a> |
		</li>
		<li class="active">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&status=active' ) ); ?>" <?php echo 'active' === $status ? 'class="current"' : ''; ?>>
				<?php esc_html_e( 'Active', 'member-registration-plugin' ); ?>
				<span class="count">(<?php echo esc_html( $status_counts['active'] ); ?>)</span>
			</a> |
		</li>
		<li class="inactive">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&status=inactive' ) ); ?>" <?php echo 'inactive' === $status ? 'class="current"' : ''; ?>>
				<?php esc_html_e( 'Inactive', 'member-registration-plugin' ); ?>
				<span class="count">(<?php echo esc_html( $status_counts['inactive'] ); ?>)</span>
			</a> |
		</li>
		<li class="pending">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&status=pending' ) ); ?>" <?php echo 'pending' === $status ? 'class="current"' : ''; ?>>
				<?php esc_html_e( 'Pending', 'member-registration-plugin' ); ?>
				<span class="count">(<?php echo esc_html( $status_counts['pending'] ); ?>)</span>
			</a>
		</li>
	</ul>

	<!-- Search Form -->
	<form method="get" class="mbrreg-search-form">
		<input type="hidden" name="page" value="mbrreg-members">
		<?php if ( $status ) : ?>
			<input type="hidden" name="status" value="<?php echo esc_attr( $status ); ?>">
		<?php endif; ?>
		<p class="search-box">
			<input type="search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search members...', 'member-registration-plugin' ); ?>">
			<input type="submit" class="button" value="<?php esc_attr_e( 'Search', 'member-registration-plugin' ); ?>">
		</p>
	</form>

	<form method="post" id="mbrreg-members-form">
		<!-- Bulk Actions -->
		<div class="tablenav top">
			<div class="alignleft actions bulkactions">
				<select name="bulk_action" id="mbrreg-bulk-action">
					<option value=""><?php esc_html_e( 'Bulk Actions', 'member-registration-plugin' ); ?></option>
					<option value="activate"><?php esc_html_e( 'Activate', 'member-registration-plugin' ); ?></option>
					<option value="deactivate"><?php esc_html_e( 'Deactivate', 'member-registration-plugin' ); ?></option>
					<option value="delete"><?php esc_html_e( 'Delete', 'member-registration-plugin' ); ?></option>
					<option value="delete_with_user"><?php esc_html_e( 'Delete (with WP user)', 'member-registration-plugin' ); ?></option>
				</select>
				<button type="button" id="mbrreg-bulk-action-btn" class="button action">
					<?php esc_html_e( 'Apply', 'member-registration-plugin' ); ?>
				</button>
			</div>

			<!-- Pagination -->
			<?php if ( $total_pages > 1 ) : ?>
				<div class="tablenav-pages">
					<span class="displaying-num">
						<?php
						printf(
							/* translators: %s: Number of items */
							esc_html( _n( '%s item', '%s items', $total, 'member-registration-plugin' ) ),
							esc_html( number_format_i18n( $total ) )
						);
						?>
					</span>
					<span class="pagination-links">
						<?php
						$pagination_args = array(
							'base'      => add_query_arg( 'paged', '%#%' ),
							'format'    => '',
							'total'     => $total_pages,
							'current'   => $paged,
							'prev_text' => '&laquo;',
							'next_text' => '&raquo;',
						);
						echo paginate_links( $pagination_args ); // phpcs:ignore
						?>
					</span>
				</div>
			<?php endif; ?>
		</div>

		<!-- Members Table -->
		<table class="wp-list-table widefat fixed striped mbrreg-members-table">
			<thead>
				<tr>
					<td class="manage-column column-cb check-column">
						<input type="checkbox" id="cb-select-all">
					</td>
					<th class="manage-column column-username"><?php esc_html_e( 'Username', 'member-registration-plugin' ); ?></th>
					<th class="manage-column column-name"><?php esc_html_e( 'Name', 'member-registration-plugin' ); ?></th>
					<th class="manage-column column-email"><?php esc_html_e( 'Email', 'member-registration-plugin' ); ?></th>
					<th class="manage-column column-status"><?php esc_html_e( 'Status', 'member-registration-plugin' ); ?></th>
					<th class="manage-column column-admin"><?php esc_html_e( 'Admin', 'member-registration-plugin' ); ?></th>
					<th class="manage-column column-registered"><?php esc_html_e( 'Registered', 'member-registration-plugin' ); ?></th>
					<th class="manage-column column-actions"><?php esc_html_e( 'Actions', 'member-registration-plugin' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $members ) ) : ?>
					<tr>
						<td colspan="8"><?php esc_html_e( 'No members found.', 'member-registration-plugin' ); ?></td>
					</tr>
				<?php else : ?>
					<?php foreach ( $members as $member_item ) : ?>
						<tr data-member-id="<?php echo esc_attr( $member_item->id ); ?>">
							<th scope="row" class="check-column">
								<input type="checkbox" name="member_ids[]" value="<?php echo esc_attr( $member_item->id ); ?>">
							</th>
							<td class="column-username">
								<strong>
									<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&action=edit&member_id=' . $member_item->id ) ); ?>">
										<?php echo esc_html( $member_item->username ); ?>
									</a>
								</strong>
							</td>
							<td class="column-name">
								<?php
								$full_name = trim( $member_item->first_name . ' ' . $member_item->last_name );
								echo esc_html( $full_name ? $full_name : '—' );
								?>
							</td>
							<td class="column-email">
								<a href="mailto:<?php echo esc_attr( $member_item->email ); ?>">
									<?php echo esc_html( $member_item->email ); ?>
								</a>
							</td>
							<td class="column-status">
								<span class="mbrreg-status mbrreg-status-<?php echo esc_attr( $member_item->status ); ?>">
									<?php echo esc_html( Mbrreg_Member::$statuses[ $member_item->status ] ); ?>
								</span>
							</td>
							<td class="column-admin">
								<?php if ( $member_item->is_admin ) : ?>
									<span class="dashicons dashicons-yes-alt" style="color: #46b450;"></span>
								<?php else : ?>
									<span class="dashicons dashicons-minus" style="color: #999;"></span>
								<?php endif; ?>
							</td>
							<td class="column-registered">
								<?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $member_item->created_at ) ) ); ?>
							</td>
							<td class="column-actions">
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=mbrreg-members&action=edit&member_id=' . $member_item->id ) ); ?>" class="button button-small">
									<?php esc_html_e( 'Edit', 'member-registration-plugin' ); ?>
								</a>
								<?php if ( 'pending' === $member_item->status ) : ?>
									<button type="button" class="button button-small mbrreg-resend-activation" data-member-id="<?php echo esc_attr( $member_item->id ); ?>">
										<?php esc_html_e( 'Resend', 'member-registration-plugin' ); ?>
									</button>
								<?php endif; ?>
								<button type="button" class="button button-small button-link-delete mbrreg-delete-member" data-member-id="<?php echo esc_attr( $member_item->id ); ?>">
									<?php esc_html_e( 'Delete', 'member-registration-plugin' ); ?>
								</button>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</form>
</div>