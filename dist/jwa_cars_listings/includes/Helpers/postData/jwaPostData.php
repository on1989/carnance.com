<?php
/**
 * Created 11.11.2020
 * Version 1.0.5
 * Last update 17.12.2020
 * Author: Alex L
 *
 */

namespace JWA\Car\Helpers\postData;

use JWA\Car\Helpers\biWeekly\jwaBiWeekly;

if ( ! defined( 'ABSPATH' ) ) {
	die ( 'Silly human what are you doing here' );
}

class jwaPostData {
	public function __construct () {

	}

	/**
	 * Generate Car Slug
	 *
	 * @param $year
	 * @param $make
	 * @param $model
	 * @param $trim
	 * @param $vin
	 *
	 * @return string
	 */
	public function getSlugGenerate ( $year, $make, $model, $trim, $vin ) {
		$makeTerm  = get_term_by( 'name', $make, 'mark' );
		$modelTerm = get_term_by( 'name', $model, 'mark' );
		$trimTerm  = get_term_by( 'name', $trim, 'mark' );

		$slugString = 'buy-' . ( ! empty( $year ) ? $year . '-' : '' ) . ( ! empty( $makeTerm ) ? $makeTerm->name . '-' :
				'' )
		              . ( ! empty( $modelTerm )
				? $modelTerm->name . '-' : '' ) . ( ! empty( $trimTerm ) ? $trimTerm->name . '-' : '' ) . substr( $vin, - 8 );

		return sanitize_title( $slugString );
	}


	/**
	 * Generate Title Car
	 *
	 * @param $year
	 * @param $make
	 * @param $model
	 * @param $trim
	 *
	 * @return string
	 */
	public function getTitleGenerate ( $year, $make, $model, $trim ) {
		$makeTerm  = get_term_by( 'name', $make, 'mark' );
		$modelTerm = get_term_by( 'name', $model, 'mark' );
		$trimTerm  = get_term_by( 'name', $trim, 'mark' );


		$titleString = ( ! empty( $year ) ? $year . ' | ' : '' ) . ( ! empty( $makeTerm ) ? $makeTerm->name . ' | ' : '' ) . (
			! empty( $modelTerm ) ? $modelTerm->name : '' ) .
		               ( ! empty( $trimTerm ) ? ' | ' . $trimTerm->name : '' );

		return $titleString;
	}

	/**
	 * Create new Term
	 *
	 * @param $termName
	 * @param $taxName
	 * @param $parentID
	 *
	 * @return false|int|mixed
	 */
	public function createTerm ( $termName, $taxName, $parent = null ) {
		if ( $parent ) {
			$parent_term    = term_exists( $parent, $taxName );
			$parent_term_id = $parent_term['term_id'];

			$insert_data = wp_insert_term(
				$termName,
				$taxName,
				[
					'description' => '',
					'slug'        => '',
					'parent'      => $parent_term_id,
				]
			);
		} else {
			$insert_data = wp_insert_term(
				$termName,
				$taxName,
				[
					'description' => '',
					'slug'        => '',
				]
			);
		}

		if ( ! is_wp_error( $insert_data ) ) {
			return $insert_data['term_id'];
		}

		return false;
	}

	/**
	 * Set Mark Taxonomy
	 *
	 * @param $markID
	 * @param $postID
	 */
	public function setMarkCar ( $markID, $postID ) {
		if ( ! empty( $markID ) ) {
			wp_set_post_terms( $postID, (int) $markID, 'mark', true );
		}
	}

	/**
	 * Set Model Taxonomy
	 *
	 * @param $markID
	 * @param $postID
	 */
	public function setModelCar ( $markID, $postID ) {
		if ( ! empty( $markID ) ) {
			wp_set_post_terms( $postID, (int) $markID, 'mark', true );
		}
	}

	/**
	 * Set Trim Taxonomy
	 *
	 * @param $markID
	 * @param $postID
	 */
	public function setTrimCar ( $markID, $postID ) {
		if ( ! empty( $markID ) ) {
			wp_set_post_terms( $postID, (int) $markID, 'mark', true );
		}
	}

	/**
	 * Set Engine Taxonomy
	 *
	 * @param $markID
	 * @param $postID
	 */
	public function setEngineCar ( $markID, $postID ) {
		if ( ! empty( $markID ) ) {
			wp_set_post_terms( $postID, (int) $markID, 'engine', false );
		}
	}

	/**
	 * Upload Images Gallery
	 *
	 * @param array $images
	 *
	 * @return array
	 */
	public function uploadImagGallery ( array $images, $postID ) {
		$imagesID = [];
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		foreach ( $images as $key => $image ) {
			$image_url        = $image;
			$image_name       = basename( $image );
			$upload_dir       = wp_upload_dir();
			$image_data       = file_get_contents( $image_url );
			$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name );
			$filename         = basename( $unique_file_name );

			if ( wp_mkdir_p( $upload_dir['path'] ) ) {
				$file = $upload_dir['path'] . '/' . $filename;
			} else {
				$file = $upload_dir['basedir'] . '/' . $filename;
			}

			if ( is_writable( $upload_dir['path'] ) ) {
				file_put_contents( $file, $image_data );
			} else {
				$error = new \WP_Error( 'error_key', 'Permission Denied to file Upload', 403 );

				return $error;
			}

			$wp_filetype = wp_check_filetype( $filename, null );

			$attachment = [
				'guid'           => $upload_dir['url'] . '/' . $filename,
				'post_mime_type' => $wp_filetype['type'],
				'post_title'     => $filename,
				'post_content'   => '',
				'post_status'    => 'inherit',
			];

			$attach_id  = wp_insert_attachment( $attachment, $file, 0 );
			$imagesID[] = $attach_id;

			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
			wp_update_attachment_metadata( $attach_id, $attach_data );
		}

		return $imagesID;
	}

	/**
	 * Search Car by VIN
	 *
	 * @param string $vin
	 * @param null   $key
	 *
	 * @return array|void|\WP_Query
	 */
	public function searchCarByVin ( string $vin, $key = null ) {

		$arg = [
			'post_type'      => JWA_CAR_POST_TYPE,
			'posts_per_page' => - 1,
			'post_status'    => [ 'publish', 'draft', 'pending', 'private', 'future' ],
			'meta_query'     => [
				[
					'key'   => 'jwa_car_vin',
					'value' => $vin,
				],
			],
		];

		$query = new \WP_Query( $arg );
		switch ( $key ) {
			case 'ID':
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();

						return [
							'statusCode' => 200,
							'ID'         => get_the_ID(),
							'carStatus'  => get_post_status( get_the_ID() ),
						];
					}
				} else {
					return [ 'statusCode' => 404, 'message' => 'Car not Found' ];
				}
				break;
			case 'OBJECT':
				return $query;
				break;
			case 'POST':
				return $query->the_post();
				break;
		}
	}

	/**
	 *
	 *
	 * @param int    $postID
	 * @param string $status
	 *
	 * @return array
	 */
	public function updateCarStatus ( int $postID, string $status, $params ) {
		$arg = [
			"ID"           => $postID,
			"post_status"  => $status,
			"post_content" => ( ! empty( $params['content'] ) ? wp_kses_allowed_html( $params['content'] ) : '' ),
		];

		$status = wp_update_post( wp_slash( $arg ) );

		if ( is_wp_error( $status ) ) {
			return [ 'statusCode' => 500, 'message' => $status->get_error_message() ];
		}

		if ( isset( $params['carMark'] ) ) {
			$mark = wp_get_post_terms( $postID, 'mark', [ 'fields' => 'ids' ] );
			wp_remove_object_terms( $postID, $mark, 'mark' );
		}
		foreach ( $params as $key => $item ) {
			switch ( $key ) {
				case'vin':
					update_post_meta( $postID, 'jwa_car_vin', $item );
					break;
				case 'carStockNumber':
					update_post_meta( $postID, 'jwa_car_stock_number', $item );
					break;
				case 'cityMpg':
					update_post_meta( $postID, 'jwa_car_city_mpg', $item );
					break;
				case 'highwayMpg':
					update_post_meta( $postID, 'jwa_car_highway_mpg', $item );
					break;
				case 'bodyType':
					$body = update_post_meta( $postID, 'jwa_car_body_type', $item );
					break;
				case 'fuelType':
					update_post_meta( $postID, 'jwa_car_fuel_type', $item );
					break;
				case 'carYear':
					update_post_meta( $postID, 'jwa_car_year', $item );
					break;
				case 'carMileage':
					update_post_meta( $postID, 'jwa_car_mileage', $item );
					break;
				case 'horsePower':
					update_post_meta( $postID, 'jwa_car_horse_power', $item );
					break;
				case 'drivetrain':
					update_post_meta( $postID, 'jwa_car_drivetrain', $item );
					break;
				case 'features':
					update_post_meta( $postID, 'jwa_car_features', explode( '|', $item ) );
					break;
				case 'transmission':
					update_post_meta( $postID, 'jwa_car_transmission', $item );
					break;
				case 'interiorColor':
					update_post_meta( $postID, 'jwa_car_interior', $item );
					break;
				case 'exteriorColor':
					update_post_meta( $postID, 'jwa_car_exterior', $item );
					break;
				case 'airbagNum':
					update_post_meta( $postID, 'jwa_car_airbag', $item );
					break;
				case 'seatingNum':
					update_post_meta( $postID, 'jwa_car_seating', $item );
					break;
				case 'doorsNum':
					update_post_meta( $postID, 'jwa_car_doors', $item );
					break;
				case 'dealer':
					$test = $item;
					if ( ! empty( $item['city'] ) ) {
						update_post_meta( $postID, 'jwa_car_city', $item['city'] );
					}

					if ( ! empty( $item['postal_code'] ) ) {
						update_post_meta( $postID, 'postal_code', $item['postal_code'] );
					}

					if ( ! empty( $item['province'] ) ) {
						update_post_meta( $postID, 'jwa_car_province', $item['jwa_car_province'] );
					}
					if ( ! empty( $item['address'] ) ) {
						update_post_meta( $postID, 'jwa_car_address', $item['jwa_car_address'] );
					}

					if ( ! empty( $item['dealerName'] ) ) {
						update_post_meta( $postID, 'jwa_car_dealer_name', $item['jwa_car_dealer_name'] );
					}

					break;
				case 'carMark':
					$makeTerm = get_term_by( 'name', $item, 'mark' );
					if ( ! $makeTerm ) {
						$mark = self::createTerm( $item, 'mark' );
					}
					if ( ! empty( $makeTerm ) && ! is_wp_error( $makeTerm ) ) {
						$termID = self::setMarkCar( $makeTerm->term_id, $postID );
						update_post_meta( $postID, 'jwa_car_mark', $makeTerm->term_id );
					} else {
						$termID = self::setMarkCar( $mark, $postID );
						update_post_meta( $postID, 'jwa_car_mark', $mark );
					}
					break;
				case 'carModel':
					$modelTerm = get_term_by( 'name', $item, 'mark' );
					if ( empty( $modelTerm ) ) {
						$model = self::createTerm( $item, 'mark', $params['carMark'] );
					}
					if ( ! empty( $modelTerm ) && ! is_wp_error( $modelTerm ) ) {
						$termID = self::setModelCar( $modelTerm->term_id, $postID, $params['carMark'] );
						update_post_meta( $postID, 'jwa_car_model', $modelTerm->term_id );
						$mark = get_term_by( 'name', $params['carMark'], 'mark' );
						wp_update_term( $modelTerm->term_id, 'mark', [ 'parent' => $mark->term_id ] );
					} else {
						$termID = self::setModelCar( $model, $postID, $params['carMark'] );
						update_post_meta( $postID, 'jwa_car_model', $model );
						$mark = get_term_by( 'name', $params['carMark'], 'mark' );
						wp_update_term( $modelTerm->term_id, 'mark', [ 'parent' => $mark->term_id ] );
					}
					break;
				case 'carTrim':
					$trimTerm = get_term_by( 'name', $item, 'mark' );
					if ( empty( $trimTerm ) ) {
						$trim = self::createTerm( $item, 'mark', $params['carModel'] );
					}
					if ( ! empty( $trimTerm ) && ! is_wp_error( $trimTerm ) ) {
						self::setTrimCar( $trimTerm->term_id, $postID, $params['carModel'] );
						update_post_meta( $postID, 'jwa_car_trim', $trimTerm->term_id );
						$model = get_term_by( 'name', $params['carModel'], 'mark' );
						wp_update_term( $trimTerm->term_id, 'mark', [ 'parent' => $model->term_id ] );
					} else {
						$termID = self::setTrimCar( $trim, $postID, $params['carModel'] );
						update_post_meta( $postID, 'jwa_car_trim', $trim );
						$model = get_term_by( 'name', $params['carModel'], 'mark' );
						wp_update_term( $trimTerm->term_id, 'mark', [ 'parent' => $model->term_id ] );
					}
					break;
				case 'carEngine':
					$engineTerm = get_term_by( 'name', $item, 'engine' );
					if ( empty( $engineTerm ) && ! is_wp_error( $engineTerm ) ) {
						$engine = self::createTerm( $item, 'engine', 0 );
					}
					if ( ! empty( $engineTerm ) && ! is_wp_error( $engineTerm ) ) {
						self::setEngineCar( $engineTerm->term_id, $postID );
						update_post_meta( $postID, 'jwa_car_engine', $engineTerm->term_id );
					} else {
						self::setEngineCar( $engine, $postID );
						update_post_meta( $postID, 'jwa_car_engine', $engine );
					}
					break;
			}
		}

		if ( isset( $params['gallery'] ) && ! empty( $params['gallery'] ) ) {
			$gallery = self::uploadImagGallery( $params['gallery'], $postID );
			if ( ! is_wp_error( $gallery ) ) {
				update_post_meta( $postID, 'jwa_car_image_gallery', $gallery );
			}
		}

		$galleryID = get_post_meta( $postID, 'jwa_car_image_gallery', true );
		$biweekly  = new jwaBiWeekly( $params['price'][0]['regular'], self::getPercentByYear( $params['carYear'] ),
			self::getLoanTermByYear( $params['carYear'] ) );

		update_post_meta( $postID, 'jwa_car_biweekly', $biweekly->getPriceBiWeekly() );

		self::setThumbnailCar( $galleryID, $postID );

		return [ 'statusCode' => 200, 'ID' => $status, 'dealer' => gettype( $test ) ];
	}

	/**
	 * If the thumbnail is not set it takes the first gallery picture
	 *
	 * @param $galleryArray
	 * @param $postID
	 */
	public function setThumbnailCar ( $galleryArray, $postID ) {
		$thumbnailsID = $galleryArray;

		if ( ! has_post_thumbnail( $postID ) ) {
			if ( is_array( $galleryArray ) ) {
				$set = set_post_thumbnail( $postID, (int) $thumbnailsID[0] );
			} else if ( is_string( $galleryArray ) ) {
				$set = set_post_thumbnail( $postID, (int) $thumbnailsID );
			} else {
				$galIDs = explode( ',', $galleryArray );
				$set    = set_post_thumbnail( $postID, (int) $galIDs[0] );
			}
		}
	}

	/**
	 * Parse string URL
	 *
	 * @param string $urlQuery
	 *
	 * @return array|false
	 */
	public function parseURLQuery ( string $urlQuery ) {
		$paramString = $urlQuery;
		$queryArg    = [];
		if ( ! empty( $paramString ) ) {
			$params = explode( '-and-', $paramString );

			foreach ( $params as $param ) {
				$item = explode( '-in-', urldecode( $param ) );
				if ( preg_match( '-or-', $item[1] ) ) {
					$items                = explode( '-or-', $item[1] );
					$queryArg[ $item[0] ] = $items;
				} else {
					$queryArg[ $item[0] ] = $item[1];
				}

			}

			return $queryArg;
		} else {
			return false;
		}
	}

	/**
	 * Get Loan Term
	 *
	 * @param string|init $year
	 *
	 * @return init
	 */
	public function getLoanTermByYear ( $year ) {
		$loanTerm     = get_option( 'jwa_car_listing' )['term_and_percent'];
		$current_year = date( 'Y' );
		switch ( $year ) {
			case ( $year > $current_year && ! empty( $loanTerm['new']['term'] ) ):
				return $loanTerm['new']['term'];
				break;
			case ( $current_year - 2 <= $year && ! empty( $loanTerm['new']['term'] ) ):
				return $loanTerm['two_year']['term'];
				break;
			case ( $current_year - 4 <= $year && ! empty( $loanTerm['new']['term'] ) ):
				return $loanTerm['three_year']['term'];
				break;
			case ( $current_year - 6 <= $year && ! empty( $loanTerm['new']['term'] ) ):
				return $loanTerm['four']['term'];
				break;
			default:
				return $loanTerm['default']['term'];
		}
	}

	/**
	 * Returns the interest rate on a loan
	 *
	 * @param string|init $year
	 *
	 * @return float|init
	 */
	public function getPercentByYear ( $year ) {
		$percent = get_option( 'jwa_car_listing' )['term_and_percent'];

		$carrent_year = date( 'Y' );
		switch ( $year ) {
			case ( $year > $carrent_year && ! empty( $percent['new']['percent'] ) ):
				return $percent['new']['percent'];
				break;
			case ( $carrent_year - 2 <= $year && ! empty( $percent['new']['percent'] ) ):
				return $percent['two_year']['percent'];
				break;
			case ( $carrent_year - 4 <= $year && ! empty( $percent['new']['percent'] ) ):
				return $percent['three_year']['percent'];
				break;
			case ( $carrent_year - 6 <= $year && ! empty( $percent['new']['percent'] ) ):
				return $percent['four']['percent'];
				break;
			default:
				return $percent['default']['percent'];
		}
	}
}
