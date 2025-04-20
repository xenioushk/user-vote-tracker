<?php
namespace UVTADDON\Callbacks\Actions;

use Xenioushk\BwlPluginApi\Api\View\ViewApi;

/**
 * Class for registering recaptcha overlay actions.
 *
 * @package UVTADDON
 * @since: 1.0.0
 * @author: Mahbub Alam Khan
 */
class UvtAdminMenuCb extends ViewApi {

	/**
	 * Get the layout for the menu.
	 */
	public function get_the_menu() {
		add_submenu_page(
			'users.php',
			esc_html__( 'My Votes', 'bpvm_uvt' ),
			esc_html__( 'My Votes', 'bpvm_uvt' ),
			'read',
			'bpvm-my-votes',
			[ $this, 'register_page' ]
		);
	}


	/**
	 * Register the page View Page.
	 */
	public function register_page() {

		$user_id = 0;

		if ( is_user_logged_in() ) {

			$current_user = wp_get_current_user();
			$user_id      = $current_user->ID;
		}

		$data = [
			'user_id'    => $user_id,
			'post_types' => \bpvm_get_widget_custom_post_types(),
		];

		$this->render( UVTADDON_VIEWS_DIR . 'Admin/ReportPage.php', $data );
	}
}
