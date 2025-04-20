<?php

namespace UVTADDON\Base;

use Xenioushk\BwlPluginApi\Api\AjaxHandlers\AjaxHandlersApi;
use UVTADDON\Callbacks\FrontendAjaxHandlers\VoteDataCb;

/**
 * Class for frontend ajax handlers.
 *
 * @package UVTADDON
 * @since: 1.1.0
 * @author: Mahbub Alam Khan
 */
class FrontendAjaxHandlers {

	/**
	 * Register frontend ajax handlers.
	 */
	public function register() {

		$ajax_handlers_api = new AjaxHandlersApi();

		// Initalize Callbacks.

		$vote_data_cb = new VoteDataCb();
		// Do not change the tag.
		// If do so, you need to change in js file too.
		$ajax_requests = [
			[
				'tag'      => 'get_user_vote_data',
				'callback' => [ $vote_data_cb, 'get_data' ],
			],
		];

		$ajax_handlers_api->add_ajax_handlers( $ajax_requests )->register();
	}
}
