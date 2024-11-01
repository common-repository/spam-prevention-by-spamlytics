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
			<h1 class="spamlytics-header"><img src="<?php echo plugins_url( SPAMLYTICS_PLUGIN_ROOT ); ?>/assets/images/logo_spamlytics_100.png" class="spamlytics-logo-in-header" />SpamLytics - <?php _e( 'Activity log', 'spam-prevention-by-spamlytics' ); ?></h1>

			<h3 class="spamlytics-header spamlytics-header-activity"><?php printf( __('Last 50 Activity Logs on %s','spam-prevention-by-spamlytics'), get_bloginfo('sitename') ); ?></h3>
			<table class="form-table spamlytics-settings-table spamlytics-activity-table">
				<?php foreach( $this->posts as $post ): ?>
					<tr>
						<td><?php
							switch( $post->post_title ) {
								case 'BLOCK':
									echo '<span class="spamlytics-type-block">BLOCKED</span>';
									break;
								case 'LOGIN':
									echo '<span class="spamlytics-type-login">LOGIN</span>';
									break;
								case 'LOGOUT':
									echo '<span class="spamlytics-type-login">LOGOUT</span>';
									break;
							}
							?></td>
						<td><strong><?php echo esc_attr( get_post_meta( $post->ID, 'spam_type', true ) ); ?></strong></td>
						<td><?php echo esc_attr( get_post_meta( $post->ID, 'ip_address', true ) ); ?></td>
						<td><?php echo esc_attr( $post->post_date ); ?></td>
						<td><a href="#" onclick="spamlytics_open('<?php echo esc_attr( $post->ID ); ?>');">Details</a></td>
					</tr>
					<tr class="spamlytics-hidden" id="spamlyticscontent-<?php echo esc_attr( $post->ID ); ?>">
						<td colspan="5"><?php echo esc_attr( $post->post_content ); ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
		<div class="spamlytics-right">
			<?php include( SPAMLYTICS_PLUGIN_PATH . 'templates/sidebar.php' ); ?>

		</div>
	</div>
</div>