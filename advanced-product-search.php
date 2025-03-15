<?php
/**
 * Plugin Name: Advanced Product Search
 * Description: Advanced AJAX product search with beautiful UI for Elementor
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: advanced-product-search
 */

if (!defined('ABSPATH')) {
    exit;
}

// Register Elementor widget
function register_product_search_widget($widgets_manager) {
    require_once(__DIR__ . '/widgets/product-search-widget.php');
    $widgets_manager->register(new \Product_Search_Widget());
}
add_action('elementor/widgets/register', 'register_product_search_widget');

// Enqueue necessary scripts and styles
function enqueue_search_assets() {
    wp_enqueue_style(
        'advanced-product-search',
        plugins_url('assets/css/style.css', __FILE__),
        array(),
        '1.0.0'
    );

    wp_enqueue_script(
        'advanced-product-search',
        plugins_url('assets/js/search.js', __FILE__),
        array('jquery'),
        '1.0.0',
        true
    );

    wp_localize_script('advanced-product-search', 'searchAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('search_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_search_assets');

// AJAX handler for product search
function handle_product_search() {
    check_ajax_referer('search_nonce', 'nonce');
    
    $search_term = sanitize_text_field($_POST['search_term']);
    
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        's' => $search_term,
        'posts_per_page' => 10,
    );

    $query = new WP_Query($args);
    $products = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $product = wc_get_product(get_the_ID());
            
            $products[] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'price' => $product->get_price_html(),
                'image' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'),
                'url' => get_permalink()
            );
        }
        wp_reset_postdata();
    }

    wp_send_json_success($products);
}
add_action('wp_ajax_product_search', 'handle_product_search');
add_action('wp_ajax_nopriv_product_search', 'handle_product_search'); 