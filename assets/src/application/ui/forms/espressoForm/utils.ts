import { FieldMetaState } from 'react-final-form';
import { getIn, AnyObject } from 'final-form';

import { FieldConditions } from './types';

type ValidateStatus = '' | 'error' | 'success' | 'warning' | 'validating';

export const getValidateStatus = ({ touched, error, submitError }: FieldMetaState<any>): ValidateStatus => {
	if (touched) {
		if (!(error || submitError)) {
			return 'success';
		}
		if (submitError || error) {
			return 'error';
		}
	}
	return '';
};

export const evalFieldConditions = (conditions: FieldConditions, formData: AnyObject, fieldName: string): boolean => {
	let conditionsApply = true;
	/**
	 * The field can be inside a repeatable field/group.
	 * Thus fieldName can be "billing.addresses[1].phones[0].code"
	 * which means that it belongs to address at index 1 (in array of addresses)
	 * and phone at index 0 (in array of phones)
	 */
	const repeatableIndices = fieldName.match(/\[\d+?\]/g) || []; // ["[1]", "[0]"]

	if (Array.isArray(conditions) && conditions.length) {
		const satisfied = conditions
			.map(({ field, compare, value }) => {
				let complexKey = field;

				// field can be "billing.addresses[x].phones[x].country"
				const variableKeyRegex = /\[x\]/;
				if (variableKeyRegex.test(complexKey)) {
					repeatableIndices.forEach((entry) => {
						complexKey = complexKey.replace(variableKeyRegex, entry);
					});
					// replace any remaining variable indices with "0"
					complexKey = complexKey.replace(new RegExp(variableKeyRegex.source, 'g'), '[0]');
				}

				const result = getIn(formData, complexKey);

				switch (compare) {
					case '=':
						return result === value;
					case '!=':
						return result !== value;
					case '>':
						return result > value;
					case '>=':
						return result >= value;
					case '<':
						return result < value;
					case '<=':
						return result <= value;
					case 'EMPTY':
						return !result;
					case 'NOT_EMPTY':
						return result;
					case 'CONTAINS':
						return (typeof result === 'string' || Array.isArray(result)) && result.includes(value);
					case 'NOT_CONTAINS':
						return !(typeof result === 'string' || Array.isArray(result)) || !result.includes(value);
					case 'MATCHES':
						return new RegExp(value).test(result);
					case 'NOT_MATCHES':
						return !new RegExp(value).test(result);
					default:
						return false;
				}
			})
			.filter(Boolean);
		// whether all conditions apply
		conditionsApply = satisfied.length === conditions.length;
	}
	return conditionsApply;
};