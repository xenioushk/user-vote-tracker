;(function ($) {
  "use strict"

  $(function () {
    var $uvt_data_overlay = "uvt_data_overlay"

    $("body").on("click", ".uvt-btn-prev", function () {
      var $uvt_data_table = $(this).parents("ul.uvt_data_table")

      var start_id = $(this).data("start_id")
      var limit = $(this).data("limit")
      var filter = $(this).data("filter")
      var meta = $(this).data("meta")
      var global_mode = $(this).data("global_mode")
      var pagination = $(this).data("pagination")

      $uvt_data_table.addClass($uvt_data_overlay)

      $.when(get_user_vote_data(start_id, filter, limit, pagination, meta, global_mode)).done(function (response_data) {
        $uvt_data_table.html(response_data).removeClass($uvt_data_overlay)
      })

      return false
    })

    $("body").on("click", ".uvt-btn-next", function () {
      var $uvt_data_table = $(this).parents("ul.uvt_data_table")
      // console.log($(this).length)

      var $uvt_btn_next = $uvt_data_table.find(".uvt-btn-next")
      // console.log($uvt_btn_next.length)

      var start_id = $(this).data("start_id")
      var limit = $(this).data("limit")
      var filter = $(this).data("filter")
      var meta = $(this).data("meta")
      var global_mode = $(this).data("global_mode")
      var pagination = $(this).data("pagination")

      $uvt_data_table.addClass($uvt_data_overlay)

      $.when(get_user_vote_data(start_id, filter, limit, pagination, meta, global_mode)).done(function (response_data) {
        $uvt_data_table.html(response_data).removeClass($uvt_data_overlay)
      })

      return false
    })

    function get_user_vote_data(start_id, filter, limit, pagination, meta, global_mode) {
      return $.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
          action: "get_user_vote_data", // this is the name of our WP AJAX function that we'll set up next
          start_id: start_id,
          filter: filter,
          limit: limit,
          pagination: pagination,
          meta: meta,
          global_mode: global_mode,
        },
      })
    }
  })
})(jQuery)
