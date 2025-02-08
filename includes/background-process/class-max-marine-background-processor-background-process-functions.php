<?php
/**
 * Class Max_Marine_Background_Processor_Background_Process_Functions
 */

namespace Max_Marine\Background_Processor\Core\Background_Process;

use stdClass;
use Exception;

use Max_Marine\Background_Processor\Core\Background_Process\Max_Marine_Background_Processor_Background_Process_Runner;
use Max_Marine\Background_Processor\Core\Background_Process\Max_Marine_Background_Processor_Background_Process_Messages_Functions;
use Max_Marine\Background_Processor\Core\Log\Max_Marine_Background_Processor_WC_Logger;

defined( 'ABSPATH' ) || exit;

/**
 * Class Max_Marine_Background_Processor_Background_Process_Functions
 */
class Max_Marine_Background_Processor_Background_Process_Functions {

	/**
	 * Add this processor to array of available processors.
	 *
	 * @since  1.0.0
	 * @param  array  $processors  Existing processor labels.
	 * @return array
	 */
	public static function add_background_processor( $processors ) {
		$processors['dummy_processor'] = array(
			'key'   => 'dummy_processor',
			'label' => __( 'Dummy Processor', 'max-marine-background-processor' ),
			'class' => 'Max_Marine\\Background_Processor\\Core\\Background_Process\\Max_Marine_Background_Processor_Dummy_Processor',
		);

		return $processors;
	}

	/**
	 * Get an array of available processors.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get_background_processors() {
		$filtered_processors = apply_filters( 'max-marine/background-processor/background-processors', array() );

		return $filtered_processors;
	}

	/**
	 * Get a processor label.
	 *
	 * @since  1.0.0
	 * @param  string  $processor  Processor key.
	 * @return string
	 */
	public static function get_background_processor_label( $processor ) {
		$processors = static::get_background_processors();

		return $processors[ $processor ]['label'];
	}

	/**
	 * Get a class for a processor.
	 *
	 * @since  1.0.0
	 * @throws Exception If class not found.
	 * @param  string  $processor  Processor key.
	 * @return array
	 */
	public static function get_background_processor_class( $processor ) {
		static $class = false;

		if ( ! $class ) {
			$class = static::get_background_processors()[ $processor ]['class'];

			if ( ! class_exists( $class ) ) {
				throw new Exception( 'Unknown processor class!' );
			}
		}

		return $class;
	}

	/**
	 * Get an array of allowed background process params.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get_allowed_background_process_extra_param_keys() {
		return apply_filters( 'max-marine/background-processor/background-processor-extra-param-keys', array() );
	}

	/**
	 * Get an array of background process param keys.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get_all_background_process_param_keys() {
		$default_keys = array(
			'background_processes_run_id',
			'background_processes_id',
			'parent_background_processes_id',
			'as_action_id',
			'processor',
			'status',
			'complete',
			'current_position',
			'current_background_process_run',
			'prerequisite_sub_background_processors',
			'prerequisite_sub_background_processes',
			'sub_params',
			'queued_time',
			'start_time',
			'completed_time',
			'last_run_time',
			'queued_time_formatted',
			'start_time_formatted',
			'completed_time_formatted',
			'last_run_time_formatted',
			'total_time',
			'total_rows',
			'total_rows_processed',
			'total_rows_failed',
			'total_rows_skipped',
			'percent_complete',
			'background_process_results',
			'background_process_runs',
		);

		return apply_filters( 'max-marine/background-processor/background-processor-param-keys', $default_keys );
	}

	/**
	 * Get an array of background process run keys.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get_all_background_process_run_keys() {
		$default_keys = array(
			'background_processes_run_id',
			'background_processes_id',
			'as_action_id',
			'status',
			'queued_time',
			'start_time',
			'completed_time',
			'last_attempt_time',
			'attempts',
		);

		return apply_filters( 'max-marine/background-processor/background-processor-run-keys', $default_keys );
	}

	/**
	 * Get an array of background process progress keys.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get_background_process_progress_keys() {
		$default_keys = array(
			'background_processes_run_id',
			'background_processes_id',
			'parent_background_processes_id',
			'processor',
			'status',
			'complete',
			'current_position',
			'current_background_process_run',
			'prerequisite_sub_background_processors',
			'prerequisite_sub_background_processes',
			'sub_params',
			'queued_time',
			'start_time',
			'last_run_time',
			'completed_time',
			'queued_time_formatted',
			'start_time_formatted',
			'completed_time_formatted',
			'last_run_time_formatted',
			'total_time',
			'total_rows',
			'total_rows_processed',
			'total_rows_failed',
			'total_rows_skipped',
			'percent_complete',
			'background_process_results',
			'background_process_runs',
			'as_action_id',
		);

		return apply_filters( 'max-marine/background-processor/background-processor-progress-keys', $default_keys );
	}

	/**
	 * Get an array of background process transient keys.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get_background_process_transient_keys() {
		$default_keys = array(
			'background_processes_id',
			'parent_background_processes_id',
			'processor',
			'status',
			'complete',
			'current_position',
			'current_background_process_run',
			'prerequisite_sub_background_processors',
			'prerequisite_sub_background_processes',
			'sub_params',
			'queued_time',
			'start_time',
			'last_run_time',
			'completed_time',
			'queued_time_formatted',
			'start_time_formatted',
			'completed_time_formatted',
			'last_run_time_formatted',
			'total_time',
			'total_rows',
			'total_rows_processed',
			'total_rows_failed',
			'total_rows_skipped',
			'percent_complete',
			'background_process_runs',
			'as_action_id',
		);

		return apply_filters( 'max-marine/background-processor/background-processor-transient-keys', $default_keys );
	}

	/**
	 * Get an array of background process run transient keys.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get_background_process_run_transient_keys() {
		$default_keys = array(
			'processor',
			'background_processes_run_id',
			'background_processes_id',
			'parent_background_processes_id',
			'as_action_id',
			'status',
			'queued_time',
			'start_time',
			'completed_time',
			'last_attempt_time',
			'attempts',
		);

		return apply_filters( 'max-marine/background-processor/background-processor-run-transient-keys', $default_keys );
	}

	/**
	 * Get an array of processor sub params.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get_processor_sub_params() {
		return apply_filters( 'max-marine/background-processor/background-processor-sub-params', array() );
	}

	/**
	 * Get all background process statuses.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get_all_background_process_statuses() {
		return array(
			Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_PENDING,
			Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_QUEUED,
			Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_PROCESSING,
			Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_FAILED,
			Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_CANCELLED,
			Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_COMPLETE,
		);
	}

	/**
	 * Gets all saved processes.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb  WordPress database instance global.
	 * @return array
	 */
	public static function get_all_completed_background_processes() {
		global $wpdb;

		$background_processes = array();

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT
					*,
					UNIX_TIMESTAMP(datetime_queued) AS queued_time,
					UNIX_TIMESTAMP(datetime_started) AS start_time,
					UNIX_TIMESTAMP(datetime_completed) AS completed_time
				FROM
					%i
				WHERE
					status != %s
				ORDER BY
					datetime_completed DESC,
					datetime_started DESC',
				$wpdb->mmbp_background_processes,
				Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_CANCELLED
			)
		);

		foreach ( $results as $result ) {
			$result->queued_time_formatted    = max_marine_background_processor_format_timestamp_to_datetime_long( $result->queued_time );
			$result->start_time_formatted     = max_marine_background_processor_format_timestamp_to_datetime_long( $result->start_time );
			$result->completed_time_formatted = max_marine_background_processor_format_timestamp_to_datetime_long( $result->completed_time );

			$background_processes[] = $result;
		}

		return $background_processes;
	}

	/**
	 * Gets background process by ID.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb                     WordPress database instance global.
	 * @param  int   $background_processes_id  Process ID.
	 * @return false|stdClass
	 */
	public static function get_background_process_by_id( $background_processes_id ) {
		global $wpdb;

		$row = $wpdb->get_row(
			$wpdb->prepare(
				'SELECT
					*,
					UNIX_TIMESTAMP(datetime_queued) AS queued_time,
					UNIX_TIMESTAMP(datetime_started) AS start_time,
					UNIX_TIMESTAMP(datetime_completed) AS completed_time
				FROM
					%i
				WHERE
					id = %d',
				$wpdb->mmbp_background_processes,
				absint( $background_processes_id )
			)
		);

		$row->queued_time_formatted    = max_marine_background_processor_format_timestamp_to_datetime_long( $row->queued_time );
		$row->start_time_formatted     = max_marine_background_processor_format_timestamp_to_datetime_long( $row->start_time );
		$row->completed_time_formatted = max_marine_background_processor_format_timestamp_to_datetime_long( $row->completed_time );

		return $row;
	}

	/**
	 * Gets background process results by ID.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb                   WordPress database instance global.
	 * @param  int   $background_processes_id  Process ID.
	 * @param  int   $per_page               Optional. Results per page. Default -1.
	 * @param  int   $page                   Optional. Current page number. Default 0.
	 * @return false|array
	 */
	public static function get_background_process_results_by_id( $background_processes_id, $per_page = -1, $page = 0 ) {
		global $wpdb;

		$row = self::get_background_process_by_id( $background_processes_id );

		if ( ! $row ) {
			Max_Marine_Background_Processor_WC_Logger::warning(
				sprintf(
					/* translators: %d: Background process ID. */
					__( 'Invalid background process ID %d!', 'max-marine-background-processor' ),
					$background_processes_id
				)
			);

			return false;
		}

		$results = array();

		if ( -1 !== $per_page ) {
			$offset = $per_page * $page;

			$results = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM %i WHERE background_processes_id = %d ORDER BY error DESC, sku DESC LIMIT %d, %d',
					$wpdb->mmbp_background_processes_results,
					$row->id,
					$offset,
					$per_page
				)
			);

			return $results;
		}

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM %i WHERE background_processes_id = %d ORDER BY error DESC, sku DESC',
				$wpdb->mmbp_background_processes_results,
				$row->id
			)
		);

		return $results;
	}

	/**
	 * Gets background process results total errors by ID.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb                     WordPress database instance global.
	 * @param  int   $background_processes_id  Process ID.
	 * @return string|null
	 */
	public static function get_background_process_results_total_errors( $background_processes_id ) {
		global $wpdb;

		$row = self::get_background_process_by_id( $background_processes_id );

		if ( ! $row ) {
			Max_Marine_Background_Processor_WC_Logger::warning(
				sprintf(
					/* translators: %d: Background process ID. */
					__( 'Invalid background process ID %d!', 'max-marine-background-processor' ),
					$background_processes_id
				)
			);

			return null;
		}

		$total_errors = $row->total_rows_failed;

		return $total_errors ?? '0';
	}

	/**
	 * Gets background process results (Errors only) by ID.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb                     WordPress database instance global.
	 * @param  int   $background_processes_id  Process ID.
	 * @param  int   $per_page                 Optional. Results per page. Default -1.
	 * @param  int   $page                     Optional. Current page number. Default 0.
	 * @return false|stdClass
	 */
	public static function get_background_process_results_errors_by_id( $background_processes_id, $per_page = -1, $page = 0 ) {
		global $wpdb;

		$row = self::get_background_process_by_id( $background_processes_id );

		if ( ! $row ) {
			Max_Marine_Background_Processor_WC_Logger::warning(
				/* translators: %d: Background process ID. */
				sprintf(
					__( 'Invalid background process ID %d!', 'max-marine-background-processor' ),
					$background_processes_id
				)
			);

			return false;
		}

		$results = array();

		if ( -1 !== $per_page ) {
			$offset = $per_page * $page;

			$results = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM %i WHERE background_processes_id = %d AND error = %d ORDER BY LENGTH(sku) DESC, sku DESC LIMIT %d, %d',
					$wpdb->mmbp_background_processes_results,
					$row->id,
					'1',
					$offset,
					$per_page
				)
			);

			foreach ( $results as &$result ) {
				$result->result_data = maybe_unserialize( $result->result_data );
			}

			return $results;
		}

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM %i WHERE background_processes_id = %d AND error = %d ORDER BY LENGTH(sku) DESC, sku DESC',
				$wpdb->mmbp_background_processes_results,
				$row->id,
				'1'
			)
		);

		foreach ( $results as &$result ) {
			$result->result_data = maybe_unserialize( $result->result_data );
		}

		return $results;
	}

	/**
	 * Gets background process result details by ID.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb                     WordPress database instance global.
	 * @param  int   $background_processes_id  Process ID.
	 * @param  int   $result_id                Result ID.
	 * @return false|stdClass
	 */
	public static function get_background_process_result_details_by_id( $background_processes_id, $result_id ) {
		global $wpdb;

		$row = $wpdb->get_row(
			$wpdb->prepare(
				'SELECT
					*
				FROM
					%i
				WHERE
					id = %d
					AND background_processes_id = %d',
				$wpdb->mmbp_background_processes_results,
				absint( $result_id ),
				absint( $background_processes_id )
			)
		);

		$row->result_data = maybe_unserialize( $row->result_data );

		return $row;
	}

	/**
	 * Gets background process run by ActionScheduler action ID.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb          WordPress database instance global.
	 * @param  int   $as_action_id  Action scheduler action ID.
	 * @return false|object{
	 *     id: int,
	 * }
	 */
	public static function get_background_process_run_by_as_action_id( $as_action_id ) {
		global $wpdb;

		$row = $wpdb->get_row(
			$wpdb->prepare(
				'SELECT * FROM %i WHERE as_action_id = %d',
				$wpdb->mmbp_background_processes_runs,
				$as_action_id
			)
		);

		return $row;
	}

	/**
	 * Gets a background processes sub-processes.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb                          WordPress database instance global.
	 * @param  int   $parent_background_processes_id  Parent process ID.
	 * @return array
	 */
	public static function get_background_prerequisite_sub_processes_by_parent_id( $parent_background_processes_id ) {
		global $wpdb;

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM %i WHERE parent_background_processes_id = %d',
				$wpdb->mmbp_background_processes,
				absint( $parent_background_processes_id )
			)
		);

		return $results;
	}

	/**
	 * Gets a background process's incomplete prerequisite sub-processes.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb                          WordPress database instance global.
	 * @param  int   $parent_background_processes_id  Parent process ID.
	 * @return array
	 */
	public static function background_incomplete_prerequisite_sub_processes_by_parent_id( $parent_background_processes_id ) {
		global $wpdb;

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM %i WHERE parent_background_processes_id = %d AND status != %s AND status != %s',
				$wpdb->mmbp_background_processes,
				absint( $parent_background_processes_id ),
				Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_COMPLETE,
				Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_FAILED
			)
		);

		return $results;
	}

	/**
	 * Queue new background process.
	 *
	 * @since  1.0.0
	 * @param  array  $params  Params for process.
	 * @return void
	 */
	public static function queue_new_background_process( $params ) {
		Max_Marine_Background_Processor_Background_Process_Runner::queue( $params );
	}

	/**
	 * Get a background process transient.
	 *
	 * @since  1.0.0
	 * @param  false|int  $background_processes_id  Background process ID.
	 * @return false|mixed
	 */
	public static function get_background_process_transient( $background_processes_id ) {
		if ( false === $background_processes_id ) {
			return false;
		}

		return get_transient( "max-marine-background-processor-background-process-{$background_processes_id}" );
	}

	/**
	 * Get a background process run transient.
	 *
	 * @since  1.0.0
	 * @param  false|int  $background_processes_run_id  Background process run ID.
	 * @return false|mixed
	 */
	public static function get_background_process_run_transient( $background_processes_run_id ) {
		if ( false === $background_processes_run_id ) {
			return false;
		}

		return get_transient( "max-marine-background-processor-background-process-run-{$background_processes_run_id}" );
	}

	/**
	 * Update a background process transient.
	 *
	 * @since  1.0.0
	 * @param  false|int  $background_processes_id    Background process ID.
	 * @param  array      $background_process_params  Process data.
	 * @return void
	 */
	public static function update_background_process_transient( $background_processes_id, $background_process_params ) {
		if ( false === $background_processes_id ) {
			return;
		}

		$transient_data = array();

		$allowed_transient_keys = self::get_background_process_transient_keys();

		foreach ( $allowed_transient_keys as $allowed_key ) {
			if ( ! isset( $background_process_params[ $allowed_key ] ) ) {
				continue;
			}

			$transient_data[ $allowed_key ] = $background_process_params[ $allowed_key ];
		}

		set_transient( "max-marine-background-processor-background-process-{$background_processes_id}", $transient_data );

		if ( ! empty( $transient_data['parent_background_processes_id'] ) ) {
			self::update_prerequisite_sub_process_parent_background_process( $background_processes_id );
		}
	}

	/**
	 * Update a background process run transient.
	 *
	 * @since  1.0.0
	 * @param  false|int  $background_processes_run_id    Background process run ID.
	 * @param  array      $background_process_run_params  Process run data.
	 * @return void
	 */
	public static function update_background_process_run_transient( $background_processes_run_id, $background_process_run_params ) {
		if ( false === $background_processes_run_id ) {
			return;
		}

		$transient_data = array();

		foreach ( self::get_background_process_run_transient_keys() as $allowed_key ) {
			if ( ! isset( $background_process_run_params[ $allowed_key ] ) ) {
				continue;
			}

			$transient_data[ $allowed_key ] = $background_process_run_params[ $allowed_key ];
		}

		set_transient( "max-marine-background-processor-background-process-run-{$background_processes_run_id}", $transient_data );
	}

	/**
	 * Delete a background process transient.
	 *
	 * @since  1.0.0
	 * @param  int  $background_processes_id  Background process ID.
	 * @return mixed
	 */
	public static function delete_background_process_transient( $background_processes_id ) {
		return delete_transient( "max-marine-background-processor-background-process-{$background_processes_id}" );
	}

	/**
	 * Delete a background process run transient.
	 *
	 * @since  1.0.0
	 * @param  int  $background_processes_run_id  Background process run ID.
	 * @return mixed
	 */
	public static function delete_background_process_run_transient( $background_processes_run_id ) {
		return delete_transient( "max-marine-background-processor-background-process-run-{$background_processes_run_id}" );
	}

	/**
	 * Process an async process by ID.
	 *
	 * @since  1.0.0
	 * @param  int  $background_processes_run_id  Background process run ID.
	 * @return void
	 */
	public static function process_background_process( $background_processes_run_id ) {
		Max_Marine_Background_Processor_Background_Process_Runner::run( $background_processes_run_id );
	}

	/**
	 * Create an async background process.
	 *
	 * @since  1.0.0
	 * @global WPDB   $wpdb                WordPress database instance global.
	 * @param  array  $background_process  Background process data.
	 * @return false|array
	 */
	public static function create_new_background_process( $background_process ) {
		if ( ! empty( $background_process['parent_background_processes_id'] ) ) {
			return self::create_new_background_child_process( $background_process );
		}

		global $wpdb;

		$queued_time = microtime( true );

		$wpdb->insert(
			$wpdb->mmbp_background_processes,
			array(
				'processor'       => $background_process['processor'],
				'user_id'         => get_current_user_id(),
				'datetime_queued' => max_marine_background_processor_get_mysql_datetime( $queued_time ),
			),
			array(
				'%s',
				'%d',
				'%s',
			)
		);

		if ( $wpdb->last_error ) {
			max_marine_background_processor_debug( $wpdb->last_error );
			return false;
		}

		$background_process['background_processes_id'] = $wpdb->insert_id;
		$background_process['status']                  = Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_PENDING;
		$background_process['complete']                = false;
		$background_process['queued_time']             = $queued_time;
		$background_process['queued_time_formatted']   = max_marine_background_processor_format_timestamp_to_datetime_long( $queued_time );

		// Add a message.
		$message_text = sprintf(
			/* translators: 1: Conditional parent background process ID, 2: Processor label, 3: Background process ID, 4: Date. */
			__( '%1$s%2$s process ID %3$d started at %4$s.', 'max-marine-background-processor' ),
			( ! empty( $background_process['parent_background_processes_id'] ) ) ? __( 'Prerequisite ', 'max-marine-background-processor' ) : '',
			self::get_background_processor_label( $background_process['processor'] ),
			$background_process['background_processes_id'],
			wp_date( 'g:i a' )
		);

		Max_Marine_Background_Processor_Background_Process_Messages_Functions::create_background_processes_message(
			array(
				'background_processes_id' => $background_process['background_processes_id'],
				'message'                 => $message_text,
				'type'                    => 'info',
				'user_id'                 => get_current_user_id(),
			)
		);

		return $background_process;
	}

	/**
	 * Create an async background process.
	 *
	 * @since  1.0.0
	 * @global WPDB   $wpdb                WordPress database instance global.
	 * @param  array  $background_process  Background process data.
	 * @return false|array
	 */
	public static function create_new_background_child_process( $background_process ) {
		if ( empty( $background_process['parent_background_processes_id'] ) ) {
			Max_Marine_Background_Processor_WC_Logger::warning(
				__( 'No parent background process ID!', 'max-marine-background-processor' )
			);

			die;
		}

		global $wpdb;

		$queued_time = microtime( true );

		$wpdb->insert(
			$wpdb->mmbp_background_processes,
			array(
				'parent_background_processes_id' => $background_process['parent_background_processes_id'],
				'processor'                      => $background_process['processor'],
				'status'                         => Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_QUEUED,
				'datetime_queued'                => max_marine_background_processor_get_mysql_datetime( $queued_time ),
			),
			array(
				'%d',
				'%s',
				'%s',
				'%s',
			)
		);

		if ( $wpdb->last_error ) {
			return false;
		}

		$background_process['background_processes_id'] = $wpdb->insert_id;
		$background_process['status']                  = Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_QUEUED;
		$background_process['queued_time']             = $queued_time;
		$background_process['queued_time_formatted']   = max_marine_background_processor_format_timestamp_to_datetime_long( $queued_time );

		return $background_process;
	}

	/**
	 * Update a processing background process.
	 *
	 * @since  1.0.0
	 * @global WPDB   $wpdb                WordPress database instance global.
	 * @param  array  $background_process  Background process data.
	 * @return void
	 */
	public static function start_queued_background_process( $background_process ) {
		global $wpdb;

		$wpdb->update(
			$wpdb->mmbp_background_processes,
			array(
				'status'           => Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_PROCESSING,
				'datetime_started' => max_marine_background_processor_get_mysql_datetime(),
				'total_rows'       => $background_process['total_rows'],
			),
			array(
				'id' => $background_process['background_processes_id'],
			),
			array(
				'%s',
				'%s',
				'%d',
			),
			array(
				'%d',
			),
		);
	}

	/**
	 * Complete an async process by ID.
	 *
	 * @since  1.0.0
	 * @param  int   $background_processes_id  Background process ID.
	 * @return void
	 */
	public static function complete_background_process( $background_processes_id ) {
		self::update_background_process_status_by_id( $background_processes_id, Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_COMPLETE );
	}

	/**
	 * Save a failed async process by ID.
	 *
	 * @since  1.0.0
	 * @param  int   $background_processes_id  Background process ID.
	 * @return void
	 */
	public static function save_failed_background_process( $background_processes_id ) {
		self::update_background_process_status_by_id( $background_processes_id, Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_FAILED );
	}

	/**
	 * Complete an async process by ID.
	 *
	 * @since  1.0.0
	 * @global WPDB    $wpdb                   WordPress database instance global.
	 * @param  int     $background_processes_id  Background process ID.
	 * @param  string  $new_status             New process status.
	 * @return void
	 */
	public static function update_background_process_status_by_id( $background_processes_id, $new_status ) {
		if ( ! in_array( $new_status, static::get_all_background_process_statuses(), true ) ) {
			return;
		}

		global $wpdb;

		$wpdb->update(
			$wpdb->mmbp_background_processes,
			array(
				'status' => $new_status,
			),
			array(
				'id' => $background_processes_id,
			),
			array(
				'%s',
			),
			array(
				'%d',
			),
		);
	}

	/**
	 * Update a background process.
	 *
	 * @since  1.0.0
	 * @global WPDB   $wpdb                WordPress database instance global.
	 * @param  array  $background_process  Process.
	 * @return void
	 */
	public static function update_background_process( $background_process ) {
		global $wpdb;

		$run_keys = array(
			'status'                       => '%s',
			'start_time'                   => '%s',
			'completed_time'               => '%s',
			'total_processing_time'        => '%s',
			'total_rows'                   => '%d',
			'total_rows_skipped'           => '%d',
			'total_rows_processed'         => '%d',
			'total_rows_failed'            => '%d',
		);

		$updated_keys  = array();
		$updated_types = array();

		foreach ( array_keys( $run_keys ) as $run_key ) {
			if ( ! isset( $background_process[ $run_key ] ) ) {
				continue;
			}

			switch ( $run_key ) {
				case 'start_time':
					if (
						Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_PENDING === $background_process['status']
						||
						Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_QUEUED === $background_process['status']
					) {
						break;
					}
					$updated_keys['datetime_started'] = max_marine_background_processor_get_mysql_datetime( $background_process[ $run_key ] );
					$updated_types[] = '%s';
					break;

				case 'completed_time':
					if ( Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_COMPLETE !== $background_process['status'] ) {
						break;
					}
					$updated_keys['datetime_completed'] = max_marine_background_processor_get_mysql_datetime( $background_process[ $run_key ] );
					$updated_types[] = '%s';
					break;

				default:
					$updated_keys[ $run_key ] = $background_process[ $run_key ];
					$updated_types[]          = $run_keys[ $run_key ];
					break;
			}
		}

		if ( ! empty( $background_process['parent_background_processes_id'] ) ) {
			$updated_keys['parent_background_processes_id'] = $background_process['parent_background_processes_id'];
			$updated_types[]                                = '%d';
		}

		if ( empty( $updated_keys ) ) {
			return;
		}

		$wpdb->update(
			$wpdb->mmbp_background_processes,
			$updated_keys,
			array(
				'id' => $background_process['background_processes_id'],
			),
			$updated_types,
			array(
				'%d',
			)
		);
	}

	/**
	 * Delete a background process by ID.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb                   WordPress database instance global.
	 * @param  int   $background_processes_id  Background process ID.
	 * @return void
	 */
	public static function delete_background_process( $background_processes_id ) {
		global $wpdb;

		$wpdb->delete(
			$wpdb->mmbp_background_processes,
			array(
				'id' => $background_processes_id,
			),
			array(
				'%d',
			)
		);

		// Delete the transient.
		self::delete_background_process_transient( $background_processes_id );
	}

	/**
	 * Cancel all processing.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb  WordPress database instance global.
	 * @return void
	 */
	public static function cancel_all_background_processes() {
		as_unschedule_all_actions( 'max-marine/background-processor/process-background-process' );

		global $wpdb;

		// Update background processes statuses.
		$wpdb->query(
			$wpdb->prepare(
				'UPDATE %i SET status = %s, datetime_cancelled = %s WHERE status = %s OR status = %s OR status = %s',
				$wpdb->mmbp_background_processes,
				Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_CANCELLED,
				max_marine_background_processor_get_mysql_datetime(),
				Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_PENDING,
				Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_QUEUED,
				Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_PROCESSING,
			)
		);

		// Update background processes runs statuses.
		$wpdb->query(
			$wpdb->prepare(
				'UPDATE %i SET status = %s WHERE status = %s OR status = %s',
				$wpdb->mmbp_background_processes_runs,
				Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_CANCELLED,
				Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_QUEUED,
				Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_PROCESSING,
			)
		);
	}

	/**
	 * Cancel a specific background process.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb                     WordPress database instance global.
	 * @param  int   $background_processes_id  Background process ID.
	 * @return void
	 */
	public static function cancel_background_process( $background_processes_id ) {
		as_unschedule_all_actions( 'max-marine/background-processor/process-background-process' );

		global $wpdb;

		// Background process.
		$background_process = self::get_background_process_by_id( $background_processes_id );

		if ( ! $background_process ) {
			return;
		}

		// Check sub processes.
		$sub_background_processes = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM %i WHERE parent_background_processes_id = %d',
				$wpdb->mmbp_background_processes,
				$background_process->id
			)
		);

		if ( ! empty( $sub_background_processes ) ) {
			foreach ( $sub_background_processes as $sub_background_process ) {
				self::cancel_background_process( $sub_background_process->id );
			}
		}

		// Get all pending runs.
		$non_completed_runs = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM %i WHERE status != %s AND status != %s',
				$wpdb->mmbp_background_processes,
				Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_FAILED,
				Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_COMPLETE
			)
		);

		if ( $non_completed_runs ) {
			foreach ( $non_completed_runs as $run ) {
				// Unschedule the AS action.
				as_unschedule_action( 'max-marine/background-processor/process-background-process', array( $run->id ), 'max-marine-background-processor' );

				self::update_background_process_run(
					array(
						'background_processes_run_id' => $run->id,
						'status'                      => Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_CANCELLED,
					)
				);

				self::delete_background_process_run_transient( $run->id );
			}
		}

		// Root process.
		self::update_background_process(
			array(
				'background_processes_id' => $background_process->id,
				'status'                  => Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_CANCELLED,
				'datetime_cancelled'      => max_marine_background_processor_get_mysql_datetime(),
			)
		);

		self::delete_background_process_transient( $background_process->id );

		// Add message.
		$message_text = sprintf(
			/* translators: 1: Processor label, 2: Process ID. */
			__( 'Canceled %1$s process ID %2$d.', 'max-marine-background-processor' ),
			self::get_background_processor_label( $background_process->processor ),
			$background_process->id
		);

		Max_Marine_Background_Processor_Background_Process_Messages_Functions::create_background_processes_message(
			array(
				'background_processes_id' => $background_process->id,
				'message'                 => $message_text,
				'type'                    => 'success',
				'user_id'                 => $background_process->user_id,
			)
		);
	}

	/**
	 * Retry a failed background process.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb                   WordPress database instance global.
	 * @param  int   $background_processes_id  Background process ID.
	 * @return void
	 */
	public static function retry_failed_background_process( $background_processes_id ) {
		Max_Marine_Background_Processor_Background_Process_Runner::retry_failed( $background_processes_id );
	}

	/**
	 * Save a completed background process.
	 *
	 * @since  1.0.0
	 * @global WPDB   $wpdb                WordPress database instance global.
	 * @param  array  $background_process  Process to complete.
	 * @return void
	 */
	public static function save_completed_background_process( $background_process ) {
		global $wpdb;

		$total_time = microtime( true ) - $background_process['start_time'];

		$wpdb->update(
			$wpdb->mmbp_background_processes,
			array(
				'status'                => Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_COMPLETE,
				'datetime_completed'    => max_marine_background_processor_get_mysql_datetime(),
				'total_processing_time' => $total_time,
				'total_rows_processed'  => $background_process['total_rows_processed'],
			),
			array(
				'id' => $background_process['background_processes_id'],
			),
			array(
				'%s',
				'%s',
				'%d',
				'%d',
			),
			array(
				'%d',
			)
		);

		// Delete the process transient.
		self::delete_background_process_transient( $background_process['background_processes_id'] );

		// Add a message.
		$message_text = sprintf(
			/* translators: 1: Conditional parent background process ID, 2: Processor label, 3: Background process ID, 4: Date. */
			__( '%1$s%2$s process ID %3$d completed at %4$s.', 'max-marine-background-processor' ),
			( ! empty( $background_process['parent_background_processes_id'] ) ) ? __( 'Prerequisite ', 'max-marine-background-processor' ) : '',
			self::get_background_processor_label( $background_process['processor'] ),
			$background_process['background_processes_id'],
			wp_date( 'g:i a' )
		);

		// Get background process data.
		$background_process = self::get_background_process_by_id( $background_process['background_processes_id'] );

		if ( ! $background_process ) {
			return;
		}

		Max_Marine_Background_Processor_Background_Process_Messages_Functions::create_background_processes_message(
			array(
				'background_processes_id' => $background_process->id,
				'message'                 => $message_text,
				'type'                    => 'success',
				'user_id'                 => $background_process->user_id,
			)
		);
	}

	/**
	 * Update a prerequisite sub process's parent process with new data.
	 *
	 * @since  1.0.0
	 * @param  int  $prerequisite_sub_background_process_id  Prerequisite sub process data.
	 * @return void
	 */
	public static function update_prerequisite_sub_process_parent_background_process( $prerequisite_sub_background_process_id ) {
		$prerequisite_sub_background_process_transient = self::get_background_process_transient( $prerequisite_sub_background_process_id );

		if ( ! $prerequisite_sub_background_process_transient ) {
			max_marine_background_processor_debug(
				sprintf(
					'`update_prerequisite_sub_process_parent_background_process`: No process transient for ID %d.',
					$prerequisite_sub_background_process_id
				)
			);

			return;
		}

		$parent_background_process = self::get_background_process_transient( $prerequisite_sub_background_process_transient['parent_background_processes_id'] );

		if ( ! $parent_background_process ) {
			max_marine_background_processor_debug(
				sprintf(
					'`update_prerequisite_sub_process_parent_background_process`: No parent process transient for ID %d.',
					$prerequisite_sub_background_process_id
				)
			);

			return;
		}

		$new_prerequisite_sub_background_processes = array();

		foreach ( $parent_background_process['prerequisite_sub_background_processes'] as $prerequisite_sub_background_process ) {
			if ( ! empty( $prerequisite_sub_background_process['background_processes_id'] ) && $prerequisite_sub_background_process['background_processes_id'] === $prerequisite_sub_background_process_id ) {
				$new_prerequisite_sub_background_processes[] = $prerequisite_sub_background_process_transient;
			} else {
				$new_prerequisite_sub_background_processes[] = $prerequisite_sub_background_process;
			}
		}

		$parent_background_process['prerequisite_sub_background_processes'] = $new_prerequisite_sub_background_processes;

		self::update_background_process_transient( $prerequisite_sub_background_process_transient['parent_background_processes_id'], $parent_background_process );
	}

	/**
	 * Create a new background process run.
	 *
	 * @since  1.0.0
	 * @global WPDB   $wpdb                    WordPress database instance global.
	 * @param  array  $background_process_run  Process run.
	 * @return false|int
	 */
	public static function create_new_background_process_run( $background_process_run ) {
		global $wpdb;

		$wpdb->insert(
			$wpdb->mmbp_background_processes_runs,
			array(
				'background_processes_id' => $background_process_run['background_processes_id'],
				'status'                => Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_QUEUED,
				'datetime_queued'       => max_marine_background_processor_get_mysql_datetime( $background_process_run['queued_time'] ?? false ),
			),
			array(
				'%d',
				'%s',
				'%s',
			)
		);

		if ( $wpdb->last_error ) {
			max_marine_background_processor_debug( $wpdb->last_error );
			return false;
		}

		return $wpdb->insert_id;
	}

	/**
	 * Save a completed background process run.
	 *
	 * @since  1.0.0
	 * @param  array  $background_process  Process.
	 * @return void
	 */
	public static function save_completed_background_process_run( $background_process ) {
		self::update_background_process_run( $background_process );

		// Delete the run transient.
		self::delete_background_process_run_transient( $background_process['background_processes_run_id'] );
	}

	/**
	 * Save a failed process run.
	 *
	 * @since  1.0.0
	 * @param  array  $background_process_run  Process run.
	 * @return void
	 */
	public static function save_failed_background_process_run( $background_process_run ) {
		$background_process_run['status'] = Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_FAILED;

		self::update_background_process_run( $background_process_run );

		// Update the run transient.
		self::update_background_process_run_transient( $background_process_run['background_processes_run_id'], $background_process_run );

		// Main process.
		$background_process_transient = self::get_background_process_transient( $background_process_run['background_processes_id'] );

		$background_process_transient['status'] = Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_FAILED;

		self::update_background_process_transient( $background_process_run['background_processes_id'], $background_process_transient );

		self::save_failed_background_process( $background_process_run['background_processes_id'] );
	}

	/**
	 * Update a background process run.
	 *
	 * @since  1.0.0
	 * @global WPDB   $wpdb                WordPress database instance global.
	 * @param  array  $background_process  Process run.
	 * @return void
	 */
	public static function update_background_process_run( $background_process ) {
		global $wpdb;

		$run_keys = array(
			'as_action_id'      => '%d',
			'status'            => '%s',
			'start_time'        => '%s',
			'completed_time'    => '%s',
			'last_attempt_time' => '%s',
			'attempts'          => '%d',
		);

		$updated_keys  = array();
		$updated_types = array();

		foreach ( array_keys( $run_keys ) as $run_key ) {
			if ( ! isset( $background_process[ $run_key ] ) ) {
				continue;
			}

			switch ( $run_key ) {
				case 'start_time':
					if (
						Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_PENDING === $background_process['status']
						||
						Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_QUEUED === $background_process['status']
					) {
						break;
					}
					$updated_keys['datetime_started'] = max_marine_background_processor_get_mysql_datetime( $background_process[ $run_key ] );
					$updated_types[] = '%s';
					break;
				case 'completed_time':
					if ( Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_COMPLETE !== $background_process['status'] ) {
						break;
					}
					$updated_keys['datetime_completed'] = max_marine_background_processor_get_mysql_datetime( $background_process[ $run_key ] );
					$updated_types[] = '%s';
					break;
				case 'last_attempt_time':
					$updated_keys['datetime_last_attempt'] = max_marine_background_processor_get_mysql_datetime( $background_process[ $run_key ] );
					$updated_types[] = '%s';
					break;

				default:
					$updated_keys[ $run_key ] = $background_process[ $run_key ];
					$updated_types[]          = $run_keys[ $run_key ];
					break;
			}
		}

		if ( empty( $updated_keys ) ) {
			return;
		}

		$wpdb->update(
			$wpdb->mmbp_background_processes_runs,
			$updated_keys,
			array(
				'id' => $background_process['background_processes_run_id'],
			),
			$updated_types,
			array(
				'%d',
			)
		);
	}

	/**
	 * Delete a background process run by ID.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb                       WordPress database instance global.
	 * @param  int   $background_processes_run_id  Background process run ID.
	 * @return void
	 */
	public static function delete_background_process_run( $background_processes_run_id ) {
		global $wpdb;

		$wpdb->delete(
			$wpdb->mmbp_background_processes_runs,
			array(
				'id' => $background_processes_run_id,
			),
			array(
				'%d',
			)
		);

		// Delete the run transient.
		self::delete_background_process_run_transient( $background_processes_run_id );
	}

	/**
	 * Save process run results.
	 *
	 * @since  1.0.0
	 * @global WPDB   $wpdb                WordPress database instance global.
	 * @param  array  $background_process  Process.
	 * @return void
	 */
	public static function save_process_run_results( $background_process ) {
		global $wpdb;

		$background_processes_id = $background_process['background_processes_id'];
		$processor               = $background_process['processor'];

		do_action( 'max-marine/background-processor/save-process-run-results', $background_process );

		unset( $background_process['background_process_results'] );
	}

	/**
	 * Get all running background processes.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb  WordPress database instance global.
	 * @return array
	 */
	public static function get_all_running_background_processes() {
		global $wpdb;

		// Currently running.
		$running = array();

		$parent_background_processes = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM %i WHERE parent_background_processes_id IS NULL AND ( status = %s OR status = %s OR status = %s )',
				$wpdb->mmbp_background_processes,
				Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_PENDING,
				Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_QUEUED,
				Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_PROCESSING
			)
		);

		foreach ( $parent_background_processes as $parent_background_process ) {
			if ( false !== $transient = self::get_background_process_transient( $parent_background_process->id ) ) {
				$running[] = $transient;
			}
		}

		return $running;
	}

	/**
	 * Add a "recently completed" process to the transient.
	 *
	 * @since  1.0.0
	 * @param  array  $completed_background_process  Completed process.
	 * @return void
	 */
	public static function add_completed_background_process_transient( $completed_background_process ) {
		set_transient( 'max-marine-background-processor-completed-background-process', $completed_background_process, ( time() + ( DAY_IN_SECONDS * 30 ) ) );
	}

	/**
	 * Get the "recently completed" process transient.
	 *
	 * @since  1.0.0
	 * @return mixed
	 */
	public static function get_completed_background_process_transient() {
		return get_transient( 'max-marine-background-processor-completed-background-process' );
	}

	/**
	 * Delete the "recently completed" process transient.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function delete_completed_background_process_transient() {
		delete_transient( 'max-marine-background-processor-completed-background-process' );
	}

	/**
	 * Save background process run results.
	 *
	 * @since  1.0.0
	 * @global WPDB   $wpdb                    WordPress database instance global.
	 * @param  array  $background_process_run  Background process run.
	 * @return void
	 */
	public static function save_background_process_run_results( $background_process_run ) {
		do_action( 'max-marine/background-processor/background-process-run-results', $background_process_run );
	}
}
