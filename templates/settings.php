<?php
if ( !defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}
?>

<div class="wrap">
	<div class="spamlytics-container">
		<div class="spamlytics-left">
			<h1 class="spamlytics-header">
				<img src="<?php echo plugins_url( SPAMLYTICS_PLUGIN_ROOT ); ?>/assets/images/logo_spamlytics_100.png" class="spamlytics-logo-in-header" />SpamLytics - <?php _e( 'Settings', 'spam-prevention-by-spamlytics' ); ?>
			</h1>

			<form method="post" action="<?php echo admin_url( 'admin.php?page=spam_settings' ); ?>" enctype="multipart/form-data">
				<?php wp_nonce_field( 'spam_settings_nonce', 'spam_settings_nonce' ); ?>

				<h3 class="spamlytics-header"><?php _e( 'SpamLytics API Key', 'spam-prevention-by-spamlytics' ); ?></h3>
				<table class="form-table spamlytics-settings-table">
					<tr>
						<td class="description" colspan="2">
							<p><?php printf( __( 'This is your site API key for SpamLytics. With this free API key you\'ll receive the latest updates for spam protection on %s.', 'spam-prevention-by-spamlytics' ), esc_attr( get_bloginfo( 'sitename' ) ) ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'API Key', 'spam-prevention-by-spamlytics' ); ?></th>
						<td>
							<label><input type="text" name="spam_settings[api_key]" class="regular-text" value="<?php echo esc_attr( $this->settings['api_key'] ); ?>" disabled="disabled" /></label>
							<br />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Contact email address', 'spam-prevention-by-spamlytics' ); ?></th>
						<td><?php echo esc_attr( $this->settings['api_contact'] ); ?></td>
					</tr>
					<tr>
						<td class="description" colspan="2">
							<p><?php printf( __( 'Help us with sending some diagnostic data about spam on your website. We don\'t get any personal or site information, and it won\'t be able for us to track it down to your website. We can improve our spam protection service for everyone with this feature enabled.', 'spam-prevention-by-spamlytics' ), esc_attr( get_bloginfo( 'sitename' ) ) ); ?>
								<a href="https://spamlytics.com/sent-anonymous-data-in-the-wp-plugin/?utm_source=WordPress&utm_medium=referral&utm_campaign=spam_settings" target="_blank"><?php _e( 'Read more about the data we\'ll receive &raquo;', 'spam-prevention-by-spamlytics' ); ?></a>
							</p></td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Sent anonymous data', 'spam-prevention-by-spamlytics' ); ?></th>
						<td>
							<label><input type="radio" name="spam_settings[sent_data]" value="true" <?php echo checked( $this->settings['sent_data'], true ); ?> /> <?php _e( 'Yes, sent anonymous data to SpamLytics', 'spam-prevention-by-spamlytics' ); ?>
							</label> <br />
							<label><input type="radio" name="spam_settings[sent_data]" value="false" <?php echo checked( $this->settings['sent_data'], false ); ?> /> <?php _e( 'No, thanks', 'spam-prevention-by-spamlytics' ); ?>
							</label> <br />
						</td>
					</tr>
				</table>

				<h3 class="spamlytics-header"><?php _e( 'Comment spam filtering', 'spam-prevention-by-spamlytics' ); ?></h3>
				<table class="form-table spamlytics-settings-table">
					<tr>
						<td class="description" colspan="2">
							<p><?php printf( __( 'Your comments are validated by SpamLytics automatically. By default, we approve your comments when they\'ve passed the comment spam validation. You can verify up to %s comments per month. This limit could be upgraded %son our website%s.', 'spam-prevention-by-spamlytics' ), esc_attr( number_format_i18n( $this->comment_stats['month_limit'], 0 ) ), '<a href="https://spamlytics.com/comment-spam-filtering-wp/?utm_source=WordPress&utm_medium=referral&utm_campaign=spam_settings" target="_blank">', '</a>' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Approve comments', 'spam-prevention-by-spamlytics' ); ?></th>
						<td>
							<label><input type="radio" name="spam_settings[mark_as_approved]" value="true" <?php echo checked( $this->settings['mark_as_approved'], true ); ?> /> <?php _e( 'Yes, auto approve comments after a successful spam verification', 'spam-prevention-by-spamlytics' ); ?>
							</label> <br />
							<label><input type="radio" name="spam_settings[mark_as_approved]" value="false" <?php echo checked( $this->settings['mark_as_approved'], false ); ?> /> <?php _e( 'No, keep them unapproved', 'spam-prevention-by-spamlytics' ); ?>
							</label> <br />
						</td>
					</tr>
				</table>

				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save settings', 'apg' ); ?>" />
				</p>
			</form>

		</div>
		<div class="spamlytics-right">
			<?php include( SPAMLYTICS_PLUGIN_PATH . 'templates/sidebar.php' ); ?>
		</div>
	</div>
</div>