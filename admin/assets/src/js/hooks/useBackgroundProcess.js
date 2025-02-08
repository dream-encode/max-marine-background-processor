import {
	useContext
} from '@wordpress/element'

import { BackgroundProcessContext } from '../contexts/BackgroundProcess.jsx'

export const useBackgroundProcess = () => useContext( BackgroundProcessContext )