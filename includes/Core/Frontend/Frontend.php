<?php
namespace CoffeeWizard\Core\Frontend;

class Frontend {
    public function init() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_get_subcategories', array($this, 'get_subcategories'));
        add_action('wp_ajax_nopriv_get_subcategories', array($this, 'get_subcategories'));
        add_action('wp_ajax_submit_coffee_order', array($this, 'submit_coffee_order'));
        add_action('wp_ajax_nopriv_submit_coffee_order', array($this, 'submit_coffee_order'));
    }

    public function enqueue_scripts() {
        wp_enqueue_style(
            'coffee-wizard-public',
            COFFEE_WIZARD_PLUGIN_URL . 'assets/css/public.css',
            array(),
            COFFEE_WIZARD_VERSION
        );

        wp_enqueue_script(
            'coffee-wizard-public',
            COFFEE_WIZARD_PLUGIN_URL . 'assets/js/public.js',
            array('jquery'),
            COFFEE_WIZARD_VERSION,
            true
        );

        wp_localize_script('coffee-wizard-public', 'coffeeWizardPublic', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('coffee-wizard-public-nonce'),
            'currency' => get_woocommerce_currency_symbol(),
            'i18n' => array(
                'selectProduct' => __('Please select a product', 'coffee-wizard'),
                'selectWeight' => __('Please select a weight option', 'coffee-wizard'),
                'selectGrinder' => __('Please select a grinder option', 'coffee-wizard'),
                'errorMessage' => __('An error occurred. Please try again.', 'coffee-wizard'),
                'total' => __('Total', 'coffee-wizard')
            )
        ));
    }

    public function get_subcategories() {
        check_ajax_referer('coffee-wizard-public-nonce', 'nonce');

        $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
        if (!$category_id) {
            wp_send_json_error(__('Invalid category ID', 'coffee-wizard'));
        }

        $items = array();
        
        // Get subcategories
        $subcategories = get_terms(array(
            'taxonomy' => 'product_cat',
            'parent' => $category_id,
            'hide_empty' => false
        ));

        if (!empty($subcategories) && !is_wp_error($subcategories)) {
            foreach ($subcategories as $category) {
                $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                $items[] = array(
                    'id' => $category->term_id,
                    'name' => $category->name,
                    'type' => 'category',
                    'image' => wp_get_attachment_url($thumbnail_id)
                );
            }
        } else {
            // If no subcategories, get products from this category
            $products = wc_get_products(array(
                'category' => array($category_id),
                'status' => 'publish',
                'limit' => -1
            ));

            foreach ($products as $product) {
                $items[] = array(
                    'id' => $product->get_id(),
                    'name' => $product->get_name(),
                    'type' => 'product',
                    'price' => $product->get_price(),
                    'formatted_price' => $product->get_price_html(),
                    'image' => wp_get_attachment_url($product->get_image_id())
                );
            }
        }

        wp_send_json_success(array('items' => $items));
    }

    public function submit_coffee_order() {
        check_ajax_referer('coffee-wizard-public-nonce', 'nonce');

        $order = isset($_POST['order']) ? $_POST['order'] : array();
        
        if (empty($order['product_id'])) {
            wp_send_json_error(array('message' => __('Invalid product selection', 'coffee-wizard')));
        }

        try {
            // Get the product
            $product = wc_get_product($order['product_id']);
            if (!$product) {
                throw new \Exception(__('Product not found', 'coffee-wizard'));
            }

            // Calculate price based on weight coefficient
            $weights = get_option('coffee_wizard_weights', array());
            $weight_data = array_filter($weights, function($w) use ($order) {
                return $w['weight'] == $order['weight'];
            });

            if (empty($weight_data)) {
                throw new \Exception(__('Invalid weight selection', 'coffee-wizard'));
            }

            $weight_data = reset($weight_data);
            $price = $product->get_price() * $weight_data['coefficient'];

            // Add grinder price if selected
            if ($order['grinding'] === 'yes' && !empty($order['grinder'])) {
                $price += floatval($order['grinder']['price']);
            }

            // Create cart item data
            $cart_item_data = array(
                'coffee_wizard_data' => array(
                    'weight' => $order['weight'],
                    'grinding' => $order['grinding'],
                    'grinder' => $order['grinding'] === 'yes' ? $order['grinder']['name'] : null,
                    'notes' => sanitize_textarea_field($order['notes'])
                )
            );

            // Add to cart
            WC()->cart->add_to_cart(
                $product->get_id(),
                1,
                0,
                array(),
                $cart_item_data
            );

            wp_send_json_success(array(
                'message' => __('Product added to cart', 'coffee-wizard'),
                'cart_url' => wc_get_cart_url()
            ));

        } catch (\Exception $e) {
            wp_send_json_error(array('message' => $e->getMessage()));
        }
    }
} 