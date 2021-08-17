<?php
/**
 * Created 28.02.2020
 * Version 1.0.0
 * Last update
 * Author: Alex L
 */

// Create multi dropdown param type
if ( function_exists( 'vc_add_shortcode_param' ) ) {
	vc_add_shortcode_param( 'dropdown_multi', 'dropdown_multi' );
	function dropdown_multi ( $param, $value ) {

		$param_line = '';
		$param_line .= '<select multiple name="' . esc_attr( $param['param_name'] ) . '" class="wpb_vc_param_value wpb-input wpb-select ' . esc_attr( $param['param_name'] ) . ' ' . esc_attr( $param['type'] ) . '">';
		foreach ( $param['value'] as $text_val => $val ) {
			if ( is_numeric( $text_val ) && ( is_string( $val ) || is_numeric( $val ) ) ) {
				$text_val = $val;
			}
			$text_val = __( $text_val, "js_composer" );
			$selected = '';

			if ( ! is_array( $value ) ) {
				$param_value_arr = explode( ',', $value );
			} else {
				$param_value_arr = $value;
			}

			if ( $value !== '' && in_array( $val, $param_value_arr ) ) {
				$selected = ' selected="selected"';
			}
			$param_line .= '<option class="' . $val . '" value="' . $val . '"' . $selected . '>' . $text_val . '</option>';
		}
		$param_line .= '</select>';

		return $param_line;
	}
}
