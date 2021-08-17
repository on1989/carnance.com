<?php
/**
 * Created 26.10.2020
 * Version 1.0.1
 * Last update 17.12.2020
 * Author: Alex L
 *
 */

$id = get_the_ID();
?>

<div class="container-fluid jwa-car-info">
	<div class="row">
		<div class="col-12">

			<ul class="nav nav-tabs" id="jwaTabs" role="tablist">
				<li class="nav-item" role="presentation">
					<a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab"
					   aria-controls="details-tab"
					   aria-selected="true"><?php _e( 'Details', 'jwa_car' ); ?></a>
				</li>
				<li class="nav-item" role="presentation">
					<a class="nav-link" id="features-tab" data-toggle="tab" href="#features" role="tab" aria-controls="features"
					   aria-selected="false"><?php _e( 'Features', 'jwa_car' ); ?></a>
				</li>
				<li class="nav-item" role="presentation">
					<a class="nav-link" id="price-tab" data-toggle="tab" href="#price" role="tab" aria-controls="profile"
					   aria-selected="false"><?php _e( 'Price', 'jwa_car' ); ?></a>
				</li>
				<li class="nav-item" role="presentation">
					<a class="nav-link" id="gallery-tab" data-toggle="tab" href="#gallery" role="tab" aria-controls="gallery"
					   aria-selected="false"><?php _e( 'Gallery', 'jwa_car' ); ?></a>
				</li>
				<li class="nav-item" role="presentation">
					<a class="nav-link" id="location-tab" data-toggle="tab" href="#location" role="tab" aria-controls="location"
					   aria-selected="false"><?php _e( 'Location', 'jwa_car' ); ?></a>
				</li>
			</ul>

			<div class="tab-content" id="jwaTabContent">
				<form enctype="multipart/form-data" method="post">
					<!--start Details-->
					<div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
						<!--start vin, stock_number-->
						<div class="row">
							<div class="col-xs-12 col-sm-12  col-md-6  col-lg-6 col-xl-6">
								<?php $vin = get_post_meta( $id, 'jwa_car_vin', true ); ?>
								<input type="text" class="form-control" placeholder="<?php _e( 'VIN', 'jwa_car' ); ?>"
								       name="jwa_car_vin"
								       id="vin" value="<?php echo( ! empty( $vin ) ? $vin : '' ); ?>">
							</div>
							<div class="col-xs-12 col-sm-12  col-md-6  col-lg-6 col-xl-6">
								<?php $stock_number = get_post_meta( $id, 'jwa_car_stock_number', true ); ?>
								<input type="text" class="form-control" placeholder="<?php _e( 'Stock Number', 'jwa_car' ); ?>"
								       id="stock_number" name="jwa_car_stock_number"
								       value="<?php echo( ! empty( $stock_number ) ? $stock_number : '' ); ?>">
							</div>
						</div>
						<!--end vin, stock_number-->

						<!--start city_mpg, highway_mpg-->
						<div class="row">
							<div class="col-xs-12 col-sm-12  col-md-6  col-lg-6 col-xl-6">
								<?php $city_mpg = get_post_meta( $id, 'jwa_car_city_mpg', true ); ?>
								<input type="text" class="form-control" placeholder="<?php _e( 'City MPG', 'jwa_car' ); ?>"
								       name="jwa_car_city_mpg" id="city_mpg"
								       value="<?php echo( ! empty( $city_mpg ) ? $city_mpg : '' ); ?>">
							</div>
							<div class="col-xs-12 col-sm-12  col-md-6  col-lg-6 col-xl-6">
								<?php $highway_mpg = get_post_meta( $id, 'jwa_car_highway_mpg', true ) ?>
								<input type="text" class="form-control" placeholder="<?php _e( 'Highway MPG', 'jwa_car' ); ?>"
								       name="jwa_car_highway_mpg" id="highway_mpg"
								       value="<?php echo( ! empty( $highway_mpg ) ? $highway_mpg : '' ); ?>">
							</div>
						</div>
						<!--end city_mpg, highway_mpg-->

						<!--start mark, model-->
						<div class="row">
							<div class="col-xs-12 col-sm-12  col-md-4  col-lg-4 col-xl-4">
								<?php
								$marks      = new JWA\Car\Helpers\taxCar\taxCar();
								$marksArray = $marks->getMake();
								$mark_term  = get_post_meta( $id, 'jwa_car_mark', true );
								?>
								<select name="jwa_car_mark" id="mark" class="form-control">
									<option value="0"><?php _e( 'Select Mark', 'jwa_car' ); ?></option>
									<?php if ( ! empty( $marksArray ) ): ?>
										<?php foreach ( $marksArray as $mark ): ?>
											<option value="<?php echo $mark->term_id; ?>"
												<?php echo( ! empty( $mark_term ) && $mark_term == $mark->term_id ? 'selected' : '' ); ?>>
												<?php echo $mark->name; ?>
											</option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>
							<div class="col-xs-12 col-sm-12  col-md-4  col-lg-4 col-xl-4">
								<?php
								if ( ! empty( $mark_term ) ) {
									$models     = new JWA\Car\Helpers\taxCar\taxCar( 'make' );
									$modelArray = $models->getModel( $mark_term );
									$model_term = get_post_meta( $id, 'jwa_car_model', true );
								}
								?>
								<select name="jwa_car_model" id="model" class="form-control">
									<option value="0"><?php _e( 'Select Model', 'jwa_car' ); ?></option>
									<?php if ( isset( $modelArray ) ): ?>
										<?php foreach ( $modelArray as $model ): ?>
											<option value="<?php echo $model->term_id; ?>"
												<?php echo( ! empty( $model_term ) && $model_term == $model->term_id ? 'selected' : '' ); ?>>
												<?php echo $model->name; ?>
											</option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>
							<div class="col-xs-12 col-sm-12  col-md-4  col-lg-4 col-xl-4">
								<?php
								if ( isset( $model_term ) ) {
									$trim_term = get_post_meta( $id, 'jwa_car_trim', true );
									$trims     = new \JWA\Car\Helpers\taxCar\taxCar( 'make' );
									$trimArray = $trims->getTrim( $model_term );
								}
								?>
								<select name="jwa_car_trim" id="trim" class="form-control">
									<option value="0"><?php _e( 'Select Trim', 'jwa_car' ); ?></option>
									<?php if ( ! empty( $trimArray ) ) : ?>
										<?php foreach ( $trimArray as $trim ): ?>
											<option value="<?php echo $trim->term_id; ?>"
												<?php echo( ! empty( $trim_term ) && $trim_term == $trim->term_id ? 'selected' : '' ); ?>>
												<?php echo $trim->name; ?>
											</option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>
						</div>
						<!--end mark, model-->

						<!--start body_type, fuel_type-->
						<div class="row">
							<div class="col-xs-12 col-sm-12  col-md-4  col-lg-4 col-xl-4">
								<?php
								$body_types     = new JWA\Car\Helpers\taxCar\taxCar();
								$body_type      = $body_types->getBodyTypes();
								$body_type_term = get_post_meta( $id, 'jwa_car_body_type', true );
								?>
								<select name="jwa_car_body_type" id="body_type" class="form-control">
									<option value="none"><?php _e( 'Select Body Type', 'jwa_car' ); ?></option>
									<?php if ( ! empty( $body_type ) ): ?>
										<?php foreach ( $body_type as $type ): ?>
											<option value="<?php echo $type; ?>"
												<?php echo( ! empty( $body_type_term ) && $body_type_term == $type ? 'selected' : '' ); ?>>
												<?php echo $type; ?>
											</option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>
							<div class="col-xs-12 col-sm-12  col-md-4  col-lg-4 col-xl-4">
								<select name="jwa_car_fuel_type" id="fuel_type" class="form-control">
									<option value="none"><?php _e( 'Select Fuel Type', 'jwa_car' ); ?></option>
									<?php
									$fuelTypes      = new JWA\Car\Helpers\taxCar\taxCar();
									$fuelType       = $fuelTypes->getFuelTypes();
									$fuel_type_term = get_post_meta( $id, 'jwa_car_fuel_type', true );
									foreach ( $fuelType as $type ): ?>
										<option value="<?php echo $type; ?>"
											<?php echo( ! empty( $fuel_type_term ) && $fuel_type_term == $type ? 'selected' : '' ); ?>>
											<?php echo $type; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-xs-12 col-sm-12  col-md-4  col-lg-4 col-xl-4">
								<?php $year = get_post_meta( $id, 'jwa_car_year', true ); ?>
								<input type="text" class="form-control" placeholder="<?php _e( 'Year', 'jwa_car' ); ?>"
								       name="jwa_car_year" id="year"
								       value="<?php echo( ! empty( $year ) ? $year : '' ); ?>">
							</div>
						</div>
						<!--end body_type, fuel_type-->

						<!--start mileage horse power-->
						<div class="row">
							<div class="col-xs-12 col-sm-12  col-md-6  col-lg-6 col-xl-6">
								<?php $mileage = get_post_meta( $id, 'jwa_car_mileage', true ); ?>
								<input type="number" class="form-control" placeholder="<?php _e( 'Mileage', 'jwa_car' ); ?>"
								       name="jwa_car_mileage" id="mileage"
								       value="<?php echo( ! empty( $mileage ) ? $mileage : '' ); ?>">
							</div>
							<div class="col-xs-12 col-sm-12  col-md-6  col-lg-6 col-xl-6">
								<?php $horse_power = get_post_meta( $id, 'jwa_car_horse_power', true ); ?>
								<input type="text" class="form-control" placeholder="<?php _e( 'Horse Power', 'jwa_car' ); ?>"
								       name="jwa_car_horse_power" id="horse_power"
								       value="<?php echo( ! empty( $horse_power ) ? $horse_power : '' ); ?>">
							</div>
						</div>
						<!--end mileage horse power-->

						<!--start engine, transmission-->
						<div class="row">
							<div class="col-xs-12 col-sm-12  col-md-4  col-lg-4 col-xl-4">
								<?php
								$engines      = get_terms( [
									'taxonomy'   => [ 'engine' ],
									'order'      => 'ASC',
									'hide_empty' => false,
								] );
								$engines_term = get_post_meta( $id, 'jwa_car_engine', true );
								?>
								<select name="jwa_car_engine" id="engine" class="form-control">
									<option value="none"><?php _e( 'Select Engine Size', 'jwa_car' ); ?></option>
									<?php if ( $engines ): ?>
										<?php foreach ( $engines as $engine ): ?>
											<option value="<?php echo $engine->term_id; ?>"
												<?php echo( ! empty( $engines_term ) && $engines_term == $engine->term_id ? 'selected' : '' ); ?>>
												<?php echo $engine->name; ?>
											</option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>
							<div class="col-xs-12 col-sm-12  col-md-4  col-lg-4 col-xl-4">
								<select name="jwa_car_transmission" id="transmission" class="form-control">
									<option value="none"><?php _e( 'Select Transmission', 'jwa_car' ); ?></option>
									<?php
									$transmissions     = new JWA\Car\Helpers\taxCar\taxCar();
									$transmission_term = get_post_meta( $id, 'jwa_car_transmission', true );
									foreach ( $transmissions->getTransmissions() as $transmission ): ?>
										<option value="<?php echo $transmission; ?>"
											<?php echo( ! empty( $transmission_term ) && $transmission_term == $transmission ? 'selected' : '' ); ?>>
											<?php echo $transmission; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-xs-12 col-sm-12  col-md-4  col-lg-4 col-xl-4">
								<?php $drivetrain = get_post_meta( $id, 'jwa_car_drivetrain', true ); ?>
								<input type="text" class="form-control" placeholder="<?php _e( 'Drivetrain', 'jwa_car' ); ?>"
								       name="jwa_car_drivetrain" id="drivetrain"
								       value="<?php echo( ! empty( $drivetrain ) ? $drivetrain : '' ); ?>">
							</div>
						</div>
						<!--end engine, transmission-->

						<!--start interior & exterior color-->
						<div class="row">
							<div class="col-xs-12 col-sm-12  col-md-6  col-lg-6 col-xl-6">
								<?php $interior = get_post_meta( $id, 'jwa_car_interior', true ); ?>
								<input type="text" class="form-control" placeholder="<?php _e( 'Interior Color', 'jwa_car' ); ?>"
								       name="jwa_car_interior" id="interior"
								       value="<?php echo( ! empty( $interior ) ? $interior : '' ); ?>">
							</div>
							<div class="col-xs-12 col-sm-12  col-md-6  col-lg-6 col-xl-6">
								<?php $exterior = get_post_meta( $id, 'jwa_car_exterior', true ); ?>
								<input type="text" class="form-control" placeholder="<?php _e( 'Exterior Color', 'jwa_car' ); ?>"
								       name="jwa_car_exterior" id="exterior"
								       value="<?php echo( ! empty( $exterior ) ? $exterior : '' ); ?>">
							</div>
						</div>
						<!--end interior & exterior color-->

						<!--start airbag, seating, doors -->
						<div class="row">
							<div class="col-xs-12 col-sm-12  col-md-4  col-lg-4 col-xl-4">
								<?php $airbag = get_post_meta( $id, 'jwa_car_airbag', true ); ?>
								<input type="text" class="form-control" placeholder="<?php _e( 'Number AirBags', 'jwa_car' ); ?>"
								       name="jwa_car_airbag" id="airbag" value="<?php echo( ! empty( $airbag ) ? $airbag : '' ); ?>">
							</div>
							<div class="col-xs-12 col-sm-12  col-md-4  col-lg-4 col-xl-4">
								<?php $seating = get_post_meta( $id, 'jwa_car_seating', true ); ?>
								<input type="text" class="form-control" placeholder="<?php _e( 'Seating', 'jwa_car' ); ?>"
								       name="jwa_car_seating" id="seating"
								       value="<?php echo( ! empty( $seating ) ? $seating : '' ); ?>">
							</div>
							<div class="col-xs-12 col-sm-12  col-md-4  col-lg-4 col-xl-4">
								<?php $doors = get_post_meta( $id, 'jwa_car_doors', true ); ?>
								<input type="text" class="form-control" placeholder="<?php _e( 'Doors', 'jwa_car' ); ?>"
								       name="jwa_car_doors" id="doors" value="<?php echo( ! empty( $doors ) ? $doors : '' ); ?>">
							</div>
						</div>
						<!--end airbag, seating, doors-->

					</div>
					<!--end Details-->

					<!--start Features-->
					<div class="tab-pane fade show" id="features" role="tabpanel" aria-labelledby="features-tab">
						<!--start Features text-->
						<div class="row">
							<div class="col-xs-12 col-sm-12  col-md-12  col-lg-12 col-xl-12">
								<?php $features = get_post_meta( $id, 'jwa_car_features', true ); ?>
								<textarea class="form-control" style="height: 400px;"
								          name="jwa_car_features"
								          id="features_text"><?php echo( ! empty( $features ) ? implode( ', ', $features ) : '' ); ?>
								</textarea>
								<p class="description"><?php _e( 'Enter text separated by ","', 'jwa_car' ); ?></p>
							</div>
						</div>
						<!--end Featuresr-->

					</div>
					<!--end Features-->

					<!--start Price-->
					<div class="tab-pane fade" id="price" role="tabpanel" aria-labelledby="price-tab">
						<!--start price, sales price, biweekly-->
						<div class="row">
							<div class="col-xs-12 col-sm-12  col-md-4  col-lg-4 col-xl-4">
								<?php $price = get_post_meta( $id, 'jwa_car_price', true ); ?>
								<input type="number" class="form-control" placeholder="<?php _e( 'Price', 'jwa_car' ); ?>"
								       name="jwa_car_price" id="price" value="<?php echo( ! empty( $price ) ? $price : '' ); ?>">
							</div>
							<div class="col-xs-12 col-sm-12  col-md-4  col-lg-4 col-xl-4">
								<?php $sales_price = get_post_meta( $id, 'jwa_car_sales_price', true ); ?>
								<input type="text" class="form-control" placeholder="<?php _e( 'Sales Price', 'jwa_car' ); ?>"
								       name="jwa_car_sales_price" id="sales_price"
								       value="<?php echo( ! empty( $sales_price ) ? $sales_price : '' ); ?>">
							</div>
							<div class="col-xs-12 col-sm-12  col-md-4  col-lg-4 col-xl-4">
								<?php $biweekly = get_post_meta( $id, 'jwa_car_biweekly', true ); ?>
								<input type="text" class="form-control" placeholder="<?php _e( 'Biweekly payment', 'jwa_car' ); ?>"
								       name="jwa_car_biweekly" id="biweekly" disabled
								       value="<?php echo( ! empty( $biweekly ) ? $biweekly : '' ); ?>">
							</div>
						</div>
						<!--end price, sales price, biweekly-->
					</div>
					<!--end Price-->

					<!--start Gallery-->
					<div class="tab-pane fade" id="gallery" role="tabpanel" aria-labelledby="gallery-tab">
						<div class="row">
							<?php
							$galleryID     = get_post_meta( $id, 'jwa_car_image_gallery', true );
							$imagesGallery = explode( ',', $galleryID ); ?>
							<input type="hidden" name="jwa_car_image_gallery" id="gallery_id" value="<?php echo $galleryID ?>">
							<input type="hidden" name="jwa_car_post_id" id="post_id" value="<?php echo $id; ?>">
							<div id="image-wrapper">
								<?php

								$urlImage = [];
								foreach ( $imagesGallery as $image ) {
									if ( ! empty( $image ) ) {
										$urlImage[] = wp_get_attachment_url( $image );
									}
								}

								if ( ! empty( $urlImage ) ):
									?>
									<?php foreach ( $urlImage as $key => $image ): ?>
									<div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
										<img src="<?php echo $image ?>" alt="gallery-<?php echo $key ?>" class="img-thumbnail"
										     style="width: 33%; height:auto;">
									</div>
								<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<a href="#" class="btn btn-primary" id="upload-image"><?php _e( 'Upload image', 'jwa_car' ); ?></a>
								<a href="#" class="btn btn-danger" id="remove-all-images"><?php _e( 'Remove all image', 'jwa_car' );
									?></a>
							</div>
						</div>
					</div>
					<!--end Gallery-->

					<!--start location-->
					<div class="tab-pane fade" id="location" role="tabpanel" aria-labelledby="location-tab">
						<!--start city, postal-code-->
						<div class="row">
							<div class="col-xs-12 col-sm-12  col-md-4  col-lg-4 col-xl-4">
								<?php $city = get_post_meta( $id, 'jwa_car_city', true ); ?>
								<input type="text" class="form-control" placeholder="<?php _e( 'City', 'jwa_car' ); ?>"
								       name="jwa_car_city"
								       id="city" value="<?php echo( ! empty( $city ) ? $city : '' ); ?>">
							</div>
							<div class="col-xs-12 col-sm-12  col-md-4  col-lg-4 col-xl-4">
								<?php $postal_code = get_post_meta( $id, 'jwa_car_postal_code', true ); ?>
								<input type="text" class="form-control" placeholder="<?php _e( 'Postal Code', 'jwa_car' ); ?>"
								       id="postal_code" name="jwa_car_postal_code"
								       value="<?php echo( ! empty( $postal_code ) ? $postal_code : '' ); ?>">
							</div>
							<div class="col-xs-12 col-sm-12  col-md-4  col-lg-4 col-xl-4">
								<select name="jwa_car_province" id="province" class="form-control">
									<option value="none"><?php _e( 'Select Province', 'jwa_car' ); ?></option>
									<?php
									$provinces     = new \JWA\Car\Helpers\taxCar\taxCar();
									$province_term = get_post_meta( $id, 'jwa_car_province', true );
									foreach ( $provinces->getProvince() as $province ): ?>
										<option value="<?php echo $province; ?>"
											<?php echo( ! empty( $province_term ) && $province_term == $province ? 'selected' : '' ); ?>>
											<?php echo $province; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<!--end city, postal-code-->

						<!--start city, postal-code-->
						<div class="row">
							<div class="col-xs-12 col-sm-12  col-md-6  col-lg-6 col-xl-6">
								<?php $address = get_post_meta( $id, 'jwa_car_address', true ); ?>
								<input type="text" class="form-control" placeholder="<?php _e( 'Address', 'jwa_car' ); ?>"
								       name="jwa_car_address"
								       id="address" value="<?php echo( ! empty( $address ) ? $address : '' ); ?>">
							</div>
							<div class="col-xs-12 col-sm-12  col-md-6  col-lg-6 col-xl-6">
								<?php $dealer_name = get_post_meta( $id, 'jwa_car_dealer_name', true ); ?>
								<input type="text" class="form-control" placeholder="<?php _e( 'Dealer Name', 'jwa_car' ); ?>"
								       id="dealer_name" name="jwa_car_dealer_name"
								       value="<?php echo( ! empty( $dealer_name ) ? $dealer_name : '' ); ?>">
							</div>
						</div>
						<!--end city, postal-code-->
					</div>
					<!--end location-->
				</form>
			</div>
		</div>
	</div>
</div>
