<?php

namespace SpamLytics\Frontend;

/**
 * Class Comments
 * @package SpamLytics\Frontend
 */
class Comments {

	/**
	 * @var array
	 */
	private $settings;

	/**
	 * Comments constructor.
	 *
	 * @param array $settings
	 */
	public function __construct( $settings = array() ) {
		$this->settings = $settings;

		if ( !empty( $this->settings['api_key'] ) ) {
			add_action( 'wp_insert_comment', array( $this, 'check_for_spam' ), 10, 2 );
		}
	}

	/**
	 * Check if the comment is spam or not
	 *
	 * @param $id
	 * @param $comment
	 */
	public function check_for_spam( $id, $comment ) {
		$url = 'https://spamlytics.com/?api_action=comment_verify&api_key=' . $this->settings['api_key'];

		$response = wp_remote_post( $url, array(
				'method'   => 'POST',
				'timeout'  => 10,
				'blocking' => true,
				'body'     => array(
					'comment' => $comment->comment_content,
					'name'    => $comment->comment_author,
					'email'   => $comment->comment_author_email,
					'website' => $comment->comment_author_url,
					'ip'      => $comment->comment_author_IP,
				),
			)
		);

		if ( isset( $response['body'] ) ) {
			$result = json_decode( $response['body'] );

			update_comment_meta( $id, 'spamlytics_result', $result->result );
			update_comment_meta( $id, 'spamlytics_points', $result->points );

			if( $result->result === 'ok' ) {
				$comment = (array) $comment;
				$comment['comment_approved'] = 1;
				wp_update_comment( $comment );
			}

			if( $result->result === 'spam' ) {
				$comment = (array) $comment;
				$comment['comment_approved'] = 'spam';
				wp_update_comment( $comment );
			}
		}
	}

}