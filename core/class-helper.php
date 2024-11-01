<?php

/**
 * Class SpamLyticsHelper
 */
class SpamLyticsHelper {

	/**
	 * Get the spam types which are available
	 *
	 * @return array
	 */
	public static function get_spam_types() {
		return array(
			'ga' => 'Google Analytics Referral Spam',
			'ip' => 'Block an IP address',
		);
	}

	/**
	 * Get the SpamLytics settings
	 *
	 * @return mixed
	 */
	public static function get_settings() {
		return array_merge( SpamLyticsHelper::default_settings(), get_option( 'spamlytics', array() ) );
	}

	/**
	 * Find the GA referral
	 *
	 * @param $url
	 *
	 * @return bool
	 */
	public static function get_ga_url( $url ) {
		$posts = get_posts( array(
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'post_type'      => 'spamlytics',
			'post_title'     => $url,
			'meta_key'       => 'spamlytics_type',
			'meta_value'     => 'ga',
		) );

		if ( isset( $posts[0]->ID ) ) {
			if ( ( $type = get_post_meta( $posts[0]->ID, 'spamlytics_type', true ) ) && $type === 'ga' ) {
				return $posts[0];
			}
		}

		return false;
	}

	/**
	 * Checks if the remote IP is blocked
	 *
	 * @param $ip
	 *
	 * @return bool
	 */
	public function is_ip_blocked( $ip ) {
		$blocked = get_option( 'spamlytics_ip_addresses', array() );

		if ( in_array( $ip, $blocked ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Checks if the referral is blocked
	 *
	 * @param $referral
	 *
	 * @return bool
	 */
	public function is_referral_blocked( $referral ) {
		$blocked = get_option( 'spamlytics_referrals', array() );

		if ( in_array( $referral, $blocked ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get the default settings
	 *
	 * @return array
	 */
	private static function default_settings() {
		return array(
			'module_ga'        => true,
			'module_ip'        => true,
			'module_log'       => true,
			'sent_data'        => false,
			'api_key'          => '',
			'mark_as_approved' => true,
		);
	}

}