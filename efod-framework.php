<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://fortidigitalstudio.com
 * @since             0.0.1
 * @package           Efod Framework
 *
 * @wordpress-plugin
 * Plugin Name:       Efod Framework
 * Plugin URI:        https://fortidigitalstudio.com
 * Description:       This is a efod theme framework to enrich the theme.
 * Version:           0.0.1
 * Author:            FORTI Digital Studio
 * Author URI:        https://fortidigitalstudio.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       efod-framework
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 0.0.2 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EFOD_FRAMEWORK_VERSION', '0.0.1' );

if ( ! defined( 'MINIMUM_ELEMENTOR_VERSION' ) ) {
	define( 'MINIMUM_ELEMENTOR_VERSION', '2.0.0' );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-efod-framework-activator.php
 */
function activate_efod_framework() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-efod-framework-activator.php';
	Efod_Framework_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-efod-framework-deactivator.php
 */
function deactivate_efod_framework() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-efod-framework-deactivator.php';
	Efod_Framework_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_efod_framework' );
register_deactivation_hook( __FILE__, 'deactivate_efod_framework' );

/**
 * Efod framework initialization
 */
if ( ! isset( $efod_fw ) ) {
	$efod_fw = require_once plugin_dir_path( __FILE__ ) . 'includes/class-efod-framework.php';
}
