import { __ } from '@wordpress/i18n'

import {
	Button
} from '@wordpress/components'

import {
	useState
} from '@wordpress/element'

import { useBackgroundProcess } from '../../hooks/useBackgroundProcess'
import { apiDismissAllBackgroundProcessMessages } from '../../utils/api'
import emitter from '../../utils/emitter'

const DismissAllAlerts = () => {
	const { updateAlerts } = useBackgroundProcess()

	const [ dismissing, setDismissing ] = useState( false )

	const dismissAll = async () => {
		setDismissing( true )

		const result = await apiDismissAllBackgroundProcessMessages()

		if ( result ) {
			emitter.emit( 'BackgroundProcessMessageDismissedAll' )

			updateAlerts( [] )
		}
	}

	return (
		<div className="dismiss-all">
			<Button
				className={ `dismiss-all-button` }
				variant="secondary"
				size="default"
				isBusy={ dismissing }
				onClick={ dismissAll }
			>
				{ __( 'Dismiss All', 'max-marine-background-processor' ) }
			</Button>
		</div>
	)
}

export default DismissAllAlerts