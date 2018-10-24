<?php
/**
 * The Option page plugin class.
 *
 * @since      1.0.0
 * @package    Trip_Departure
 * @subpackage Trip_Departure/includes
 * @author     lastdoorsolutions <info@lastdoorsolutions.com>
 */
class Trip_Departure_Option_Page
{
	/**
	 * Holds the values to be used in the fields callbacks.
	 *
	 */
	protected $options;


	/**
	 * Define the Option page functionality of the plugin.
	 */
	public function __construct()
	{

		add_action('admin_menu', array($this, 'add_plugin_page'));
		add_action('admin_init', array($this, 'page_init'));

	}

	/**
	 * Add options page
	 */
	public function add_plugin_page()
	{
		// This page will be under "Settings"
		add_options_page(
			'Trip departure settings',
			'Trip departure settings',
			'manage_options',
			'trip-departure-date-page',
			array($this, 'trip_departure_general'),
			'dashicons-money', 90
		);


	}

	/**
	 * Setting page callback
	 */
	public function trip_departure_general()
	{
		$this->options = get_option('trip_departure_option_name');
		?>
		<div class="wrap">
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields('trip_departure_option_group');
				do_settings_sections('trip-departure-date-page');
				submit_button();
				?>
			</form>
		</div>
		<?php
	}


	/**
	 * Register and add settings
	 */
	public function page_init()
	{
		register_setting(
			'trip_departure_option_group',
			'trip_departure_option_name',
			array($this, 'sanitize')
		);

		add_settings_section(
			'setting_section_id',
			'Trip departure Options',
			array($this, 'print_section_info'),
			'trip-departure-date-page'
		);

		add_settings_field(
			'Trip_post_type_slug',
			'Trip Post type slug',
			array($this, 'Trip_post_type_slug_callback'),
			'trip-departure-date-page',
			'setting_section_id'
		);

		add_settings_field(
			'trip_price',
			'Price section in frontend',
			array($this, 'trip_price_hide_callback'),
			'trip-departure-date-page',
			'setting_section_id'
		);
		add_settings_field(
			'trip_discount',
			'Discount section in frontend',
			array($this, 'trip_price_hide_callback'),
			'trip-departure-date-page',
			'setting_section_id'
		);
		add_settings_field(
			'trip_status',
			'Discount section in frontend',
			array($this, 'trip_status_hide_callback'),
			'trip-departure-date-page',
			'setting_section_id'
		);
		add_settings_field(
			'trip_status_option',
			'Discount section in frontend',
			array($this, 'trip_status_option_callback'),
			'trip-departure-date-page',
			'setting_section_id'
		);


	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize($input)
	{
		$new_input = array();
		if (isset($input['Trip_post_type_slug']))
			$new_input['Trip_post_type_slug'] = sanitize_text_field($input['Trip_post_type_slug']);
		if (isset($input['trip_price_hide']))
			$new_input['trip_price_hide'] = sanitize_text_field($input['trip_price_hide']);
		if (isset($input['trip_discount_hide']))
			$new_input['trip_discount_hide'] = sanitize_text_field($input['trip_discount_hide']);
		if (isset($input['trip_status_hide']))
			$new_input['trip_status_hide'] = sanitize_text_field($input['trip_status_hide']);
		if (isset($input['trip_status_option']))
			$new_input['trip_status_option'] = sanitize_text_field($input['trip_status_option']);

		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function print_section_info()
	{
		print 'Make sure you have activated ACF and created required fields. Please configure your settings.';
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function Trip_post_type_slug_callback()
	{
		printf(
			'<input type="text" id="Trip_post_type_slug" name="trip_departure_option_name[Trip_post_type_slug]" value="%s" />',
			isset($this->options['Trip_post_type_slug']) ? esc_attr($this->options['Trip_post_type_slug']) : ''
		);

	}
	public function trip_price_hide_callback()
	{
	    //echo $this->options['trip_price_hide'];
		$trip_price_hide = '<select name="trip_departure_option_name[trip_price_hide]">';
		if( 0 == $this->options['trip_price_hide']){
			$trip_price_hide .= '<option value="0" selected>Hide</option>';
        }else{
			$trip_price_hide .= '<option value="0" >Hide</option>';
        }
		if( 1 == $this->options['trip_price_hide']){
			$trip_price_hide .= '<option value="1" selected>Show</option>';
		}else{
			$trip_price_hide .= '<option value="1" >Show</option>';
		}
		$trip_price_hide .= '</select>';
		echo 	$trip_price_hide;

	}

	public function trip_discount_hide_callback()
	{
		//echo $this->options['trip_price_hide'];
		$trip_price_hide = '<select name="trip_departure_option_name[trip_discount_hide]">';
		if( 0 == $this->options['trip_discount_hide']){
			$trip_discount_hide .= '<option value="0" selected>Hide</option>';
		}else{
			$trip_discount_hide .= '<option value="0" >Hide</option>';
		}
		if( 1 == $this->options['trip_discount_hide']){
			$trip_discount_hide .= '<option value="1" selected>Show</option>';
		}else{
			$trip_discount_hide .= '<option value="1" >Show</option>';
		}
		$trip_discount_hide .= '</select>';
		echo 	$trip_discount_hide;

	}

	public function trip_status_hide_callback()
	{
		//echo $this->options['trip_price_hide'];
		$trip_status_hide = '<select name="trip_departure_option_name[trip_status_hide]">';
		if( 0 == $this->options['trip_status_hide']){
			$trip_status_hide .= '<option value="0" selected>Hide</option>';
		}else{
			$trip_status_hide .= '<option value="0" >Hide</option>';
		}
		if( 1 == $this->options['trip_status_hide']){
			$trip_status_hide .= '<option value="1" selected>Show</option>';
		}else{
			$trip_status_hide .= '<option value="1" >Show</option>';
		}
		$trip_status_hide .= '</select>';
		echo 	$trip_status_hide;

	}

	public function trip_status_option_callback()
	{
		printf('<label>Add Trip status option (maximum 4)by separating , (E.g. Booking open,Closed,Full).</label><br/>
			<input type="text" id="trip_status_option" name="trip_departure_option_name[trip_status_option]" value="%s" style="width:400px;"/>',
			isset($this->options['trip_status_option']) ? esc_attr($this->options['trip_status_option']) : ''
		);

	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function hash_code_callback()
	{
		printf(
			'<input type="text" id="hash_code" name="trip_departure_option_name[hash_code]" value="%s" />',
			isset($this->options['hash_code']) ? esc_attr($this->options['hash_code']) : ''
		);
	}


	/**
	 * Get the settings option array and print one of its values
	 */
	public function currencyCode_callback()
	{
		printf(
			'<input type="text" id="currencyCode" name="trip_departure_option_name[currencyCode]" value="%s" />',
			isset($this->options['currencyCode']) ? esc_attr($this->options['currencyCode']) : ''
		);
	}


	public function currency_callback()
	{
		?>
		<select name='trip_departure_option_name[currency_symbol]'>
			<option value='524' <?php if ($this->options['currency_symbol'] == "524") {
				echo "selected";
			} ?>>Nepalese Rupee
			</option>
			<option value='840' <?php if ($this->options['currency_symbol'] == "840") {
				echo "selected";
			} ?>>United States dollar
			</option>
		</select>

		<?php
	}


}

if (is_admin())
	$option_page = new Trip_Departure_Option_Page();
