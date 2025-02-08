import { __, sprintf } from '@wordpress/i18n'

import {
	__experimentalText as Text,
} from '@wordpress/components'

import {
	useState,
	useEffect,
	useCallback
} from '@wordpress/element'

import { addSecondsToCurrentTime, convertTimestampToFriendlyTime, secondsToDhmsShort } from '../../utils/helpers.js'
import RetryFailedBackgroundProcessButton from '../Buttons/RetryFailedBackgroundProcessButton.jsx'

const showEstimatedCompletedTime = true

const BackgroundProcessStatusSummary = ( { backgroundProcess } ) => {
	if ( ! backgroundProcess ) {
		return null
	}

	const [ rawSecondsRemaining, setRawSecondsRemaining ]                         = useState( false )
	const [ intervalSecondsRemaining, setIntervalSecondsRemaining ]               = useState( false )
	const [ formattedTimeRemaining, setFormattedTimeRemaining ]                   = useState( __( 'Calculating time remaining...', 'max-marine-background-processor' ) )
	const [ estimatedCompletedTime, setEstimatedCompletedTime ]                   = useState( false )
	const [ formattedEstimatedCompletedTime, setFormattedEstimatedCompletedTime ] = useState( false )

	let interval

	useEffect( () => {
		if ( backgroundProcess.percent_complete < 100 ) {
			getTimeRemaining()
		}

		return () => clearInterval( interval )
	}, [] )

	useEffect( () => {
		if ( backgroundProcess.percent_complete < 100 ) {
			getTimeRemaining()
		}
	}, [ backgroundProcess.percent_complete ] )

	useEffect( () => {
		if ( !! rawSecondsRemaining ) {
			updateTimeRemaining()

			setIntervalSecondsRemaining( rawSecondsRemaining )

			setEstimatedCompletedTime( addSecondsToCurrentTime( rawSecondsRemaining ) )
		}
	}, [ rawSecondsRemaining ] )

	useEffect( () => {
		!! interval && clearInterval( interval )

		if ( !! intervalSecondsRemaining ) {
			interval = setInterval( updateTimeRemaining, 1000 )
		}

		updateFormattedTimeRemaining()

		return () => clearInterval( interval )
	}, [ intervalSecondsRemaining ] )

	useEffect( () => {
		updateFormattedEstimatedCompletedTime()
	}, [ estimatedCompletedTime ] )

	const getTimeRemaining = () => {
		if ( ! backgroundProcess.percent_complete || ! backgroundProcess.queued_time || ! backgroundProcess.background_process_runs || backgroundProcess.background_process_runs.length < 1 || backgroundProcess.percent_complete >= 100 ) {
			return
		}

		const secondsSinceStartTime = Date.now()/1000 - Number( backgroundProcess.queued_time )

		const decimalComplete = backgroundProcess.percent_complete / 100

		const totalSeconds = secondsSinceStartTime / decimalComplete

		const remaining = totalSeconds - secondsSinceStartTime

		setRawSecondsRemaining( remaining )
	}

	const updateTimeRemaining = () => {
		if ( intervalSecondsRemaining <= 0 ) {
			clearInterval( interval )

			return
		}

		setIntervalSecondsRemaining( ( old ) => old - 1 )
	}

	const updateFormattedTimeRemaining = useCallback( () => {
		if ( backgroundProcess.percent_complete >= 100 ) {
			setFormattedTimeRemaining( __( 'A few seconds remaining', 'max-marine-background-processor' ) )

			clearInterval( interval )
			return
		}

		if ( intervalSecondsRemaining < 0 ) {
			setFormattedTimeRemaining( __( 'Calculating time remaining...', 'max-marine-background-processor' ) )

			return
		}

		if ( !! intervalSecondsRemaining ) {
			setFormattedTimeRemaining( sprintf(
				/* translators: %s: Time remaining. */
				__( 'Approx. %s remaining', 'max-marine-background-processor' ),
				secondsToDhmsShort( intervalSecondsRemaining )
			) )
		}
	}, [ intervalSecondsRemaining ] )

	const updateFormattedEstimatedCompletedTime = useCallback( () => {
		if ( ! estimatedCompletedTime ) {
			setFormattedEstimatedCompletedTime( false )

			return
		}

		setFormattedEstimatedCompletedTime( ` (${ convertTimestampToFriendlyTime( estimatedCompletedTime ) })` )
	}, [ intervalSecondsRemaining ] )

	switch ( backgroundProcess.status ) {
		case 'pending':
		case 'queued':
			return (
				<div className="summary">
					<Text variant="muted">{ __( 'Waiting...', 'max-marine-background-processor' ) }</Text>
				</div>
			)

		case 'processing':
			if ( backgroundProcess.percent_complete >= 100 ) {
				return (
					<div className="summary">
						<Text variant="muted">{ __( 'Completing background process', 'max-marine-background-processor' ) }</Text>
					</div>
				)
			}

			return (
				<div className="summary">
					<Text variant="muted">{ __( 'In-progress', 'max-marine-background-processor' ) }</Text>
					<Text variant="muted">|</Text>
					<Text variant="muted">
						{ sprintf(
							/* translators: %s: Percent complete. */
							__( '%s%% complete', 'max-marine-background-processor' ),
							parseFloat( backgroundProcess.percent_complete ).toFixed( 1 )
						) }
					</Text>
					<Text variant="muted">|</Text>
					<Text variant="muted">
						{ formattedTimeRemaining }{ showEstimatedCompletedTime && formattedEstimatedCompletedTime }
					</Text>
				</div>
			)

		case 'complete':
			return (
				<div className="summary">
					<Text variant="muted">{ __( 'Complete', 'max-marine-background-processor' ) }</Text>
				</div>
			)

		case 'failed':
			return (
				<div className="summary">
					<Text variant="muted" isDestructive="true">{ __( 'Failed!', 'max-marine-background-processor' ) }</Text>
					<Text variant="muted">|</Text>
					<RetryFailedBackgroundProcessButton backgroundProcess={ backgroundProcess } />
				</div>
			)
	}
}

export default BackgroundProcessStatusSummary