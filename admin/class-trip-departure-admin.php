<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Trip_Departure
 * @subpackage Trip_Departure/admin
 * @author     lastdoorsolutions <info@lastdoorsolutions.com>
 */
class Trip_Departure_Admin {

	/**
	 * Initialize the class and set its properties.
	 *
	 */
	public function __construct( ) {

		add_action( 'admin_enqueue_scripts', array( $this,'trip_departure_enqueue_admin_script'));
		add_filter( 'acf/load_field/name=select_trip', array( $this,'trip_departure_load_select_trip_field_choices') );

	}

	public function get_trip_post(){
		$trip_post = get_option('trip_departure_option_name');
		return $trip_post['Trip_post_type_slug'];
	}


	/**
	 * Dynamically populate custom post title in dropdown
	 * @param  string|array
	 * @return array
	 * @since    1.0.0
	 */
	public function trip_departure_load_select_trip_field_choices($field) {
		$trip_post = $this->get_trip_post();
		//var_dump($trip_post);
		if($trip_post == "" || empty($trip_post)){
			$trip_post = "trip";
		}
		$args = array(
			'post_type' => $trip_post,
			'posts_per_page' => 500,
			'orderby' => 'title',
			'order' => 'asc',
		);
		$trip_posts = array();
		$loop = new WP_Query($args);
		//add default trip title and id
		$trip_posts[] = "Trip Name";
		while ($loop->have_posts()): $loop->the_post();
			if(get_the_title()){
				$trip_posts[] = get_the_title();
			}

		endwhile;
		wp_reset_query();

		// reset choices
		//$field['choices'] = $trip_posts;

		$choices = $trip_posts;

		// loop through array and add to field 'choices'
		if (is_array($choices)) {
			foreach ($choices as $choice) {
				$field['choices'][$choice] = $choice;
			}
		}
		// return the field
		return $field;
	}


	/**
	 * Add script in admin area
	 *
	 * @since    1.0.0
	 */
	public function trip_departure_enqueue_admin_script() {
		/**
		 * Add admin scripts
		 */
		wp_register_script('trip-departure-admin-script', plugin_dir_url( __FILE__ ) . '/js/date-generator.js', array('jquery'), true);
		wp_register_style('trip-departure-admin-style', plugin_dir_url( __FILE__ ) . '/css/trip-departure-admin-style.css');
		wp_enqueue_script('momentjs',  'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js', array('jquery'), true);
		wp_enqueue_script('daterangepicker',  'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js', array('jquery'), true);
		//wp_enqueue_script('daterangepicker-css',  'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css');
		wp_enqueue_script( 'trip-departure-admin-script' );
		wp_enqueue_style( 'trip-departure-admin-style' );

		$trip_status_option = get_option('trip_departure_option_name');
		$trip_status_option_ = $trip_status_option['trip_status_option'];
		wp_localize_script( 'trip-departure-admin-script', 'trip_departure_plugin', array(
			'trip_status_option' => $trip_status_option_
		));

	}

}


$Trip_Departure_Admin = new Trip_Departure_Admin();