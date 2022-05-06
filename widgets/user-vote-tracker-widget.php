<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/***********************************************************
* @Description: Custom Widget to Display WooCommerce Products
* @Created At: 25-04-2014
* @Last Edited AT: 25-04-2014
* @Created By: Mahbub
***********************************************************/


/*------------------------------  Start Widget Code ---------------------------------*/

class Uvt_Widget extends WP_Widget {

    public function __construct() {
 
            parent::__construct(
                    'uvt_widget',
                    __('User Vote Tracker Widget' , 'bpvm_uvt'),
                    array(
                            'classname'     =>  'Uvt_Widget',
                            'description'    =>   __('Display recent up/down voted items in sidebar area.' , 'bpvm_uvt')
                    )
            );
        
    }
    
    public function form($instance) {
 
        $defaults = array(
            'title'                              =>  __('Recent Voted Posts' , 'bpvm_uvt'),
            'uvt_filter_type'     => 'all',
            'uvt_pagination'    =>  'on',
            'uvt_hide_meta'    =>  '',
            'uvt_no_of_post'    =>  '5',
            'uvt_global_mode'    =>  ''
        );
        
        $instance = wp_parse_args((array) $instance, $defaults);
        
        extract($instance);
        
        ?>
 
        
        <p>
            <label for="<?php echo $this->get_field_id('title') ?>"><?php _e('Title' , 'bpvm_uvt'); ?></label>
            <input type="text" 
                       class="widefat" 
                       id="<?php echo $this->get_field_id('title') ?>" 
                       name="<?php echo $this->get_field_name('title') ?>"
                       value="<?php echo esc_attr($title) ?>"/>
        </p>
        
        <!-- Order Type -->
         <p>
                <label for="<?php echo $this->get_field_id('uvt_filter_type'); ?>"><?php _e('Order Type:', 'bpvm_uvt') ?></label> 
                <select id="<?php echo $this->get_field_id('uvt_filter_type'); ?>" name="<?php echo $this->get_field_name('uvt_filter_type'); ?>" class="widefat" style="width:100%;">
                    <option value="all" <?php if ( $instance['uvt_filter_type'] == 'all' ) echo 'selected="selected"'; ?>><?php _e('All', 'bpvm_uvt'); ?></option>                        
                    <option value="1" <?php if ( $instance['uvt_filter_type'] == '1' ) echo 'selected="selected"'; ?>><?php _e('Liked', 'bpvm_uvt'); ?></option>                        
                    <option value="2" <?php if ( $instance['uvt_filter_type'] == '2' ) echo 'selected="selected"'; ?>><?php _e('Disliked', 'bpvm_uvt'); ?></option>                        
                </select>
        </p>
        
        <!-- Display No of Posts  -->
        <p>
            <label for="<?php echo $this->get_field_id('uvt_no_of_post') ?>"><?php _e('No Of Posts' , 'bpvm_uvt'); ?></label>
            <input type="text" 
                       class="widefat" 
                       id="<?php echo $this->get_field_id('uvt_no_of_post') ?>" 
                       name="<?php echo $this->get_field_name('uvt_no_of_post') ?>"
                       value="<?php echo esc_attr($uvt_no_of_post) ?>"/>
        </p>
        
        <!-- Display Front Pagination  -->
        <p>            
            <label for="<?php echo $this->get_field_id('uvt_pagination'); ?>"><?php _e('Display Pagination' , 'bpvm_wpva'); ?>: </label>
            <input id="<?php echo $this->get_field_id('uvt_pagination'); ?>" 
                       name="<?php echo $this->get_field_name('uvt_pagination'); ?>" 
                       type="checkbox" <?php checked($uvt_pagination, 'on'); ?> />
        </p>
        
        <!-- Display Hide Meta Information -->
        <p>            
            <label for="<?php echo $this->get_field_id('uvt_hide_meta'); ?>"><?php _e('Hide Meta Information' , 'bpvm_wpva'); ?>: </label>
            <input id="<?php echo $this->get_field_id('uvt_hide_meta'); ?>" 
                       name="<?php echo $this->get_field_name('uvt_hide_meta'); ?>" 
                       type="checkbox" <?php checked($uvt_hide_meta, 'on'); ?> />
        </p>
        
        <!-- Display Front End Global Mode -->
        <p>            
            <label for="<?php echo $this->get_field_id('uvt_global_mode'); ?>"><?php _e('Enable Global Mode' , 'bpvm_wpva'); ?>: </label>
            <input id="<?php echo $this->get_field_id('uvt_global_mode'); ?>" 
                       name="<?php echo $this->get_field_name('uvt_global_mode'); ?>" 
                       type="checkbox" <?php checked($uvt_global_mode, 'on'); ?> />
            <br /><small><?php _e('Display recent liked/disliked votes by all the voters.', 'bpvm_wpva');?></small>
        </p>
        
        <?php
        
    }
    
    public function update($new_instance, $old_instance) {
        
        $instance                               = $old_instance;
        
        $instance['title']                     = strip_tags( stripslashes( $new_instance['title'] ) );
           
        $instance['uvt_filter_type']    =  strip_tags( stripslashes( $new_instance['uvt_filter_type'] ) );
        
        $instance['uvt_no_of_post']  =  strip_tags( stripslashes( $new_instance['uvt_no_of_post'] ) );
        
        $instance['uvt_pagination']  =  strip_tags( stripslashes( $new_instance['uvt_pagination'] ) );
        
        $instance['uvt_hide_meta']  =  strip_tags( stripslashes( $new_instance['uvt_hide_meta'] ) );
        
        $instance['uvt_global_mode']  =  strip_tags( stripslashes( $new_instance['uvt_global_mode'] ) );
        
        return $instance;
        
    }
    
    public function widget($args, $instance) {
        
        extract($args);
        
        $title                      = apply_filters('widget-title' , $instance['title']);
        
        $uvt_filter_type = $instance['uvt_filter_type'];       
        
        $uvt_no_of_post = $instance['uvt_no_of_post'];
        
        $uvt_pagination = ( isset( $instance['uvt_pagination'] ) && $instance['uvt_pagination']=="on") ?   1: 0; 
        
        $uvt_hide_meta = ( isset( $instance['uvt_hide_meta'] ) && $instance['uvt_hide_meta']=="on") ?   1: 0; 
        
        $uvt_global_mode = ( isset( $instance['uvt_global_mode'] ) && $instance['uvt_global_mode']=="on") ?   1: 0; 
        
        if ( ! is_user_logged_in() && $uvt_global_mode == 0 ) {
      
               return ""; // uncomment it later
               
        }
        
        echo $before_widget;
        
        if($title) :
            
            echo $before_title . $title . $after_title;
        
        endif;
         
        echo do_shortcode('[uvt_front filter="'.$uvt_filter_type.'" limit="'.$uvt_no_of_post.'" pagination="'.$uvt_pagination.'" meta="'.$uvt_hide_meta.'" global_mode="'.$uvt_global_mode.'"]');
        
        echo $after_widget;
        
    }
    
}


/*------------------------------  Widget Initialization ---------------------------------*/

function uvt_widget_init() {
   
    register_widget('Uvt_Widget');
     
}

add_action( 'widgets_init', 'uvt_widget_init' ); 