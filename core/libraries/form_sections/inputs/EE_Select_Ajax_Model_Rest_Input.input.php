<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) { exit('No direct script access allowed'); }
/**
 * EE_Select_Ajax_Model_Rest_Input
 * select input which uses ajax and the EE4 REST API to access the EE4 models
 * for options, and 
 *
 * @package			Event Espresso
 * @subpackage
 * @author				Mike Nelson
 */
class EE_Select_Ajax_Model_Rest_Input extends EE_Form_Input_With_Options_Base{
	
	protected $_model_name;
	protected $_display_field_name;
	protected $_value_field_name;

	/**
	 * @param array | EE_Question_Option[] $answer_options
	 * @param array $input_settings
	 */
	public function __construct( $input_settings = array() ) {
		//needed input settings:
		//model name
		//query params
		//value_field_name
		//display_field_name
		//is_multi
		//select2_args
		if( isset( $input_settings[ 'multi' ] )
			&& $input_settings[ 'multi' ] === true ) {
			//todo
		} 
		$this->_model_name = EEH_Array::is_set( 
			$input_settings,
			'model_name',
			null 
		);
		$model = $this->_get_model();
		
		$query_params = EEH_Array::is_set( $input_settings, 'query_params', array( 'limit' => 10, 'caps' => EEM_Base::caps_read_admin ) );
		$this->_value_field_name = EEH_Array::is_set( $input_settings, 'value_field_name', $model->primary_key_name() );
		$this->_display_field_name = EEH_Array::is_set( $input_settings, 'display_field_name', $model->get_a_field_of_type( 'EE_Text_Field_Base' )->get_name() );
		$this->_add_validation_strategy( new EE_Model_Matching_Query_Validation_Strategy( 
				'',
				$this->_model_name,
				$query_params,
				$this->_value_field_name
			)
		);
		//get resouce endpoint
		$rest_controller = new EventEspresso\core\libraries\rest_api\controllers\model\Read();
		$rest_controller->set_requested_version( EED_Core_Rest_Api::latest_rest_api_version() );
		$url = $rest_controller->get_versioned_link_to( EEH_Inflector::pluralize_and_lower( $this->_model_name ) );
		$default_select2_args = array(
			'ajax' => array( 
				'url' => $url,
				'dataType' => 'json',
				'delay' => '250',
				'data_interface' => 'EE_Select2_REST_API_Interface',
				'data_interface_args' => array(
					'default_query_params' => (object)$query_params,
					'display_field' => $this->_display_field_name,
					'value_field' => $this->_value_field_name,
					'nonce' => wp_create_nonce( 'wp_rest' )
				),
			),
			'cache' => true,
			'width' => '100',
		);
		$this->set_display_strategy( new EE_Select2_Display_Strategy( $default_select2_args ) );
		parent::__construct( array(), $input_settings );
	}
	
	/**
	 * Before setting the default, sets the options so that the current selections 
	 * appear on initial display
	 * @param mixed $value
	 * @return boolean
	 */
	public function set_default( $value ) {
		
		$values_for_options = (array)$value;
		$value_field = $this->_get_model()->field_settings_for( $this->_value_field_name );
		$display_field = $this->_get_model()->field_settings_for( $this->_display_field_name );
		$display_values = $this->_get_model()->get_all_wpdb_results( 
			array(
				array(
					$this->_value_field_name => array( 'IN', $values_for_options )
				)
			),
			ARRAY_A,
			implode(
				',',
				array(
					$value_field->get_qualified_column() . ' AS ' . $this->_value_field_name,
					$display_field->get_qualified_column() . ' AS ' . $this->_display_field_name
				)
			)
		);
		$select_options = array();
		foreach( $display_values as $db_rows ) {
			$select_options[ $db_rows[ $this->_value_field_name ] ] = $db_rows[ $this->_display_field_name ];
		}
		$this->set_select_options( $select_options );
		return parent::set_default( $value );
	}
	
	/**
	 * Returns the model, or throws an exception if the model name provided in constructor doesn't exist
	 * @return EEM_Base
	 * @throws EE_Error
	 */
	protected function _get_model() {
		if( ! EE_Registry::instance()->is_model_name(  $this->_model_name ) ) {
			throw new EE_Error( sprintf( __( '%1$s is not a proper model name. Please provide a model name in the "model_name" form input argument', 'event_espresso'), $this->_model_name ) );
		} else {
			return EE_Registry::instance()->load_model( $this->_model_name );
		}
	}

}

// End of file EE_HABTM_Input.input.php