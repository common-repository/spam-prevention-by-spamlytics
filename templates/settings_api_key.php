<?php
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

global $current_user;
get_currentuserinfo();
?>

<div class="wrap">
	<div class="spamlytics-container">
		<div class="spamlytics-left">
			<h1 class="spamlytics-header"><img src="<?php echo plugins_url( SPAMLYTICS_PLUGIN_ROOT ); ?>/assets/images/logo_spamlytics_100.png" class="spamlytics-logo-in-header" />SpamLytics - <?php _e( 'Settings', 'spam-prevention-by-spamlytics' ); ?></h1>

			<div class="spamlytics-highlight">
				<p>
					<?php _e('We want to sent you free daily updates with the newest blacklisted domains for Google Analytics. You\'ll need to generate an API key for free with this form to get started.','spam-prevention-by-spamlytics'); ?>
				</p>
			</div>

			<form method="post" action="<?php echo admin_url('admin.php?page=spam_settings&setapi=true'); ?>" enctype="multipart/form-data">
				<?php wp_nonce_field( 'spam_settings_nonce', 'spam_settings_nonce' ); ?>

				<h3 class="spamlytics-header"><?php _e('Generate a free SpamLytics API Key','spam-prevention-by-spamlytics'); ?></h3>
				<table class="form-table spamlytics-settings-table">
					<tr><td class="description" colspan="2"><p><?php _e('We will only use this email address to sent you a copy of the API key for backup purposes. If you\'ve enabled the newsletter, we will sent it to this email address too.', 'spam-prevention-by-spamlytics'); ?></p></td></tr>
					<tr>
						<th scope="row"><?php _e('Contact email address', 'spam-prevention-by-spamlytics' ); ?></th>
						<td>
							<label><input type="text" name="spam_api[email]" class="regular-text" value="<?php echo esc_attr( $current_user->user_email ); ?>" /></label> <br />
						</td>
					</tr>
					<tr><td class="description" colspan="2"><p><?php _e('Would you like to receive the free weekly newsletter about SpamLytics with tips & tricks for Google Analytics and spam prevention tips?', 'spam-prevention-by-spamlytics'); ?></p></td></tr>
					<tr>
						<th scope="row"><?php _e('Newsletter', 'spam-prevention-by-spamlytics' ); ?></th>
						<td>
							<label><input type="radio" name="spam_api[newsletter]" value="true" /> <?php _e( 'Yes, sent me the weekly newsletter!', 'spam-prevention-by-spamlytics' ); ?></label><br /><label><input type="radio" name="spam_api[newsletter]" value="false"<?php checked( false, false ); ?> /> <?php _e( 'No, thanks', 'spam-prevention-by-spamlytics' ); ?></label> <br />
						</td>
					</tr>
				</table>

				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Generate API Key', 'apg'); ?>" /></p>
			</form>

		</div>
		<div class="spamlytics-right">
			<?php include( SPAMLYTICS_PLUGIN_PATH . 'templates/sidebar.php' ); ?>
		</div>
	</div>
</div>