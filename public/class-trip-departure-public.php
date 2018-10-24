<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Trip_Departure
 * @subpackage Trip_Departure/public
 * @author     lastdoorsolutions <info@lastdoorsolutions.com>
 */
class Trip_Departure_Public {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct( ) {
		add_action( 'wp_enqueue_scripts', array( $this, 'public_enqueue_scripts' ) );
	}


	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function public_enqueue_scripts() {

		wp_register_script( 'departure-date-filter', plugin_dir_url( __FILE__ ) . '/js/departure-dates.js', array( 'jquery' ), true );
		wp_enqueue_script('departure-date-filter');
		wp_localize_script( 'departure-date-filter', 'admin_ajax', array(
			'url' => admin_url( 'admin-ajax.php'),'postID' => get_the_ID()
		));

	}



}

$Trip_Departure_Public = new Trip_Departure_Public();