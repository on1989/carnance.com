<?php
/**
 * Created 12.11.2020
 * Version 1.0.0
 * Last update
 * Author: Alex L
 *
 */

namespace JWA\Car\wpbkComponent\carInventory;

if ( ! defined( 'ABSPATH' ) ) {
	die ( 'Silly human what are you doing here' );
}

class jwaCarInventory {
	public function __construct () {
		add_shortcode( 'jwa_car_inventory', [ $this, 'output' ] );

		// Map shortcode to Visual Composer
		if ( function_exists( 'vc_lean_map' ) ) {
			vc_lean_map( 'jwa_car_inventory', [ $this, 'map' ] );
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

		include JWA_CAR_PLUGIN_DIR . '/wpbkTemplate/carInventory/template.php';

		return ob_get_clean();
	}

	/**
	 * map field
	 *
	 * @return array
	 */
	public function map () {
		return [
			'name'                    => esc_html__( 'Car Inventory', 'jwa_car' ),
			'description'             => esc_html__( 'Add Car Inventory', 'jwa_car' ),
			'base'                    => 'jwa_car_inventory',
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
