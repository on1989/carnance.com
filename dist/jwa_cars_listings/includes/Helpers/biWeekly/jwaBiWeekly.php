<?php
/**
 * Created 02.11.2020
 * Version 1.0.0
 * Last update
 * Author: Alex L
 *
 */

namespace JWA\Car\Helpers\biWeekly;

if ( ! defined( 'ABSPATH' ) ) {
	die ( 'Silly human what are you doing here' );
}

class jwaBiWeekly {

	private $price;
	private $percent;
	private $term;

	public function __construct ( int $price, float $percent, int $term ) {
		$this->term    = $term;
		$this->percent = $percent;
		$this->price   = $price;

		add_action( 'save_post', [ $this, 'savePostMeta' ], 15, 3 );
	}

	/**
	 * Return price biweekly
	 *
	 * @return string
	 */
	public function getPriceBiWeekly () {
		$tax             = $this->price * ( 13 / 100 );
		$totalPrice      = $this->price + $tax;
		$biWeeklyPayment = ( ( $totalPrice * ( $this->percent / 100 ) / 26 ) ) / ( 1 - pow( ( 1 + ( $this->percent / 100 ) / 26 )
					, ( - 1 * $this->term ) ) );

		return number_format( $biWeeklyPayment, 2, '.', ',' );
	}
}
