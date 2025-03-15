<?php
/**
 * Plugin Name: Coffee Wizard Form
 * Plugin URI: 
 * Description: A wizard form plugin for coffee ordering with product categories, weights, and grinder options
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: coffee-wizard
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('COFFEE_WIZARD_VERSION', '1.0.0');
define('COFFEE_WIZARD_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('COFFEE_WIZARD_PLUGIN_URL', plugin_dir_url(__FILE__));

// Autoloader for classes
spl_autoload_register(function ($class) {
    $prefix = 'CoffeeWizard\\';
    $base_dir = COFFEE_WIZARD_PLUGIN_DIR . 'includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Initialize the plugin
function coffee_wizard_init() {
    if (class_exists('CoffeeWizard\\Core\\Plugin')) {
        $plugin = new CoffeeWizard\Core\Plugin();
        $plugin->init();
    }
}
add_action('plugins_loaded', 'coffee_wizard_init');

// Activation hook
register_activation_hook(__FILE__, function() {
    require_once COFFEE_WIZARD_PLUGIN_DIR . 'includes/Core/Activator.php';
    CoffeeWizard\Core\Activator::activate();
});

// Deactivation hook
register_deactivation_hook(__FILE__, function() {
    require_once COFFEE_WIZARD_PLUGIN_DIR . 'includes/Core/Deactivator.php';
    CoffeeWizard\Core\Deactivator::deactivate();
}); 