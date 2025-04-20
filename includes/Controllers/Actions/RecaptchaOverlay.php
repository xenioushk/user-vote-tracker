<?php
namespace RECAPADDON\Controllers\Actions;

use Xenioushk\BwlPluginApi\Api\Actions\ActionsApi;
use RECAPADDON\Callbacks\Actions\RecaptchaOverlayCb;

/**
 * Class for registering the recaptcha overlay actions.
 *
 * @since: 1.1.0
 * @package RECAPADDON
 */
class RecaptchaOverlay {

    /**
	 * Register filters.
	 */
    public function register() {

        // Initialize API.
        $actions_api = new ActionsApi();

        // Initialize callbacks.
        $recaptcha_overlay_cb = new RecaptchaOverlayCb();

        $actions = [
            [
                'tag'      => 'wp_footer',
                'callback' => [ $recaptcha_overlay_cb, 'get_the_layout' ],
            ],

        ];

        $actions_api->add_actions( $actions )->register();
    }
}
