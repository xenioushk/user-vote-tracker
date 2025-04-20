<?php
namespace UVTADDON\Base;

/**
 * Class for plucin deactivation.
 *
 * @since: 1.1.0
 * @package UVTADDON
 */
class Deactivate {

	/**
	 * Deactivate the plugin.
	 */
	public static function deactivate() { // phpcs:ignore
		flush_rewrite_rules();
	}
}
