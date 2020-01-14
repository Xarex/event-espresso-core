import { useApolloClient } from '@apollo/react-hooks';
import { renderHook, act } from '@testing-library/react-hooks';

import useCacheRehydration from '../../initialization/useCacheRehydration';
import useUpdateDatetimeList from '../useUpdateDatetimeList';
import useDatetimeQueryOptions from '../../queries/datetimes/useDatetimeQueryOptions';
import useDatetimes from '../../queries/datetimes/useDatetimes';
import useDatetimeIds from '../../queries/datetimes/useDatetimeIds';
import { ApolloMockedProvider } from '../../../context';

describe('useUpdateDatetimeList', () => {
	it('checks for datetimes cache update', async () => {
		const wrapper = ApolloMockedProvider();
		const { result } = renderHook(
			() => {
				useCacheRehydration();
				return {
					queryOptions: useDatetimeQueryOptions(),
					datetimelist: useDatetimes(),
					cacheUpdater: useUpdateDatetimeList(),
					client: useApolloClient(),
				};
			},
			{
				wrapper,
			}
		);

		const datetimelist = result.current.datetimelist;

		const datetime = { ...datetimelist[0], id: datetimelist[0].id + '-alpha' };

		// add datetime to the list.
		const nodes = [...datetimelist, datetime];

		act(() => {
			result.current.cacheUpdater({
				...result.current.queryOptions,
				data: {
					espressoDatetimes: {
						__typename: 'EspressoRootQueryDatetimesConnection',
						nodes,
					},
				},
			});
		});

		const cache = result.current.client.extract();
		const { result: cacheResult } = renderHook(
			() => {
				const client = useApolloClient();
				// restore the cache from previous render
				client.restore(cache);
				return useDatetimeIds();
			},
			{
				wrapper,
			}
		);

		const cachedDatetimeIds = cacheResult.current;

		expect(cachedDatetimeIds.length).toBe(datetimelist.length + 1);

		expect(cachedDatetimeIds).toContain(datetime.id);
	});
});
