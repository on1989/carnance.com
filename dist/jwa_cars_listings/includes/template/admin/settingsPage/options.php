<?php
/**
 * Created 14.11.2020
 * Version 1.0.0
 * Last update
 * Author: Alex L
 *
 */
?>
<h1><?php _e( 'Settings JWA Car Listing', 'trust-bar-reviews' ); ?></h1>
<form method="POST" action="options.php">
	<?php
	settings_fields( 'jwa_car_listing_group' );
	do_settings_sections( 'listing-settings' );
	?>
	<div class="form-control">
		<input type="submit" name="submit" value="<?php _e( 'Save Changes', 'trust-bar-reviews' ); ?>"
		       class="button button-primary">
	</div>
</form>
