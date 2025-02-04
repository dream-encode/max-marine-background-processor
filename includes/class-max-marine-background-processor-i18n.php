<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Max_Marine_Background_Processor
 * @subpackage Max_Marine_Background_Processor/includes
 */

namespace Max_Marine\Background_Processor\Core;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Max_Marine_Background_Processor
 * @subpackage Max_Marine_Background_Processor/includes
 * @author     David Baumwald <david@dream-encode.com>
 */
class Max_Marine_Background_Processor_I18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'max-marine-background-processor',
			false,
			MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'languages/'
		);
	}
}
