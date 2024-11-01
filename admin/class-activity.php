<?php

namespace SpamLytics\Admin;

/**
 * Class Activity
 * @package SpamLytics\Admin
 */
class Activity {

	/**
	 * @var
	 */
	private $posts;

	/**
	 * Show the latest activity logs (max 50)
	 */
	public function render() {
		$this->posts = get_posts( array(
			'post_type'      => 'spamlytics_log',
			'post_status'    => 'inherit',
			'posts_per_page' => 50,
		) );

		include( SPAMLYTICS_PLUGIN_PATH . '/templates/activity.php' );
	}

}