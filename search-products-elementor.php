<?php
/**
 * Plugin Name: Search Products Elementor
 * Description: An Elementor widget that adds a beautiful search icon with AJAX product search functionality.
 * Version: 1.0.0
 * Author: Flow Coffee
 * Text Domain: search-products-elementor
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SPE_VERSION', '1.0.0');
define('SPE_FILE', __FILE__);
define('SPE_PATH', plugin_dir_path(SPE_FILE));
define('SPE_URL', plugin_dir_url(SPE_FILE));

/**
 * Main Plugin Class
 */
final class Search_Products_Elementor {

    /**
     * Singleton instance
     */
    private static $_instance = null;

    /**
     * Get instance
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        // Load textdomain
        add_action('plugins_loaded', [$this, 'load_textdomain']);
        
        // Check if Elementor is installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_elementor']);
            return;
        }
        
        // Check if WooCommerce is installed and activated
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_woocommerce']);
            return;
        }
        
        // Register widget
        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);
        
        // For Elementor 3.0+
        add_action('elementor/widgets/register', [$this, 'register_widgets_new']);
        
        // Enqueue styles and scripts
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        
        // Register AJAX handlers
        add_action('wp_ajax_search_products', [$this, 'ajax_search_products']);
        add_action('wp_ajax_nopriv_search_products', [$this, 'ajax_search_products']);
        
        // Add debugging information
        add_action('wp_footer', [$this, 'add_debug_info']);
    }

    /**
     * Admin notice for missing Elementor
     */
    public function admin_notice_missing_elementor() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'search-products-elementor'),
            '<strong>' . esc_html__('Search Products Elementor', 'search-products-elementor') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'search-products-elementor') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice for missing WooCommerce
     */
    public function admin_notice_missing_woocommerce() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: WooCommerce */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'search-products-elementor'),
            '<strong>' . esc_html__('Search Products Elementor', 'search-products-elementor') . '</strong>',
            '<strong>' . esc_html__('WooCommerce', 'search-products-elementor') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Load plugin textdomain
     */
    public function load_textdomain() {
        load_plugin_textdomain('search-products-elementor', false, dirname(plugin_basename(SPE_FILE)) . '/languages');
    }

    /**
     * Register Elementor widgets (for Elementor < 3.0)
     */
    public function register_widgets() {
        // Require the widget class
        require_once(SPE_PATH . 'widgets/class-search-icon-widget.php');
        
        // Register the widget
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Search_Icon_Widget());
    }
    
    /**
     * Register Elementor widgets (for Elementor >= 3.0)
     */
    public function register_widgets_new($widgets_manager) {
        // Require the widget class
        require_once(SPE_PATH . 'widgets/class-search-icon-widget.php');
        
        // Register the widget
        $widgets_manager->register(new \Search_Icon_Widget());
    }

    /**
     * Enqueue styles and scripts
     */
    public function enqueue_scripts() {
        // Styles
        wp_enqueue_style(
            'search-products-elementor',
            SPE_URL . 'assets/css/search-products-elementor.css',
            [],
            SPE_VERSION
        );
        
        // Scripts
        wp_enqueue_script(
            'search-products-elementor',
            SPE_URL . 'assets/js/search-products-elementor.js',
            ['jquery'],
            SPE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script(
            'search-products-elementor',
            'searchProductsData',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('search_products_nonce'),
            ]
        );
    }

    /**
     * AJAX search products handler
     */
    public function ajax_search_products() {
        // Check nonce
        check_ajax_referer('search_products_nonce', 'nonce');
        
        // Get search query
        $search_query = isset($_POST['search_query']) ? sanitize_text_field($_POST['search_query']) : '';
        
        // Search products
        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 10,
            's' => $search_query,
        ];
        
        $query = new \WP_Query($args);
        $products = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $product_id = get_the_ID();
                $product = wc_get_product($product_id);
                
                if (!$product) {
                    continue;
                }
                
                $products[] = [
                    'id' => $product_id,
                    'title' => get_the_title(),
                    'permalink' => get_permalink(),
                    'price' => $product->get_price_html(),
                    'image' => get_the_post_thumbnail_url($product_id, 'thumbnail'),
                ];
            }
            wp_reset_postdata();
        }
        
        wp_send_json_success([
            'products' => $products,
            'count' => count($products),
        ]);
    }
    
    /**
     * Add debugging information to footer
     */
    public function add_debug_info() {
        if (current_user_can('administrator')) {
            echo '<div style="display:none;">';
            echo '<div id="search-products-debug">Plugin version: ' . SPE_VERSION . '</div>';
            echo '</div>';
            
            echo '<script>
                console.log("Search Products Elementor loaded successfully");
                console.log("Plugin URL: ' . SPE_URL . '");
            </script>';
        }
    }
}

// Initialize the plugin
Search_Products_Elementor::instance(); 