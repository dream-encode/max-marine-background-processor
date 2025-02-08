import { __ } from '@wordpress/i18n'

import {
	Fragment
} from '@wordpress/element'

import {
	__experimentalText as Text,
} from '@wordpress/components'

import { BackgroundProcessProvider } from '../../contexts/BackgroundProcess.jsx'

import BackgroundProcesses from './BackgroundProcesses.jsx'

import { getAppVersion } from '../../utils/helpers'

const BackgroundProcessesPage = () => {
	return (
		<Fragment>
			<div className="header">
				<div className="container">
					<h1>{ __( 'Max Marine - Background Processes', 'max-marine-background-processor' ) }</h1>
					<Text className="version" variant="muted">(v{ getAppVersion() })</Text>
				</div>
			</div>

			<main>
				<BackgroundProcessProvider>
					<BackgroundProcesses />
    			</BackgroundProcessProvider>
			</main>
		</Fragment>
	)
}

export default BackgroundProcessesPage