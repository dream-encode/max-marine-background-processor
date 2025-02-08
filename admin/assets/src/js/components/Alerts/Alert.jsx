import {
	Notice,
	Flex,
	FlexItem,
	FlexBlock,
	Dashicon
} from '@wordpress/components'

import {
	useState
} from '@wordpress/element'

import { useBackgroundProcess } from '../../hooks/useBackgroundProcess'
import { apiDismissBackgroundProcessMessageByID } from '../../utils/api'

import emitter from '../../utils/emitter'

const iconType = ( type ) => {
	switch ( type ) {
		case 'error':
		case 'warning':
			return 'warning'
		case 'success':
			return 'yes'
		case 'info':
		default:
			return 'info-outline'
	}
}

const AlertIcon = ( { type } ) => <Dashicon icon={ iconType( type ) } />

const Alert = ( { alert } ) => {
	const { updateAlerts } = useBackgroundProcess()

	const [ dismissing, setDismissing ] = useState( false )

	const dismissNotice = async () => {
		setDismissing( true )

		const result = await apiDismissBackgroundProcessMessageByID( alert.id )

		if ( result ) {
			emitter.emit( 'BackgroundProcessMessageDismissed' )

			updateAlerts( ( oldAlerts ) => oldAlerts.filter( ( a ) => a.id !== alert.id ) )
		}
	}

	return (
		<Notice
			className={ `background-process${ dismissing ? ' dismissing' : '' }` }
			status={ alert.type }
			isDismissible={ true }
			onRemove={ dismissNotice }
		>
			<Flex>
				<FlexItem>
					<AlertIcon type={ alert.type } />
				</FlexItem>
				<FlexBlock>
					<p>{ alert.message }</p>
				</FlexBlock>
			</Flex>
		</Notice>
	)
}

export default Alert