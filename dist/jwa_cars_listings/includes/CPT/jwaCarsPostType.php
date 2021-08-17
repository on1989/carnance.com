<?php
/**
 * Created 22.10.2020
 * Version 1.0.1
 * Last update 01.12.2020
 * Author: Alex L
 *
 */

namespace JWA\Car\CPT;

if ( ! defined( 'ABSPATH' ) ) {
	die ( 'Silly human what are you doing here' );
}

/**
 * Class jwaCarsPostType
 *
 * Create Custom Post Type and everything connected with it
 *
 * @package JWA\Car\CPT
 */
class jwaCarsPostType {
	private $nameCTP;

	public function __construct ( string $nameCPT ) {
		$this->nameCTP = $nameCPT;
		self::createCPT();
		self::createTaxonomy();
		self::urlRewriteRules();
		self::registerWidgetZone();

		add_filter( 'single_template', [ $this, 'templateSingleCar' ] );
		add_filter( 'template_include', [ $this, 'addArchiveCarTemplate' ] );
		add_filter( 'query_vars', [ $this, 'addFilterVars' ] );
	}

	/**
	 * Create Custom Post Type
	 */
	private function createCPT () {
		register_post_type( $this->nameCTP, [
			'labels'             => [
				'name'               => __( 'Cars', 'jwa_car' ),
				'singular_name'      => __( 'Car', 'jwa_car' ),
				'add_new'            => __( 'Add new', 'jwa_car' ),
				'add_new_item'       => __( 'Add new car', 'jwa_car' ),
				'edit_item'          => __( 'Edit car', 'jwa_car' ),
				'new_item'           => __( 'New Car', 'jwa_car' ),
				'view_item'          => __( 'View Car', 'jwa_car' ),
				'search_items'       => __( 'Search Car', 'jwa_car' ),
				'not_found'          => __( 'Car not found', 'jwa_car' ),
				'not_found_in_trash' => __( 'No car found in the trash', 'jwa_car' ),
				'menu_name'          => __( 'Cars', 'jwa_car' ),
			],
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => [ 'slug' => 'cars', 'with_front' => false, 'feeds' => false, 'pages' => true ],
			'has_archive'        => 'cars',
			'capability_type'    => 'post',
			'hierarchical'       => false,
			'supports'           => [ 'title', 'editor', 'author', 'thumbnail', 'custom-fields', 'revisions' ],
			'menu_position'      => 9,
			'menu_icon'          => 'dashicons-car',
		] );
	}

	private function createTaxonomy () {
		register_taxonomy( 'mark', [ $this->nameCTP ], [
			'label'             => '',
			'labels'            => [
				'name'              => __( 'Marks & Models', 'jwa_car' ),
				'singular_name'     => __( 'Marks & Models', 'jwa_car' ),
				'search_items'      => __( 'Search Model', 'jwa_car' ),
				'all_items'         => __( 'All Cars', 'jwa_car' ),
				'view_item'         => __( 'View Model', 'jwa_car' ),
				'parent_item'       => __( 'Mark', 'jwa_car' ),
				'parent_item_colon' => __( 'Mark', 'jwa_car' ),
				'edit_item'         => __( 'Edit', 'jwa_car' ),
				'update_item'       => __( 'Update', 'jwa_car' ),
				'add_new_item'      => __( 'Add New', 'jwa_car' ),
				'new_item_name'     => __( 'New Mark or Model', 'jwa_car' ),
				'menu_name'         => __( 'Mark & Models', 'jwa_car' ),
			],
			'description'       => __( 'Create Car Brand and Add Model to it', 'jwa_car' ),
			'public'            => true,
			'hierarchical'      => true,
			'rewrite'           => true,
			'capabilities'      => [],
			'meta_box_cb'       => null,
			'show_admin_column' => true,
			'show_in_rest'      => null,
			'rest_base'         => null,
		] );

		register_taxonomy( 'engine', [ $this->nameCTP ], [
			'label'             => '',
			'labels'            => [
				'name'              => __( 'Engine', 'jwa_car' ),
				'singular_name'     => __( 'Engine', 'jwa_car' ),
				'search_items'      => __( 'Search Engine', 'jwa_car' ),
				'all_items'         => __( 'All Engines', 'jwa_car' ),
				'view_item'         => __( 'View Engine', 'jwa_car' ),
				'parent_item'       => __( 'Engine', 'jwa_car' ),
				'parent_item_colon' => __( 'Engine', 'jwa_car' ),
				'edit_item'         => __( 'Edit', 'jwa_car' ),
				'update_item'       => __( 'Update', 'jwa_car' ),
				'add_new_item'      => __( 'Add New', 'jwa_car' ),
				'new_item_name'     => __( 'New Engine', 'jwa_car' ),
				'menu_name'         => __( 'Engine', 'jwa_car' ),
			],
			'description'       => __( 'Create Engine Size', 'jwa_car' ),
			'public'            => true,
			'hierarchical'      => true,
			'rewrite'           => true,
			'capabilities'      => [],
			'meta_box_cb'       => null,
			'show_admin_column' => true,
			'show_in_rest'      => null,
			'rest_base'         => null,
		] );

		register_taxonomy( 'car_tag', [ $this->nameCTP ], [
			'label'             => '',
			'labels'            => [
				'name'              => __( 'Tag', 'jwa_car' ),
				'singular_name'     => __( 'Tag', 'jwa_car' ),
				'search_items'      => __( 'Search Tag', 'jwa_car' ),
				'all_items'         => __( 'All Tags', 'jwa_car' ),
				'view_item'         => __( 'View Tag', 'jwa_car' ),
				'parent_item'       => __( 'Tag', 'jwa_car' ),
				'parent_item_colon' => __( 'Tag', 'jwa_car' ),
				'edit_item'         => __( 'Edit', 'jwa_car' ),
				'update_item'       => __( 'Update', 'jwa_car' ),
				'add_new_item'      => __( 'Add New', 'jwa_car' ),
				'new_item_name'     => __( 'New Tag', 'jwa_car' ),
				'menu_name'         => __( 'Tag', 'jwa_car' ),
			],
			'description'       => __( 'Create Engine Size', 'jwa_car' ),
			'public'            => true,
			'hierarchical'      => false,
			'rewrite'           => true,
			'capabilities'      => [],
			'meta_box_cb'       => null,
			'show_admin_column' => true,
			'show_in_rest'      => null,
			'rest_base'         => null,
		] );
	}

	/**
	 * Checks if the theme has a custom template for output Singe Car
	 *
	 * @param $single
	 *
	 * @return string
	 */
	public function templateSingleCar ( $single ) {
		global $post;

		if ( $post->post_type == $this->nameCTP ) {
			if ( file_exists( get_template_directory() . '/single-car.php' ) ) {
				return $single;
			} else {
				return JWA_CAR_PLUGIN_DIR . '/template/single_car/single-car.php';
			}
		}

		return $single;
	}

	/**
	 * Checks if the theme has a custom template for output Archive Car
	 *
	 * @param $template
	 *
	 * @return string
	 */
	public function addArchiveCarTemplate ( $template ) {
		if ( is_post_type_archive( JWA_CAR_POST_TYPE ) ) {
			$theme_files     = [ 'archive-' . JWA_CAR_POST_TYPE . '.php' ];
			$exists_in_theme = locate_template( $theme_files, false );
			if ( $exists_in_theme != '' ) {
				return $exists_in_theme;
			} else {
				return JWA_CAR_PLUGIN_DIR . '/template/archive/archive-car.php';
			}
		}

		return $template;
	}

	/**
	 * Add Rewrite rules url
	 */
	public function urlRewriteRules () {

		add_rewrite_rule(
			'cars/filter/([-_a-zA-Z0-9+%.:]+)/page/(\d+)/?$',
			'index.php?post_type=' . JWA_CAR_POST_TYPE . '&filter=$matches[1]&paged=$matches[2]',
			'top'
		);

		add_rewrite_rule(
			"cars/filter/([-_a-zA-Z0-9+%.:]+)/?$",
			'index.php?post_type=' . JWA_CAR_POST_TYPE . '&filter=$matches[1]',
			'top'
		);

		flush_rewrite_rules();
	}

	/**
	 * Add Query vars filter
	 *
	 * @param $vars
	 *
	 * @return mixed
	 */
	public function addFilterVars ( $vars ) {
		$vars[] = 'filter';

		return $vars;
	}

	/**
	 * Register widget zone in single car
	 */
	public function registerWidgetZone () {
		register_sidebar( [
			'name'          => __( 'Single Car Widget', 'jwa_car' ),
			'id'            => "jwa_single_car",
			'description'   => '',
			'class'         => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => "</div>\n",
			'before_title'  => '<h2 class="single_car_title">',
			'after_title'   => "</h2>\n",
		] );
	}
}
