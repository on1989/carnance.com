<?php
/**
 * Created 04.12.2020
 * Version 1.0.0
 * Last update
 * Author: Alex L
 *
 */
?>
<form class="filters filters-banner" action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">
	<h2 class="title"><?php echo( ! empty( $atts['title'] ) ? $atts['title'] : '' ); ?></h2>
	<div class="left-block">
		<!--start make-->
		<div class="select ic-make">
			<?php
			$makes = new \JWA\Car\Helpers\taxCar\taxCar();
			?>
			<select id="make" name="jwa_filter[make]">
				<option value="0"><?php _e( 'Make', 'jwa_car' ); ?></option>
				<?php foreach ( $makes->getMake() as $make ): ?>
					<option
						value="<?php echo $make->slug; ?>"><?php echo $make->name; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<!--end make-->

		<!--start model-->
		<div class="select ic-model">
			<select id="model" name="jwa_filter[model]">
				<option value="0">Model</option>
			</select>
		</div>
		<!--end model-->

		<!--start year-->
		<div class="select ic-calendar">
			<?php
			$years = new JWA\Car\Helpers\taxCar\taxCar();
			$year  = $years->getAllYearsSelect();
			?>
			<select id="year" name="jwa_filter[car-year]">
				<option value="0"><?php _e( 'Year', 'jwa_car' ); ?></option>
				<?php if ( $year ): ?>
					<?php foreach ( $year as $item ): ?>
						<option value="<?php echo $item; ?>"><?php echo $item; ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>
		<!--end year-->


		<div class="input ic-location">
			<input type="text" placeholder="Enter a location" name="jwa_filter[location]">
		</div>

		<div class="hidden-filters">
			<!--start price-->
			<div class="select-range ic-price">
				<?php
				$prices = new JWA\Car\Helpers\taxCar\taxCar();
				$price  = $prices->getDynamicPrice();
				?>
				<p><?php _e( 'Price', 'jwa_car' ); ?></p>
				<div class="range-wrapper">
					<div class="range-slider">
						<input type="text" id="price" name="jwa_filter[price]" class="js-range-slider" value="" data-min="0"
						       data-postfix="$"
						       data-max="<?php echo $price ?>"
						       data-step="1000"/>
					</div>
					<div class="extra-controls form-inline">
						<div class="form-group">
							<input class="js-input-from form-control" type="text" value="0">
							<input class="js-input-to form-control" type="text" value="0">
						</div>
					</div>
				</div>
			</div>
			<!--end price-->

			<!--start body type-->
			<div class="select ic-body-type">
				<?php
				$bodiesTypes = new JWA\Car\Helpers\taxCar\taxCar();
				$bodies      = $bodiesTypes->getBodyTypes();
				?>
				<select id="body-type" name="jwa_filter[body-type]">
					<option value="0"><?php _e( 'Body Type', 'jwa_car' ); ?></option>
					<?php if ( $bodies ): ?>
						<?php foreach ( $bodies as $body ): ?>
							<option value="<?php echo $body ?>"><?php echo $body ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
			<!--end body type-->

			<!--start fuel type-->
			<div class="select ic-fuel">
				<?php $fuelTypes = new \JWA\Car\Helpers\taxCar\taxCar(); ?>
				<select id="fuel" name="jwa_filter[fuel-type]">
					<option value="0"><?php _e( 'Fuel type', 'jwa_car' ); ?></option>
					<?php foreach ( $fuelTypes->getFuelTypes() as $type ): ?>
						<option value="<?php echo $type; ?>"><?php echo $type; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<!--start fuel type-->

			<div class="select ic-exterior">
				<?php
				$exteriorColor = new \JWA\Car\Helpers\taxCar\taxCar();
				$colors        = $exteriorColor->exteriorColorAvailable();
				?>
				<select id="exterior-color" name="jwa_filter[exterior-color]">
					<option value="0"><?php _e( 'Exterior Color', 'jwa_car' ); ?></option>
					<?php if ( $colors ) : ?>
						<?php foreach ( $colors as $color ): ?>
							<option value="<?php echo $color; ?>"><?php echo $color; ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
			<div class="select ic-engine">
				<?php
				$engines     = new \JWA\Car\Helpers\taxCar\taxCar();
				$enginesTerm = $engines->getAllEngines();
				?>
				<select id="engine" name="jwa_filter[engine]">
					<option value="0"><?php _e( 'Engine', 'jwa_car' ); ?></option>
					<?php if ( $enginesTerm ): ?>
						<?php foreach ( $enginesTerm as $term ): ?>
							<option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
			<div class="select ic-interior">
				<?php
				$interiorColor = new \JWA\Car\Helpers\taxCar\taxCar();
				$colors        = $interiorColor->exteriorColorAvailable();
				?>
				<select id="interior-color" name="jwa_filter[interior-color]">
					<option value=""><?php _e( 'Interior Color', 'jwa_car' ); ?></option>
					<?php if ( $colors ): ?>
						<?php foreach ( $colors as $color ): ?>
							<option value="<?php echo $color; ?>"><?php echo $color; ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
		</div>
	</div>


	<div class="right-block">
		<input type="hidden" name="action" value="jwa_car_filter">
		<button class="ic-search jwa_button filter-button" type="submit"><?php _e( 'Search', 'jwa_car' ); ?></button>
		<button class="ic-reset" type="reset"><?php _e( 'Reset', 'jwa_car' ); ?></button>
	</div>
	<a class="show-filters"><?php _e( 'Show advanced filters', 'jwa_car' ); ?></a>
	<div class="sefety"><img src="<?php echo wp_get_attachment_url( $atts['icon'] ) ?>" alt="icon">
		<?php if ( ! empty( $content ) ) {
			echo $content;
		} ?>
	</div>
</form>

