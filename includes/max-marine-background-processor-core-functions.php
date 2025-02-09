<?php
/**
 * Common functions for the plugin.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Max_Marine_Background_Processor
 * @subpackage Max_Marine_Background_Processor/includes
 */

/**
 * Define a constant if it is not already defined.
 *
 * @since  1.0.0
 * @param  string  $name   Constant name.
 * @param  mixed   $value  Constant value.
 * @return void
 */
function max_marine_background_processor_maybe_define_constant( $name, $value ) {
	if ( ! defined( $name ) ) {
		define( $name, $value );
	}
}

/**
 * Get a plugin setting by key.
 *
 * @since  1.0.0
 * @param  string  $key      Setting key.
 * @param  mixed   $default  Optional. Default value. Default false.
 * @return mixed
 */
function max_marine_background_processor_get_plugin_setting( $key, $default = false ) {
	static $settings = false;

	if ( false === $settings ) {
		$settings = get_option( 'max_marine_background_processor_plugin_settings', array() );
	}

	if ( isset( $settings[ $key ] ) ) {
		return $settings[ $key ];
	}

	return $default;
}

/**
 * Get an array of data that relates enqueued assets to specific admin screens.
 *
 * @since  1.0.0
 * @return array
 */
function max_marine_background_processor_get_admin_screens_to_assets() {
	return array(
		'tools_page_max-marine-background-processor' => array(
			array(
				'name'         => 'background-processes-page',
				'localization' => array(
					'ADMIN_URL'   => admin_url( '/' ),
					'REST_URL'    => get_rest_url( null, '/max-marine/v1/background-processor' ),
					'WP_REST_URL' => get_rest_url(),
					'NONCES'      => array(
						'REST' => wp_create_nonce( 'wp_rest' ),
					),
				),
			),
		),
		'max-marine_page_max-marine-background-processor-settings' => array(
			array(
				'name'         => 'settings-page',
				'localization' => array(
					'REST_URL'    => get_rest_url( null, '' ),
					'WP_REST_URL' => get_rest_url(),
					'NONCES'      => array(
						'REST' => wp_create_nonce( 'wp_rest' ),
					),
					'SETTINGS'    => get_option( 'max_marine_background_processor_plugin_settings', array() ),
				),
			),
		),
	);
}

/**
 * Get a list of WP style dependencies.
 *
 * @since  1.0.0
 * @return string[]
 */
function max_marine_background_processor_get_wp_style_dependencies() {
	return array(
		'wp-components',
	);
}

/**
 * Get a list of WP style dependencies.
 *
 * @since  1.0.0
 * @param  array  $dependencies  Raw dependencies.
 * @return string[]
 */
function max_marine_background_processor_get_style_asset_dependencies( $dependencies ) {
	$style_dependencies = max_marine_background_processor_get_wp_style_dependencies();

	$new_dependencies = array();

	foreach ( $dependencies as $dependency ) {
		if ( in_array( $dependency, $style_dependencies, true ) ) {
			$new_dependencies[] = $dependency;
		}
	}

	return $new_dependencies;
}

/**
 * Get enqueued assets for the current admin screen.
 *
 * @since  1.0.0
 * @return array
 */
function max_marine_background_processor_admin_current_screen_enqueued_assets() {
	$current_screen = get_current_screen();

	if ( ! $current_screen instanceof WP_Screen ) {
		return array();
	}

	$assets = max_marine_background_processor_get_admin_screens_to_assets();

	return ! empty( $assets[ $current_screen->id ] ) ? $assets[ $current_screen->id ] : array();
}

/**
 * Check if the current admin screen has any enqueued assets.
 *
 * @since  1.0.0
 * @return int
 */
function max_marine_background_processor_admin_current_screen_has_enqueued_assets() {
	return count( max_marine_background_processor_admin_current_screen_enqueued_assets() );
}

/**
 * Get enqueued assets for the an admin screen.
 *
 * @since  1.0.0
 * @param  WP_Screen  $screen  Screen to check.
 * @return array
 */
function max_marine_background_processor_admin_screen_enqueued_assets( $screen ) {
	if ( ! $screen instanceof WP_Screen ) {
		return array();
	}

	$assets = max_marine_background_processor_get_admin_screens_to_assets();

	return ! empty( $assets[ $screen->id ] ) ? $assets[ $screen->id ] : array();
}

/**
 * Check if an admin screen has any enqueued assets.
 *
 * @since  1.0.0
 * @param  WP_Screen  $screen  Screen to check.
 * @return int
 */
function max_marine_background_processor_admin_screen_has_enqueued_assets( $screen ) {
	return count( max_marine_background_processor_admin_screen_enqueued_assets( $screen ) );
}

/**
 * Get a MYSQL DateTime from a timestamp.
 *
 * @since  1.0.0
 * @param  false|float|int     $time       Optional. Timestamp to convert.  Default false.
 * @param  false|DateTimeZone  $timezone   Optional. Timezone. Default false.
 * @return string|false
 */
function max_marine_background_processor_get_mysql_datetime( $time = false, $timezone = false ) {
	if ( ! $time ) {
		$time = time();
	}

	if ( false === $timezone ) {
		$timezone = wp_timezone();
	}

	return wp_date( 'Y-m-d H:i:s', intval( $time ), $timezone );
}

/**
 * Get a short date format.
 *
 * @since  1.0.0
 * @param  string  $datetime  MySQL DateTime.
 * @return string|false
 */
function max_marine_background_processor_mysql_datetime_to_date_time_short( $datetime ) {
	return mysql2date( 'D M j, Y g:i:s a', $datetime );
}

/**
 * Format a timestamp to a long date time format.
 *
 * @since  1.0.0
 * @param  null|float|int      $timestamp  Optional. Timestamp to convert. Default null.
 * @param  false|DateTimeZone  $timezone   Optional. Timezone. Default wp_timezone().
 * @return string|false
 */
function max_marine_background_processor_format_timestamp_to_datetime_long( $timestamp = null, $timezone = false ) {
	if ( ! $timestamp ) {
		$timestamp = time();
	}

	if ( ! $timezone ) {
		$timezone = wp_timezone();
	}

	return wp_date( 'l F j, Y \a\t g:i:s a', (int) $timestamp, $timezone );
}

/**
 * Convert seconds to minutes and seconds, e.g. 5:13.
 *
 * @since  1.0.0
 * @global WPDB  $wpdb     WordPress database instance global.
 * @param  int   $seconds  Seconds.
 * @return string
 */
function max_marine_background_processor_convert_seconds_to_minutes_seconds( $seconds ) {
	return sprintf(
		'%02d:%02d',
		( $seconds / MINUTE_IN_SECONDS ) % MINUTE_IN_SECONDS,
		$seconds % MINUTE_IN_SECONDS
	);
}

/**
 * Get a user by ID.
 *
 * @since  1.0.0
 * @param  int   $user_id  User ID.
 * @return string
 */
function max_marine_background_processor_get_user_name_by_id( $user_id ) {
	$user = get_user_by( 'id', $user_id );

	if ( ! $user instanceof WP_User ) {
		return __( 'N/A', 'max-marine-background-processor' );
	}

	return $user->user_nicename;
}

/**
 * Output debug stuff.
 *
 * @since  1.0.0
 * @param  mixed  $value  Value.
 * @param  bool  $exit    Optional. Exit? Default false.
 * @return void
 */
function max_marine_background_processor_debug( $value, $exit = false ) {
	if ( function_exists( 'ray' ) ) {
		ray( $value );
	}

	if ( $exit ) {
		( function_exists( 'ray' ) ) ? ray()->pause() : die();
	}
}

