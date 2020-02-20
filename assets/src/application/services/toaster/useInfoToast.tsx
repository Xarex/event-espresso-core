import { useCallback } from 'react';
import { notification } from 'antd';
import { ArgsProps } from 'antd/lib/notification';

import { InfoToast, InfoToastCallback } from './types';

const useInfoToast: InfoToast = (toaster, hash) =>
	useCallback<InfoToastCallback>(
		({ message = '', placement = 'bottomRight', ...rest }) => {
			if (message) {
				const args: ArgsProps = {
					...rest,
					message,
					placement,
				};

				notification.info(args);
			}
		},
		[toaster, hash]
	);

export default useInfoToast;
