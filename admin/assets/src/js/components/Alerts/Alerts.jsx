import { useBackgroundProcess } from '../../hooks/useBackgroundProcess'
import Alert from './Alert.jsx'
import DismissAllAlerts from './DismissAllAlerts.jsx'

const Alerts = () => {
	const { alerts } = useBackgroundProcess()

	return (
		<div id="max-marine-background-processor-messages" className="alerts">
			{ !! alerts && !! alerts.length && <DismissAllAlerts /> }
			{ alerts?.map( ( alert ) => (
				<Alert
					alert={ alert }
					key={ alert.id  }
				/>
			) ) }
		</div>
	)
}

export default Alerts