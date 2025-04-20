<?php
namespace UVTADDON\Callbacks\AdminAjaxHandlers;

/**
 * Class for plugin report table.
 *
 * @package UVTADDON
 */
class ReportTableCb {

	/**
	 * Save the installation data.
	 */
	public function get_the_data() {

		global $wpdb;
		$bpvm_posts_data_table  = $wpdb->prefix . 'posts'; // for deatils. each day info.
		$bpvm_voting_data_table = $wpdb->prefix . 'bpvm_data'; // for deatils. each day info.
		$con_mv_post_filters    = '';
		$requestData            = $_POST;
		$data                   = [];

		$columns = [
			0 => 'ID',
			1 => 'vote_date',
			2 => 'vote_type',
			3 => 'votes',
		];

		$user_id               = $_POST['user_id']; // Get post ID
		$post_type             = $_POST['post_type']; // WordPress Available Post Types.
		$mv_post_filters       = $_POST['mv_post_filters']; // Filter Type: Like/Dislike.
		$mv_vote_info_type     = $_POST['mv_vote_info_type']; // Voting Info type: Summary/ Details
		$uvt_custom_date_range = $_POST['uvt_custom_date_range']; // Get Custom Date range status.
		$uvt_filter_start_date = $_POST['uvt_filter_start_date']; // Starting date of filter.
		$uvt_filter_end_date   = $_POST['uvt_filter_end_date']; // Ending date of filter.

		// New Code Implemented In Version 1.1.4

		$vars = [ $user_id ];

		$bpvm_selected_columns = "{$bpvm_posts_data_table}.ID,{$bpvm_voting_data_table}.postid,{$bpvm_voting_data_table}.post_type,{$bpvm_voting_data_table}.vote_type,{$bpvm_voting_data_table}.votes,DATE({$bpvm_voting_data_table}.vote_date) as vote_date, {$bpvm_posts_data_table}.post_title ";

		// Start Query Conditions.

		$con_mv_date_range_filters = '';

		if ( $post_type != '' ) {
				$con_mv_post_filters .= " AND {$bpvm_voting_data_table}.post_type = %s ";
				$vars[]               = $post_type;
		}

		if ( $mv_post_filters != '' ) {
				$con_mv_post_filters .= " AND {$bpvm_voting_data_table}.vote_type = %d ";
				$vars[]               = $mv_post_filters;
		}

		if ( $uvt_custom_date_range == 'true' ) {

				$uvt_filter_start_date = ( $uvt_filter_start_date == '' ) ? '2010-04-01' : $uvt_filter_start_date;
				$uvt_filter_end_date   = ( $uvt_filter_end_date == '' ) ? '2020-04-01' : $uvt_filter_end_date;

				$con_mv_date_range_filters .= " AND DATE({$bpvm_voting_data_table}.vote_date) >= %s AND DATE({$bpvm_voting_data_table}.vote_date) <= %s ";

				$vars[] = $uvt_filter_start_date;
				$vars[] = $uvt_filter_end_date;
		}

		// End Query Conditions.

		$count_sql = $wpdb->prepare(
            "SELECT COUNT({$bpvm_voting_data_table}.ID) AS total_count FROM {$bpvm_voting_data_table}, {$bpvm_posts_data_table} 
																													 WHERE 
																													 {$bpvm_posts_data_table}.ID = {$bpvm_voting_data_table}.postid AND
																														{$bpvm_voting_data_table}.user_id = %d 
																													 " . $con_mv_post_filters . '
																													 ' . $con_mv_date_range_filters . '', $vars
		);

		// Generate data from query.
		$bpvm_total_votes = $wpdb->get_results( $count_sql, ARRAY_A );

		wp_reset_vars( $vars );
		wp_reset_query();

		$totalData     = ( ! empty( $bpvm_total_votes[0]['total_count'] ) ) ? $bpvm_total_votes[0]['total_count'] : 0;
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		// Get each votes info in details. ( Details )

		$order_query = ' ORDER BY ' . $columns[ $requestData['order'][0]['column'] ] . '   ' . $requestData['order'][0]['dir'] . '  LIMIT ' . $requestData['start'] . ' ,' . $requestData['length'] . '   ';

		$sql = $wpdb->prepare(
            'SELECT ' . $bpvm_selected_columns . " FROM {$bpvm_voting_data_table}, {$bpvm_posts_data_table} 
							 WHERE 
							 {$bpvm_posts_data_table}.ID = {$bpvm_voting_data_table}.postid AND
							 {$bpvm_voting_data_table}.user_id = %d 
							 " . $con_mv_post_filters . '
							 ' . $con_mv_date_range_filters . $order_query . '', $vars
		);

		// Generate data from query.

		$pvm_vote_data = $wpdb->get_results( $sql, ARRAY_A );

		$bpvm_full_vote_data = [];

		if ( sizeof( $pvm_vote_data ) > 0 ) :

				$i = 1 + $requestData['start'];

			foreach ( $pvm_vote_data as $vote_data ) :

					$row_id         = $vote_data['ID']; // auto incremental ID.
					$post_title     = $vote_data['post_title'];
					$post_permalink = get_the_permalink( $row_id );
					$post_type      = $vote_data['post_type'];
					$user_id        = $vote_data['postid'];
					$vote_type      = $vote_data['vote_type'];
					$votes          = $vote_data['votes'];
					$vote_date      = $vote_data['vote_date'];

					// End of backend total vote counting.

					array_push(
                        $bpvm_full_vote_data, [
							"<a href='{$post_permalink}' target='_blank'>" . $post_title . '</a>',
							$post_type,
							$vote_date,
							( $vote_type == 2 ) ? esc_html__( 'Dislike', 'bpvm_uvt' ) : esc_html__( 'Like', 'bpvm_uvt' ),
							$votes,
						]
					);

					++$i;

				endforeach;

		endif;

		wp_reset_vars( $vars );
		wp_reset_postdata();

		$data = [
			'draw'            => intval( $requestData['draw'] ), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			'recordsTotal'    => intval( $totalData ), // total number of records
			'recordsFiltered' => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			'data'            => $bpvm_full_vote_data,
		];
		ob_start( 'ob_gzhandler' );

		echo wp_json_encode( $data );
		wp_die();
	}
}
