<?php

namespace SpamLytics\Admin;

use SpamLytics\Admin\Postmetabox;

/**
 * Class Init
 * @package SpamLytics\Admin
 */
class Init {

	/**
	 * @var
	 */
	private $settings;

	/**
	 * Admin initiated, hook the things we need for SpamLytics
	 */
	public function __construct() {
		$this->settings_class = new Settings();
		$this->settings       = \SpamLyticsHelper::get_settings();

		// add_actions
		add_action( 'admin_menu', array( $this, 'hook_submenu_items' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( SPAMLYTICS_ROOT_PATH ), array(
			$this,
			'plugin_actions'
		) );

		add_action( 'init', array( $this, 'init_admin' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'hook_admin_style' ) );
		add_action( 'admin_notices', array( $this, 'post_type_info' ) );

		// If the user has enabled diagnostic data, we'll need to hook in the comments for spam.
		if ( (bool) $this->settings['sent_data'] === true ) {
			add_action( 'transition_comment_status', array( $this, 'report_spam_hook' ), 10, 3 );
		}

		new Comments();

		$this->check_admin_message();
	}

	/**
	 * Hook the admin style
	 */
	public function hook_admin_style() {
		wp_enqueue_style( 'spamlytics-backend', plugins_url( 'assets/spamlytics.backend.min.css', SPAMLYTICS_ROOT_PATH ) );
		wp_enqueue_script( 'spamlytics-backend-js', plugins_url( 'assets/spamlytics-backend.js', SPAMLYTICS_ROOT_PATH ), array( 'jquery' ) );
		wp_enqueue_script( 'spamlytics-backend-chart', plugins_url( 'assets/js/Chart.min.js', SPAMLYTICS_ROOT_PATH ), array( 'jquery' ) );
		wp_enqueue_script( 'spamlytics-backend-chart-doughnut', plugins_url( 'assets/js/Chart.Doughnut.js', SPAMLYTICS_ROOT_PATH ), array( 'jquery' ) );
	}

	/**
	 * Hook the sub menu items
	 */
	public function hook_submenu_items() {
		add_menu_page( 'SpamLytics', 'SpamLytics', 'manage_options', 'spamlytics', array(
			$this,
			'show_page'
		), 'dashicons-shield', '26.240503034' );

		$pages                      = array();
		$pages['spamlytics'] = array(
			'label'      => __( 'Overview', 'spam' ),
			'capability' => 'manage_options',
			'action'     => 'spamlytics',
			'callback'   => array( $this, 'show_page' ),
		);
		$pages['spam_settings']     = array(
			'label'      => __( 'Settings', 'spam' ),
			'capability' => 'manage_options',
			'action'     => 'spam_settings',
			'callback'   => array( $this, 'show_page' ),
		);
		array_merge( $pages, apply_filters( 'spamlytics_submenu_items', array() ) );

		/**
		 * Loop through the pages and add them as a submenu item
		 */
		foreach ( $pages as $key => $item ) {
			add_submenu_page( 'spamlytics', $item['label'], $item['label'], $item['capability'], $item['action'], $item['callback'] );
		}
	}

	/**
	 * Render the requested page
	 */
	public function show_page() {
		switch ( filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) ) {
			case 'spamlytics':
				$overview = new Overview();
				$overview->render();
				break;
			case 'spam_ip_addresses':
				$list = new Listing();
				$list->type( 'spam_ip_addresses' );
				$list->render();
				break;
			case 'spam_referrals':
				$list = new Listing();
				$list->type( 'spam_referrals' );
				$list->render();
				break;
			case 'spam_settings':
				$this->settings_class->render();
				break;
		}


		if ( $this->settings['module_log'] && filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) == 'spam_log' ) {
			$activity = new Activity();
			$activity->render();
		}
	}

	/**
	 * Add some action links at the plugin page
	 *
	 * @param $links
	 *
	 * @return array
	 */
	public function plugin_actions( $links ) {
		$spam_links = array(
			'<a href="' . admin_url( 'edit.php?post_type=spamlytics&page=settings' ) . '">' . __( 'Settings', 'spam' ) . '</a>',
		);

		return array_merge( $spam_links, $links );
	}

	/**
	 * Add the metabox
	 */
	public function init_admin() {
		new Postmetabox();

		if ( empty( $this->settings['api_key'] ) ) {
			add_action( 'admin_notices', array( $this, 'add_api_key_notice' ) );
		}
	}

	/**
	 * Show some information above the post type
	 */
	public function post_type_info() {
		$screen = get_current_screen();
		if ( 'spamlytics' == $screen->post_type
			&& 'edit' == $screen->base
		) {
			?>
			<div class="spamlytics-highlight updated">
				<p><?php printf(
						__( 'Welcome in the SpamLytics Manager. Here you can add and edit spam rules for your website. When a spam rule matches with a request, the request will be blocked and logged in the %sactivity manager%s.' ),
						'<a href="' . admin_url( 'edit.php?post_type=spamlytics&page=spam_log' ) . '">',
						'</a>' ); ?></p>

				<p>The SpamLytics team has already preconfigured 485+ Google Analytics referral spam domains.</p>

				<p><i><strong><?php _e( 'Note:', 'spam' ); ?></strong> <?php printf(
							__( 'If you use the Google Analytics referral URLs spam rules, we will only block spam visits who actually visit your website. In order to block all referral spam, we can %screate Filters & Segments in your account%s automatically.', 'spam' ),
							'<a href="https://spamlytics.com/block-referrer-spam-automatically/?utm_source=WordPress&utm_medium=referral&utm_campaign=spam_settings" target="_blank">',
							'</a>'
						); ?></i></p>
			</div>
			<?php
		}
	}

	/**
	 * The user didn't set an API key, so we sent him a message to set his free api key
	 */
	public function add_api_key_notice() {
		echo '<div class="clear"></div><div class="error notice">';
		echo '<p>' . __( 'Thanks for installing SpamLytics! In order to activate comment spam filtering and updates for Google Analytics Referral spam, we need to you enter a free API key. You can generate an API key from this dashboard to get started.', 'spam-prevention-by-spamlytics' );
		echo ' <a href="' . admin_url( 'admin.php?page=spam_settings&setapi=true' ) . '" class="button button-primary">' . __( 'Get a free API key', 'spam-prevention-by-spamlytics' ) . '</a>';
		echo '</p>';
		echo '</div>';
	}

	/**
	 * Hook into the spam report action and report it to SpamLytics if the user has enabled it in his settings.
	 *
	 * @param $new_status
	 * @param $old_status
	 * @param $comment
	 */
	public function report_spam_hook( $new_status, $old_status, $comment ) {
		if ( $old_status != $new_status ) {
			if ( $new_status == 'spam' ) {
				$result = wp_remote_post( 'https://spamlytics.com/?api_action=comment_report', array(
					'method'      => 'POST',
					'timeout'     => 30,
					'redirection' => 2,
					'httpversion' => '1.0',
					'blocking'    => true,
					'body'        => array(
						'ip'      => $comment->comment_author_IP,
						'name'    => $comment->comment_author,
						'website' => $comment->comment_author_url,
						'email'   => $comment->comment_author_email,
						'agent'   => $comment->comment_agent,
						'comment' => $comment->comment_content,
					),
				) );
			}
		}
	}

	/**
	 * Show a spamlytics upgrade notice
	 */
	public function spamlytics_upgrade_notice() {
		echo '<div class="error notice">';
		echo '<img src="' . plugins_url( SPAMLYTICS_PLUGIN_ROOT ) . '/assets/images/logo_spamlytics_100.png" class="spamlytics-logo-in-header" style="margin-top: 10px !important;" />';
		echo '<p>' . __( 'Thank you for using SpamLytics! You\'ve reached your monthly comment spam check limit, so please upgrade your API key. With a higher limit are you able to use our service and prevent comment spam.', 'spam-prevention-by-spamlytics' );
		echo ' <a href="https://spamlytics.com/comment-spam-filtering-wp/?utm_source=WordPress&utm_medium=referral&utm_campaign=spam_upsell_overview" class="button button-primary" target="_blank">' . __( 'Upgrade now', 'spam-prevention-by-spamlytics' ) . ' &raquo;</a>';
		echo '</p>';
		echo '<p align="right"><a href="?spamlytics_notice=false"><i>' . __( 'No thanks, hide this notice', 'spam-prevention-by-spamlytics' ) . '</i></a></p>';
		echo '</div>';
	}

	/**
	 * Check if we need to show an admin message
	 */
	private function check_admin_message() {
		if ( ( $dismiss = filter_input( INPUT_GET, 'spamlytics_notice' ) ) && $dismiss !== '' ) {
			set_transient( 'spamlytics_notice_dismissed', true, ( 24 * HOUR_IN_SECONDS * 7 ) );
		}

		$cache     = get_transient( 'spamlytics_comment_stats' );
		$dismissed = get_transient( 'spamlytics_notice_dismissed', false );

		if ( $cache['month_total'] >= $cache['month_limit'] && $dismissed === false && $cache['month_limit'] !== 0 ) {
			add_action( 'admin_notices', array( $this, 'spamlytics_upgrade_notice' ) );
		}
	}

}