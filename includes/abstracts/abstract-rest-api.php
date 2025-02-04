<?php
/**
 * Class Max_Marine_Background_Processor_API_Base
 *
 * Base class for api
 *
 * @since 1.0.0
 */

namespace Max_Marine\Background_Processor\Core\Abstracts;

/**
 * Class Max_Marine_Background_Processor_API_Base
 *
 * Base class for api
 *
 * @since 1.0.0
 */
abstract class Max_Marine_Background_Processor_Abstract_API {
	/**
	 * The current version.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     string   $version  The current version.
	 */
	public $version = 'v1';

	/**
	 * The current endpoint.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     string   $endpoint  The current endpoint.
	 */
	public $endpoint = '';

	/**
	 * Controllers to load.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     array   $controllers  Controllers to load.
	 */
	public $controllers = array();

	/**
	 * Max_Marine_Background_Processor_API_Base constructor.
	 */
	public function __construct() {
		$this->rest_api_init();
	}

	/**
	 * Init REST.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function rest_api_init() {
		if ( ! class_exists( '\WP_REST_Server' ) ) {
			return;
		}

		$this->rest_api_includes();

		add_action( 'rest_api_init', array( $this, 'rest_api_register_routes' ), 10 );
	}

	/**
	 * Include relevant files.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function rest_api_includes() {
		include_once MAX_MARINE_BACKGROUND_PROCESSOR_PLUGIN_PATH . 'includes/rest-api/class-max-marine-background-processor-rest-authentication.php';
	}

	/**
	 * Register routes
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function rest_api_register_routes() {
		if ( ! $this->controllers ) {
			return;
		}

		$controllers = array();

		foreach ( $this->controllers as $name => $controller ) {
			if ( is_string( $controller ) ) {
				$name                 = $controller;
				$class                = '\\Max_Marine\\Background_Processor\\Core\\RestApi\\V1\\Frontend\\' . $controller;
				$controllers[ $name ] = new $class();
			} else {
				$controllers[ $name ] = $controller;
			}

			$controllers[ $name ]->register_routes();
		}

		$this->controllers = $controllers;
	}
}
