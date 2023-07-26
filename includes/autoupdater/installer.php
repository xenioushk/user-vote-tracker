<?php

add_action("wp_ajax_uvt_bpvm_installation_counter", "uvtBpvmAddInstallationData");
add_action("wp_ajax_nopriv_uvt_bpvm_installation_counter", "uvtBpvmAddInstallationData");

function uvtBpvmApiUrl()
{
  $baseUrl = get_home_url();
  if (strpos($baseUrl, "localhost") != false) {
    return "http://localhost/bwl_api/";
  } elseif (strpos($baseUrl, "staging.bluewindlab.com") != false) {
    return "https://staging.bluewindlab.com/bwl_api/";
  } else {
    return "https://api.bluewindlab.net/";
  }
}
// 
function uvtBpvmAddInstallationData()
{
  $apiURL = uvtBpvmApiUrl();
  $site_url = get_site_url();
  $product_id = BPVM_UVT_CC_ID; // change the id
  $ip = $_SERVER['REMOTE_ADDR'];
  $requestUrl = $apiURL . "wp-json/bwlapi/v1/installation/count?product_id=$product_id&site=$site_url&referer=$ip";

  $output = wp_remote_get($requestUrl);

  // New Code.

  // Default.
  $data = [
    'status' => 0
  ];

  if (is_array($output) && !is_wp_error($output) && wp_remote_retrieve_response_code($output) === 200) {

    $data = wp_remote_retrieve_body($output); // Get the response body.

    $output_decode = json_decode($data, true);

    if (isset($output_decode['status']) && $output_decode['status'] == 1) {

      update_option('uvt_bpvm_installation', '1'); // change the tag

      $data = [
        'status' => $output_decode['status'],
        'msg' => $output_decode['msg']
      ];
    }
  } else {
    // Request failed or returned an error status code.
    if (is_wp_error($output)) {
      // Handle WP_Error case.
      $error_message = $output->get_error_message();
      $data = [
        'msg' => $error_message
      ];
    } else {
      // Handle non-200 status codes.
      $status_code = wp_remote_retrieve_response_code($output);

      $data = [
        'msg' =>  "Request failed with status code: $status_code"
      ];
    }
  }

  echo json_encode($data);

  die();
}
