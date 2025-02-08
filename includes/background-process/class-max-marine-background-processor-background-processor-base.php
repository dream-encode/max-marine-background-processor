<?php
/**
 * Base background processor class.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Max_Marine\Background_Processor
 * @subpackage Max_Marine\Background_Processor
 */

namespace Max_Marine\Background_Processor\Core\Background_Process;

use Max_Marine\Background_Processor\Core\Background_Process\Max_Marine_Background_Processor_Background_Process_Functions;
use Max_Marine\Background_Processor\Core\Background_Process\Max_Marine_Background_Processor_Background_Process_Messages_Functions;

/**
 * Base processor class.
 *
 * @since      1.0.0
 * @package    Max_Marine\Background_Processor
 * @subpackage Max_Marine\Background_Processor
 * @author     David Baumwald <david@dream-encode.com>
 */
class Max_Marine_Background_Processor_Base_Processor {

	/**
	 * Processor.
	 *
	 * @var string
	 */
	public $processor;

	/**
	 * Params.
	 *
	 * @var array
	 */
	protected $params = array();

	/**
	 * Sub params.
	 *
	 * @var array
	 */
	protected $sub_params = array();

	/**
	 * Prerequisite sub processors.
	 *
	 * @var array
	 */
	public $prerequisite_sub_background_processors = array();

	/**
	 * Prerequisite sub processes.
	 *
	 * @var array
	 */
	public $prerequisite_sub_background_processes = array();

	/**
	 * Prerequisite sub process results.
	 *
	 * @var array
	 */
	public $prerequisite_sub_background_process_results = array();

	/**
	 * Current time.
	 *
	 * @var float|null
	 */
	public $current_time;

	/**
	 * Current process run.
	 *
	 * @var array
	 */
	public $current_background_process_run = array();

	/**
	 * Process runs.
	 *
	 * @var array
	 */
	protected $background_process_runs = array();

	/**
	 * Status.
	 *
	 * @var string
	 */
	public $status = self::PROCESS_STATUS_QUEUED;

	/**
	 * Errors Array
	 * Holds errors
	 *
	 * @var array
	 */
	private $errors = array();

	/**
	 * Data.
	 *
	 * @var array
	 */
	public $data = array();

	/**
	 * Queued time of current process.
	 *
	 * @var null|float
	 */
	protected $queued_time = null;

	/**
	 * Start time of current process.
	 *
	 * @var null|float
	 */
	protected $start_time = null;

	/**
	 * Time of last run.
	 *
	 * @var null|float
	 */
	protected $last_run_time = null;

	/**
	 * Completed time of current process.
	 *
	 * @var null|float
	 */
	protected $completed_time = null;

	/**
	 * Formatted queued time of current process.
	 *
	 * @var false|string
	 */
	protected $queued_time_formatted = false;

	/**
	 * Formatted start time of current process.
	 *
	 * @var false|string
	 */
	protected $start_time_formatted = false;

	/**
	 * Formatted time of last run.
	 *
	 * @var false|string
	 */
	protected $last_run_time_formatted = false;

	/**
	 * Formatted completed time of current process.
	 *
	 * @var false|string
	 */
	protected $completed_time_formatted = false;

	/**
	 * Total time of current process.
	 *
	 * @var float
	 */
	protected $total_time = 0;

	/**
	 * Current position.
	 *
	 * @var int
	 */
	public $current_position = 0;

	/**
	 * Percentage complete.
	 *
	 * @var float
	 */
	public $percent_complete = 0;

	/**
	 * Complete.
	 *
	 * @var bool
	 */
	public $complete = false;

	/**
	 * Total rows.
	 *
	 * @var int
	 */
	public $total_rows = 0;

	/**
	 * Total rows limit.
	 *
	 * @var int
	 */
	public $total_rows_limit = -1;

	/**
	 * Total rows failed.
	 *
	 * @var int
	 */
	public $total_rows_failed = 0;

	/**
	 * Total rows skipped.
	 *
	 * @var int
	 */
	public $total_rows_skipped = 0;

	/**
	 * Total rows processed.
	 *
	 * @var int
	 */
	public $total_rows_processed = 0;

	/**
	 * Batch size.
	 *
	 * @var int
	 */
	protected $batch_size = 100;

	/**
	 * Prevent timeouts.
	 *
	 * @var bool
	 */
	protected $prevent_timeouts = false;

	/**
	 * Results of this run.
	 *
	 * @var array
	 */
	public $background_process_results = array();

	/**
	 * This background process ID.
	 *
	 * @var false|int
	 */
	public $background_processes_id = false;

	/**
	 * This background process parent's ID.
	 *
	 * @var false|int
	 */
	public $parent_background_processes_id = false;

	/**
	 * This background process run ID.
	 *
	 * @var false|int
	 */
	public $background_processes_run_id = false;

	/**
	 * This process's action scheduler action ID.
	 *
	 * @var false|int
	 */
	public $as_action_id = false;

	/**
	 * Queued prerequisite sub processes.
	 *
	 * @var array
	 */
	public $queued_prerequisite_sub_background_processors = array();

	/**
	 * Unqueued prerequisite sub processes.
	 *
	 * @var array
	 */
	public $unqueued_prerequisite_sub_background_processors = array();

	/**
	 * Incomplete prerequisite sub processes.
	 *
	 * @var array
	 */
	public $incomplete_prerequisite_sub_background_processors = array();

	/**
	 * Process time limit.
	 *
	 * @var int
	 */
	const PROCESS_TIME_LIMIT = 20;

	/**
	 * Process memory limit.
	 *
	 * @var string
	 */
	const PROCESS_MEMORY_LIMIT = '128M';

	/**
	 * Pending status.
	 *
	 * @var string
	 */
	const PROCESS_STATUS_PENDING = 'pending';

	/**
	 * Queued status.
	 *
	 * @var string
	 */
	const PROCESS_STATUS_QUEUED = 'queued';

	/**
	 * Processing status.
	 *
	 * @var string
	 */
	const PROCESS_STATUS_PROCESSING = 'processing';

	/**
	 * Failed status.
	 *
	 * @var string
	 */
	const PROCESS_STATUS_FAILED = 'failed';

	/**
	 * Cancelled status.
	 *
	 * @var string
	 */
	const PROCESS_STATUS_CANCELLED = 'cancelled';

	/**
	 * Complete status.
	 *
	 * @var string
	 */
	const PROCESS_STATUS_COMPLETE = 'complete';

	/**
	 * Initialize processor.
	 */
	public function __construct() {
		$this->current_time = microtime( true );
	}

	/**
	 * Init.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function init() {
		$this->parse_params();

		$this->parse_sub_params();

		$this->set_incomplete_prerequisite_sub_background_processors();
	}

	/**
	 * Queue new processor.
	 *
	 * @since  1.0.0
	 * @param  array  $init_params  Initial processor params.
	 * @return void
	 */
	public function queue_background_processor( $init_params = array() ) {
		$this->prerequisite_sub_background_processes = $this->get_initial_prerequisite_sub_background_processes();

		$queued_background_process = wp_parse_args( $init_params, $this->get_progress() );

		$new_background_process = Max_Marine_Background_Processor_Background_Process_Functions::create_new_background_process( $queued_background_process );

		if ( ! $new_background_process ) {
			max_marine_background_processor_debug( "No new_background_process for processor {$this->get_processor()}:" );
			return;
		}

		$new_background_process = $this->create_background_process_run( $new_background_process );

		if ( false === $new_background_process || ! $new_background_process['background_processes_id'] ) {
			return;
		}

		Max_Marine_Background_Processor_Background_Process_Functions::update_background_process_transient( $new_background_process['background_processes_id'], $new_background_process );
	}

	/**
	 * Queue prerequisite sub processor.
	 *
	 * @since  1.0.0
	 * @param  array  $init_params  Initial sub processor params.
	 * @return false|array
	 */
	public function queue_prerequisite_sub_background_processor( $init_params ) {
		$new_background_process = Max_Marine_Background_Processor_Background_Process_Functions::create_new_background_process( $init_params );

		if ( ! $new_background_process ) {
			return false;
		}

		$new_prerequisite_sub_background_process = $this->create_background_process_run( $new_background_process );

		if ( false === $new_prerequisite_sub_background_process || ! $new_prerequisite_sub_background_process['parent_background_processes_id'] ) {
			return false;
		}

		$parent_background_process = Max_Marine_Background_Processor_Background_Process_Functions::get_background_process_transient( $new_prerequisite_sub_background_process['parent_background_processes_id'] );

		if ( ! $parent_background_process ) {
			return false;
		}

		$parent_process['prerequisite_sub_background_processes'] = $this->get_prerequisite_sub_background_processes();

		Max_Marine_Background_Processor_Background_Process_Functions::update_background_process_transient( $new_prerequisite_sub_background_process['background_processes_id'], $new_prerequisite_sub_background_process );

		// Parent process.
		Max_Marine_Background_Processor_Background_Process_Functions::update_background_process_transient( $parent_background_process['background_processes_id'], $parent_background_process );

		return $new_prerequisite_sub_background_process;
	}

	/**
	 * Parse params.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function parse_params() {
		$current_background_process_run = Max_Marine_Background_Processor_Background_Process_Functions::get_background_process_run_transient( $this->get_background_processes_run_id() );

		if ( ! $current_background_process_run ) {
			max_marine_background_processor_debug( '! $current_process_run' );
			return;
		}

		if ( empty( $current_background_process_run['background_processes_id'] ) ) {
			max_marine_background_processor_debug( 'empty process ID' );
			return;
		}

		$background_processes_id = $current_background_process_run['background_processes_id'];

		$background_process = Max_Marine_Background_Processor_Background_Process_Functions::get_background_process_transient( $background_processes_id );

		foreach ( Max_Marine_Background_Processor_Background_Process_Functions::get_all_background_process_param_keys() as $key ) {
			if ( isset( $background_process[ $key ] ) ) {
				$this->{$key} = $background_process[ $key ];
			} else {
				if ( 'prerequisite_sub_background_processes' === $key ) {
					$this->{$key} = $this->get_initial_prerequisite_sub_background_processes();
				}
			}
		}
	}

	/**
	 * Get sub processor parameters.
	 *
	 * @since  1.0.0
	 * @param  array  $args  Arguments.
	 * @return void
	 */
	public function parse_sub_params( $args = array() ) {
		$processors_sub_params = Max_Marine_Background_Processor_Background_Process_Functions::get_processor_sub_params();

		if ( ! isset( $processors_sub_params[ $this->get_processor() ] ) ) {
			return;
		}

		$sub_param_keys = $processors_sub_params[ $this->get_processor() ];

		foreach ( $sub_param_keys as $key ) {
			if ( isset( $args[ $key ] ) ) {
				$this->sub_params[ $key ] = $args[ $key ];
			}
		}
	}

	/**
	 * Set a processor sub param.
	 *
	 * @since  1.0.0
	 * @param  string  $param  Param name.
	 * @param  mixed   $value  Value.
	 * @return void
	 */
	public function set_sub_param( $param, $value ) {
		$this->sub_params[ $param ] = $value;
	}

	/**
	 * Get a processor sub param, checking if it exists first.
	 *
	 * @since  1.0.0
	 * @param  string  $param  Param name.
	 * @return mixed
	 */
	public function get_sub_param( $param ) {
		$sub_params = $this->get_sub_params();

		if ( empty( $sub_params ) ) {
			return null;
		}

		if ( ! isset( $sub_params[ $param ] ) ) {
			return null;
		}

		return $sub_params[ $param ];
	}

	/**
	 * Set the initial sub processes array.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_initial_prerequisite_sub_background_processes() {
		$initial = array();

		foreach ( $this->get_prerequisite_sub_background_processors() as $prerequisite_sub_background_processor ) {
			$initial[] = array(
				'processor'        => $prerequisite_sub_background_processor,
				'status'           => self::PROCESS_STATUS_PENDING,
				'complete'         => false,
				'current_position' => 0,
			);
		}

		return $initial;
	}

	/**
	 * Queue prerequisite processors.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function maybe_queue_prerequisite_sub_background_processors() {
		if ( empty( $this->get_prerequisite_sub_background_processors() ) || ! $this->get_background_processes_id() ) {
			return;
		}

		$prerequisite_sub_background_processes = $this->get_prerequisite_sub_background_processes();

		$queued_prerequisite_sub_background_processes = wp_list_filter( $prerequisite_sub_background_processes, array( 'status' => self::PROCESS_STATUS_PENDING ), 'NOT' );

		$this->queued_prerequisite_sub_background_processors = wp_list_pluck( $queued_prerequisite_sub_background_processes, 'processor' );

		$this->unqueued_prerequisite_sub_background_processors = array_diff( $this->get_prerequisite_sub_background_processors(), $this->queued_prerequisite_sub_background_processors );

		if ( empty( $this->unqueued_prerequisite_sub_background_processors ) ) {
			return;
		}

		// Reset sub processes to only the ones that are already queued or running.
		$prerequisite_sub_background_processes = $queued_prerequisite_sub_background_processes;

		$as_queue_mode = max_marine_background_processor_get_plugin_setting( 'background_process_action_scheduler_queue_mode' );

		if ( empty( $as_queue_mode ) || 'concurrent' === $as_queue_mode ) {
			foreach ( $this->unqueued_prerequisite_sub_background_processors as $unqueued_prerequisite_sub_background_processor ) {
				$prerequisite_sub_background_processor_to_queue = array(
					'processor'                      => $unqueued_prerequisite_sub_background_processor,
					'parent_background_processes_id' => $this->get_background_processes_id(),
					'status'                         => self::PROCESS_STATUS_PENDING,
					'complete'                       => false,
					'sub_params'                     => $this->get_sub_params(),
				);

				$prerequisite_sub_background_processes[] = $this->queue_prerequisite_sub_background_processor( $prerequisite_sub_background_processor_to_queue );
			}
		} else {
			$unqueued_prerequisite_sub_background_processor = $this->unqueued_prerequisite_sub_background_processors[0];

			$prerequisite_sub_background_processor_to_queue = array(
				'processor'                      => $unqueued_prerequisite_sub_background_processor,
				'parent_background_processes_id' => $this->get_background_processes_id(),
				'status'                         => self::PROCESS_STATUS_PENDING,
				'complete'                       => false,
				'sub_params'                     => $this->get_sub_params(),
			);

			$prerequisite_sub_background_processes[] = $this->queue_prerequisite_sub_background_processor( $prerequisite_sub_background_processor_to_queue );
		}

		$this->prerequisite_sub_background_processes = $prerequisite_sub_background_processes;

		// Update the parent process transient to add the sub processes.
		$parent_process = Max_Marine_Background_Processor_Background_Process_Functions::get_background_process_transient( $this->get_background_processes_id() );

		$parent_process['prerequisite_sub_background_processes'] = $this->get_prerequisite_sub_background_processes();

		Max_Marine_Background_Processor_Background_Process_Functions::update_background_process_transient( $this->get_background_processes_id(), $parent_process );
	}

	/**
	 * Check if this process has prerequisite sub processors.
	 *
	 * @since  1.0.0
	 * @return bool
	 */
	public function has_prerequisite_sub_background_processors() {
		return ! empty( $this->get_prerequisite_sub_background_processors() );
	}

	/**
	 * Set incomplete prerequisite sub processors.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function set_incomplete_prerequisite_sub_background_processors() {
		$incomplete = wp_list_filter( $this->get_prerequisite_sub_background_processes(), array( 'complete' => false ) );

		$this->incomplete_prerequisite_sub_background_processors = $incomplete;
	}

	/**
	 * Re-queue a parent process when there are incomplete prerequisite sub processes.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function maybe_requeue_parent_background_process() {
		$this->schedule_background_process_run( $this->get_background_processes_run_id() );
	}

	/**
	 * Maybe update a parent process transient.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function maybe_update_parent_background_process() {
		if ( ! $this->get_parent_background_processes_id() || ! $this->get_background_processes_id() ) {
			return;
		}

		Max_Marine_Background_Processor_Background_Process_Functions::update_prerequisite_sub_process_parent_background_process( $this->get_background_processes_id() );
	}

	/**
	 * Get info on the progress of the processing.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_progress() {
		$defaults = array();

		foreach ( $this->get_progress_keys() as $progress_key ) {
			$getter = "get_{$progress_key}";

			if ( is_callable( array( $this, $getter ), true ) ) {
				$defaults[ $progress_key ] = $this->$getter();
			}
		}

		return array_merge( $defaults, $this->extra_process_fields() );
	}

	/**
	 * Update the percent completed based on the current process run and the total rows.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function update_percent_complete() {
		if ( 0 === $this->get_total_rows() ) {
			return;
		}

		$this->percent_complete = round( min( ( $this->get_current_position() / $this->get_total_rows() ) * 100, 100 ), 1 );
	}

	/**
	 * Do stuff before starting a new process.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	protected function pre_background_process() {
	}

	/**
	 * Perform pre process actions.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	protected function background_process_start() {
		if ( 0 === $this->get_total_rows() ) {
			$this->set_total_rows();
		}

		$current_time = $this->get_current_time();

		if ( ! $current_time ) {
			max_marine_background_processor_debug( 'Problem starting process.' );

			return;
		}

		$this->status                  = self::PROCESS_STATUS_PROCESSING;
		$this->last_run_time           = $this->get_current_time();
		$this->last_run_time_formatted = max_marine_background_processor_format_timestamp_to_datetime_long( $this->get_current_time() );

		if ( ! $this->get_start_time() ) {
			$this->start_time           = $this->get_current_time();
			$this->start_time_formatted = max_marine_background_processor_format_timestamp_to_datetime_long( $this->get_current_time() );

			Max_Marine_Background_Processor_Background_Process_Functions::update_background_process( $this->get_progress() );
		}

		$this->get_data();
	}

	/**
	 * Perform pre process run actions.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	protected function background_process_run_start() {
		if ( ! $this->get_background_processes_run_id() ) {
			return;
		}

		$last_run = Max_Marine_Background_Processor_Background_Process_Functions::get_background_process_run_transient( $this->get_background_processes_run_id() );

		$this->current_background_process_run = array(
			'as_action_id'                   => $last_run['as_action_id'],
			'background_processes_run_id'    => $last_run['background_processes_run_id'],
			'background_processes_id'        => $last_run['background_processes_id'],
			'parent_background_processes_id' => $last_run['parent_background_processes_id'],
			'status'                         => self::PROCESS_STATUS_PROCESSING,
			'start_time'                     => $this->get_current_time(),
			'last_attempt_time'              => $this->get_current_time(),
			'attempts'                       => $last_run['attempts'] + 1,
		);

		Max_Marine_Background_Processor_Background_Process_Functions::update_background_process_run( $this->get_current_background_process_run() );

		$this->background_process_results = array(
			'skipped'   => array(),
			'failed'    => array(),
			'processed' => array(),
		);

		Max_Marine_Background_Processor_Background_Process_Functions::update_background_process_transient( $this->get_background_processes_id(), $this->get_progress() );
	}

	/**
	 * Run processor.
	 *
	 * @since  1.0.0
	 * @param  int  $background_processes_run_id  Run ID.
	 * @return void
	 */
	public function run( $background_processes_run_id ) {
		$this->background_processes_run_id = $background_processes_run_id;

		$this->init();

		if ( ! empty( $this->get_incomplete_prerequisite_sub_background_processors() ) ) {
			$this->maybe_queue_prerequisite_sub_background_processors();

			$this->maybe_requeue_parent_background_process();

			return;
		}

		$this->pre_background_process();

		$this->background_process_run_start();

		$this->background_process_start();

		$this->process_data();

		$this->post_background_process();
	}

	/**
	 * Process data.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function process_data() {
	}

	/**
	 * Perform post process actions.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	protected function post_background_process() {
		$background_process_run_skipped_rows   = count( $this->background_process_results['skipped'] );
		$background_process_run_failed_rows    = count( $this->background_process_results['failed'] );
		$background_process_run_processed_rows = count( $this->background_process_results['processed'] );

		$this->current_background_process_run['total_rows_skipped']   = $background_process_run_skipped_rows;
		$this->current_background_process_run['total_rows_failed']    = $background_process_run_failed_rows;
		$this->current_background_process_run['total_rows_processed'] = $background_process_run_processed_rows;

		$this->total_rows_skipped   += $background_process_run_skipped_rows;
		$this->total_rows_failed    += $background_process_run_failed_rows;
		$this->total_rows_processed += $background_process_run_processed_rows;

		$this->total_time += microtime( true ) - $this->get_last_run_time();

		$this->update_percent_complete();

		$this->post_background_process_run();

		if ( false !== $this->is_background_process_complete() ) {
			$this->background_process_complete();
		} else {
			$this->queue_next_background_process_run();
		}

		Max_Marine_Background_Processor_Background_Process_Functions::update_background_process_transient( $this->get_background_processes_id(), $this->get_progress() );
	}

	/**
	 * Create a process run.
	 *
	 * @since  1.0.0
	 * @param  array  $background_process  Process data.
	 * @return false|array
	 */
	public function create_background_process_run( $background_process ) {
		$background_processes_run_id = Max_Marine_Background_Processor_Background_Process_Functions::create_new_background_process_run( $background_process );

		if ( empty( $background_processes_run_id ) ) {
			max_marine_background_processor_debug( 'Problem creating new bg process.' );
			return false;
		}

		$action_id = $this->schedule_background_process_run( $background_processes_run_id );

		$background_process['background_processes_run_id'] = $background_processes_run_id;
		$background_process['as_action_id']                = $action_id;
		$background_process['attempts']                    = 0;

		// Update the run with the action id.
		Max_Marine_Background_Processor_Background_Process_Functions::update_background_process_run( $background_process );

		Max_Marine_Background_Processor_Background_Process_Functions::update_background_process_run_transient( $background_processes_run_id, $background_process );

		return $background_process;
	}

	/**
	 * Schedule a process run using ActionScheduler.
	 *
	 * @since  1.0.0
	 * @param  false|int  $background_processes_run_id  Process run ID.
	 * @param  false|int  $delay                      Optional. Delay, in seconds. Default false.
	 * @return int|false
	 */
	public function schedule_background_process_run( $background_processes_run_id, $delay = false ) {
		if ( false === $background_processes_run_id ) {
			return false;
		}

		$as_queue_mode = max_marine_background_processor_get_plugin_setting( 'background_process_action_scheduler_queue_mode' );

		if ( empty( $as_queue_mode ) ) {
			max_marine_background_processor_debug( 'Max_Marine_Ebay_Listings_Checker_Abstract_Processor::schedule_process_run - No AS queue mode setting.' );

			return false;
		}

		if ( 'async' === $as_queue_mode && false === $delay ) {
			return as_enqueue_async_action( 'max-marine/background-processor/process-background-process', array( $background_processes_run_id ), 'max-marine-background-processor' );
		} else {
			$as_queue_mode_scheduled_delay = max_marine_background_processor_get_plugin_setting( 'background_process_action_scheduler_queue_mode_scheduled_delay' );

			if ( false === $delay && empty( $as_queue_mode_scheduled_delay ) ) {
				max_marine_background_processor_debug( 'Max_Marine_Ebay_Listings_Checker_Abstract_Processor::schedule_process_run - No AS queue mode scheduled delay.' );

				return false;
			}

			$next_run_timestamp = time() + ( ( false === $delay ) ? $as_queue_mode_scheduled_delay : absint( $delay ) );

			return as_schedule_single_action( $next_run_timestamp, 'max-marine/background-processor/process-background-process', array( $background_processes_run_id ), 'max-marine-background-processor' );
		}
	}

	/**
	 * Queue the next process run.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function queue_next_background_process_run() {
		$this->create_background_process_run( $this->get_progress() );
	}

	/**
	 * Completed actions from this process run.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function background_process_run_complete() {
		$this->current_background_process_run['status']         = self::PROCESS_STATUS_COMPLETE;
		$this->current_background_process_run['completed_time'] = microtime( true );

		$this->current_background_process_run['total_time'] = $this->current_background_process_run['completed_time'] - $this->current_background_process_run['start_time'];

		Max_Marine_Background_Processor_Background_Process_Functions::save_completed_background_process_run( $this->get_current_background_process_run() );

		// What this a retry?
		if ( $this->current_background_process_run['attempts'] > 1 ) {
			$message_text = sprintf(
				/* translators: 1: Conditional parent background process ID, 2: Processor label, 3: Background process ID, 4: Date. */
				__( '%1$s%2$s run ID %3$d successfully recovered after %4$d previous failure(s).', 'max-marine-background-processor' ),
				( ! empty( $this->get_parent_background_processes_id() ) ) ? __( 'Prerequisite ', 'max-marine-background-processor' ) : '',
				Max_Marine_Background_Processor_Background_Process_Functions::get_background_processor_label( $this->get_processor() ),
				$this->get_background_processes_id(),
				$this->current_background_process_run['attempts']
			);

			Max_Marine_Background_Processor_Background_Process_Messages_Functions::create_background_processes_message(
				array(
					'background_processes_id'     => $this->get_background_processes_id(),
					'background_processes_run_id' => $this->get_background_processes_run_id(),
					'message'                     => $message_text,
					'type'                        => 'success',
				)
			);
		}

		Max_Marine_Background_Processor_Background_Process_Functions::update_background_process_transient( $this->get_background_processes_id(), $this->get_progress() );
	}

	/**
	 * Add process run.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function post_background_process_run() {
		if ( false === $this->get_background_processes_id() ) {
			return;
		}

		$this->background_process_run_complete();

		$this->update_percent_complete();

		$this->background_process_runs[] = $this->get_current_background_process_run();

		Max_Marine_Background_Processor_Background_Process_Functions::save_background_process_run_results( $this->get_progress() );
	}

	/**
	 * Check if the process is complete.
	 *
	 * @since  1.0.0
	 * @return bool
	 */
	protected function is_background_process_complete() {
		return $this->get_current_position() >= $this->get_total_rows();
	}

	/**
	 * Perform post process actions.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	protected function background_process_complete() {
		$completed_time = microtime( true );

		$this->complete                 = true;
		$this->completed_time           = $completed_time;
		$this->completed_time_formatted = max_marine_background_processor_format_timestamp_to_datetime_long( $completed_time );
		$this->status                   = self::PROCESS_STATUS_COMPLETE;

		$this->total_time = round( $this->get_total_time(), 4 );

		Max_Marine_Background_Processor_Background_Process_Functions::save_completed_background_process( $this->get_progress() );
	}

	/**
	 * Add an error to the errors array.
	 *
	 * @since  1.0.0
	 * @param  string  $error  Error text to add.
	 * @return void
	 */
	protected function add_error( $error ) {
		$this->errors[] = $error;
	}

	/**
	 * Generic set total rows.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	protected function set_total_rows() {
	}

	/**
	 * Set data.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	protected function set_data() {
	}

	/**
	 * Memory exceeded.
	 *
	 * Ensures the current process run never exceeds 90%
	 * of the maximum WordPress memory.
	 *
	 * @since  1.0.0
	 * @return bool
	 */
	protected function check_memory_exceeded() {
		$memory_limit   = $this->get_memory_limit() * 0.9; // 90% of max memory
		$current_memory = memory_get_usage( true );
		$return         = false;

		if ( $current_memory >= $memory_limit ) {
			$return = true;
		}

		return $return;
	}

	/**
	 * Get memory limit.
	 *
	 * @since  1.0.0
	 * @return int
	 */
	protected function get_memory_limit() {
		if ( function_exists( 'ini_get' ) ) {
			$memory_limit = ini_get( 'memory_limit' );
		} else {
			$memory_limit = self::PROCESS_MEMORY_LIMIT;
		}

		if ( ! $memory_limit || -1 === intval( $memory_limit ) ) {
			// Unlimited, set to 32GB.
			$memory_limit = '32000M';
		}

		return intval( $memory_limit ) * 1024 * 1024;
	}

	/**
	 * Check time exceeded.
	 *
	 * Ensures the current process run never exceeds a sensible time limit.
	 * A timeout limit of 30s is common on shared hosting.
	 *
	 * @since  1.0.0
	 * @return bool
	 */
	protected function check_time_exceeded() {
		$finish = $this->last_run_time + self::PROCESS_TIME_LIMIT;
		$return = false;

		if ( time() >= $finish ) {
			$return = true;
		}

		return $return;
	}

	/**
	 * Get extra process fields.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	protected function extra_process_fields() {
		return array();
	}

	/**
	 * Get processor parameters.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_params() {
		return $this->params;
	}

	/**
	 * Get processor sub parameters.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_sub_params() {
		return $this->sub_params;
	}

	/**
	 * Generic get data.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	protected function get_data() {
	}

	/**
	 * Get percentage complete.
	 *
	 * @since  1.0.0
	 * @return float
	 */
	public function get_percent_complete() {
		return $this->percent_complete;
	}

	/**
	 * Get batch size.
	 *
	 * @since  1.0.0
	 * @return int
	 */
	public function get_batch_size() {
		if ( -1 === $this->get_total_rows_limit() ) {
			return $this->batch_size;
		}

		$limit = $this->batch_size;

		if ( $this->get_current_position() + $limit > $this->get_total_rows_limit() ) {
			$limit = $this->get_total_rows_limit() - $this->get_current_position();
		}

		return $limit;
	}

	/**
	 * Get progress keys.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_progress_keys() {
		$progress_keys = Max_Marine_Background_Processor_Background_Process_Functions::get_background_process_progress_keys();

		return $progress_keys;
	}

	/**
	 * Get current time.
	 *
	 * @since  1.0.0
	 * @return null|float
	 */
	public function get_current_time() {
		return $this->current_time;
	}

	/**
	 * Get status.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * Get queued time.
	 *
	 * @since  1.0.0
	 * @return null|float
	 */
	public function get_queued_time() {
		return $this->queued_time;
	}

	/**
	 * Get start time.
	 *
	 * @since  1.0.0
	 * @return null|float
	 */
	public function get_start_time() {
		return $this->start_time;
	}

	/**
	 * Get last run time.
	 *
	 * @since  1.0.0
	 * @return null|float
	 */
	public function get_last_run_time() {
		return $this->last_run_time;
	}

	/**
	 * Get completed time.
	 *
	 * @since  1.0.0
	 * @return null|float
	 */
	public function get_completed_time() {
		return $this->completed_time;
	}

	/**
	 * Get formatted queued time.
	 *
	 * @since  1.0.0
	 * @return false|string
	 */
	public function get_queued_time_formatted() {
		return $this->queued_time_formatted;
	}

	/**
	 * Get formatted start time.
	 *
	 * @since  1.0.0
	 * @return false|string
	 */
	public function get_start_time_formatted() {
		return $this->start_time_formatted;
	}

	/**
	 * Get formatted last run time.
	 *
	 * @since  1.0.0
	 * @return false|string
	 */
	public function get_last_run_time_formatted() {
		return $this->last_run_time_formatted;
	}

	/**
	 * Get formatted completed time.
	 *
	 * @since  1.0.0
	 * @return false|string
	 */
	public function get_completed_time_formatted() {
		return $this->completed_time_formatted;
	}

	/**
	 * Get total time.
	 *
	 * @since  1.0.0
	 * @return float
	 */
	public function get_total_time() {
		return $this->total_time;
	}

	/**
	 * Get the current position.
	 *
	 * @since  1.0.0
	 * @return int
	 */
	public function get_current_position() {
		return $this->current_position;
	}

	/**
	 * Get the complete status.
	 *
	 * @since  1.0.0
	 * @return bool
	 */
	public function get_complete() {
		return $this->complete;
	}

	/**
	 * Get the total number of rows.
	 *
	 * @since  1.0.0
	 * @return int
	 */
	public function get_total_rows() {
		return $this->total_rows;
	}

	/**
	 * Get the total rows limit which is sometimes overridden in processors.
	 *
	 * @since  1.0.0
	 * @return int
	 */
	public function get_total_rows_limit() {
		return $this->total_rows_limit;
	}

	/**
	 * Get the total number of rows failed.
	 *
	 * @since  1.0.0
	 * @return int
	 */
	public function get_total_rows_failed() {
		return $this->total_rows_failed;
	}

	/**
	 * Get the total number of rows skipped.
	 *
	 * @since  1.0.0
	 * @return int
	 */
	public function get_total_rows_skipped() {
		return $this->total_rows_skipped;
	}

	/**
	 * Get the total number of rows processed.
	 *
	 * @since  1.0.0
	 * @return int
	 */
	public function get_total_rows_processed() {
		return $this->total_rows_processed;
	}

	/**
	 * Get current processor.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_processor() {
		return $this->processor;
	}

	/**
	 * Get current background process ID.
	 *
	 * @since  1.0.0
	 * @return false|int
	 */
	public function get_background_processes_id() {
		return $this->background_processes_id;
	}

	/**
	 * Get current background process parent's ID.
	 *
	 * @since  1.0.0
	 * @return false|int
	 */
	public function get_parent_background_processes_id() {
		return $this->parent_background_processes_id;
	}

	/**
	 * Get current background process run ID.
	 *
	 * @since  1.0.0
	 * @return false|int
	 */
	public function get_background_processes_run_id() {
		return $this->background_processes_run_id;
	}

	/**
	 * Get current action scheduler action ID.
	 *
	 * @since  1.0.0
	 * @return false|int
	 */
	public function get_as_action_id() {
		return $this->as_action_id;
	}

	/**
	 * Get current background process results.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_background_process_results() {
		return $this->background_process_results;
	}

	/**
	 * Get incomplete prerequisite sub background processors.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_incomplete_prerequisite_sub_background_processors() {
		return $this->incomplete_prerequisite_sub_background_processors;
	}

	/**
	 * Get prerequisite sub processes.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_prerequisite_sub_background_processes() {
		return $this->prerequisite_sub_background_processes;
	}

	/**
	 * Get prerequisite sub background process results.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_prerequisite_sub_background_process_results() {
		return $this->prerequisite_sub_background_process_results;
	}

	/**
	 * Get current prerequisite sub background processes.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_prerequisite_sub_background_processors() {
		return $this->prerequisite_sub_background_processors;
	}

	/**
	 * Get process runs.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_background_process_runs() {
		return $this->background_process_runs;
	}

	/**
	 * Get current background process run.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_current_background_process_run() {
		return $this->current_background_process_run;
	}
}
