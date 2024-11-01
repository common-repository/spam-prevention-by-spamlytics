<?php

namespace SpamLytics\Frontend;

/**
 * Class Ip
 * @package SpamLytics\Frontend
 */
class Ip extends Blocker {

	/**
	 * @var
	 */
	private $ips;

	/**
	 * Construct the Google Analytics block listener
	 *
	 * @param $ip_address
	 */
	public function __construct( $ip_address ) {
		$data = \SpamLyticsHelper::is_ip_blocked( $ip_address );

		if( $data === true ) {
			$this->block_request( 'IP', $ip_address );
		}
	}

}