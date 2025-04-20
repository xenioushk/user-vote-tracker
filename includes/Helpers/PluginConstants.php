<?php
namespace UVTADDON\Helpers;

/**
 * Class for plugin constants.
 *
 * @package UVTADDON
 */
class PluginConstants {

		/**
         * Static property to hold plugin options.
         *
         * @var array
         */
	public static $plugin_options = [];

	/**
	 * Initialize the plugin options.
	 */
	public static function init() {

		self::$plugin_options = get_option( 'bwl_pvm_options' );
	}

		/**
         * Get the relative path to the plugin root.
         *
         * @return string
         * @example wp-content/plugins/<plugin-name>/
         */
	public static function get_plugin_path(): string {
		return dirname( dirname( __DIR__ ) ) . '/';
	}


    /**
     * Get the plugin URL.
     *
     * @return string
     * @example http://appealwp.local/wp-content/plugins/<plugin-name>/
     */
	public static function get_plugin_url(): string {
		return plugin_dir_url( self::get_plugin_path() . UVTADDON_PLUGIN_ROOT_FILE );
	}

	/**
	 * Register the plugin constants.
	 */
	public static function register() {
		self::init();
		self::set_paths_constants();
		self::set_base_constants();
		self::set_assets_constants();
		self::set_recaptcha_constants();
		self::set_updater_constants();
		self::set_product_info_constants();
	}

	/**
	 * Set the plugin base constants.
     *
	 * @example: $plugin_data = get_plugin_data( UVTADDON_PLUGIN_DIR . '/' . UVTADDON_PLUGIN_ROOT_FILE );
	 * echo '<pre>';
	 * print_r( $plugin_data );
	 * echo '</pre>';
	 * @example_param: Name,PluginURI,Description,Author,Version,AuthorURI,RequiresAtLeast,TestedUpTo,TextDomain,DomainPath
	 */
	private static function set_base_constants() {
		// This is super important to check if the get_plugin_data function is already loaded or not.
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$plugin_data = get_plugin_data( UVTADDON_PLUGIN_DIR . UVTADDON_PLUGIN_ROOT_FILE );

		define( 'UVTADDON_PLUGIN_VERSION', $plugin_data['Version'] ?? '1.0.0' );
		define( 'UVTADDON_PLUGIN_TITLE', $plugin_data['Name'] ?? 'User Vote Tracker For BWL Pro Voting Manager' );
		define( 'UVTADDON_TRANSLATION_DIR', $plugin_data['DomainPath'] ?? '/languages/' );
		define( 'UVTADDON_TEXT_DOMAIN', $plugin_data['TextDomain'] ?? '' );

		define( 'UVTADDON_PLUGIN_FOLDER', 'user-vote-tracker' );
		define( 'UVTADDON_PLUGIN_CURRENT_VERSION', UVTADDON_PLUGIN_VERSION );

	}

	/**
	 * Set the plugin paths constants.
	 */
	private static function set_paths_constants() {
		define( 'UVTADDON_PLUGIN_ROOT_FILE', 'user-vote-tracker.php' );
		define( 'UVTADDON_PLUGIN_DIR', self::get_plugin_path() );
		define( 'UVTADDON_PLUGIN_FILE_PATH', UVTADDON_PLUGIN_DIR );
		define( 'UVTADDON_PLUGIN_URL', self::get_plugin_url() );
	}

	/**
	 * Set the plugin assets constants.
	 */
	private static function set_assets_constants() {
		define( 'UVTADDON_PLUGIN_STYLES_ASSETS_DIR', UVTADDON_PLUGIN_URL . 'assets/styles/' );
		define( 'UVTADDON_PLUGIN_SCRIPTS_ASSETS_DIR', UVTADDON_PLUGIN_URL . 'assets/scripts/' );
		define( 'UVTADDON_PLUGIN_LIBS_DIR', UVTADDON_PLUGIN_URL . 'libs/' );
	}

	/**
	 * Set the recaptcha constants.
	 */
	private static function set_recaptcha_constants() {
		define( 'UVTADDON_SITE_KEY', self::$plugin_options['bpvm_recaptcha_site_key'] ?? '' );
		define( 'UVTADDON_ENABLE_STATUS', self::$plugin_options['bpvm_recaptcha_conditinal_fields']['enabled'] ?? [] );
		define( 'UVTADDON_TIME_INTERVAL_STATUS', self::$plugin_options['bpvm_recaptcha_conditinal_fields']['bpvm_recaptcha_interval'] ?? 3600 );
	}

	/**
	 * Set the updater constants.
	 */
	private static function set_updater_constants() {

		// Only change the slug.
		$slug        = 'bpvm/notifier_uvt.php';
		$updater_url = "https://projects.bluewindlab.net/wpplugin/zipped/plugins/{$slug}";

		define( 'UVTADDON_PLUGIN_UPDATER_URL', $updater_url ); // phpcs:ignore
		define( 'UVTADDON_PLUGIN_UPDATER_SLUG', UVTADDON_PLUGIN_FOLDER . '/' . UVTADDON_PLUGIN_ROOT_FILE ); // phpcs:ignore
		define( 'UVTADDON_PLUGIN_PATH', UVTADDON_PLUGIN_DIR );
	}

	/**
	 * Set the product info constants.
	 */
	private static function set_product_info_constants() {
		define( 'UVTADDON_PRODUCT_ID', '18480330' ); // Plugin codecanyon/themeforest Id.
		define( 'UVTADDON_PRODUCT_INSTALLATION_TAG', 'uvt_bpvm_installation_' . str_replace( '.', '_', UVTADDON_PLUGIN_VERSION ) );
	}
}
