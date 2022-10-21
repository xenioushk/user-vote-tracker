(function($) {
 
    /*------------------------------ Vote Reporting ---------------------------------*/

    if ($('#uvt_voting_report_page').length) {

        var $uvt_ctrl_section = $('.uvt-ctrl-section'),
                $mv_buvt_post_type = $uvt_ctrl_section.find("#mv_buvt_post_type"),
                $mv_post_title = $uvt_ctrl_section.find("#mv_post_title"),
                $mv_post_filters = $uvt_ctrl_section.find("#mv_post_filters"),
                $mv_vote_info_type = $uvt_ctrl_section.find("#mv_vote_info_type"),
                $uvt_custom_date_range = $uvt_ctrl_section.find("#uvt_custom_date_range"),
                $uvt_filter_start_date = $uvt_ctrl_section.find("#uvt_filter_start_date"),
                $uvt_filter_end_date = $uvt_ctrl_section.find("#uvt_filter_end_date"),
                $mv_uvt_go = $("#mv_uvt_go"),
                $uvt_amv = $("#uvt_amv");
                
        var $uvt_user_id = $("#uvt_user_id");

        $mv_buvt_post_type.val("");
        $mv_post_filters.val("");
        $mv_vote_info_type.val(1);
        
        var $deleteTriger = $('#deleteTriger');

        /*------------------------------ Date Range Filter ---------------------------------*/

        var $uvt_date_range_items = $([]).add($uvt_filter_start_date).add($uvt_filter_end_date);
        
        $uvt_custom_date_range.prop( "checked", false );
        $uvt_date_range_items.val("");

        $uvt_custom_date_range.on('click', function () {

            if ($uvt_custom_date_range.is(':checked')) {
                $uvt_date_range_items.val("").removeAttr('disabled');
            } else {
                $uvt_date_range_items.val("").attr('disabled', 'disabled');
            }

        });

        // Filter Start Date.

        $uvt_filter_start_date.datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            dateFormat: 'yy-mm-dd',
            numberOfMonths: 1,
            onSelect: function (selectedDate) {

                $uvt_filter_end_date.datepicker("option", "minDate", selectedDate);

            }
        });

        $uvt_filter_end_date.datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            dateFormat: 'yy-mm-dd',
            numberOfMonths: 1,
            onSelect: function (selectedDate) {

                $uvt_filter_start_date.datepicker({maxDate: selectedDate});

            }
        });

        ///////////////////////////////////////////////////////////////////////////////////////////

        var $uvt_msg_container = $("#uvt_msg_container"),
             $mv_post_title_default_option_value = $("#mv_post_title").find("option:first");

            $uvt_msg_container.html("");

        // Go Events.

        $mv_uvt_go.on('click', function () {
            
            $deleteTriger.unbind('click');

            buvt_data_table ();

        });
        
        // Start Coding.
        
        buvt_data_table ();
        
        function buvt_data_table () {
            
            var post_type = $mv_buvt_post_type.val(),
                    post_id = $mv_post_title.val(),
                    mv_post_filters = $mv_post_filters.val(),
                    mv_vote_info_type = $mv_vote_info_type.val(),
                    uvt_custom_date_range = $uvt_custom_date_range.is(':checked'),
                    uvt_filter_start_date = $uvt_filter_start_date.val(),
                    uvt_filter_end_date = $uvt_filter_end_date.val();
            
            var $buvt_data_table = $('#uvt-data-table');

            $buvt_data_table.DataTable().destroy();

            $buvt_data_table.removeClass('dn');

            var dataTable = $buvt_data_table.DataTable({
                "processing": true,
                "serverSide": true,
                searching: false,
               "order": [[ 0, "desc" ]],
//                "columnDefs": [{
//                        "targets": 0,
//                        "orderable": false,
//                        "searchable": false
//                    }],
                "ajax": {
                    "url": ajaxurl + '?action=uvt_voting_stats',
                    "type": "POST",
                    "data": {
                        user_id: $uvt_user_id.val(),
                        post_id: post_id,
                        post_type: post_type,
                        mv_post_filters: mv_post_filters,
                        mv_vote_info_type: mv_vote_info_type,
                        uvt_custom_date_range: uvt_custom_date_range,
                        uvt_filter_start_date: uvt_filter_start_date,
                        uvt_filter_end_date: uvt_filter_end_date
                    }
                }
            });
            
        }
        

    } 
    
})(jQuery);