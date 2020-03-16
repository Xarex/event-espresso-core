import React from 'react';
import classNames from 'classnames';
import { __ } from '@wordpress/i18n';

// import { addZebraStripesOnMobile } from '@appLayout/espressoTable/utils';
import { ResponsiveTable } from '@appLayout/espressoTable';
import ticketsListTableHeader from './ticketsListTableHeader';
import ticketsListTableRow from './ticketsListTableRow';
import { useTicketItem } from '@edtrServices/apollo/queries';
import { TicketsListViewProps } from '../types';

import './styles.scss';

// const noZebraStripe = ['row', 'stripe', 'name', 'actions'];

/**
 * EditorTicketsListView
 * Displays tickets in a standard list table like view
 */
const TableView: React.FC<TicketsListViewProps> = ({ className, filterState, entities: tickets }) => {
	const { displayStartOrEndDate } = filterState;

	const formRows = tickets.map((ticket) => {
		const columns = ticketsListTableRow({ displayStartOrEndDate, ticket });
		return columns;
	});

	const headerRows = ticketsListTableHeader(displayStartOrEndDate);
	const tableClassName = classNames(className, 'ee-tickets-list-list-view');

	return (
		<ResponsiveTable
			bodyRows={formRows}
			className={{ tableClassName }}
			headerRows={[headerRows]}
			metaData={{
				tableId: 'date-entities-list-view',
				tableCaption: __('Tickets'),
			}}
		/>
	);
};

export default TableView;
