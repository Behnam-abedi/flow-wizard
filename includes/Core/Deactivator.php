<?php
namespace CoffeeWizard\Core;

class Deactivator {
    public static function deactivate() {
        // Clean up plugin options if needed
        // Note: We're not deleting options by default to preserve user settings
        // Uncomment the following lines if you want to delete options on deactivation
        /*
        delete_option('coffee_wizard_categories');
        delete_option('coffee_wizard_weights');
        delete_option('coffee_wizard_grinders');
        */

        // Flush rewrite rules
        flush_rewrite_rules();
    }
} 