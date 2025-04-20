<?php
namespace RECAPADDON\Callbacks\Notices;

use RECAPADDON\Traits\TraitAdminNotice;

/**
 * Class NoticeCb
 *
 * Handles the FAQ items shortcode callbacks.
 *
 * @package RECAPADDON
 */
class NoticeCb {

	use TraitAdminNotice;

	/**
	 * Display the plugin notices.
	 *
	 * @param array $notice The notice data.
	 */
	public function get_the_notice( $notice = [] ) {
		$this->get_notice_html( $notice );
	}
}
