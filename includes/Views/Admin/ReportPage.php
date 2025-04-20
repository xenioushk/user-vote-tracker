<?php
// phpcs:ignoreFile
/**
 * User Voting Report Page Template
 *
 * @package UVTADDON
 */
?>

<div class="wrap bpvm-filter-wrapper" id="uvt_voting_report_page">
  <input type="hidden" id="uvt_user_id" name="uvt_user_id" value="<?php echo $user_id; ?>" />
  <h2><?php esc_html_e( 'My Voting Report:', 'bpvm_uvt' ); ?><span id="bpvm-admin-loader"
      class="dn"><?php esc_html_e( 'Processing....', 'bpvm_uvt' ); ?></span></h2>

  <hr class="welcome-hr" />

  <div class="uvt-ctrl-section">

    <form method="post" action="" enctype="multipart/form-data">

      <?php wp_nonce_field( 'pvm-voting-data-export', '_wpnonce-pvm-voting-data-export' ); ?>

      <table>
        <tr style="text-align: center;">
          <td style="width: 25%;"><?php esc_html_e( 'Post Types:', 'bpvm_uvt' ); ?>
            <span class="report-break"></span>
            <select id="mv_buvt_post_type" name="mv_buvt_post_type">
              <option value="" selected>- <?php esc_html_e( 'Select', 'bpvm_uvt' ); ?> -</option>
              <?php
                            

				foreach ( $post_types as $buvt_post_type_key => $buvt_post_type_value ) :

					$buvt_post_type_value = strtolower( $buvt_post_type_value );

					$buvt_post_type_title = ucfirst( str_replace( '_', ' ', $buvt_post_type_value ) );
					?>
              <option value="<?php echo $buvt_post_type_value; ?>"><?php echo $buvt_post_type_title; ?></option>
              <?php
                            endforeach;
				?>
            </select>
          </td>

          <td style="width: 25%; display: none;"><?php esc_html_e( 'Posts:', 'bpvm_uvt' ); ?>
            <span class="report-break"></span>
            <select id="mv_post_title" name="mv_post_title">
              <option value="" selected>- <?php esc_html_e( 'Select', 'bpvm_uvt' ); ?> -</option>
            </select>
          </td>

          <td style="width:10%;"><?php esc_html_e( 'Filter Type:', 'bpvm_uvt' ); ?>
            <span class="report-break"></span>
            <select id="mv_post_filters" name="mv_post_filters">
              <option value="" selected>- <?php esc_html_e( 'Select', 'bpvm_uvt' ); ?> -</option>
              <option value="1"><?php esc_html_e( 'Only Likes', 'bpvm_uvt' ); ?></option>
              <option value="2"><?php esc_html_e( 'Only Dislikes', 'bpvm_uvt' ); ?></option>
            </select>

          </td>

          <td style="width: 10%; display: none;">
            <?php esc_html_e( 'Info Type:', 'bpvm_uvt' ); ?>
            <span class="report-break"></span>
            <select id="mv_vote_info_type" name="mv_vote_info_type">
              <option value="1" selected="selected"><?php esc_html_e( 'Summary', 'bpvm_uvt' ); ?></option>
              <option value="2"><?php esc_html_e( 'Details', 'bpvm_uvt' ); ?></option>
            </select>
          </td>

          <td style="width: 40%;"><?php esc_html_e( 'Custom Date Range :', 'bpvm_uvt' ); ?> <input type="checkbox"
              id="uvt_custom_date_range" />
            <span class="report-break"></span>
            <input type="text" placeholder="Start" size="10" id="uvt_filter_start_date" />
            <input type="text" placeholder="End" size="10" id="uvt_filter_end_date" />
          </td>
        </tr>

        <tr>
          <td colspan="9" style="text-align:center;">
            <input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
            <input type="button" name="mv_uvt_go" id="mv_uvt_go"
              value="<?php esc_html_e( 'Generate Report', 'bpvm_uvt' ); ?>"
              class="button button-primary button-large" />
          </td>
        </tr>
      </table>

    </form>

  </div>


  <table class="nowrap display dn" cellspacing="0" width="100%" id="uvt-data-table"
    style="text-align: left !important;">
    <thead>
      <tr>
        <th width="30%"><?php esc_html_e( 'Post Title', 'bpvm_uvt' ); ?></th>
        <th width="20%"><?php esc_html_e( 'Post Type', 'bpvm_uvt' ); ?></th>
        <th width="10%"><?php esc_html_e( 'Vote Date', 'bpvm_uvt' ); ?></th>
        <th width="10%"><?php esc_html_e( 'Vote Type', 'bpvm_uvt' ); ?></th>
        <th width="10%"><?php esc_html_e( 'Votes', 'bpvm_uvt' ); ?></th>
      </tr>
    </thead>
  </table>

</div>
