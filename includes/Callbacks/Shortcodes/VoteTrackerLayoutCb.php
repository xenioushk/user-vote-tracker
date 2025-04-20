<?php
namespace UVTADDON\Callbacks\Shortcodes;

use UVTADDON\Helpers\UvtHelpers;

/**
 * Class BreadcrumbCb
 * Handles Breadcrumb shortcode.
 *
 * @package UVTADDON
 * @since: 1.0.0
 * @author: Mahbub Alam Khan
 */
class VoteTrackerLayoutCb {

	/**
	 * Get the output.
	 *
	 * @param array $atts Attributes.
	 *
	 * @return string
	 */
	public function get_the_output( $atts ) {

		$atts = shortcode_atts(
			[
				'filter'      => 'all', // all, 1=liked, 2=disliked
				'limit'       => 5,
				'pagination'  => 1,
				'meta'        => 0,
				'global_mode' => 0,
			], $atts
		);

		extract( $atts ); // phpcs:ignore

		$uvt_vote_data = UvtHelpers::uvt_get_data( 1, $filter, $limit, $pagination, $meta, $global_mode );

		$uvt_front_output = "<ul class='uvt_data_table'>" . $uvt_vote_data . '</ul>';

		return $uvt_front_output;
	}
}
