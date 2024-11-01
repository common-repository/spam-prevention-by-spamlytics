<?php

namespace SpamLytics\Admin;

/**
 * Class Settings
 * @package SpamLytics\Admin
 */
class Settings {

	/**
	 * @var
	 */
	private $settings, $comment_stats;

	/**
	 * Construct the settings page
	 */
	public function __construct() {
		$this->settings = \SpamLyticsHelper::get_settings();

		$this->comment_stats = get_transient( 'spamlytics_comment_stats' );
	}

	/**
	 * Render the SpamLytics settings page
	 */
	public function render() {
		if ( isset( $_POST['spam_settings_nonce'], $_POST['spam_settings'] ) && wp_verify_nonce( $_POST['spam_settings_nonce'], 'spam_settings_nonce' ) && current_user_can( 'manage_options' ) ) {
			if ( $this->handle_settings_post() ) {
				$this->spam_success( __( 'Your new settings are saved successfully!', 'spam-prevention-by-spamlytics' ) );
			} else {
				$this->spam_error( __( 'There are no changes to save.', 'spam-prevention-by-spamlytics' ) );
			}
		}
		if ( isset( $_POST['spam_settings_nonce'], $_POST['spam_api'] ) && wp_verify_nonce( $_POST['spam_settings_nonce'], 'spam_settings_nonce' ) && current_user_can( 'manage_options' ) ) {
			if ( $this->generate_and_set_api_key() ) {
				$this->spam_success( __( 'Your API key is saved successfully! You should receive an confirmation email soon. Thanks for generating an API key.', 'spam-prevention-by-spamlytics' ) );
			} else {
				$this->spam_error( __( 'There was an error while generating your API key. Please contact SpamLytics.', 'spam-prevention-by-spamlytics' ) );
			}
		}

		if ( empty( $this->settings['api_key'] ) ) {
			include( SPAMLYTICS_PLUGIN_PATH . '/templates/settings_api_key.php' );
		} else {
			include( SPAMLYTICS_PLUGIN_PATH . '/templates/settings.php' );
		}
	}

	/**
	 * Handle the settings post and save the data in the SpamLytics options
	 *
	 * @return bool
	 */
	private function handle_settings_post() {
		$new_settings = filter_input( INPUT_POST, 'spam_settings', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		if ( isset( $new_settings['sent_data'] ) ) {
			$this->settings['sent_data'] =  (bool) filter_var( $new_settings['sent_data'], FILTER_VALIDATE_BOOLEAN );
		}
		if ( isset( $new_settings['mark_as_approved'] ) ) {
			$this->settings['mark_as_approved'] =  (bool) filter_var( $new_settings['mark_as_approved'], FILTER_VALIDATE_BOOLEAN );
		}

		return update_option( 'spamlytics', $this->settings );
	}

	/**
	 * Generate an API key
	 *
	 * @return bool
	 */
	private function generate_and_set_api_key() {
		$new_settings = filter_input( INPUT_POST, 'spam_api', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$result       = wp_remote_get( 'https://spamlytics.com/?api_action=create_key&email=' . $new_settings['email'] . '&newsletter=' . $new_settings['newsletter'] );

		if ( is_array( $result ) && isset( $result['body'] ) && strlen( $result['body'] ) >= 20 ) {
			$json                          = json_decode( $result['body'] );
			$this->settings['api_key']     = $json->key;
			$this->settings['api_contact'] = $new_settings['email'];

			return update_option( 'spamlytics', $this->settings );
		}

		return false;
	}

	/**
	 * Success!
	 *
	 * @param $message
	 */
	private function spam_success( $message ) {
		echo '<div class="updated"><p>' . $message . '</p></div>';
	}

	/**
	 * Success!
	 *
	 * @param $message
	 */
	private function spam_error( $message ) {
		echo '<div class="error"><p>' . $message . '</p></div>';
	}

}