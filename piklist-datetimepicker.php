<?php
/*
Plugin Name: Piklist DataTimePicker
Description: Adds datetimepicker field to piklist
Version: 0.0.1
Author: Manuel Abadía
Plugin Type: Piklist
Text Domain: piklist-datetimepickers
License: GPL2
*/

// if accessed directly, exit
if (!defined('ABSPATH')) {
	exit;
}

/**
 * The Piklist DateTimePicker Plugin class
 */
class Piklist_DateTimePicker_Plugin {
	private static $inst = null;

	/**
	 * Returns the one and only instance of this class
	 *
	 * @since 0.0.1
	 */
	public static function Instance()
    {
        if (self::$inst === null) {
			self::$inst = new self();

			// piklist plugin check
			add_action('init', array(self::$inst, 'check_for_piklist'));

			// scripts/styles registration
			add_filter('piklist_field_assets', array(self::$inst, 'field_assets'));

			// datetimepicker behaviour
			add_filter('piklist_field_alias', array(self::$inst, 'field_alias'));
			add_filter("piklist_request_field", array(self::$inst, 'request_field'));
			add_filter("piklist_pre_render_field", array(self::$inst, 'pre_render_field'));
        }

        return self::$inst;
    }

	/**
	 * Private Constructor
	 *
	 * @since 0.0.1
	 */
	private function __construct() {
	}

	/**
	 * Checks that piklist is installed
	 *
	 * @return void
	 * @since 0.0.1
	 */
	function check_for_piklist(){
		if(is_admin()){
			include_once(plugin_dir_path( __FILE__ ) . 'class-piklist-checker.php');
	
			if (!piklist_checker::check(__FILE__)){
				return;
			}
		}
	}

	/**
	 * Sets the callback to register the resources for the datetimepicker type
	 *
	 * @param array $field_assets The fields with its corresponding assets
	 * @return array The updated array
	 * @since 0.0.1
	 */
	function field_assets($field_assets) {
		$field_assets['datetimepicker'] = array('callback' => array(self::$inst, 'render_field_assets'));

		return $field_assets;
	}

	/**
	 * Registers the CSS and JS files required for the datetimepicker to work properly
	 *
	 * @param string $type The field type
	 * @return void
	 * @since 0.0.1
	 */
	function render_field_assets($type) {
		wp_enqueue_style('piklist-datetimepicker', plugins_url('lib/css/jquery-ui-timepicker-addon.min.css', __FILE__));

		wp_enqueue_script('piklist-datetimepicker', plugins_url('lib/js/jquery-ui-timepicker-addon.min.js', __FILE__), array('jquery-ui-datepicker', 'jquery-ui-slider'), false, true);
		wp_enqueue_script('piklist-datetimepicker-setup', plugins_url('parts/js/datetimepicker-setup.js', __FILE__), array('piklist-datetimepicker'), false, true);

		/**
		* Notifies that is time to add additional assets related to the datetimepicker field
		*
		* @since 0.0.1
		*/
		do_action('piklist_datetimepicker_field_assets');
	}

	/**
	 * Add an alias from datetimepicker to text
	 *
	 * @return void
	 * @since 0.0.1
	 */
	function field_alias($alias){
		$alias['datetimepicker'] = 'text';

		return $alias;
	}

	/**
	 * Performs the initialization for the datetimepicker field
	 *
	 * @param array $field The settings for the field
	 * @return array The updated field
	 * @since 0.0.1
	 */
	function request_field($field) {
		if ($field['type'] == 'datetimepicker') {
			// sets the default configuration options for non initialized entries
			static $default_config = array(
				'first-day' => null,				// the first day of the week: Sunday is 0, Monday is 1, etc.
				'date-format' => null,				// the format for parsed and displayed dates
				'time-format' => null,				// the format for parsed and displayed times

				'control-type' => null,				// slider or select
				'step-hour' => null,				// hours per step
				'step-minute' => null,				// minutes per step
				'step-second' => null,				// seconds per step
				'timezone' => null,					// initial timezone set. This is the offset in minutes. If null the browser's local timezone will be used
				'hour-min' => null,					// the minimum hour allowed for all dates
				'minute-min' => null,				// the minimum minute allowed for all dates
				'second-min' => null,				// the minimum second allowed for all dates
				'hour-max' => null,					// the maximum hour allowed for all dates
				'minute-max' => null,				// the maximum minute allowed for all dates
				'second-max' => null,				// the maximum second allowed for all dates

				'show-button-panel' => null,		// whether to show the button panel at the bottom
				'time-input' => null,				// Aalows direct input in time field
				'time-only' => null,				// hide the datepicker and only provide a time interface
				'separator' => null,				// when formatting the time this string is placed between the formatted date and formatted time
				
				'picker-time-format' => null,		// how to format the time displayed within the timepicker
				'picker-time-suffix' => null,		// string to place after the formatted time within the timepicker
				'show-Timepicker' => null,			// whether to show the timepicker within the datepicker
				'one-line' => null,					// try to show the time dropdowns all on one line
				'add-slider-access' => null,		// adds the sliderAccess plugin to sliders within timepicker
				'slider-access-args' => null,		// Object to pass to sliderAccess when used
				'default-value' => null,			// string of the default time value placed in the input on focus when the input is empty

				'min-date-time' => null,			// date object of the minimum datetime allowed
				'max-date-time' => null,			// date object of the maximum datetime allowed
				'min-time' => null,					// string of the minimum time allowed
				'parse' => null,					// how to parse the time string. 'strict' must match the timeFormat exactly; 'loose' uses javascript's new Date(timeString)
			);

			/**
			* Filters the default config options
			*
			* @param array $default_config The default config parameters
			* @param array $field The settings for the field
			*
			* @since 0.0.1
			*/
			$config_options = apply_filters('piklist_datetimepicker_default_config_options', $default_config, $field);

			$field['options'] = wp_parse_args($field['options'], $config_options);
		}
		return $field;
	}

	/**
	 * The main functionality of the datetimepicker field is here
	 *
	 * @param array $field The settings for the field
	 * @return array The updated field
	 * @since 0.0.1
	 */
	function pre_render_field($field) {
		if ($field['type'] == 'datetimepicker') {
			$attributes =& $field['attributes'];
			$options =& $field['options'];

			// resolves the language plugin url if the language is set
			if (isset($options['language'])) {
				$options['language'] = plugins_url('lib/js/i18n/' . $options['language'] . '.json', __FILE__);
			}

			array_push($attributes['class'], 'piklist-datetimepicker');
			if (isset($options['style'])) {
				array_push($attributes['class'], $options['style']);
			}

			// this array holds unmapped properties that may be treated specially
			static $do_not_map = array(
			);

			// saves the data values to configure the field
			foreach($options as $key => $val) {
				if (!array_key_exists($key, $do_not_map)) {
					if (isset($options[$key])) {
						// the datetimepicker doesn't understand 0 or 1 as true false, so we convert it here
						if (is_bool($val)) {
							$val = ($val) ? 'true' : 'false';
						} 
						$attributes['data-' . $key] = $val;
					}
				}
			}
		}
		return $field;
	}
}

// creates the one an only instance of this plugin
Piklist_DateTimePicker_Plugin::Instance();
?>