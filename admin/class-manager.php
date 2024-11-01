<?php

namespace SpamLytics\Admin;

/**
 * Class Manager
 * @package SpamLytics\Admin
 */
class Manager {

	/**
	 * @var
	 */
	private $posts;

	/**
	 * Construct the manager
	 */
	public function __construct() {
		$this->posts = get_posts( array(
			'post_type'      => 'spamlytics',
			'posts_per_page' => 30,
		) );
	}

	/**
	 * Render the management page
	 */
	public function render() {

		include( SPAMLYTICS_PLUGIN_PATH . '/templates/manager.php' );
	}

}