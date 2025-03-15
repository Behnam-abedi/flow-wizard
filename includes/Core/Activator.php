<?php
namespace CoffeeWizard\Core;

class Activator {
    public static function activate() {
        // Create default options if they don't exist
        if (!get_option('coffee_wizard_categories')) {
            update_option('coffee_wizard_categories', array());
        }

        if (!get_option('coffee_wizard_weights')) {
            update_option('coffee_wizard_weights', array(
                array(
                    'weight' => 250,
                    'coefficient' => 0.25
                ),
                array(
                    'weight' => 500,
                    'coefficient' => 0.5
                ),
                array(
                    'weight' => 1000,
                    'coefficient' => 1
                )
            ));
        }

        if (!get_option('coffee_wizard_grinders')) {
            update_option('coffee_wizard_grinders', array());
        }

        // Flush rewrite rules
        flush_rewrite_rules();
    }
} 