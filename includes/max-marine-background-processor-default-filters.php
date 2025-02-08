<?php
/**
 * Default filters for the plugin.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Max_Marine_Background_Processor
 */

namespace Max_Marine\Background_Processor\Core;

// Action hooks.
add_action( 'max-marine/background-processor/queue-new-background-process', array( 'Max_Marine\Background_Processor\Core\Background_Process\Max_Marine_Background_Processor_Background_Process_Functions', 'queue_new_background_process' ) );
add_action( 'max-marine/background-processor/cancel-background-process', array( 'Max_Marine\Background_Processor\Core\Background_Process\Max_Marine_Background_Processor_Background_Process_Functions', 'cancel_background_process' ) );

// Process.
add_action( 'max-marine/background-processor/process-background-process', array( 'Max_Marine\Background_Processor\Core\Background_Process\Max_Marine_Background_Processor_Background_Process_Runner', 'run' ) );

// Process run results.
add_action( 'max-marine/background_processor/save-process-run-results', array( 'Max_Marine\Background_Processor\Core\Background_Process\Max_Marine_Background_Processor_Dummy_Processor', 'process_run_results' ) );

// Processor.
add_filter( 'max-marine/background-processor/background-processors', array( 'Max_Marine\Background_Processor\Core\Background_Process\Max_Marine_Background_Processor_Background_Process_Functions', 'add_background_processor' ) );

// Action Scheduler.
add_action( 'action_scheduler_failed_execution', array( 'Max_Marine\Background_Processor\Core\Background_Process\Max_Marine_Background_Processor_Background_Process_Runner', 'handle_run_failure' ), 10, 2 );
