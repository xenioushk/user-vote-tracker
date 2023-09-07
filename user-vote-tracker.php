<?php

/**
 * Plugin Name:   User Vote Tracker - Pro Voting Manager Addon
 * Plugin URI:      https://bluewindlab.net/portfolio/user-vote-tracker-bwl-pro-voting-manager-addon/
 * Description:     This addon performs the task of diligently monitoring and counting votes on each post. It then compiles these vote records into user-friendly lists readily accessible on the individual user profile pages. This feature simplifies reviewing one's voting history and encourages user engagement with post-voting.
 * Version:          1.0.6
 * Author:           Mahbub Alam Khan
 * Author URI:     https://1.envato.market/bpvm-wp
 * Requires at least: 6.0+
 * Text Domain:   bpvm_uvt
 * Domain Path: /languages/
 * 
 * 
 * @package User Vote Tracker - Pro Voting Manager Addon
 * @author Mahbub Alam Khan
 * @license GPL-2.0+
 * @link https://bluewindlab.net
 * @copyright 2023 BlueWindLab
 * 
 */


// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

//Version Define For Parent Plugin And Addon.

define('BPVM_UVT_PARENT_PLUGIN_INSTALLED_VERSION', get_option('bwl_pvm_plugin_version'));
define('BPVM_UVT_ADDON_PARENT_PLUGIN_TITLE', 'BWL Pro Voting Manager');
define('BPVM_UVT_ADDON_TITLE', "User Vote Tracker Addon");
define('BPVM_UVT_PARENT_PLUGIN_REQUIRED_VERSION', '1.3.0'); // change plugin required version in here.
define('BPVM_UVT_ADDON_CURRENT_VERSION', '1.0.6'); // change plugin current version in here.

define("BPVM_UVT_ADDON_ROOT_FILE", "user-vote-tracker.php"); // use for the meta info.

define('BPVM_UVT_PATH', plugin_dir_path(__FILE__));
define("BPVM_UVT_ADDON_DIR", plugins_url() . '/user-vote-tracker/');
define("BPVM_UVT_ADDON_UPDATER_SLUG", plugin_basename(__FILE__));
define("BPVM_UVT_CC_ID", "18480330");
define("BPVM_UVT_INSTALLATION_TAG", "uvt_bpvm_installation_" . str_replace('.', '_', BPVM_UVT_ADDON_CURRENT_VERSION));

require_once(plugin_dir_path(__FILE__) . 'includes/public/class-uvt-addon.php');

register_activation_hook(__FILE__, ['BPVM_UVT', 'activate']);
register_deactivation_hook(__FILE__, ['BPVM_UVT', 'deactivate']);

add_action('plugins_loaded', ['BPVM_UVT', 'get_instance']);

if (is_admin()) {
    require_once(plugin_dir_path(__FILE__) . 'includes/admin/class-uvt-addon-admin.php');
    add_action('plugins_loaded', ['BPVM_UVT_Admin', 'get_instance']);
}
