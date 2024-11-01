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
			<h1 class="spamlytics-header"><img src="<?php echo plugins_url( SPAMLYTICS_PLUGIN_ROOT ); ?>/assets/images/logo_spamlytics_100.png" class="spamlytics-logo-in-header" />SpamLytics - <?php _e( 'Overview', 'spam-prevention-by-spamlytics' ); ?></h1>

			<div class="spamlytics-highlight" style="width: 94%;">
				<table class="spamlytics-table">
					<tr>
						<td align="center">
							<h5><?php _e('SpamLytics API Key','spam-prevention-by-spamlytics'); ?></h5><br />
							<span class="spamlytics-valid"><?php _e('Valid and Activated','spam-prevention-by-spamlytics'); ?></span>
						</td>
						<td align="center">
							<h5><?php _e('Last updated','spam-prevention-by-spamlytics'); ?></h5><br />
							<span class="spamlytics-number"><?php echo esc_attr( $this->last_update ); ?></span>
						</td>
						<td align="center">
							<h5><?php _e('Comment spam','spam-prevention-by-spamlytics'); ?></h5><br />
							<span class="spamlytics-number"><i><strong<?php if( $this->comment_stats['month_total'] >= $this->comment_stats['month_limit'] ) { echo ' style="color:red;"'; } ?>>
										<?php echo esc_attr( number_format_i18n( $this->comment_stats['month_total'], 0 ) ); ?>
									</strong> /
									<?php echo esc_attr( number_format_i18n( $this->comment_stats['month_limit'], 0 ) ); ?>
								</i></span><br />
							<?php _e( 'Checks per month', 'spam-prevention-by-spamlytics' ); ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
						<td align="center">
							<a href="https://spamlytics.com/comment-spam-filtering-wp/?utm_source=WordPress&utm_medium=referral&utm_campaign=spam_upsell_overview" class="button button-primary" target="_blank"><?php _e( 'Upgrade now!', 'spam-prevention-by-spamlytics' ); ?></a>
						</td>
					</tr>
					<tr>
						<td colspan="3" align="center">
							<p align="center"><i><?php printf( __('%sYour API key:%s %s', 'spam-prevention-by-spamlytics' ), '<strong>', '</strong>', $this->api_key ); ?></i></p>
							<?php if( $this->comment_stats['month_total'] >= $this->comment_stats['month_limit'] ) { ?>
							<p align="center" style="color: orange; font-weight: 600;">
							<?php printf( __('You\'ve reached your monthly limit, please %supgrade your license &raquo;%s.', 'spam-prevention-by-spamlytics'),
									'<a href="https://spamlytics.com/comment-spam-filtering-wp/?utm_source=WordPress&utm_medium=referral&utm_campaign=spam_upsell_overview" target="_blank">',
									'</a>'); } ?></p>
						</td>
					</tr>
				</table>
			</div>

			<h3 class="spamlytics-header"><?php _e( 'Comment Spam Statistics', 'spam-prevention-by-spamlytics' ); ?></h3>
			<table class="form-table spamlytics-settings-table">
				<tr>
					<td width="50%">
						<canvas id="canvas" height="60" width="80%"></canvas>
						<p align="center"><i>Today's statistics</i></p>
					</td>
					<td width="50%">
						<table class="form-table">
							<tr>
								<th><?php _e( 'Today total', 'spam-prevention-by-spamlytics' ); ?></th>
								<td align="right"><?php echo esc_attr( number_format_i18n( $this->comment_stats['today_total'] ) ); ?></td>
							</tr>
							<tr>
								<th><?php _e( 'Today spam', 'spam-prevention-by-spamlytics' ); ?></th>
								<td align="right"><?php echo esc_attr( number_format_i18n( $this->comment_stats['today_spam'] ) ); ?></td>
							</tr>
							<tr>
								<th><?php _e( 'Today valid', 'spam-prevention-by-spamlytics' ); ?></th>
								<td align="right"><?php echo esc_attr( number_format_i18n( $this->comment_stats['today_ok'] ) ); ?></td>
							</tr>
							<tr>
								<th><?php _e( 'Month total', 'spam-prevention-by-spamlytics' ); ?></th>
								<td align="right"><?php echo esc_attr( number_format_i18n( $this->comment_stats['month_total'] ) ); ?></td>
							</tr>
							<tr>
								<th><?php _e( 'Month limit', 'spam-prevention-by-spamlytics' ); ?></th>
								<td align="right">
									<?php echo esc_attr( number_format_i18n( $this->comment_stats['month_limit'] ) ); ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<div class="spamlytics-right">
			<?php include( SPAMLYTICS_PLUGIN_PATH . 'templates/sidebar.php' ); ?>
		</div>
	</div>
</div>

<script>
	var overviewSpamLytics = [
		{
			value: <?php echo esc_attr( number_format_i18n( $this->comment_stats['today_total'] ) ); ?>,
			color:"#F7464A",
			highlight: "#FF5A5E",
			label: "Spam comments"
		},
		{
			value: <?php echo esc_attr( number_format_i18n( $this->comment_stats['today_spam'] ) ); ?>,
			color: "#4298b5",
			highlight: "#5AD3D1",
			label: "Safe comments"
		}
	];

	window.onload = function(){
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myLine = new Chart(ctx).Pie(overviewSpamLytics, {
			responsive: true
		});
	}
</script>