# Coffee Wizard Form

A WordPress plugin that adds a wizard-style form for coffee product ordering with advanced options for weights and grinding.

## Description

Coffee Wizard Form is a WooCommerce extension that provides a user-friendly, step-by-step wizard interface for customers to order coffee products. The plugin allows shop administrators to configure product categories, weight options with price coefficients, and grinding options.

### Features

- Four-step wizard interface
- Category and subcategory navigation with breadcrumb
- Weight selection with automatic price calculation
- Optional grinding service with customizable grinder options
- Responsive design with mobile support
- RTL language support
- Dark mode support
- Accessibility compliant

## Requirements

- WordPress 5.0 or higher
- WooCommerce 3.0 or higher
- PHP 7.2 or higher

## Installation

1. Download the plugin zip file
2. Go to WordPress admin panel > Plugins > Add New
3. Click "Upload Plugin" and select the downloaded zip file
4. Click "Install Now"
5. After installation, click "Activate"

## Usage

### Admin Configuration

1. Go to WordPress admin panel > Coffee Wizard
2. Configure the following settings:

#### Categories
- Select two main product categories for the wizard form
- These categories will be displayed as the first options in the wizard

#### Weights & Coefficients
- Add different weight options (e.g., 250g, 500g, 1kg)
- Set price coefficients for each weight
- Example: 0.25 coefficient for 250g means the price will be 25% of the full product price

#### Grinder Options
- Add different grinder options
- Set grinding service prices for each option

### Frontend Usage

Add the wizard form to any page or post using the shortcode:

```
[coffee_wizard]
```

The wizard will guide customers through four steps:

1. **Coffee Selection**
   - Choose from main categories
   - Navigate through subcategories
   - Select the desired product

2. **Weight Selection**
   - Choose from available weight options
   - See adjusted prices based on weight coefficients

3. **Grinding Options**
   - Choose whether to grind the coffee
   - If yes, select from available grinder options

4. **Order Details**
   - Add special instructions
   - Review order summary
   - Add to cart

## Customization

### CSS Customization

The plugin includes two CSS files that can be customized:

- `assets/css/public.css` - Frontend styles
- `assets/css/admin.css` - Admin panel styles

### Filters and Actions

The plugin provides several filters and actions for developers to extend its functionality:

```php
// Modify weight coefficients
add_filter('coffee_wizard_weight_coefficient', function($coefficient, $weight) {
    // Your custom logic
    return $coefficient;
}, 10, 2);

// Modify grinder prices
add_filter('coffee_wizard_grinder_price', function($price, $grinder) {
    // Your custom logic
    return $price;
}, 10, 2);

// Action before adding to cart
add_action('coffee_wizard_before_add_to_cart', function($product_id, $order_data) {
    // Your custom logic
}, 10, 2);
```

## Support

For support, feature requests, or bug reports, please visit our [GitHub repository](https://github.com/your-username/coffee-wizard-form) or contact our support team.

## License

This plugin is licensed under the GPL v2 or later.

```
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details. 