<?php
namespace UVTADDON\Base;

use Xenioushk\BwlPluginApi\Api\PluginUpdate\WpAutoUpdater;

/**
 * Class for plugin update.
 *
 * @since: 1.1.0
 * @package UVTADDON
 */
class PluginUpdate {

  	/**
     * Register the plugin text domain.
     */
	public function register() {
		add_action( 'admin_init', [ $this, 'check_for_the_update' ] );
	}

	/**
     * Check for the plugin update.
     */
	public function check_for_the_update() {
		new WpAutoUpdater( UVTADDON_PLUGIN_VERSION, UVTADDON_PLUGIN_UPDATER_URL, UVTADDON_PLUGIN_UPDATER_SLUG );
	}
}
