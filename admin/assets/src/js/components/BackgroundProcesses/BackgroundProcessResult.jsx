import { __ } from '@wordpress/i18n'

import {
	Dashicon
} from '@wordpress/components'

import {
	useState
} from '@wordpress/element'

import { useBackgroundProcess } from '../../hooks/useBackgroundProcess.js'

import BackgroundProcessorLabel from '../BackgroundProcessor/BackgroundProcessorLabel'

const BackgroundProcessResultDetails = ( { result } ) => {
	if ( ! result?.data ) {
		return null
	}

	const { backgroundProcessors } = useBackgroundProcess()

	return (
		<div className="details">
			{ backgroundProcessors.map( ( processor ) => {
				if ( ! result.data[ processor.key ] ) {
					return
				}

				return (
					<table>
						<thead>
							<tr>
								<th colspan="2">
									<BackgroundProcessorLabel backgroundProcessor={ processor.key } />
								</th>
							</tr>
						</thead>
						<tbody>
							{ result.data[ processor.key ].length ? result.data[ processor.key ].map( ( result ) => {
								return (
									<tr>
										<td></td>
									</tr>
								)
							} ) : (
								<tr>
									<td></td>
								</tr>
							) }
						</tbody>
					</table>
				)
			} ) }
		</div>
	)
}

const BackgroundProcessResult = ( { result } ) => {
	const [ detailsOpened, setDetailsOpened ] = useState( false )

	const toggleDetails = async () => {
		setDetailsOpened( ( old ) => ! old )
	}

	return (
		<div className="result-row">
			<div className="row">
				<div className="item-id">
					{ result.item_id }
				</div>
				<div className="error-text">
					{ result.error_text }
				</div>
				<div className="icon" onClick={ toggleDetails }>
					<Dashicon icon={ detailsOpened ? 'arrow-down' : 'arrow-up' } />
				</div>
			</div>
			{ detailsOpened && <BackgroundProcessResultDetails result={ result } /> }
		</div>
	)
}

export default BackgroundProcessResult
