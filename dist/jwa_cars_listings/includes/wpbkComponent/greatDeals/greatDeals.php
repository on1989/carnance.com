<?php
/**
 * Created 04.11.2020
 * Version 1.0.0
 * Last update
 * Author: Alex L
 *
 */

namespace JWA\Car\wpbkComponent\greatDeals;

use JWA\Car\Helpers\taxCar\taxCar;

if ( ! defined( 'ABSPATH' ) ) {
	die ( 'Silly human what are you doing here' );
}

class greatDeals {

	public function __construct () {
		add_shortcode( 'jwa_car_greet_deals', [ $this, 'output' ] );

		// Map shortcode to Visual Composer
		if ( function_exists( 'vc_lean_map' ) ) {
			vc_lean_map( 'jwa_car_greet_deals', [ $this, 'map' ] );
		}
	}

	/**
	 * Output Short Code template
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return false|string
	 */
	public function output ( $atts, $content = null ) {
		ob_start();

		include JWA_CAR_PLUGIN_DIR . '/wpbkTemplate/greatDeals/template.php';

		return ob_get_clean();
	}

	/**
	 * map field
	 *
	 * @return array
	 */
	public function map () {
		$tag = new taxCar( 'car_tag' );

		return [
			'name'                    => esc_html__( 'Great Deals', 'jwa_car' ),
			'description'             => esc_html__( 'Add Great Deals', 'jwa_car' ),
			'base'                    => 'jwa_car_greet_deals',
			'category'                => __( 'JWA', 'jwa_car' ),
			'show_settings_on_create' => false,
			'icon'                    => '',
			'params'                  => [
				[
					'type'        => 'dropdown_multi',
					'heading'     => __( 'Select category', 'jwa' ),
					'param_name'  => 'car_tag',
					'value'       => $tag->getTaxArray(),
					'description' => __( 'Select a category', 'jwa' ),
					'admin_label' => false,
					'save_always' => true,
					'group'       => 'General',
				],
				[
					'type'        => 'textfield',
					'heading'     => __( 'Title', 'jwa_car' ),
					'param_name'  => 'title',
					'value'       => '',
					'admin_label' => false,
					'save_always' => true,
					'group'       => 'General',
				],
				[
					'type'        => 'textfield',
					'heading'     => __( 'Sub Title', 'jwa_car' ),
					'param_name'  => 'sub_title',
					'value'       => '',
					'admin_label' => false,
					'save_always' => true,
					'group'       => 'General',
				],
				[
					'type'        => 'vc_link',
					'heading'     => __( 'Button', 'jwa' ),
					'param_name'  => 'btn',
					'value'       => '',
					'admin_label' => false,
					'save_always' => true,
					'group'       => 'General',
				],
				[
					'type'        => 'colorpicker',
					'heading'     => __( 'Button Color', 'jwa' ),
					'param_name'  => 'btn_color',
					'value'       => '',
					'admin_label' => false,
					'save_always' => true,
					'group'       => 'General',
				],
				[
					'type'        => 'colorpicker',
					'heading'     => __( 'Button Hover Color', 'jwa' ),
					'param_name'  => 'btn_hover_color',
					'value'       => '',
					'admin_label' => false,
					'save_always' => true,
					'group'       => 'General',
				],
				[
					'type'        => 'colorpicker',
					'heading'     => __( 'Button Text Color', 'jwa' ),
					'param_name'  => 'btn_text_color',
					'value'       => '',
					'admin_label' => false,
					'save_always' => true,
					'group'       => 'General',
				],
				[
					'type'       => 'css_editor',
					'heading'    => esc_html__( 'CSS box', 'jwa_car' ),
					'param_name' => 'css',
					'group'      => esc_html__( 'Design Options', 'jwa_car' ),
				],
			],
		];
	}
}
