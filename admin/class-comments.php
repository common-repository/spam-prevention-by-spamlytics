<?php

namespace SpamLytics\Admin;

/**
 * Class Comments
 * @package SpamLytics\Admin
 */
class Comments {

	public function __construct() {
		add_filter( 'manage_edit-comments_columns', array( $this, 'add_comment_columns' ) );
		add_filter( 'manage_comments_custom_column', array( $this, 'register_comment_content' ), 10, 2 );

		add_filter( 'comment_text', array( $this, 'filter_comment_excerpt' ), 10, 1 );

		if ( ( $verify = filter_input( INPUT_GET, 'spamlytics_check' ) ) && $verify !== '' ) {
			$this->verify_comment( $verify );
		}

		$this->check_stats_transient();
	}

	/**
	 * Register the custom column in the admin page
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function add_comment_columns( $columns ) {
		$columns['spamlytics_result'] = __( 'SpamLytics', 'spam-prevention-by-spamlytics' );

		return $columns;
	}

	/**
	 * Populate the colum with the SpamLytics data
	 *
	 * @param $column
	 * @param $comment_ID
	 */
	public function register_comment_content( $column, $comment_ID ) {
		if ( 'spamlytics_result' == $column ) {
			$result = get_comment_meta( $comment_ID, 'spamlytics_result', true );
			$status = filter_input( INPUT_GET, 'comment_status' );

			if ( is_null( $status ) ) {
				$status = 'all';
			}

			if ( $result === 'ok' ) {
				echo '<span style="color: green;"><span class="dashicons dashicons-yes"></span> OK</span><br />';
				echo '<a href="' . admin_url( 'edit-comments.php?comment_status=' . $status . '&spamlytics_check=' . $comment_ID ) . '"><i>Verify again</i></a>';
			} elseif ( $result === 'spam' ) {
				echo '<span style="color: red;"><span class="dashicons dashicons-warning"></span> Spam</span><br />';
				echo '<a href="' . admin_url( 'edit-comments.php?comment_status=' . $status . '&spamlytics_check=' . $comment_ID ) . '"><i>Verify again</i></a>';
			} elseif ( $result === 'limit' ) {
				echo '<span style="color: orange;"><span class="dashicons dashicons-warning"></span> Not checked, reached monthly limit</span><br />';
				echo '<a href="https://spamlytics.com/comment-spam-filtering-wp/?utm_source=WordPress&utm_medium=referral&utm_campaign=spam_upsell_comments" class="button" target="_blank"><i>Upgrade now!</i></a> or ';
				echo '<a href="' . admin_url( 'edit-comments.php?comment_status=' . $status . '&spamlytics_check=' . $comment_ID ) . '"><i>Try again</i></a>';
			} else {
				echo '<span style="color: orange;"><span class="dashicons dashicons-shield"></span> Not checked</span><br />';
				echo '<a href="' . admin_url( 'edit-comments.php?comment_status=' . $status . '&spamlytics_check=' . $comment_ID ) . '"><i>Verify this comment</i></a>';
			}
		}
	}

	/**
	 * Filter the comment with the status
	 *
	 * @param $comment
	 *
	 * @return mixed
	 */
	public function filter_comment_excerpt( $comment ) {

		return $comment;
	}

	/**
	 * Verify a comment from the admin interface and update the comment status if needed
	 *
	 * @param $id
	 */
	private function verify_comment( $id ) {
		$frontend_class = new \SpamLytics\Frontend\Comments( \SpamLyticsHelper::get_settings() );
		$frontend_class->check_for_spam( $id, get_comment( $id ) );
	}

	/**
	 * Check the comment stats transient
	 */
	private function check_stats_transient() {
		$stats = get_transient( 'spamlytics_comment_stats' );

		if ( $stats === false ) {
			$spamlytics_option = get_option( 'spamlytics', '' );

			if ( isset( $spamlytics_option['api_key'] ) ) {
				$url = 'https://spamlytics.com/?api_action=stats&api_key=' . $spamlytics_option['api_key'];

				$response = wp_remote_post( $url, array(
						'method'   => 'POST',
						'timeout'  => 10,
						'blocking' => true,
						'body'     => array(),
					)
				);

				if ( is_array( $response ) && isset( $response['body'] ) ) {
					$json = (array) json_decode( $response['body'] );

					set_transient( 'spamlytics_comment_stats', $json, ( 5 * 60 ) ); // save the transient for 5 minutes
				}
			}
		}
	}

}