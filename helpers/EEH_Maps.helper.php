<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) { exit('NO direct script access allowed'); }
/**
 * Event Espresso
 *
 * Event Registration and Management Plugin for Wordpress
 *
 * @package		Event Espresso
 * @author		Seth Shoultes
 * @copyright	(c)2009-2012 Event Espresso All Rights Reserved.
 * @license		http://eventespresso.com/support/terms-conditions/  ** see Plugin Licensing **
 * @link			http://www.eventespresso.com
 * @version		4.0
 *
 * ------------------------------------------------------------------------
 *
 * EEH_Maps	
 *
 * This is a helper utility class that provides different helpers related to mapping and displaying location related data.
 *
 * @package		Event Espresso
 * @subpackage	/helpers/EEH_Maps.helper.php
 * @author		Hugo Ashmore, Brent Christensen, Darren Ethier
 *
 * ------------------------------------------------------------------------
 */
class EEH_Maps {

	// array of map settings
	private static $gmap_vars;


	/**
	 * google_map - creates a Google Map Link
	 * @param  array $atts aray of attributes required for the map link generation
	 * @return string (link to map!)
	 */
	public static function google_map( $ee_gmaps_opts ){
		
		$ee_map_width = isset( $ee_gmaps_opts['ee_map_width'] ) && ! empty( $ee_gmaps_opts['ee_map_width'] ) ? $ee_gmaps_opts['ee_map_width'] : '300';	
		$ee_map_height = isset( $ee_gmaps_opts['ee_map_height'] ) && ! empty( $ee_gmaps_opts['ee_map_height'] ) ? $ee_gmaps_opts['ee_map_height'] : '185';
		$ee_map_zoom = isset( $ee_gmaps_opts['ee_map_zoom'] ) && ! empty( $ee_gmaps_opts['ee_map_zoom'] ) ? $ee_gmaps_opts['ee_map_zoom'] : '12';
		$ee_map_nav_display = isset( $ee_gmaps_opts['ee_map_nav_display'] ) && ! empty( $ee_gmaps_opts['ee_map_nav_display'] ) ? 'true' : 'false';
		$ee_map_nav_size =  isset( $ee_gmaps_opts['ee_map_nav_size'] ) && ! empty( $ee_gmaps_opts['ee_map_nav_size'] )? $ee_gmaps_opts['ee_map_nav_size'] : 'default';
		$ee_map_type_control =  isset( $ee_gmaps_opts['ee_map_type_control'] ) && ! empty( $ee_gmaps_opts['ee_map_type_control'] )? $ee_gmaps_opts['ee_map_type_control'] : 'default';
		$static_url =  isset( $ee_gmaps_opts['ee_static_url'] ) && ! empty( $ee_gmaps_opts['ee_static_url'] )? $ee_gmaps_opts['ee_static_url'] : FALSE;

		if( isset( $ee_gmaps_opts['ee_map_align'] ) && ! empty( $ee_gmaps_opts['ee_map_align'] )){
			switch( $ee_gmaps_opts['ee_map_align'] ){
				case "left":
					$map_align = ' ee-gmap-align-left left';
					break;
				case "right":
					$map_align = ' ee-gmap-align-right right';
					break;
				case "center":
					$map_align = ' ee-gmap-align-center center';
					break;
				case "none":
				default:
					$map_align = ' ee-gmap-align-none';
			}
		} else {
			$map_align = ' ee-gmap-align-none';
		}

		// check whether event_meta enable indivudual event for maps is true
		if( isset( $ee_gmaps_opts['ee_enable_for_gmap'] ) && $ee_gmaps_opts['ee_enable_for_gmap'] ) {
			// Determine whether user has set a hardoded url to use and
			// if so display a Google static iframe map else run V3 api
			if( $static_url ) {
				
				$html = '<div class="ee-gmap-iframewrap ee-gmap-parent' . $map_align . '">';
				$html .= '<iframe src="' . $static_url . '&output=embed" style="width: ' . $ee_map_width  .'px; height: ' . $ee_map_height . 'px;" frameborder="0" scrolling="no">';
				$html .= '</iframe>';
				$html .= '<a href="' . $static_url . '">View Large map</a>';
				$html .= '</div>';
				return $html;
				
			 } else {

				wp_enqueue_script( 'gmap_api', 'http://maps.google.com/maps/api/js?sensor=false', array('jquery'), NULL, TRUE );
				wp_register_script( 'ee_gmap', plugin_dir_url(__FILE__) . 'assets/ee_gmap.js', array('gmap_api'), '1.0', TRUE );
				self::$gmap_vars[ $ee_gmaps_opts['VNU_ID'] ] = array(
					'VNU_ID' => $ee_gmaps_opts['VNU_ID'],
					'ee_map_zoom' => $ee_map_zoom,
					'ee_map_nav_display' => $ee_map_nav_display,
					'ee_map_nav_size' => $ee_map_nav_size,
					'ee_map_type_control' => $ee_map_type_control,
					'location' => $ee_gmaps_opts['location']
				);
				add_action('wp_footer', 'espresso_google_map_js', 1 );
				
				
				$html = '<div class="ee-gmap-parent'.$map_align.';">';
				$html .= '	<div class="ee-gmap" id="map_canvas_' . $ee_gmaps_opts['VNU_ID'] .'" style="width: '.$ee_map_width.'px; height: '.$ee_map_height.'px;"></div>';
				$html .= '</div>';
				return $html;
				
			} // end auto map or static url map check
		} // end check for enable_for_maps
	}



	/**
	 * creates a Google Map Link
	 * @param  array $atts aray of attributes required for the map link generation
	 * @return string (link to map!)
	 */
	public function espresso_google_map_js() { 
		wp_enqueue_script( 'ee_gmap' );
		wp_localize_script( 'ee_gmap', 'ee_gmap_vars', self::$gmap_vars );
	}



	/**
	 * creates a Google Map Link
	 * @param  array $atts aray of attributes required for the map link generation
	 * @return string (link to map!)
	 */
	public static function google_map_link($atts) {
		do_action('AHEE_log', __FILE__, __FUNCTION__, '');
		extract($atts);

		$address = "{$address}";
		$city = "{$city}";
		$state = "{$state}";
		$zip = "{$zip}";
		$country = "{$country}";
		$text = isset($text) ? "{$text}" : "";
		$type = isset($type) ? "{$type}" : "";
		$map_w = isset($map_w) ? "{$map_w}" : 400;
		$map_h = isset($map_h) ? "{$map_h}" : 400;
		$id = isset($id) ? $id : 'not_set';
		$map_image_class = isset($map_image_class) ? $map_image_class : 'ee_google_map_view';

		$gaddress = ($address != '' ? $address : '') . ($city != '' ? ',' . $city : '') . ($state != '' ? ',' . $state : '') . ($zip != '' ? ',' . $zip : '') . ($country != '' ? ',' . $country : '');

		$google_map = htmlentities2('http://maps.google.com/maps?q=' . urlencode($gaddress));

		switch ($type) {
			case 'text':
			default:
				$text = $text == '' ? __('Map and Directions', 'event_espresso') : $text;
				break;

			case 'url':
				$text = $google_map;
				break;

			case 'map':
				$google_map_link = '<a class="a_map_image_link" href="' . $google_map . '" target="_blank">' . '<img class="map_image_link" id="venue_map_' . $id . '" ' . $map_image_class . ' src="' . htmlentities2('http://maps.googleapis.com/maps/api/staticmap?center=' . urlencode($gaddress) . '&amp;zoom=14&amp;size=' . $map_w . 'x' . $map_h . '&amp;markers=color:green|label:|' . urlencode($gaddress) . '&amp;sensor=false') . '" /></a>';
				return $google_map_link;
		}

		$google_map_link = '<a href="' . $google_map . '" target="_blank">' . $text . '</a>';
		return $google_map_link;
	}
	
	
} 
// End of file EEH_Maps.helper.php
// Location: /helpers/EEH_Maps.helper.php