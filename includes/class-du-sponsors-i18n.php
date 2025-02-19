<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.drunken-unicorn.eu
 * @since      1.0.0
 *
 * @package    Du_Sponsors
 * @subpackage Du_Sponsors/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Du_Sponsors
 * @subpackage Du_Sponsors/includes
 * @author     Drunken Unicorn <contact@drunken-unicorn.eu>
 */
class Du_Sponsors_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'du-sponsors',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
