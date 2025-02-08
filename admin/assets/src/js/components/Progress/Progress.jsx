const Progress = ( { currentValue, maxValue, status } ) => {
	return (
		<progress id="progress-bar" className={ `progress-bar ${ status }` } value={ currentValue } max={ maxValue }>{ currentValue }%</progress>
	)
}

export default Progress