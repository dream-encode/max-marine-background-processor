<?php
/**
 * Class Max_Marine_Background_Processor_REST_Background_Processes_Messages_Controller
 */

namespace Max_Marine\Background_Processor\Core\RestApi\V1\Frontend;

use Exception;
use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use Max_Marine\Background_Processor\Core\RestApi\Max_Marine_Background_Processor_REST_Response;
use Max_Marine\Background_Processor\Core\Abstracts\Max_Marine_Background_Processor_Abstract_REST_Controller;
use Max_Marine\Background_Processor\Core\Background_Process\Max_Marine_Background_Processor_Background_Process_Messages_Functions;

/**
 * Class Max_Marine_Background_Processor_REST_Background_Processes_Messages_Controller
 */
class Max_Marine_Background_Processor_REST_Background_Processes_Messages_Controller extends Max_Marine_Background_Processor_Abstract_REST_Controller {
	/**
	 * Max_Marine_Background_Processor_REST_Background_Processes_Messages_Controller constructor.
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
			'messages/dismiss'     => array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'dismiss_background_process_message' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
			),
			'messages/dismiss-all' => array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'dismiss_all_background_process_messages' ),
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
	 * Dismiss a background processes message.
	 *
	 * @since  1.0.0
	 * @param  WP_REST_Request  $request  Request object.
	 * @return WP_Error|WP_REST_Response
	 */
	public function dismiss_background_process_message( $request ) {
		$response = new Max_Marine_Background_Processor_REST_Response();

		$background_process_message_id = $request->get_param( 'id' );

		try {
			if ( ! $background_process_message_id || ! absint( $background_process_message_id ) > 0 ) {
				return rest_ensure_response(
					new WP_Error(
						'rest_resource_not_found',
						__( 'Process message ID invalid.', 'max-marine-background-processor' ),
						array( 'status' => '200' )
					)
				);
			}

			Max_Marine_Background_Processor_Background_Process_Messages_Functions::dismiss_background_processes_message( $background_process_message_id );
		} catch ( Exception $e ) {
			$response->message = $e->getMessage();
		}

		$response->success = true;
		$response->status  = '200';

		return rest_ensure_response( $response );
	}

	/**
	 * Dismiss all background processes messages for a user.
	 *
	 * @since  1.3.0
	 * @param  WP_REST_Request  $request  Request object.
	 * @return WP_Error|WP_REST_Response
	 */
	public function dismiss_all_background_process_messages( $request ) {
		$response = new Max_Marine_Background_Processor_REST_Response();

		Max_Marine_Background_Processor_Background_Process_Messages_Functions::dismiss_all_background_processes_messages();

		$response->success = true;
		$response->status  = '200';

		return rest_ensure_response( $response );
	}
}
