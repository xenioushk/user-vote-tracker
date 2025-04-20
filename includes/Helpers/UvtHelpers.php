<?php
namespace UVTADDON\Helpers;

use UVTADDON\Helpers\PluginConstants;

/**
 * Class for plugin helpers.
 *
 * @package UVTADDON
 */
class UvtHelpers {

    /**
     * Get the voting data.
     *
     * @param int    $start Start ID.
     * @param string $filter Filter type.
     * @param int    $limit Limit of data.
     * @param int    $pagination Pagination status.
     * @param int    $meta Meta status.
     * @param int    $global_mode Global mode status.
     * @return string Voting data.
     * @since 1.0.0
     */
    public static function uvt_get_data( $start = 0, $filter = 'all', $limit = 5, $pagination = 1, $meta = 0, $global_mode = 0 ) {

		global $wpdb;
		$bpvm_posts_data_table  = $wpdb->prefix . 'posts'; // for deatils. each day info.
		$bpvm_voting_data_table = $wpdb->prefix . 'bpvm_data'; // for deatils. each day info.

		if ( isset( $start ) && $start != 0 ) {
				$id    = $start;
				$start = ( $id - 1 ) * $limit;
		} else {
				$id = 0;
				// $start = 0;
		}

		// Add USER ID.

		$user_id = -1;

		if ( is_user_logged_in() && $global_mode == 0 ) {

				$current_user = wp_get_current_user();
				$user_id      = $current_user->ID;
		}

		if ( $user_id < 1 && $global_mode == 0 ) {
				return ''; // uncomment it later
		}

		$columns = [
			0 => 'ID',
			1 => 'vote_date',
			2 => 'vote_type',
			3 => 'votes',
		];

		$post_type             = ''; // WordPress Available Post Types.
		$uvt_custom_date_range = false; // Get Custom Date range status.
		$uvt_filter_start_date = ''; // Starting date of filter.
		$uvt_filter_end_date   = ''; // Ending date of filter.

		$vars = [];

		$bpvm_selected_columns = "{$bpvm_voting_data_table}.postid,{$bpvm_voting_data_table}.vote_type,{$bpvm_voting_data_table}.votes, {$bpvm_voting_data_table}.vote_date AS vote_date_time,DATE({$bpvm_voting_data_table}.vote_date) as vote_date, {$bpvm_posts_data_table}.post_title ";

		// Start Query Conditions.
		$con_mv_post_filters       = '';
		$con_mv_date_range_filters = '';
		$con_mv_user_filters       = '';

		if ( $post_type != '' ) {
				$con_mv_post_filters .= " AND {$bpvm_voting_data_table}.post_type = %s ";
				$vars[]               = $post_type;
		}

		// $filter='all';

		if ( isset( $filter ) && $filter != 'all' ) {
				$con_mv_post_filters .= " AND {$bpvm_voting_data_table}.vote_type = %d ";
				$vars[]               = $filter;
		}

		if ( $uvt_custom_date_range == 'true' ) {

				$uvt_filter_start_date = ( $uvt_filter_start_date == '' ) ? '2010-04-01' : $uvt_filter_start_date;
				$uvt_filter_end_date   = ( $uvt_filter_end_date == '' ) ? '2020-04-01' : $uvt_filter_end_date;

				$con_mv_date_range_filters .= " AND DATE({$bpvm_voting_data_table}.vote_date) >= %s AND DATE({$bpvm_voting_data_table}.vote_date) <= %s ";

				$vars[] = $uvt_filter_start_date;
				$vars[] = $uvt_filter_end_date;
		}

		// For Users.

		if ( isset( $global_mode ) && $global_mode == 0 ) {
				$con_mv_user_filters .= " AND {$bpvm_voting_data_table}.user_id = %d  ";
				$vars[]               = $user_id;
		}

		// For Votes Count.

		$con_votes_count_filters = " AND {$bpvm_voting_data_table}.votes = %d  ";
		$vars[]                  = 1;

		// End Query Conditions.

		$count_sql = $wpdb->prepare(
            "SELECT COUNT({$bpvm_voting_data_table}.ID) AS total_count FROM {$bpvm_voting_data_table}, {$bpvm_posts_data_table} 
																													 WHERE 
																													 {$bpvm_posts_data_table}.ID = {$bpvm_voting_data_table}.postid 
																													 " . $con_mv_user_filters . '
																													 ' . $con_mv_post_filters . '
																													 ' . $con_votes_count_filters . '
																													 ' . $con_mv_date_range_filters . '', $vars
		);

		// Generate data from query.

		$bpvm_total_votes = $wpdb->get_results( $count_sql, ARRAY_A );

		wp_reset_postdata();

		$totalData     = ( ! empty( $bpvm_total_votes[0]['total_count'] ) ) ? $bpvm_total_votes[0]['total_count'] : 0;
		$totalFiltered = ceil( $totalData / $limit ); // when there is no search parameter then total number rows = total number filtered rows.

		$order_type = 'DESC';

		$order_query = ' ORDER BY vote_date_time   ' . $order_type . '  LIMIT ' . $start . ' ,' . $limit . '   ';

		$sql = $wpdb->prepare(
            'SELECT ' . $bpvm_selected_columns . " FROM {$bpvm_voting_data_table}, {$bpvm_posts_data_table} 
								WHERE 
								{$bpvm_posts_data_table}.ID = {$bpvm_voting_data_table}.postid 
								" . $con_mv_user_filters . '
								' . $con_mv_post_filters . '
								' . $con_votes_count_filters . '
								' . $con_mv_date_range_filters . $order_query . '', $vars
		);

		// Generate data from query.

		$pvm_vote_data = $wpdb->get_results( $sql, ARRAY_A );

		$uvt_output = '';

		if ( sizeof( $pvm_vote_data ) > 0 ) :

				$uvt_output .= '';

			foreach ( $pvm_vote_data as $vote_data ) :

					$post_title = $vote_data['post_title'];
					$post_id    = $vote_data['postid'];
					$vote_type  = $vote_data['vote_type'];
					$voted_date = $vote_data['vote_date_time'];

					$vote_type_text = ( $vote_type == 2 ) ? esc_html__( 'Disliked', 'bpvm_uvt' ) : esc_html__( 'Liked', 'bpvm_uvt' );

					$uvt_post_link = get_permalink( $post_id );

				if ( isset( $meta ) && $meta == 1 ) {

						$uvt_meta = '';
				} else {

						$my_current_time = strtotime( date( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );

						$signed_date = strtotime( $voted_date );

						$voted_time_ago = human_time_diff( $signed_date, $my_current_time );

						$uvt_meta = '<span><time class="" datetime="' . $voted_date . '">' . $vote_type_text . ' ' . $voted_time_ago . ' ' . esc_html__( 'ago', 'bpvm_uvt' ) . '</time></span>';
				}

					$uvt_output .= '<li><a href="' . $uvt_post_link . '">' . $post_title . '</a>' . $uvt_meta . '</li>';

				endforeach;

		else :

				$uvt_output .= '<li>' . esc_html__( 'Sorry, No Result Found !', 'bpvm_uvt' ) . '</li>';

		endif;

		// Pagination Buttons.

		$uvt_pagination_link = '';

		if ( $pagination == 1 ) {

				$uvt_pagination_link .= '<div class="uvt_pagination">';

			if ( $id > 1 ) {
					$uvt_pagination_link .= '<a href="#" data-start_id="' . ( $id - 1 ) . '" data-limit="' . $limit . '" data-filter="' . $filter . '" data-pagination="' . $pagination . '" data-meta="' . $meta . '" data-global_mode="' . $global_mode . '"  class="uvt-btn-prev">' . esc_html__( 'Previous', 'bpvm_uvt' ) . '</a>';
			}

			if ( $id != $totalFiltered ) {
					$uvt_pagination_link .= '<a href="#" data-start_id="' . ( $id + 1 ) . '" data-limit="' . $limit . '" data-filter="' . $filter . '" data-pagination="' . $pagination . '" data-meta="' . $meta . '" data-global_mode="' . $global_mode . '"  class="uvt-btn-next">' . esc_html__( 'Next', 'bpvm_uvt' ) . '</a>';
			}

				$uvt_pagination_link .= '</div>';
		}

		wp_reset_postdata();

		if ( ! in_array( 'ob_gzhandler', ob_list_handlers() ) ) {
				ob_start( 'ob_gzhandler' );
		}

		return $uvt_output . $uvt_pagination_link;
	}
}
