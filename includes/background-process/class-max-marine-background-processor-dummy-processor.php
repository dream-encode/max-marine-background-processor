<?php
/**
 * Class to test background processing.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Max_Marine\Background_Processor
 * @subpackage Max_Marine\Background_Processor/includes/background-process
 */

namespace Max_Marine\Background_Processor\Core\Background_Process;

use Exception;
use WC_Product;
use WC_Product_Query;
use WP_Term;

use Max_Marine\Background_Processor\Core\Background_Process\Max_Marine_Background_Processor_Base_Processor;
use Max_Marine\Background_Processor\Core\Log\Max_Marine_Background_Processor_WC_Logger;

/**
 * Class to process existing WooCommerce Products.
 *
 * @since      1.0.0
 * @package    Max_Marine\Background_Processor
 * @subpackage Max_Marine\Background_Processor/includes/background-process
 * @author     David Baumwald <david@dream-encode.com>
 */
final class Max_Marine_Background_Processor_Dummy_Processor extends Max_Marine_Background_Processor_Base_Processor {

	/**
	 * Processor.
	 *
	 * @var string
	 */
	public $processor = 'dummy_process';

	/**
	 * Batch size.
	 *
	 * @var int
	 */

	protected $batch_size = 500;

	/**
	 * Total rows limit.
	 *
	 * @var int
	 */
	public $total_rows_limit = -1;

	/**
	 * Initialize process.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get the total number of rows to process.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function set_total_rows() {
		$args = array(
			'status' => 'publish',
			'limit'  => $this->get_total_rows_limit(),
			'return' => 'ids',
		);

		$products = (array) wc_get_products( $args );

		$this->total_rows = count( $products );
	}

	/**
	 * Get a set of data for this batch.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function get_data() {
		$args = array(
			'offset' => $this->get_current_position(),
			'limit'  => $this->get_batch_size(),
		);

		$query = new WC_Product_Query( $args );

		$this->data = (array) $query->get_products();
	}

	/**
	 * Process all data.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function process_data() {
		$index = 0;

		foreach ( $this->data as $wc_product ) {
			if ( ! $wc_product instanceof WC_Product ) {
				$this->background_process_results['skipped'][] = __( 'Skipped: Invalid WC_Product.', 'max-marine-background-processor' );

				continue;
			}

			$results = $this->process_data_row( $wc_product );

			if ( false === $results ) {
				$this->background_process_results['failed'][] = $wc_product->get_id();
			} else {
				$this->background_process_results['processed'][] = $results;
			}

			++$index;

			if ( $this->prevent_timeouts && ( $this->check_time_exceeded() || $this->check_memory_exceeded() ) ) {
				break;
			}
		}

		$this->current_background_process_run['total_rows'] = $index;

		$this->current_position += $index;
	}

	/**
	 * Process a single data row.
	 *
	 * @since  1.0.0
	 * @throws Exception  If item cannot be processed.
	 * @param  mixed  $product  Row data.
	 * @return false|array {
	 *     item_id: int,
	 *     error: bool,
	 *     errors: Array<mixed>,
	 *     data: Array<mixed>
	 * }
	 */
	protected function process_data_row( $product ) {
		if ( ! $product instanceof WC_Product ) {
			return false;
		}

		return array(
			'item_id' => $product->get_id(),
			'error'   => false,
			'errors'  => array(),
			'data'    => array(),
		);
	}

	/**
	 * Process a run's results.
	 *
	 * @since  1.0.0
	 * @param  array  $results  Run results.
	 * @return void
	 */
	protected function process_run_results( $results ) {
		max_marine_background_processor_debug( $results );
	}
}
