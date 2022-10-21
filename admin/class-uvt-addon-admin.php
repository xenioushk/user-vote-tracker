<?php

class BPVM_UVT_Admin {

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Slug of the plugin screen.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_screen_hook_suffix = null;

    /**
     * Initialize the plugin by loading admin scripts & styles and adding a
     * settings page and menu.
     *
     * @since     1.0.0
     */
    private function __construct() {

        //@Description: First we need to check if KB Plugin & WooCommerce is activated or not. If not then we display a message and return false.
        //@Since: Version 1.0.5
        
        if( ! class_exists( 'BWL_Pro_Voting_Manager' ) || BPVM_UVT_PARENT_PLUGIN_INSTALLED_VERSION < '1.1.4' ) {
            add_action('admin_notices', array($this, 'uvt_version_update_admin_notice'));
            return false;
        }
        
        // Start Plugin Admin Panel Code.
        
        $plugin = BPVM_UVT::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();
        
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        
        add_action('admin_menu', array($this, 'bkb_welcome_submenu'));
        
        add_action('wp_ajax_uvt_voting_stats', array($this, 'uvt_voting_stats'));
        
        add_shortcode('uvt_report', array($this, 'uvt_report'));
        
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        /*
         * @TODO :
         *
         * - Uncomment following lines if the admin class should only be available for super admins
         */
        /* if( ! is_super_admin() ) {
          return;
          } */

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

        /**
         * Register and enqueues public-facing JavaScript files.
         *
         * @since    1.0.0
         */
        public function enqueue_admin_styles() {
      
                wp_register_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/uvt-admin-style.css', __FILE__ ), array(), BPVM_UVT::VERSION ); 

        }
        
        /**
         * Register and enqueues public-facing JavaScript files.
         *
         * @since    1.0.0
         */
        public function enqueue_scripts() {
            
            wp_register_script($this->plugin_slug . '-admin-custom-script', plugins_url('assets/js/uvt-admin-scripts.js', __FILE__), array('jquery'), BPVM_UVT::VERSION, TRUE);
            
        }

        //Version Manager:  Update Checking

    public function uvt_version_update_admin_notice() {

        echo '<div class="updated"><p>You need to download & install '
                . '<b><a href="https://1.envato.market/bpvm-wp" target="_blank"> BWL Pro Voting Manager (Minimum Version 1.1.4)</a></b> '
                . 'to use '.BPVM_UVT_ADDON_TITLE.'.</p></div>';
    }
    
    function bkb_welcome_submenu() {
        
        add_submenu_page(
                'users.php', 
                __('My Votes', 'bpvm_uvt'),
                __('My Votes', 'bpvm_uvt'), 
                'read', 
                'bpvm-my-votes', 
                array($this, 'cb_bpvm_my_votes')
        );
    }

    function cb_bpvm_my_votes() {
        
        echo do_shortcode('[uvt_report]');
        
    }
    
    function uvt_report() {
        
        wp_enqueue_style( $this->plugin_slug .'-admin-styles' );
        wp_enqueue_style('bwl-pvm-datatables-style');
        wp_enqueue_script('bpvm-datatable-script');
        wp_enqueue_script('bpvm-admin-voting-report-script');
        wp_enqueue_script('bpvm-datatable-script');
        wp_enqueue_script( $this->plugin_slug . '-admin-custom-script');
        include_once 'includes/view-user-voting-report.php';
        
    }
    

    //@Description: Generate Voting Data in data grid.
    //@Since: Version 1.1.1


    function uvt_voting_stats() {
        

        global $wpdb;
        $bpvm_posts_data_table = $wpdb->prefix . "posts"; // for deatils. each day info.
        $bpvm_voting_data_table = $wpdb->prefix . "bpvm_data"; // for deatils. each day info.

        $requestData = $_POST;
        $data = array();

        $columns = array(
            0 => 'ID',
            1 => 'vote_date',
            2 => 'vote_type',
            3 => 'votes'
        );

        $user_id = $_POST['user_id']; // Get post ID 
        $post_type = $_POST['post_type']; // WordPress Available Post Types.
        $mv_post_filters = $_POST['mv_post_filters']; // Filter Type: Like/Dislike.
        $mv_vote_info_type = $_POST['mv_vote_info_type']; // Voting Info type: Summary/ Details
        $uvt_custom_date_range = $_POST['uvt_custom_date_range']; // Get Custom Date range status.
        $uvt_filter_start_date = $_POST['uvt_filter_start_date']; // Starting date of filter.
        $uvt_filter_end_date = $_POST['uvt_filter_end_date']; // Ending date of filter.
        
        // New Code Implemented In Version 1.1.4

        $vars = array($user_id);

        $bpvm_selected_columns = "{$bpvm_posts_data_table}.ID,{$bpvm_voting_data_table}.postid,{$bpvm_voting_data_table}.post_type,{$bpvm_voting_data_table}.vote_type,{$bpvm_voting_data_table}.votes,DATE({$bpvm_voting_data_table}.vote_date) as vote_date, {$bpvm_posts_data_table}.post_title ";

        // Start Query Conditions.

        $con_mv_date_range_filters = "";

        if ($post_type != "") {
            $con_mv_post_filters .= " AND {$bpvm_voting_data_table}.post_type = %s ";
            $vars[] = $post_type;
        }

        if ($mv_post_filters != "") {
            $con_mv_post_filters .= " AND {$bpvm_voting_data_table}.vote_type = %d ";
            $vars[] = $mv_post_filters;
        }

        if ($uvt_custom_date_range == "true") {

            $uvt_filter_start_date = ( $uvt_filter_start_date == "" ) ? "2010-04-01" : $uvt_filter_start_date;
            $uvt_filter_end_date = ( $uvt_filter_end_date == "" ) ? "2020-04-01" : $uvt_filter_end_date;

            $con_mv_date_range_filters .= " AND DATE({$bpvm_voting_data_table}.vote_date) >= %s AND DATE({$bpvm_voting_data_table}.vote_date) <= %s ";

            $vars[] = $uvt_filter_start_date;
            $vars[] = $uvt_filter_end_date;
        }


        // End Query Conditions.

        $count_sql = $wpdb->prepare("SELECT COUNT({$bpvm_voting_data_table}.ID) AS total_count FROM {$bpvm_voting_data_table}, {$bpvm_posts_data_table} 
                                                               WHERE 
                                                               {$bpvm_posts_data_table}.ID = {$bpvm_voting_data_table}.postid AND
                                                                {$bpvm_voting_data_table}.user_id = %d 
                                                               " . $con_mv_post_filters . "
                                                               " . $con_mv_date_range_filters . "", $vars);


        // Generate data from query.

        $bpvm_total_votes = $wpdb->get_results($count_sql, ARRAY_A);

//               echo $wpdb->last_query;

        wp_reset_vars($vars);
        wp_reset_query();

        $totalData = (!empty($bpvm_total_votes[0]['total_count']) ) ? $bpvm_total_votes[0]['total_count'] : 0;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
        //
           // Get each votes info in details. ( Details )

        $order_query = " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";

        $sql = $wpdb->prepare("SELECT " . $bpvm_selected_columns . " FROM {$bpvm_voting_data_table}, {$bpvm_posts_data_table} 
                   WHERE 
                   {$bpvm_posts_data_table}.ID = {$bpvm_voting_data_table}.postid AND
                   {$bpvm_voting_data_table}.user_id = %d 
                   " . $con_mv_post_filters . "
                   " . $con_mv_date_range_filters . $order_query . "", $vars);

        // Generate data from query.

        $pvm_vote_data = $wpdb->get_results($sql, ARRAY_A);
        //    echo "<pre>";
        //    print_r($pvm_vote_data);
        //    echo "</pre>";
//                echo $wpdb->last_query;
        //        die();

        $bpvm_full_vote_data = array();

        if (sizeof($pvm_vote_data) > 0) :

            $i = 1 + $requestData['start'];

            foreach ($pvm_vote_data as $vote_data) :

                $row_id = $vote_data['ID']; // auto incremental ID.
                $post_title = $vote_data['post_title'];
                $post_type = $vote_data['post_type'];
                $user_id = $vote_data['postid'];
                $vote_type = $vote_data['vote_type'];
                $votes = $vote_data['votes'];
                $vote_date = $vote_data['vote_date'];

                // End of backend total vote counting.

                array_push($bpvm_full_vote_data, array(
//                    ( $mv_vote_info_type == 2 ) ? '<input type="checkbox"  class="deleteRow" value="' . $row_id . '" data-post_id="' . $user_id . '" data-votes="' . $votes . '" data-vote_type="' . $vote_type . '" data-vote_date="' . $vote_date . '"/>' : '-',
                    $post_title,
                    $post_type,
                    $vote_date,
                    ( $vote_type == 2 ) ? __('Dislike', 'bwl-pro-voting-manager') : __('Like', 'bwl-pro-voting-manager'),
                    $votes
                ));

                $i++;

            endforeach;

        endif;

        wp_reset_vars($vars);
        wp_reset_query();


        $data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            'data' => $bpvm_full_vote_data
        );
        ob_start("ob_gzhandler");
        echo json_encode($data);
        die();
    }

}
