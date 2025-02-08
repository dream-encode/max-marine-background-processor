import { __, sprintf } from '@wordpress/i18n'

import {
	Card,
	CardBody,
	CardHeader,
	SelectControl,
	__experimentalHStack as HStack,
	__experimentalVStack as VStack,
	__experimentalSpacer as Spacer
} from '@wordpress/components'

import {
	useMemo
} from '@wordpress/element'

import { SHOW_BACKGROUND_PROCESS_ID_IN_PREVIOUS_BACKGROUND_PROCESSES_SELECT } from '../../utils/constants.js'

import Loader from '../Loader/Loader.jsx'

import { useBackgroundProcess } from '../../hooks/useBackgroundProcess'
import { getBackgroundProcessorLabel } from '../../utils/helpers.js'

const getExistingBackgroundProcessLabel = ( backgroundProcess, backgroundProcessors ) => {
	return sprintf(
		/* translators: 1: Processor name, 2: Completed time formatted, 3: Total rows processed, 4: Total errors, 5. Conditionally shown background process ID. */
		__( '%1$s - %2$s (Total items processed: %3$s, Total errors: %4$s)%5$s', 'max-marine-background-processor' ),
		getBackgroundProcessorLabel( backgroundProcess.processor, backgroundProcessors ),
		backgroundProcess.completed_time_formatted,
		Number( backgroundProcess.total_rows_processed ).toLocaleString(),
		Number( backgroundProcess.total_rows_failed ).toLocaleString(),
		( SHOW_BACKGROUND_PROCESS_ID_IN_PREVIOUS_BACKGROUND_PROCESSES_SELECT ) ? `(ID: ${ backgroundProcess.id })` : ''
	)
}

const ExistingBackgroundProcesses = () => {
	const { backgroundProcessors, existingBackgroundProcessesLoading, existingBackgroundProcesses, selectedExistingBackgroundProcess, updateSelectedExistingBackgroundProcess } = useBackgroundProcess()

	const existingBackgroundProcessesOptions = useMemo( () => ( [
		{
			label: __( 'Please select...', 'max-marine-background-processor' ),
			value: ''
		},
		...existingBackgroundProcesses.map( ( backgroundProcess ) => (
			{
				label: getExistingBackgroundProcessLabel( backgroundProcess, backgroundProcessors ),
				value: backgroundProcess.id
			}
		) )
	] ), [ existingBackgroundProcesses ] )

	const existingBackgroundOnChange = ( value ) => {
		updateSelectedExistingBackgroundProcess( value )

		if ( 'URLSearchParams' in window ) {
			const url = new URL( window.location )

			url.searchParams.set( 'backgroundProcessID', value )

			history.pushState( null, '', url )
		}
	}

	if ( existingBackgroundProcessesLoading ) {
		return <Loader />
	}

	return (
		<Card className="existing-background-processes">
			<CardHeader>
				{ __( 'Existing Background Processes', 'max-marine-background-processor' ) }
			</CardHeader>
			<CardBody>
				<VStack>
					<HStack
						alignment="center"
						justify="center"
					>
						<Spacer />
						<SelectControl
							className="background-process-select"
							label="Select a previous background process..."
							value={ selectedExistingBackgroundProcess }
							options={ existingBackgroundProcessesOptions }
							onChange={ existingBackgroundOnChange }
							disabled={ existingBackgroundProcessesLoading }
							__nextHasNoMarginBottom
						/>
						<Spacer />
					</HStack>
					<Spacer />
				</VStack>
			</CardBody>
		</Card>
	)
}

export default ExistingBackgroundProcesses
