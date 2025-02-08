import { __ } from '@wordpress/i18n'

import {
	__experimentalText as Text,
} from '@wordpress/components'

import CopyToClipboard from '../Common/CopyToClipboard.jsx'

const BackgroundProcessInfo = ( { backgroundProcess } ) => {
	if ( ! backgroundProcess ) {
		return null
	}

	return (
		<div className="background-process-info">
			{ !! backgroundProcess.completed_time && (
				<div className="row">
					<Text className="key">{ __( 'Background process ID', 'max-marine-background-processor' ) }</Text>
					<div className="value">{ backgroundProcess.id } <CopyToClipboard content={ window.location } icon="admin-links" /></div>
				</div>
			) }
			{ !! backgroundProcess.queued_time && ! backgroundProcess.completed_time && (
			<div className="row">
				<Text className="key">{ __( 'Time Queued', 'max-marine-background-processor' ) }</Text>
				<Text className="value">{ backgroundProcess.queued_time_formatted }</Text>
			</div>
			) }
			{ !! backgroundProcess.start_time && (
				<div className="row">
					<Text className="key">{ __( 'Time Started', 'max-marine-background-processor' ) }</Text>
					<Text className="value">{ backgroundProcess.start_time_formatted }</Text>
				</div>
			) }
			{ !! backgroundProcess.completed_time && (
				<div className="row">
					<Text className="key">{ __( 'Time Completed', 'max-marine-background-processor' ) }</Text>
					<Text className="value">{ backgroundProcess.completed_time_formatted }</Text>
				</div>
			) }
			{ !! backgroundProcess.last_run_time && ! backgroundProcess.completed_time && (
				<div className="row">
					<Text className="key">{ __( 'Last Run', 'max-marine-background-processor' ) }</Text>
					<Text className="value">{ backgroundProcess.last_run_time_formatted }</Text>
				</div>
			) }
			{ !! backgroundProcess.total_rows && (
				<div className="row">
					<Text className="key">{ __( 'Items Processed', 'max-marine-background-processor' ) }</Text>
					<Text className="value">{ Number( backgroundProcess.total_rows_processed ).toLocaleString() } / { Number( backgroundProcess.total_rows ).toLocaleString() }</Text>
				</div>
			) }
			{ !! backgroundProcess.total_rows_failed && (
				<div className="row">
					<Text className="key">{ __( 'Total Errors', 'max-marine-background-processor' ) }</Text>
					<Text className="value">{ Number( backgroundProcess.total_rows_failed ).toLocaleString() }</Text>
				</div>
			) }
		</div>
	)
}

export default BackgroundProcessInfo