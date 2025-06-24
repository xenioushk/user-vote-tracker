<?php

/**
 * Plugin Name:   User Vote Tracker - Pro Voting Manager Addon
 * Plugin URI:      https://1.envato.market/bpvm-wp
 * Description:     This addon performs the task of diligently monitoring and counting votes on each post.
 * Version:          2.0.2
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
require_once __DIR__ . '/includes/Widgets/index.php';
