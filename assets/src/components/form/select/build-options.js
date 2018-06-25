/**
 * External dependencies
 */
import { reduce, isFunction } from 'lodash';

/**
 * Internal dependencies
 */
import { dateTimeModel } from '../../../data/model';

/**
 * A default map used for mapping options for select.
 * @type {Object}
 */
const DEFAULT_MODEL_OPTIONS_MAP = {
	event: {
		label: 'EVT_name',
		value: 'EVT_ID',
	},
	datetime: {
		label: ( entity ) => {
			return dateTimeModel.prettyDateFromDateTime( entity );
		},
		value: 'DTT_ID',
	},
	ticket: {
		label: 'TKT_name',
		value: 'TKT_ID',
	},
};

/**
 * Receives an array of event entities and returns an array of simple objects
 * that can be passed along to the options array used for the WordPress
 * SelectControl component.
 *
 * @param { Array } entities
 * @param { string } modelName
 * @param { Object } optionsEntityMap
 * @return { Array }  Returns an array of simple objects formatted for any
 * select control that receives its options in the format of an array of objects
 * with label and value keys.
 */
const buildOptions = (
	entities,
	modelName,
	optionsEntityMap = DEFAULT_MODEL_OPTIONS_MAP,
) => {
	const MAP_FOR_MODEL = optionsEntityMap[ modelName ] ? optionsEntityMap[ modelName ] : false;
	return entities && MAP_FOR_MODEL ?
		reduce( entities, function( options, entity ) {
			const label = isFunction( MAP_FOR_MODEL.label ) ?
				MAP_FOR_MODEL.label( entity ) :
				entity[ MAP_FOR_MODEL.label ];
			const value = isFunction( MAP_FOR_MODEL.value ) ?
				MAP_FOR_MODEL.value( entity ) :
				entity[ MAP_FOR_MODEL.value ];
			if ( label && value ) {
				options.push( { label, value } );
			}
			return options;
		}, [] ) :
		[];
};

export default buildOptions;
