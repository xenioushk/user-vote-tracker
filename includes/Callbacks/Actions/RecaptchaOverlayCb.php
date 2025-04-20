<?php
namespace RECAPADDON\Callbacks\Actions;

/**
 * Class for registering recaptcha overlay actions.
 *
 * @package RECAPADDON
 * @since: 1.0.0
 * @author: Mahbub Alam Khan
 */
class RecaptchaOverlayCb {

	/**
	 * Get the layout for the recaptcha overlay.
	 */
	public function get_the_layout() {

		if ( empty( trim( RECAPADDON_SITE_KEY ) ) ) {
			return;
		}

		?>

<div class="bpvm_recaptcha_overlay">
    <div id="bpvm_recaptcha" class="g-recaptcha" data-sitekey="<?php echo RECAPADDON_SITE_KEY; ?>"
    data-callback="cb_bpvmrecap_count_vote"></div>
</div>


		<?php
	}
}
