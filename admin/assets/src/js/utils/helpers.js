/* global MAX_MARINE_BACKGROUND_PROCESSOR, APP_VERSION */
import { __ } from '@wordpress/i18n'

/**
 * Get the current app version from package.json.
 *
 * @since  1.0.0
 * @return string
 */
export const getAppVersion = () => {
	return APP_VERSION
}

/**
 * Get a label for a background processor.
 *
 * @since  1.0.0
 * @param  string  backgroundProcessor   Background process type.
 * @param  string  backgroundProcessors  All available background processors.
 * @return string
 */
export const getBackgroundProcessorLabel = ( backgroundProcessor, backgroundProcessors ) => {
	return backgroundProcessors.find( ( bp ) => bp.key === backgroundProcessor )?.label ?? __( 'N/A', 'max-marine-background-processor' )
}

/**
 * Capitalize the first letter of a string.
 *
 * @since  0.1.2
 * @param  string  string  String.
 * @return string
 */
export const capitalizeFirstLetter = ( string ) => {
	return string.toLowerCase()
		.replace( /\b[a-z]/g, ( letter ) => {
			return letter.toUpperCase()
		} )
}

/**
 * Convert seconds to a human readable format.
 * Example: "3 days, 11, hours, 2 minutes, 58 seconds"
 *
 * @since  1.0.0
 * @param  int  seconds  Seconds remaining.
 * @return string
 */
export const secondsToDhms = ( seconds ) => {
	if ( seconds <= 0 ) {
		return false
	}

	seconds = Number(seconds)

	const d = Math.floor( seconds / ( 3600*24 ) )
	const h = Math.floor( seconds % ( 3600*24 ) / 3600 )
	const m = Math.floor( seconds % 3600 / 60 )
	const s = Math.floor( seconds % 60 )

	const dDisplay = d > 0 ? d + ( 1 === d ? ' day, ' : ' days, ') : ''
	const hDisplay = h > 0 ? h + ( 1 === h ? ' hour, ' : ' hours, ') : ''
	const mDisplay = m > 0 ? m + ( 1 === m ? ' minute, ' : ' minutes, ') : ''
	const sDisplay = s > 0 ? s + ( 1 === s ? ' second' : ' seconds') : ''

	return dDisplay + hDisplay + mDisplay + sDisplay
}

/**
 * Convert seconds to a human readable format, using toISOString.
 * Example: "2:58"
 *
 * @since  1.0.0
 * @param  int  seconds  Seconds remaining.
 * @return string
 */
export const secondsToDhmsShortIso = ( seconds ) => {
	if ( seconds <= 0 ) {
		return false
	}

	if ( seconds < 3600 ) {
		return new Date( seconds * 1000 ).toISOString().substring( 14, 19 )
	}

	return new Date( seconds * 1000 ).toISOString().substring( 11, 16 )
}

/**
 * Convert seconds to a human readable format, but a bit shorter.
 * Example: "11:02:59"
 *
 * @since  1.0.0
 * @param  int  seconds  Seconds remaining.
 * @return string
 */
export const secondsToDhmsShort = ( seconds ) => {
	if ( seconds <= 0 ) {
		return false
	}

	seconds = Number(seconds)

	const h = Math.floor( seconds % ( 3600*24 ) / 3600 )
	const m = Math.floor( seconds % 3600 / 60 )
	const s = Math.floor( seconds % 60 )

	const hDisplay = h > 0 ? h + ':' : ''
	const mDisplay = ( h > 0 ) ? zeroPadNumber( m, 2 ) + ':' : ( m > 0 ) ? m + ':': zeroPadNumber( m, 1 ) + ':'
	const sDisplay = seconds > 0 ? zeroPadNumber( s, 2 ) : ''

	return hDisplay + mDisplay + sDisplay
}

/**
 * Convert a timestamp to a human readable date format, using toLocaleString.
 *
 * @since  1.0.0
 * @param  int  timestamp  Timestamp.
 * @return string
 */
export const convertTimestampToFriendlyDate = ( timestamp ) => {
	if ( ! timestamp ) {
		return __( 'N/A', 'max-marine-background-processor' )
	}

	const date = new Date( timestamp * 1000 )

	const options = {
		dateStyle: 'full',
		timeStyle: 'short',
	}

	return date.toLocaleString( 'en-US', options )
}

/**
 * Convert a timestamp to a human readable time format, using toLocaleTimeString.
 *
 * @since  1.0.0
 * @param  int  timestamp  Timestamp.
 * @return string
 */
export const convertTimestampToFriendlyTime = ( timestamp ) => {
	if ( ! timestamp ) {
		return __( 'N/A', 'max-marine-background-processor' )
	}

	const date = new Date( timestamp * 1000 )

	const options = {
		timeStyle: 'short',
	}

	return date.toLocaleTimeString( 'en-US', options )
}

/**
 * Add leading zeroes to a number.
 *
 * @since  1.0.0
 * @param  number  num     Number.
 * @param  int     places  Total places.
 * @return string
 */
export const zeroPadNumber = ( num, places ) => {
	return String( num ).padStart( places, '0' )
}

/**
 * Calculate an estimated time remaining, using Simple Moving Average(SMA).
 *
 * @since  1.0.0
 * @param  array         times   Array of previous run times.
 * @param  int           window  Subset of times to use.
 * @param  Infinity|int  n       Number of loops.
 * @return array
 */
export const calculateBackgroundProcessEstimatedTimeRemainingUsingSMA = ( times, window = 2, n = Infinity ) => {
	if ( ! times || times.length < window ) {
		return []
	}

	let index = window - 1
	const length = times.length + 1

	const simpleMovingAverages = []

	let numberOfSMAsCalculated = 0

	while ( ++index < length && numberOfSMAsCalculated++ < n ) {
		const windowSlice = times.slice( index - window, index )
		const sum = windowSlice.reduce( ( prev, curr ) => prev + curr, 0 )

		simpleMovingAverages.push( sum / window )
	}

	return simpleMovingAverages
}

/**
 * Calculate an estimated time remaining, using Exponential Moving Average(EMA).
 *
 * @since  1.0.0
 * @param  array  batchRuns  Array of previous run times.
 * @param  int    window     Subset of times to use.
 * @return array
 */
export const calculateBackgroundProcessEstimatedTimeRemainingUsingEMA = ( batchRuns, window = 2 ) => {
	if ( ! batchRuns || batchRuns.length < window ) {
		return []
	}

	const times = batchRuns.map( ( run ) => run.total_time )

	let index = window - 1
	let previousEmaIndex = 0

	const length = times.length
	const smoothingFactor = 2 / ( window + 1 )

	const exponentialMovingAverages = []

	const [ sma ] = calculateBackgroundProcessEstimatedTimeRemainingUsingSMA( times, window, 1 )

	exponentialMovingAverages.push( sma )

	while ( ++index < length ) {
		const value = times[ index ]
		const previousEma = exponentialMovingAverages[ previousEmaIndex++ ]
		const currentEma = ( value - previousEma ) * smoothingFactor + previousEma

		exponentialMovingAverages.push( currentEma )
	}

	return exponentialMovingAverages
}

/**
 * Add a number of seconds to the current time.
 *
 * @since  1.0.0
 * @param  int  seconds  Seconds to add.
 * @return int
 */
export const addSecondsToCurrentTime = ( seconds ) => {
	let rawDate = new Date()

	rawDate = new Date( rawDate.getTime() + Number( seconds * 1000 ) )

	return rawDate.getTime() / 1000
}