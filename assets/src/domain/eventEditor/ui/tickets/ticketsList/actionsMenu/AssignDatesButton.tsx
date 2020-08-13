import React from 'react';
import { __ } from '@wordpress/i18n';

import { Calendar } from '@appDisplay/icons';
import { IconButton } from '@application/ui/input';
import { EntityListItemProps } from '@appLayout/entityList';
import ItemCount from '@appDisplay/ItemCount';
import { useRelatedDatetimes } from '@edtrServices/apollo/queries';
import useTicketAssignmentsManager from '@edtrUI/ticketAssignmentsManager/useTicketAssignmentsManager';
import { TypeName } from '@appServices/apollo/status';
import withIsLoaded from '@sharedUI/hoc/withIsLoaded';
import { Ticket } from '@edtrServices/apollo';
import { TooltipProps } from '@infraUI/display';
import { useMemoStringify } from '@application/services/hooks';

const AssignDatesButton: React.FC<EntityListItemProps<Ticket>> = React.memo(({ entity }) => {
	const { ModalContainer, onOpen, ...disclosure } = useTicketAssignmentsManager();

	const relatedDatetimes = useRelatedDatetimes({
		entity: 'tickets',
		entityId: entity.id,
	});

	const count = relatedDatetimes.length;

	const title = count
		? __('Number of related dates')
		: __(
				'There are no event dates assigned to this ticket. Please click the calendar icon to update the assignments.'
		  );

	const tooltipProps = useMemoStringify<TooltipProps>({ placement: 'left' });

	return (
		<>
			<ItemCount count={count} emphasizeZero title={title} zeroCountChar='!'>
				<IconButton
					borderless
					icon={Calendar}
					onClick={onOpen}
					tooltip={__('assign dates')}
					tooltipProps={tooltipProps}
				/>
			</ItemCount>
			<ModalContainer assignmentType='forTicket' entity={entity} {...disclosure} />
		</>
	);
});

export default withIsLoaded<EntityListItemProps<Ticket>>(TypeName.datetimes, ({ entity, loaded }) => {
	/* Hide TAM unless dates are loaded */
	return loaded && <AssignDatesButton entity={entity} />;
});