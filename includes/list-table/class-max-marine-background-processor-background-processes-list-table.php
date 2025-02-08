<?php
/**
 * List table for displaying background processes.
 *
 * This class extends the WP_List_Table for a view of background processes.
 *
 * @since      1.0.0
 * @package    Max_Marine\Background_Processor
 * @subpackage Max_Marine\Background_Processor/admin
 * @author     David Baumwald <david@dream-encode.com>
 */

namespace Max_Marine\Background_Processor\Core\ListTable;

use WP_List_Table;

use Max_Marine\Background_Processor\Core\Background_Process\Max_Marine_Background_Processor_Background_Process_Functions;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List table for displaying background processes.
 *
 * This class extends the WP_List_Table for a view of background processes.
 *
 * @since      1.0.0
 * @package    Max_Marine\Background_Processor
 * @subpackage Max_Marine\Background_Processor/admin
 * @author     David Baumwald <david@dream-encode.com>
 */
class Max_Marine_Background_Processor_Background_Processes_List_Table extends WP_List_Table {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => __( 'Background Process', 'max-marine-background-processor' ),
				'plural'   => __( 'Background Processes', 'max-marine-background-processor' ),
				'ajax'     => false,
			)
		);
	}

	/**
	 * Retrieve background processes data from the database.
	 *
	 * @since  1.0.0
	 * @global $wpdb  Global WordPress database object.
	 * @return mixed
	 */
	public static function get_data() {
		global $wpdb;

		$sql  = "SELECT
					bp.*,
					UNIX_TIMESTAMP(datetime_queued) AS datetime_queued_timestamp,
					UNIX_TIMESTAMP(datetime_started) AS datetime_started_timestamp,
					UNIX_TIMESTAMP(datetime_completed) AS datetime_completed_timestamp,
					UNIX_TIMESTAMP(datetime_cancelled) AS datetime_cancelled_timestamp
				FROM
					{$wpdb->mmbp_background_processes} AS bp
				WHERE 1=1
					AND bp.parent_background_processes_id IS NULL";

 		// @phpcs:ignore
		if ( isset( $_POST['s'] ) && ! empty( $_POST['s'] ) ) {
			$search = filter_input( INPUT_POST, 's' );

			$sql .= " AND ( bp.processor = '" . esc_sql( wp_unslash( $search ) ) . "' )";
		}

		$sql .= ' ORDER BY';

 		// @phpcs:ignore
		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' r.' . esc_sql( wp_unslash( $_REQUEST['order'] ) ) : ' ASC';
		} else {
			$sql .= ' bp.datetime_queued DESC';
		}

		// @phpcs:ignore
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @since  1.0.0
	 * @global object  $wpdb  Global WordPress database object.
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->mmbp_background_processes} AS bp WHERE 1=1 AND bp.parent_background_processes_id IS NULL";

		if ( ! empty( $_REQUEST['s'] ) ) {
			$search = sanitize_text_field( $_REQUEST['s'] );

			$sql .= " AND ( bp.processor = '" . esc_sql( $search ) . "' )";
		}

		return $wpdb->get_var( $sql ); // @phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * Displays the search box.
	 *
	 * @since  1.0.0
	 * @param  string  $text      The 'submit' button label.
	 * @param  string  $input_id  ID attribute value for the search input field.
	 * @return void
	 */
	public function search_box( $text, $input_id ) {
		if ( empty( $_REQUEST['s'] ) && ! $this->has_items() ) {
			return;
		}

		$input_id = $input_id . '-search-input';

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
		}
		if ( ! empty( $_REQUEST['order'] ) ) {
			echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
		}
		?>

		<p class="search-box">
			<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $text ); ?>:</label>
			<input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>" />
			<?php submit_button( $text, '', '', false, array( 'id' => 'search-submit' ) ); ?>
		</p>

		<?php
	}

	/**
	 * Displayed when no records exist.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function no_items() {
		esc_html_e( 'No background processes.', 'max-marine-background-processor' );
	}

	/**
	 * Render each table row.
	 *
	 * @since  1.0.0
	 * @param  array  $item  The current item from the database.
	 * @return void
	 */
	public function single_row( $item ) {
		$row_classes = array();

		echo '<tr class="' . esc_attr( implode( ' ', $row_classes ) ) . '">';

		$this->single_row_columns( $item );

		echo '</tr>';
	}

	/**
	 * Render a column when no column specific method exist.
	 *
	 * @since  1.0.0
	 * @param  array   $item         Row data.
	 * @param  string  $column_name  Column name.
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'id':
				return esc_html( $item['id'] );

			case 'status':
				return esc_html( ucwords( $item['status'] ) );

			case 'user_id':
				return esc_html( max_marine_background_processor_get_user_name_by_id( $item['user_id'] ) );

			case 'processor':
				return esc_html( Max_Marine_Background_Processor_Background_Process_Functions::get_background_processor_label( $item['processor'] ) );

			case 'datetime_queued':
			case 'datetime_started':
			case 'datetime_completed':
			case 'datetime_cancelled':
				if ( empty( $item[ $column_name ] ) ) {
					return esc_html__( 'N/A', 'max-marine-background-processor' );
				}

				$timestamp = $item[ $column_name . '_timestamp' ];

				$date = wp_date( 'l F j, Y \a\t g:i a', $timestamp );

				if ( ! $date ) {
					return esc_html__( 'N/A', 'max-marine-background-processor' );
				}

				return esc_html( $date );

			case 'total_rows':
				return number_format( absint( $item['total_rows'] ) );
		}
	}

	/**
	 *  Associative array of columns.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'id'                 => __( 'ID', 'max-marine-background-processor' ),
			'user_id'            => __( 'Initiated By', 'max-marine-background-processor' ),
			'status'             => __( 'Status', 'max-marine-background-processor' ),
			'datetime_queued'    => __( 'Date Queued', 'max-marine-background-processor' ),
			'datetime_started'   => __( 'Date Started', 'max-marine-background-processor' ),
			'datetime_completed' => __( 'Date Completed', 'max-marine-background-processor' ),
			'datetime_cancelled' => __( 'Date Cancelled', 'max-marine-background-processor' ),
			'total_rows'         => __( 'Total Rows', 'max-marine-background-processor' ),
		);

		return $columns;
	}

	/**
	 * Columns to make sortable.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array();

		return $sortable_columns;
	}

	/**
	 * Define hidden columns.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_hidden_columns() {
		$hidden_columns = array();

		return $hidden_columns;
	}

	/**
	 * Handles data query and filter, sorting, and pagination.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function prepare_items() {
		$columns  = $this->get_columns();
		$hidden   = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$per_page = 20;

		$data = $this->get_data();

		$current_page = $this->get_pagenum();
		$total_items  = count( $data );

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
			)
		);

		$data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items           = $data;

		$this->_column_headers = $this->get_column_info();
	}
}
