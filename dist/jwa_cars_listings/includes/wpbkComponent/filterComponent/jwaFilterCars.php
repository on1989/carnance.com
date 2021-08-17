<?php
/**
 * Created 04.12.2020
 * Version 1.0.0
 * Last update
 * Author: Alex L
 *
 */

namespace JWA\Car\wpbkComponent\filterComponent;

if ( ! defined( 'ABSPATH' ) ) {
	die ( 'Silly human what are you doing here' );
}

class jwaFilterCars {
	public function __construct () {
		add_shortcode( 'jwa_car_filter', [ $this, 'output' ] );

		// Map shortcode to Visual Composer
		if ( function_exists( 'vc_lean_map' ) ) {
			vc_lean_map( 'jwa_car_filter', [ $this, 'map' ] );
		}
	}

	/**
	 * map field
	 *
	 * @return array
	 */
	public function map () {
		return [
			'name'                    => esc_html__( 'Car Filter', 'jwa_car' ),
			'description'             => esc_html__( 'Add Car filter', 'jwa_car' ),
			'base'                    => 'jwa_car_filter',
			'category'                => __( 'JWA', 'jwa_car' ),
			'show_settings_on_create' => false,
			'icon'                    => '',
			'params'                  => [
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
					'type'        => 'colorpicker',
					'heading'     => __( 'Main Color', 'jwa' ),
					'param_name'  => 'main_color',
					'value'       => '',
					'admin_label' => false,
					'save_always' => true,
					'group'       => 'General',
				],
				[
					'type'        => 'attach_image',
					'heading'     => __( 'Icon', 'jwa' ),
					'param_name'  => 'icon',
					'value'       => '',
					'admin_label' => false,
					'save_always' => true,
					'group'       => 'General',
				],
				[
					'type'        => 'textarea_html',
					'heading'     => __( 'Text', 'jwa' ),
					'param_name'  => 'content',
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

		include JWA_CAR_PLUGIN_DIR . '/wpbkTemplate/filterComponent/template.php';

		return ob_get_clean();
	}
}
