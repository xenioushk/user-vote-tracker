<?php

namespace UVTADDON\Controllers\Shortcodes;

use Xenioushk\BwlPluginApi\Api\Shortcodes\ShortcodesApi;
use UVTADDON\Callbacks\Shortcodes\VoteTrackerLayoutCb;
/**
 * Class for Addon shortcodes.
 *
 * @since: 1.1.0
 * @package UVTADDON
 */
class AddonShortcodes {

    /**
	 * Register shortcode.
	 */
    public function register() {
        // Initialize API.
        $shortcodes_api = new ShortcodesApi();

        // Initialize callbacks.
        $vote_tracker_layout_cb = new VoteTrackerLayoutCb();

        // All Shortcodes.
        $shortcodes = [
            [
                'tag'      => 'uvt_front',
                'callback' => [ $vote_tracker_layout_cb, 'get_the_output' ],
            ],
        ];

        $shortcodes_api->add_shortcodes( $shortcodes )->register();
    }
}
