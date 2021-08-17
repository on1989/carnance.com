<?php
/**
 * Created 04.12.2020
 * Version 1.0.0
 * Last update
 * Author: Alex L
 *
 */

$years = new JWA\Car\Helpers\taxCar\taxCar();
$years->getAllYears();
$urlPars = new \JWA\Car\Helpers\postData\jwaPostData();
$params  = $urlPars->parseURLQuery( get_query_var( 'filter' ) );
if ( isset( $params['search'] ) && is_array( $params['search'] ) ) {
	$params['search'] = $params['search'][0];
}
?>
<form class="filters" action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">
	<div class="search">
		<input type="text" placeholder="Search makes, models or keywords" id="search_filter"
		       name="jwa_filter[search]"
		       value="<?php echo( isset( $params['search'] ) && ! empty( $params['search'] ) ? $params['search'] : '' ) ?>">
		<button class="ic-search search_btn"></button>
	</div>

	<?php
	$makes      = new \JWA\Car\Helpers\taxCar\taxCar();
	$makeParams = ! empty( $params['make'] ) ? $params['make'] : '';
	if ( is_array( $makeParams ) ) {
		$makeParams = $makeParams[0];
	}
	?>
	<div class="select">
		<select name="jwa_filter[make]" id="make">
			<option value="">Make</option>
			<?php foreach ( $makes->getMake() as $make ): ?>
				<option
					value="<?php echo $make->slug; ?>" <?php echo( isset( $makeParams ) && $makeParams == $make->slug ?
					'selected' : '' ) ?>><?php echo $make->name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="select">
		<select name="jwa_filter[model]" id="model">
			<option value="">Model</option>
			<?php if ( $makeParams ):
				$models = new \JWA\Car\Helpers\taxCar\taxCar();
				if ( is_array( $params['model'] ) ) {
					$params['model'] = $params['model'][0];
				}
				$modelParams = ! empty( $params['model'] ) ? $params['model'] : '';

				foreach ( $models->getModelBySlug( $makeParams ) as $model ):
					?>
					<option
						value="<?php echo $model->slug; ?>" <?php echo( isset( $modelParams ) && $modelParams ==
						                                                                         $model->slug ? 'selected'
						: '' ) ?>><?php echo $model->name ?></option>
				<?php endforeach; endif; ?>
		</select>
	</div>

	<div class="select-range">
		<p><?php _e( 'Year', 'jwa_car' ); ?></p>
		<?php
		$years = new JWA\Car\Helpers\taxCar\taxCar();
		if ( empty( $modelParams ) && empty( $makeParams ) ) {
			$year = $years->getAllYears();
		} else {
			$term = $modelParams ? $modelParams : $makeParams;
			$year = $years->getModelsYear( $term, $filter->getQueryArgs( $params ) );
		}
		$yearGet = ! empty( $params['car-year'] ) ? $params['car-year'] : '';
		?>
		<div class="range-wrapper">
			<div class="range-slider year-slider">
				<input type="text" id="car_year" name="jwa_filter[car-year]" class="js-range-slider" value=""
				       data-min="<?php echo $year['min']; ?>"
				       data-max="<?php echo $year['max']; ?>"
				       data-from="<?php echo( ! empty( $yearGet ) ? explode( '-', $yearGet )[0] : '' ) ?>"
				       data-to="<?php echo( ! empty( $yearGet ) ? explode( '-', $yearGet )[1] : '' ) ?>"
				       data-step="1"/>
			</div>
		</div>
	</div>


	<div class="select-range">
		<p>Mileage</p>
		<?php
		$mileages = new JWA\Car\Helpers\taxCar\taxCar();

		if ( empty( $modelParams ) && empty( $makeParams ) ) {
			$mileage = $mileages->getDynamicMileage();
		} else {
			$term    = ! empty( $modelParams ) ? $modelParams : $makeParams;
			$mileage = $mileages->getMileageByTerm( $term, $filter->getQueryArgs( $params ) );
		}
		$mileageParams = ! empty( $params['mileage'] ) ? $params['mileage'] : '';
		?>
		<div class="range-wrapper">
			<div class="range-slider">
				<input type="text" class="js-range-slider" name="jwa_filter[mileage]" id="mileage" value=""
				       data-from="<?php echo( ! empty( $mileageParams ) ? explode( '-', $mileageParams )[0] : '' ) ?>"
				       data-to="<?php echo( ! empty( $mileageParams ) ? explode( '-', $mileageParams )[1] : '' ) ?>"
				       data-postfix="km"
				       data-min="0"
				       data-max="<?php echo $mileage ?>" data-step="1000"/>
			</div>
			<div class="extra-controls form-inline">
				<div class="form-group">
					<input type="text" class="js-input-from form-control" value="0"/>
					<input type="text" class="js-input-to form-control" value="0"/>
				</div>
			</div>

		</div>
	</div>

	<div class="select-range">
		<p><?php _e( 'Price', 'jwa_car' ); ?></p>
		<?php
		$prices = new JWA\Car\Helpers\taxCar\taxCar();
		if ( empty( $modelParams ) && empty( $makeParams ) ) {
			$price = $prices->getDynamicPrice();
		} else {
			$term  = ! empty( $modelParams ) ? $modelParams : $makeParams;
			$price = $prices->getPriceByTerm( $term, $filter->getQueryArgs( $params ) );
		}

		$pricesParams = ! empty( $params['price'] ) ? $params['price'] : '';

		?>
		<div class="range-wrapper">
			<div class="range-slider price-slider">
				<input type="text" id="price" name="jwa_filter[price]" class="js-range-slider" value="" data-min="0"
				       data-postfix="$"
				       data-max="<?php echo $price ?>"
				       data-from="<?php echo( ! empty( $pricesParams ) ? explode( '-', $pricesParams )[0] : '' ) ?>"
				       data-to="<?php echo( ! empty( $pricesParams ) ? explode( '-', $pricesParams )[1] : '' ) ?>"
				       data-step="1000"/>
			</div>
			<div class="extra-controls form-inline">
				<div class="form-group">
					<input type="text" class="js-input-from form-control" value="0"/>
					<input type="text" class="js-input-to form-control" value="0"/>
				</div>
			</div>
		</div>
	</div>

	<div class="select">
		<select name="jwa_filter[body-type]" id="body_type">
			<option value="">Body Type</option>
			<?php
			$bodiesTypes = new JWA\Car\Helpers\taxCar\taxCar();
			if ( is_array( $params['body-type'] ) ) {
				$params['body-type'] = $params['body-type'][0];
			}
			$bodiesTypeParams = ! empty( $params['body-type'] ) ? $params['body-type'] : '';
			if ( empty( $modelParams ) && empty( $makeParams ) ) {
				$bodies = $bodiesTypes->getBodyTypes();
			} else {

				$term   = ! empty( $modelParams ) ? $modelParams : $makeParams;
				$bodies = $bodiesTypes->getBodyTypeByMake( $term, $filter->getQueryArgs( $params ) );
			}
			foreach ( $bodies as $body ):?>
				<option
					value="<?php echo $body ?>" <?php echo( $body == $bodiesTypeParams ? 'selected' : '' ); ?>><?php echo $body ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<input type="hidden" name="action" value="jwa_car_filter"/>
	<input type="submit" value="<?php _e( 'Filter', 'jwa_filter' ); ?>" class="jwa_button filter-button">
	<a href="<?php echo get_post_type_archive_link( JWA_CAR_POST_TYPE ) ?>"
	   class="jwa_button transparent"><?php _e( 'Clear Filter' ); ?></a>
</form>
