<?php
/**
 * Created 11.11.2020
 * Version 1.0.2
 * Last update 17.12.2020
 * Author: Alex L
 *
 */

namespace JWA\Car\REST;

use JWA\Car\Helpers\biWeekly\jwaBiWeekly;
use JWA\Car\Helpers\postData\jwaPostData;

if ( ! defined( 'ABSPATH' ) ) {
	die ( 'Silly human what are you doing here' );
}

class jwaCarRESTApi {
	public function __construct () {
		add_action( 'rest_api_init', [ $this, 'addRegisterRest' ] );
	}

	public function addRegisterRest () {
		register_rest_route( JWA_REST_NAMESPACE, '/cars/(?P<id>[\d]+)', [
			[
				'methods'  => 'GET',
				'callback' => [ $this, 'getCarByID' ],
			],
			[
				'methods'  => 'POST',
				'callback' => [ $this, 'updateCarStatusByID' ],
				'args'     => [
					'token'          => [ 'type' => 'string', 'required' => false ],
					'ID'             => [ 'type' => 'integer', 'required' => true ],
					'carStatus'      => [ 'type' => 'string', 'required' => true ],
					'vin'            => [ 'type' => 'string', 'required' => false ],
					'carStockNumber' => [ 'type' => 'string', 'required' => false ],
					'cityMpg'        => [ 'type' => 'string', 'required' => false ],
					'highwayMpg'     => [ 'type' => 'string', 'required' => false ],
					'carMark'        => [ 'type' => 'string', 'required' => false ],
					'carModel'       => [ 'type' => 'string', 'required' => false ],
					'carTrim'        => [ 'type' => 'string', 'required' => false ],
					'bodyType'       => [ 'type' => 'string', 'required' => false ],
					'fuelType'       => [ 'type' => 'string', 'required' => false ],
					'carYear'        => [ 'type' => 'string', 'required' => false ],
					'carMileage'     => [ 'type' => 'integer', 'required' => false ],
					'horsePower'     => [ 'type' => 'string', 'required' => false ],
					'carEngine'      => [ 'type' => 'string', 'required' => false ],
					'transmission'   => [ 'type' => 'string', 'required' => false ],
					'interiorColor'  => [ 'type' => 'string', 'required' => false ],
					'exteriorColor'  => [ 'type' => 'string', 'required' => false ],
					'airbagNum'      => [ 'type' => 'integer', 'required' => false ],
					'seatingNum'     => [ 'type' => 'integer', 'required' => false ],
					'doorsNum'       => [ 'type' => 'integer', 'required' => false ],
					'price'          => [ 'type' => 'array', 'required' => false ],
					'gallery'        => [ 'type' => 'array', 'required' => false ],
					'dealer'         => [ 'type' => 'array', 'required' => false ],
					'content'        => [ 'type' => 'string', 'required' => false ],
					'drivetrain'     => [ 'type' => 'string', 'required' => false ],
					'features'       => [ 'type' => 'string', 'required' => false ],
				],
			],

		] );

		register_rest_route( JWA_REST_NAMESPACE, '/cars-by-vin/(?P<vin>.+)', [
			[
				'methods'  => 'GET',
				'callback' => [ $this, 'getCarByVIN' ],
			],

		] );

		register_rest_route( JWA_REST_NAMESPACE, '/cars/', [
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'insertCar' ],
				'args'                => [
					'token'          => [ 'type' => 'string', 'required' => false ],
					'title'          => [ 'type' => 'string', 'required' => false ],
					'carStatus'      => [ 'type' => 'string', 'required' => true ],
					'vin'            => [ 'type' => 'string', 'required' => true ],
					'carStockNumber' => [ 'type' => 'string', 'required' => false ],
					'cityMpg'        => [ 'type' => 'string', 'required' => false ],
					'highwayMpg'     => [ 'type' => 'string', 'required' => false ],
					'carMark'        => [ 'type' => 'string', 'required' => true ],
					'carModel'       => [ 'type' => 'string', 'required' => true ],
					'carTrim'        => [ 'type' => 'string', 'required' => false ],
					'bodyType'       => [ 'type' => 'string', 'required' => false, 'default' => 'Sedan' ],
					'fuelType'       => [ 'type' => 'string', 'required' => false, 'default' => 'Gas' ],
					'carYear'        => [ 'type' => 'string', 'required' => true ],
					'carMileage'     => [ 'type' => 'integer', 'required' => false ],
					'horsePower'     => [ 'type' => 'string', 'required' => false ],
					'carEngine'      => [ 'type' => 'string', 'required' => false ],
					'transmission'   => [ 'type' => 'string', 'required' => false, 'default' => 'Automatic' ],
					'interiorColor'  => [ 'type' => 'string', 'required' => false ],
					'exteriorColor'  => [ 'type' => 'string', 'required' => false ],
					'airbagNum'      => [ 'type' => 'integer', 'required' => false ],
					'seatingNum'     => [ 'type' => 'integer', 'required' => false ],
					'doorsNum'       => [ 'type' => 'integer', 'required' => false ],
					'price'          => [ 'type' => 'array', 'required' => true ],
					'gallery'        => [ 'type' => 'array', 'required' => true ],
					'dealer'         => [ 'type' => 'array', 'required' => false ],
					'content'        => [ 'type' => 'string', 'required' => false ],
					'drivetrain'     => [ 'type' => 'string', 'required' => false ],
					'features'       => [ 'type' => 'string', 'required' => false ],
				],
				'permission_callback' => [ $this, 'tokenChecks' ],
			],
		] );


	}

	/**
	 * Car by ID endpoint GET
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return false|WP_Error|string
	 */
	public function getCarByID ( \WP_REST_Request $request ) {
		$post = get_posts( [ 'post_type' => JWA_CAR_POST_TYPE, 'p' => (int) $request['id'] ] );

		if ( empty( $post ) ) {
			return new WP_Error( 'no_posts', __( 'Car not found', 'jwa_car' ), [ 'status' => 404 ] );
		}


		return self::getCarInfo( $post );
	}


	/**
	 * Generate array to car by id
	 *
	 * @param $post
	 *
	 * @return array
	 */
	private function getCarInfo ( $post ) {
		$id            = $post[0]->ID;
		$galleryID     = get_post_meta( $id, 'jwa_car_image_gallery', true );
		$imagesGallery = explode( ',', $galleryID );
		$urlImage      = [];
		foreach ( $imagesGallery as $image ) {
			if ( ! empty( $image ) ) {
				$urlImage[] = wp_get_attachment_url( $image );
			}
		}

		$carAllInfo = [
			'carID'          => $id,
			'title'          => $post[0]->post_title,
			'carStatus'      => $post[0]->post_status,
			'carLink'        => get_the_permalink( $id ),
			'image'          => get_the_post_thumbnail( $id, 'full' ),
			'vin'            => get_post_meta( $id, 'jwa_car_vin', true ),
			'carStockNumber' => get_post_meta( $id, 'jwa_car_stock_number', true ),
			'cityMpg'        => get_post_meta( $id, 'jwa_car_city_mpg', true ),
			'highwayMpg'     => get_post_meta( $id, 'jwa_car_highway_mpg', true ),
			'drivetrain'     => get_post_meta( $id, 'jwa_car_drivetrain', true ),
			'carMark'        => [
				'name'   => get_term_by( 'id', get_post_meta( $id, 'jwa_car_mark', true ), 'mark' )->name,
				'termID' => get_post_meta( $id, 'jwa_car_mark', true ),
			],
			'carModel'       => [
				'name'   => get_term_by( 'id', get_post_meta( $id, 'jwa_car_model', true ), 'mark' )->name,
				'termID' => get_post_meta( $id, 'jwa_car_model', true ),
			],
			'carTrim'        => [
				'name'   => get_term_by( 'id', get_post_meta( $id, 'jwa_car_trim', true ), 'mark' )->name,
				'termID' => get_post_meta( $id, 'jwa_car_trim', true ),
			],
			'bodyType'       => get_post_meta( $id, 'jwa_car_body_type', true ),
			'fuelType'       => get_post_meta( $id, 'jwa_car_fuel_type', true ),
			'carYear'        => get_post_meta( $id, 'jwa_car_year', true ),
			'carMileage'     => get_post_meta( $id, 'jwa_car_mileage', true ),
			'horsePower'     => get_post_meta( $id, 'jwa_car_horse_power', true ),
			'carEngine'      => [
				'name'   => get_term_by( 'id', get_post_meta( $id, 'jwa_car_engine', true ), 'engine' )->name,
				'termID' => get_post_meta( $id, 'jwa_car_engine', true ),
			],
			'transmission'   => get_post_meta( $id, 'jwa_car_transmission', true ),
			'interiorColor'  => get_post_meta( $id, 'jwa_car_interior', true ),
			'exteriorColor'  => get_post_meta( $id, 'jwa_car_exterior', true ),
			'airbagNum'      => get_post_meta( $id, 'jwa_car_airbag', true ),
			'seatingNum'     => get_post_meta( $id, 'jwa_car_seating', true ),
			'doorsNum'       => get_post_meta( $id, 'jwa_car_doors', true ),
			'features'       => implode( ', ', get_post_meta( $id, 'jwa_car_features', true ) ),
			'price'          => [
				'regular'  => get_post_meta( $id, 'jwa_car_price', true ),
				'sales'    => get_post_meta( $id, 'jwa_car_sales_price', true ),
				'biweekly' => get_post_meta( $id, 'jwa_car_biweekly', true ),
			],
			'gallery'        => [
				'ids'  => $galleryID,
				'urls' => $urlImage,
			],
			'dealer'         => [
				'city'        => get_post_meta( $id, 'jwa_car_city', true ),
				'postal_code' => get_post_meta( $id, 'jwa_car_postal_code', true ),
				'province'    => get_post_meta( $id, 'jwa_car_province', true ),
				'address'     => get_post_meta( $id, 'jwa_car_address', true ),
				'dealerName'  => get_post_meta( $id, 'jwa_car_dealer_name', true ),
			],

		];

		return $carAllInfo;
	}

	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed
	 */
	public function insertCar ( \WP_REST_Request $request ) {
		return self::createCar( $request );
	}

	private function createCar ( \WP_REST_Request $request ) {
		$params   = $request->get_params();
		$postData = new jwaPostData();

		if ( ! empty( $params['price'][0]['regular'] ) ) {
			$biweekly = new jwaBiWeekly( $params['price'][0]['regular'], $postData->getPercentByYear( $params['carYear'] ),
				$postData->getLoanTermByYear( $params['carYear'] ) );
		} else {
			$biweekly = new jwaBiWeekly( $params['price'][1]['sales'], $postData->getPercentByYear( $params['carYear'] ), $postData->getLoanTermByYear( $params['carYear'] ) );
		}

		$makeTerm = get_term_by( 'name', $params['carMark'], 'mark' );
		if ( empty( $makeTerm ) ) {
			$termMark = $postData->createTerm( $params['carMark'], 'mark' );
		}

		$modelTerm = get_term_by( 'name', $params['carModel'], 'mark' );
		if ( empty( $modelTerm ) ) {
			$termModel = $postData->createTerm( $params['carModel'], 'mark', $params['carMark'] );
		}

		$trimTerm = get_term_by( 'name', $params['carTrim'], 'mark' );
		if ( empty( $trimTerm ) ) {
			$termTrim = $postData->createTerm( $params['carTrim'], 'mark', $params['carModel'] );
		}

		$engineTerm = get_term_by( 'name', $params['carEngine'], 'engine' );
		if ( empty( $engineTerm ) ) {
			$engine = $postData->createTerm( $params['carEngine'], 'engine', 0 );
		}

		$post_data = [
			'post_title'   => $postData->getTitleGenerate( $params['carYear'], $params['carMark'], $params['carModel'], $params['carTrim'] ),
			'post_status'  => $params['carStatus'],
			'post_author'  => 1,
			'post_type'    => JWA_CAR_POST_TYPE,
			'post_name'    => $postData->getSlugGenerate( $params['carYear'], $params['carMark'], $params['carModel'], $params['carTrim'], $params['vin'] ),
			'post_content' => ( ! empty( $params['content'] ) ? wp_kses_allowed_html( $params['content'] ) : '' ),
			'meta_input'   => [
				'jwa_car_vin'          => $params['vin'],
				'jwa_car_stock_number' => $params['carStockNumber'],
				'jwa_car_city_mpg'     => $params['cityMpg'],
				'jwa_car_highway_mpg'  => $params['highwayMpg'],
				'jwa_car_body_type'    => $params['bodyType'],
				'jwa_car_fuel_type'    => $params['fuelType'],
				'jwa_car_year'         => $params['carYear'],
				'jwa_car_mileage'      => $params['carMileage'],
				'jwa_car_horse_power'  => $params['horsePower'],
				'jwa_car_transmission' => $params['transmission'],
				'jwa_car_drivetrain'   => $params['drivetrain'],
				'jwa_car_features'     => explode( '|', $params['features'] ),
				'jwa_car_interior'     => $params['interiorColor'],
				'jwa_car_exterior'     => $params['exteriorColor'],
				'jwa_car_airbag'       => $params['airbagNum'],
				'jwa_car_seating'      => $params['seatingNum'],
				'jwa_car_doors'        => $params['doorsNum'],
				'jwa_car_price'        => $params['price'][0]['regular'],
				'jwa_car_sales_price'  => $params['price'][1]['sales'],
				'jwa_car_biweekly'     => $biweekly->getPriceBiWeekly(),
				'jwa_car_city'         => $params['dealer'][0]['city'],
				'jwa_car_postal_code'  => $params['dealer'][1]['postal_code'],
				'jwa_car_province'     => $params['dealer'][2]['province'],
				'jwa_car_address'      => $params['dealer'][3]['address'],
				'jwa_car_dealer_name'  => $params['dealer'][4]['dealerName'],
				'jwa_car_mark'         => ( isset( $termMark ) ? $termMark : $makeTerm->term_id ),
				'jwa_car_model'        => ( isset( $termModel ) ? $termModel : $modelTerm->term_id ),
				'jwa_car_trim'         => ( isset( $termTrim ) ? $termTrim : $trimTerm->term_id ),
				'jwa_car_engine'       => ( isset( $engine ) ? $engine : $engineTerm->term_id ),
			],
		];
		$post_id   = wp_insert_post( $post_data, true );
		if ( is_wp_error( $post_id ) ) {
			return [ 'statusCode' => 500, 'error' => $post_id->get_error_message() ];
		}

		$make       = $postData->setMarkCar( $post_data['meta_input']['jwa_car_mark'], $post_id );
		$model      = $postData->setModelCar( $post_data['meta_input']['jwa_car_model'], $post_id );
		$trim       = $postData->setTrimCar( $post_data['meta_input']['jwa_car_trim'], $post_id );
		$engine     = $postData->setEngineCar( $post_data['meta_input']['jwa_car_engine'], $post_id );
		$gallery    = $postData->uploadImagGallery( $params['gallery'], $post_id );
		$thumbnail  = $postData->setThumbnailCar( $gallery, $post_id );
		$galleryIDs = update_post_meta( $post_id, 'jwa_car_image_gallery', implode( ',', $gallery ) );


		return [
			'statusCode'   => 200,
			"car_id"       => $post_id,
			'upload_image' => $gallery,
		];
	}

	/**
	 * Token access
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return bool
	 */
	public function tokenChecks ( \WP_REST_Request $request ) {
		$token = $request['token'];

		return $token == JWA_REST_TOKEN ? true : false;
	}

	/**
	 * Get car by VIN
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return array
	 */
	public function getCarByVIN ( \WP_REST_Request $request ) {
		$car = new jwaPostData();

		return $car->searchCarByVin( $request->get_param( 'vin' ), 'ID' );
	}

	/**
	 * Update Status Car
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return array
	 */
	public function updateCarStatusByID ( \WP_REST_Request $request ) {
		$statusUpdate = new jwaPostData();

		return $statusUpdate->updateCarStatus( $request->get_param( 'ID' ), $request->get_param( 'carStatus' ), $request->get_params() );
	}
}
