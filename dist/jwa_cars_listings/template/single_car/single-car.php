<?php
/**
 * Created 29.10.2020
 * Version 1.0.1
 * Last update 04.12.20
 * Author: Alex L
 *
 */

get_header();
$id           = get_the_ID();
$price        = (int) get_post_meta( $id, 'jwa_car_price', true );
$sales_price  = (int) get_post_meta( $id, 'jwa_car_sales_price', true );
$year         = get_post_meta( $id, 'jwa_car_year', true );
$mileage      = (int) get_post_meta( $id, 'jwa_car_mileage', true );
$engines_term = (int) get_post_meta( $id, 'jwa_car_engine', true );
$engine       = get_term( $engines_term );
$vin          = get_post_meta( $id, 'jwa_car_vin', true );
$transmission = get_post_meta( $id, 'jwa_car_transmission', true );
$exterior     = get_post_meta( $id, 'jwa_car_exterior', true );
$interior     = get_post_meta( $id, 'jwa_car_interior', true );
$biWeekly     = get_post_meta( $id, 'jwa_car_biweekly', true );
$container    = get_option( 'jwa_car_listing', false )['container'];

$imagesGallery = explode( ',', get_post_meta( $id, 'jwa_car_image_gallery', true ) );
$urlImage      = [];
foreach ( $imagesGallery as $image ) {
	if ( ! empty( $image ) ) {
		$urlImage[] = wp_get_attachment_url( $image );
	}
}

?>
<section class="car">
	<div class="<?php echo( $container == 1 ? 'container-fluid' : 'container' ) ?>">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="dfr">
					<?php if ( ! empty( $urlImage ) ): ?>
						<div class="gallery-block">
							<!--start gallery-->
							<div class="gallery">
								<?php foreach ( $urlImage as $image ): ?>
									<div class="item">
										<a href="<?php echo $image; ?>" data-fancybox="gallery">
											<img src="<?php echo $image; ?>" alt="#">
										</a>
									</div>
								<?php endforeach; ?>
							</div>
							<!--end gallery-->

							<!--start gallery nav-->
							<div class="gallery-nav">
								<?php foreach ( $urlImage as $image ): ?>
									<div class="item">
										<img src="<?php echo $image; ?>" alt="#">
									</div>
								<?php endforeach; ?>
							</div>
							<!--end gallery nav-->
						</div>
					<?php else: ?>
						<div class="gallery-block">
							<div class="gallery">
								<div class="item">
									<img src="<?php echo JWA_CAR_PLUGIN_URL . '/assets/img/no_image_car.png' ?>" alt="No Image">
								</div>
							</div>
						</div>
					<?php endif; ?>

					<!--start description-->
					<div class="description-block">
						<h1><?php the_title(); ?></h1>
						<?php if ( ! empty( $price ) ): ?>
							<p class="price">$<?php echo number_format( $price, '2', '.', ',' ) ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $sales_price ) ): ?>
							<p class="sales-price">$<?php echo number_format( $sales_price, '2', ',', '.' ) ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $biWeekly ) ): ?>
							<p class="beweekly-price">$<?php echo $biWeekly . ' ' . __( 'biweekly', 'jwa_car' ) ?></p>
						<?php endif; ?>
						<a
							href="<?php echo( ! empty( JWA_CAR_FORM_PAGE ) ? get_the_permalink( JWA_CAR_FORM_PAGE ) . '?vin=' . $vin : '#' )
							?>" class="jwa_button"><?php _e( 'Apply Now', 'jwa_car' ); ?></a>
						<div class="car-details">
							<h3><?php _e( 'Car Details: ', 'jwa_car' ); ?></h3>
							<ul>
								<?php if ( isset( $year ) ): ?>
									<li><span><?php _e( 'Year:', 'jwa_car' ); ?></span><?php echo $year; ?></li>
								<?php endif; ?>
								<?php if ( isset( $mileage ) ): ?>
									<li><span><?php _e( 'Mileage:', 'jwa_car' ); ?></span>
										<?php echo number_format( $mileage, 0, '.', ',' ) ?>
										<?php _e( "km", 'jwa_car' ); ?>
									</li>
								<?php endif; ?>
								<?php if ( isset( $engine ) && ! is_wp_error( $engine ) ): ?>
									<li><span><?php _e( 'Engine:', 'jwa_car' ); ?></span><?php echo $engine->name; ?></li>
								<?php endif; ?>
								<?php if ( isset( $transmission ) ): ?>
									<li><span><?php _e( 'Transmission:', 'jwa_car' ); ?></span><?php echo $transmission; ?></li>
								<?php endif; ?>
								<?php if ( isset( $exterior ) ): ?>
									<li><span><?php _e( 'Exterior Color:', 'jwa_car' ); ?></span><?php echo $exterior; ?></li>
								<?php endif; ?>
								<?php if ( $interior ): ?>
									<li><span><?php _e( 'Interior Color:', 'jwa_car' ); ?></span><?php echo $interior; ?></li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
					<!--end description-->
					<?php
					$content = get_the_content( $id );
					if ( is_active_sidebar( 'jwa_single_car' ) ) {
						$sidebar = true;
					} else {
						$sidebar = false;
					}

					if ( $content || $sidebar ): ?>
						<div class="additional-block ">
							<div class="additional <?php echo( $sidebar ? 'in-sidebar' : '' ); ?>">
								<h2><?php _e( 'Additional Information', 'jwa_car' ); ?></h2>
								<?php the_content(); ?>
							</div>
							<?php if ( $sidebar ): ?>
								<div class="sidebar">
									<?php dynamic_sidebar( 'jwa_single_car' ); ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer() ?>
