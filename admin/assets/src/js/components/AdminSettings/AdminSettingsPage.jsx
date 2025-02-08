import { __ } from '@wordpress/i18n'

import {
	PanelBody,
	PanelRow,
	Button,
	SelectControl,
	Placeholder,
	Spinner,
	__experimentalNumberControl as NumberControl,
	__experimentalHStack as HStack
} from '@wordpress/components'

import {
	Fragment
} from '@wordpress/element'

import { useSettings } from '../../hooks/useSettings'

import Notices from '../Notices/Notices'

import { LOG_LEVELS, BACKGROUND_PROCESS_ACTION_SCHEDULER_QUEUE_MODE } from '../../utils/constants'
import { capitalizeFirstLetter } from '../../utils/helpers'

const mappedLogLevels = LOG_LEVELS.map( ( level ) => ( {
	label: capitalizeFirstLetter( level ),
	value: level
} ) )

const mappedActionSchedulerQueueModes = BACKGROUND_PROCESS_ACTION_SCHEDULER_QUEUE_MODE.map( ( mode ) => ( {
	label: capitalizeFirstLetter( mode ),
	value: mode
} ) )

const AdminSettingsPage = () => {
	const {
		settingsLoaded,
        pluginLogLevel,
        updatePluginLogLevel,
        backgroundProcessActionSchedulerQueueMode,
        updateBackgroundProcessActionSchedulerQueueMode,
        backgroundProcessActionSchedulerQueueModeScheduledDelay,
        updateBackgroundProcessActionSchedulerQueueModeScheduledDelay,
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
						<h1>{ __( 'Max Marine - Background Processes', 'max-marine-background-processor' ) }</h1>
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
						<PanelBody title={ __( 'Processing' ) }>
							<PanelRow className="field-row">
								<SelectControl
									label={ __( 'Action Scheduler Queue Mode', 'max-marine-background-processor' ) }
									value={ backgroundProcessActionSchedulerQueueMode || 'async' }
									options={ mappedActionSchedulerQueueModes }
									onChange={ updateBackgroundProcessActionSchedulerQueueMode }
									__nextHasNoMarginBottom
								/>
							</PanelRow>
							{ 'scheduled' === backgroundProcessActionSchedulerQueueMode && (
								<PanelRow className="field-row">
									<NumberControl
										label={ __( 'Scheduled Mode Delay (in seconds)', 'max-marine-background-processor' ) }
										value={ backgroundProcessActionSchedulerQueueModeScheduledDelay || '1' }
										onChange={ updateBackgroundProcessActionSchedulerQueueModeScheduledDelay }
										step="1"
										min="1"
										__nextHasNoMarginBottom
									/>
								</PanelRow>
							) }
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
