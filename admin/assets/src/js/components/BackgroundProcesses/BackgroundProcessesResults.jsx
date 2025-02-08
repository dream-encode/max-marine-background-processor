import { __ } from '@wordpress/i18n'

import {
	Button,
	PanelRow,
	__experimentalHStack as HStack
} from '@wordpress/components'

import {
	useState,
	useCallback,
	useEffect,
	Fragment
} from '@wordpress/element'

import { useBackgroundProcess } from '../../hooks/useBackgroundProcess.js'

import { BACKGROUND_PROCESS_RESULTS_PER_PAGE } from '../../utils/constants.js'

import { apiGetExistingBackgroundProcessResultsErrors } from '../../utils/api.js'

import BackgroundProcessInfo from '../BackgroundProcess/BackgroundProcessInfo.jsx'
import BackgroundProcessResult from './BackgroundProcessResult.jsx'
import PanelLoader from '../Loader/PanelLoader.jsx'

const NoResults = () => {
	return (
		<div className="no-results">
			{ __( 'No background process errors to display in this run.', 'max-marine-background-processor' ) }
		</div>
	)
}

const BackgroundProcessResults = () => {
	const { selectedExistingBackgroundProcess, resultsLoading, updateResultsLoading, results, updateResults } = useBackgroundProcess()

	const [ moreResultsLoading, setMoreResultsLoading ] = useState( false )

	useEffect( () => {
		selectedExistingBackgroundProcess && getExistingBackgroundProcessResults()
	}, [ selectedExistingBackgroundProcess ] )

	const getExistingBackgroundProcessResults = async () => {
		updateResultsLoading( true )

		const result = await apiGetExistingBackgroundProcessResultsErrors( selectedExistingBackgroundProcess )

		if ( ! result || ! result.data ) {
			updateResultsLoading( false )
			return
		}

		updateResults( result.data )
		updateResultsLoading( false )
	}

	const loadMoreExistingBackgroundProcessResults = useCallback( async () => {
		const nextPage = results.page + 1

		setMoreResultsLoading( true )

		const result = await apiGetExistingBackgroundProcessResultsErrors( selectedExistingBackgroundProcess, BACKGROUND_PROCESS_RESULTS_PER_PAGE, nextPage )

		if ( ! result || ! result.data ) {
			setMoreResultsLoading( false )
			return
		}

		updateResults( ( oldResults ) => ( {
			...oldResults,
			results: [ ...oldResults.results, ...result.data.results ],
			page: nextPage
		} ) )
		setMoreResultsLoading( false )
	}, [ selectedExistingBackgroundProcess, results.page ] )

	if ( ! selectedExistingBackgroundProcess ) {
		return null
	}

	if ( resultsLoading ) {
		return <PanelLoader text={ __( 'Results loading...', 'max-marine-background-processor' ) } />
	}

	return (
		<Fragment>
			<PanelRow className="field-row">
				<BackgroundProcessInfo backgroundProcess={ results.backgroundProcess } />
			</PanelRow>
			<PanelRow className="field-row">
				<HStack
					alignment="top"
					justify="center"
					className="previous-background-process-details"
				>
					{ results.results?.length ? (
						<div className="results">
							<div className="header">
								<div className="item-id">{ __( 'SKU', 'max-marine-background-processor' ) }</div>
								<div className="error-text">{  __( 'Error', 'max-marine-background-processor' ) }</div>
							</div>
							<div className="body">
								{ results.results.map( ( result, index ) => (
									<BackgroundProcessResult
										key={ `${ selectedExistingBackgroundProcess }-${ index }` }
										result={ result }
									/>
								) ) }
							</div>
						</div>
					) : (
						<NoResults />
					) }
				</HStack>
			</PanelRow>
			{ results.results?.length > 0 && results.results.length < results.backgroundProcess.total_rows_failed && (
				<PanelRow className="field-row">
					<HStack
						alignment="center"
						justify="center"
					>
						<Button
							variant="secondary"
							isBusy={ resultsLoading || moreResultsLoading }
							disabled={ moreResultsLoading }
							onClick={ loadMoreExistingBackgroundProcessResults }
						>
							{ moreResultsLoading ? __( 'Loading...', 'max-marine-background-processor' ) : __( 'Load more', 'max-marine-background-processor' ) }
						</Button>
					</HStack>
				</PanelRow>
			) }
		</Fragment>
	)
}

export default BackgroundProcessResults
