;(function ($) {
  function uvt_bpvm_installation_counter() {
    return $.ajax({
      type: "POST",
      url: ajaxurl,
      data: {
        action: "uvt_bpvm_installation_counter", // this is the name of our WP AJAX function that we'll set up next
        product_id: uvtBpvmAdminData.product_id, // change the localization variable.
      },
      dataType: "JSON",
    })
  }

  if (typeof uvtBpvmAdminData.installation != "undefined" && uvtBpvmAdminData.installation != 1) {
    $.when(uvt_bpvm_installation_counter()).done(function (response_data) {
      // console.log(response_data)
    })
  }
})(jQuery)
