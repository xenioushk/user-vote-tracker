<?php
namespace UVTADDON\Helpers;

/**
 * Class for plugin dependency manager.
 *
 * @package UVTADDON
 */
class DependencyManager {

	/**
	 * Allowed themes.
	 *
	 * @var array
	 */
	public static $allowed_themes = [];

	/**
	 * Plugin parent BKBM URL.
	 *
	 * @var string
	 */
	public static $bpvm_url;
	/**
	 * Plugin parent BKBM license URL.
	 *
	 * @var string
	 */
	public static $bpvm_license_url;
	/**
     * Plugin parent WPBakery Page Builder URL.
     *
     * @var string
     */
	public static $wpb_url;

	/**
	 * Plugin addon title.
	 *
	 * @var string
	 */
	public static $addon_title;

	/**
	 * Register the plugin constants.
	 */
	public static function register() {
		self::set_dependency_constants();
		self::set_urls();
	}

	/**
	 * Set the plugin dependency URLs.
	 */
	private static function set_urls() {
		self::$bpvm_url         = "<strong><a href='https://1.envato.market/bpvm-wp' target='_blank'>BWL Pro Voting Manager</a></strong>";
		self::$bpvm_license_url = "<strong><a href='" . admin_url( 'admin.php?page=bpvm-license' ) . "'>BWL Pro Voting Manager license</a></strong>";
		self::$addon_title      = '<strong>User Vote Tracker For BWL Pro Voting Manager</strong>';
	}

	/**
	 * Set the plugin dependency constants.
	 */
	private static function set_dependency_constants() {
		define( 'UVTADDON_MIN_BPVM_VERSION', '1.4.5' );
		define( 'UVTADDON_MIN_PHP_VERSION', '7.0' );
	}

	/**
	 * Check the minimum version requirement status.
	 *
	 * @return int
	 */
	public static function check_minimum_version_requirement_status() {
		// This is super important to check if the get_plugin_data function is already loaded or not.
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/bwl-pro-voting-manager/bwl-pro-voting-manager.php' );

		if ( ! defined( 'BPVM_CURRENT_PLUGIN_VERSION' ) ) {
			define( 'BPVM_CURRENT_PLUGIN_VERSION', $plugin_data['Version'] );
		}

		return ( version_compare( BPVM_CURRENT_PLUGIN_VERSION, UVTADDON_MIN_BPVM_VERSION, '>=' ) );
	}

	/**
	 * Set the product activation constants.
     *
	 * @return bool
	 */
	public static function get_product_activation_status() {

		return intval( get_option( 'bpvm_purchase_verified' ) );

	}

	/**
     * Function to handle the minimum version of parent plugin notice.
     *
     * @return void
     */
	public static function notice_min_version_main_plugin() {

		$message = sprintf(
				// translators: 1: Plugin name, 2: Addon title, 3: Current version, 4: Minimum required version
            esc_html__( 'The %2$s requires %1$s %4$s or higher. You are using %3$s', 'bpvm-recap' ),
            self::$bpvm_url,
            self::$addon_title,
            BPVM_CURRENT_PLUGIN_VERSION,
            UVTADDON_MIN_BPVM_VERSION
        );

		printf( '<div class="notice notice-error"><p>⚠️ %1$s</p></div>', $message ); // phpcs:ignore
	}

	/**
     * Function to handle the missing plugin notice.
     *
     * @return void
     */
	public static function notice_missing_main_plugin() {

		$message = sprintf(
						// translators: 1: Plugin name, 2: Addon title
            esc_html__( 'Please install and activate the %1$s plugin to use %2$s.', 'bpvm-recap' ),
            self::$bpvm_url,
            self::$addon_title
		);

	printf( '<div class="notice notice-error"><p>⚠️ %1$s</p></div>', $message ); // phpcs:ignore
	}

	/**
     * Function to handle the purchase verification notice.
     *
     * @return void
     */
	public static function notice_missing_purchase_verification() {

		$message = sprintf(
						// translators: 1: Plugin activation link, 2: Addon title
            esc_html__( 'Please Activate the %1$s to use the %2$s.', 'bpvm-recap' ),
            self::$bpvm_license_url,
            self::$addon_title
		);

		printf( '<div class="notice notice-error"><p>🔑 %1$s</p></div>', $message ); // phpcs:ignore
	}
}
