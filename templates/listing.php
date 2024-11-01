<?php
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}
?>

<div class="wrap">
	<div class="spamlytics-container">
		<div class="spamlytics-left">
			<h1 class="spamlytics-header"><img src="<?php echo plugins_url( SPAMLYTICS_PLUGIN_ROOT ); ?>/assets/images/logo_spamlytics_100.png" class="spamlytics-logo-in-header" />SpamLytics - <?php printf( __('Block %s', 'spam-prevention-by-spamlytics'), esc_attr( $this->title ) ); ?></h1>

			<div class="spamlytics-highlight">
				<p>
					<?php printf( __('In this section you can block custom %s. SpamLytics blocks by default the most common Google Analytics referral URLs. Those URLs will be updated daily, and they are not shown in this section.','spam-prevention-by-spamlytics'), $this->title ); ?>
				</p>
				<p><a href="https://spamlytics.com/referral-spam/?utm_source=WordPress&utm_medium=referral&utm_campaign=spam_listing" class="button" target="_blank"><?php _e('View GA referral spam blacklist'); ?> <span class="dashicons dashicons-external"></span></a></p>
				<hr>
				<p>
					<?php printf( __('You can add extra %s here to block the visits and access to your website.','spam-prevention-by-spamlytics'),  $this->title ); ?>
				</p>

				<form method="post" action="<?php echo admin_url('admin.php?page=' . $this->type ); ?>">
					<?php wp_nonce_field( 'spam_listing_nonce', 'spam_listing_nonce' ); ?>
					<table class="form-table">
						<tr>
							<th scope="row"><?php printf( __('Block new %s', 'spam-prevention-by-spamlytics' ), $this->single_title ); ?></th>
							<td>
								<label><input type="text" name="spam_listing_new" class="regular-text" value="" placeholder="<?php printf( __('Enter new value'), $this->type ); ?>" /></label>
								<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Add', 'apg'); ?>" />
							</td>
						</tr>
					</table>
				</form>
			</div>

			<h3 class="spamlytics-header spamlytics-header-activity"><?php echo esc_attr( $this->title ); ?></h3>
			<table class="form-table spamlytics-settings-table spamlytics-activity-table">
				<?php $remove_nonce = wp_create_nonce( 'spamlytics-listing-delete' ); foreach( $this->data as $post ): ?>
					<tr>
						<td><strong><?php echo esc_attr( $post ); ?></strong></td>
						<td><a href="<?php echo admin_url('admin.php?page=' . $this->type . '&delete=' . $post . '&spam_nonce=' . $remove_nonce); ?>"><?php _e('Remove','spam-prevention-by-spamlytics'); ?></a></td>
					</tr>
				<?php endforeach; if( count( $this->data ) == 0 ) { echo '<tr><td>' . __('No custom blocking rules found, you can add one above.','spam-prevention-by-spamlytics') . '</td></tr>'; } ?>
			</table>
		</div>
		<div class="spamlytics-right">
			<?php include( SPAMLYTICS_PLUGIN_PATH . 'templates/sidebar.php' ); ?>
		</div>
	</div>
</div>