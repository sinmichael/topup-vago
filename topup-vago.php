<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/sinmichael
 * @since             1.0.0
 * @package           Topup_Vago
 *
 * @wordpress-plugin
 * Plugin Name:       Topup Vago
 * Plugin URI:        https://github.com/sinmichael/topup-vago
 * Description:       Topup Vago Plugin for WooCommerce
 * Version:           1.0.0
 * Author:            Sean Michael
 * Author URI:        https://github.com/sinmichael
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       topup-vago
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('TOPUP_VAGO_VERSION', '1.0.0');

if (!function_exists('is_plugin_active')) {
	include_once(ABSPATH . '/wp-admin/includes/plugin.php');
}

/**
 * Check for the existence of WooCommerce and any other requirements
 */
function topup_vago_check_requirements()
{
	if (is_plugin_active('woocommerce/woocommerce.php')) {
		return true;
	} else {
		add_action('admin_notices', 'topup_vago_missing_wc_notice');
		return false;
	}
}

/**
 * Display a message advising WooCommerce is required
 */
function topup_vago_missing_wc_notice()
{
	$class = 'notice notice-error';
	$message = __('Topup Vago requires WooCommerce to be installed and active.', 'topup-vago');

	printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-topup-vago-activator.php
 */
function activate_topup_vago()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-topup-vago-activator.php';
	Topup_Vago_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-topup-vago-deactivator.php
 */
function deactivate_topup_vago()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-topup-vago-deactivator.php';
	Topup_Vago_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_topup_vago');
register_deactivation_hook(__FILE__, 'deactivate_topup_vago');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-topup-vago.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_topup_vago()
{
	if (topup_vago_check_requirements()) {
		$plugin = new Topup_Vago();
		$plugin->run();
	}
}
run_topup_vago();
