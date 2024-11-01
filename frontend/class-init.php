<?php

namespace SpamLytics\Frontend;

/**
 * Class Init
 * @package SpamLytics\Frontend
 */
class Init {

	/**
	 * @var mixed
	 */
	private $settings;

	/**
	 * Listen for referral urls
	 */
	public function __construct() {
		$this->settings = \SpamLyticsHelper::get_settings();
		$referrer       = $this->filter_url( $_SERVER['HTTP_REFERER'] );

		new Ga( $referrer );
		new Ip( $_SERVER['REMOTE_ADDR'] );
		new Referral( $_SERVER['HTTP_REFERER'] );
		new Comments( $this->settings );
	}

	/**
	 * Remove URL
	 *
	 * @param $url
	 *
	 * @return mixed
	 */
	private function filter_url( $url ) {
		$url = str_replace( array( 'https://', 'http://' ), '', $url );

		return $url;
	}

}