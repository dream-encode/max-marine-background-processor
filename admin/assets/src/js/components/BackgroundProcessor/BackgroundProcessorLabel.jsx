import { __ } from '@wordpress/i18n'

import { useBackgroundProcess } from '../../hooks/useBackgroundProcess.js'

const BackgroundProcessorLabel = ( { backgroundProcessor } ) => {
	if ( ! backgroundProcessor ) {
		return null
	}

	const { backgroundProcessors } = useBackgroundProcess()

	if ( ! backgroundProcessors ) {
		return __( 'N/A', 'max-marine-background-processor' )
	}

	return backgroundProcessors.find( ( bp ) => bp.key === backgroundProcessor )?.label ?? __( 'N/A', 'max-marine-background-processor' )
}

export default BackgroundProcessorLabel