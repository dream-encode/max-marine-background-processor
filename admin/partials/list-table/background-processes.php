<?php
/**
 * List table for background processes.
 *
 * Mostly HTML to display the list background processes.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Max_Marine\Background_Processor
 * @subpackage Max_Marine\Background_Processor/admin/partials
 */

?>

<div class="wrap">
	<h2><?php esc_html_e( 'Background Processes', 'max-marine-background-processor' ); ?></h2>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<form method="post">
						<?php $list_table->search_box( __( 'Search by processor', 'max-marine-background-processor' ), 'post' ); ?>
						<?php $list_table->display(); // @phpcs:ignore ?>
					</form>
				</div>
			</div>
		</div>
		<br class="clear">
	</div>
</div>
