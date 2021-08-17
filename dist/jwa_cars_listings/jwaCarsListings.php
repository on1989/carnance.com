<?php
/**
 * Created 22.10.2020
 * Version 1.0.12
 * Last update 21.12.2020
 * Author: Alex L
 *
 * Plugin Name: JWA Car Listing
 * Plugin URI: https://www.justwebagency.com/
 * Description: Creates a filtered car listing
 * Author: Alex L
 * Author URI: https://gitlab.com/AlsconWeb
 * Version: 1.0.12
 * Domain Path: /languages
 * Since: 1.0
 * Requires WordPress Version at least: 5.1
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:jwa_car
 */


namespace JWA\Car;

use JWA\Car\CPT\jwaCarsPostType;
use JWA\Car\Filter\jwaFilterCar;
use JWA\Car\MetaBox\jwaMetaBoxCar;
use JWA\Car\REST\jwaCarRESTApi;
use JWA\Car\Settings\jwaCarListingSettings;
use JWA\Car\Settings\jwaSettingsField;
use JWA\Car\wpbkComponent\greatDeals\greatDeals;

if ( ! defined( 'ABSPATH' ) ) {
	die ( 'Silly human what are you doing here' );
}

/**
 * Class jwaCarsListings
 *
 * Main Class Plugin
 *
 * @package JWA\Car
 */
class jwaCarsListings {
	private $ctp;
	private $matabox;
	private $filter;
	private $rest;
	private $settings;
	private $settingsField;
	private $style;

	public function __construct () {
		define( 'JWA_CAR_VERSION', '1.0.12' );
		define( 'JWA_CAR_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'JWA_CAR_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		define( 'JWA_CAR_POST_TYPE', 'car' );

		$options = get_option( 'jwa_car_listing', false );
		if ( $options ) {
			define( 'JWA_CAR_FORM_PAGE', $options['page_form'] );
			define( 'JWA_REST_TOKEN', $options['token'] );

			$this->style = $options['preset'];

		} else {

			define( 'JWA_CAR_FORM_PAGE', '' );
			define( 'JWA_REST_TOKEN', 'uhcKFEGUD396XuBcRUVz' );
		}
		define( 'JWA_REST_NAMESPACE', 'jwa-cars-listing/v1' );

		require_once JWA_CAR_PLUGIN_DIR . '/vendor/autoload.php';

		if ( ! function_exists( 'dropdown_multi' ) ) {
			include JWA_CAR_PLUGIN_DIR . '/includes/Helpers/wpbkField/wpbk_multiselect.php';
		}

		add_action( 'init', [ $this, 'init' ] );
		add_action( 'admin_menu', [ $this, 'adminMenu' ] );
		add_action( 'admin_init', [ $this, 'adminInit' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'addScripts' ] );
		add_action( 'vc_before_init', [ $this, 'addWPBKComponents' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'addAdminScript' ] );
	}

	/**
	 * Init
	 */
	public function init () {

		$this->ctp     = new jwaCarsPostType( JWA_CAR_POST_TYPE );
		$this->matabox = new jwaMetaBoxCar( JWA_CAR_POST_TYPE );
		$this->filter  = new jwaFilterCar();
		$this->rest    = new jwaCarRESTApi();

		add_image_size( 'car_card', 262, 196, [ 'top', 'center' ] );
		add_image_size( 'car_listing', 384, 288, [ 'top', 'center' ] );
	}

	/**
	 * Add menu Page
	 */
	public function adminMenu () {
		$this->settings = new jwaCarListingSettings();
	}

	/**
	 * Admin INIT Settings
	 */
	public function adminInit () {
		$this->settingsField = new jwaSettingsField();
	}

	/**
	 * Add Style and Scripts
	 */
	public function addScripts () {
		global $post;

		if ( is_singular( 'car' ) ) {

			//style
			wp_enqueue_style( 'fancybox', JWA_CAR_PLUGIN_URL . '/assets/css/jquery.fancybox.min.css', '', JWA_CAR_VERSION );
			wp_enqueue_style( 'slick-theme', JWA_CAR_PLUGIN_URL . '/assets/css/slick-theme.css', '', JWA_CAR_VERSION );
			wp_enqueue_style( 'slick', JWA_CAR_PLUGIN_URL . '/assets/css/slick.css', '', JWA_CAR_VERSION );


			//js
			wp_enqueue_script( 'slick', JWA_CAR_PLUGIN_URL . '/assets/js/slick.min.js', [ 'jquery' ],
				JWA_CAR_VERSION,
				true );
			wp_enqueue_script( 'fancybox', JWA_CAR_PLUGIN_URL . '/assets/js/jquery.fancybox.min.js', [ 'jquery' ],
				JWA_CAR_VERSION,
				true );
		}

		if ( is_archive( JWA_CAR_POST_TYPE ) || has_shortcode( $post->post_content, 'jwa_car_filter' ) ) {
			wp_enqueue_script( 'rangeSlider', JWA_CAR_PLUGIN_URL . '/assets/js/ion.rangeSlider.min.js', [ 'jquery' ],
				JWA_CAR_VERSION,
				true );
			wp_enqueue_style( 'rangeSlider', JWA_CAR_PLUGIN_URL . '/assets/css/ion.rangeSlider.min.css' );
		}

		wp_enqueue_style( 'style-car', JWA_CAR_PLUGIN_URL . '/assets/css/style-car.css', '', self::versionFile( '/assets/css/style-car.css' ) );

		wp_enqueue_script( 'main-сar', JWA_CAR_PLUGIN_URL . '/assets/js/main.js', [ 'jquery' ],
			self::versionFile( '/assets/js/main.js' ),
			true );

		wp_enqueue_script( 'select', JWA_CAR_PLUGIN_URL . '/assets/js/select2.min.js', [ 'jquery' ],
			JWA_CAR_VERSION,
			true );

		wp_enqueue_script( 'IncrementBox', JWA_CAR_PLUGIN_URL . '/assets/js/jquery.IncrementBox.js', [ 'jquery' ],
			JWA_CAR_VERSION,
			true );
		wp_enqueue_style( 'select', JWA_CAR_PLUGIN_URL . '/assets/css/select2.min.css', '', self::versionFile( '/assets/css/select2.min.css' ) );

		wp_localize_script( 'main-сar', 'jwaCarFilter', [
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'filter'  => get_post_type_archive_link( JWA_CAR_POST_TYPE ),
		] );

		if ( $this->style !== 'default' ) {
			wp_enqueue_style( 'presets', JWA_CAR_PLUGIN_URL . '/assets/css/presets/style-' . $this->style . '.css', [ 'style-car' ],
				self::versionFile( '/assets/css/presets/style-' . $this->style . '.css' ) );
		}
	}

	/**
	 * Init WPBakery Component's
	 */
	public function addWPBKComponents () {
		$greatDeals   = new greatDeals();
		$carInventory = new wpbkComponent\carInventory\jwaCarInventory();
		$carFilter    = new wpbkComponent\filterComponent\jwaFilterCars();
	}

	/**
	 * Add JS\CSS to admin panel
	 *
	 * @param $pageName
	 */
	public function addAdminScript ( $pageName ) {
		if ( $pageName == 'car_page_listing-settings' ) {
			wp_enqueue_script( 'token-generate', JWA_CAR_PLUGIN_URL . '/assets/js/admin/settingsPage/main.js', [ 'jquery' ],
				self::versionFile( '/assets/js/admin/settingsPage/main.js' ),
				true );
		}
		wp_enqueue_style( 'admin', JWA_CAR_PLUGIN_URL . '/assets/css/admin/style.css', '',
			self::versionFile( '/assets/css/admin/style.css' ) );
	}


	/**
	 * Get control version file
	 *
	 * @param string $src
	 *
	 * @return false|int
	 */
	private function versionFile ( string $src ) {
		return filemtime( JWA_CAR_PLUGIN_DIR . $src );
	}
}

new jwaCarsListings();
