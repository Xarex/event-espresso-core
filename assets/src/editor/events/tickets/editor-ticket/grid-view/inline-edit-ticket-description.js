/**
 * External imports
 */
import classNames from 'classnames';
import { useMemo } from '@wordpress/element';
import { InlineEditInput } from '@eventespresso/components';
import { __ } from '@eventespresso/i18n';

const InlineEditTicketDescription = ( {
	ticket,
	showDesc,
	wrapperElement,
} ) => useMemo(
	() => {
		const WrapperElement = wrapperElement ? wrapperElement : 'div';
		const htmlClass = classNames(
			'ee-editor-ticket-desc-div',
			{ 'ee-ticket-desc-excerpt': showDesc === 'excerpt' }
		);
		return (
			<WrapperElement className={ htmlClass }>
				<InlineEditInput
					htmlId={ `ee-editor-ticket-desc-${ ticket.id }` }
					type="textarea"
					value={ ticket.description }
					onChange={ ( description ) => {
						ticket.description = description;
						return description;
					} }
					label={ __( 'Ticket Description', 'event_espresso' ) }
				/>
			</WrapperElement>
		);
	},
	[ ticket.id, ticket.description, showDesc, wrapperElement ]
);

export default InlineEditTicketDescription;
