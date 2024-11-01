<?php

namespace SpamLytics\Frontend;

/**
 * Class Blocker
 * @package SpamLytics\Frontend
 */
class Blocker {

	/**
	 * @var
	 */
	private $referrer;

	/**
	 * @var
	 */
	private $ip;

	/**
	 * @var
	 */
	private $type;

	/**
	 * @var
	 */
	private $value;

	/**
	 * @var
	 */
	private $spam;

	/**
	 * Block a request
	 *
	 * @param $type
	 * @param null $referrer
	 */
	public function block_request( $type, $referrer = null ) {
		$this->type = 'BLOCK';
		$this->ip   = $_SERVER['REMOTE_ADDR'];

		if( $type === 'GA_REFERRAL' ) {
			if ( ! is_null( $referrer ) ) {
				$this->referrer = $referrer;
				$this->value    = 'Block referral spam: ' . $_SERVER['HTTP_REFERER'];
				$this->spam     = 'Google Analytics';
			} else {
				$this->value = 'Unknown block';
				$this->spam  = 'Other';
			}
		}
		elseif( $type === 'REFERRAL' ) {
			if ( ! is_null( $referrer ) ) {
				$this->referrer = $referrer;
				$this->value    = 'Block referral spam: ' . $_SERVER['HTTP_REFERER'];
				$this->spam     = 'Google Analytics';
			} else {
				$this->value = 'Unknown block';
				$this->spam  = 'Other';
			}
		}
		elseif( $type === 'IP' ) {
			$this->value    = 'IP Blocked: ' . $_SERVER['REMOTE_ADDR'];
			$this->spam     = 'IP';
		}

		$html = apply_filters( 'spamlytics_block_page', "<h1>Whoops! You're blocked.</h1>" );
		if ( ! empty( $html ) ) {
			echo $html;
		}

		add_action( 'init', array( $this, 'log_blocked' ) );

	}

	/**
	 * Log a blocked request
	 */
	public function log_blocked() {
		$post_id = wp_insert_post( array(
			'post_title'   => $this->type,
			'post_content' => $this->value,
			'post_status'  => 'inherit',
			'post_type'    => 'spamlytics_log',
		) );

		update_post_meta( $post_id, 'ip_address', $this->ip );
		update_post_meta( $post_id, 'type', $this->type );
		update_post_meta( $post_id, 'spam_type', $this->spam );

		if ( apply_filters( 'spamlytics_block_exit', true ) ) {
			exit;
		}
	}

}