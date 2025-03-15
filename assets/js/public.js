jQuery(document).ready(function($) {
    const wizard = {
        currentStep: 1,
        maxSteps: 4,
        selectedCategory: null,
        selectedProduct: null,
        selectedWeight: null,
        selectedGrinder: null,
        breadcrumbPath: [],
        productPrice: 0,

        init: function() {
            this.bindEvents();
            this.updateProgressBar();
            this.updateBreadcrumb();
        },

        bindEvents: function() {
            // Category selection
            $('.category-item').on('click', (e) => {
                const $item = $(e.currentTarget);
                $('.category-item').removeClass('selected');
                $item.addClass('selected');
                
                const categoryId = $item.data('category-id');
                this.loadSubcategories(categoryId);
            });

            // Weight selection
            $('.weight-item').on('click', (e) => {
                const $item = $(e.currentTarget);
                $('.weight-item').removeClass('selected');
                $item.addClass('selected');
                
                this.selectedWeight = {
                    weight: $item.data('weight'),
                    coefficient: $item.data('coefficient')
                };
                this.updateWeightPrices();
            });

            // Grinding options
            $('input[name="grinding"]').on('change', (e) => {
                const showGrinders = $(e.target).val() === 'yes';
                $('.grinder-options').toggle(showGrinders);
                if (!showGrinders) {
                    this.selectedGrinder = null;
                    $('input[name="grinder"]').prop('checked', false);
                }
                this.updateOrderSummary();
            });

            $('input[name="grinder"]').on('change', (e) => {
                const $selected = $(e.target);
                this.selectedGrinder = {
                    name: $selected.val(),
                    price: $selected.closest('.grinder-item').data('price')
                };
                this.updateOrderSummary();
            });

            // Navigation
            $('.prev-step').on('click', () => this.prevStep());
            $('.next-step').on('click', () => this.nextStep());
            $('.submit-order').on('click', () => this.submitOrder());
        },

        loadSubcategories: function(categoryId) {
            $.ajax({
                url: coffeeWizardPublic.ajaxurl,
                type: 'POST',
                data: {
                    action: 'get_subcategories',
                    nonce: coffeeWizardPublic.nonce,
                    category_id: categoryId
                },
                success: (response) => {
                    if (response.success) {
                        this.updateCategoryGrid(response.data.items);
                        this.breadcrumbPath.push({
                            id: categoryId,
                            name: $('.category-item.selected h3').text()
                        });
                        this.updateBreadcrumb();
                    }
                }
            });
        },

        updateCategoryGrid: function(items) {
            const $grid = $('.category-grid');
            $grid.empty();

            items.forEach(item => {
                const itemHtml = `
                    <div class="category-item" data-category-id="${item.id}" data-type="${item.type}" data-price="${item.price || 0}">
                        ${item.image ? `<img src="${item.image}" alt="${item.name}">` : ''}
                        <h3>${item.name}</h3>
                        ${item.type === 'product' ? `<div class="product-price">${item.formatted_price}</div>` : ''}
                    </div>
                `;
                $grid.append(itemHtml);
            });

            // Rebind events for new items
            this.bindEvents();
        },

        updateWeightPrices: function() {
            if (!this.selectedProduct || !this.selectedWeight) return;

            const basePrice = this.selectedProduct.price;
            const coefficient = this.selectedWeight.coefficient;
            this.productPrice = basePrice * coefficient;

            $('.weight-item').each((_, item) => {
                const $item = $(item);
                const itemCoefficient = $item.data('coefficient');
                const price = basePrice * itemCoefficient;
                $item.find('.weight-price').text(this.formatPrice(price));
            });

            this.updateOrderSummary();
        },

        updateOrderSummary: function() {
            const $summary = $('.summary-items');
            const $total = $('.total-price');
            let html = '';
            let total = 0;

            if (this.selectedProduct && this.selectedWeight) {
                html += `
                    <div class="summary-item">
                        <span class="item-name">${this.selectedProduct.name} (${this.selectedWeight.weight}g)</span>
                        <span class="item-price">${this.formatPrice(this.productPrice)}</span>
                    </div>
                `;
                total += this.productPrice;
            }

            if (this.selectedGrinder) {
                html += `
                    <div class="summary-item">
                        <span class="item-name">${this.selectedGrinder.name}</span>
                        <span class="item-price">${this.formatPrice(this.selectedGrinder.price)}</span>
                    </div>
                `;
                total += this.selectedGrinder.price;
            }

            $summary.html(html);
            $total.html(`<strong>${coffeeWizardPublic.i18n.total}:</strong> ${this.formatPrice(total)}`);
        },

        updateProgressBar: function() {
            const progress = (this.currentStep / this.maxSteps) * 100;
            $('.progress-fill').css('width', `${progress}%`);
            $('.step').removeClass('active');
            $(`.step[data-step="${this.currentStep}"]`).addClass('active');
        },

        updateBreadcrumb: function() {
            const $breadcrumb = $('.coffee-wizard-breadcrumb');
            if (this.breadcrumbPath.length === 0) {
                $breadcrumb.empty();
                return;
            }

            const html = this.breadcrumbPath.map((item, index) => {
                const isLast = index === this.breadcrumbPath.length - 1;
                return `
                    <span class="breadcrumb-item" data-id="${item.id}">
                        ${item.name}
                    </span>
                    ${!isLast ? '<span class="breadcrumb-separator">›</span>' : ''}
                `;
            }).join('');

            $breadcrumb.html(html);
        },

        prevStep: function() {
            if (this.currentStep > 1) {
                this.currentStep--;
                this.updateStepVisibility();
            }
        },

        nextStep: function() {
            if (this.validateCurrentStep()) {
                this.currentStep++;
                this.updateStepVisibility();
            }
        },

        validateCurrentStep: function() {
            switch (this.currentStep) {
                case 1:
                    if (!this.selectedProduct) {
                        alert(coffeeWizardPublic.i18n.selectProduct);
                        return false;
                    }
                    break;
                case 2:
                    if (!this.selectedWeight) {
                        alert(coffeeWizardPublic.i18n.selectWeight);
                        return false;
                    }
                    break;
                case 3:
                    const needsGrinding = $('input[name="grinding"]:checked').val() === 'yes';
                    if (needsGrinding && !this.selectedGrinder) {
                        alert(coffeeWizardPublic.i18n.selectGrinder);
                        return false;
                    }
                    break;
            }
            return true;
        },

        updateStepVisibility: function() {
            $('.wizard-step').removeClass('active');
            $(`.wizard-step[data-step="${this.currentStep}"]`).addClass('active');

            // Update navigation buttons
            $('.prev-step').toggle(this.currentStep > 1);
            $('.next-step').toggle(this.currentStep < this.maxSteps);
            $('.submit-order').toggle(this.currentStep === this.maxSteps);

            this.updateProgressBar();
        },

        submitOrder: function() {
            const orderData = {
                product_id: this.selectedProduct.id,
                weight: this.selectedWeight.weight,
                grinding: $('input[name="grinding"]:checked').val(),
                grinder: this.selectedGrinder,
                notes: $('#order-notes').val()
            };

            $.ajax({
                url: coffeeWizardPublic.ajaxurl,
                type: 'POST',
                data: {
                    action: 'submit_coffee_order',
                    nonce: coffeeWizardPublic.nonce,
                    order: orderData
                },
                success: (response) => {
                    if (response.success) {
                        window.location.href = response.data.cart_url;
                    } else {
                        alert(response.data.message || coffeeWizardPublic.i18n.errorMessage);
                    }
                },
                error: () => {
                    alert(coffeeWizardPublic.i18n.errorMessage);
                }
            });
        },

        formatPrice: function(price) {
            return coffeeWizardPublic.currency + price.toLocaleString();
        }
    };

    wizard.init();
}); 