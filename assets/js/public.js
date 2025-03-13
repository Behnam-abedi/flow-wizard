jQuery(document).ready(function($) {
    // Wizard state
    var wizardState = {
        currentStep: 1,
        selectedProduct: null,
        selectedWeight: null,
        selectedGrindingOption: 'no-grinding',
        selectedGrindingMachine: null,
        notes: '',
        breadcrumbs: [
            { id: 150, name: coffee_wizard.i18n.quick_order || 'Quick Order' }
        ]
    };
    
    // Initialize the wizard
    initWizard();
    
    /**
     * Initialize the wizard
     */
    function initWizard() {
        // Load initial categories
        loadCategories(150);
        
        // Next step button click
        $('.next-step-button').on('click', function() {
            if ($(this).prop('disabled')) {
                return;
            }
            
            goToStep(wizardState.currentStep + 1);
        });
        
        // Previous step button click
        $('.prev-step-button').on('click', function() {
            if ($(this).prop('disabled')) {
                return;
            }
            
            goToStep(wizardState.currentStep - 1);
        });
        
        // Category click
        $(document).on('click', '.coffee-wizard-category', function() {
            var categoryId = $(this).data('id');
            var categoryName = $(this).data('name');
            var hasChildren = $(this).data('has-children');
            
            // Update breadcrumbs
            wizardState.breadcrumbs.push({ id: categoryId, name: categoryName });
            updateBreadcrumbs();
            
            if (hasChildren) {
                // Load subcategories
                loadCategories(categoryId);
            } else {
                // Load products
                loadProducts(categoryId);
            }
        });
        
        // Breadcrumb click
        $(document).on('click', '.coffee-wizard-breadcrumbs li', function() {
            var categoryId = $(this).data('id');
            var index = wizardState.breadcrumbs.findIndex(function(item) {
                return item.id === categoryId;
            });
            
            if (index !== -1) {
                // Truncate breadcrumbs
                wizardState.breadcrumbs = wizardState.breadcrumbs.slice(0, index + 1);
                updateBreadcrumbs();
                
                // Load categories or products
                loadCategories(categoryId);
            }
        });
        
        // Product click
        $(document).on('click', '.coffee-wizard-product', function() {
            var productId = $(this).data('id');
            var productName = $(this).data('name');
            var productPrice = $(this).data('price');
            var productImage = $(this).data('image');
            var productDescription = $(this).data('description');
            
            // Deselect all products
            $('.coffee-wizard-product').removeClass('selected');
            
            // Select this product
            $(this).addClass('selected');
            
            // Update wizard state
            wizardState.selectedProduct = {
                id: productId,
                name: productName,
                price: productPrice,
                image: productImage,
                description: productDescription
            };
            
            // Enable next button
            $('.next-step-button').prop('disabled', false);
        });
        
        // Weight option click
        $(document).on('click', '.coffee-wizard-weight-option', function() {
            var weight = $(this).data('weight');
            var priceMultiplier = $(this).data('price-multiplier');
            
            // Deselect all weight options
            $('.coffee-wizard-weight-option').removeClass('selected');
            
            // Select this weight option
            $(this).addClass('selected');
            
            // Update wizard state
            wizardState.selectedWeight = {
                weight: weight,
                priceMultiplier: priceMultiplier
            };
            
            // Update product price
            updateProductPrice();
            
            // Enable next button
            $('.next-step-button').prop('disabled', false);
        });
        
        // Grinding option change
        $('input[name="grinding_option"]').on('change', function() {
            var option = $(this).val();
            
            // Update wizard state
            wizardState.selectedGrindingOption = option;
            
            // Show/hide grinding machines
            if (option === 'grind') {
                $('.coffee-wizard-grinding-machines').show();
                loadGrindingOptions();
            } else {
                $('.coffee-wizard-grinding-machines').hide();
                wizardState.selectedGrindingMachine = null;
                updateProductPrice();
                
                // Enable next button
                $('.next-step-button').prop('disabled', false);
            }
        });
        
        // Grinding machine click
        $(document).on('click', '.coffee-wizard-grinding-machine', function() {
            var machineName = $(this).data('name');
            var machinePrice = $(this).data('price');
            
            // Deselect all grinding machines
            $('.coffee-wizard-grinding-machine').removeClass('selected');
            
            // Select this grinding machine
            $(this).addClass('selected');
            
            // Update wizard state
            wizardState.selectedGrindingMachine = {
                name: machineName,
                price: machinePrice
            };
            
            // Update product price
            updateProductPrice();
            
            // Enable next button
            $('.next-step-button').prop('disabled', false);
        });
        
        // Order notes change
        $('#order-notes').on('input', function() {
            wizardState.notes = $(this).val();
            
            // Always enable next button for notes step
            $('.next-step-button').prop('disabled', false);
        });
        
        // Add to cart button click
        $('.add-to-cart-button').on('click', function() {
            addToCart();
        });
        
        // Another order yes button click
        $('.another-order-yes').on('click', function() {
            // Hide modal
            $('#another-order-modal').hide();
            
            // Reset wizard
            resetWizard();
        });
        
        // Another order no button click
        $('.another-order-no').on('click', function() {
            // Hide modal
            $('#another-order-modal').hide();
            
            // Redirect to checkout
            window.location.href = coffee_wizard.checkout_url;
        });
    }
    
    /**
     * Go to a specific step
     *
     * @param {number} step
     */
    function goToStep(step) {
        // Validate step
        if (step < 1 || step > 5) {
            return;
        }
        
        // Update current step
        wizardState.currentStep = step;
        
        // Update step UI
        $('.coffee-wizard-step').removeClass('active');
        $('.coffee-wizard-step[data-step="' + step + '"]').addClass('active');
        
        $('.coffee-wizard-step-content').removeClass('active');
        $('.coffee-wizard-step-content[data-step="' + step + '"]').addClass('active');
        
        // Update navigation buttons
        $('.prev-step-button').prop('disabled', step === 1);
        
        // Handle step-specific logic
        switch (step) {
            case 1:
                // Product selection step
                $('.next-step-button').prop('disabled', !wizardState.selectedProduct);
                break;
                
            case 2:
                // Weight selection step
                $('.next-step-button').prop('disabled', !wizardState.selectedWeight);
                
                // Load weight options
                loadWeightOptions();
                
                // Update product info
                updateProductInfo();
                break;
                
            case 3:
                // Grinding type step
                if (wizardState.selectedGrindingOption === 'grind') {
                    $('.next-step-button').prop('disabled', !wizardState.selectedGrindingMachine);
                } else {
                    $('.next-step-button').prop('disabled', false);
                }
                
                // Update product summary
                updateProductSummary();
                break;
                
            case 4:
                // Order notes step
                $('.next-step-button').prop('disabled', false);
                
                // Update product summary
                updateProductSummary();
                break;
                
            case 5:
                // Summary step
                $('.next-step-button').prop('disabled', true);
                
                // Update order summary
                updateOrderSummary();
                break;
        }
    }
    
    /**
     * Load categories via AJAX
     *
     * @param {number} parentId
     */
    function loadCategories(parentId) {
        $.ajax({
            url: coffee_wizard.ajax_url,
            type: 'POST',
            data: {
                action: 'get_product_categories',
                parent_id: parentId,
                nonce: coffee_wizard.nonce
            },
            beforeSend: function() {
                $('.coffee-wizard-categories').html('<div class="coffee-wizard-loading"><i class="fas fa-spinner fa-spin"></i><span>' + (coffee_wizard.i18n.loading_categories || 'Loading categories...') + '</span></div>');
                $('.coffee-wizard-products').hide();
            },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    var categoriesHtml = '';
                    
                    $.each(response.data, function(index, category) {
                        categoriesHtml += '<div class="coffee-wizard-category" data-id="' + category.id + '" data-name="' + category.name + '" data-has-children="' + (category.has_children ? 'true' : 'false') + '">';
                        
                        if (category.icon_class) {
                            categoriesHtml += '<div class="category-icon"><i class="' + category.icon_class + '"></i></div>';
                        } else if (category.image) {
                            categoriesHtml += '<div class="category-image"><img src="' + category.image + '" alt="' + category.name + '"></div>';
                        }
                        
                        categoriesHtml += '<div class="category-name">' + category.name + '</div>';
                        categoriesHtml += '</div>';
                    });
                    
                    $('.coffee-wizard-categories').html(categoriesHtml);
                    $('.coffee-wizard-categories').show();
                    $('.coffee-wizard-products').hide();
                } else {
                    $('.coffee-wizard-categories').html('<div class="coffee-wizard-empty">' + (coffee_wizard.i18n.no_categories || 'No categories found.') + '</div>');
                }
            },
            error: function() {
                $('.coffee-wizard-categories').html('<div class="coffee-wizard-error">' + (coffee_wizard.i18n.error_loading_categories || 'Error loading categories.') + '</div>');
            }
        });
    }
    
    /**
     * Load products via AJAX
     *
     * @param {number} categoryId
     */
    function loadProducts(categoryId) {
        $.ajax({
            url: coffee_wizard.ajax_url,
            type: 'POST',
            data: {
                action: 'get_products_by_category',
                category_id: categoryId,
                nonce: coffee_wizard.nonce
            },
            beforeSend: function() {
                $('.coffee-wizard-products').html('<div class="coffee-wizard-loading"><i class="fas fa-spinner fa-spin"></i><span>' + (coffee_wizard.i18n.loading_products || 'Loading products...') + '</span></div>');
                $('.coffee-wizard-categories').hide();
                $('.coffee-wizard-products').show();
            },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    var productsHtml = '';
                    
                    $.each(response.data, function(index, product) {
                        productsHtml += '<div class="coffee-wizard-product" data-id="' + product.id + '" data-name="' + product.name + '" data-price="' + product.price + '" data-image="' + product.image + '" data-description="' + product.description + '">';
                        
                        if (product.image) {
                            productsHtml += '<div class="product-image"><img src="' + product.image + '" alt="' + product.name + '"></div>';
                        }
                        
                        productsHtml += '<div class="product-details">';
                        productsHtml += '<div class="product-name">' + product.name + '</div>';
                        productsHtml += '<div class="product-price">' + product.formatted_price + '</div>';
                        productsHtml += '</div>';
                        productsHtml += '</div>';
                    });
                    
                    $('.coffee-wizard-products').html(productsHtml);
                    $('.coffee-wizard-products').show();
                    $('.coffee-wizard-categories').hide();
                } else {
                    $('.coffee-wizard-products').html('<div class="coffee-wizard-empty">' + (coffee_wizard.i18n.no_products || 'No products found.') + '</div>');
                }
            },
            error: function() {
                $('.coffee-wizard-products').html('<div class="coffee-wizard-error">' + (coffee_wizard.i18n.error_loading_products || 'Error loading products.') + '</div>');
            }
        });
    }
    
    /**
     * Load weight options via AJAX
     */
    function loadWeightOptions() {
        // Get category slug from breadcrumbs
        var categorySlug = '';
        
        if (wizardState.breadcrumbs.length >= 2) {
            var categoryId = wizardState.breadcrumbs[1].id;
            
            if (categoryId === 129) {
                categorySlug = 'blend';
            } else if (categoryId === 130) {
                categorySlug = 'specialty';
            }
        }
        
        if (!categorySlug) {
            $('.coffee-wizard-weight-options').html('<div class="coffee-wizard-error">' + (coffee_wizard.i18n.invalid_category || 'Invalid category.') + '</div>');
            return;
        }
        
        $.ajax({
            url: coffee_wizard.ajax_url,
            type: 'POST',
            data: {
                action: 'get_weight_options',
                category_slug: categorySlug,
                nonce: coffee_wizard.nonce
            },
            beforeSend: function() {
                $('.coffee-wizard-weight-options').html('<div class="coffee-wizard-loading"><i class="fas fa-spinner fa-spin"></i><span>' + (coffee_wizard.i18n.loading_weight_options || 'Loading weight options...') + '</span></div>');
            },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    var optionsHtml = '';
                    
                    $.each(response.data, function(index, option) {
                        var selected = wizardState.selectedWeight && wizardState.selectedWeight.weight === option.weight ? ' selected' : '';
                        
                        optionsHtml += '<div class="coffee-wizard-weight-option' + selected + '" data-weight="' + option.weight + '" data-price-multiplier="' + option.price_multiplier + '">';
                        optionsHtml += '<div class="weight-option-value">' + option.weight + '</div>';
                        optionsHtml += '</div>';
                    });
                    
                    $('.coffee-wizard-weight-options').html(optionsHtml);
                } else {
                    $('.coffee-wizard-weight-options').html('<div class="coffee-wizard-empty">' + (coffee_wizard.i18n.no_weight_options || 'No weight options found.') + '</div>');
                }
            },
            error: function() {
                $('.coffee-wizard-weight-options').html('<div class="coffee-wizard-error">' + (coffee_wizard.i18n.error_loading_weight_options || 'Error loading weight options.') + '</div>');
            }
        });
    }
    
    /**
     * Load grinding options via AJAX
     */
    function loadGrindingOptions() {
        $.ajax({
            url: coffee_wizard.ajax_url,
            type: 'POST',
            data: {
                action: 'get_grinding_options',
                nonce: coffee_wizard.nonce
            },
            beforeSend: function() {
                $('.coffee-wizard-grinding-machines').html('<div class="coffee-wizard-loading"><i class="fas fa-spinner fa-spin"></i><span>' + (coffee_wizard.i18n.loading_grinding_options || 'Loading grinding options...') + '</span></div>');
            },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    var optionsHtml = '';
                    
                    $.each(response.data, function(index, option) {
                        var selected = wizardState.selectedGrindingMachine && wizardState.selectedGrindingMachine.name === option.name ? ' selected' : '';
                        
                        optionsHtml += '<div class="coffee-wizard-grinding-machine' + selected + '" data-name="' + option.name + '" data-price="' + option.price + '">';
                        optionsHtml += '<div class="grinding-machine-name">' + option.name + '</div>';
                        optionsHtml += '<div class="grinding-machine-price">' + formatPrice(option.price) + '</div>';
                        optionsHtml += '</div>';
                    });
                    
                    $('.coffee-wizard-grinding-machines').html(optionsHtml);
                } else {
                    $('.coffee-wizard-grinding-machines').html('<div class="coffee-wizard-empty">' + (coffee_wizard.i18n.no_grinding_options || 'No grinding options found.') + '</div>');
                }
            },
            error: function() {
                $('.coffee-wizard-grinding-machines').html('<div class="coffee-wizard-error">' + (coffee_wizard.i18n.error_loading_grinding_options || 'Error loading grinding options.') + '</div>');
            }
        });
    }
    
    /**
     * Update breadcrumbs
     */
    function updateBreadcrumbs() {
        var breadcrumbsHtml = '';
        
        $.each(wizardState.breadcrumbs, function(index, item) {
            var activeClass = index === wizardState.breadcrumbs.length - 1 ? ' class="active"' : '';
            breadcrumbsHtml += '<li data-id="' + item.id + '"' + activeClass + '>' + item.name + '</li>';
        });
        
        $('.coffee-wizard-breadcrumbs ul').html(breadcrumbsHtml);
    }
    
    /**
     * Update product info in step 2
     */
    function updateProductInfo() {
        if (wizardState.selectedProduct) {
            var product = wizardState.selectedProduct;
            
            // Update product image
            if (product.image) {
                $('.coffee-wizard-product-info .product-image').html('<img src="' + product.image + '" alt="' + product.name + '">');
            } else {
                $('.coffee-wizard-product-info .product-image').html('');
            }
            
            // Update product details
            $('.coffee-wizard-product-info .product-name').text(product.name);
            $('.coffee-wizard-product-info .product-price').html(formatPrice(product.price));
            $('.coffee-wizard-product-info .product-description').html(product.description);
        }
    }
    
    /**
     * Update product price based on selected options
     */
    function updateProductPrice() {
        if (wizardState.selectedProduct && wizardState.selectedWeight) {
            var basePrice = wizardState.selectedProduct.price;
            var weightMultiplier = wizardState.selectedWeight.priceMultiplier;
            var grindingPrice = 0;
            
            if (wizardState.selectedGrindingOption === 'grind' && wizardState.selectedGrindingMachine) {
                grindingPrice = wizardState.selectedGrindingMachine.price;
            }
            
            var finalPrice = (basePrice * weightMultiplier) + grindingPrice;
            
            // Update price in product summary
            $('.coffee-wizard-product-summary .product-price').html(formatPrice(finalPrice));
        }
    }
    
    /**
     * Update product summary in steps 3 and 4
     */
    function updateProductSummary() {
        if (wizardState.selectedProduct && wizardState.selectedWeight) {
            // Update product name
            $('.coffee-wizard-product-summary .product-name').text(wizardState.selectedProduct.name);
            
            // Update product weight
            $('.coffee-wizard-product-summary .product-weight').text(wizardState.selectedWeight.weight);
            
            // Update product grinding
            if (wizardState.selectedGrindingOption === 'grind' && wizardState.selectedGrindingMachine) {
                $('.coffee-wizard-product-summary .product-grinding').text(wizardState.selectedGrindingMachine.name);
            } else {
                $('.coffee-wizard-product-summary .product-grinding').text(coffee_wizard.i18n.no_grinding || 'No grinding');
            }
            
            // Update product price
            updateProductPrice();
        }
    }
    
    /**
     * Update order summary in step 5
     */
    function updateOrderSummary() {
        if (wizardState.selectedProduct && wizardState.selectedWeight) {
            // Update product name
            $('.coffee-wizard-order-summary .product-name').text(wizardState.selectedProduct.name);
            
            // Update product weight
            $('.coffee-wizard-order-summary .product-weight').text(wizardState.selectedWeight.weight);
            
            // Update product grinding
            if (wizardState.selectedGrindingOption === 'grind' && wizardState.selectedGrindingMachine) {
                $('.coffee-wizard-order-summary .product-grinding').text(wizardState.selectedGrindingMachine.name);
            } else {
                $('.coffee-wizard-order-summary .product-grinding').text(coffee_wizard.i18n.no_grinding || 'No grinding');
            }
            
            // Update product notes
            if (wizardState.notes) {
                $('.coffee-wizard-order-summary .product-notes').text(wizardState.notes);
                $('.coffee-wizard-order-summary .notes-item').show();
            } else {
                $('.coffee-wizard-order-summary .notes-item').hide();
            }
            
            // Update product price
            updateProductPrice();
        }
    }
    
    /**
     * Add to cart via AJAX
     */
    function addToCart() {
        if (!wizardState.selectedProduct || !wizardState.selectedWeight) {
            return;
        }
        
        var data = {
            action: 'add_to_cart',
            product_id: wizardState.selectedProduct.id,
            coffee_wizard_weight: wizardState.selectedWeight.weight,
            coffee_wizard_grinding_option: wizardState.selectedGrindingOption,
            coffee_wizard_notes: wizardState.notes,
            nonce: coffee_wizard.nonce
        };
        
        if (wizardState.selectedGrindingOption === 'grind' && wizardState.selectedGrindingMachine) {
            data.coffee_wizard_grinding_machine = wizardState.selectedGrindingMachine.name;
        }
        
        $.ajax({
            url: coffee_wizard.ajax_url,
            type: 'POST',
            data: data,
            beforeSend: function() {
                $('.add-to-cart-button').prop('disabled', true).text(coffee_wizard.i18n.adding_to_cart || 'Adding to cart...');
            },
            success: function(response) {
                if (response.success) {
                    // Show another order modal
                    $('#another-order-modal').show();
                } else {
                    alert(response.data || (coffee_wizard.i18n.error_adding_to_cart || 'Error adding to cart.'));
                    $('.add-to-cart-button').prop('disabled', false).text(coffee_wizard.i18n.add_to_cart || 'Add to Cart');
                }
            },
            error: function() {
                alert(coffee_wizard.i18n.error_adding_to_cart || 'Error adding to cart.');
                $('.add-to-cart-button').prop('disabled', false).text(coffee_wizard.i18n.add_to_cart || 'Add to Cart');
            }
        });
    }
    
    /**
     * Reset wizard to initial state
     */
    function resetWizard() {
        // Reset wizard state
        wizardState = {
            currentStep: 1,
            selectedProduct: null,
            selectedWeight: null,
            selectedGrindingOption: 'no-grinding',
            selectedGrindingMachine: null,
            notes: '',
            breadcrumbs: [
                { id: 150, name: coffee_wizard.i18n.quick_order || 'Quick Order' }
            ]
        };
        
        // Reset UI
        $('.coffee-wizard-step').removeClass('active');
        $('.coffee-wizard-step[data-step="1"]').addClass('active');
        
        $('.coffee-wizard-step-content').removeClass('active');
        $('.coffee-wizard-step-content[data-step="1"]').addClass('active');
        
        $('.prev-step-button').prop('disabled', true);
        $('.next-step-button').prop('disabled', true);
        
        // Reset form elements
        $('input[name="grinding_option"][value="no-grinding"]').prop('checked', true);
        $('.coffee-wizard-grinding-machines').hide();
        $('#order-notes').val('');
        
        // Load initial categories
        loadCategories(150);
        
        // Update breadcrumbs
        updateBreadcrumbs();
    }
    
    /**
     * Format price with currency symbol
     *
     * @param {number} price
     * @return {string}
     */
    function formatPrice(price) {
        return coffee_wizard.currency_symbol + price.toLocaleString();
    }
}); 