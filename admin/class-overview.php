<?php

namespace SpamLytics\Admin;

/**
 * Class Overview
 * @package SpamLytics\Admin
 */
class Overview {

	private $api_key, $spam_urls, $last_update, $comment_stats;

	/**
	 * Show the latest activity logs and some basic info
	 */
	public function render() {
		$this->posts = get_posts( array(
			'post_type'      => 'spamlytics_log',
			'post_status'    => 'inherit',
			'posts_per_page' => 5,
		) );

		if( ( $manual = filter_input( INPUT_GET, 'manual_update_urls' ) ) && $manual !== '' ) {
			delete_transient( 'spamlytics_ga_referral_updated' );
			exit;
		}

		$spamlytics_option = get_option( 'spamlytics', '' );
		if( isset( $spamlytics_option['api_key'] ) ) {
			$this->api_key = $spamlytics_option['api_key'];
		}
		else {
			$this->api_key = 'Not set';
		}

		$this->spam_urls = get_option( 'spamlytics_ga_urls', array() );
		if ( ( $last_update = get_transient( 'spamlytics_ga_referral_updated' ) ) && $last_update !== false ) {
			$this->last_update = date( 'd M Y', $last_update );
		} else {
			$this->last_update = __( 'Never', 'spam-prevention-by-spamlytics' );
		}

		$this->comment_stats = get_transient( 'spamlytics_comment_stats' );

		include( SPAMLYTICS_PLUGIN_PATH . '/templates/overview.php' );
	}

}