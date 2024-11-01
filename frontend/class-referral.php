<?php

namespace SpamLytics\Frontend;

/**
 * Class Referral
 * @package SpamLytics\Frontend
 */
class Referral extends Blocker {

	/**
	 * Construct the Google Analytics block listener
	 *
	 * @param $ip_address
	 */
	public function __construct( $ip_address ) {
		$data = \SpamLyticsHelper::is_referral_blocked( $ip_address );

		if( $data === true ) {
			$this->block_request( 'IP', $ip_address );
		}
	}

}