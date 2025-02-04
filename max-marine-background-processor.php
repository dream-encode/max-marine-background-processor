<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://dream-encode.com
 * @since             1.0.0
 * @package           Max_Marine_Background_Processor
 *
 * @wordpress-plugin
 * Plugin Name:       Max Marine - Background Processor
 * Plugin URI:        https://example.com
 * Description:       A reusable, abstracted plugin that provides a fraework for processing background tasks.
 * Version:           1.0.0
 * Author:            David Baumwald
 * Author URI:        https://dream-encode.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       max-marine-background-processor
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/dream-encode/max-marine-background-processor
 * Primary Branch:    main
 * Release Asset:     true
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Constants
 */
require_once 'includes/max-marine-background-processor-constants.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-max-marine-background-processor-activator.php
 *
 * @return void
 */
function max_marine_background_processor_activate() {
	require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/class-max-marine-background-processor-activator.php';
	Max_Marine\Background_Processor\Core\Max_Marine_Background_Processor_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-max-marine-background-processor-deactivator.php
 *
 * @return void
 */
function max_marine_background_processor_deactivate() {
	require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/class-max-marine-background-processor-deactivator.php';
	Max_Marine\Background_Processor\Core\Max_Marine_Background_Processor_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'max_marine_background_processor_activate' );
register_deactivation_hook( __FILE__, 'max_marine_background_processor_deactivate' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since  1.0.0
 * @return void
 */
function max_marine_background_processor_init() {
	/**
	 * Import some common functions.
	 */
	require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/max-marine-background-processor-core-functions.php';

	/**
	 * Main plugin loader class.
	 */
	require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/class-max-marine-background-processor.php';

	$plugin = new Max_Marine\Background_Processor\Core\Max_Marine_Background_Processor();
	$plugin->run();
}

max_marine_background_processor_init();
