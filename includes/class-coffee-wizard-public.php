<?php
/**
 * Coffee Wizard Public Class
 */
class Coffee_Wizard_Public {
    /**
     * Initialize the public functionality
     */
    public function init() {
        // Enqueue public scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts'));
        
        // Add custom price calculation to cart
        add_filter('woocommerce_add_cart_item_data', array($this, 'add_custom_price_data'), 10, 3);
        add_filter('woocommerce_get_cart_item_from_session', array($this, 'get_cart_item_from_session'), 10, 2);
        add_filter('woocommerce_get_item_data', array($this, 'get_item_data'), 10, 2);
        add_action('woocommerce_before_calculate_totals', array($this, 'calculate_custom_price'));
    }
    
    /**
     * Enqueue public scripts and styles
     */
    public function enqueue_public_scripts() {
        // Only enqueue on pages with the shortcode
        global $post;
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'coffee_wizard_form')) {
            // Font Awesome for icons
            wp_enqueue_style(
                'font-awesome',
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
                array(),
                '6.0.0'
            );
            
            // Main CSS
            wp_enqueue_style(
                'coffee-wizard-public',
                COFFEE_WIZARD_URL . 'assets/css/public.css',
                array(),
                COFFEE_WIZARD_VERSION
            );
            
            // Main JS
            wp_enqueue_script(
                'coffee-wizard-public',
                COFFEE_WIZARD_URL . 'assets/js/public.js',
                array('jquery'),
                COFFEE_WIZARD_VERSION,
                true
            );
            
            // Localize script
            wp_localize_script('coffee-wizard-public', 'coffee_wizard', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('coffee_wizard_nonce'),
                'checkout_url' => wc_get_checkout_url(),
                'currency_symbol' => get_woocommerce_currency_symbol(),
                'i18n' => array(
                    'select_product' => __('Select Product', 'coffee-wizard-form'),
                    'select_weight' => __('Select Weight', 'coffee-wizard-form'),
                    'select_grinding' => __('Select Grinding Type', 'coffee-wizard-form'),
                    'order_notes' => __('Order Notes', 'coffee-wizard-form'),
                    'order_summary' => __('Order Summary', 'coffee-wizard-form'),
                    'next_step' => __('Next Step', 'coffee-wizard-form'),
                    'prev_step' => __('Previous Step', 'coffee-wizard-form'),
                    'add_to_cart' => __('Add to Cart', 'coffee-wizard-form'),
                    'another_order' => __('Do you have another order?', 'coffee-wizard-form'),
                    'yes' => __('Yes', 'coffee-wizard-form'),
                    'no' => __('No', 'coffee-wizard-form'),
                    'grind_product' => __('Grind the product', 'coffee-wizard-form'),
                    'no_grinding' => __('No grinding', 'coffee-wizard-form')
                )
            ));
        }
    }
    
    /**
     * Add custom price data to cart item
     *
     * @param array $cart_item_data
     * @param int $product_id
     * @param int $variation_id
     * @return array
     */
    public function add_custom_price_data($cart_item_data, $product_id, $variation_id) {
        if (isset($_POST['coffee_wizard_weight']) && isset($_POST['coffee_wizard_grinding_option'])) {
            $product = wc_get_product($product_id);
            $base_price = $product->get_price();
            $weight = sanitize_text_field($_POST['coffee_wizard_weight']);
            $grinding_option = sanitize_text_field($_POST['coffee_wizard_grinding_option']);
            $grinding_machine = isset($_POST['coffee_wizard_grinding_machine']) ? sanitize_text_field($_POST['coffee_wizard_grinding_machine']) : '';
            
            // Get category
            $category_terms = wp_get_post_terms($product_id, 'product_cat');
            $category_slug = '';
            
            foreach ($category_terms as $term) {
                if ($term->parent == 150) { // Quick Order parent
                    $category_slug = $term->slug;
                    break;
                }
            }
            
            // Calculate weight price
            $weight_options = get_option('coffee_wizard_weight_options_' . $category_slug, array());
            $weight_multiplier = 1;
            
            foreach ($weight_options as $option) {
                if ($option['weight'] === $weight) {
                    $weight_multiplier = $option['price_multiplier'];
                    break;
                }
            }
            
            $price_after_weight = $base_price * $weight_multiplier;
            
            // Calculate grinding price
            $grinding_price = 0;
            
            if ($grinding_option === 'grind') {
                $grinding_options = get_option('coffee_wizard_grinding_options', array());
                
                foreach ($grinding_options as $option) {
                    if ($option['name'] === $grinding_machine) {
                        $grinding_price = $option['price'];
                        break;
                    }
                }
            }
            
            $final_price = $price_after_weight + $grinding_price;
            
            // Add custom data
            $cart_item_data['coffee_wizard_custom_data'] = array(
                'weight' => $weight,
                'grinding_option' => $grinding_option,
                'grinding_machine' => $grinding_machine,
                'notes' => isset($_POST['coffee_wizard_notes']) ? sanitize_textarea_field($_POST['coffee_wizard_notes']) : '',
                'base_price' => $base_price,
                'weight_multiplier' => $weight_multiplier,
                'grinding_price' => $grinding_price,
                'final_price' => $final_price
            );
            
            // Make each item unique
            $cart_item_data['unique_key'] = md5(microtime() . rand());
        }
        
        return $cart_item_data;
    }
    
    /**
     * Get cart item from session
     *
     * @param array $cart_item
     * @param array $values
     * @return array
     */
    public function get_cart_item_from_session($cart_item, $values) {
        if (isset($values['coffee_wizard_custom_data'])) {
            $cart_item['coffee_wizard_custom_data'] = $values['coffee_wizard_custom_data'];
        }
        
        return $cart_item;
    }
    
    /**
     * Add custom data to cart item display
     *
     * @param array $item_data
     * @param array $cart_item
     * @return array
     */
    public function get_item_data($item_data, $cart_item) {
        if (isset($cart_item['coffee_wizard_custom_data'])) {
            $custom_data = $cart_item['coffee_wizard_custom_data'];
            
            $item_data[] = array(
                'key' => __('Weight', 'coffee-wizard-form'),
                'value' => $custom_data['weight']
            );
            
            if ($custom_data['grinding_option'] === 'grind') {
                $item_data[] = array(
                    'key' => __('Grinding', 'coffee-wizard-form'),
                    'value' => $custom_data['grinding_machine']
                );
            } else {
                $item_data[] = array(
                    'key' => __('Grinding', 'coffee-wizard-form'),
                    'value' => __('No grinding', 'coffee-wizard-form')
                );
            }
            
            if (!empty($custom_data['notes'])) {
                $item_data[] = array(
                    'key' => __('Notes', 'coffee-wizard-form'),
                    'value' => $custom_data['notes']
                );
            }
        }
        
        return $item_data;
    }
    
    /**
     * Calculate custom price
     *
     * @param WC_Cart $cart
     */
    public function calculate_custom_price($cart) {
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }
        
        if (did_action('woocommerce_before_calculate_totals') >= 2) {
            return;
        }
        
        foreach ($cart->get_cart() as $cart_item) {
            if (isset($cart_item['coffee_wizard_custom_data'])) {
                $custom_data = $cart_item['coffee_wizard_custom_data'];
                $cart_item['data']->set_price($custom_data['final_price']);
            }
        }
    }
} 