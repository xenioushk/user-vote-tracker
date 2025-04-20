<?php

/**
 * Plugin Name:   User Vote Tracker - Pro Voting Manager Addon
 * Plugin URI:      https://1.envato.market/bpvm-wp
 * Description:     This addon performs the task of diligently monitoring and counting votes on each post. It then compiles these vote records into user-friendly lists readily accessible on the individual user profile pages. This feature simplifies reviewing one's voting history and encourages user engagement with post-voting.
 * Version:          2.0.0
 * Author:           Mahbub Alam Khan
 * Author URI:     https://codecanyon.net/user/xenioushk
 * WP Requires at least: 6.0+
 * Text Domain:   bpvm_uvt
 * Domain Path: /languages/
 *
 * @package   UVTADDON
 * @author    Mahbub Alam Khan
 * @license   GPL-2.0+
 * @link      https://codecanyon.net/user/xenioushk
 * @copyright 2025 BlueWindLab
 */

namespace UVTADDON;

// security check.
defined( 'ABSPATH' ) || die( 'Unauthorized access' );

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}
// Load the plugin constants
if ( file_exists( __DIR__ . '/includes/Helpers/DependencyManager.php' ) ) {
	require_once __DIR__ . '/includes/Helpers/DependencyManager.php';
	Helpers\DependencyManager::register();
}

use UVTADDON\Base\Activate;
use UVTADDON\Base\Deactivate;

/**
 * Function to handle the activation of the plugin.
 *
 * @return void
 */
 function activate_plugin() { // phpcs:ignore
	$activate = new Activate();
	$activate->activate();
}

/**
 * Function to handle the deactivation of the plugin.
 *
 * @return void
 */
 function deactivate_plugin() { // phpcs:ignore
	Deactivate::deactivate();
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate_plugin' );
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\\deactivate_plugin' );

/**
 * Function to handle the initialization of the plugin.
 *
 * @return void
 */
function init_recap_addon() {

	// Check if the parent plugin installed.
	if ( ! class_exists( 'BPVMWP\\Init' ) ) {
		add_action( 'admin_notices', [ Helpers\DependencyManager::class, 'notice_missing_main_plugin' ] );
		return;
	}

	// Check parent plugin activation status.
	if ( ! ( Helpers\DependencyManager::get_product_activation_status() ) ) {
		add_action( 'admin_notices', [ Helpers\DependencyManager::class, 'notice_missing_purchase_verification' ] );
		return;
	}

	if ( class_exists( 'UVTADDON\\Init' ) ) {

		// Check the required minimum version of the parent plugin.
		if ( ! ( Helpers\DependencyManager::check_minimum_version_requirement_status() ) ) {
			add_action( 'admin_notices', [ Helpers\DependencyManager::class, 'notice_min_version_main_plugin' ] );
			return;
		}

		// Initialize the plugin.
		Init::register_services();
	}
}

add_action( 'init', __NAMESPACE__ . '\\init_recap_addon' );

return;

// Version Define For Parent Plugin And Addon.

define( 'BPVM_UVT_PARENT_PLUGIN_INSTALLED_VERSION', get_option( 'bwl_pvm_plugin_version' ) );
define( 'BPVM_UVT_ADDON_PARENT_PLUGIN_TITLE', 'BWL Pro Voting Manager' );
define( 'BPVM_UVT_ADDON_TITLE', 'User Vote Tracker Addon' );
define( 'BPVM_UVT_PARENT_PLUGIN_REQUIRED_VERSION', '1.3.0' ); // change plugin required version in here.
define( 'BPVM_UVT_ADDON_CURRENT_VERSION', '1.0.9' ); // change plugin current version in here.

define( 'BPVM_UVT_ADDON_ROOT_FILE', 'user-vote-tracker.php' ); // use for the meta info.

define( 'BPVM_UVT_PATH', plugin_dir_path( __FILE__ ) );
define( 'BPVM_UVT_ADDON_DIR', plugins_url() . '/user-vote-tracker/' );
define( 'BPVM_UVT_ADDON_UPDATER_SLUG', plugin_basename( __FILE__ ) );
define( 'BPVM_UVT_CC_ID', '18480330' );
define( 'BPVM_UVT_INSTALLATION_TAG', 'uvt_bpvm_installation_' . str_replace( '.', '_', BPVM_UVT_ADDON_CURRENT_VERSION ) );

require_once plugin_dir_path( __FILE__ ) . 'includes/public/class-uvt-addon.php';

register_activation_hook( __FILE__, [ 'BPVM_UVT', 'activate' ] );
register_deactivation_hook( __FILE__, [ 'BPVM_UVT', 'deactivate' ] );

add_action( 'plugins_loaded', [ 'BPVM_UVT', 'get_instance' ] );

if ( is_admin() ) {
    include_once plugin_dir_path( __FILE__ ) . 'includes/admin/class-uvt-addon-admin.php';
    add_action( 'plugins_loaded', [ 'BPVM_UVT_Admin', 'get_instance' ] );
}
