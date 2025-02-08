<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Max_Marine_Background_Processor
 * @subpackage Max_Marine_Background_Processor/includes
 */

namespace Max_Marine\Background_Processor\Core;

use Max_Marine\Background_Processor\Core\Max_Marine_Background_Processor_Loader;
use Max_Marine\Background_Processor\Core\Max_Marine_Background_Processor_I18n;
use Max_Marine\Background_Processor\Admin\Max_Marine_Background_Processor_Admin;
use Max_Marine\Background_Processor\Frontend\Max_Marine_Background_Processor_Public;
use Max_Marine\Background_Processor\Core\Upgrade\Max_Marine_Background_Processor_Upgrader;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Max_Marine_Background_Processor
 * @subpackage Max_Marine_Background_Processor/includes
 * @author     David Baumwald <david@dream-encode.com>
 */
class Max_Marine_Background_Processor {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     Max_Marine_Background_Processor_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->plugin_name = 'max-marine-background-processor';

		$this->load_dependencies();
		$this->define_tables();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_global_hooks();
		$this->define_cli_commands();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Max_Marine_Background_Processor_Loader. Orchestrates the hooks of the plugin.
	 * - Max_Marine_Background_Processor_I18n. Defines internationalization functionality.
	 * - Max_Marine_Background_Processor_Admin. Defines all hooks for the admin area.
	 * - Max_Marine_Background_Processor_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function load_dependencies() {
		/**
		 * Logger
		 */
		require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/abstracts/abstract-wc-logger.php';
		require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/log/class-max-marine-background-processor-wc-logger.php';

		/**
		 * Action Scheduler
		 */
		require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'libraries/action-scheduler/action-scheduler.php';

		/**
		 * Upgrader.
		 */
		require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/upgrade/class-max-marine-background-processor-upgrader.php';

		/**
		 * REST API
		 */
		require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/rest-api/class-max-marine-background-processor-rest-response.php';
		require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/abstracts/abstract-rest-api.php';
		require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/abstracts/abstract-rest-controller.php';
		require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/rest-api/class-max-marine-background-processor-core-api.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/class-max-marine-background-processor-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/class-max-marine-background-processor-i18n.php';

		/**
		 * Default filters.
		 */
		require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/max-marine-background-processor-default-filters.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'admin/class-max-marine-background-processor-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'public/class-max-marine-background-processor-public.php';

		Max_Marine_Background_Processor_Upgrader::init();

		/**
		 * Background processing.
		 */
		require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/background-process/class-max-marine-background-processor-background-process-functions.php';
		require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/background-process/class-max-marine-background-processor-background-process-messages-functions.php';
		require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/background-process/class-max-marine-background-processor-background-process-runner.php';
		require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/background-process/class-max-marine-background-processor-background-processor-base.php';
		require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/background-process/class-max-marine-background-processor-dummy-processor.php';

		/**
		 * If this is a WP_CLI request, include the commands.
		 */
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			include_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/cli/class-max-marine-background-processor-cli-commands.php';
		}

		$this->loader = new Max_Marine_Background_Processor_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Max_Marine_Background_Processor_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function set_locale() {
		$plugin_i18n = new Max_Marine_Background_Processor_I18n();

		$this->loader->add_action( 'init', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Define custom databases tables.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function define_tables() {
		Max_Marine_Background_Processor_Upgrader::define_tables();
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Max_Marine_Background_Processor_Admin();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_pages' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function define_public_hooks() {
		$plugin_public = new Max_Marine_Background_Processor_Public();

		$this->loader->add_action( 'upgrader_process_complete', $plugin_public, 'upgrader_process_complete', 10, 2 );
		$this->loader->add_action( 'max_marine_background_processor_process_plugin_upgrade', $plugin_public, 'process_plugin_upgrade', 10, 2 );

		$this->loader->add_action( 'init', $plugin_public, 'rest_api_cors' );
		$this->loader->add_action( 'init', $plugin_public, 'rest_init' );

		$this->loader->add_action( 'rest_api_init', $plugin_public, 'register_plugin_settings' );
		$this->loader->add_action( 'admin_init', $plugin_public, 'register_plugin_settings' );
	}

	/**
	 * Register all of the global hooks .
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function define_global_hooks() {
	}

	/**
	 * Register custom WP_Cli commands.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	private function define_cli_commands() {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			\WP_CLI::add_command( 'mmbp', 'Max_Marine\Background_Processor\Core\CLI\Max_Marine_Background_Processor_CLI_Commands' ); // @phpstan-ignore-line
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since  1.0.0
	 * @return string  The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since  1.0.0
	 * @return Max_Marine_Background_Processor_Loader  Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since  1.0.0
	 * @return string  The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
