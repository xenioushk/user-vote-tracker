<?php
namespace UVTADDON\Base;

use Xenioushk\BwlPluginApi\Api\AjaxHandlers\AjaxHandlersApi;
use UVTADDON\Callbacks\AdminAjaxHandlers\ReportTableCb;
use UVTADDON\Callbacks\AdminAjaxHandlers\PluginInstallationCb;
/**
 * Class for admin ajax handlers.
 *
 * @package UVTADDON
 */
class AdminAjaxHandlers {

	/**
	 * Register admin ajax handlers.
	 */
	public function register() {

		$ajax_handlers_api = new AjaxHandlersApi();

		// Initialize callbacks.
		$report_table_cb        = new ReportTableCb();
		$plugin_installation_cb = new PluginInstallationCb();

		// Do not change the tag.
		// If do so, you need to change in js file too.
		$ajax_requests = [
			[
				'tag'      => 'uvt_voting_stats',
				'callback' => [ $report_table_cb, 'get_the_data' ],
			],
			[
				'tag'      => 'uvt_bpvm_installation_counter',
				'callback' => [ $plugin_installation_cb, 'save' ],
			],
		];
		$ajax_handlers_api->add_ajax_handlers( $ajax_requests )->register();
	}
}
