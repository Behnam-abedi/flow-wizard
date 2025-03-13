<?php
/**
 * Coffee Wizard Admin Class
 */
class Coffee_Wizard_Admin {
    /**
     * Initialize the admin functionality
     */
    public function init() {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
        
        // Add custom category fields
        add_action('product_cat_add_form_fields', array($this, 'add_category_icon_field'));
        add_action('product_cat_edit_form_fields', array($this, 'edit_category_icon_field'), 10, 2);
        add_action('created_product_cat', array($this, 'save_category_icon_field'));
        add_action('edited_product_cat', array($this, 'save_category_icon_field'));
        
        // Enqueue admin scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        
        // Add admin notices for settings
        add_action('admin_notices', array($this, 'admin_notices'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Coffee Wizard', 'coffee-wizard-form'),
            __('Coffee Wizard', 'coffee-wizard-form'),
            'manage_options',
            'coffee-wizard',
            array($this, 'render_admin_page'),
            'dashicons-coffee',
            30
        );
        
        add_submenu_page(
            'coffee-wizard',
            __('Weight Options', 'coffee-wizard-form'),
            __('Weight Options', 'coffee-wizard-form'),
            'manage_options',
            'coffee-wizard-weight',
            array($this, 'render_weight_options_page')
        );
        
        add_submenu_page(
            'coffee-wizard',
            __('Grinding Options', 'coffee-wizard-form'),
            __('Grinding Options', 'coffee-wizard-form'),
            'manage_options',
            'coffee-wizard-grinding',
            array($this, 'render_grinding_options_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        // Weight options for specialty
        register_setting(
            'coffee_wizard_weight_options_specialty',
            'coffee_wizard_weight_options_specialty',
            array($this, 'sanitize_weight_options')
        );
        
        // Weight options for blend
        register_setting(
            'coffee_wizard_weight_options_blend',
            'coffee_wizard_weight_options_blend',
            array($this, 'sanitize_weight_options')
        );
        
        // Grinding options
        register_setting(
            'coffee_wizard_grinding_options',
            'coffee_wizard_grinding_options',
            array($this, 'sanitize_grinding_options')
        );
        
        // Add redirect after settings save
        add_action('admin_init', array($this, 'settings_save_redirect'));
    }
    
    /**
     * Redirect after saving settings
     */
    public function settings_save_redirect() {
        // Check if we need to redirect
        if (isset($_POST['coffee_wizard_redirect']) && !empty($_POST['coffee_wizard_redirect'])) {
            // Make sure settings have been saved
            if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') {
                // Get the redirect URL
                $redirect_url = esc_url_raw($_POST['coffee_wizard_redirect']);
                
                // Add the settings-updated parameter
                $redirect_url = add_query_arg('settings-updated', 'true', $redirect_url);
                
                // Redirect
                wp_redirect($redirect_url);
                exit;
            }
        }
    }
    
    /**
     * Sanitize weight options
     *
     * @param array $options
     * @return array
     */
    public function sanitize_weight_options($options) {
        $sanitized_options = array();
        
        if (is_array($options)) {
            foreach ($options as $option) {
                if (isset($option['weight']) && isset($option['price_multiplier'])) {
                    $sanitized_options[] = array(
                        'weight' => sanitize_text_field($option['weight']),
                        'price_multiplier' => floatval($option['price_multiplier'])
                    );
                }
            }
        }
        
        return $sanitized_options;
    }
    
    /**
     * Sanitize grinding options
     *
     * @param array $options
     * @return array
     */
    public function sanitize_grinding_options($options) {
        $sanitized_options = array();
        
        if (is_array($options)) {
            foreach ($options as $option) {
                if (isset($option['name']) && isset($option['price'])) {
                    $sanitized_options[] = array(
                        'name' => sanitize_text_field($option['name']),
                        'price' => floatval($option['price'])
                    );
                }
            }
        }
        
        return $sanitized_options;
    }
    
    /**
     * Render admin page
     */
    public function render_admin_page() {
        include COFFEE_WIZARD_PATH . 'templates/admin/admin-page.php';
    }
    
    /**
     * Render weight options page
     */
    public function render_weight_options_page() {
        include COFFEE_WIZARD_PATH . 'templates/admin/weight-options.php';
    }
    
    /**
     * Render grinding options page
     */
    public function render_grinding_options_page() {
        include COFFEE_WIZARD_PATH . 'templates/admin/grinding-options.php';
    }
    
    /**
     * Add category icon field
     */
    public function add_category_icon_field() {
        ?>
        <div class="form-field">
            <label for="category_icon_class"><?php _e('Category Icon Class', 'coffee-wizard-form'); ?></label>
            <input type="text" name="category_icon_class" id="category_icon_class" value="">
            <p class="description"><?php _e('Enter the icon class for this category (e.g., fa fa-coffee)', 'coffee-wizard-form'); ?></p>
        </div>
        <?php
    }
    
    /**
     * Edit category icon field
     *
     * @param WP_Term $term
     */
    public function edit_category_icon_field($term) {
        $icon_class = get_term_meta($term->term_id, 'category_icon_class', true);
        ?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="category_icon_class"><?php _e('Category Icon Class', 'coffee-wizard-form'); ?></label>
            </th>
            <td>
                <input type="text" name="category_icon_class" id="category_icon_class" value="<?php echo esc_attr($icon_class); ?>">
                <p class="description"><?php _e('Enter the icon class for this category (e.g., fa fa-coffee)', 'coffee-wizard-form'); ?></p>
            </td>
        </tr>
        <?php
    }
    
    /**
     * Save category icon field
     *
     * @param int $term_id
     */
    public function save_category_icon_field($term_id) {
        if (isset($_POST['category_icon_class'])) {
            update_term_meta(
                $term_id,
                'category_icon_class',
                sanitize_text_field($_POST['category_icon_class'])
            );
        }
    }
    
    /**
     * Enqueue admin scripts and styles
     *
     * @param string $hook
     */
    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, 'coffee-wizard') !== false) {
            wp_enqueue_style(
                'coffee-wizard-admin',
                COFFEE_WIZARD_URL . 'assets/css/admin.css',
                array(),
                COFFEE_WIZARD_VERSION
            );
            
            wp_enqueue_script(
                'coffee-wizard-admin',
                COFFEE_WIZARD_URL . 'assets/js/admin.js',
                array('jquery', 'jquery-ui-sortable'),
                COFFEE_WIZARD_VERSION,
                true
            );
            
            wp_localize_script('coffee-wizard-admin', 'coffee_wizard_admin', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('coffee_wizard_admin_nonce'),
                'i18n' => array(
                    'at_least_one_option' => __('You must have at least one option.', 'coffee-wizard-form'),
                    'confirm_delete' => __('Are you sure you want to delete this option?', 'coffee-wizard-form'),
                    'changes_saved' => __('Changes saved successfully.', 'coffee-wizard-form'),
                    'error_saving' => __('Error saving changes.', 'coffee-wizard-form')
                )
            ));
        }
    }
    
    /**
     * Display admin notices
     */
    public function admin_notices() {
        // Check if we're on our settings page
        $screen = get_current_screen();
        if (!$screen || strpos($screen->id, 'coffee-wizard') === false) {
            return;
        }
        
        // Display settings updated message
        if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') {
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e('Settings saved successfully.', 'coffee-wizard-form'); ?></p>
            </div>
            <?php
        }
        
        // Display error message
        if (isset($_GET['error']) && !empty($_GET['error'])) {
            ?>
            <div class="notice notice-error is-dismissible">
                <p><?php echo esc_html($_GET['error']); ?></p>
            </div>
            <?php
        }
    }
} 