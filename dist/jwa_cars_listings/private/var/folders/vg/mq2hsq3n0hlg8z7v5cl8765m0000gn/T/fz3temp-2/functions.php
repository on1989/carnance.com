<?php

/**
 * Created 09.10.19
 * Version 1.1.0
 * Last update 09.10.19
 * Author: Zhenja L
 */

// add script and style
add_action('wp_enqueue_scripts', 'scripts_init');

function scripts_init()
{
    wp_enqueue_script('build', get_template_directory_uri() . '/assets/js/build.js', array('jquery'), version('/assets/js/build.js'), true);
    wp_enqueue_script('g-map', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCIe_Nx28mjmD1ApD5b6LQJWEAboJXmseI&amp;', array('build'), '', true);
    wp_enqueue_script('map', get_template_directory_uri() . '/assets/js/map.js', array('jquery'), version('/assets/js/map.js'), true);
    wp_enqueue_style('main-css', get_template_directory_uri() . '/assets/css/main.css', array(), version('/assets/css/main.css'));
    wp_enqueue_style('style-css', get_template_directory_uri() . '/style.css', array(), version('/style.css'));
    wp_enqueue_style('google_fonts', 'https://fonts.googleapis.com/css?family=Montserrat:400,500,700,800&amp;display=swap');
    wp_enqueue_style('google_fonts_2', 'https://fonts.googleapis.com/css?family=Roboto:400,700&amp;display=swap');

    wp_enqueue_script('ajax', get_template_directory_uri() . '/assets/js/ajax.js', array('jquery'), version('/assets/js/ajax.js'), true);

    wp_localize_script('ajax', 'url', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
    ));
}

function version($src)
{
    return filemtime(get_stylesheet_directory() . $src);
}
function sidebar_widget_init(){
    register_sidebar( array(
    'name'          => 'Sidebar',
    'id'            => 'Sidebar',
    ));
}
add_action('widgets_init', 'sidebar_widget_init');
/**
 * mime_types
 * add support svg file
 * Version 1.0.0
 */
function mime_types($mime_types)
{
    $mime_types['svg'] = 'image/svg+xml';
    return $mime_types;
}
add_filter('upload_mimes', 'mime_types', 1, 1);

add_action('init', 'register_product_types');
function register_product_types()
{
    register_post_type('products', array(
        'label' => null,
        'labels' => array(
            'name' => 'Products',
            'singular_name' => 'Product',
            'add_new' => 'Add Product',
            'add_new_item' => 'Add New Product',
            'edit_item' => 'Edite Product',
            'new_item' => 'New Product',
            'view_item' => 'View Product',
            'search_items' => 'Search Product',
            'not_found' => 'Not Found',
            'not_found_in_trash' => 'Not Found In Trash',
            'menu_name' => 'Products',
        ),
        'description' => '',
        'public' => true,

        'show_in_menu' => null,
        'show_in_rest' => null,
        'rest_base' => null,
        'menu_position' => null,
        'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" height="26px" viewBox="0 0 64 64" width="26px" class=""><g><path d="m4 53a1 1 0 0 0 1 1h31v-23a3 3 0 0 1 3-3h13v-7h-18v7a1 1 0 0 1 -1.625.781l-4.375-3.501-4.375 3.5a1 1 0 0 1 -1.625-.78v-7h-18z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#9EA3A8"/><path d="m27.375 23.219a1 1 0 0 1 1.25 0l3.375 2.7v-12.919h-8v12.919z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#9EA3A8"/><path d="m34 13h18v6h-18z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#9EA3A8"/><path d="m4 13h18v6h-18z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#9EA3A8"/><path d="m55.707 32.293.293.293v-2.586h-2v2.586l.293-.293a1 1 0 0 1 1.414 0z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#9EA3A8"/><path d="m39 60h20a1 1 0 0 0 1-1v-28a1 1 0 0 0 -1-1h-1v5a1 1 0 0 1 -.617.924.987.987 0 0 1 -.383.076 1 1 0 0 1 -.707-.293l-1.293-1.293-1.293 1.293a1 1 0 0 1 -1.707-.707v-5h-13a1 1 0 0 0 -1 1v28a1 1 0 0 0 1 1zm15-22h4v2h-4zm-4 0h2v2h-2zm0 4h8v2h-8zm0 7h8v2h-8zm0 4h8v2h-8zm-8.293-12.707 1.293 1.293 3.293-3.293 1.414 1.414-4 4a1 1 0 0 1 -1.414 0l-2-2zm0 11 1.293 1.293 3.293-3.293 1.414 1.414-4 4a1 1 0 0 1 -1.414 0l-2-2z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#9EA3A8"/><path d="m45.93 4h-35.86a.985.985 0 0 0 -.825.44l-4.376 6.56h46.262l-4.373-6.556a.988.988 0 0 0 -.828-.444z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#9EA3A8"/></g> </svg>'),
        'hierarchical' => false,
        'supports' => array('thumbnail', 'title', 'custom-fields', 'editor',),
        'taxonomies' => [],
        'has_archive' => false,
        'rewrite' => true,
        'query_var' => true,
    ));
}
add_action('init', 'register_reviews_types');
function register_reviews_types()
{
    register_post_type('reviews', array(
        'label' => null,
        'labels' => array(
            'name' => 'Reviews',
            'singular_name' => 'Reviews',
            'add_new' => 'Add Reviews',
            'add_new_item' => 'Add New Reviews',
            'edit_item' => 'Edite Reviews',
            'new_item' => 'New Reviews',
            'view_item' => 'View Reviews',
            'search_items' => 'Search Reviews',
            'not_found' => 'Not Found',
            'not_found_in_trash' => 'Not Found In Trash',
            'menu_name' => 'Reviews',
        ),
        'description' => '',
        'public' => true,

        'show_in_menu' => null,
        'show_in_rest' => null,
        'rest_base' => null,
        'menu_position' => null,
        'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 372.96 372.96" style="enable-background:new 0 0 372.96 372.96;" xml:space="preserve" width="26px" height="26px"><path d="M133.04,128.128c-8.922-8.95-21.043-13.974-33.68-13.96c-26.311-0.007-47.646,21.316-47.653,47.627    s21.316,47.646,47.627,47.653s47.646-21.316,47.653-47.627C146.99,149.185,141.973,137.065,133.04,128.128z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#9EA3A8"/>		<path d="M101.92,225.008h-5.08C43.411,225.14,0.132,268.42,0,321.848v6.84c0,3.756,3.044,6.8,6.8,6.8h185.12    c3.756,0,6.8-3.044,6.8-6.8v-6.84C198.566,268.444,155.324,225.184,101.92,225.008z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#9EA3A8"/><path d="M340.64,60.568c-22.035-15.432-48.382-23.509-75.28-23.08c-26.898-0.429-53.245,7.648-75.28,23.08    c-20,14.72-32.32,35.2-32.32,58c0.032,10.316,2.557,20.471,7.36,29.6c4.292,8.129,9.963,15.451,16.76,21.64l-17.08,35.48    c-1.635,3.357-0.239,7.403,3.118,9.037c2.102,1.024,4.584,0.889,6.562-0.357l39.28-24.24c7.471,3.05,15.212,5.394,23.12,7    c9.372,1.919,18.914,2.884,28.48,2.88c26.898,0.429,53.245-7.648,75.28-23.08c20-14.72,32.32-35.2,32.32-58    C372.96,95.728,360.6,75.288,340.64,60.568z M316.372,107.636c-0.004,0.017-0.008,0.035-0.012,0.052h0    c-0.266,1.185-0.846,2.277-1.68,3.16l-17.44,20l2.32,26.84c0.327,3.741-2.44,7.039-6.182,7.367    c-1.307,0.114-2.619-0.152-3.778-0.767l-24.24-10.2l-24.8,10.52c-3.43,1.474-7.406-0.111-8.88-3.541    c-0.446-1.039-0.625-2.173-0.52-3.299l2.32-26.84l-17.64-20.44c-2.443-2.853-2.11-7.145,0.743-9.588    c0.823-0.705,1.803-1.203,2.857-1.452l26.2-6.08l13.88-22.88c1.924-3.225,6.098-4.28,9.323-2.357    c0.969,0.578,1.779,1.388,2.357,2.357l13.8,22.96l26.2,6.08C314.867,100.339,317.183,103.969,316.372,107.636z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#9EA3A8"/></svg>'),
        'hierarchical' => false,
        'supports' => ['title', 'custom-fields', 'author', 'editor', 'excerpt'],
        'taxonomies' => [],
        'has_archive' => false,
        'rewrite' => true,
        'query_var' => true,
    ));
}
add_action('init', 'create_taxonomy');
function create_taxonomy()
{
    register_taxonomy('product-category', ['products'], [
        'label' => '',
        'labels' => [
            'name' => 'Product Category',
            'singular_name' => 'Product Category',
            'search_items' => 'Search Product Category',
            'all_items' => 'All Product Category',
            'view_item ' => 'View Product Category',
            'parent_item' => 'Parent Product Category',
            'parent_item_colon' => 'Parent Product Category:',
            'edit_item' => 'Edit Product Category',
            'update_item' => 'Update Product Category',
            'add_new_item' => 'Add New Product Category',
            'new_item_name' => 'New Product Category Name',
            'menu_name' => 'Product Category',
        ],
        'description' => '',
        'public' => true,
        'publicly_queryable' => null,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_tagcloud' => true,
        'show_in_rest' => false,
        'rest_base' => false,
        'hierarchical' => true,
        'rewrite' => true,
        'capabilities' => array(),
        'meta_box_cb' => null,
        'show_admin_column' => false,
        '_builtin' => false,
        'show_in_quick_edit' => null,
        'query_var' => true,

        'rewrite' => array('slug' => 'product'),
    ]);
}
add_action('after_setup_theme', 'init_theme');
function init_theme()
{

    add_theme_support('post-thumbnails');

    // add custom thumbnails
    add_image_size('latest-img-size', 370, 370, array('center', 'top'));
    add_image_size('big_image_news_block', 655, 480, array('center', 'top'));
    add_image_size('small_image_news_block', 75, 75, true);
    add_image_size('inner_post_thub', 226, 361, true);
    add_image_size('cat_big_image', 740, 520, array('center', 'top'));
    add_image_size('diy_big_image', 1140, 525, array('center', 'top'));
    add_image_size('post_news', 270, 320, true);
    add_image_size('single_post_thumbnail', 900, 600, array('center', 'top'));

    // register menu
    register_nav_menus([
        'header_menu' => 'Top-menu',
        'post_category' => 'Post-category',
        'post_category' => 'Terms-Menu',
    ]);
}
if (function_exists('acf_add_options_page')) {
    acf_add_options_page();
}

remove_action ( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

// add ajax
require_once get_template_directory() . '/admin/ajax.php';

// add menu filter
require_once get_template_directory() . '/admin/menu.php';

// remove tax slug
// require_once get_template_directory() . '/admin/remove_tax_slug.php';
