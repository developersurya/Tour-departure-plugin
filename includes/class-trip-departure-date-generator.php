<?php
	/**
	* The Option page plugin class.
	*
	* @since      1.0.0
	* @package    Trip_Departure
	* @subpackage Trip_Departure/includes
	* @author     lastdoorsolutions <info@lastdoorsolutions.com>
	*/
	class Trip_Departure_date_Generator {
		/**
		* Define the Option page functionality of the plugin.
		*/
		public function __construct() {

			//ajax call for repeater field to remove old dates, Delete all dates
			add_action( "wp_ajax_trip_departure_update_repeater_field_tripdates", array( $this, "trip_departure_update_repeater_field_tripdates" ) );
			add_action( "wp_ajax_nopriv_trip_departure_update_repeater_field_tripdates", array( $this, "trip_departure_update_repeater_field_tripdates" ) );
			add_action( "wp_ajax_trip_departure_delete_repeater_field_tripdates", array( $this, "trip_departure_delete_repeater_field_tripdates" ) );
			add_action( "wp_ajax_nopriv_trip_departure_delete_repeater_field_tripdates", array( $this, "trip_departure_delete_repeater_field_tripdates" ) );

		}

		/**
		 * Dynamically populate custom post title in dropdown
		 * @param  string|array $field [description]
		 * @return [type] [description]
		 */
		public function trip_departure_load_select_trip_field_choices($field) {
			//echo "sf";die();

			$args = array(
				'post_type' => 'trip',
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
		 * Remove old dates and update
		 * @return [type] [description]
		 */
		public function trip_departure_update_repeater_field_tripdates() {
			$p_id = $_POST['postId'];
			if (have_rows('generate_departure_date', $p_id)) {
				$new_repeater = array();
				while (have_rows('generate_departure_date', $p_id)) {
					the_row();
					//spliting into single date
					$rep_date = get_sub_field('start_date__end_date');
					$repe_date = explode(" - ", $rep_date);
					$repea_date = $repe_date['0'];
					$repeat_date = new DateTime($repea_date);
					//yesterday date for preventing delete for today's trip dates.
					$yesterday_date = date('Y/m/d', strtotime("-30 days"));
					$now = new DateTime($yesterday_date);

					if ($repeat_date > $now) {
						// echo 'present';
						$new_repeater[] = array(
							'start_date__end_date' => get_sub_field('start_date__end_date'),
							'price' => get_sub_field('price'),
							'discount' => get_sub_field('discount'),
							'status' => get_sub_field('status'),
						);
					} else {
						//do something for present date
					}

				}
				update_field('generate_departure_date', $new_repeater, $p_id);
			}
			die();
		}

		/**
		 * Delete all dates including present and past dates
		 *
		 */
		public function trip_departure_delete_repeater_field_tripdates() {
			$p_id = $_POST['postId'];
			if (have_rows('generate_departure_date', $p_id)) {
				$new_repeater = array();
				while (have_rows('generate_departure_date', $p_id)) {
					the_row();
					$new_repeater[] = array(
					);
					delete_row('generate_departure_date', 1, $p_id);
				}
			}
			die();
		}


		public function trip_departure_remove_old_dates_admin_css() {
			?>
			<style>

			</style>
			<?php
		}

	}
	$Trip_Departure_date_Generator = new Trip_Departure_date_Generator();