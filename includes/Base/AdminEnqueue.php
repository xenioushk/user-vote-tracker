<?php
namespace RECAPADDON\Base;

/**
 * Class for registering the plugin admin scripts and styles.
 *
 * @package RECAPADDON
 */
class AdminEnqueue {

	/**
	 * Admin script slug.
	 *
	 * @var string $admin_script_slug
	 */
	private $admin_script_slug;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Frontend script slug.
		// This is required to hook the loclization texts.
		$this->admin_script_slug = 'bpvm-recap-admin';
	}

	/**
	 * Register the plugin scripts and styles loading actions.
	 */
	public function register() {
		// for admin.
		add_action( 'admin_enqueue_scripts', [ $this, 'get_the_scripts' ] );
	}
	/**
     * Load the plugin styles and scripts.
     */
	public function get_the_scripts() {

				wp_enqueue_script(
					$this->admin_script_slug,
					RECAPADDON_PLUGIN_SCRIPTS_ASSETS_DIR . 'admin.js',
					[ 'jquery' ],
					RECAPADDON_PLUGIN_VERSION, true
				);

				$this->get_the_localization_texts();
	}

	/**
	 * Load the localization texts.
	 */
	private function get_the_localization_texts() {

		// Localize scripts.
		// Frontend.
		// Access data: bkbTplAdminData.version
		wp_localize_script(
            $this->admin_script_slug,
            'RecapAddonAdminData',
            [
				'version'      => RECAPADDON_PLUGIN_VERSION,
				'ajaxurl'      => esc_url( admin_url( 'admin-ajax.php' ) ),
				'product_id'   => RECAPADDON_PRODUCT_ID,
				'installation' => get_option( RECAPADDON_PRODUCT_INSTALLATION_TAG ),
			]
		);
	}
}
