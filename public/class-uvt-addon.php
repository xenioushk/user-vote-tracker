<?php

class BPVM_UVT
{

    const VERSION = '1.0.0';

    protected $plugin_slug = 'bpvm-uvt';
    protected static $instance = null;

    private function __construct()
    {

        if (class_exists('BWL_Pro_Voting_Manager') && BPVM_UVT_PARENT_PLUGIN_INSTALLED_VERSION > '1.1.3') {

            $this->uvt_create_custom_column();

            // Load plugin text domain
            add_action('init', array($this, 'load_plugin_textdomain'));

            // Load public-facing style sheet and JavaScript.
            add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
            add_shortcode('uvt_front', array($this, 'cb_uvt_front'));

            add_action('wp_ajax_get_user_vote_data', array($this, 'get_user_vote_data'));
            add_action('wp_ajax_nopriv_get_user_vote_data', array($this, 'get_user_vote_data'));

            $this->include_files();
        }
    }

    function get_user_vote_data()
    {

        echo $uvt_vote_data = $this->uvt_get_data($_POST['start_id'], $_POST['filter'], $_POST['limit'], $_POST['pagination'], $_POST['meta'], $_POST['global_mode']);

        die();
    }

    public function include_files()
    {
        require_once(BPVM_UVT_DIR . 'includes/widgets/user-vote-tracker-widget.php');
    }

    public function uvt_create_custom_column()
    {


        // First we need to check if the patch already applied or not.
        $pvm_uvt_columns = get_option('pvm_uvt_upgrade_114');

        if ($pvm_uvt_columns != 1) {

            global $wpdb;

            $bpvm_voting_data_table = $wpdb->prefix . "bpvm_data"; // for deatils. each day info.
            //@Data Table Status.
            $pvm_dt_table_status = $wpdb->get_row("SELECT * FROM " . $bpvm_voting_data_table);

            if (!isset($pvm_dt_table_status->user_id)) {
                //                ALTER TABLE `wp_bpvm_data` ADD `user_id` INT NOT NULL DEFAULT '0' AFTER `voted_ip`; 
                $wpdb->query("ALTER TABLE " . $bpvm_voting_data_table . " ADD user_id INT NOT NULL DEFAULT '0' AFTER voted_ip");
            }

            //@Finally Update DB upgrade status.
            update_option('pvm_uvt_upgrade_114', 1);
        }
    }

    /**
     * Return the plugin slug.
     *
     * @since    1.0.0
     *
     * @return    Plugin slug variable.
     */
    public function get_plugin_slug()
    {
        return $this->plugin_slug;
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance()
    {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Fired when the plugin is activated.
     *
     * @since    1.0.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses
     *                                       "Network Activate" action, false if
     *                                       WPMU is disabled or plugin is
     *                                       activated on an individual blog.
     */
    public static function activate($network_wide)
    {

        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
                    self::single_activate();
                }

                restore_current_blog();
            } else {
                self::single_activate();
            }
        } else {
            self::single_activate();
        }
    }

    /**
     * Fired when the plugin is deactivated.
     *
     * @since    1.0.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses
     *                                       "Network Deactivate" action, false if
     *                                       WPMU is disabled or plugin is
     *                                       deactivated on an individual blog.
     */
    public static function deactivate($network_wide)
    {

        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
                    self::single_deactivate();
                }

                restore_current_blog();
            } else {
                self::single_deactivate();
            }
        } else {
            self::single_deactivate();
        }
    }

    public function activate_new_site($blog_id)
    {

        if (1 !== did_action('wpmu_new_blog')) {
            return;
        }

        switch_to_blog($blog_id);
        self::single_activate();
        restore_current_blog();
    }

    private static function get_blog_ids()
    {

        global $wpdb;

        // get an array of blog ids
        $sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

        return $wpdb->get_col($sql);
    }

    /**
     * Fired for each blog when the plugin is activated.
     *
     * @since    1.0.0
     */
    private static function single_activate()
    {
        // @TODO: Define activation functionality here
    }

    /**
     * Fired for each blog when the plugin is deactivated.
     *
     * @since    1.0.0
     */
    private static function single_deactivate()
    {
        // @TODO: Define deactivation functionality here
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain()
    {

        $domain = $this->plugin_slug;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
    }

    /**
     * Register and enqueue public-facing style sheet.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_slug . '-frontend', BPVM_UVT_ADDON_DIR . 'assets/styles/frontend.css', [], self::VERSION);
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_slug . '-frontend', BPVM_UVT_ADDON_DIR . 'assets/scripts/frontend.js', ['jquery'], self::VERSION);
    }

    public function uvt_get_data($start = 0, $filter = "all", $limit = 5, $pagination = 1, $meta = 0, $global_mode = 0)
    {

        global $wpdb;
        $bpvm_posts_data_table = $wpdb->prefix . "posts"; // for deatils. each day info.
        $bpvm_voting_data_table = $wpdb->prefix . "bpvm_data"; // for deatils. each day info.

        if (isset($start) && $start != 0) {
            $id = $start;
            $start = ($id - 1) * $limit;
        } else {
            $id = 0;
            //                        $start = 0;
        }

        // Add USER ID.

        $user_id = -1;

        if (is_user_logged_in() && $global_mode == 0) {

            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;
        }


        if ($user_id < 1 && $global_mode == 0) {
            return ""; // uncomment it later
        }

        $columns = array(
            0 => 'ID',
            1 => 'vote_date',
            2 => 'vote_type',
            3 => 'votes'
        );


        $post_type = ""; // WordPress Available Post Types.
        $uvt_custom_date_range = FALSE; // Get Custom Date range status.
        $uvt_filter_start_date = ""; // Starting date of filter.
        $uvt_filter_end_date = ""; // Ending date of filter.

        $vars = [];

        $bpvm_selected_columns = "{$bpvm_voting_data_table}.postid,{$bpvm_voting_data_table}.vote_type,{$bpvm_voting_data_table}.votes, {$bpvm_voting_data_table}.vote_date AS vote_date_time,DATE({$bpvm_voting_data_table}.vote_date) as vote_date, {$bpvm_posts_data_table}.post_title ";

        // Start Query Conditions.
        $con_mv_post_filters = "";
        $con_mv_date_range_filters = "";
        $con_mv_user_filters = "";

        if ($post_type != "") {
            $con_mv_post_filters .= " AND {$bpvm_voting_data_table}.post_type = %s ";
            $vars[] = $post_type;
        }

        //                    $filter='all';

        if (isset($filter) && $filter != "all") {
            $con_mv_post_filters .= " AND {$bpvm_voting_data_table}.vote_type = %d ";
            $vars[] = $filter;
        }

        if ($uvt_custom_date_range == "true") {

            $uvt_filter_start_date = ($uvt_filter_start_date == "") ? "2010-04-01" : $uvt_filter_start_date;
            $uvt_filter_end_date = ($uvt_filter_end_date == "") ? "2020-04-01" : $uvt_filter_end_date;

            $con_mv_date_range_filters .= " AND DATE({$bpvm_voting_data_table}.vote_date) >= %s AND DATE({$bpvm_voting_data_table}.vote_date) <= %s ";

            $vars[] = $uvt_filter_start_date;
            $vars[] = $uvt_filter_end_date;
        }

        // For Users.

        if (isset($global_mode) && $global_mode == 0) {
            $con_mv_user_filters .= " AND {$bpvm_voting_data_table}.user_id = %d  ";
            $vars[] = $user_id;
        }

        // For Votes Count.

        $con_votes_count_filters = " AND {$bpvm_voting_data_table}.votes = %d  ";
        $vars[] = 1;

        // End Query Conditions.

        $count_sql = $wpdb->prepare("SELECT COUNT({$bpvm_voting_data_table}.ID) AS total_count FROM {$bpvm_voting_data_table}, {$bpvm_posts_data_table} 
                                                               WHERE 
                                                               {$bpvm_posts_data_table}.ID = {$bpvm_voting_data_table}.postid 
                                                               " . $con_mv_user_filters . "
                                                               " . $con_mv_post_filters . "
                                                               " . $con_votes_count_filters . "
                                                               " . $con_mv_date_range_filters . "", $vars);


        // Generate data from query.

        $bpvm_total_votes = $wpdb->get_results($count_sql, ARRAY_A);

        wp_reset_query();

        $totalData = (!empty($bpvm_total_votes[0]['total_count'])) ? $bpvm_total_votes[0]['total_count'] : 0;
        $totalFiltered = ceil($totalData / $limit); // when there is no search parameter then total number rows = total number filtered rows.
        // Get each votes info in details. ( Details )

        $order_type = "DESC";

        //                    $order_query = " ORDER BY " . $columns[1] . "   " . $order_type . "  LIMIT " . $start . " ," . $limit . "   ";
        $order_query = " ORDER BY vote_date_time   " . $order_type . "  LIMIT " . $start . " ," . $limit . "   ";

        $sql = $wpdb->prepare("SELECT " . $bpvm_selected_columns . " FROM {$bpvm_voting_data_table}, {$bpvm_posts_data_table} 
                    WHERE 
                    {$bpvm_posts_data_table}.ID = {$bpvm_voting_data_table}.postid 
                    " . $con_mv_user_filters . "
                    " . $con_mv_post_filters . "
                    " . $con_votes_count_filters . "
                    " . $con_mv_date_range_filters . $order_query . "", $vars);

        // Generate data from query.

        $pvm_vote_data = $wpdb->get_results($sql, ARRAY_A);

        $uvt_output = '';

        if (sizeof($pvm_vote_data) > 0) :

            $uvt_output .= "";

            foreach ($pvm_vote_data as $vote_data) :

                $post_title = $vote_data['post_title'];
                $post_id = $vote_data['postid'];
                $vote_type = $vote_data['vote_type'];
                $voted_date = $vote_data['vote_date_time'];

                $vote_type_text = ($vote_type == 2) ? __('Disliked', 'bpvm_uvt') : __('Liked', 'bpvm_uvt');

                $uvt_post_link = get_permalink($post_id);


                if (isset($meta) && $meta == 1) {

                    $uvt_meta = "";
                } else {

                    $my_current_time = strtotime(date('Y-m-d H:i:s', current_time('timestamp')));

                    $signed_date = strtotime($voted_date);

                    $voted_time_ago = human_time_diff($signed_date, $my_current_time);

                    $uvt_meta = '<span><time class="" datetime="' . $voted_date . '">' . $vote_type_text . ' ' . $voted_time_ago .  ' ' . __('ago', 'bpvm_uvt') . '</time></span>';
                }

                $uvt_output .= '<li><a href="' . $uvt_post_link . '">' . $post_title . '</a>' . $uvt_meta . '</li>';

            endforeach;

        else :

            $uvt_output .= '<li>' . __('Sorry, No Result Found !', 'bpvm_uvt') . '</li>';

        endif;

        // Pagination Buttons.

        $uvt_pagination_link = "";

        if ($pagination == 1) {

            $uvt_pagination_link .= '<div class="uvt_pagination">';

            if ($id > 1) {
                $uvt_pagination_link .= '<a href="#" data-start_id="' . ($id - 1) . '" data-limit="' . $limit . '" data-filter="' . $filter . '" data-pagination="' . $pagination . '" data-meta="' . $meta . '" data-global_mode="' . $global_mode . '"  class="uvt-btn-prev">' . __('Previous', 'bpvm_uvt') . '</a>';
            }

            if ($id != $totalFiltered) {
                $uvt_pagination_link .= '<a href="#" data-start_id="' . ($id + 1) . '" data-limit="' . $limit . '" data-filter="' . $filter . '" data-pagination="' . $pagination . '" data-meta="' . $meta . '" data-global_mode="' . $global_mode . '"  class="uvt-btn-next">' . __('Next', 'bpvm_uvt') . '</a>';
            }

            $uvt_pagination_link .= '</div>';
        }

        wp_reset_query();

        if (!in_array('ob_gzhandler', ob_list_handlers())) {
            ob_start('ob_gzhandler');
        }

        return $uvt_output . $uvt_pagination_link;
    }

    public function cb_uvt_front($atts)
    {

        $atts = shortcode_atts(array(
            'filter' => 'all', // all, 1=liked, 2=disliked
            'limit' => 5,
            'pagination' => 1,
            'meta' => 0,
            'global_mode' => 0
        ), $atts);

        extract($atts);

        $uvt_vote_data = $this->uvt_get_data(1, $filter, $limit, $pagination, $meta, $global_mode); // 1= start id.

        $uvt_front_output = "<ul class='uvt_data_table'>" . $uvt_vote_data . "</ul>";

        return $uvt_front_output;
    }
}
