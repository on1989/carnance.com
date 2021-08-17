<?php
/**
 * Created 14.11.2020
 * Version 1.0.2
 * Last update 04.12.2020
 * Author: Alex L
 *
 */

namespace JWA\Car\Settings;
if ( ! defined( 'ABSPATH' ) ) {
	die ( 'Silly human what are you doing here' );
}

class jwaSettingsField {
	public function __construct () {
		self::registerSettings();
		self::displaySettingSection();
	}

	/**
	 * Register a setting
	 */
	public static function registerSettings () {
		register_setting( 'jwa_car_listing_group', 'jwa_car_listing' );
	}

	public function displaySettingSection () {
		add_settings_section( 'jwa_car_finance', __( 'Finance', 'jwa_car' ), null, 'listing-settings' );
		add_settings_section( 'jwa_car_from', __( 'Form', 'jwa_car' ), null, 'listing-settings' );
		add_settings_section( 'jwa_car_rest', __( 'REST API', 'jwa_car' ), null, 'listing-settings' );
		add_settings_section( 'jwa_car_style', __( 'Style', 'jwa_car' ), null, 'listing-settings' );
		add_settings_section( 'jwa_car_page_count', __( 'Output car Number', 'jwa_car' ), null, 'listing-settings' );

		$fields = [
			[
				'type_field'  => 'text',
				'section'     => 'jwa_car_finance',
				'id'          => 'percent',
				'page'        => 'listing-settings',
				'name'        => __( 'Percent', 'jwa_car' ),
				'description' => __( 'Enter the percentage of the loan margin' ),
			],
			[
				'type_field'  => 'text',
				'section'     => 'jwa_car_finance',
				'id'          => 'term',
				'page'        => 'listing-settings',
				'name'        => __( 'Term', 'jwa_car' ),
				'description' => __( 'Enter a term', 'jwa_car' ),
			],
			[
				'type_field'  => 'term_and_percent',
				'section'     => 'jwa_car_finance',
				'id'          => 'term_and_percent',
				'page'        => 'listing-settings',
				'name'        => __( 'Term by year', 'jwa_car' ),
				'description' => __( 'Enter a term', 'jwa_car' ),
			],
			[
				'type_field'  => 'select-page',
				'section'     => 'jwa_car_from',
				'id'          => 'page_form',
				'term'        => 'page',
				'page'        => 'listing-settings',
				'name'        => __( 'Select page form', 'jwa_car' ),
				'description' => __( 'Select Page Apply form', 'jwa_car' ),
			],
			[
				'type_field'  => 'text',
				'section'     => 'jwa_car_rest',
				'id'          => 'token',
				'page'        => 'listing-settings',
				'name'        => __( 'Token by REST API', 'jwa_car' ),
				'description' => __( 'Enter a Token', 'jwa_car' ),
			],
			[
				'type_field' => 'button',
				'section'    => 'jwa_car_rest',
				'id'         => 'token_generate',
				'page'       => 'listing-settings',
				'name'       => __( 'Generate Token', 'jwa_car' ),
			],
			[
				'type_field'  => 'select',
				'section'     => 'jwa_car_style',
				'id'          => 'preset',
				'page'        => 'listing-settings',
				'name'        => __( 'Select Style', 'jwa_car' ),
				'description' => __( 'Select style preset', 'jwa_car' ),
				'options'     => [
					'default' => 'Default',
					'1'       => 'Style 1',
					'2'       => 'Style 2',
					'3'       => 'Style 3',
					'4'       => 'Style 4',
					'5'       => 'Style 5',
				],
			],
			[
				'type_field'  => 'radio',
				'section'     => 'jwa_car_style',
				'id'          => 'container',
				'page'        => 'listing-settings',
				'name'        => __( 'Select Container Style', 'jwa_car' ),
				'description' => __( 'Select Container Style', 'jwa_car' ),
				'options'     => [
					'0' => 'Box',
					'1' => 'Full Width',
				],
			],
			[
				'type_field' => 'text',
				'section'    => 'jwa_car_page_count',
				'id'         => 'post_count',
				'page'       => 'listing-settings',
				'name'       => __( 'Number of cars displayed', 'jwa_car' ),
			],
		];

		self::addSettingField( $fields );
	}

	/**
	 * Returns all theme options
	 */
	private static function getOptionsSettings () {
		return get_option( 'jwa_car_listing', false );
	}

	/**
	 * Add field function
	 *
	 * @param array $fields
	 */
	public function addSettingField ( array $fields ) {
		foreach ( $fields as $field ) {
			add_settings_field( $field['id'], $field['name'], [
				$this,
				'fieldTemplate',
			], $field['page'], $field['section'],
				$field );
		}
	}

	/**
	 * Output field
	 *
	 * @param array $args
	 */
	public function fieldTemplate ( array $args ) {
		$option = self::getOptionsSettings();
		switch ( $args['type_field'] ) {
			case 'text':
				echo ' <input type="text" name="jwa_car_listing[' . $args['id'] . ']" id="' . $args['id'] . '" value="' . ( $option ? $option[ $args['id'] ] : '' ) . '" />';
				echo '<p class="description" > ' . $args['description'] . ' </p > ';
				break;
			case 'select-page':
				$html = ' <select name="jwa_car_listing[' . $args['id'] . ']" id ="' . $args['id'] . '">';
				$html .= '<option value=""> ' . __( 'Select Form page Apply', 'jwa_car' ) . ' </option > ';

				$pages = self::getPagesName();

				if ( $pages ) {
					foreach ( $pages as $key => $page ) {
						$html .= '<option value="' . $key . '" ' . ( $option['page_form'] == $key ? 'selected' : '' ) . '> ' . $page . ' </option > ';
					}
				}
				$html .= '</select > ';
				$html .= '<p class="description" > ' . $args['description'] . ' </p> ';
				echo $html;
				break;
			case 'hidden':
				echo ' <input type="hidden" name="jwa_car_listing[' . $args['id'] . ']" id="' . $args['id'] . '" value="' . (
					$option ? $option[ $args['id'] ] : '' ) . '" />';
				break;
			case 'button':
				echo '<button  id="' . $args['id'] . '" class="button button-secondary">' . $args['name'] . '</button>';
				break;
			case 'select':
				$html = ' <select name="jwa_car_listing[' . $args['id'] . ']" id ="' . $args['id'] . '">';
				foreach ( $args['options'] as $key => $style ) {
					$html .= '<option value="' . $key . '" ' . ( $option['preset'] == $key ? 'selected' : '' ) . '> ' . $style . ' </option > ';
				}
				$html .= '</select > ';
				$html .= '<p class="description" > ' . $args['description'] . ' </p> ';
				echo $html;
				break;
			case 'radio':
				$html = '<div>';
				foreach ( $args['options'] as $key => $item ) {
					$html .= '<label><input name="jwa_car_listing[' . $args['id'] . ']" type="radio" value="' . $key . '" ' . (
						$option['container'] == $key ? 'checked' : '' ) . '>' .
					         $item
					         . '</label><br>';
				}
				$html .= '</div>';
				echo $html;
				break;

			case 'term_and_percent':
				$html = '<h5>New years car</h5>';
				$html .= "<input name='jwa_car_listing[{$args['id']}][new][percent]' type='text' value='" . ( $option ?
						$option[ $args['id'] ]['new']['percent'] : '' ) . "' placeholder='Percent' >";
				$html .= "<input name='jwa_car_listing[{$args['id']}][new][term]' type='text' value='" . ( $option ? $option[ $args['id'] ]['new']['term'] : '' ) . "' placeholder='Term'>";
				$html .= '<h5>1-2 years car</h5>';
				$html .= "<input name='jwa_car_listing[{$args['id']}][two_year][percent]' type='text' value='" . ( $option ?
						$option[ $args['id'] ]['two_year']['percent'] : '' ) . "' placeholder='Percent' >";
				$html .= "<input name='jwa_car_listing[{$args['id']}][two_year][term]' type='text' value='" . ( $option ?
						$option[ $args['id'] ]['two_year']['term'] : '' ) . "' placeholder='Term'>";
				$html .= '<h5>3-4 years car</h5>';
				$html .= "<input name='jwa_car_listing[{$args['id']}][three_year][percent]' type='text' value='" . ( $option ?
						$option[ $args['id'] ]['three_year']['percent'] : '' ) . "' placeholder='Percent' >";
				$html .= "<input name='jwa_car_listing[{$args['id']}][three_year][term]' type='text' value='" . ( $option ?
						$option[ $args['id'] ]['three_year']['term'] : '' ) . "' placeholder='Term'>";
				$html .= '<h5>5-6 years car</h5>';
				$html .= "<input name='jwa_car_listing[{$args['id']}][four][percent]' type='text' value='" . ( $option ?
						$option[ $args['id'] ]['four']['percent'] : '' ) . "' placeholder='Percent' >";
				$html .= "<input name='jwa_car_listing[{$args['id']}][four][term]' type='text' value='" . ( $option ?
						$option[ $args['id'] ]['four']['term'] : '' ) . "' placeholder='Term'>";
				$html .= '<h5>Default</h5>';
				$html .= "<input name='jwa_car_listing[{$args['id']}][default][percent]' type='text' value='" . ( $option ?
						$option[ $args['id'] ]['default']['percent'] : '' ) . "' placeholder='Percent' >";
				$html .= "<input name='jwa_car_listing[{$args['id']}][default][term]' type='text' value='" . ( $option ?
						$option[ $args['id'] ]['default']['term'] : '' ) . "' placeholder='Term'>";
				echo $html;
				break;
		}
	}

	/**
	 * Returns an array for select
	 *
	 * @return array|false
	 */
	public function getPagesName () {
		$arg       = [
			'post_type'      => [ 'page' ],
			'posts_per_page' => - 1,
		];
		$pageArray = [];
		$query     = new \WP_Query( $arg );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$id               = get_the_ID();
				$pageArray[ $id ] = get_the_title( $id );
			}
			wp_reset_postdata();

			return $pageArray;
		} else {
			return false;
		}
	}

}
