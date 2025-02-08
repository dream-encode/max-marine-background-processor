<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Max_Marine_Background_Processor
 * @subpackage Max_Marine_Background_Processor/admin
 */

namespace Max_Marine\Background_Processor\Admin;

use WP_Screen;

use Max_Marine\Background_Processor\Core\ListTable\Max_Marine_Background_Processor_Background_Processes_List_Table;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Max_Marine_Background_Processor
 * @subpackage Max_Marine_Background_Processor/admin
 * @author     David Baumwald <david@dream-encode.com>
 */
class Max_Marine_Background_Processor_Admin {

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function enqueue_styles() {
		if ( ! max_marine_background_processor_admin_current_screen_has_enqueued_assets() ) {
			return;
		}

		$current_screen = get_current_screen();

		if ( ! $current_screen instanceof WP_Screen ) {
			return;
		}

		$screens_to_assets = max_marine_background_processor_get_admin_screens_to_assets();

		foreach ( $screens_to_assets as $screen => $assets ) {
			if ( $current_screen->id !== $screen ) {
				continue;
			}

			foreach ( $assets as $asset ) {
				$asset_base_url = MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_URL . 'admin/';

				$asset_file = include( MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . "admin/assets/dist/js/admin-{$asset['name']}.min.asset.php" );

				wp_enqueue_style(
					"max-marine-background-processor-admin-{$asset['name']}",
					$asset_base_url . "assets/dist/css/admin-{$asset['name']}.min.css",
					max_marine_background_processor_get_style_asset_dependencies( $asset_file['dependencies'] ),
					$asset_file['version'],
					'all'
				);
			}
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( ! max_marine_background_processor_admin_current_screen_has_enqueued_assets() ) {
			return;
		}

		$current_screen = get_current_screen();

		if ( ! $current_screen instanceof WP_Screen ) {
			return;
		}

		$screens_to_assets = max_marine_background_processor_get_admin_screens_to_assets();

		foreach ( $screens_to_assets as $screen => $assets ) {
			if ( $current_screen->id !== $screen ) {
				continue;
			}

			foreach ( $assets as $asset ) {
				$asset_base_url = MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_URL . 'admin/';

				$asset_file = include( MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . "admin/assets/dist/js/admin-{$asset['name']}.min.asset.php" );

				wp_register_script(
					"max-marine-background-processor-admin-{$asset['name']}",
					$asset_base_url . "assets/dist/js/admin-{$asset['name']}.min.js",
					$asset_file['dependencies'],
					$asset_file['version'],
					array(
						'in_footer' => true,
					)
				);

				if ( ! empty( $asset['localization'] ) ) {
					wp_localize_script( "max-marine-background-processor-admin-{$asset['name']}", 'MAX_MARINE_BACKGROUND_PROCESSOR', $asset['localization'] );
				}

				wp_enqueue_script( "max-marine-background-processor-admin-{$asset['name']}" );

				wp_set_script_translations( "max-marine-background-processor-admin-{$asset['name']}", 'max-marine-background-processor' );
			}
		}
	}

	/**
	 * Adds menu pages.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function add_menu_pages() {
		add_submenu_page(
			'max-marine',
			__( 'Max Marine - Background Processor', 'max-marine-background-processor' ),
			__( 'Background Processor', 'max-marine-background-processor' ),
			'manage_options',
			'max-marine-background-processor-settings',
			array( $this, 'admin_settings_menu_callback' )
		);

		add_submenu_page(
			'tools.php',
			__( 'Max Marine - Background Processes', 'max-marine-background-processor' ),
			__( 'Background Processes', 'max-marine-background-processor' ),
			'manage_options',
			'max-marine-background-processor',
			array( $this, 'background_processes_menu_callback' ),
			300
		);

		add_submenu_page(
			'tools.php',
			__( 'Existing Background Processes', 'max-marine-background-processor' ),
			__( 'Existing Background Processes', 'max-marine-background-processor' ),
			'manage_options',
			'max-marine-background-processes',
			array( $this, 'background_processes_list_table' ),
			301
		);
	}

	/**
	 * Admin menu callback for the background processes page.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function background_processes_menu_callback() {
		echo '<div id="max-marine-background-processor-background-processes-page"></div>';
	}

	/**
	 * Admin menu callback for the plugin settings page.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function admin_settings_menu_callback() {
		echo '<div id="max-marine-background-processor-plugin-settings"></div>';
	}

	/**
	 * Display a listing of background processes.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function background_processes_list_table() {
		$option = 'per_page';

		$args = array(
			'label'   => __( 'Entries', 'max-marine-background-processor' ),
			'default' => 50,
			'option'  => 'entries_per_page',
		);

		add_screen_option( $option, $args );

		require_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/list-table/class-max-marine-background-processor-background-processes-list-table.php';

		$list_table = new Max_Marine_Background_Processor_Background_Processes_List_Table();

		$list_table->prepare_items();

		include MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'admin/partials/list-table/background-processes.php';
	}
}
