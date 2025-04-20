<?php
namespace RECAPADDON\Base;

/**
 * Class for registering the plugin scripts and styles.
 *
 * @package RECAPADDON
 */
class Enqueue {

	/**
	 * Frontend script slug.
	 *
	 * @var string $frontend_script_slug
	 */
	private $frontend_script_slug;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Frontend script slug.
		// This is required to hook the loclization texts.
		$this->frontend_script_slug = 'bpvm-recap-frontend';
	}

	/**
	 * Register the plugin scripts and styles loading actions.
	 */
	public function register() {
		// Check if the site key is empty.
		if ( empty( trim( RECAPADDON_SITE_KEY ) ) ) {
			return;
		}

		// Enqueue scripts and styles.
		add_action( 'wp_enqueue_scripts', [ $this, 'get_the_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'get_the_scripts' ] );
	}

	/**
	 * Load the plugin styles.
	 */
	public function get_the_styles() {

		wp_enqueue_style(
            $this->frontend_script_slug,
            RECAPADDON_PLUGIN_STYLES_ASSETS_DIR . 'frontend.css',
            [],
            RECAPADDON_PLUGIN_VERSION
		);
	}

	/**
	 * Load the plugin scripts.
	 */
	public function get_the_scripts() {

		// Register JS
		wp_register_script( 'google-recaptcha', 'https://www.google.com/recaptcha/api.js', [], RECAPADDON_PLUGIN_VERSION, true );
		wp_enqueue_script(
            $this->frontend_script_slug,
            RECAPADDON_PLUGIN_SCRIPTS_ASSETS_DIR . 'frontend.js',
            [ 'jquery','google-recaptcha' ],
            RECAPADDON_PLUGIN_VERSION,
            true
		);

		// Load frontend variables used by the JS files.
		$this->get_the_localization_texts();
	}

	/**
	 * Load the localization texts.
	 */
	private function get_the_localization_texts() {

		// Localize scripts.
		// Frontend.
		// Access data: bpvmRecapData.version
		wp_localize_script(
            $this->frontend_script_slug,
            'bpvmRecapData',
            [
				'version'             => RECAPADDON_PLUGIN_VERSION,
				'recap_time_status'   => empty( RECAPADDON_ENABLE_STATUS ) ? 0 : 1,
				'recap_time_interval' => is_numeric( RECAPADDON_TIME_INTERVAL_STATUS ) ? RECAPADDON_TIME_INTERVAL_STATUS : 3600,
			]
		);
	}
}
