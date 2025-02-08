import { __ } from '@wordpress/i18n'

import {
	Card,
	CardBody,
	CardHeader,
	Button,
	SelectControl,
	__experimentalHStack as HStack,
	__experimentalVStack as VStack,
	__experimentalSpacer as Spacer,
} from '@wordpress/components'

import {
	useState,
	useEffect,
	Fragment
} from '@wordpress/element'

import { useBackgroundProcess } from '../../hooks/useBackgroundProcess.js'

import emitter from '../../utils/emitter.js'

import { apiRunBackgroundProcess, apiCancelAllBackgroundProcesses, apiGetAvailableBackgroundProcessors } from '../../utils/api.js'
import { SHOW_CANCEL_ALL_BACKGROUND_PROCESSES_BUTTON } from '../../utils/constants.js'

const availableBackgroundProcessorsBase = [
	{
		value: '',
		label: __( 'Please select...', 'max-marine-background-processor' )
	},
]

const RunNewBackgroundProcess = () => {
	const [ availableBackgroundProcessors, setAvailableBackgroundProcessors ] = useState( availableBackgroundProcessorsBase )
	const [ backgroundProcessStarting, setBackgroundProcessStarting ]         = useState( false )
	const [ backgroundProcessStarted, setBackgroundProcessStarted ]           = useState( false )
	const [ selectedBackgroundProcessor, setSelectedBackgroundProcessor ]     = useState( false )

	const {
		processing,
		backgroundProcessorsLoaded,
		updateBackgroundProcessorsLoaded,
		backgroundProcessors,
		updateBackgroundProcessors,
	} = useBackgroundProcess()

	useEffect( () => {
		load()
	}, [] )

	useEffect( () => {
		!! processing && setBackgroundProcessStarted( false )
	}, [ backgroundProcessors ] )

	useEffect( () => {
		!! processing && setBackgroundProcessStarted( false )
	}, [ processing ] )

	const load = async () => {
		try {
			updateBackgroundProcessorsLoaded( false )

			const result = await apiGetAvailableBackgroundProcessors()

			if ( ! result ) {
				alert( __( 'Error loading background processors.', 'max-marine-background-processor' ) )
				return
			}

			const mappedProcessors = result?.data.map( ( processor ) => ( { value: processor.key, label: processor.label } ) )

			updateBackgroundProcessors( result?.data )

			setAvailableBackgroundProcessors( [
				...availableBackgroundProcessorsBase,
				...mappedProcessors,
			] )

			updateBackgroundProcessorsLoaded( true )
		} catch ( e ) {
			alert( e )
		}
	}

	const handleRunBackgroundProcessClick = async ( event ) => {
		event.preventDefault()

		try {
			setBackgroundProcessStarting( true )

			const initialBackgroundProcessData = {
				processor: selectedBackgroundProcessor
			}

			const started = await apiRunBackgroundProcess( initialBackgroundProcessData )
			if ( ! started ) {
				setBackgroundProcessStarting( false )
				return
			}

			setBackgroundProcessStarting( false )
			setBackgroundProcessStarted( true )

			emitter.emit( 'BackgroundProcessStarted' )
		} catch ( e ) {
			alert( e )
			setBackgroundProcessStarting( false )
			setBackgroundProcessStarted( false )
		}
	}

	const handleResetClick = async ( event ) => {
		event.preventDefault()

		 const cancel = await apiCancelAllBackgroundProcesses()

		 if ( ! cancel ) {
			return
		 }
	}

	return (
		<Card className="new-background-process">
			<CardHeader>
				{ __( 'Run New Background Process', 'max-marine-background-processor' ) }
			</CardHeader>
			<CardBody>
				<HStack
					alignment="center"
					justify="center"
				>
					<Spacer />
					<Spacer />
					<Spacer />
					<SelectControl
						className="background-process-select"
						label="Select background process to run..."
						value={ selectedBackgroundProcessor }
						disabled={ ! backgroundProcessorsLoaded }
						options={ availableBackgroundProcessors }
						onChange={ setSelectedBackgroundProcessor }
						__nextHasNoMarginBottom
					/>
					<Spacer />
					<Spacer />
					<Spacer />
				</HStack>
				<VStack
					alignment="center"
					justify="center"
					className="background-process-button-row"
				>
					<Spacer />
					<Button
						variant="primary"
						isLarge
						target="_blank"
						href="#"
						disabled={ backgroundProcessStarting || backgroundProcessStarted || ! selectedBackgroundProcessor }
						isBusy={ backgroundProcessStarting || backgroundProcessStarted }
						onClick={ handleRunBackgroundProcessClick }
					>
						{ backgroundProcessStarted ? __( 'Starting background process...', 'max-marine-background-processor' ) : __( 'Run Background Process', 'max-marine-background-processor' ) }
					</Button>
					{ SHOW_CANCEL_ALL_BACKGROUND_PROCESSES_BUTTON && (
						<Fragment>
							<Spacer />
							<Button
								variant="secondary"
								isLarge
								target="_blank"
								href="#"
								onClick={ handleResetClick }
							>
								{ __( 'Cancel All Background Processes', 'max-marine-background-processor' ) }
							</Button>
						</Fragment>
					) }
				</VStack>
			</CardBody>
		</Card>
	)
}

export default RunNewBackgroundProcess
