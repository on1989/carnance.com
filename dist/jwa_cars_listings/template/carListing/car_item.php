<?php
/**
 * Created 06.11.2020
 * Version 1.0.0
 * Last update
 * Author: Alex L
 *
 */

$id           = get_the_ID();
$price        = (int) get_post_meta( $id, 'jwa_car_price', true );
$sales_price  = (int) get_post_meta( $id, 'jwa_car_sales_price', true );
$year         = get_post_meta( $id, 'jwa_car_year', true );
$mileage      = (int) get_post_meta( $id, 'jwa_car_mileage', true );
$engines_term = (int) get_post_meta( $id, 'jwa_car_engine', true );
$engine       = get_term( $engines_term, 'engine' );
$vin          = get_post_meta( $id, 'jwa_car_vin', true );
$transmission = get_post_meta( $id, 'jwa_car_transmission', true );
$exterior     = get_post_meta( $id, 'jwa_car_exterior', true );
$interior     = get_post_meta( $id, 'jwa_car_interior', true );
$biWeekly     = get_post_meta( $id, 'jwa_car_biweekly', true );
?>

<div class="car-item">
	<a href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail( $id ) ):
			the_post_thumbnail( 'car_listing' );
			?>
		<?php else: ?>
			<img
				src="<?php echo JWA_CAR_PLUGIN_URL . '/assets/img/no_image_car.png' ?>"
				alt="No Image">
		<?php endif; ?>
	</a>
	<div class="description-block">
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<div class="car-details">
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
				<?php if ( isset( $engine ) ): ?>
					<li>
						<span><?php _e( 'Engine:', 'jwa_car' ); ?></span><?php echo( isset( $engine->name ) && ! is_wp_error( $engine ) ? $engine->name
							: ''
						); ?>
					</li>
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
	<div class="wrap-price">
		<?php if ( $price ): ?>
			<p class="price">$<?php echo number_format( $price, '2', '.', ',' ) ?></p>
		<?php endif; ?>
		<?php if ( $sales_price ): ?>
			<p class="price sales-price">$<?php echo number_format( $sales_price, '2', '.', ',' ) ?></p>
		<?php endif; ?>
		<?php if ( $biWeekly ): ?>
			<p class="beweekly-price">$<?php echo $biWeekly . ' ' . __( 'biweekly', 'jwa_car' ) ?></p>
		<?php endif; ?>
		<a
			href="<?php echo( ! empty( JWA_CAR_FORM_PAGE ) ? get_the_permalink( JWA_CAR_FORM_PAGE ) . '?vin=' . $vin : '#' )
			?>"
			class="jwa_button">Apply
			Now</a>
		<a href="<?php the_permalink(); ?>" class="jwa_button transparent"><?php _e( 'Details', 'jwa_car' ); ?></a>
	</div>
</div>
