<?php

namespace RECAPADDON\Controllers\Notices;

use Xenioushk\BwlPluginApi\Api\Notices\NoticesApi;
use RECAPADDON\Callbacks\Notices\NoticeCb;

/**
 * Class PluginNotices
 *
 * This class handles the registration of the plugin admin notices.
 *
 * @since: 1.1.1
 * @package RECAPADDON
 */
class PluginNotices {

	/**
	 * Register PluginNotices.
	 */
	public function register() {

		add_action( 'admin_init', [ $this, 'initialize' ] );
	}

	/**
	 * Initialize PluginNotices.
	 */
	public function initialize() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		// Initialize API.
		$notices_api = new NoticesApi();

		// Initialize callbacks.
		$notice_cb = new NoticeCb();

		$notices = [

			[
				'callback' => [ $notice_cb, 'get_the_notice' ],
				'notice'   => [
					'noticeClass'    => 'error',
					'msg'            => '🚨 To activate and enable the ' . RECAPADDON_PLUGIN_TITLE . ', <a href="' . get_admin_url() . 'admin.php?page=bwl-pvm_option_panel#bpvm_recaptcha_options" class="bwl_plugins_notice_text--bold">enter the ReCAPTCHA Site Key</a>.',
					'key'            => 'bpvm_recap_site_key_status',
					'status'         => ! empty( trim( RECAPADDON_SITE_KEY ) ) ? 1 : 0,
					'is_dismissable' => 0,
				],
			],
		];

		$notices_api->add_notices( $notices )->register();
	}
}
