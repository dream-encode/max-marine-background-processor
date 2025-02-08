<?php
/**
 * Class to trigger background processes.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Max_Marine\Background_Processor
 * @subpackage Max_Marine\Background_Processor/includes/background-process
 */

namespace Max_Marine\Background_Processor\Core\Background_Process;

use Exception;

use Max_Marine\Background_Processor\Max_Marine_Abstract_Background_Processor;
use Max_Marine\Background_Processor\Core\Log\Max_Marine_Background_Processor_WC_Logger;
use Max_Marine\Background_Processor\Core\Background_Process\Max_Marine_Background_Processor_Background_Process_Functions;
use Max_Marine\Background_Processor\Core\Background_Process\Max_Marine_Background_Processor_Background_Process_Messages_Functions;
use Max_Marine\Background_Processor\Core\Background_Process\Max_Marine_Background_Processor_Base_Processor;

/**
 * Class to trigger background processes.
 *
 * @since      1.0.0
 * @package    Max_Marine\Background_Processor
 * @subpackage Max_Marine\Background_Processor/includes/background-process
 * @author     David Baumwald <david@dream-encode.com>
 */
class Max_Marine_Background_Processor_Background_Process_Runner {

	/**
	 * Max retry attempts.
	 *
	 * @var int
	 */
	const PROCESSOR_RUN_MAX_ATTEMPTS = 3;

	/**
	 * Queue processor.
	 *
	 * @since  1.0.0
	 * @param  array  $params  Arguments for the processor.
	 * @return void
	 */
	public static function queue( $params ) {
		$process_name = $params['processor'];

		$processor_class = Max_Marine_Background_Processor_Background_Process_Functions::get_background_processor_class( $process_name );

		/**
		 * For PHPStan hints.
		 *
		 * @var Max_Marine_Background_Processor_Base_Processor $background_process
		 */
		$background_process = new $processor_class();

		$background_process->queue_background_processor( $params );
	}

	/**
	 * Run processor.
	 *
	 * @since  1.0.0
	 * @param  int  $background_processes_run_id  Background process run ID.
	 * @return void
	 */
	public static function run( $background_processes_run_id ) {
		$current_background_process_run = Max_Marine_Background_Processor_Background_Process_Functions::get_background_process_run_transient( $background_processes_run_id );

		if ( ! $current_background_process_run || ! $current_background_process_run['processor'] ) {
			max_marine_background_processor_debug( 'No run transient' );
			return;
		}

		$processor_class = Max_Marine_Background_Processor_Background_Process_Functions::get_background_processor_class( $current_background_process_run['processor'] );

		$background_process = new $processor_class();

		/**
		 * For PHPStan hints.
		 *
		 * @var Max_Marine_Background_Processor_Base_Processor $background_process
		 */
		$background_process->run( $background_processes_run_id );
	}

	/**
	 * Handle run failure.
	 *
	 * @since  1.0.0
	 * @param  int              $action_id  Action ID.
	 * @param  false|Exception  $exception  Optional. Exception instance. Default false.
	 * @return void
	 */
	public static function handle_run_failure( $action_id, $exception = false ) {
		$background_process_run = Max_Marine_Background_Processor_Background_Process_Functions::get_background_process_run_by_as_action_id( $action_id );

		if ( empty( $background_process_run ) ) {
			return;
		}

		$background_processes_run_id = absint( $background_process_run->id ); // @phpstan-ignore-line

		$run_transient = Max_Marine_Background_Processor_Background_Process_Functions::get_background_process_run_transient( $background_processes_run_id );

		if ( ! $run_transient || ! $run_transient['processor'] ) {
			max_marine_background_processor_debug( 'No run transient' );

			Max_Marine_Background_Processor_WC_Logger::error(
				sprintf(
					/* translators: %d: Background process run ID. */
					__( 'Unable to get run transient for background process run ID %d.', 'max-marine-background-processor' ),
					$background_processes_run_id
				)
			);

			return;
		}

		// Check attempts.
		if ( ! empty( $run_transient['attempts'] ) && $run_transient['attempts'] >= static::PROCESSOR_RUN_MAX_ATTEMPTS ) {
			$message_text = sprintf(
				/* translators: 1: Processor label, 2: Run ID, 3: Number of attempts. */
				__( '%1$s run ID %2$d failed after %3$d attempts.', 'max-marine-background-processor' ),
				Max_Marine_Background_Processor_Background_Process_Functions::get_background_processor_label( $run_transient['processor'] ),
				$background_processes_run_id,
				$run_transient['attempts']
			);

			if ( $exception && $exception instanceof Exception ) {
				$message_text .= sprintf(
					/* translators: %s: Exception reason. */
					__( '  Reason: %s', 'max-marine-background-processor' ),
					$exception->getMessage()
				);
			}

			Max_Marine_Background_Processor_Background_Process_Functions::save_failed_background_process_run( $run_transient );

			Max_Marine_Background_Processor_Background_Process_Messages_Functions::create_background_processes_message(
				array(
					'background_processes_id'     => $run_transient['background_processes_id'],
					'background_processes_run_id' => $background_processes_run_id,
					'message'                     => $message_text,
					'type'                        => 'error',
				)
			);

			Max_Marine_Background_Processor_WC_Logger::error(
				sprintf(
					/* translators: 1: Background process run ID, 2: Number of attempts. */
					__( 'Run ID %1$d failed after %2$d attempts.  Exception follows:', 'max-marine-background-processor' ),
					$background_processes_run_id,
					$run_transient['attempts']
				)
			);

			if ( $exception && $exception instanceof Exception ) {
				Max_Marine_Background_Processor_WC_Logger::error( $exception );
			}

			return;
		}

		$run_transient['attempts'] = $run_transient['attempts'] + 1;

		$processor_class = Max_Marine_Background_Processor_Background_Process_Functions::get_background_processor_class( $run_transient['processor'] );

		/**
		 * For PHPStan hints.
		 *
		 * @var Max_Marine_Background_Processor_Base_Processor $background_process
		 */
		$background_process = new $processor_class();

		// Add some exponential backoff.
		$delay = 5 * pow( $run_transient['attempts'], 2 );

		$new_as_action_id = $background_process->schedule_background_process_run( $background_processes_run_id, $delay );

		if ( ! $new_as_action_id ) {
			$message_text = sprintf(
				/* translators: 1: Processor label, 2: Run ID, 3: Number of attempts. */
				__( 'Unable to schedule a retry for failed run ID %d.', 'max-marine-background-processor' ),
				$background_processes_run_id
			);

			Max_Marine_Background_Processor_Background_Process_Functions::save_failed_background_process_run( $run_transient );

			Max_Marine_Background_Processor_Background_Process_Messages_Functions::create_background_processes_message(
				array(
					'background_processes_id'     => $run_transient['background_processes_id'],
					'background_processes_run_id' => $background_processes_run_id,
					'message'                     => $message_text,
					'type'                        => 'error',
				)
			);

			Max_Marine_Background_Processor_WC_Logger::error(
				sprintf(
					/* translators: %d: Background process run ID. */
					__( 'Retry could not be scheduled for run ID %d.', 'max-marine-background-processor' ),
					$background_processes_run_id
				)
			);

			return;
		}

		$run_transient['as_action_id'] = $new_as_action_id;

		Max_Marine_Background_Processor_Background_Process_Functions::update_background_process_run( $run_transient );

		Max_Marine_Background_Processor_Background_Process_Functions::update_background_process_run_transient( $run_transient['background_processes_run_id'], $run_transient );

		// Message.
		$message_text = sprintf(
			/* translators: 1: Processor label, 2: Run ID. */
			__( '%1$s run ID %2$d failed.  Scheduled a retry in %3$d seconds.', 'max-marine-background-processor' ),
			Max_Marine_Background_Processor_Background_Process_Functions::get_background_processor_label( $run_transient['processor'] ),
			$background_processes_run_id,
			$delay
		);

		Max_Marine_Background_Processor_Background_Process_Messages_Functions::create_background_processes_message(
			array(
				'background_processes_id'     => $run_transient['background_processes_id'],
				'background_processes_run_id' => $background_processes_run_id,
				'message'                     => $message_text,
				'type'                        => 'warning',
			)
		);

		Max_Marine_Background_Processor_WC_Logger::error(
			sprintf(
				/* translators: %d: Background process run ID. */
				__( 'Run ID %d failed.  Exception follows:', 'max-marine-background-processor' ),
				$background_processes_run_id
			)
		);

		if ( $exception && $exception instanceof Exception ) {
			Max_Marine_Background_Processor_WC_Logger::error( $exception );
		}
	}

	/**
	 * Retry failed processor.
	 *
	 * @since  1.0.0
	 * @param  int  $background_process_id  Process to retry.
	 * @return void
	 */
	public static function retry_failed( $background_process_id ) {
		$background_process = Max_Marine_Background_Processor_Background_Process_Functions::get_background_process_transient( $background_process_id );

		if ( empty( $background_process ) ) {
			max_marine_background_processor_debug( 'Error getting background process transient ID: ' . $background_process_id );

			return;
		}

		Max_Marine_Background_Processor_Background_Process_Functions::update_background_process_status_by_id( $background_process_id, Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_QUEUED );

		// Run.
		$background_processes_run_id = Max_Marine_Background_Processor_Background_Process_Functions::create_new_background_process_run( $background_process );

		if ( empty( $background_processes_run_id ) ) {
			max_marine_background_processor_debug( 'Problem creating new background process.' );

			return;
		}

		$action_id = as_enqueue_async_action( 'max-marine/background-processor/process-background-process', array( $background_processes_run_id ), 'max-marine-background-processor' );

		$background_process['background_processes_run_id'] = $background_processes_run_id;
		$background_process['as_action_id']                = $action_id;
		$background_process['attempts']                    = 0;
		$background_process['status']                      = Max_Marine_Background_Processor_Base_Processor::PROCESS_STATUS_QUEUED;

		// Update the run with the action id.
		Max_Marine_Background_Processor_Background_Process_Functions::update_background_process_run( $background_process );

		Max_Marine_Background_Processor_Background_Process_Functions::update_background_process_run_transient( $background_processes_run_id, $background_process );

		// Main process.
		Max_Marine_Background_Processor_Background_Process_Functions::update_background_process_transient( $background_process_id, $background_process );

		// Message.
		$message_text = sprintf(
			/* translators: 1: Processor label, 2: Process ID. */
			__( 'Retrying failed %1$s process ID %2$d.', 'max-marine-background-processor' ),
			Max_Marine_Background_Processor_Background_Process_Functions::get_background_processor_label( $background_process['processor'] ),
			$background_process['background_processes_id']
		);

		Max_Marine_Background_Processor_Background_Process_Messages_Functions::create_background_processes_message(
			array(
				'background_processes_id'   => $background_process['background_processes_id'],
				'message'                   => $message_text,
				'type'                      => 'info',
			)
		);
	}
}
