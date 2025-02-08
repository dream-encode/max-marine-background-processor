<?php
/**
 * Class Max_Marine_Background_Processor_Background_Process_Messages_Functions
 */

namespace Max_Marine\Background_Processor\Core\Background_Process;

use Max_Marine\Background_Processor\Core\Log\Max_Marine_Background_Processor_WC_Logger;

defined( 'ABSPATH' ) || exit;

/**
 * Class Max_Marine_Background_Processor_Background_Process_Messages_Functions
 */
class Max_Marine_Background_Processor_Background_Process_Messages_Functions {
	/**
	 * Get an array of allowed background process message types.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get_allowed_background_process_message_types() {
		return array(
			'info',
			'success',
			'warning',
			'error',
		);
	}

	/**
	 * Get all undismissed background processes messages.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb  WordPress database instance global.
	 * @return array
	 */
	public static function get_undismissed_background_processes_messages() {
		global $wpdb;

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM %i WHERE dismissed = %d ORDER BY datetime_added ASC',
				$wpdb->mmbp_background_processes_messages,
				0
			)
		);

		return $results;
	}

	/**
	 * Get all undismissed background processes messages by user ID.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb     WordPress database instance global.
	 * @param  int   $user_id  User ID.
	 * @return array
	 */
	public static function get_undismissed_background_processes_messages_for_user( $user_id ) {
		global $wpdb;

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM %i WHERE user_id = %d AND dismissed = %d ORDER BY datetime_added ASC',
				$wpdb->mmbp_background_processes_messages,
				absint( $user_id ),
				0
			)
		);

		return $results;
	}

	/**
	 * Add a single background processes message.
	 *
	 * @since  1.0.0
	 * @global WPDB   $wpdb     WordPress database instance global.
	 * @param  array  $message  Message data.
	 * @return int|false
	 */
	public static function create_background_processes_message( $message ) {
		$required_keys = array( 'background_processes_id', 'message', 'type' );

		foreach ( $required_keys as $key ) {
			if ( ! isset( $message[ $key ] ) || empty( $message[ $key ] ) ) {
				Max_Marine_Background_Processor_WC_Logger::warning(
					sprintf(
						/* translators: %s: Missing required key. */
						__( 'Missing "%s" field!', 'max-marine-background-processor' ),
						sanitize_text_field( $key )
					)
				);

				return false;
			}
		}

		// Check message type.
		if ( ! in_array( $message['type'], self::get_allowed_background_process_message_types(), true ) ) {
			Max_Marine_Background_Processor_WC_Logger::warning(
				sprintf(
					/* translators: %s: Invalid message type. */
					__( 'Invalid background message type: %s!', 'max-marine-background-processor' ),
					sanitize_text_field( $message['type'] )
				)
			);

			return false;
		}

		// No errors, so insert the message.
		$allowed_keys = array(
			'background_processes_id'     => '%d',
			'background_processes_run_id' => '%d',
			'message'                     => '%s',
			'type'                        => '%s',
			'user_id'                     => '%d',
		);

		$insert_keys = array();
		$insert_types = array();

		foreach ( array_keys( $allowed_keys ) as $allowed_key ) {
			if ( ! isset( $message[ $allowed_key ] ) ) {
				continue;
			}

			$insert_keys[ $allowed_key ] = $message[ $allowed_key ];
			$insert_types[]              = $allowed_keys[ $allowed_key ];
		}

		if ( empty( $insert_keys ) ) {
			return false;
		}

		// Add defaults.
		$insert_keys['datetime_added'] = max_marine_background_processor_get_mysql_datetime();
		$insert_types[]                = '%s';

		if ( ! isset( $message['type'] ) ) {
			$insert_keys['type'] = 'message';
			$insert_types[]      = '%s';
		}

		global $wpdb;

		$wpdb->insert(
			$wpdb->mmbp_background_processes_messages,
			$insert_keys,
			$insert_types
		);

		if ( ! empty( $wpdb->last_error ) ) {
			Max_Marine_Background_Processor_WC_Logger::error(
				sprintf(
					/* translators: %s: MySQL error text. */
					__( 'Error inserting message!  MySQL said: %s', 'max-marine-background-processor' ),
					sanitize_text_field( $wpdb->last_error )
				)
			);

			return false;
		}

		return $wpdb->insert_id;
	}

	/**
	 * Dismiss a single background process message.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb                             WordPress database instance global.
	 * @param  int   $background_processes_message_id  Background process message ID.
	 * @return void
	 */
	public static function dismiss_background_processes_message( $background_processes_message_id ) {
		global $wpdb;

		$wpdb->update(
			$wpdb->mmbp_background_processes_messages,
			array(
				'dismissed'          => 1,
				'datetime_dismissed' => max_marine_background_processor_get_mysql_datetime(),
			),
			array(
				'id' => absint( $background_processes_message_id ),
			),
			array(
				'%d',
				'%s',
			),
			array(
				'%d',
			)
		);
	}

	/**
	 * Dismiss all undismissed background process messages.
	 *
	 * @since  1.3.0
	 * @global WPDB  $wpdb  WordPress database instance global.
	 * @return void
	 */
	public static function dismiss_all_background_processes_messages() {
		global $wpdb;

		$wpdb->update(
			$wpdb->mmbp_background_processes_messages,
			array(
				'dismissed'          => 1,
				'datetime_dismissed' => max_marine_background_processor_get_mysql_datetime(),
			),
			array(
				'dismissed' => 0,
			),
			array(
				'%d',
				'%s',
			),
			array(
				'%d',
			)
		);
	}
}
