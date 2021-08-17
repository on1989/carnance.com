<?php
/**
 * Created 14.11.2020
 * Version 1.0.0
 * Last update
 * Author: Alex L
 *
 */

namespace JWA\Car\Settings;
if ( ! defined( 'ABSPATH' ) ) {
	die ( 'Silly human what are you doing here' );
}

class jwaCarListingSettings {
	public function __construct () {
		self::addAdminMenu();

	}

	/**
	 * Add menu page Theme Settings
	 */
	public function addAdminMenu () {

		add_submenu_page( 'edit.php?post_type=' . JWA_CAR_POST_TYPE, 'Settings', 'Car Listing Settings', 'manage_options', 'listing-settings', [
			$this,
			'templateAdminPage',
		] );
	}

	/**
	 * Output template page
	 */
	public function templateAdminPage () {
		include_once JWA_CAR_PLUGIN_DIR . '/includes/template/admin/settingsPage/options.php';
	}


}
