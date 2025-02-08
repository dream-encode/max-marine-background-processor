import { __ } from '@wordpress/i18n'

import {
	Icon,
	PanelBody,
	PanelRow,
	__experimentalHStack as HStack,
	__experimentalVStack as VStack,
	__experimentalText as Text,
} from '@wordpress/components'

import {
	useState,
	useEffect
} from '@wordpress/element'

import emitter from '../../utils/emitter.js'

import { useBackgroundProcess } from '../../hooks/useBackgroundProcess.js'

import Progress from '../Progress/Progress.jsx'

import BackgroundProcessInfo from './BackgroundProcessInfo.jsx'
import BackgroundProcessStatusSummary from './BackgroundProcessStatusSummary.jsx'
import CancelBackgroundProcessButton from '../Buttons/CancelBackgroundProcessButton.jsx'
import PanelLoader from '../Loader/PanelLoader.jsx'
import BackgroundProcessorLabel from '../BackgroundProcessor/BackgroundProcessorLabel.jsx'

const BackgroundProcessStatusInfo = ( { backgroundProcess } ) => {
	const [ showDetails, setShowDetails ] = useState( false )
	const [ isCancelled, setIsCancelled ] = useState( false )

	useEffect( () => {
		emitter.on( 'BackgroundProcessCancelled', backgroundProcessCancelled )

		return () => {
			emitter.off( 'BackgroundProcessCancelled', backgroundProcessCancelled )

			backgroundProcessCompleted()
		}
	}, [] )

	if ( ! backgroundProcess ) {
		return null
	}

	const toggleShowDetails = () => {
		setShowDetails( ( current ) => ! current )
	}

	const backgroundProcessCancelled = ( backgroundProcessId ) => {
		if ( backgroundProcessId === backgroundProcess.background_processes_id ) {
			setIsCancelled( true )
		}
	}

	const backgroundProcessCompleted = () => {
		emitter.emit( 'BackgroundProcessCompleted' )
	}

	return (
		<div className={ `background-process child${ isCancelled ? ' cancelled' : '' }` }>
			<HStack alignment="left">
				<VStack>
					<HStack alignment="left">
						<Text className="background-process-name">
							<BackgroundProcessorLabel backgroundProcessor={ backgroundProcess.processor } />
						</Text>
						{ !! backgroundProcess.background_processes_id && (
							<Text className="background-process-id" variant="muted">
							{ `#${backgroundProcess.background_processes_id}` }
						</Text>
						) }
					</HStack>
					<Progress
						currentValue={ backgroundProcess.percent_complete ?? 0 }
						maxValue={ 100 }
						status={ backgroundProcess.status }
					/>
					<BackgroundProcessStatusSummary backgroundProcess={ backgroundProcess } />
				</VStack>
				{ !! backgroundProcess.background_processes_id && <DetailsIcon backgroundProcess={ backgroundProcess } onClick={ toggleShowDetails } /> }
				{ !! backgroundProcess.background_processes_id && <CancelBackgroundProcessButton backgroundProcess={ backgroundProcess } /> }
			</HStack>
			<div className={ `details ${ showDetails ? 'open' : 'closed' }` }>
				<BackgroundProcessInfo backgroundProcess={ backgroundProcess } />
			</div>
		</div>
	)
}

const DetailsIcon = ( props ) => {
	if ( ( 'pending' === props.backgroundProcess.status || 'queued' === props.backgroundProcess.status ) ) {
		return null
	}

	return (
		<Icon className="background-process-details-icon" icon="menu" size={ 28 } onClick={ !! props.onClick && props.onClick } />
	)
}

const BackgroundProcessStatus = () => {
	const { backgroundProcesses } = useBackgroundProcess()

	useEffect( () => {
		return () => {
			emitter.emit( 'BackgroundProcessComplete' )
		}
	}, [] )

	return (
		<PanelBody className="background-process-status" title={ __( 'In-Progress', 'max-marine-background-processor' ) }>
			<PanelRow className="field-row">
				{ !! backgroundProcesses && backgroundProcesses.length ? backgroundProcesses.map( ( runningBackgroundProcess ) => {
					return <BackgroundProcessStatusInfo key={ runningBackgroundProcess.background_processes_id } backgroundProcess={ runningBackgroundProcess } />
				} ) : <PanelLoader text={ __( 'Background process starting...', 'max-marine-background-processor' ) } /> }
			</PanelRow>
		</PanelBody>
	)
}

export default BackgroundProcessStatus