<?php
/**
 * Created 15.12.2020
 * Version 1.0.0
 * Last update
 * Author: Alex L
 *
 */

foreach ( $params as $key => $param ):
	switch ( $key ) {
		case 'price':
			$price = explode( '-', $param );
			$min   = number_format( $price[0], 0, '.', ',' );
			$max   = number_format( $price[1], 0, '.', ',' );
			echo '<li><span>' . __( 'Price: ', 'jwa' ) . '</span>$' . $min . ' - $' . $max . ' <i class=“ic-clear”></i></li>';
			break;
		case 'biweekly':
			$biweekly = explode( '-', $param );
			$min      = number_format( $biweekly[0], 0, '.', ',' );
			$max      = number_format( $biweekly[1], 0, '.', ',' );
			echo '<li><span>' . __( 'Biweekly Payment: ', 'jwa' ) . '</span>$' . $min . ' - $' . $max . ' <i class=“ic-clear”></i></li>';
			break;
		case 'location':
			$location = $param[0];
			echo '<li><span>' . __( 'Location: ', 'jwa' ) . '</span>' . $location . ' <i class=“ic-clear”></i></li>';
			break;
		case 'make':
			if ( ! is_array( $param ) ) {
				$makes[] = $param;
			} else {
				$makes = $param;
			}
			$makeArray = [];
			foreach ( $makes as $make ) {
				$makeArray[] = get_term_by( 'slug', $make, 'mark' )->name;
			}
			echo '<li><span>' . __( 'Make: ', 'jwa' ) . '</span>' . implode( ', ', $makeArray ) . ' <i class=“ic-clear”></i></li>';
			break;
		case 'model':
			if ( ! is_array( $param ) ) {
				$models[] = $param;
			} else {
				$models = $param;
			}

			$modelArray = [];
			foreach ( $models as $model ) {
				$modelArray[] = get_term_by( 'slug', $model, 'mark' )->name;
			}
			echo '<li><span>' . __( 'Make: ', 'jwa' ) . '</span>' . implode( ', ', $modelArray ) . ' <i class=“ic-clear”></i></li>';
			break;
		case 'car-year':
			if ( ! is_array( $param ) ) {
				$year[] = $param;
			} else {
				$year = $param;
			}
			echo '<li><span>' . __( 'Year: ', 'jwa' ) . '</span>' . implode( ', ', $year ) . ' <i class=“ic-clear”></i></li>';
			break;
		case 'body-type':
			if ( ! is_array( $param ) ) {
				$bodyType[] = $param;
			} else {
				$bodyType = $param;
			}
			echo '<li><span>' . __( 'Body Type: ', 'jwa' ) . '</span>' . implode( ', ', $bodyType ) . ' <i class=“ic-clear”></i></li>';
			break;
		case 'fuel-type':
			if ( ! is_array( $param ) ) {
				$fuelType[] = $param;
			} else {
				$fuelType = $param;
			}
			echo '<li><span>' . __( 'Body Type: ', 'jwa' ) . '</span>' . implode( ', ', $fuelType ) . ' <i class=“ic-clear”></i></li>';
			break;
		case 'exterior-color':
			if ( ! is_array( $param ) ) {
				$exteriorColor[] = str_replace( '%20', ' ', $param );
			} else {
				$exteriorColor = str_replace( '%20', ' ', $param );
			}
			echo '<li><span>' . __( 'Exterior Color: ', 'jwa' ) . '</span>' . implode( ', ', $exteriorColor ) . ' <i class=“ic-clear”></i></li>';
			break;
		case 'transmission':
			if ( ! is_array( $param ) ) {
				$transmission[] = $param;
			} else {
				$transmission = $param;
			}
			echo '<li><span>' . __( 'Transmission: ', 'jwa' ) . '</span>' . implode( ', ', $transmission ) . ' <i class=“ic-clear”></i></li>';
			break;
		case 'engine':
			if ( ! is_array( $param ) ) {
				$engines[] = $param;
			} else {
				$engines = $param;
			}

			$enginesArray = [];
			foreach ( $engines as $engine ) {
				$enginesArray[] = get_term_by( 'slug', $engine, 'engine' )->name;

			}
			echo '<li><span>' . __( 'Engine: ', 'jwa' ) . '</span>' . implode( ', ', $enginesArray ) . ' <i class=“ic-clear”></i></li>';
			break;
		case 'interior-color':
			if ( ! is_array( $param ) ) {
				$interiorColor[] = str_replace( '%20', ' ', $param );
			} else {
				$interiorColor = str_replace( '%20', ' ', $param );
			}
			echo '<li><span>' . __( 'Engine: ', 'jwa' ) . '</span>' . implode( ', ', $interiorColor ) . ' <i class=“ic-clear”></i></li>';
			break;
	} ?>
<?php endforeach; ?>

