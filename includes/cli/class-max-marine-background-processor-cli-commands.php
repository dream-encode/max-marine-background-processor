<?php
/**
 * Class Max_Marine_Background_Processor_CLI_Commands
 *
 * Base class for custom WP_CLI commands.
 *
 * @since 1.0.0
 */

namespace Max_Marine\Background_Processor\Core\CLI;

use WP_CLI;

/**
 * Class Max_Marine_Background_Processor_CLI_Commands
 *
 * Base class for custom WP_CLI commands for manual migrations and fixes.
 *
 * @since 1.0.0
 */
final class Max_Marine_Background_Processor_CLI_Commands {
	/**
	 * Example command.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $args        Indexed array of arguments.
	 * @param  array  $assoc_args  Assoc array of arguments.
	 * @return void
	 */
	public function example_command( $args, $assoc_args ) {
		$dry_run = WP_CLI\Utils\get_flag_value( $assoc_args, 'dry-run' ); // @phpstan-ignore-line

		if ( $dry_run ) {
			WP_CLI::line(  // @phpstan-ignore-line
				__( 'Dry run only.', 'max-marine-background-processor' )
			);
		}
	}
}
