<?php
namespace UVTADDON\Widgets;

use WP_Widget;
/**
 * Class for BPVM Widget
 *
 * @package UVTADDON
 */
class Uvt_Widget extends WP_Widget {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		parent::__construct(
            'uvt_widget',
            esc_html__( 'User Vote Tracker Widget', 'bpvm_uvt' ),
            [
				'classname'   => 'Uvt_Widget',
				'description' => esc_html__( 'Display recent up/down voted items in sidebar area.', 'bpvm_uvt' ),
            ]
		);
	}

	public function form( $instance ) {

		$defaults = [
			'title'           => esc_html__( 'Recent Voted Posts', 'bpvm_uvt' ),
			'uvt_filter_type' => 'all',
			'uvt_pagination'  => 'on',
			'uvt_hide_meta'   => '',
			'uvt_no_of_post'  => '5',
			'uvt_global_mode' => '',
		];

		$instance = wp_parse_args( (array) $instance, $defaults );

		extract( $instance ); //phpcs:ignore

		?>


<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title', 'bpvm_uvt' ); ?></label>
    <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
    name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
</p>


<p>
    <label
    for="<?php echo $this->get_field_id( 'uvt_filter_type' ); ?>"><?php esc_html_e( 'Order Type:', 'bpvm_uvt' ); ?></label>
    <select id="<?php echo $this->get_field_id( 'uvt_filter_type' ); ?>"
    name="<?php echo $this->get_field_name( 'uvt_filter_type' ); ?>" class="widefat" style="width:100%;">
    <option value="all" <?php if ( $instance['uvt_filter_type'] == 'all' ) { echo 'selected="selected"';} ?>>
		<?php esc_html_e( 'All', 'bpvm_uvt' ); ?></option>
    <option value="1" <?php if ( $instance['uvt_filter_type'] == '1' ) { echo 'selected="selected"';} ?>>
		<?php esc_html_e( 'Liked', 'bpvm_uvt' ); ?></option>
    <option value="2" <?php if ( $instance['uvt_filter_type'] == '2' ) { echo 'selected="selected"';} ?>>
		<?php esc_html_e( 'Disliked', 'bpvm_uvt' ); ?></option>
    </select>
</p>

<!-- Display No of Posts  -->
<p>
    <label
    for="<?php echo $this->get_field_id( 'uvt_no_of_post' ); ?>"><?php esc_html_e( 'No Of Posts', 'bpvm_uvt' ); ?></label>
    <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'uvt_no_of_post' ); ?>"
    name="<?php echo $this->get_field_name( 'uvt_no_of_post' ); ?>"
    value="<?php echo esc_attr( $uvt_no_of_post ); ?>" />
</p>

<!-- Display Front Pagination  -->
<p>
    <label
    for="<?php echo $this->get_field_id( 'uvt_pagination' ); ?>"><?php esc_html_e( 'Display Pagination', 'bpvm_uvt' ); ?>:
    </label>
    <input id="<?php echo $this->get_field_id( 'uvt_pagination' ); ?>"
    name="<?php echo $this->get_field_name( 'uvt_pagination' ); ?>" type="checkbox"
		<?php checked( $uvt_pagination, 'on' ); ?> />
</p>

<!-- Display Hide Meta Information -->
<p>
    <label
    for="<?php echo $this->get_field_id( 'uvt_hide_meta' ); ?>"><?php esc_html_e( 'Hide Meta Information', 'bpvm_uvt' ); ?>:
    </label>
    <input id="<?php echo $this->get_field_id( 'uvt_hide_meta' ); ?>"
    name="<?php echo $this->get_field_name( 'uvt_hide_meta' ); ?>" type="checkbox"
		<?php checked( $uvt_hide_meta, 'on' ); ?> />
</p>

<!-- Display Front End Global Mode -->
<p>
    <label
    for="<?php echo $this->get_field_id( 'uvt_global_mode' ); ?>"><?php esc_html_e( 'Enable Global Mode', 'bpvm_uvt' ); ?>:
    </label>
    <input id="<?php echo $this->get_field_id( 'uvt_global_mode' ); ?>"
    name="<?php echo $this->get_field_name( 'uvt_global_mode' ); ?>" type="checkbox"
		<?php checked( $uvt_global_mode, 'on' ); ?> />
    <br /><small><?php esc_html_e( 'Display recent liked/disliked votes by all the voters.', 'bpvm_uvt' ); ?></small>
</p>

		<?php

	}

	/**
	 * Update widget settings.
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $instance     Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $instance ) {

		$instance['title'] = strip_tags( stripslashes( $new_instance['title'] ) );

		$uvt_filter_type = $new_instance['uvt_filter_type'] ?? 'all'; // all, 1=liked, 2=disliked
		$uvt_no_of_post  = $new_instance['uvt_no_of_post'] ?? 5;
		$uvt_pagination  = $new_instance['uvt_pagination'] ?? 0;
		$uvt_hide_meta   = $new_instance['uvt_hide_meta'] ?? 0;
		$uvt_global_mode = $new_instance['uvt_global_mode'] ?? 0;

		$instance['uvt_filter_type'] = $uvt_filter_type;
		$instance['uvt_no_of_post']  = empty( $uvt_no_of_post ) ? 5 : $uvt_no_of_post;
		$instance['uvt_pagination']  = ( $uvt_pagination === 'on' ) ? 1 : 0;
		$instance['uvt_hide_meta']   = ( $uvt_hide_meta === 'on' ) ? 1 : 0;
		$instance['uvt_global_mode'] = ( $uvt_global_mode === 'on' ) ? 1 : 0;

		return $instance;
	}

	/**
	 * Frontend display of widget.
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		extract( $args ); //phpcs:ignore

		$title = apply_filters( 'widget-title', $instance['title'] ?? esc_html__( 'Recent Voted Posts', 'bpvm_uvt' ) );

		$uvt_filter_type = $instance['uvt_filter_type'] ?? 'all'; // all, 1=liked, 2=disliked
		$uvt_no_of_post  = $instance['uvt_no_of_post'] ?? 5;
		$uvt_pagination  = $instance['uvt_pagination'] ?? 0;
		$uvt_hide_meta   = $instance['uvt_hide_meta'] ?? 0;
		$uvt_global_mode = $instance['uvt_global_mode'] ?? 0;

		if ( ! is_user_logged_in() && $uvt_global_mode == 0 ) {

			return ''; // uncomment it later

		}

		echo $before_widget; //phpcs:ignore

		if ( $title ) {
			echo $before_title . $title . $after_title; //phpcs:ignore
		}

		echo do_shortcode( sprintf(
			'[uvt_front filter="%s" limit="%d" pagination="%d" meta="%d" global_mode="%d"]',
			$uvt_filter_type,
			empty( $uvt_no_of_post ) ? 5 : $uvt_no_of_post,
			( $uvt_pagination === 'on' ) ? 1 : 0,
			( $uvt_hide_meta === 'on' ) ? 1 : 0,
			( $uvt_global_mode === 'on' ) ? 1 : 0,
		) );

		echo $after_widget; //phpcs:ignore
	}
}
