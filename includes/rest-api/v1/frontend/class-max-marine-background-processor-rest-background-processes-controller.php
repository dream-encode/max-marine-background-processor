<?php
/**
 * Class Max_Marine_Background_Processor_REST_Background_Processes_Controller
 */

namespace Max_Marine\Background_Processor\Core\RestApi\V1\Frontend;

use Exception;
use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use Max_Marine\Background_Processor\Core\RestApi\Max_Marine_Background_Processor_REST_Response;
use Max_Marine\Background_Processor\Core\Abstracts\Max_Marine_Background_Processor_Abstract_REST_Controller;
use Max_Marine\Background_Processor\Core\Background_Process\Max_Marine_Background_Processor_Background_Process_Functions;
use Max_Marine\Background_Processor\Core\Background_Process\Max_Marine_Background_Processor_Background_Process_Messages_Functions;

/**
 * Class Max_Marine_Background_Processor_REST_Background_Processes_Controller
 */
class Max_Marine_Background_Processor_REST_Background_Processes_Controller extends Max_Marine_Background_Processor_Abstract_REST_Controller {
	/**
	 * Max_Marine_Background_Processor_REST_Background_Processes_Controller constructor.
	 */
	public function __construct() {
		$this->namespace = 'max-marine/v1';
		$this->rest_base = 'background-processor/background-processes';
	}

	/**
	 * Register routes API.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function register_routes() {
		$this->routes = array(
			'' => array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_new_background_process' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_completed_background_processes' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
			),
			'(?P<id>[\d]+)' => array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_background_process' ),
					'permission_callback' => array( $this, 'permission_callback' ),
					'args'                => array(
						'id' => array(
							'description'       => __( 'Unique identifier for the resource.', 'max-marine-background-processor' ),
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
						),
					),
				),
			),
			'(?P<id>[\d]+)/cancel' => array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'cancel_running_background_process' ),
					'permission_callback' => array( $this, 'permission_callback' ),
					'args'                => array(
						'id' => array(
							'description'       => __( 'Unique identifier for the resource.', 'max-marine-background-processor' ),
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
						),
					),
				),
			),
			'(?P<id>[\d]+)/retry-failed' => array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'retry_failed_running_background_process' ),
					'permission_callback' => array( $this, 'permission_callback' ),
					'args'                => array(
						'id' => array(
							'description'       => __( 'Unique identifier for the resource.', 'max-marine-background-processor' ),
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
						),
					),
				),
			),
			'(?P<id>[\d]+)/results' => array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_background_process_results' ),
					'permission_callback' => array( $this, 'permission_callback' ),
					'args'                => array(
						'id' => array(
							'description'       => __( 'Unique identifier for the resource.', 'max-marine-background-processor' ),
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
						),
					),
				),
			),
			'(?P<id>[\d]+)/results/errors' => array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_background_process_results_errors' ),
					'permission_callback' => array( $this, 'permission_callback' ),
					'args'                => array(
						'id' => array(
							'description'       => __( 'Unique identifier for the resource.', 'max-marine-background-processor' ),
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
						),
					),
				),
			),
			'(?P<id>[\d]+)/results/details' => array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_background_process_result_details' ),
					'permission_callback' => array( $this, 'permission_callback' ),
					'args'                => array(
						'id' => array(
							'description'       => __( 'Unique identifier for the resource.', 'max-marine-background-processor' ),
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
						),
					),
				),
			),
			'(?P<processId>[\d]+)/results/(?P<resultId>[\d]+)/details' => array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_background_process_result_details' ),
					'permission_callback' => array( $this, 'permission_callback' ),
					'args'                => array(
						'processId' => array(
							'description'       => __( 'Unique identifier for the resource.', 'max-marine-background-processor' ),
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
						),
						'resultId' => array(
							'description'       => __( 'Unique identifier for the resource.', 'max-marine-background-processor' ),
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
						),
					),
				),
			),
			'background-process' => array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'run_new_background_process' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
			),
			'cancel-all' => array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'cancel_all' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
			),
			'running' => array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_all_running_background_processes' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
			),
			'background-processors' => array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_available_background_processors' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
			),
		);

		parent::register_routes();
	}

	/**
	 * Validate user permissions.
	 *
	 * @since  1.0.0
	 * @return bool
	 */
	public function permission_callback() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Get all completed background processes.
	 *
	 * @since  1.0.0
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_completed_background_processes() {
		$response = new Max_Marine_Background_Processor_REST_Response();

		$success = false;

		try {
			$results = Max_Marine_Background_Processor_Background_Process_Functions::get_all_completed_background_processes();

			$success = true;

			$response->status = '200';
			$response->data   = $results;
		} catch ( Exception $e ) {
			$response->message = $e->getMessage();
		}

		$response->success = $success;
		$response->status  = $success ? '200' : '401';

		return rest_ensure_response( $response );
	}

	/**
	 * Get a background process by ID.
	 *
	 * @since  1.0.0
	 * @param  WP_REST_Request  $request  Request object.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_background_process( $request ) {
		$response = new Max_Marine_Background_Processor_REST_Response();

		$background_processes_id = $request->get_param( 'id' );

		try {
			if ( ! $background_processes_id || ! absint( $background_processes_id ) > 0 ) {
				return rest_ensure_response(
					new WP_Error(
						'rest_resource_not_found',
						__( 'Process ID invalid.', 'max-marine-background-processor' ),
						array( 'status' => '200' )
					)
				);
			}

			$result = Max_Marine_Background_Processor_Background_Process_Functions::get_background_process_by_id( $background_processes_id );

			if ( false === $result ) {
				return rest_ensure_response(
					new WP_Error(
						'rest_resource_not_found',
						__( 'Saved background process not found with given params.', 'max-marine-background-processor' ),
						array( 'status' => '200' )
					)
				);
			}

			$response->status = '200';
			$response->data   = $result;
		} catch ( Exception $e ) {
			$response->message = $e->getMessage();
		}

		$response->success = true;
		$response->status  = '200';

		return rest_ensure_response( $response );
	}

	/**
	 * Get a background process's results by process ID.
	 *
	 * @since  1.0.0
	 * @param  WP_REST_Request  $request  Request object.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_background_process_results( $request ) {
		$response = new Max_Marine_Background_Processor_REST_Response();

		$background_processes_id = $request->get_param( 'id' );

		try {
			if ( ! $background_processes_id || ! absint( $background_processes_id ) > 0 ) {
				return rest_ensure_response(
					new WP_Error(
						'rest_resource_not_found',
						__( 'Process ID invalid.', 'max-marine-background-processor' ),
						array( 'status' => '200' )
					)
				);
			}

			$query_params = $request->get_query_params();

			$per_page = ( ! empty( $query_params['per_page'] ) ) ? $query_params['per_page'] : -1;
			$page     = ( ! empty( $query_params['page'] ) ) ? $query_params['page'] : 0;

			$background_process = Max_Marine_Background_Processor_Background_Process_Functions::get_background_process_by_id( $background_processes_id );

			if ( false === $background_process ) {
				return rest_ensure_response(
					new WP_Error(
						'rest_resource_not_found',
						__( 'Saved background process not found with given params.', 'max-marine-background-processor' ),
						array( 'status' => '200' )
					)
				);
			}

			// TODO: Get these from the processor.
			$results = array();

			$response->status = '200';
			$response->data   = compact( 'background_process', 'results', 'per_page', 'page' );
		} catch ( Exception $e ) {
			$response->message = $e->getMessage();
		}

		$response->success = true;
		$response->status  = '200';

		return rest_ensure_response( $response );
	}

	/**
	 * Get a background process's results errors by process ID.
	 *
	 * @since  1.0.0
	 * @param  WP_REST_Request  $request  Request object.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_background_process_results_errors( $request ) {
		$response = new Max_Marine_Background_Processor_REST_Response();

		$background_processes_id = $request->get_param( 'id' );

		try {
			if ( ! $background_processes_id || ! absint( $background_processes_id ) > 0 ) {
				return rest_ensure_response(
					new WP_Error(
						'rest_resource_not_found',
						__( 'Process ID invalid.', 'max-marine-background-processor' ),
						array( 'status' => '200' )
					)
				);
			}

			$query_params = $request->get_query_params();

			$per_page = ( ! empty( $query_params['per_page'] ) ) ? $query_params['per_page'] : -1;
			$page     = ( ! empty( $query_params['page'] ) ) ? $query_params['page'] : 0;

			$background_process = Max_Marine_Background_Processor_Background_Process_Functions::get_background_process_by_id( $background_processes_id );

			if ( false === $background_process ) {
				return rest_ensure_response(
					new WP_Error(
						'rest_resource_not_found',
						__( 'Saved background process not found with given params.', 'max-marine-background-processor' ),
						array( 'status' => '200' )
					)
				);
			}

			// TODO: Get these from the processor.
			$results = array();

			$response->status = '200';
			$response->data   = compact( 'background_process', 'results', 'per_page', 'page' );
		} catch ( Exception $e ) {
			$response->message = $e->getMessage();
		}

		$response->success = true;
		$response->status  = '200';

		return rest_ensure_response( $response );
	}

	/**
	 * Get a background process's result error details.
	 *
	 * @since  1.0.0
	 * @param  WP_REST_Request  $request  Request object.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_background_process_result_details( $request ) {
		$response = new Max_Marine_Background_Processor_REST_Response();

		$background_processes_id = $request->get_param( 'processId' );
		$result_id             = $request->get_param( 'resultId' );

		try {
			if ( ! $background_processes_id || ! absint( $background_processes_id ) > 0 ) {
				return rest_ensure_response(
					new WP_Error(
						'rest_resource_not_found',
						__( 'Background process ID invalid.', 'max-marine-background-processor' ),
						array( 'status' => '200' )
					)
				);
			}

			if ( ! $result_id || ! absint( $result_id ) > 0 ) {
				return rest_ensure_response(
					new WP_Error(
						'rest_resource_not_found',
						__( 'Error ID invalid.', 'max-marine-background-processor' ),
						array( 'status' => '200' )
					)
				);
			}

			// TODO: Get these from the processor.
			$details = array();

			$response->status = '200';
			$response->data   = $details;
		} catch ( Exception $e ) {
			$response->message = $e->getMessage();
		}

		$response->success = true;
		$response->status  = '200';

		return rest_ensure_response( $response );
	}

	/**
	 * Run a new validator process.
	 *
	 * @since  1.0.0
	 * @param  WP_REST_Request  $request  Request object.
	 * @return WP_Error|WP_REST_Response
	 */
	public function run_new_background_process( $request ) {
		$response = new Max_Marine_Background_Processor_REST_Response();

		Max_Marine_Background_Processor_Background_Process_Functions::queue_new_background_process( $request->get_params() );

		$response->success = true;
		$response->status  = '200';

		return rest_ensure_response( $response );
	}

	/**
	 * Cancel all running validator processes.
	 *
	 * @since  1.0.0
	 * @param  WP_REST_Request  $request  Request object.
	 * @return WP_Error|WP_REST_Response
	 */
	public function cancel_all( $request ) {
		$response = new Max_Marine_Background_Processor_REST_Response();

		Max_Marine_Background_Processor_Background_Process_Functions::cancel_all_background_processes();

		$response->success = true;
		$response->status  = '200';

		return rest_ensure_response( $response );
	}

	/**
	 * Cancel a running background processes.
	 *
	 * @since  1.0.0
	 * @param  WP_REST_Request  $request  Request object.
	 * @return WP_Error|WP_REST_Response
	 */
	public function cancel_running_background_process( $request ) {
		$response = new Max_Marine_Background_Processor_REST_Response();

		$background_processes_id = $request->get_param( 'id' );

		try {
			if ( ! $background_processes_id || ! absint( $background_processes_id ) > 0 ) {
				return rest_ensure_response(
					new WP_Error(
						'rest_resource_not_found',
						__( 'Process ID invalid.', 'max-marine-background-processor' ),
						array( 'status' => '200' )
					)
				);
			}

			Max_Marine_Background_Processor_Background_Process_Functions::cancel_background_process( $background_processes_id );

			$response->status = '200';
		} catch ( Exception $e ) {
			$response->message = $e->getMessage();
		}

		$response->success = true;
		$response->status  = '200';

		return rest_ensure_response( $response );
	}

	/**
	 * Retry a failed background processes.
	 *
	 * @since  1.0.0
	 * @param  WP_REST_Request  $request  Request object.
	 * @return WP_Error|WP_REST_Response
	 */
	public function retry_failed_running_background_process( $request ) {
		$response = new Max_Marine_Background_Processor_REST_Response();

		$background_processes_id = $request->get_param( 'id' );

		try {
			if ( ! $background_processes_id || ! absint( $background_processes_id ) > 0 ) {
				return rest_ensure_response(
					new WP_Error(
						'rest_resource_not_found',
						__( 'Process ID invalid.', 'max-marine-background-processor' ),
						array( 'status' => '200' )
					)
				);
			}

			Max_Marine_Background_Processor_Background_Process_Functions::retry_failed_background_process( $background_processes_id );

			$response->status = '200';
		} catch ( Exception $e ) {
			$response->message = $e->getMessage();
		}

		$response->success = true;
		$response->status  = '200';

		return rest_ensure_response( $response );
	}

	/**
	 * Run a new async background process.
	 *
	 * @since  1.0.0
	 * @param  WP_REST_Request  $request  Request object.
	 * @return WP_Error|WP_REST_Response
	 */
	public function run_async_background_process( $request ) {
		$response = new Max_Marine_Background_Processor_REST_Response();

		$processors = array_keys( Max_Marine_Background_Processor_Background_Process_Functions::get_background_processors() );

		$processor = $request->get_param( 'processor' );

		if ( ! in_array( $processor, $processors, true ) ) {
			$response->success = false;
			$response->status  = 'error';

			$response->data = new WP_Error(
				'max_marine_background_processor_invalid_process_error',
				__( 'No processor or invalid processor specified!', 'max-marine-background-processor' ),
				array( 'status' => '200' )
			);

			return rest_ensure_response( $response );
		}

		$response->success = true;
		$response->status  = '200';

		return rest_ensure_response( $response );
	}

	/**
	 * Get running processes status.
	 *
	 * @since  1.0.0
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_all_running_background_processes() {
		$response = new Max_Marine_Background_Processor_REST_Response();

		$response->success = true;
		$response->status  = '200';

		$response->data = array(
			'running'  => Max_Marine_Background_Processor_Background_Process_Functions::get_all_running_background_processes(),
			'messages' => Max_Marine_Background_Processor_Background_Process_Messages_Functions::get_undismissed_background_processes_messages(),
		);

		return rest_ensure_response( $response );
	}

	/**
	 * Get available background processors.
	 *
	 * @since  1.0.0
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_available_background_processors() {
		$response = new Max_Marine_Background_Processor_REST_Response();

		$response->success = true;
		$response->status  = '200';

		$processors = array_values( Max_Marine_Background_Processor_Background_Process_Functions::get_background_processors() );

		$response->data = $processors;

		return rest_ensure_response( $response );
	}
}
