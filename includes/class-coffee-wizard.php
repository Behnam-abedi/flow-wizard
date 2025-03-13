<?php
/**
 * Main Coffee Wizard Class
 */
class Coffee_Wizard {
    /**
     * Admin class instance
     *
     * @var Coffee_Wizard_Admin
     */
    private $admin;

    /**
     * Public class instance
     *
     * @var Coffee_Wizard_Public
     */
    private $public;

    /**
     * Initialize the plugin
     */
    public function init() {
        // Initialize admin
        $this->admin = new Coffee_Wizard_Admin();
        $this->admin->init();

        // Initialize public
        $this->public = new Coffee_Wizard_Public();
        $this->public->init();

        // Register AJAX handlers
        $this->register_ajax_handlers();

        // Register shortcodes
        add_shortcode('coffee_wizard_form', array($this, 'render_wizard_form'));
    }

    /**
     * Register AJAX handlers
     */
    private function register_ajax_handlers() {
        // Get product categories
        add_action('wp_ajax_get_product_categories', array($this, 'get_product_categories'));
        add_action('wp_ajax_nopriv_get_product_categories', array($this, 'get_product_categories'));

        // Get products by category
        add_action('wp_ajax_get_products_by_category', array($this, 'get_products_by_category'));
        add_action('wp_ajax_nopriv_get_products_by_category', array($this, 'get_products_by_category'));

        // Get weight options
        add_action('wp_ajax_get_weight_options', array($this, 'get_weight_options'));
        add_action('wp_ajax_nopriv_get_weight_options', array($this, 'get_weight_options'));

        // Get grinding options
        add_action('wp_ajax_get_grinding_options', array($this, 'get_grinding_options'));
        add_action('wp_ajax_nopriv_get_grinding_options', array($this, 'get_grinding_options'));

        // Add to cart
        add_action('wp_ajax_add_to_cart', array($this, 'add_to_cart'));
        add_action('wp_ajax_nopriv_add_to_cart', array($this, 'add_to_cart'));
    }

    /**
     * Render the wizard form shortcode
     *
     * @return string
     */
    public function render_wizard_form() {
        ob_start();
        include COFFEE_WIZARD_PATH . 'templates/public/wizard-form.php';
        return ob_get_clean();
    }

    /**
     * AJAX handler for getting product categories
     */
    public function get_product_categories() {
        $parent_id = isset($_POST['parent_id']) ? intval($_POST['parent_id']) : 150; // Default to Quick Order (ID: 150)
        
        $categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => $parent_id
        ));

        $formatted_categories = array();
        
        foreach ($categories as $category) {
            $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
            $image = wp_get_attachment_url($thumbnail_id);
            $icon_class = get_term_meta($category->term_id, 'category_icon_class', true);
            
            $formatted_categories[] = array(
                'id' => $category->term_id,
                'name' => $category->name,
                'slug' => $category->slug,
                'image' => $image,
                'icon_class' => $icon_class,
                'has_children' => $this->category_has_children($category->term_id)
            );
        }
        
        wp_send_json_success($formatted_categories);
    }

    /**
     * Check if a category has children
     *
     * @param int $category_id
     * @return bool
     */
    private function category_has_children($category_id) {
        $children = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => $category_id
        ));
        
        return !empty($children);
    }

    /**
     * AJAX handler for getting products by category
     */
    public function get_products_by_category() {
        $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
        
        if (!$category_id) {
            wp_send_json_error('Invalid category ID');
        }
        
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $category_id
                )
            )
        );
        
        $products = new WP_Query($args);
        $formatted_products = array();
        
        if ($products->have_posts()) {
            while ($products->have_posts()) {
                $products->the_post();
                $product = wc_get_product(get_the_ID());
                
                $formatted_products[] = array(
                    'id' => $product->get_id(),
                    'name' => $product->get_name(),
                    'price' => $product->get_price(),
                    'formatted_price' => $product->get_price_html(),
                    'image' => wp_get_attachment_url($product->get_image_id()),
                    'description' => $product->get_short_description()
                );
            }
            
            wp_reset_postdata();
        }
        
        wp_send_json_success($formatted_products);
    }

    /**
     * AJAX handler for getting weight options
     */
    public function get_weight_options() {
        $category_slug = isset($_POST['category_slug']) ? sanitize_text_field($_POST['category_slug']) : '';
        
        if (!in_array($category_slug, array('specialty', 'blend'))) {
            wp_send_json_error('Invalid category slug');
        }
        
        $weight_options = get_option('coffee_wizard_weight_options_' . $category_slug, array());
        
        wp_send_json_success($weight_options);
    }

    /**
     * AJAX handler for getting grinding options
     */
    public function get_grinding_options() {
        $grinding_options = get_option('coffee_wizard_grinding_options', array());
        
        wp_send_json_success($grinding_options);
    }

    /**
     * AJAX handler for adding to cart
     */
    public function add_to_cart() {
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $weight = isset($_POST['weight']) ? sanitize_text_field($_POST['weight']) : '';
        $grinding_option = isset($_POST['grinding_option']) ? sanitize_text_field($_POST['grinding_option']) : '';
        $grinding_machine = isset($_POST['grinding_machine']) ? sanitize_text_field($_POST['grinding_machine']) : '';
        $notes = isset($_POST['notes']) ? sanitize_textarea_field($_POST['notes']) : '';
        
        if (!$product_id) {
            wp_send_json_error('Invalid product ID');
        }
        
        $cart_item_data = array(
            'coffee_wizard_weight' => $weight,
            'coffee_wizard_grinding_option' => $grinding_option,
            'coffee_wizard_grinding_machine' => $grinding_machine,
            'coffee_wizard_notes' => $notes
        );
        
        // Add to cart
        $cart_item_key = WC()->cart->add_to_cart($product_id, 1, 0, array(), $cart_item_data);
        
        if ($cart_item_key) {
            wp_send_json_success(array(
                'cart_item_key' => $cart_item_key,
                'redirect_url' => wc_get_checkout_url()
            ));
        } else {
            wp_send_json_error('Failed to add product to cart');
        }
    }
} 