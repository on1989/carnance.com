<?php
/**
 * Created 12.11.2020
 * Version 1.0.0
 * Last update
 * Author: Alex L
 *
 */

$button = vc_build_link( $atts['btn'] );
$arg    = [
	'post_type'      => JWA_CAR_POST_TYPE,
	'posts_per_page' => 4,
	'orderby'        => 'date',
];
$query  = new \WP_Query( $arg );
?>
<div class="container cars-list">
	<div class="vc_row">
		<div class="vc_col-sm-12">
			<h2><?php echo $atts['title'] ?></h2>
		</div>
	</div>
	<div class="vc_row">
		<?php if ( $query->have_posts() ): ?>
			<?php while ( $query->have_posts() ): $query->the_post(); ?>
				<?php
				$id       = get_the_ID();
				$biWeekly = get_post_meta( $id, 'jwa_car_biweekly', true );
				$millage  = (int) get_post_meta( $id, 'jwa_car_mileage', true );
				$price    = (int) get_post_meta( $id, 'jwa_car_price', true );
				?>

				<div class="vc_col-lg-3 vc_col-md-6 vc_col-sm-6 vc_col-xs-12">
					<div class="item">
						<?php if ( has_post_thumbnail( $id ) ):
							the_post_thumbnail( 'car_card' );
						else: ?>
							<img
								src="<?php echo JWA_CAR_PLUGIN_URL . '/assets/img/no_image_car.png' ?>"
								alt="No Image">
						<?php endif; ?>
						<div class="car-meta-top heading-font">
							<div class="title-wrapper m-price">
								<h4 class="car-title"><?php the_title() ?></h4>
								<div class="price">
									<p class="monthly-price">$<?php echo $biWeekly ?></p>
									<div class="price discounted-price">
										<div class="normal-price">
											$<?php echo number_format( $price, 2, '.', ',' ); ?>
										</div>
									</div>
								</div>
							</div>
							<div class="carnance-price-wrapper">
								<p
									class="mileage"><?php echo number_format( $millage, 0, ' ', ' ' ) . ' ' . __( 'km', 'jwa_car' ) ?></p>
							</div>
						</div>
						<a href="<?php the_permalink(); ?>" class="link"></a>
					</div>
				</div>
			<?php endwhile;
			wp_reset_postdata(); ?>
		<?php endif; ?>
	</div>
	<div class="vc_row">
		<div class="vc_btn3-container vc_btn3-center">
			<a href="<?php echo $button['url'] ?>"
			   class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-color-black
			   jwa_button">
				<?php echo $button['title'] ?>
			</a>
		</div>
	</div>
</div>

