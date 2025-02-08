import { __ } from '@wordpress/i18n'

import { useBackgroundProcess } from '../../hooks/useBackgroundProcess.js'

const BackgroundProcessorLabel = ( { backgroundProcessor } ) => {
	if ( ! backgroundProcessor ) {
		return null
	}

	const { backgroundProcessors } = useBackgroundProcess()

	return backgroundProcessors.find( ( bp ) => bp.key === backgroundProcessor )?.label ?? __( 'N/A', 'max-marine-background-processor' )
}

export default BackgroundProcessorLabel