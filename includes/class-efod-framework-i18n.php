<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://fortidigitalstudio.com
 * @since      1.0.0
 *
 * @package    Efod_Framework
 * @subpackage Efod_Framework/includes
 */

/**
 * The file the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Efod_Framework
 * @subpackage Efod_Framework/includes
 * @author     FORTI Digital Studio <fortidigitalstudio@gmail.com>
 */
class Efod_Framework_I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'efod-framework',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}
