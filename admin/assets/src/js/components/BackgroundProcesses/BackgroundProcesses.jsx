import { __ } from '@wordpress/i18n'

import {
	PanelBody,
	PanelRow,
	__experimentalHStack as HStack,
	__experimentalSpacer as Spacer,
	__experimentalText as Text,
} from '@wordpress/components'

import {
	useEffect,
	useState,
	Fragment
} from '@wordpress/element'

import RunNewBackgroundProcess from './RunNewBackgroundProcess.jsx'
import ExistingBackgroundProcesses from './ExistingBackgroundProcesses.jsx'
import BackgroundProcessStatus from '../BackgroundProcess/BackgroundProcessStatus.jsx'
import Alerts from '../Alerts/Alerts.jsx'
import BackgroundProcessesResults from './BackgroundProcessesResults.jsx'
import PanelLoader from '../Loader/PanelLoader.jsx'

import { useBackgroundProcess } from '../../hooks/useBackgroundProcess'

import { apiGetExistingBackgroundProcesses, apiGetRunningBackgroundProcesses } from '../../utils/api'

import emitter from '../../utils/emitter'

const BackgroundProcesses = () => {
	let interval

	const { inProgressBackgroundProcessesLoaded, updateInProgressBackgroundProcessesLoaded, processing, updateProcessing, updateBackgroundProcesses, updateExistingBackgroundProcessesLoading, existingBackgroundProcesses, updateExistingBackgroundProcesses, updateAlerts, selectedExistingBackgroundProcess, updateSelectedExistingBackgroundProcess } = useBackgroundProcess()

	const [ runningBackgroundProcessesInterval, setRunningBackgroundProcessesInterval ] = useState( 30000 )

	useEffect( () => {
		load()

		loadExistingBackgroundProcesses()

		emitter.on( 'BackgroundProcessStarted', backgroundProcessStarted )
		emitter.on( 'BackgroundProcessCompleted', loadExistingBackgroundProcesses )

		return () => {
			!! interval && clearInterval( interval )

			emitter.off( 'BackgroundProcessStarted', backgroundProcessStarted )
			emitter.off( 'BackgroundProcessCompleted', loadExistingBackgroundProcesses )
		}
	}, [] )

	useEffect( () => {
		!! existingBackgroundProcesses && maybeLoadExistingBackgroundProcesses()
	}, [ existingBackgroundProcesses ] )

	useEffect( () => {
		setRunningBackgroundProcessesInterval( processing ? 10000 : 30000 )
	}, [ processing ] )

	useEffect( () => {
		interval = setInterval( load, runningBackgroundProcessesInterval )

		return () => {
			clearInterval( interval )
		}
	}, [ runningBackgroundProcessesInterval ] )

	const load = async () => {
		loadRunningBackgroundProcesses()
	}

	const loadExistingBackgroundProcesses = async () => {
		updateExistingBackgroundProcessesLoading( true )

		const getExistingBackgroundProcessesResult = await apiGetExistingBackgroundProcesses()

		if ( ! getExistingBackgroundProcessesResult ) {
			updateExistingBackgroundProcessesLoading( false )
			return
		}

		const sortedExistingBackgroundProcesses = getExistingBackgroundProcessesResult.data?.sort( ( a, b ) => b.completed_time - a.completed_time )

		updateExistingBackgroundProcesses( sortedExistingBackgroundProcesses )
		updateExistingBackgroundProcessesLoading( false )
	}

	const maybeLoadExistingBackgroundProcesses = async () => {
		const urlParams = new URLSearchParams( window.location.search )

		const backgroundProcessID = urlParams.get( 'backgroundProcessID' )

		if ( ! backgroundProcessID ) {
			return
		}

		if ( existingBackgroundProcesses.find( ( bp ) => bp.id === backgroundProcessID ) ) {
			updateSelectedExistingBackgroundProcess( backgroundProcessID )
		}
	}

	const loadRunningBackgroundProcesses = async () => {
		const getRunningBackgroundProcessesResult = await apiGetRunningBackgroundProcesses()

		if ( ! getRunningBackgroundProcessesResult ) {
			return
		}

		const { data } = getRunningBackgroundProcessesResult

		if ( data.running && data.running.length ) {
			updateBackgroundProcesses( data.running )
			updateProcessing( true )
		} else {
			updateBackgroundProcesses( [] )
			updateProcessing( false )
		}

		if ( data.messages ) {
			updateAlerts( [ ...data.messages ] )
		}

		updateInProgressBackgroundProcessesLoaded( true )
	}

	const backgroundProcessStarted = () => {
		updateProcessing( true )
	}

	return (
		<Fragment>
			<PanelBody title={ __( 'Background Processes', 'max-marine-background-processor' ) }>
				<PanelRow className="field-row">
					<HStack
						alignment="top"
					>
						<RunNewBackgroundProcess />
						<Spacer />
						<ExistingBackgroundProcesses />
					</HStack>
				</PanelRow>
			</PanelBody>

			{ ! inProgressBackgroundProcessesLoaded ? (
				<PanelLoader text={ __( 'Loading in-progress background processes...', 'max-marine-background-processor' ) } />
			) : (
				<Fragment>
					{ !! processing && <BackgroundProcessStatus /> }
				</Fragment>
			) }

			{ selectedExistingBackgroundProcess  && (
				<PanelBody className="background-process-results" title={ __( 'Background Process Results', 'max-marine-background-processor' ) } opened="true">
					<BackgroundProcessesResults />
				</PanelBody>
			) }

			<Alerts />
		</Fragment>
	)
}

export default BackgroundProcesses
