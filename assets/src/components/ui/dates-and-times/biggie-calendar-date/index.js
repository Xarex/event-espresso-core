/**
 * External imports
 */
import classNames from 'classnames';
import PropTypes from 'prop-types';
import { isFunction } from 'lodash';
import { IconButton } from '@wordpress/components';
import { DateTime } from '@eventespresso/value-objects';

/**
 * Internal dependencies
 */
import './style.css';
import { EspressoIcon } from '../../image/espresso-icon';

/**
 * Displays a full calendar date, but REALLY BIG!!!
 *
 * @function
 * @param {string} position
 * @param {DateTime} date
 * @param {string} htmlClass
 * @param {string} headerText
 * @param {string} footerText
 * @param {Function} onEdit
 * @param {Object} editButton
 * @param {boolean} showTime
 * @return {string} rendered date
 */
const BiggieCalendarDate = ( {
	date,
	htmlClass,
	headerText,
	footerText,
	position = 'left',
	onEdit = null,
	editButton = {},
	showTime = false,
} ) => {
	htmlClass = classNames(
		'ee-biggie-calendar-date-bg',
		htmlClass,
		{
			'ee-bcd-pos-left': position === 'left',
			'ee-bcd-pos-right': position !== 'left'
		}
	);
	const editDateButton = isFunction( onEdit ) ? (
		<IconButton
			className="ee-edit-calendar-date-btn"
			onClick={ ( event ) => onEdit( event ) }
			onKeyPress={ ( event ) => onEdit( event ) }
			tooltip={ editButton.tooltip }
			labelPosition={ editButton.tooltipPosition }
			icon={ <EspressoIcon icon="calendar"/> }
		/>
	): null;
	return (
		<div className={ htmlClass }>
			{
				headerText && (
					<div className="ee-biggie-calendar-date-header">
						{ headerText }
					</div>
				)
			}
			<div className="ee-biggie-calendar-date">
				<div className="ee-bcd-weekday">
					{ date.toFormat( 'dddd' ) }
				</div>
				<div className="ee-bcd-month">
					{ date.toFormat( 'MMMM' ) }
				</div>
				<div className="ee-bcd-month-day-sep"></div>
				<div className="ee-bcd-day">
					{ date.toFormat( 'DD' ) }
				</div>
				<div className="ee-bcd-year">
					{ date.toFormat( 'YYYY' ) }
				</div>
				{
					showTime && (
						<div className="ee-bcd-time">
							{ date.toFormat( 'h:mm a' ) }
						</div>
					)
				}
			</div>
			{
				footerText && (
					<div className="ee-biggie-calendar-date-footer">
						{ footerText }
					</div>
				)
			}
			{ editDateButton }
		</div>
	);
};

BiggieCalendarDate.propTypes = {
	date: PropTypes.oneOfType( [
		PropTypes.object,
		PropTypes.instanceOf( DateTime )
	] ),
	htmlClass: PropTypes.string,
	headerText: PropTypes.oneOfType( [
		PropTypes.array,
		PropTypes.object,
		PropTypes.string,
	] ),
	footerText: PropTypes.oneOfType( [
		PropTypes.array,
		PropTypes.object,
		PropTypes.string,
	] ),
	onEdit: PropTypes.func,
	editButton: PropTypes.object,
	showTime: PropTypes.bool,
};

export default BiggieCalendarDate;