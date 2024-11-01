<?php

namespace SpamLytics\Admin;

/**
 * Class Listing
 * @package SpamLytics\Admin
 */
class Listing {

	private $api_key, $spam_urls, $last_update;

	/**
	 * @var
	 */
	private $type, $title, $single_title, $data;

	/**
	 * Set the type of data we should render in the overview (ip_addresses or referrals)
	 *
	 * @param $type
	 */
	public function type( $type ) {
		$this->type = $type;

		if ( $type === 'spam_ip_addresses' ) {
			$this->title        = __( 'IP Addresses', 'spam-prevention-by-spamlytics' );
			$this->single_title = __( 'IP Address', 'spam-prevention-by-spamlytics' );
			$this->data         = get_option( 'spamlytics_ip_addresses', array() );
		} else {
			$this->title        = __( 'Referral URLs', 'spam-prevention-by-spamlytics' );
			$this->single_title = __( 'Referral URL', 'spam-prevention-by-spamlytics' );
			$this->data         = get_option( 'spamlytics_referrals', array() );
		}

		// Check for post calls
		if ( ( $nonce = filter_input( INPUT_POST, 'spam_listing_nonce' ) ) && wp_verify_nonce( $nonce, 'spam_listing_nonce' ) ) {
			$this->handle_post_new();
		}

		// Check for delete calls
		if ( ( $nonce = filter_input( INPUT_GET, 'spam_nonce' ) ) && wp_verify_nonce( $nonce, 'spamlytics-listing-delete' ) ) {
			$this->handle_delete();
		}
	}

	/**
	 * Show the latest activity logs and some basic info
	 */
	public function render() {
		include( SPAMLYTICS_PLUGIN_PATH . '/templates/listing.php' );
	}

	/**
	 * Show a validation error as an admin notice
	 */
	public function show_success_message() {
		echo '<div class="updated"><p>';
		_e( 'Your new value was added successfully. The new filter is activated immediately.', 'spam-prevention-by-spamlytics' );
		echo '</p></div>';
	}

	/**
	 * Show a validation error as an admin notice
	 */
	public function show_validation_error() {
		echo '<div class="error"><p>';
		_e( 'The given value was not valid. Please try again.', 'spam-prevention-by-spamlytics' );
		echo '</p></div>';
	}

	/**
	 * Handle POST call to add a new record
	 */
	private function handle_post_new() {
		$value = filter_input( INPUT_POST, 'spam_listing_new' );

		if ( strlen( $value ) >= 3 ) {
			$value = str_replace( array( ' ', 'http://', 'https://' ), '', $value );

			$this->data[] = $value;
			if ( $this->type === 'spam_ip_addresses' && $this->validate_ip_address( $value ) ) {
				update_option( 'spamlytics_ip_addresses', $this->data );

				$this->show_success_message();
			} elseif ( $this->type === 'spam_referrals' && $this->validate_referral( $value ) ) {
				update_option( 'spamlytics_referrals', $this->data );

				$this->show_success_message();
			} else {
				unset( $this->data[ ( count( $this->data ) - 1 ) ] );

				$this->show_validation_error();
			}
		}
	}

	/**
	 * Handle a delete get
	 */
	private function handle_delete() {
		if ( ( $value = filter_input( INPUT_GET, 'delete' ) ) && ! empty( $value ) ) {
			foreach ( $this->data as $id => $dt ) {
				if ( $dt == $value ) {
					unset( $this->data[ $id ] );

					if ( $this->type === 'spam_ip_addresses' ) {
						update_option( 'spamlytics_ip_addresses', $this->data );
					} elseif ( $this->type === 'spam_referrals' ) {
						update_option( 'spamlytics_referrals', $this->data );
					}

					return;
				}
			}

			$this->show_validation_error();
		}
	}

	/**
	 * Validate an IP address
	 *
	 * @param $ip
	 *
	 * @return bool
	 */
	private function validate_ip_address( $ip ) {
		if ( ! filter_var( $ip, FILTER_VALIDATE_IP ) === false ) {
			return true;
		}

		return false;
	}

	/**
	 * Validate a referral URL on input
	 *
	 * @param $url
	 *
	 * @return bool
	 */
	private function validate_referral( $url ) {
		if ( strlen( $url ) >= 2 || strpos( $url, '.' ) !== false ) {
			return true;
		}

		return false;
	}

}