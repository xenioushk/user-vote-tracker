<?php

    // Add USER ID.

    $user_id = 0;

    if (is_user_logged_in()) {

        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        
    }
    
?>

<div class="wrap bpvm-filter-wrapper" id="uvt_voting_report_page">
    <input type="hidden" id="uvt_user_id" name="uvt_user_id" value="<?php echo $user_id; ?>"/>  
    <h2><?php _e('My Voting Report:', 'bpvm_uvt'); ?><span id="bpvm-admin-loader" class="dn">Processing ....</span></h2>
    
    <hr class="welcome-hr"/>
        
    <div class="uvt-ctrl-section">
        
        <form method="post" action="" enctype="multipart/form-data">
            
        <?php wp_nonce_field( 'pvm-voting-data-export', '_wpnonce-pvm-voting-data-export' ); ?>
        
        
        <table>
            <tr style="text-align: center;">
                <td style="width: 25%;"><?php _e('Post Types:', 'bpvm_uvt'); ?>
                    <span class="report-break"></span>
                    <select id="mv_buvt_post_type" name="mv_buvt_post_type">
                        <option value="" selected>- <?php _e('Select', 'bpvm_uvt'); ?> -</option>
                        <?php
                        $available_buvt_post_types = bpvm_get_widget_custom_post_types();

                        foreach ($available_buvt_post_types as $buvt_post_type_key => $buvt_post_type_value) :

                            $buvt_post_type_value = strtolower($buvt_post_type_value);

                            $buvt_post_type_title = ucfirst(str_replace('_', ' ', $buvt_post_type_value));
                        ?>
                            <option value="<?php echo $buvt_post_type_value; ?>"><?php echo $buvt_post_type_title; ?></option>
                         <?php
                            endforeach;
                            ?>
                    </select>
                </td>
                
                <td style="width: 25%; display: none;"><?php _e('Posts:', 'bpvm_uvt'); ?>
                    <span class="report-break"></span>
                    <select id="mv_post_title" name="mv_post_title">
                        <option value="" selected>- <?php _e('Select', 'bpvm_uvt'); ?> -</option>
                    </select>
                </td>
                
                <td style="width:10%;"><?php _e('Filter Type:', 'bpvm_uvt'); ?>
                    <span class="report-break"></span>
                    <select id="mv_post_filters" name="mv_post_filters">
                        <option value="" selected>- <?php _e('Select', 'bpvm_uvt'); ?> -</option>
                        <option value="1"><?php _e('Only Likes', 'bpvm_uvt'); ?></option>
                        <option value="2"><?php _e('Only Dislikes', 'bpvm_uvt'); ?></option>
                    </select>
                    
                </td>
                
                <td style="width: 10%; display: none;">
                    <?php _e('Info Type:', 'bpvm_uvt'); ?>
                    <span class="report-break"></span>
                    <select id="mv_vote_info_type" name="mv_vote_info_type">
                        <option value="1" selected="selected"><?php _e('Summary', 'bpvm_uvt'); ?></option>
                        <option value="2"><?php _e('Details', 'bpvm_uvt'); ?></option>
                    </select>
                </td>
                
                <td style="width: 40%;"><?php _e( 'Custom Date Range :', 'bpvm_uvt' ); ?> <input type="checkbox" id="uvt_custom_date_range"/> 
                    <span class="report-break"></span>
                    <input type="text" placeholder="Start" size="10" id="uvt_filter_start_date"/>
                    <input type="text" placeholder="End" size="10" id="uvt_filter_end_date"/>
                </td>
            </tr>
            
            <tr>
                <td colspan="9" style="text-align:center;">
                    <input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI'] ?>" />
                    <input type="button" name="mv_uvt_go" id="mv_uvt_go" value="<?php _e( 'Generate Report', 'bpvm_uvt' ); ?>" class="button button-primary button-large"/>
                </td>
            </tr>
        </table>
            
        </form>
     
    </div>
    
    
    <table class="nowrap display dn" cellspacing="0" width="100%" id="uvt-data-table" style="text-align: left !important;">
        <thead>
            <tr>
                <!--<th width="2%"><input type="checkbox" id="buvt_bulkdelete"/><button id="deleteTriger" class="button button-primary button-small btn-delete-data">X</button></th>-->                  
                <th width="30%"><?php _e('Post Title', 'bpvm_uvt'); ?></th>
                <th width="20%"><?php _e('Post Type', 'bpvm_uvt'); ?></th>
                <th width="10%"><?php _e('Vote Date', 'bpvm_uvt'); ?></th>
                <th width="10%"><?php _e('Vote Type', 'bpvm_uvt'); ?></th>
                <th width="10%"><?php _e('Votes', 'bpvm_uvt'); ?></th>                            
                <!--<th width="35%"><?php _e('Action', 'bpvm_uvt'); ?></th>-->
            </tr>
        </thead>
    </table>
    
</div> 