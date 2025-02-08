/* global MAX_MARINE_BACKGROUND_PROCESSOR */
import { BACKGROUND_PROCESS_RESULTS_PER_PAGE } from './constants'

export const fetchGetOptions = () => {
	return {
		headers: {
			'X-WP-Nonce': MAX_MARINE_BACKGROUND_PROCESSOR.NONCES.REST,
		},
	}
}

export const fetchPostOptions = ( postData ) => {
	return {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-WP-Nonce': MAX_MARINE_BACKGROUND_PROCESSOR.NONCES.REST,
		},
		body: JSON.stringify( postData ),
	}
}

export const apiGetAvailableBackgroundProcessors = async () => {
	const response = await fetch( `${ MAX_MARINE_BACKGROUND_PROCESSOR.REST_URL }/background-processes/background-processors`, fetchGetOptions() )

	return response.json()
}

export const apiRunBackgroundProcess = async ( backgroundProcessData ) => {
	const response = await fetch( `${ MAX_MARINE_BACKGROUND_PROCESSOR.REST_URL }/background-processes/background-process`, fetchPostOptions( backgroundProcessData ) )

	return response.json()
}

export const apiCancelAllBackgroundProcesses = async () => {
	const response = await fetch( `${ MAX_MARINE_BACKGROUND_PROCESSOR.REST_URL }/background-processes/cancel-all-background-processes`, fetchGetOptions() )

	return response.json()
}

export const apiCancelBackgroundProcess = async ( id ) => {
	const response = await fetch( `${ MAX_MARINE_BACKGROUND_PROCESSOR.REST_URL }/background-processes/${ id }/cancel`, fetchGetOptions() )

	return response.json()
}

export const apiRetryFailedBackgroundProcess = async ( id ) => {
	const response = await fetch( `${ MAX_MARINE_BACKGROUND_PROCESSOR.REST_URL }/background-processes/${ id }/retry-failed`, fetchGetOptions() )

	return response.json()
}

export const apiGetExistingBackgroundProcesses = async () => {
	const response = await fetch( `${ MAX_MARINE_BACKGROUND_PROCESSOR.REST_URL }/background-processes`, fetchGetOptions() )

	return response.json()
}

export const apiGetPExistingBackgroundProcess = async ( id ) => {
	console.log( { id } )
	const response = await fetch( `${ MAX_MARINE_BACKGROUND_PROCESSOR.REST_URL }/background-processes/${ id }`, fetchGetOptions() )

	return response.json()
}

export const apiGetExistingBackgroundProcessResults = async ( id, per_page = BACKGROUND_PROCESS_RESULTS_PER_PAGE, page = 0 ) => {
	const response = await fetch( `${ MAX_MARINE_BACKGROUND_PROCESSOR.REST_URL }/background-processes/${ id }/results?per_page=${ per_page }&page=${ page }`, fetchGetOptions() )

	return response.json()
}

export const apiGetExistingBackgroundProcessResultsErrors = async ( id, per_page = BACKGROUND_PROCESS_RESULTS_PER_PAGE, page = 0 ) => {
	const response = await fetch( `${ MAX_MARINE_BACKGROUND_PROCESSOR.REST_URL }/background-processes/${ id }/results/errors?per_page=${ per_page }&page=${ page }`, fetchGetOptions() )

	return response.json()
}

export const apiGetExistingBackgroundProcessResultsDetails = async ( backgroundProcessId, resultId ) => {
	const response = await fetch( `${ MAX_MARINE_BACKGROUND_PROCESSOR.REST_URL }/background-processes/${ backgroundProcessId }/results/${ resultId }/details`, fetchGetOptions() )

	return response.json()
}

export const apiGetRunningBackgroundProcesses = async () => {
	const response = await fetch( `${ MAX_MARINE_BACKGROUND_PROCESSOR.REST_URL }/background-processes/running`, fetchGetOptions() )

	return response.json()
}

export const apiDismissBackgroundProcessMessageByID = async ( id ) => {
	const response = await fetch( `${ MAX_MARINE_BACKGROUND_PROCESSOR.REST_URL }/background-processes/messages/dismiss`, fetchPostOptions( { id } ) )

	return response.json()
}

export const apiDismissAllBackgroundProcessMessages = async () => {
	const response = await fetch( `${ MAX_MARINE_BACKGROUND_PROCESSOR.REST_URL }/background-processes/messages/dismiss-all`, fetchGetOptions() )

	return response.json()
}

export const apiGetSettings = async () => {
	const response = await fetch( `${ MAX_MARINE_BACKGROUND_PROCESSOR.REST_URL }/plugin-settings`, fetchGetOptions() )

	return response.json()
}

export const apiSaveSettings = async ( settings ) => {
	const response = await fetch( `${ MAX_MARINE_BACKGROUND_PROCESSOR.REST_URL }/plugin-settings`, fetchPostOptions( { settings } ) )

	return response.json()
}
