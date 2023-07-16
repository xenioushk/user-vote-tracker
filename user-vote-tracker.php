<?php

/**
 * Plugin Name:   User Vote Tracker - Pro Voting Manager Addon
 * Plugin URI:      https://bluewindlab.net/portfolio/user-vote-tracker-bwl-pro-voting-manager-addon/
 * Description:     Addon track and count each post vote and display voted posts lists in user profile page.
 * Version:          1.0.3
 * Author:           Md Mahbub Alam Khan
 * Author URI:     https://1.envato.market/bpvm-wp
 * Text Domain:   bpvm_uvt
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

//Version Define For Parent Plugin And Addon.
// @Since: 1.0.1

define('BPVM_UVT_PARENT_PLUGIN_INSTALLED_VERSION', get_option('bwl_pvm_plugin_version'));
define('BPVM_UVT_ADDON_PARENT_PLUGIN_TITLE', '<b>BWL Pro Voting Manager</b> ');
define('BPVM_UVT_ADDON_TITLE', '<b>User Vote Tracker Addon</b>');
define('BPVM_UVT_PARENT_PLUGIN_REQUIRED_VERSION', '1.1.4'); // change plugin required version in here.
define('BPVM_UVT_ADDON_CURRENT_VERSION', '1.0.3'); // change plugin current version in here.
define("BPVM_UVT_ADDON_DIR", plugins_url() . '/user-vote-tracker/');
define("BPVM_UVT_ADDON_UPDATER_SLUG", plugin_basename(__FILE__)); // change plugin current version in here.

define('BPVM_UVT_DIR', plugin_dir_path(__FILE__));

require_once(plugin_dir_path(__FILE__) . 'public/class-uvt-addon.php');

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook(__FILE__, array('BPVM_UVT', 'activate'));
register_deactivation_hook(__FILE__, array('BPVM_UVT', 'deactivate'));

add_action('plugins_loaded', array('BPVM_UVT', 'get_instance'));

if (is_admin()) {

    require_once(plugin_dir_path(__FILE__) . 'admin/class-uvt-addon-admin.php');
    add_action('plugins_loaded', array('BPVM_UVT_Admin', 'get_instance'));
}