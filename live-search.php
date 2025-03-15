<?php
/*
Plugin Name: جستجوی زنده پیشرفته
Plugin URI: 
Description: افزونه جستجوی زنده با پشتیبانی از پستها، برگهها، محصولات و دستهبندیها
Version: 1.0
Author: Your Name
Text Domain: live-search
*/

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/functions.php';

// Enqueue scripts and styles
function live_search_scripts() {
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'live_search')) {
        wp_enqueue_style('live-search-style', plugins_url('assets/css/style.css', __FILE__));
        wp_enqueue_script('live-search-script', plugins_url('assets/js/script.js', __FILE__), array('jquery'), '1.0', true);
        
        // اصلاح localize script
        wp_localize_script('live-search-script', 'live_search_obj', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('live_search_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'live_search_scripts');

// Add shortcode for search box
function live_search_shortcode() {
    ob_start(); ?>
    <div class="live-search-container">
        <input type="text" id="live-search-input" placeholder="جستجو کنید...">
        <div id="live-search-results"></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('live_search', 'live_search_shortcode');

// AJAX handler 
add_action('wp_ajax_live_search', 'live_search_results');
add_action('wp_ajax_nopriv_live_search', 'live_search_results');