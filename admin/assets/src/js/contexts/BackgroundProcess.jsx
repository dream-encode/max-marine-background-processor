import {
	createContext,
	useState
} from '@wordpress/element'

export const BackgroundProcessContext = createContext()

export const BackgroundProcessProvider = ( { children } ) => {
	const [ alerts, updateAlerts ]                                                           = useState( [] )
	const [ backgroundProcessorsLoaded, updateBackgroundProcessorsLoaded ]                   = useState( false )
	const [ backgroundProcessors, updateBackgroundProcessors ]                               = useState( false )
	const [ inProgressBackgroundProcessesLoaded, updateInProgressBackgroundProcessesLoaded ] = useState( false )
	const [ processing, updateProcessing ]                                                   = useState( false )
	const [ backgroundProcesses, updateBackgroundProcesses ]                                 = useState( [] )
	const [ existingBackgroundProcesses, updateExistingBackgroundProcesses ]                 = useState( [] )
	const [ existingBackgroundProcessesLoading, updateExistingBackgroundProcessesLoading ]   = useState( true )
	const [ selectedExistingBackgroundProcess, updateSelectedExistingBackgroundProcess ]     = useState( false )
	const [ resultsLoading, updateResultsLoading ]                                           = useState( false )
	const [ results, updateResults ]                                                         = useState( {
		results: [],
		page: 0,
		total_rows: 0
	} )

	const state = {
		backgroundProcessorsLoaded,
		updateBackgroundProcessorsLoaded,
		backgroundProcessors,
		updateBackgroundProcessors,
		inProgressBackgroundProcessesLoaded,
		updateInProgressBackgroundProcessesLoaded,
		processing,
		updateProcessing,
		backgroundProcesses,
		updateBackgroundProcesses,
		existingBackgroundProcessesLoading,
		updateExistingBackgroundProcessesLoading,
		existingBackgroundProcesses,
		updateExistingBackgroundProcesses,
		selectedExistingBackgroundProcess,
		updateSelectedExistingBackgroundProcess,
		resultsLoading,
		updateResultsLoading,
		results,
		updateResults,
		alerts,
		updateAlerts,
	}

	return <BackgroundProcessContext.Provider value={ state }>
		{ children }
	</BackgroundProcessContext.Provider>
}