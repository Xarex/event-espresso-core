import React, { useContext, CSSProperties } from 'react';
import { Button } from '@blueprintjs/core/lib/esm';

import { TicketContext } from '../../context/TicketProvider';
import { EditItemButtonProps } from '../../types';

const EditTicketButton: React.FC<EditItemButtonProps> = ({ position }): JSX.Element => {
	const { editors, editorState } = useContext(TicketContext);
	const onClick = (): void => editorState.openEditor(editors.editForm);

	const style: CSSProperties = {
		position: 'absolute',
		right: '.5rem',
		textAlign: 'right',
		zIndex: 1,
		...(position === 'top' && {
			top: '.5rem',
		}),
	};

	return (
		<div style={style}>
			<Button icon={'edit'} onClick={onClick} minimal />
		</div>
	);
};

export default EditTicketButton;