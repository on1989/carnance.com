<?php
/**
 * Created 04.11.2020
 * Version 1.0.0
 * Last update
 * Author: Alex L
 *
 */

$button = vc_build_link( $atts['btn'] );
$arg    = [
	'post_type'      => JWA_CAR_POST_TYPE,
	'posts_per_page' => 3,
	'tax_query'      => [
		[
			'taxonomy' => 'car_tag',
			'field'    => 'id',
			'terms'    => explode( ',', $atts['car_tag'] ),
		],
	],
];
$query  = new \WP_Query( $arg );

?>

<?php if ( $query->have_posts() ):
	while ( $query->have_posts() ): $query->the_post();
		$id          = get_the_ID();
		$biWeekly    = get_post_meta( $id, 'jwa_car_biweekly', true );
		$millage     = (int) get_post_meta( $id, 'jwa_car_mileage', true );
		$price       = (int) get_post_meta( $id, 'jwa_car_price', true );
		$sales_price = (int) get_post_meta( $id, 'jwa_car_sales_price', true );
		$car_tag     = wp_get_post_terms( $id, 'car_tag' );

		?>
		<?php if ( ! empty( $atts['btn_color'] ) ): ?>
			<style>

          .btn-style {
              background: <?php echo $atts['btn_color']; ?>;
              color: <?php echo $atts['btn_text_color'] ?>;
          }

          .btn-style:hover {
              background: <?php echo $atts['btn_hover_color']; ?>;
          }
			</style>
		<?php endif; ?>
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 list-item">
			<a href="<?php the_permalink(); ?>" class="rmv_txt_drctn">
				<div class="image">
					<?php if ( has_post_thumbnail( $id ) ):
						the_post_thumbnail( 'car_card' );
					else: ?>
						<img
							src="<?php echo JWA_CAR_PLUGIN_URL . '/assets/img/no_image_car.png' ?>"
							alt="No Image">
					<?php endif; ?>
				</div>
				<div class="listing-car-item-meta">
					<div class="car-meta-top heading-font clearfix">
						<?php if ( ! empty( $car_tag ) ): ?>
							<div class="labels">
								<ul>
									<?php foreach ( $car_tag as $tag ): ?>
										<li><?php echo $tag->name; ?></li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
						<div class="title-wrapper m-price">
							<h4 class="car-title"><?php the_title() ?></h4>
							<div class="price">
								<p class="monthly-price">$<?php echo $biWeekly ?><span><?php _e( 'biweekly', 'jwa_car' ); ?></span></p>
							</div>
						</div>
						<div class="carnance-price-wrapper">
							<p class="mileage"><?php echo number_format( $millage, 0, ' ', ' ' ) . ' ' . __( 'km', 'jwa_car' ) ?></p>
							<?php if ( ! empty( $price ) && empty( $sales_price ) ): ?>
								<div class="price discounted-price">
									<div class="normal-price"><?php _e( 'Price:', 'jwa_car' ); ?>
										$<?php echo number_format( $price, 2, '.', ',' )
										?></div>
								</div>
							<?php endif; ?>
							<?php if ( ! empty( $sales_price ) ): ?>
								<div class="price discounted-price">
									<div class="normal-price"><?php _e( 'Sales Price:', 'jwa_car' ); ?><br>
										$<?php echo number_format( $sales_price, 0, '.', ',' )
										?></div>
								</div>
							<?php endif; ?>
						</div>

					</div>
				</div>
			</a>
		</div>
	<?php endwhile;
	wp_reset_postdata(); ?>
<?php else: ?>
	<div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
		<p class="no-cars"><?php _e( 'No cars with such parameters found', 'jwa_car' ); ?></p>
	</div>
<?php endif; ?>
<div class="vc_col-lg-3 vc_col-md-3 vc_col-sm-6 vc_col-xs-6">
	<h2 class="vc_custom_heading"><?php echo $atts['title'] ?><strong><?php echo $atts['sub_title'] ?></strong></h2>
	<a class="jwa_button btn-style" href="<?php echo $button['url'] ?>"><?php echo $button['title'] ?> <i class="fas
	fa-long-arrow-alt-right"></i></a>
</div>
