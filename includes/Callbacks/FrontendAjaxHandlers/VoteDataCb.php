<?php
namespace UVTADDON\Callbacks\FrontendAjaxHandlers;

use UVTADDON\Helpers\UvtHelpers;

/**
 * Class for count votes callback.
 *
 * @package UVTADDON
 */
class VoteDataCb {

	/**
	 * Add votes count.
	 */
	public function get_data() {
		$output = UvtHelpers::uvt_get_data(
            $_POST['start_id'],
            $_POST['filter'],
            $_POST['limit'],
            $_POST['pagination'],
            $_POST['meta'],
            $_POST['global_mode']
        );
		echo $output; //phpcs:ignore
		wp_die();
	}
}
