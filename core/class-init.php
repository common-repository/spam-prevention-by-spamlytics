<?php

namespace SpamLytics\Core;

/**
 * Class Init
 * @package SpamLytics\Core
 */
class Init {

	/**
	 * The plugin is initiated. Let's rock!
	 */
	public function init_plugin() {

		if ( is_admin() ) {
			// Backend, start manager
			new \SpamLytics\Admin\Init();

			// Check for new referral spam updates
			add_action( 'shutdown', array( $this, 'spamlytics_new_daily_data' ) );
		} else {
			// Frontend, start blocker
			new \SpamLytics\Frontend\Init();
		}

		add_action( 'wp_login', array( $this, 'spamlytics_user_login' ), 10, 2 );
		add_action( 'wp_logout', array( $this, 'spamlytics_user_logout' ), 10, 2 );
	}

	/**
	 * Log when a user is logged in
	 *
	 * @param $user_login
	 * @param $user
	 */
	public function spamlytics_user_login( $user_login, $user ) {
		$post_id = wp_insert_post( array(
			'post_title'   => 'LOGIN',
			'post_content' => 'User ' . $user_login . ' logged in from ' . $_SERVER['REMOTE_ADDR'] . ' with hostname ' . $_SERVER['REMOTE_HOST'],
			'post_status'  => 'inherit',
			'post_type'    => 'spamlytics_log',
		) );

		update_post_meta( $post_id, 'ip_address', $_SERVER['REMOTE_ADDR'] );
		update_post_meta( $post_id, 'type', 'LOGIN' );
		update_post_meta( $post_id, 'spam_type', 'User' );
	}

	/**
	 * Log when a user is logged in
	 *
	 * @param $user_login
	 * @param $user
	 */
	public function spamlytics_user_logout( $user_login, $user ) {
		$post_id = wp_insert_post( array(
			'post_title'   => 'LOGOUT',
			'post_content' => 'User ' . $user_login . ' logged out from ' . $_SERVER['REMOTE_ADDR'],
			'post_status'  => 'inherit',
			'post_type'    => 'spamlytics_log',
		) );

		update_post_meta( $post_id, 'ip_address', $_SERVER['REMOTE_ADDR'] );
		update_post_meta( $post_id, 'type', 'LOGOUT' );
		update_post_meta( $post_id, 'spam_type', 'User' );
	}

	/**
	 *
	 */
	public function spamlytics_new_daily_data() {
		$settings = \SpamLyticsHelper::get_settings();

		if ( $settings['api_key'] !== '' ) {
			$fetcher = new Fetcher( $settings['api_key'] );
			$fetcher->check_for_updates();
		}
	}

}