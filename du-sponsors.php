<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.drunken-unicorn.eu
 * @since             1.0.0
 * @package           Du_Sponsors
 *
 * @wordpress-plugin
 * Plugin Name:       Sponsor Lists
 * Plugin URI:        https://www.drunken-unicorn.eu
 * Description:       Show sponsors of several grades in several ways on your website. Slider, list and detailpage with some extra Elementor widgets
 * Version:           1.0.0
 * Author:            Drunken Unicorn
 * Author URI:        https://www.drunken-unicorn.eu/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       du-sponsors
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DU_SPONSORS_VERSION', '1.0.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-du-sponsors-activator.php
 */
function activate_du_sponsors() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-du-sponsors-activator.php';
	Du_Sponsors_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-du-sponsors-deactivator.php
 */
function deactivate_du_sponsors() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-du-sponsors-deactivator.php';
	Du_Sponsors_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_du_sponsors' );
register_deactivation_hook( __FILE__, 'deactivate_du_sponsors' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-du-sponsors.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_du_sponsors() {

	$plugin = new Du_Sponsors();
	$plugin->run();

}
run_du_sponsors();
