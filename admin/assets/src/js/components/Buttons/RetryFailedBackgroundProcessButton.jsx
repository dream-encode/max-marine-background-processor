import { __ } from '@wordpress/i18n'

import {
	Button
} from '@wordpress/components'

import {
	useState
} from '@wordpress/element'

import { apiRetryFailedBackgroundProcess } from '../../utils/api'
import emitter from '../../utils/emitter'

const RetryFailedBackgroundProcessButton = ( { backgroundProcess } ) => {
	const [ retrying, updateRetrying ] = useState( false )

	const retryFailedBackgroundProcess = async () => {
		updateRetrying( true )

		const retry = await apiRetryFailedBackgroundProcess( backgroundProcess.background_processes_id )

		if ( ! retry ) {
			updateRetrying( false )
			return
		}

		emitter.emit( 'BackgroundProcessRetry', backgroundProcess.background_processes_id )
	}

	return (
		<Button
			className="retry-failed-background-process-button"
			variant="link"
			isDestructive="true"
			isBusy={ retrying }
			disabled={ retrying }
			onClick={ retryFailedBackgroundProcess }
		>
			{ retrying ? __( 'Retrying...', 'max-marine-background-processor' ) : __( 'Retry', 'max-marine-background-processor' ) }
		</Button>
	)
}

export default RetryFailedBackgroundProcessButton