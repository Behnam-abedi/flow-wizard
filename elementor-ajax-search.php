<?php
/**
 * Plugin Name: Elementor AJAX Product Search
 * Description: Adds a beautiful search feature with real-time AJAX product search
 * Version: 1.0.0
 * Author: Claude
 * Text Domain: elementor-ajax-search
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('EAPS_VERSION', '1.0.0');
define('EAPS_PATH', plugin_dir_path(__FILE__));
define('EAPS_URL', plugin_dir_url(__FILE__));

/**
 * Check if Elementor is installed and activated
 * @return boolean
 */
function eaps_is_elementor_activated() {
    return did_action('elementor/loaded');
}

/**
 * Show admin notice if Elementor is not active
 */
function eaps_admin_notice_missing_elementor() {
    if (isset($_GET['activate'])) {
        unset($_GET['activate']);
    }

    $message = sprintf(
        esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'elementor-ajax-search'),
        '<strong>' . esc_html__('Elementor AJAX Product Search', 'elementor-ajax-search') . '</strong>',
        '<strong>' . esc_html__('Elementor', 'elementor-ajax-search') . '</strong>'
    );

    printf('<div class="notice notice-error"><p>%1$s</p></div>', $message);
}

/**
 * Init the plugin after plugins loaded
 */
function eaps_init_plugin() {
    // Check if Elementor installed and activated
    if (!eaps_is_elementor_activated()) {
        add_action('admin_notices', 'eaps_admin_notice_missing_elementor');
        return;
    }

    // Register widget scripts and styles
    add_action('wp_enqueue_scripts', 'eaps_enqueue_scripts');
    
    // Register AJAX handlers
    add_action('wp_ajax_eaps_search_products', 'eaps_ajax_search_products');
    add_action('wp_ajax_nopriv_eaps_search_products', 'eaps_ajax_search_products');
    
    // Register Elementor widget
    add_action('elementor/widgets/widgets_registered', 'eaps_register_widgets');
    
    // Add new category
    add_action('elementor/elements/categories_registered', 'eaps_add_elementor_widget_categories');
}

/**
 * Enqueue scripts and styles
 */
function eaps_enqueue_scripts() {
    // Enqueue the main CSS file
    wp_enqueue_style(
        'eaps-style',
        EAPS_URL . 'assets/css/eaps-style.css',
        array(),
        EAPS_VERSION
    );

    // Enqueue the main JS file
    wp_enqueue_script(
        'eaps-script',
        EAPS_URL . 'assets/js/eaps-script.js',
        array('jquery'),
        EAPS_VERSION,
        true
    );

    // Localize the script with our data
    wp_localize_script(
        'eaps-script',
        'eaps_params',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('eaps_search_nonce'),
            'no_results_text' => __('چیزی یافت نشد', 'elementor-ajax-search')
        )
    );
}

/**
 * AJAX handler for product search
 */
function eaps_ajax_search_products() {
    // Check the nonce
    check_ajax_referer('eaps_search_nonce', 'nonce');

    // Get the search query
    $search_query = sanitize_text_field($_POST['search_query']);

    // The arguments for the WP_Query
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        's' => $search_query,
        'posts_per_page' => 10,
    );

    // The Query
    $search_query = new WP_Query($args);

    // Prepare the response
    $response = array(
        'success' => true,
        'products' => array(),
    );

    // Check if there are any posts
    if ($search_query->have_posts()) {
        while ($search_query->have_posts()) {
            $search_query->the_post();
            
            $product_id = get_the_ID();
            $product = wc_get_product($product_id);
            
            if (!$product) {
                continue;
            }
            
            $response['products'][] = array(
                'id' => $product_id,
                'title' => get_the_title(),
                'permalink' => get_permalink(),
                'price' => $product->get_price_html(),
                'image' => get_the_post_thumbnail_url($product_id, 'thumbnail'),
            );
        }
    } else {
        $response['success'] = false;
        $response['message'] = __('چیزی یافت نشد', 'elementor-ajax-search');
    }

    // Reset post data
    wp_reset_postdata();

    // Send the response
    wp_send_json($response);
}

/**
 * Add a custom category for our widgets
 */
function eaps_add_elementor_widget_categories($elements_manager) {
    $elements_manager->add_category(
        'eaps-elements',
        [
            'title' => __('AJAX Product Search', 'elementor-ajax-search'),
            'icon' => 'fa fa-search',
        ]
    );
}

/**
 * Register Elementor widget
 */
function eaps_register_widgets() {
    // Include the widget file only when needed
    require_once EAPS_PATH . 'widgets/class-search-icon-widget.php';
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor_Search_Icon_Widget());
}

// Initialize plugin after all plugins are loaded
add_action('plugins_loaded', 'eaps_init_plugin');