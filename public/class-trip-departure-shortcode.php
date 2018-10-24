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
class Trip_Departure_Shortcode {

	/**
	 * Initialize the class and set its properties.
	 *
	 */
	public function __construct() {

		//$this->options = get_option( 'hbl_option_name' );
		add_shortcode( 'trip_departure_show',  array( $this,'trip_departure_shortcode' ));

	}

	/**
	 * Discounted price with round number
	 * @param  String $original_price
	 * @param  String $discount
	 * @return float String
	 */
	public function trip_departure_discounted_price($original_price,$discount){

		$discounted_cost = (int)$original_price-(((int)$original_price * (int)$discount)/100);

		//return round($discounted_cost);
		return ($discounted_cost);

	}

	/**
	 * Add shortcode for payment form
	 */
	public function trip_departure_data() {
		//check the slug of new date post
		$post_slug = get_post_field( 'post_name', get_post() );

		$p_id = false;
		$repeater_data = false;
		$args = array(
			'post_type' => 'departure-dates',
			'name' =>$post_slug,
		);
		$trip_posts = array();
		$loop = new WP_Query($args);
		while($loop->have_posts()): $loop->the_post();
			$p_id = get_the_ID();
			wp_reset_postdata();
		endwhile;
		//var_dump($p_id);

		if (have_rows('generate_departure_date', $p_id)) {
			$data = array();
			while (have_rows('generate_departure_date', $p_id)):the_row();
				if (!empty(get_sub_field('start_date__end_date'))) {
					//date checking
					$dateS = explode('-', get_sub_field('start_date__end_date'));
					$str = explode("/",$dateS[0]);

					//Showing departure dates excatly from today
					//$today = strtotime(date('Y-m-d'));

					//change today date to three days ahead, for booking process current departure date selection is not logical.
					$today = date('Y-m-d');
					//$today = date('2018-06-23');
					$today = strtotime(date('Y-m-d', strtotime($today. ' + 3 days')));

					//calculate discounted price
					$discounted_price = $this->trip_departure_discounted_price(get_sub_field('price'),get_sub_field('discount'));

					if (strtotime($dateS[0]) >= $today) {

						$repeater_data[]= array(
							//'count'                 => $count,
							'price'                 => get_sub_field('price'),
							'discount'              => get_sub_field('discount'),
							'discounted_price'      => $discounted_price,
							'status'                => get_sub_field('status'),
							'start_date__end_date'  => get_sub_field('start_date__end_date'),
							'start_date__only'      => ($dateS[0]),
							'date_today'            => $today,
							'date_array'            =>  $str
						);

					}
				}
			endwhile;
		}

		//re-order the data according to date

		function sortFunction( $a, $b ) {
			return strtotime($a["start_date__only"]) - strtotime($b["start_date__only"]);
		}
		if(!empty($repeater_data)){
			usort($repeater_data, "sortFunction");
			//var_dump($repeater_data);
		}

		//check the condition for group data
		//get_the_ID() will provide Trip post ID as we are in template
		$group_data = "";
		$trip_post_id = get_the_ID();
		if (have_rows('group_discount', $trip_post_id)) {
			$group_data = array();
			while (have_rows('group_discount', $trip_post_id)):the_row();
				if (!empty(get_sub_field('group_range'))) {
					$group_range = get_sub_field('group_range');
					$price_per_person = get_sub_field('price_per_person');
					$group_data[] = array(
						'group_range' =>  $group_range,
						'price_per_person' =>  $price_per_person,
					);
				}
			endwhile;
		}

		$trip_data = array(
			'p_id'=>$p_id,
			'data'=>$repeater_data,
			'group_data' =>$group_data
		);
		return $trip_data;

	}

	/**
	 * Add shortcode for payment form
	 */
	public function trip_departure($atts)
	{
		$trip_departure_data = $this->trip_departure_data();
			//var_dump($trip_departure_data);
			//die();
		if (!empty($trip_departure_data)):
			//var_dump($args);
			//get the post_id
			$p_id = $trip_departure_data['p_id'];
			if(!empty($trip_departure_data["data"])) {
				//get the last and first from array
				$start_year = $trip_departure_data['data'][0]['date_array']['2'];
				$end_year   = $trip_departure_data['data'][ count( $trip_departure_data['data'] ) - 1 ]['date_array']['2'];
				//get the user input secret codes for payment form .
				do_action('trip_extra_data');
				$trip_detail_data = '<div class="search-ajax-wrp">
                <div class="search-ajax-year">
                    <select id="search-ajax-year" class="">
                        <option value="yearlist0">Select Year</option>';
				$x                = (int) $start_year;
				$y                = (int) $end_year;
				while ( $x <= $y ) {
					$trip_detail_data .= '<option value="yearlist' . $x . '">' . $x . '</option>';
					$x ++;
				}
				$trip_detail_data .= '</select></div>';

				$trip_detail_data .= '<div class="search-ajax-month">
			                    <select id="search-ajax-month" class="">
			                    <option value="monthlist0">Select Month</option>
			                    <option value="monthlist1">Jan</option>
			                    <option value="monthlist2">Feb</option>
			                    <option value="monthlist3">Mar</option>
			                    <option value="monthlist4">Apr</option>
			                    <option value="monthlist5">May</option>
			                    <option value="monthlist6">Jun</option>
			                    <option value="monthlist7">Jul</option>
			                    <option value="monthlist8">Aug</option>
			                    <option value="monthlist9">Sep</option>
			                    <option value="monthlist10">Oct</option>
			                    <option value="monthlist11">Nov</option>
			                    <option value="monthlist12">Dec</option>
			                    </select>
			                </div>';
				if ( isset( $args['group_data'] ) ) {
					$group_data = $args['group_data'];

					$trip_detail_data .= '<div class="search-ajax-month">
				                <select id="search-ajax-group" class="">
				                    <option value="0">Group discount available</option>
				                    foreach ( $group_data as $groupdata ){
				                    <option value="' . $groupdata['group_range'] . '">' . $groupdata['group_range'] . '</option>
				                     } 
				                </select>
					            </div>
					            <div class="search-ajax-month">
					                <button class="btn-warning reset-btn">Reset</button>
					            </div>';
				}
				$trip_detail_data .= '</div> </div>';

				$trip_detail_data .= '<div class="departure-pid" style="display:none;">'.$p_id.'</div>
									    <div class="departure-result-table">
									    <div id="custom-scroller">
									    <div class="date-list">
									    <div class="overlay-table" style="display: none;position:relative;"><div class="searching">Searching <div class="lds-facebook">
									    <div></div><div></div><div></div></div></div></div>
									        <table id="booking-dates" class="table  table-hover">
									            <thead class="thead-light">
									                <tr class="active">
									                    <th scope="col">S.N.</th> 
									                    <th scope="col">Departure Date</th>';
				$trip_price_hide = get_option('trip_departure_option_name');
                if( 1 == $trip_price_hide['trip_price_hide']) {
	            $trip_detail_data .= '<th scope="col">Price (Per Person)</th>';
                }
                if( 1 == $trip_price_hide['trip_discount_hide']){
				$trip_detail_data .= '<th scope="col">Discount</th>';
				}
				if( 1 == $trip_price_hide['trip_status_hide']) {
					$trip_detail_data .= '<th scope="col">Trip Status</th>';
				}
					$trip_detail_data .= '<th scope="col"></th>
									                    <th scope="col"></th>
									                </tr>
									            </thead>';
				$trip_detail_data .= '<tbody id="update">';
					                 if (!empty($trip_departure_data['data'])) {
					                    $count=1;
					                    $pax = false;
					                     foreach($trip_departure_data['data'] as $data) {
					                     	//change date format
						                     $date_e = explode("-", $data['start_date__end_date']);
						                     //var_dump($date_e);
						                     $old_date_s = date($date_e['0']);
						                     $old_date_timestamp_s = strtotime($old_date_s);
						                     $new_date_s = date('j M,Y', $old_date_timestamp_s);
						                     $old_date_e = date($date_e['1']);
						                     $old_date_timestamp_e = strtotime($old_date_e);
						                     $new_date_e = date('j M,Y', $old_date_timestamp_e);

						                     $trip_detail_data .= '<tr data-year="' .trim($data['date_array']['2']).'" data-month="'.$data['date_array']['0'].'">';
                $trip_detail_data .= '<th scope="row">' .$count. '</th>';
                $trip_detail_data .= '<td>'.$new_date_e.'-'.$new_date_s .'</td>';
				if( 1 == $trip_price_hide['trip_price_hide']) {
				$trip_detail_data .= '<td> USD <del>' . $data['price'] . '</del>';
				$trip_detail_data .=  $data['discounted_price'] . '</td>';
				}
				if( 1 == $trip_price_hide['trip_discount_hide']) {
                $trip_detail_data .= '<td>' . $data['discount'] . '%</td>';
				}
                 if( 1 == $trip_price_hide['trip_status_hide']) {
                     $trip_detail_data .= '<td>' . $data['status'] . '</td>';
                 }
                $trip_detail_data .= '<td><button type="button" class="btn btn-secondary">
										<a href="' . site_url().'/trip-booking/?tid='.get_the_ID().'&dt='.$data['start_date__end_date'].'&px='.$pax.'">Inquire Now</a>
										</button>';
                $trip_detail_data .= '</td>';
						                     $trip_detail_data .= '<td> Share ';

						                     $trip_detail_data .= '</td>';
				$trip_detail_data .= '</tr>';
					                 $count++;
					                    }
					                    }
				$trip_detail_data .= '</tbody>
										</table>
										</div>
										</div>
										</div>
										</div>';
			}

		endif;

		return $trip_detail_data;

	}

	/**
	 * Add shortcode for payment form
	 */
	public function trip_departure_shortcode($atts)
	{
		// [hbl_form ]
		$a = shortcode_atts( array(
			'price' => '000000010000',
			'productDesc' => 'something else',
		), $atts );


		return $this->trip_departure($atts);
	}


}

$Trip_Departure_Shortcode = new Trip_Departure_Shortcode();