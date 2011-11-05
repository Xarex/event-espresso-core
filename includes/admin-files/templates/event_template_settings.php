			<ul id="event-layout-settings">
				<li>      
					<fieldset class="template-settings a">
							<legend><?php _e('Template Settings', 'event_espresso') ?></legend>
							 <ul>
									<li>
          <label for="display_short_description_in_event_list">
            <?php _e('Display short descriptions in the event listings? <br /><em>(Be sure to use the "More..." tag in your event description)</em>','event_espresso'); ?>
          </label>
          <?php echo select_input('display_short_description_in_event_list', $values, isset($org_options['display_short_description_in_event_list']) ? $org_options['display_short_description_in_event_list'] : '', 'id="display_short_description_in_event_list"'); ?>
					  	</li>
        <?php if (function_exists('event_espresso_multi_reg_init')){?>
        
					  	<li>
          <label for="display_description_on_multi_reg_page">
            <?php _e('Display event descriptions in the multiple event registration pages?','event_espresso'); ?>
          </label>
          <?php echo select_input('display_description_on_multi_reg_page', $values, isset($org_options['display_description_on_multi_reg_page']) ? $org_options['display_description_on_multi_reg_page'] : '', 'id="display_description_on_multi_reg_page"'); ?> 
					  	</li>
        <?php } ?>
        
					  	<li>
          <label for="display_address_in_event_list">
            <?php _e('Display addresses in the event listings?','event_espresso'); ?>
          </label>
          <?php echo select_input('display_address_in_event_list', $values, isset($org_options['display_address_in_event_list']) ? $org_options['display_address_in_event_list'] : '', 'id="display_address_in_event_list"'); ?>
				  		</li>
					
					  	<li>
          <label for="display_thickbox_for_thumbs">
            <?php _e('Enable thumbnail popup display for event?','event_espresso'); ?>
          </label>
          <?php echo select_input('thunbnail_popup_lists', $values, isset($org_options['thunbnail_popup_lists']) ? $org_options['thunbnail_popup_lists'] : '', 'id="display_thickbox_for_thumbs"'); ?>
					  	</li>
        
					  	<li>
          <label for="display_address_in_regform">
            <?php _e('Display the address in the registration form? <br /><i>Disable the address if you are using the venue manager shortcodes in your event description.</i>','event_espresso'); ?>
          </label>
          <?php echo select_input('display_address_in_regform', $values, isset($org_options['display_address_in_regform']) ? $org_options['display_address_in_regform'] : '', 'id="display_address_in_regform"'); ?> 
				  		</li>
        
					  	<li>
          <label for="use_custom_post_types">
            <?php _e('Use the custom post types feature?','event_espresso'); ?>
          </label>
          <?php echo select_input('use_custom_post_types', $values, isset($org_options['use_custom_post_types']) ? $org_options['use_custom_post_types']: '', 'id="use_custom_post_types"'); ?> 
					  	</li>
							</ul>
 					</fieldset>
					</li>
				</ul>					

					
	