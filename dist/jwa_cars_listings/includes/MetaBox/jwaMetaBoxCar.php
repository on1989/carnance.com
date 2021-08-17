<?php
/**
 * Created 26.10.2020
 * Version 1.0.1
 * Last update 17.12.2020
 * Author: Alex L
 *
 */

namespace JWA\Car\MetaBox;

use JWA\Car\Helpers\biWeekly\jwaBiWeekly;

if ( ! defined( 'ABSPATH' ) ) {
	die ( 'Silly human what are you doing here' );
}

/**
 * Class jwaMetaBoxCar
 *
 * Create Meta Box in Car Post Type
 *
 * @package JWA\Car\MetaBox
 */
class jwaMetaBoxCar {
	private $postTypeName;


	public function __construct ( string $postTypeName ) {
		$this->postTypeName = $postTypeName;

		add_action( 'add_meta_boxes', [ $this, 'addMetaBox' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'addScriptsAdmin' ] );

		add_action( 'wp_ajax_get_model', [ $this, 'getModelAjax' ] );
		add_action( 'wp_ajax_nopriv_get_model', [ $this, 'getModelAjax' ] );

		add_action( 'wp_ajax_get_trim', [ $this, 'getTrimAjax' ] );
		add_action( 'wp_ajax_nopriv_get_trim', [ $this, 'getTrimAjax' ] );

		add_action( 'wp_ajax_jwa_remove_gallery', [ $this, 'removeGallery' ] );
		add_action( 'wp_ajax_nopriv_jwa_remove_gallery', [ $this, 'removeGallery' ] );

		add_action( 'save_post', [ $this, 'savePostMeta' ], 10, 3 );
	}

	/**
	 * Add meta box
	 */
	public function addMetaBox () {
		add_meta_box( 'jwa_car_metabox', __( 'Car Details' ), [
			$this,
			'outputMetaBox',
		], [ $this->postTypeName ], 'advanced', 'high' );
	}

	/**
	 * Output meta box template
	 */
	public function outputMetaBox () {
		include_once JWA_CAR_PLUGIN_DIR . '/includes/template/admin/metabox/template.php';
	}

	/**
	 * Add Style & Scripts in admin panel
	 *
	 * @param $hook_suffix
	 */
	public function addScriptsAdmin ( $hook_suffix ) {

		if ( $hook_suffix == 'post-new.php' && $_GET['post_type'] == $this->postTypeName || $hook_suffix == 'post.php' ) {

			if ( ! did_action( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			}

			wp_enqueue_style( 'bootstrap', JWA_CAR_PLUGIN_URL . '/assets/css/bootstrap.min.css' );
			wp_enqueue_script( 'bootstrap', JWA_CAR_PLUGIN_URL . '/assets/js/bootstrap.min.js', [ 'jquery' ], JWA_CAR_VERSION,
				true );
			wp_enqueue_script( 'metabox-main', JWA_CAR_PLUGIN_URL . '/assets/js/admin/metabox/main.js', [ 'jquery' ],
				JWA_CAR_VERSION,
				true );

			wp_localize_script( 'metabox-main', 'jwa_car', [ 'url_ajax' => admin_url( 'admin-ajax.php' ) ] );
		}
	}

	/**
	 * This function works both with AJAX requests, gives the Auto Models depending on the brand
	 *
	 * @param null $term_id
	 *
	 * @return JSON
	 */
	public function getModelAjax ( $term_id = null ) {
		if ( isset( $_POST['make'] ) ) {
			$markID = $_POST['make'];
			if ( isset( $markID ) ) {
				$model = get_terms( [
					'taxonomy'   => 'mark',
					'hide_empty' => false,
					'parent'     => $markID,
				] );
				wp_send_json_success( [ 'model' => $model ] );
			}
			wp_send_json_error( [ 'message' => 'No Model' ] );
		}
	}

	/**
	 * This function works both with AJAX requests, gives the Auto Trim depending on
	 * the brand
	 *
	 * @param null $term_id
	 *
	 * @return JSON
	 */
	public function getTrimAjax ( $term_id = null ) {
		if ( isset( $_POST['model'] ) ) {
			$modelID = $_POST['model'];
			if ( isset( $modelID ) ) {
				$trim = get_terms( [
					'taxonomy'   => 'mark',
					'hide_empty' => false,
					'parent'     => $modelID,
				] );

				wp_send_json_success( [ 'trim' => $trim ] );
			}
			wp_send_json_error( [ 'message' => 'No Trim' ] );
		}
	}


	/**
	 * Save Meta Box Settings
	 *
	 * @param $post_ID
	 * @param $post
	 * @param $update
	 */
	public function savePostMeta ( $post_ID, $post, $update ) {
		$slug = $this->postTypeName;

		if ( $slug != $_POST['post_type'] ) {
			return;
		}

		$dataArray = self::gatMetaBoxDataArray( $_POST );

		foreach ( $dataArray as $key => $value ) {
			if ( $key == 'jwa_car_price' ) {
				if ( ! empty( $value ) ) {
					$biWeekly      = new jwaBiWeekly( $value, JWA_CAR_PERCENT, JWA_CAR_TERM );
					$biWeeklyPrice = $biWeekly->getPriceBiWeekly();
					update_post_meta( $post_ID, 'jwa_car_biweekly', $biWeeklyPrice );
				}
				update_post_meta( $post_ID, 'jwa_car_price', $value );
			} else if ( $key == 'jwa_car_sales_price' ) {
				if ( ! empty( $value ) ) {
					$biWeekly      = new jwaBiWeekly( $value, JWA_CAR_PERCENT, JWA_CAR_TERM );
					$biWeeklyPrice = $biWeekly->getPriceBiWeekly();
					update_post_meta( $post_ID, 'jwa_car_biweekly', $biWeeklyPrice );
				}
				update_post_meta( $post_ID, 'jwa_car_sales_price', $value );
			} else if ( $key == 'jwa_car_features' ) {
				update_post_meta( $post_ID, 'jwa_car_biweekly', explode( ', ', $value ) );
			} else {
				update_post_meta( $post_ID, $key, $value );
			}
		}

		self::setThumbnailCar( $dataArray['jwa_car_image_gallery'], $post_ID );
		wp_delete_object_term_relationships( $post_ID, 'mark' );
		self::setMarkCar( $dataArray['jwa_car_mark'], $post_ID );
		self::setModelCar( $dataArray['jwa_car_model'], $post_ID );
		self::setTrimCar( $dataArray['jwa_car_trim'], $post_ID );
		self::setEngineCar( $dataArray['jwa_car_engine'], $post_ID );
		if ( ! wp_is_post_revision( $post_ID ) ) {

			// unhook this function to prevent infinite looping
			remove_action( 'save_post', [ $this, 'savePostMeta' ] );

			// update the post slug
			wp_update_post( [
				'ID'         => $post_ID,
				'post_name'  => self::getSlugGenerate( $dataArray['jwa_car_year'], $dataArray['jwa_car_mark'], $dataArray['jwa_car_model'], $dataArray['jwa_car_trim'], $dataArray['jwa_car_vin'] ),
				'post_title' => self::getTitleGenerate( $dataArray['jwa_car_year'], $dataArray['jwa_car_mark'], $dataArray['jwa_car_model'], $dataArray['jwa_car_trim'] ),
			] );

			// re-hook this function
			add_action( 'save_post', [ $this, 'savePostMeta' ] );

		}

	}

	/**
	 * Create Array Meta Box Field Value
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	private function gatMetaBoxDataArray ( array $data ) {
		$metaBoxData = [];
		foreach ( $data as $key => $value ) {
			if ( preg_match( '/jwa_car_*/', $key ) && ! empty( $value ) ) {
				$metaBoxData[ $key ] = $value;
			} else if ( preg_match( '/jwa_car_*/', $key ) && empty( $value ) ) {
				$metaBoxData[ $key ] = '';
			}
		}

		return $metaBoxData;
	}

	/**
	 * Remove Gallery Post Meta
	 */
	public function removeGallery () {
		$post_id = $_POST['post_id'];
		if ( ! empty( $post_id ) ) {
			delete_post_meta( $post_id, 'jwa_car_image_gallery' );

			wp_send_json_success();
		}

		wp_send_json_error( [ 'message' => 'Error post Id Empty' ] );
	}

	/**
	 * If the thumbnail is not set it takes the first gallery picture
	 *
	 * @param $galleryArray
	 * @param $postID
	 */
	public function setThumbnailCar ( $galleryArray, $postID ) {
		$thumbnailsID = array_diff( explode( ',', $galleryArray ), [ '' ] );

		if ( ! has_post_thumbnail( $postID ) ) {
			$set = set_post_thumbnail( $postID, (int) $thumbnailsID[1] );
		}
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
		$makeTerm  = get_term( $make )->name;
		$modelTerm = get_term( $model )->name;
		$trimTerm  = get_term( $trim )->name;

		$slugString = 'buy-' . ( ! empty( $year ) ? $year . '-' : '' ) . ( ! empty( $makeTerm ) ? $makeTerm . '-' : '' )
		              . ( ! empty( $modelTerm )
				? $modelTerm . '-' : '' ) . ( ! empty( $trimTerm ) ? $trimTerm . '-' : '' ) . substr( $vin, - 8 );

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
		$makeTerm  = get_term( $make )->name;
		$modelTerm = get_term( $model )->name;
		$trimTerm  = get_term( $trim )->name;

		$titleString = ( ! empty( $year ) ? $year . ' | ' : '' ) . ( ! empty( $makeTerm ) ? $makeTerm . ' | ' : '' ) . ( ! empty( $modelTerm ) ? $modelTerm : '' ) .
		               ( ! empty( $trimTerm ) ? ' | ' . $trimTerm : '' );

		return $titleString;
	}
}
