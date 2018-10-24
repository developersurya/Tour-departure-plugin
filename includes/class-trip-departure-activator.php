<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Trip_Departure
 * @subpackage Trip_Departure/includes
 * @author     lastdoorsolutions <info@lastdoorsolutions.com>
 */

class Trip_Departure_Activator {


	public function __construct() {
		add_action( 'init', array( $this, 'trip_departure_cpt_init' ) );
		//echo "here";
	}

	public static function activate() {

	}
	/**
	 * Register a trip post type.
	 */
	public function trip_departure_cpt_init() {
		register_post_type( 'trip',
			array(
				'labels' => array(
					'name' => __( 'Trip' ),
					'singular_name' => __( 'Trip' )
				),
				'public' => true,
				'has_archive' => true,
				'supports' => array( 'title','editor','custom-fields')
			)
		);
	}

}
