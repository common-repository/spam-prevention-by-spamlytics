<?php

namespace SpamLytics\Core;

/**
 * Class Fetcher
 * @package SpamLytics\Core
 */
class Fetcher {

	/**
	 * @var
	 */
	private $last_fetch;

	/**
	 * @var
	 */
	private $api_key;

	/**
	 * Construct the fetcher
	 *
	 * @param $api_key
	 */
	public function __construct( $api_key ) {
		$this->last_fetch = get_transient( 'spamlytics_ga_referral_updated' );
		$this->api_key    = $api_key;
	}

	/**
	 * Check for updates
	 */
	public function check_for_updates() {
		if ( $this->last_fetch !== false ) {
			return;
		}

		$result = wp_remote_get( 'https://spamlytics.com/?api_action=get_spamdomains&api_key=' . $this->api_key );

		if ( is_array( $result ) && isset( $result['body'] ) ) {
			$json = json_decode( $result['body'] );
			$set  = array();
			foreach ( $json->domains as $row ) {
				$set[] = array(
					'url'     => $row->url,
					'created' => $row->created
				);
			}

			update_option( 'spamlytics_ga_urls', $set );
			set_transient( 'spamlytics_ga_referral_updated', time(), 24 * HOUR_IN_SECONDS );
		}
	}

}