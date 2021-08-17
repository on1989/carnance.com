<?php
/**
 * Created 04.11.2020
 * Version 1.0.6
 * Last update 18.12.2020
 * Author: Alex L
 *
 */

namespace JWA\Car\Helpers\taxCar;
if ( ! defined( 'ABSPATH' ) ) {
	die ( 'Silly human what are you doing here' );
}

class taxCar {
	private $taxName;

	private $bodyType = [
		'Compact',
		'Sedan',
		'Coupe',
		'Hatchback',
		'Kombi',
		'Limousine',
		'Microvan',
		'Minivan',
		'Pickup',
		'Roadster',
		'Station wagon',
		'Convertible',
	];
	private $fuelType = [
		'Gas',
		'Diesel',
		'Electric',
	];

	private $transmission = [
		'Manual',
		'Automatic',
	];

	private $province = [
		'Alberta',
		'British Columbia',
		'Manitoba',
		'New Brunswick',
		'Newfoundland and Labrador',
		'Northwest Territories',
		'Nova Scotia',
		'Nunavut',
		'Ontario',
		'Prince Edward Island',
		'Quebec',
		'Saskatchewan',
		'Yukon',
	];

	private $mileage = [
		"0-25000",
		"25000-50000",
		"50000-75000",
		"75000-100000",
		"100000-125000",
		"125000-150000",
		"150000-175000",
		"175000km-200000",
	];

	private $price = [
		"2500-5000",
		"5000-10000",
		"10000-15000",
		"15000-20000",
		"20000-25000",
		"25000-30000",
		"30000-35000",
		"35000-40000",
		"40000-45000",
		"45000-50000",
	];

	private $exteriorColor = [
		"Black",
		"Blue",
		"Brown",
		"Dark Blue",
		"Dark Grey",
		"Gray",
		"Red",
		"Silver",
		"White",
	];

	private $db;
	private $prefix;

	public function __construct ( string $taxName = null ) {
		$this->taxName = $taxName;
		global $wpdb;
		$this->db     = $wpdb;
		$this->prefix = $this->db->prefix;

		$dynamicMileage = self::getDynamicMileage();
		if ( $dynamicMileage ) {
			$this->mileage = $dynamicMileage;
		}

		$dynamicPrice = self::getDynamicPrice();
		if ( $dynamicPrice ) {
			$this->price = $dynamicPrice;
		}

		$bodyTypeAvailable = self::getBodyTypeAvailable();
		if ( $bodyTypeAvailable ) {
			$this->bodyType = $bodyTypeAvailable;
		}

		$fuelTypeAvailable = self::getFuelTypeAvailable();
		if ( $fuelTypeAvailable ) {
			$this->fuelType = $fuelTypeAvailable;
		}
	}

	/**
	 * Returns the array of all taxonomy items
	 *
	 * @return array
	 */
	public function getTaxArray () {
		$taxArray   = [];
		$taxonomies = get_terms( [ 'taxonomy' => $this->taxName, 'hide_empty' => false ] );

		foreach ( $taxonomies as $tax ) {
			$taxArray[ $tax->name ] = $tax->term_id;
		}

		return $taxArray;
	}

	/**
	 * Get Mark Car
	 *
	 * @return int|\WP_Error|\WP_Term[]
	 */
	public function getMake () {
		$marks = get_terms( [
			'taxonomy'   => [ 'mark' ],
			'order'      => 'ASC',
			'parent'     => 0,
			'hide_empty' => true,
		] );

		return $marks;
	}

	/**
	 * Get Make by Post ID
	 *
	 * @param $post_id
	 *
	 * @return array|false|\WP_Term
	 */
	public function getMakeByPostID ( $post_id ) {
		$makes    = wp_get_post_terms( $post_id, 'mark' );
		$makeTerm = [];
		foreach ( $makes as $make ) {
			if ( $make->parent == 0 ) {
				$makeTerm = $make;
				break;
			}
		}

		if ( ! empty( $makeTerm ) && ! is_wp_error( $makeTerm ) ) {
			return $makeTerm;
		} else {
			return false;
		}
	}

	/**
	 * This function gives the Auto Models depending on the brand
	 *
	 * @param string $slug
	 *
	 * @return int|\WP_Error|\WP_Term[]
	 */
	public function getModel ( $term_id ) {

		$model = get_terms( [
			'taxonomy'   => 'mark',
			'hide_empty' => true,
			'parent'     => $term_id,
		] );

		return $model;
	}

	/**
	 * Get Model by Post ID
	 *
	 * @param $post_id
	 *
	 * @return array|false|\WP_Term
	 */
	public function getModelByPostID ( $post_id ) {
		$make   = self::getMakeByPostID( $post_id )->term_id;
		$models = wp_get_post_terms( $post_id, 'mark' );

		$modelTerm = [];
		foreach ( $models as $model ) {
			if ( $model->parent == $make ) {
				$modelTerm = $model;
				break;
			}
		}

		if ( ! empty( $modelTerm ) && ! is_wp_error( $modelTerm ) ) {
			return $modelTerm;
		} else {
			return false;
		}
	}


	/**
	 * This function gives the Auto Models depending on the brand
	 *
	 * @param string|array $slug
	 *
	 * @return int|\WP_Error|\WP_Term[]
	 */
	public function getModelBySlug ( $slug ) {
		if ( is_array( $slug ) ) {
			foreach ( $slug as $term ) {
				$term_id = get_term_by( 'slug', $term, 'mark' )->term_id;
				$model[] = get_terms( [
					'taxonomy'   => 'mark',
					'hide_empty' => true,
					'parent'     => $term_id,
				] );

			}
			$model = call_user_func_array( 'array_merge', $model );
		} else {
			$term_id = get_term_by( 'slug', $slug, 'mark' )->term_id;
			$model   = get_terms( [
				'taxonomy'   => 'mark',
				'hide_empty' => true,
				'parent'     => $term_id,
			] );
		}


		return $model;

	}


	/**
	 * This function gives the Auto Trim depending on
	 * the brand
	 *
	 * @param null $term_id
	 *
	 * @return int|\WP_Error|\WP_Term[]
	 */
	public function getTrim ( $term_id = null ) {

		$trim = get_terms( [
			'taxonomy'   => 'mark',
			'hide_empty' => true,
			'parent'     => $term_id,
		] );

		return $trim;

	}

	/**
	 * Get Body Types
	 *
	 * @return string[]
	 */
	public function getBodyTypes () {
		return $this->bodyType;
	}

	/**
	 * Get Fuel Types
	 *
	 * @return string[]
	 */
	public function getFuelTypes () {
		return $this->fuelType;
	}

	/**
	 * Get Transmission
	 *
	 * @return string[]
	 */

	public function getTransmissions () {
		return $this->transmission;
	}

	/**
	 * Get Province
	 *
	 * @return string[]
	 */
	public function getProvince () {
		return $this->province;
	}

	/**
	 * Get Mileage
	 *
	 * @return array
	 */
	public function getMileage () {
		$mileages = [];

		foreach ( $this->mileage as $mileage ) {
			$mileageText    = explode( '-', $mileage );
			$mileageText[0] = number_format( $mileageText[0], 0, ',', ',' ) . 'km';
			$mileageText[1] = number_format( $mileageText[1], 0, ',', ',' ) . 'km';;
			$mileages[ $mileage ] = $mileageText[0] . '-' . $mileageText[1];
		}

		return $mileages;
	}

	/**
	 * Get Prices
	 *
	 * @return array
	 */
	public function getPrices () {
		$prices = [];
		foreach ( $this->price as $price ) {
			$pricesText       = explode( '-', $price );
			$pricesText[0]    = '$' . number_format( $pricesText[0], 0, ',', ',' );
			$pricesText[1]    = '$' . number_format( $pricesText[1], 0, ',', ',' );
			$prices[ $price ] = $pricesText[0] . '-' . $pricesText[1];
		}

		return $prices;
	}

	/**
	 * Returns all available vehicle years
	 *
	 * @return array|false
	 */
	public function getAllYears () {
		$sqlMIN     = "SELECT MIN(cast(meta_value as unsigned)) FROM `{$this->prefix}postmeta` WHERE meta_key = 'jwa_car_year'";
		$sqlMAX     = "SELECT MAX(cast(meta_value as unsigned)) FROM `{$this->prefix}postmeta` WHERE meta_key = 'jwa_car_year'";
		$yearsArray = [];
		$yearMin    = $this->db->get_results( $sqlMIN, ARRAY_N );
		$yearMax    = $this->db->get_results( $sqlMAX, ARRAY_N );

		$yearsArray['min'] = $yearMin[0][0];
		$yearsArray['max'] = $yearMax[0][0];

		if ( ! empty( $yearsArray ) ) {
			return $yearsArray;
		}

		return false;
	}

	/**
	 * Return years by post id
	 *
	 * @param array $slug
	 *
	 * @return array
	 */
	public function getModelsYear ( $slug, $query_var = null ) {
		$carYears = [];
		$ids      = implode( ',', self::getIDByTerm( $slug, $query_var ) );
		$sql      = "SELECT * FROM `{$this->prefix}postmeta` WHERE `meta_key` = 'jwa_car_year' AND `post_id` IN ({$ids})";

		$modelYear = $this->db->get_results( $sql );
		foreach ( $modelYear as $year ) {
			$carYears[] = $year->meta_value;
		}

		$carYearsRange['min'] = min( $carYears );
		$carYearsRange['max'] = max( $carYears );


		return $carYearsRange;
	}

	/**
	 * Get Models Year By Term ID
	 *
	 * @param string $slug
	 *
	 * @return array|false
	 */
	public function getModelsYearByTermID ( $slug ) {

		$term_id = get_term_by( 'slug', $slug, 'mark' )->term_id;

		$args  = [
			'post_type'      => JWA_CAR_POST_TYPE,
			'posts_per_page' => - 1,
			'post_status'    => 'publish',
			'tax_query'      => [
				[
					'taxonomy' => 'mark',
					'field'    => 'id',
					'terms'    => [ $term_id ],
				],
			],
		];
		$query = new \WP_Query( $args );
		$ids   = [];
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$ids[] = get_the_ID();
			}
			wp_reset_postdata();

			return self::getModelsYear( $ids );
		}

		return false;
	}

	/**
	 * Return Dynamic Mileage
	 *
	 * @return false|array
	 */
	public function getDynamicMileage () {
		$sql = "SELECT MAX(cast(meta_value as unsigned)) FROM `{$this->prefix}postmeta` WHERE meta_key = 'jwa_car_mileage'";

		$mileage = $this->db->get_results( $sql, ARRAY_N );
		if ( $mileage ) {
			return round( ( int ) $mileage[0][0], - 5, PHP_ROUND_HALF_ODD );
		} else {
			return false;
		}
	}

	/**
	 * Return Dynamic Price
	 *
	 * @return false|array
	 */
	public function getDynamicPrice () {
		$sql = "SELECT MAX(cast(meta_value as unsigned)) FROM `{$this->prefix}postmeta` WHERE meta_key = 'jwa_car_price'";

		$price = $this->db->get_results( $sql, ARRAY_N );

		if ( ! empty( $price ) ) {
			$maxPrice = round( ( int ) $price[0][0], - 3, PHP_ROUND_HALF_ODD );

			return $maxPrice + 1000;
		} else {
			return false;
		}
	}

	/**
	 * get POST ID By Term
	 *
	 * @param string | array $slug
	 * @param array          $query_var
	 *
	 * @return array|false
	 */
	private function getIDByTerm ( $slug, $query_var = null ) {

		if ( $query_var == null ) {
			$arg = [
				'post_type'      => JWA_CAR_POST_TYPE,
				'posts_per_page' => - 1,
				'post_status'    => 'publish',
				'tax_query'      => [
					[
						'taxonomy'         => 'mark',
						'field'            => 'slug',
						'terms'            => $slug,
						'include_children' => false,
					],
				],
			];
		} else {
			$arg = $query_var;
		}

		$query = new \WP_Query( $arg );
		$ids   = [];
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$ids[] = get_the_ID();
			}
			wp_reset_postdata();

			return $ids;
		}

		return false;
	}

	/**
	 * Max Mileage by Term
	 *
	 * @param string $slug
	 * @param array  $query_var
	 *
	 * @return float|int
	 */
	public function getMileageByTerm ( $slug, $query_var = null ) {
		$postIDs = implode( ',', self::getIDByTerm( $slug, $query_var ) );
		$sql     = "SELECT MAX(cast(meta_value as unsigned)) FROM `{$this->prefix}postmeta` WHERE `meta_key` = 'jwa_car_mileage' AND `post_id` IN ({$postIDs})";

		$modelMileage = $this->db->get_results( $sql, ARRAY_N );

		return round( ( int ) $modelMileage[0][0], - 3, PHP_ROUND_HALF_ODD ) + 1000;
	}

	/**
	 * Max Price By TERM
	 *
	 * @param string $slug
	 * @param array  $query_var
	 *
	 * @return float
	 */
	public function getPriceByTerm ( $slug, $query_var = null ) {

		$postIDs = implode( ',', self::getIDByTerm( $slug, $query_var ) );
		$sql     = "SELECT MAX(cast(meta_value as unsigned)) FROM `{$this->prefix}postmeta` WHERE `meta_key` = 'jwa_car_price' AND `post_id` IN ({$postIDs})";

		$modelPrice = $this->db->get_results( $sql, ARRAY_N );

		return round( ( int ) $modelPrice[0][0] + 1000, - 3, PHP_ROUND_HALF_ODD );
	}

	/**
	 * All available body types
	 *
	 * @return array
	 */
	public function getBodyTypeAvailable () {
		$sql = "SELECT DISTINCT `meta_value` FROM `{$this->prefix}postmeta` WHERE `meta_key` = 'jwa_car_body_type'";

		$bodyTypes = $this->db->get_results( $sql, ARRAY_N );
		$bodyType  = [];
		foreach ( $bodyTypes as $type ) {
			if ( ! empty( $type[0] ) && $type[0] !== 'none' ) {
				$bodyType[] = $type[0];
			}
		}

		return $bodyType;
	}

	public function getFuelTypeAvailable () {
		$sql = "SELECT DISTINCT `meta_value` FROM `{$this->prefix}postmeta` WHERE `meta_key` = 'jwa_car_fuel_type'";

		$fuelTypes = $this->db->get_results( $sql, ARRAY_N );
		$fuelType  = [];
		foreach ( $fuelTypes as $type ) {
			if ( ! empty( $type[0] ) && $type[0] !== 'none' ) {
				$fuelType[] = $type[0];
			}
		}

		return $fuelType;
	}

	/**
	 * Available body types for the car brand
	 *
	 * @param string $slug
	 * @param array  $query_var
	 *
	 * @return array
	 */
	public function getBodyTypeByMake ( $slug, $query_var = null ) {
		$postIDs = implode( ',', self::getIDByTerm( $slug, $query_var ) );
		$sql     = "SELECT DISTINCT `meta_value` FROM `{$this->prefix}postmeta` WHERE `meta_key` = 'jwa_car_body_type' AND `post_id` IN ({$postIDs})";

		$bodyTypes = $this->db->get_results( $sql, ARRAY_N );
		$bodyType  = [];
		foreach ( $bodyTypes as $type ) {
			if ( ! empty( $type[0] ) && $type[0] !== 'none' ) {
				$bodyType[] = $type[0];
			}
		}

		return $bodyType;
	}

	/**
	 * Returns all available vehicle years for use in the select element
	 *
	 * @return array|false
	 */
	public function getAllYearsSelect () {
		$carYears = [];
		$sql      = "SELECT DISTINCT `meta_value` FROM `{$this->prefix}postmeta` WHERE `meta_key` = 'jwa_car_year'";
		$years    = $this->db->get_results( $sql, ARRAY_A );

		foreach ( $years as $year ) {
			$carYears[] = (int) $year['meta_value'];
		}
		asort( $carYears );
		if ( ! empty( $carYears ) ) {
			return $carYears;
		} else {
			return false;
		}
	}

	/**
	 * Get Years by Model from select elements
	 *
	 * @param string|array $slug
	 * @param array        $query_var
	 *
	 * @return array|false
	 */
	public function getYearsByModelSelect ( $slug, $query_var = null ) {

		$postIDs  = implode( ',', self::getIDByTerm( $slug, $query_var ) );
		$carYears = [];
		$sql      = "SELECT DISTINCT `meta_value` FROM `{$this->prefix}postmeta` WHERE `meta_key` = 'jwa_car_year' AND `post_id` IN ({$postIDs})";

		$years = $this->db->get_results( $sql, ARRAY_A );

		foreach ( $years as $year ) {
			$carYears[] = (int) $year['meta_value'];
		}
		asort( $carYears );
		if ( ! empty( $carYears ) ) {
			return $carYears;
		} else {
			return false;
		}
	}

	/**
	 * All color extender for car
	 *
	 * @return array|false
	 */
	public function exteriorColorAvailable () {
		$exteriorColor = [];
		$sql           = "SELECT DISTINCT `meta_value` FROM `{$this->prefix}postmeta` WHERE `meta_key` = 'jwa_car_exterior'";
		$exterior      = $this->db->get_results( $sql, ARRAY_A );

		foreach ( $exterior as $color ) {
			$exteriorColor[] = $color['meta_value'];
		}

		asort( $exteriorColor );

		if ( ! empty( $exteriorColor ) ) {
			return $exteriorColor;
		} else {
			return false;
		}
	}

	/**
	 * All color extender for car by model or make
	 *
	 * @param string|array $slug
	 * @param array        $query_var
	 *
	 * @return array|false
	 */
	public function exteriorColorByMark ( $slug, $query_var = null ) {
		$postIDs     = implode( ',', self::getIDByTerm( $slug, $query_var ) );
		$carFuelType = [];
		$sql         = "SELECT DISTINCT `meta_value` FROM `{$this->prefix}postmeta` WHERE `meta_key` = 'jwa_car_exterior' AND `post_id` IN ({$postIDs})";

		$fuelTypes = $this->db->get_results( $sql, ARRAY_A );


		foreach ( $fuelTypes as $type ) {
			$carFuelType[] = $type['meta_value'];
		}

		if ( ! empty( $carFuelType ) ) {
			return $carFuelType;
		} else {
			return false;
		}
	}

	/**
	 * Displays all types of engines
	 *
	 * @return int|\WP_Error|\WP_Term[]
	 */
	public function getAllEngines () {
		$engines = get_terms( [
			'taxonomy'   => 'engine',
			'hide_empty' => true,
		] );

		return $engines;
	}

	/**
	 * Displays all types of engines by slug
	 *
	 * @param string | array $slug
	 * @param array          $query_var
	 *
	 * @return array|false
	 */
	public function getEngineByModel ( $slug, $query_var = null ) {
		$postsID    = self::getIDByTerm( $slug, $query_var );
		$engineType = [];

		foreach ( $postsID as $id ) {
			$engineType[] = wp_get_post_terms( $id, 'engine' )[0];
		}
		$result = array_unique( $engineType, SORT_REGULAR );

		if ( $result ) {
			return $result;
		} else {
			return false;
		}
	}

	/**
	 * All color interior for car
	 *
	 * @return array|false
	 */
	public function interiorColorAvailable () {
		$interiorsColor = [];
		$sql            = "SELECT DISTINCT `meta_value` FROM `{$this->prefix}postmeta` WHERE `meta_key` = 'jwa_car_interior'";
		$interiors      = $this->db->get_results( $sql, ARRAY_A );

		foreach ( $interiors as $color ) {
			$interiorsColor[] = $color['meta_value'];
		}

		asort( $interiorsColor );

		if ( ! empty( $interiorsColor ) ) {
			return $interiorsColor;
		} else {
			return false;
		}
	}

	/**
	 * Get Interior color by model
	 *
	 * @param string $slug
	 * @param array  $query_var
	 *
	 * @return array|false
	 */
	public function interiorColorByModel ( $slug, $query_var = null ) {
		$postIDs        = implode( ',', self::getIDByTerm( $slug, $query_var ) );
		$interiorsColor = [];
		$sql            = "SELECT DISTINCT `meta_value` FROM `{$this->prefix}postmeta` WHERE `meta_key` = 'jwa_car_interior' AND `post_id` IN ({$postIDs})";
		$interiors      = $this->db->get_results( $sql, ARRAY_A );

		foreach ( $interiors as $color ) {
			if ( ! empty( $color['meta_value'] ) ) {
				$interiorsColor[] = $color['meta_value'];
			}
		}

		asort( $interiorsColor );

		if ( ! empty( $interiorsColor ) ) {
			return $interiorsColor;
		} else {
			return false;
		}
	}

	/**
	 * Max Biweekly Payments
	 *
	 * @return false|float
	 */
	public function biweeklyPaymentAvailable () {

		$sql = "SELECT MAX(cast(meta_value as unsigned)) FROM `{$this->prefix}postmeta` WHERE meta_key = 'jwa_car_biweekly'";

		$biweekly = $this->db->get_results( $sql, ARRAY_N );
		if ( $biweekly ) {
			return round( ( int ) $biweekly[0][0], 0, PHP_ROUND_HALF_ODD );
		} else {
			return false;
		}
	}

	/**
	 * return drivetrain
	 *
	 * @return array|false
	 */
	public function transmissionAvailable () {
		$transmissionArray = [];
		$sql               = "SELECT DISTINCT `meta_value` FROM `{$this->prefix}postmeta` WHERE `meta_key` = 'jwa_car_transmission'";
		$transmissions     = $this->db->get_results( $sql, ARRAY_A );

		foreach ( $transmissions as $transmission ) {
			$transmissionArray[] = $transmission['meta_value'];
		}

		asort( $transmissionArray );

		if ( ! empty( $transmissionArray ) ) {
			return $transmissionArray;
		} else {
			return false;
		}
	}

	/**
	 * Get Car fuel type by mark or model
	 *
	 * @param string|array $slug
	 * @param array        $query_var
	 *
	 * @return array|false
	 */
	public function getCarFuelTypeByModel ( $slug, $query_var = null ) {

		$postIDs     = implode( ',', self::getIDByTerm( $slug, $query_var ) );
		$carFuelType = [];
		$sql         = "SELECT DISTINCT `meta_value` FROM `{$this->prefix}postmeta` WHERE `meta_key` = 'jwa_car_fuel_type' AND `post_id` IN ({$postIDs})";

		$fuelTypes = $this->db->get_results( $sql, ARRAY_A );


		foreach ( $fuelTypes as $type ) {
			$carFuelType[] = $type['meta_value'];
		}

		if ( ! empty( $carFuelType ) ) {
			return $carFuelType;
		} else {
			return false;
		}
	}
}
