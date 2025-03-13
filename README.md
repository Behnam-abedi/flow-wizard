# Coffee Wizard Form

A multi-step wizard form for coffee product selection in WordPress/WooCommerce.

## Description

Coffee Wizard Form is a WordPress plugin that creates a beautiful, multi-step wizard form for coffee product selection. It allows customers to:

1. Select a coffee product category and subcategory
2. Choose a specific coffee product
3. Select a weight option
4. Choose a grinding type and machine (optional)
5. Add order notes
6. Review their order before adding to cart

The plugin integrates with WooCommerce and allows for custom pricing based on weight and grinding options.

## Installation

1. Upload the `coffee-wizard-form` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the weight and grinding options in the Coffee Wizard admin menu
4. Add the shortcode `[coffee_wizard_form]` to any page where you want to display the wizard form

## Requirements

- WordPress 5.0 or higher
- WooCommerce 4.0 or higher
- A theme that supports WooCommerce

## Configuration

### Category Structure

The plugin requires the following category structure in WooCommerce:

- Quick Order (ID: 150)
  - Blend (ID: 129, Slug: blend)
  - Specialty (ID: 130, Slug: specialty)

You can create these categories in the WooCommerce > Products > Categories section of your WordPress admin.

### Weight Options

Configure weight options for each coffee category (Specialty and Blend) in the Coffee Wizard > Weight Options page. For each weight option, you can set:

- Weight (e.g., 100g, 250g, 500g)
- Price Multiplier (e.g., 1.0, 2.5, 4.0)

The price multiplier is used to calculate the final price based on the base price of the product.

### Grinding Options

Configure grinding machine options in the Coffee Wizard > Grinding Options page. For each grinding machine, you can set:

- Machine Name (e.g., French Press, Espresso, Pour Over)
- Price (additional cost for grinding)

### Category Icons

You can add icon classes to your product categories by editing the category and entering a Font Awesome icon class in the "Category Icon Class" field.

## Usage

1. Add the shortcode `[coffee_wizard_form]` to any page where you want to display the wizard form.
2. Customers will be guided through the multi-step process to select their coffee products.
3. When a customer completes the form, the product will be added to their cart with the selected options.
4. Customers can choose to add another product or proceed to checkout.

## Customization

You can customize the appearance of the wizard form by modifying the CSS in the `assets/css/public.css` file.

## Troubleshooting

If you encounter any issues with the plugin, please check the following:

1. Make sure you have the correct category structure set up in WooCommerce.
2. Verify that you have configured weight options for both Specialty and Blend categories.
3. Check that your products are assigned to the correct categories.
4. Ensure that WooCommerce is properly configured and working.

## License

This plugin is licensed under the GPL v2 or later.

## Credits

- Font Awesome for the icons
- jQuery for the JavaScript functionality 