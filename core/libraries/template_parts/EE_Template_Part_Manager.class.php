<?php
//namespace EventEspresso\core\libraries\templates;

if ( ! defined( 'EVENT_ESPRESSO_VERSION' ) ) {
	exit( 'No direct script access allowed' );
}
/**
 * Class EE_Template_Part_Manager
 *
 * class for managing template parts and controlling the order in which they are applied to content
 *
 * @package               Event Espresso
 * @subpackage            core
 * @author                Brent Christensen
 * @since                 $VID:$
 *
 */
class EE_Template_Part_Manager {

	/**
	 * @param EE_Template_Part_PriorityQueue $template_parts
	 */
	protected $template_parts;

	/**
	 * @param int $event_desc_priority
	 */
	protected $event_desc_priority;

	/**
	 * @param array $priorities
	 */
	protected $priorities = array();

	/**
	 * @param int $first_template_part_priority
	 */
	protected $first_template_part_priority = 100;

	/**
	 * @param int $last_template_part_priority
	 */
	protected $last_template_part_priority = 100;



	/**
	 *    class constructor
	 */
	public function __construct() {
		$this->template_parts = new EE_Template_Part_PriorityQueue();
	}



	/**
	 *    add_template_part
	 *
	 *    used for setting the details about a particular template part
	 *
	 * @param string $name     - just a simple string identifier - do NOT use 'event'
	 * @param string $label    - template part label displayed in admin
	 * @param string $template - name or path of template to be used by EEH_Template::locate_template()
	 * @param int $priority    - order in which template parts should be applied
	 */
	public function add_template_part( $name, $label, $template, $priority ) {
		// SplPriorityQueue doesn't play nice with multiple items having the same priority
		// so if the incoming priority is already occupied, then let's increment it by one,
		// and then pass everything back into this method and try again with the new priority
		if ( isset( $priorities[  $priority ] ) ) {
			$priority++;
			$this->add_template_part( $name, $label, $template, $priority );
			return;
		}
		// kk now we can mark this priority as being occupied
		$priorities[ $priority ] = true;
		// create the template part and add to the queue
		$this->template_parts->insert(
			new EE_Template_Part( $name, $label, $template, $priority ),
			$priority
		);
		if ( $name == 'event' ) {
			$this->event_desc_priority = $priority;
		}
		// determine first and last priorities
		$this->first_template_part_priority = min( $this->first_template_part_priority, $priority );
		$this->last_template_part_priority = max( $this->last_template_part_priority, $priority );
	}



	/**
	 *    apply_template_part_filters
	 *
	 * 	adds template parts to the supplied content
	 * 	according to the details set when the template parts were added
	 *
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public function apply_template_part_filters( $content = '' ) {
		$counter = 0;
		$total_count = $this->template_parts->count();
		$this->template_parts->rewind();
		/** @type EE_Template_Part $first_template_part */
		$first_template_part = $this->template_parts->top();
		// loop through template parts and add template content
		while ( $this->template_parts->valid() ) {
			$counter++;
			if ( $counter === 1 ) {
				// due to the way that filters work, we don't want to apply the first template part
				// until the inner template parts have been processed and before the end
				$this->template_parts->next();
				continue;
			}
			if  ( $counter === $total_count ) {
				// process the first template part before the last, now that all of the inner parts are done
				$content = $this->position_template_part(
					$content,
					$first_template_part->template(),
					$first_template_part->priority()
				);
			}
			$content = $this->position_template_part(
				$content,
				$this->template_parts->current()->template(),
				$this->template_parts->current()->priority()
			);
			$this->template_parts->next();
		}
		return $content;
	}



	/**
	 *    display_template_parts
	 *
	 * just for debugging purposes
	 *
	 * @access    public
	 * @return string
	 */
	public function display_template_parts() {
		if ( WP_DEBUG ) {
			foreach ( $this->template_parts as $template_part ) {
				EEH_Debug_Tools::printr( $template_part, '$template_part', __FILE__, __LINE__ );
			}
		}
	}



	/**
	 *    _position_filtered_element
	 *
	 * @access public
	 * @param string 	$content
	 * @param string 	$template
	 * @param int 		$priority
	 * @return string
	 */
	public function position_template_part( $content, $template, $priority ) {
		if ( $this->event_desc_priority == $this->first_template_part_priority ) {
			// EVENT is FIRST so all elements go AFTER the content
			$before = false;
		} else if ( $this->event_desc_priority == $this->last_template_part_priority ) {
			// EVENT is LAST so all elements go BEFORE the content
			$before = true;
		} else if ( $priority == $this->last_template_part_priority ) {
			// this element is LAST - add AFTER existing content
			$before = false;
		} else if ( $priority == $this->first_template_part_priority ) {
			// this element is FIRST - add BEFORE existing content
			$before = true;
		} else if ( $priority < $this->event_desc_priority ) {
			// this element is BEFORE the content
			$before = true;
		} else {
			// this element is AFTER the content
			$before = false;
		}
		$template_part = EEH_Template::locate_template( $template );
		return $before ? $template_part . $content : $content . $template_part;
	}



	/**
	 *    generate_sortable_list_of_template_parts
	 *
	 *    creates an HTML list (<ul>) with list items (<li>) for each template part,
	 *    in a format that can be used as a sortable list in the admin
	 *
	 * @access public
	 * @param string $list_css_id
	 * @param string $list_css_class
	 * @param string $list_item_css_class
	 * @param string $list_item_css_id_prefix
	 * @return string
	 */
	public function generate_sortable_list_of_template_parts(
		$list_css_id = '',
		$list_css_class = '',
		$list_item_css_class = '',
		$list_item_css_id_prefix = ''
	) {
		EE_Registry::instance()->load_helper( 'HTML' );
		$event_archive_display_order = EEH_HTML::ul( $list_css_id, $list_css_class );
		$this->template_parts->rewind();
		// loop through template parts and add template content
		while ( $this->template_parts->valid() ) {
			$event_archive_display_order .= EEH_HTML::li(
				EEH_HTML::span( '', '', 'dashicons dashicons-arrow-up-alt2' ) .
				EEH_HTML::span( '', '', 'dashicons dashicons-arrow-down-alt2' ) .
				$this->template_parts->current()->label(),
				$list_item_css_id_prefix . $this->template_parts->current()->name(),
				$list_item_css_class
			);
			$this->template_parts->next();
		}
		$event_archive_display_order .= EEH_HTML::ulx();
		return $event_archive_display_order;
	}


}
// End of file EE_Template_Part_Manager.class.php
// Location: /EE_Template_Part_Manager.class.php