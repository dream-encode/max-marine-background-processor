import {
	__
} from '@wordpress/i18n'

import {
	useState,
	useEffect
} from '@wordpress/element'

import apiFetch from '@wordpress/api-fetch'

import {
	useDispatch
} from '@wordpress/data'

import {
	store as noticesStore
} from '@wordpress/notices'

export const useSettings = () => {
    const { createSuccessNotice, createErrorNotice } = useDispatch( noticesStore )

    const [ settingsLoaded, updateSettingsLoaded ]                                                                                   = useState( false )
    const [ settingsSaving, updateSettingsSaving ]                                                                                   = useState( false )
    const [ pluginLogLevel, updatePluginLogLevel ]                                                                                   = useState( 'off' )
    const [ backgroundProcessActionSchedulerQueueMode, updateBackgroundProcessActionSchedulerQueueMode ]                             = useState( 'scheduled' )
    const [ backgroundProcessActionSchedulerQueueModeScheduledDelay, updateBackgroundProcessActionSchedulerQueueModeScheduledDelay ] = useState( '5' )

	useEffect( () => {
        load()
    }, [] )

	const load = async () => {
        apiFetch( {
			path: '/wp/v2/settings'
		} ).then( ( settings ) => {
            updatePluginLogLevel( settings.max_marine_background_processor_plugin_settings.plugin_log_level )
            updateBackgroundProcessActionSchedulerQueueMode( settings.max_marine_background_processor_plugin_settings.background_process_action_scheduler_queue_mode )
            updateBackgroundProcessActionSchedulerQueueModeScheduledDelay( settings.max_marine_background_processor_plugin_settings.background_process_action_scheduler_queue_mode_scheduled_delay )

			updateSettingsLoaded( true )
        } )
    }

	const saveSettings = async () => {
        updateSettingsSaving( true )

        const saveResult = await apiFetch( {
            path: '/wp/v2/settings',
            method: 'POST',
            data: {
                max_marine_background_processor_plugin_settings: {
                    plugin_log_level: pluginLogLevel,
                    background_process_action_scheduler_queue_mode: backgroundProcessActionSchedulerQueueMode,
                    background_process_action_scheduler_queue_mode_scheduled_delay: backgroundProcessActionSchedulerQueueModeScheduledDelay,
                },
            },
        } )

        if ( ! saveResult ) {
            updateSettingsSaving( false )

            createErrorNotice(
                sprintf(
                    /* translators: %s: Error message. */
                    __( 'Error saving settings: %s.', 'max-marine-background-processor' ),
                    ( saveResult?.message ?? 'Unknown error' )
                )
            )

            return
        }

        updateSettingsSaving( false )

        createSuccessNotice(
            __( 'Settings saved.', 'max-marine-background-processor' )
        )
    }

    return {
		settingsLoaded,
		updateSettingsLoaded,
        pluginLogLevel,
        updatePluginLogLevel,
        backgroundProcessActionSchedulerQueueMode,
        updateBackgroundProcessActionSchedulerQueueMode,
        backgroundProcessActionSchedulerQueueModeScheduledDelay,
        updateBackgroundProcessActionSchedulerQueueModeScheduledDelay,
		saveSettings,
        settingsSaving,
        updateSettingsSaving
    }
}