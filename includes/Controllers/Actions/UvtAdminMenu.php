<?php
namespace UVTADDON\Controllers\Actions;

use Xenioushk\BwlPluginApi\Api\Actions\ActionsApi;
use UVTADDON\Callbacks\Actions\UvtAdminMenuCb;

/**
 * Class for registering the recaptcha overlay actions.
 *
 * @since: 1.1.0
 * @package UVTADDON
 */
class UvtAdminMenu {

    /**
	 * Register filters.
	 */
    public function register() {

        // Initialize API.
        $actions_api = new ActionsApi();

        // Initialize callbacks.
        $uvt_admin_menu_cb = new UvtAdminMenuCb();

        $actions = [
            [
                'tag'      => 'admin_menu',
                'callback' => [ $uvt_admin_menu_cb, 'get_the_menu' ],
            ],

        ];

        $actions_api->add_actions( $actions )->register();
    }
}
