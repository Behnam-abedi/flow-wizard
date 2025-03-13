<?php
/**
 * Plugin Name: Coffee Wizard Form
 * Description: A multi-step wizard form for coffee product selection
 * Version: 1.0.0
 * Author: Flow Coffee
 * Text Domain: coffee-wizard-form
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('COFFEE_WIZARD_PATH', plugin_dir_path(__FILE__));
define('COFFEE_WIZARD_URL', plugin_dir_url(__FILE__));
define('COFFEE_WIZARD_VERSION', '1.0.0');

// Include required files
require_once COFFEE_WIZARD_PATH . 'includes/class-coffee-wizard.php';
require_once COFFEE_WIZARD_PATH . 'includes/class-coffee-wizard-admin.php';
require_once COFFEE_WIZARD_PATH . 'includes/class-coffee-wizard-public.php';

// Initialize the plugin
function coffee_wizard_init() {
    $coffee_wizard = new Coffee_Wizard();
    $coffee_wizard->init();
}
add_action('plugins_loaded', 'coffee_wizard_init');

// Register activation hook
register_activation_hook(__FILE__, 'coffee_wizard_activate');
function coffee_wizard_activate() {
    // Activation code here
}

// Register deactivation hook
register_deactivation_hook(__FILE__, 'coffee_wizard_deactivate');
function coffee_wizard_deactivate() {
    // Deactivation code here
} 