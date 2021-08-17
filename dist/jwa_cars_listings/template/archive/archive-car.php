<?php
/**
 * Created 05.11.2020
 * Version 1.0.1
 * Last update 26.11.2020
 * Author: Alex L
 *
 */

get_header();
$container = get_option( 'jwa_car_listing', false )['container'];
$filter    = new \JWA\Car\Filter\jwaFilterCar();
?>

	<section>
		<div class="<?php echo( $container == 1 ? 'container-fluid' : 'container' ) ?>">
			<div class="row">
				<div class=" col-sm-12 col-lg-12 col-md-12 col-xs-12">
					<h1 class="title"><?php _e( 'Our Inventory', 'jwa_car' ); ?></h1>
					<?php include_once JWA_CAR_PLUGIN_DIR . '/template/filterForm/horizontal/template.php'; ?>
					<?php
					$arg = [
						'post_type'      => JWA_CAR_POST_TYPE,
						'posts_per_page' => 10,
						'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
						'orderby'        => 'title',
					];

					if ( empty( $params ) ) {
						$query        = new \WP_Query( $arg );
						$countAll     = $query->found_posts;
						$current      = $query->query_vars['paged'] * 10;
						$currentCount = ( $current > $countAll ? $countAll : $current );
					} else {
						$query        = new \WP_Query( $filter->getQueryArgs( $params ) );
						$current      = $query->query_vars['paged'] * 10;
						$countAll     = $query->found_posts;
						$currentCount = ( $current > $countAll ? $countAll : $current );
					}
					if ( $query->have_posts() ):
						?>
						<div class="search-count">
							<?php _e( 'Shown ', 'jwa_car' ); ?><span
								class="current"><?php echo $currentCount ?></span><?php _e( ' out of ', 'jwa_car' ); ?><span
								class="all-fount"><?php echo $countAll; ?></span>
						</div>
						<div class="cars">
							<?php while ( $query->have_posts() ): $query->the_post(); ?>
								<?php
								$theme_files     = [ '/template_part/car_item.php' ];
								$exists_in_theme = locate_template( $theme_files, false );
								if ( $exists_in_theme != '' ) {
									include $exists_in_theme;
								} else {
									include JWA_CAR_PLUGIN_DIR . '/template/carListing/car_item.php';
								}
								?>
							<?php endwhile; ?>

						</div>
					<?php else: ?>
						<div class="cars">
							<p class="not-found"><?php _e( 'No car with these parameters found', 'jwa_car' ); ?></p>
						</div>
					<?php endif; ?>
					<?php
					if ( function_exists( 'wp_pagenavi' ) ) {
						wp_pagenavi( [ 'query' => $query ] );
					}
					wp_reset_postdata(); ?>
				</div>
			</div>
		</div>
	</section>

<?php get_footer(); ?>
