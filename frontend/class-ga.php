<?php

namespace SpamLytics\Frontend;

/**
 * Class Ga
 * @package SpamLytics\Frontend
 */
class Ga extends Blocker {

	/**
	 * @var
	 */
	private $urls;

	/**
	 * Construct the Google Analytics block listener
	 *
	 * @param $referral_url
	 */
	public function __construct( $referral_url ) {
		$data = \SpamLyticsHelper::get_ga_url( $referral_url );

		if( is_object( $data ) && $data->post_title == $referral_url ) {
			$this->block_request( 'GA_REFERRAL', $referral_url );
		}
	}

}