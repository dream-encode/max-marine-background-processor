import {
	createRoot
} from '@wordpress/element'

import domReady from '@wordpress/dom-ready'

import BackgroundProcessesPage from './components/BackgroundProcesses/BackgroundProcessesPage.jsx'

domReady( () => {
	const root = createRoot(
		document.getElementById( 'max-marine-background-processor-background-processes-page' )
	)

	root.render( <BackgroundProcessesPage /> )
} )
