<?php
/**
 * Created 06.11.2020
 * Version 1.0.3
 * Last update 15.12.2020
 * Author: Alex L
 *
 */

namespace JWA\Car\Filter;

use JWA\Car\Helpers\taxCar\taxCar;

if ( ! defined( 'ABSPATH' ) ) {
	die ( 'Silly human what are you doing here' );
}

class jwaFilterCar {
	private $carTax;

	public function __construct () {

		$this->carTax = new taxCar();

		add_action( 'wp_ajax_get_filters_car', [ $this, 'getModelByMakeAjax' ] );
		add_action( 'wp_ajax_nopriv_get_filters_car', [ $this, 'getModelByMakeAjax' ] );

		add_action( 'wp_ajax_get_filters_car_horizons', [ $this, 'getModelByMakeAjaxHorizonsFilter' ] );
		add_action( 'wp_ajax_nopriv_get_filters_car_horizons', [ $this, 'getModelByMakeAjaxHorizonsFilter' ] );

		add_action( 'admin_post_nopriv_jwa_car_filter', [ $this, 'carFilterHandler' ] );
		add_action( 'admin_post_jwa_car_filter', [ $this, 'carFilterHandler' ] );


	}


	public function getSearchQuery () {
		$search = $_POST['search'];

		$query = new \WP_Query( 's=' . $search . '&post_type=' . JWA_CAR_POST_TYPE );

		if ( $query->have_posts() ) {
			ob_start();
			while ( $query->have_posts() ) {
				$query->the_post();
				$ids[]           = get_the_ID();
				$theme_files     = [ '/template_part/car_item.php' ];
				$exists_in_theme = locate_template( $theme_files, false );
				if ( $exists_in_theme != '' ) {
					include $exists_in_theme;
				} else {
					include JWA_CAR_PLUGIN_DIR . '/template/carListing/car_item.php';
				}
			}
			if ( function_exists( 'wp_pagenavi' ) ) {
				wp_pagenavi( [ 'query' => $query ] );
			}
			wp_reset_postdata();
			wp_send_json_success( [
				'message' => 'ok',
				'html'    => ob_get_clean(),
			] );
		} else {
			wp_send_json_success( [
				'message' => __( 'Cars not found', 'jwa_car' ),
				'html'    => '<p class="not-found">' . __( 'Cars not found', 'jwa_car' ) . '</p>',
			] );
		}

		wp_reset_postdata();
	}

	/**
	 * Converts a delimited string to an array of integers
	 *
	 * @param string $string
	 * @param string $delimiter
	 *
	 * @return array
	 */
	public function stringToArrayInt ( string $string, string $delimiter ) {
		$arrayString = explode( $delimiter, $string );
		$arrayInt    = [];
		foreach ( $arrayString as $item ) {
			$arrayInt[] = $item;
		}

		return $arrayInt;
	}

	/**
	 * Create Query Arguments
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	public function getQueryArgs ( array $params ) {
		$count = get_option( 'jwa_car_listing', false );
		$arg   = [
			'post_type'      => JWA_CAR_POST_TYPE,
			'posts_per_page' => isset( $count['post_count'] ) ? $count['post_count'] : 10,
			'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
			'post_status'    => 'publish',
		];

		if ( isset( $params['make'] ) && ! empty( $params['make'] ) ) {
			$arg['tax_query'] = [
				[
					'taxonomy'         => 'mark',
					'field'            => 'slug',
					'terms'            => $params['make'],
					'include_children' => false,
				],
			];
		}

		if ( isset( $params['model'] ) && ! empty( $params['model'] ) ) {
			$arg['tax_query'] = [
				[
					'taxonomy'         => 'mark',
					'field'            => 'slug',
					'terms'            => $params['model'],
					'include_children' => false,
				],
			];
		}

		if ( isset( $params['engine'] ) && ! empty( $params['engine'] ) ) {
			$arg['tax_query'] = [
				[
					'taxonomy'         => 'engine',
					'field'            => 'slug',
					'terms'            => $params['engine'],
					'include_children' => false,
				],
			];
		}

		if ( isset( $params['car-year'] ) && ! empty( $params['car-year'] ) ) {
			$queryYearArray             = [];
			$queryYearArray['relation'] = 'OR';

			if ( is_array( $params['car-year'] ) ) {
				foreach ( $params['car-year'] as $year ) {
					$queryYearArray[] = [
						'key'   => 'jwa_car_year',
						'value' => $year,
						'type'  => 'NUMERIC',
					];
				}
				$arg['meta_query'][] = $queryYearArray;
			} else {
				if ( strlen( $params['car-year'] ) == 4 ) {
					$arg['meta_query'][] = [
						'relation' => 'AND',
						[
							'key'   => 'jwa_car_year',
							'value' => $params['car-year'],
							'type'  => 'NUMERIC',
						],
					];
				} else {
					$arg['meta_query'][] = [
						'relation' => 'AND',
						[
							'key'     => 'jwa_car_year',
							'value'   => self::stringToArrayInt( $params['car-year'], '-' ),
							'type'    => 'NUMERIC',
							'compare' => "BETWEEN",
						],
					];
				}
			}
		}

		if ( isset( $params['mileage'] ) && ! empty( $params['mileage'] ) ) {
			$arg['meta_query'][] = [
				'relation' => 'AND',
				[
					'key'     => 'jwa_car_mileage',
					'value'   => self::stringToArrayInt( $params['mileage'], '-' ),
					'type'    => 'NUMERIC',
					'compare' => "BETWEEN",
				],
			];
		}

		if ( isset( $params['price'] ) && ! empty( $params['price'] ) ) {
			$arg['meta_query'][] =
				[
					'relation' => 'AND',
					[
						'key'     => 'jwa_car_price',
						'value'   => self::stringToArrayInt( $params['price'], '-' ),
						'type'    => 'NUMERIC',
						'compare' => "BETWEEN",
					],
//					[
//						'key'     => 'jwa_car_sales_price',
//						'value'   => self::stringToArrayInt( $params['price'], '-' ),
//						'type'    => 'NUMERIC',
//						'compare' => "BETWEEN",
//					],
			];
		}

		if ( isset( $params['body-type'] ) && ! empty( $params['body-type'] ) ) {
			$arg['meta_query'][] = [
				'relation' => 'AND',
				[
					'key'   => 'jwa_car_body_type',
					'value' => str_replace( '%20', ' ', $params['body-type'] ),
				],
			];
		}

		if ( isset( $params['fuel-type'] ) && ! empty( isset( $params['fuel-type'] ) ) ) {
			if ( is_array( $params['fuel-type'] ) ) {
				$typeArray             = [];
				$typeArray['relation'] = 'OR';
				foreach ( $params['fuel-type'] as $type ) {
					$typeArray[] = [
						'key'   => 'jwa_car_fuel_type',
						'value' => $type,
					];
				}
				$arg['meta_query'] = $typeArray;
			} else {
				$arg['meta_query'][] = [
					'relation' => 'AND',
					[
						'key'   => 'jwa_car_fuel_type',
						'value' => $params['fuel-type'],
					],
				];
			}

		}

		if ( isset( $params['exterior-color'] ) && ! empty( isset( $params['exterior-color'] ) ) ) {
			if ( is_array( $params['exterior-color'] ) ) {
				$exteriorArray             = [];
				$exteriorArray['relation'] = 'OR';
				foreach ( $params['exterior-color'] as $color ) {
					$exteriorArray[] = [
						'key'   => 'jwa_car_exterior',
						'value' => $color,
					];
				}
				$arg['meta_query'][] = $exteriorArray;
			} else {
				$arg['meta_query'][] = [
					'relation' => 'AND',
					[
						'key'     => 'jwa_car_exterior',
						'value'   => $params['exterior-color'],
						'compare' => '=',
					],
				];
			}

		}

		if ( isset( $params['interior-color'] ) && ! empty( isset( $params['interior-color'] ) ) ) {
			$arg['meta_query'][] = [
				'relation' => 'AND',
				[
					'key'   => 'jwa_car_interior',
					'value' => $params['interior-color'],
				],
			];
		}

		if ( isset( $params['location'] ) && ! empty( isset( $params['location'] ) ) ) {
			$arg['meta_query'][] = [
				'relation' => 'OR',
				[
					'key'         => 'jwa_car_city',
					'value'       => $params['location'],
					'compare_key' => 'LIKE',
				],
				[
					'key'         => 'jwa_car_postal_code',
					'value'       => $params['location'],
					'compare_key' => 'LIKE',
				],
				[
					'key'         => 'jwa_car_province',
					'value'       => $params['location'],
					'compare_key' => 'LIKE',
				],
				[
					'key'         => 'jwa_car_address',
					'value'       => $params['location'],
					'compare_key' => 'LIKE',
				],
			];
		}
		$code_match = [
			'"',
			'!',
			'@',
			'#',
			'$',
			'%',
			'^',
			'&',
			'*',
			'(',
			')',
			'_',
			'+',
			'{',
			'}',
			'|',
			':',
			'"',
			'<',
			'>',
			'?',
			'[',
			']',
			';',
			"'",
			',',
			'.',
			'/',
			'',
			'~',
			'`',
			'=',
		];


		if ( isset( $params['search'] ) && ! empty( $params['search'] ) ) {
			unset( $arg['meta_query'] );
			unset( $arg['tax_query'] );
			$arg['s'] = str_replace( $code_match, '', $params['search'] );
		}

		return $arg;
	}

	/**
	 * Generate Url Redirect
	 */
	public function carFilterHandler () {
		$request = $_REQUEST['jwa_filter'];
		$params  = [];
		foreach ( $request as $key => $item ) {
			if ( ! empty( $item ) ) {
				$params[ $key ] = urlencode( $item );
			}
		}
		$redirect = get_bloginfo( 'url' ) . '/cars/filter/';
		$i        = 0;
		foreach ( $params as $key => $param ) {
			if ( $i == 0 ) {
				$redirect .= $key . '-in-' . $param;
			} else {
				if ( ! is_array( $param ) ) {
					$redirect .= '-and-' . $key . '-in-' . $param;

				} else {
					foreach ( $param as $i => $item ) {
						if ( $i == 0 ) {
							$redirect .= '-and-' . $key . '-in-' . $item;
						} else {
							$redirect .= '-or-' . $item;
						}
					}
				}
			}
			$i ++;
		}
		wp_redirect( $redirect, 302 );
		die();
	}

	/**
	 * Ajax get Model and Year by Mark
	 */
	public function getModelByMakeAjax () {
		if ( ! empty( $_POST['make'] ) ) {
			$model = new taxCar();
			wp_send_json_success( [
				'make' => $model->getModelBySlug( $_POST['make'] ),
				'year' => $model->getYearsByModelSelect( $_POST['make'] ),
			] );
		}

		return wp_send_json_error( [ 'message' => 'Error Empty make' ] );
	}

	public function getModelByMakeAjaxHorizonsFilter () {
		if ( ! empty( $_POST['make'] ) ) {
			$model = new taxCar();
			wp_send_json_success( [
				'make'    => $model->getModelBySlug( $_POST['make'] ),
				'year'    => $model->getModelsYear( $_POST['make'] ),
				'mileage' => $model->getMileageByTerm( $_POST['make'] ),
				'price'   => $model->getPriceByTerm( $_POST['make'] ),
			] );
		}
	}

	/**
	 * Outputs the parameter filtering label
	 *
	 * @param            $params
	 * @param array|null $args
	 *
	 * @return false|string
	 */
	public function filterChoiceLabelOutput ( $params, array $args = null ) {
		ob_start();

		include JWA_CAR_PLUGIN_DIR . '/template/archive/archive-filter-label.php';

		return ob_get_clean();
	}
}
