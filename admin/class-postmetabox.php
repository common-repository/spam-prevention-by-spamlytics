<?php

namespace SpamLytics\Admin;

/**
 * Class Postmetabox
 */
class Postmetabox {

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'spam_add_metabox' ), 5 );
		add_action( 'save_post', array( $this, 'spam_save_metabox' ), 10, 2 );
	}

	/**
	 * Hook the metabox
	 */
	public function spam_add_metabox() {
		add_meta_box(
			'spam_metabox_type',
			'SpamLytics - ' . __( 'Spam Record Type', 'apg' ),
			array( $this, 'spam_metabox_type' ),
			'spamlytics',
			'normal',
			'high'
		);
		add_meta_box(
			'spam_metabox_support',
			__( 'Questions and Support', 'apg' ),
			array( $this, 'spam_metabox_support' ),
			'spamlytics',
			'side',
			'high'
		);
	}

	/**
	 * Save the APG metabox photo order
	 *
	 * @param $post_id
	 * @param $post
	 *
	 * @return mixed
	 */
	public function spam_save_metabox( $post_id, $post ) {
		if ( isset( $_POST['spam_metabox_nonce'], $_POST['spamlytics'] ) && wp_verify_nonce( $_POST['spam_metabox_nonce'], 'spam_metabox_nonce' ) && current_user_can( 'manage_options' ) ) {
			$type = strip_tags( filter_input( INPUT_POST, 'spamlytics' ) );

			update_post_meta( $post_id, 'spamlytics_type', $type );

			return $post_id;
		}

		return $post_id;
	}

	/**
	 * Callback: show the metabox content
	 *
	 * @param $post
	 */
	public function spam_metabox_type( $post ) {
		wp_nonce_field( 'spam_metabox_nonce', 'spam_metabox_nonce' );
		echo '<p><strong>' . __('Step 1', 'spam') . ':</strong> ' . __('Enter the value (a full referral URL or IP to block) for this spam record in the title field above.','spam') . '</p>';

		echo '<p><strong>' . __('Step 2', 'spam') . ':</strong> ' . __('Select the type of this spam record below:', 'spam') . '</p>';

		foreach( \SpamLyticsHelper::get_spam_types() as $key => $type ) {
			$value   = get_post_meta( $post->ID, 'spamlytics_type', true );
			$checked = checked( $value, $key, false );

			if( empty( $value ) ) {
				$checked = checked( 'ga', $key, false );
			}

			echo '<label><input type="radio" name="spamlytics" value="' . esc_attr( $key ) .'"' . $checked . '> ' . esc_attr( $type ) . '</label><br />';
		}

		echo '<p><strong>' . __('Step 3', 'spam') . ':</strong> ' . __('Hit publish (or update) and your spam record is active!','spam') . '</p>';
	}

	/**
	 * Metabox support
	 *
	 * @param $post
	 */
	public function spam_metabox_support( $post ) {
		echo '<p>' . __( 'Thank you for using SpamLytics.', 'apg' ) . '</p>';
		echo '<p>' . sprintf( __( 'Do you have questions or feature requests? We have %1$sdocumentation about the plugin%4$s on our website, or contact us by using %2$sTwitter%4$s or the %3$sWordPress forums%4$s.', 'apg' ),
				'<a href="https://spamlytics.com/documentation/?utm_source=WordPress&utm_medium=referral&utm_campaign=spam_post_sidebar" target="_blank">',
				'<a href="https://twitter.com/SpamLytics" target="_blank">',
				'<a href="https://wordpress.org/support/plugin/spam-prevention-by-spamlytics/" target="_blank">',
				'</a>' ) . '</p>';
	}


}