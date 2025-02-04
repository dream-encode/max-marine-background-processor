<?php
/**
 * Fired during plugin activation.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Max_Marine\Background_Processor
 * @subpackage Max_Marine\Background_Processor/includes
 */

namespace Max_Marine\Background_Processor\Core;

use Max_Marine\Background_Processor\Core\Upgrade\Max_Marine_Background_Processor_Upgrader;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Max_Marine\Background_Processor
 * @subpackage Max_Marine\Background_Processor/includes
 * @author     David Baumwald <david@dream-encode.com>
 */
class Max_Marine_Background_Processor_Activator {
	/**
	 * Activator.
	 *
	 * Runs on plugin activation.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function activate() {
		Max_Marine_Background_Processor_Upgrader::install();
	}
}
