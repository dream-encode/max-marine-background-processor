import { __ } from '@wordpress/i18n'

import {
	PanelBody,
	PanelRow,
	Button,
	SelectControl,
	Placeholder,
	Spinner,
	__experimentalHStack as HStack
} from '@wordpress/components'

import {
	Fragment
} from '@wordpress/element'

import { useSettings } from '../../hooks/useSettings'

import Notices from '../Notices/Notices'

const AdminSettingsPage = () => {
	const {
		settingsLoaded,
        pluginLogLevel,
        updatePluginLogLevel,
		saveSettings,
		settingsSaving
    } = useSettings()

	const updateSettings = async ( event ) => {
		event.preventDefault()

		saveSettings()
	}

	return (
		<Fragment>
			<div className="settings-header">
				<div className="settings-container">
					<div className="settings-logo">
						<h1>{ __( 'Max Marine - Background Processor', 'max-marine-background-processor' ) }</h1>
					</div>
				</div>
			</div>

			<div className="settings-main">
				{ ! settingsLoaded ? (
					<Placeholder>
						<Spinner />
					</Placeholder>
				) : (
					<Fragment>
						<Notices />

						<PanelBody title={ __( 'General', 'max-marine-background-processor' ) }>
							<PanelRow className="field-row">
								<SelectControl
									label={ __( 'Log Level', 'max-marine-background-processor' ) }
									value={ pluginLogLevel || 'off' }
									options={ mappedLogLevels }
									onChange={ updatePluginLogLevel }
									__nextHasNoMarginBottom
								/>
							</PanelRow>
						</PanelBody>
						<HStack
							alignment="center"
						>
							<Button
								variant="primary"
								isBusy={ settingsSaving }
								isLarge
								target="_blank"
								href="#"
								onClick={ updateSettings }
							>
								{ __( 'Save', 'max-marine-background-processor' ) }
							</Button>
						</HStack>

					</Fragment>
				) }
			</div>
		</Fragment>
	)
}

export default AdminSettingsPage
