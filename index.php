<?php
/*
Plugin Name: Glowlogix Datatables
Description: A simple WordPress plugin template
Version: 1.0
Author: GlowLogix
Author URI: https://www.glowlogix.com/
License: GPL2
*/
/*
Copyright 2018    (email : masood@glowlogix.com)

*/
if ( !defined('DATATABLE_BASE_URL') )
    define('DATATABLE_BASE_URL', plugin_dir_path( __FILE__ ));

require DATATABLE_BASE_URL . 'includes/activation.php';
require DATATABLE_BASE_URL . 'includes/frontend.php';
require DATATABLE_BASE_URL . 'includes/backend.php';
//require DATATABLE_BASE_URL . 'css/form.css';
register_activation_hook(__FILE__,"create_agri_table");
register_deactivation_hook( __FILE__, 'my_plugin_remove_tables' );
?>
<?php

add_action('admin_print_styles', 'custom_css');
function custom_css() {
    wp_enqueue_style('agri-style',plugin_dir_url( __FILE__ ) . 'css/form1.css',false);
}

/*Function to Show the DataTable Ends Here*/
add_shortcode('data-added','datatable_add');
function custom_post_type(){
    $labels = array(
        'name'                  => _x( 'Ingredients', 'Post type general name', 'textdomain' ),
        'singular_name'         => _x( 'Ingredient', 'Post type singular name', 'textdomain' ),
        'menu_name'             => _x( 'Ingredients', 'Admin Menu text', 'textdomain' ),
        'name_admin_bar'        => _x( 'Ingredient', 'Add New on Toolbar', 'textdomain' ),
        'add_new'               => __( 'Add New', 'textdomain' ),
        'add_new_item'          => __( 'Add New Ingredient', 'textdomain' ),
        'new_item'              => __( 'New Ingredient', 'textdomain' ),
        'edit_item'             => __( 'Edit Ingredient', 'textdomain' ),
        'view_item'             => __( 'View Ingredient', 'textdomain' ),
        'all_items'             => __( 'All Ingredients', 'textdomain' ),
        'search_items'          => __( 'Search Ingredients', 'textdomain' ),
        'parent_item_colon'     => __( 'Parent Ingredients:', 'textdomain' ),
        'not_found'             => __( 'No Ingredients found.', 'textdomain' ),
        'not_found_in_trash'    => __( 'No Ingredients found in Trash.', 'textdomain' ),
        'featured_image'        => _x( 'Ingredient  Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'archives'              => _x( 'Ingredient archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
        'insert_into_item'      => _x( 'Insert into Ingredient', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this Ingredient', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
        'filter_items_list'     => _x( 'Filter Ingredients list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
        'items_list_navigation' => _x( 'Ingredients list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
        'items_list'            => _x( 'Ingredients list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'ingredient' ),
        'capability_type'    => 'page',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title' , 'editor', 'thumbnail'),
    );
    register_post_type( 'Ingredient', $args );

    $chemical = array(
        'label'        => __( 'Chemical Group', 'textdomain' ),
        'public'       => true,
        'rewrite'      => false,
        'hierarchical' => true,
        'show_in_rest'      => true,
    );
    $ingredient = array(
        'label'        => __( 'Ingredient Category', 'textdomain' ),
        'public'       => true,
        'rewrite'      => false,
        'hierarchical' => true,
        'show_in_rest'      => true,
    );

    register_taxonomy( 'chemical_group', 'ingredient', $chemical );
    register_taxonomy( 'ingredient_category', 'ingredient', $ingredient );

    /**
     * Product Custom post type and taxonomies.
     */
    $labels = array(
        'name'                  => _x( 'Products', 'Post type general name', 'textdomain' ),
        'singular_name'         => _x( 'Product', 'Post type singular name', 'textdomain' ),
        'menu_name'             => _x( 'Products', 'Admin Menu text', 'textdomain' ),
        'name_admin_bar'        => _x( 'Product', 'Add New on Toolbar', 'textdomain' ),
        'add_new'               => __( 'Add New', 'textdomain' ),
        'add_new_item'          => __( 'Add New Product', 'textdomain' ),
        'new_item'              => __( 'New Product', 'textdomain' ),
        'edit_item'             => __( 'Edit Product', 'textdomain' ),
        'view_item'             => __( 'View Product', 'textdomain' ),
        'all_items'             => __( 'All Products', 'textdomain' ),
        'search_items'          => __( 'Search Products', 'textdomain' ),
        'parent_item_colon'     => __( 'Parent Products:', 'textdomain' ),
        'not_found'             => __( 'No Products found.', 'textdomain' ),
        'not_found_in_trash'    => __( 'No Products found in Trash.', 'textdomain' ),
        'featured_image'        => _x( 'Product  Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'archives'              => _x( 'Product archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
        'insert_into_item'      => _x( 'Insert into Product', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this Product', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
        'filter_items_list'     => _x( 'Filter Products list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
        'items_list_navigation' => _x( 'Products list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
        'items_list'            => _x( 'Products list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
    );
    $args   = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        //'rewrite'            => array( 'slug' => 'product' ),
        'rewrite'            => array( 'slug' => 'product/%country%', 'with_front' => false ),
        'has_archive'        => 'product',
        'capability_type'    => 'page',
        //'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
    );
    register_post_type( 'Product', $args );

    $country = array(
        'label'        => __( 'Countries', 'textdomain' ),
        'public'       => true,
        //'rewrite'      => false,
        'rewrite'      => array( 'slug' => 'product', 'with_front' => false ),
        'hierarchical' => true,
        'show_in_rest'      => true,
    );
    $manufacturer = array(
        'label'        => __( 'Manufacturer', 'textdomain' ),
        'public'       => true,
        'rewrite'      => false,
        'hierarchical' => true
    );
    $crop = array(
        'label'                      => __( 'Crops', 'textdomain' ),
        'show_ui'                    => true,
        'show_in_quick_edit'         => false,
        'meta_box_cb'                => false,
        'public'                     => true,
        'rewrite'                    => false,
        'hierarchical'               => true,
        'show_in_rest'      => true,
    );
    $activity = array(
        'label'        => __( 'Activity', 'textdomain' ),
        'show_ui'                    => true,
        'show_in_quick_edit'         => false,
        'meta_box_cb'                => false,
        'public'                     => true,
        'rewrite'                    => false,
        'hierarchical'               => true,
        'show_in_rest'      => true,
    );

    register_taxonomy( 'country', 'product', $country );
    register_taxonomy( 'manufacturer', 'product', $manufacturer );
    register_taxonomy( 'crop', 'product', $crop );
    register_taxonomy( 'activity', array( 'product'), $activity );
    
}
add_action('init', 'custom_post_type');

/**
 * @param $post_link
 * @param $post
 * @return mixed
 */
function wpa_show_permalinks( $post_link, $post ){
    if ( is_object( $post ) && $post->post_type == 'product' ){
        $terms = wp_get_object_terms( $post->ID, 'country' );
        if( $terms ){
            return str_replace( '%country%' , $terms[0]->slug , $post_link );
        }
    }
    return $post_link;
}
add_filter( 'post_type_link', 'wpa_show_permalinks', 1, 2 );

/**
 * Enqueue Select2 Script and CSS.
 */
function wpa_enqueue($hook) {
    global $current_screen;

    if ( 'post.php' != $hook ) return;
    if ( 'product'  != $current_screen->post_type ) return;

    wp_enqueue_style( 'select2_css', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css' );
    wp_enqueue_script( 'select2_script', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js' );
}
add_action( 'admin_enqueue_scripts', 'wpa_enqueue' );