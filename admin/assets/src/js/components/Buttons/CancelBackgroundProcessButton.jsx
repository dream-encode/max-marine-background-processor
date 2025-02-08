import { __ } from '@wordpress/i18n'

import {
	Button,
	Modal,
	__experimentalText as Text,
	__experimentalHStack as HStack,
	__experimentalVStack as VStack,
	__experimentalSpacer as Spacer,
} from '@wordpress/components'

import {
	useState,
	Fragment
} from '@wordpress/element'

import { apiCancelBackgroundProcess } from '../../utils/api'

import emitter from '../../utils/emitter'
import BackgroundProcessorLabel from '../BackgroundProcessor/BackgroundProcessorLabel'

const CancelBackgroundProcessButton = ( { backgroundProcess } ) => {
    const [ confirmModalOpen, updateConfirmModalOpen ] = useState( false )
	const [ cancelled, updateCancelled ]               = useState( false )
	const [ cancelling, updateCancelling ]             = useState( false )

	const cancelBackgroundProcess = async () => {
		updateConfirmModalOpen( true )
	}

	const confirmCancelBackgroundProcess = async () => {
		closeConfirmModal()
		updateCancelling( true )

		const cancel = await apiCancelBackgroundProcess( backgroundProcess.background_processes_id )

		if ( ! cancel ) {
			return
		}

		updateCancelling( false )

		emitter.emit( 'BackgroundProcessCancelled', backgroundProcess.background_processes_id )
		updateCancelled( true )
	}

	const closeConfirmModal = () => {
		updateConfirmModalOpen( false )
	}

	if ( cancelled ) {
		return null
	}

	return (
		<Fragment>
			<Button
				className="cancel-background-process-button"
				variant="secondary"
				isDestructive="true"
				isBusy={ cancelling }
				onClick={ cancelBackgroundProcess }
			>
				{ cancelling ? __( 'Cancelling...', 'max-marine-background-processor' ) : __( 'Cancel', 'max-marine-background-processor' ) }
			</Button>
			{ confirmModalOpen && (
				<Modal
					title={ __( 'Confirm Background Process Cancellation', 'max-marine-background-processor' ) }
					size="medium"
					onRequestClose={ closeConfirmModal }
				>
					<VStack>
						<Spacer />
						<HStack
							alignment="center"
							className="max-marine-background-processor-modal-body"
						>
							<Text>
								{ sprintf(
									/* translators: 1. Background process name, 2. Background process ID. */
									__( 'Are you sure you want to cancel this %1$s background process(ID: %2$d)?', 'max-marine-background-processor' ),
									<BackgroundProcessorLabel backgroundProcessor={ backgroundProcess.processor } />,
									backgroundProcess.background_processes_id
								) }
							</Text>
						</HStack>
						<Spacer />
						<Spacer />
						<HStack
							alignment="bottomRight"
							spacing="5px"
						>
							<Button
								variant="secondary"
								onClick={ closeConfirmModal }
							>
								{ __( 'Cancel', 'max-marine-background-processor' ) }
							</Button>
							<Button
								variant="primary"
								onClick={ confirmCancelBackgroundProcess }
							>
								{ __( 'Confirm', 'max-marine-background-processor' ) }
							</Button>
						</HStack>
					</VStack>
				</Modal>
			) }
		</Fragment>
	)
}

export default CancelBackgroundProcessButton