<div class="coffee-wizard-container">
    <div class="coffee-wizard-steps">
        <div class="coffee-wizard-step active" data-step="1">
            <span class="step-number">1</span>
            <span class="step-title"><?php _e('Select Product', 'coffee-wizard-form'); ?></span>
        </div>
        <div class="coffee-wizard-step" data-step="2">
            <span class="step-number">2</span>
            <span class="step-title"><?php _e('Select Weight', 'coffee-wizard-form'); ?></span>
        </div>
        <div class="coffee-wizard-step" data-step="3">
            <span class="step-number">3</span>
            <span class="step-title"><?php _e('Grinding Type', 'coffee-wizard-form'); ?></span>
        </div>
        <div class="coffee-wizard-step" data-step="4">
            <span class="step-number">4</span>
            <span class="step-title"><?php _e('Order Notes', 'coffee-wizard-form'); ?></span>
        </div>
        <div class="coffee-wizard-step" data-step="5">
            <span class="step-number">5</span>
            <span class="step-title"><?php _e('Summary', 'coffee-wizard-form'); ?></span>
        </div>
    </div>
    
    <div class="coffee-wizard-content">
        <!-- Step 1: Select Product -->
        <div class="coffee-wizard-step-content active" data-step="1">
            <h2><?php _e('Select Product', 'coffee-wizard-form'); ?></h2>
            
            <div class="coffee-wizard-categories">
                <!-- Categories will be loaded here via AJAX -->
                <div class="coffee-wizard-loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span><?php _e('Loading categories...', 'coffee-wizard-form'); ?></span>
                </div>
            </div>
            
            <div class="coffee-wizard-products" style="display: none;">
                <!-- Products will be loaded here via AJAX -->
            </div>
            
            <div class="coffee-wizard-breadcrumbs">
                <ul>
                    <li data-id="150" class="active"><?php _e('Quick Order', 'coffee-wizard-form'); ?></li>
                </ul>
            </div>
        </div>
        
        <!-- Step 2: Select Weight -->
        <div class="coffee-wizard-step-content" data-step="2">
            <h2><?php _e('Select Weight', 'coffee-wizard-form'); ?></h2>
            
            <div class="coffee-wizard-weight-options">
                <!-- Weight options will be loaded here via AJAX -->
                <div class="coffee-wizard-loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span><?php _e('Loading weight options...', 'coffee-wizard-form'); ?></span>
                </div>
            </div>
            
            <div class="coffee-wizard-product-info">
                <div class="product-image">
                    <!-- Product image will be displayed here -->
                </div>
                <div class="product-details">
                    <h3 class="product-name"></h3>
                    <div class="product-price"></div>
                    <div class="product-description"></div>
                </div>
            </div>
        </div>
        
        <!-- Step 3: Grinding Type -->
        <div class="coffee-wizard-step-content" data-step="3">
            <h2><?php _e('Choose Grinding Type', 'coffee-wizard-form'); ?></h2>
            
            <div class="coffee-wizard-grinding-options">
                <div class="grinding-option">
                    <input type="radio" name="grinding_option" id="no-grinding" value="no-grinding" checked>
                    <label for="no-grinding"><?php _e('No grinding', 'coffee-wizard-form'); ?></label>
                </div>
                
                <div class="grinding-option">
                    <input type="radio" name="grinding_option" id="grind" value="grind">
                    <label for="grind"><?php _e('Grind the product', 'coffee-wizard-form'); ?></label>
                </div>
                
                <div class="coffee-wizard-grinding-machines" style="display: none;">
                    <!-- Grinding machines will be loaded here via AJAX -->
                    <div class="coffee-wizard-loading">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span><?php _e('Loading grinding options...', 'coffee-wizard-form'); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="coffee-wizard-product-summary">
                <h3><?php _e('Product Summary', 'coffee-wizard-form'); ?></h3>
                <div class="product-summary-details">
                    <div class="summary-item">
                        <span class="summary-label"><?php _e('Product:', 'coffee-wizard-form'); ?></span>
                        <span class="summary-value product-name"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label"><?php _e('Weight:', 'coffee-wizard-form'); ?></span>
                        <span class="summary-value product-weight"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label"><?php _e('Price:', 'coffee-wizard-form'); ?></span>
                        <span class="summary-value product-price"></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Step 4: Order Notes -->
        <div class="coffee-wizard-step-content" data-step="4">
            <h2><?php _e('Order Notes', 'coffee-wizard-form'); ?></h2>
            
            <div class="coffee-wizard-notes">
                <label for="order-notes"><?php _e('Add any special instructions for your order (optional):', 'coffee-wizard-form'); ?></label>
                <textarea id="order-notes" name="order_notes" rows="5"></textarea>
            </div>
            
            <div class="coffee-wizard-product-summary">
                <h3><?php _e('Product Summary', 'coffee-wizard-form'); ?></h3>
                <div class="product-summary-details">
                    <div class="summary-item">
                        <span class="summary-label"><?php _e('Product:', 'coffee-wizard-form'); ?></span>
                        <span class="summary-value product-name"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label"><?php _e('Weight:', 'coffee-wizard-form'); ?></span>
                        <span class="summary-value product-weight"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label"><?php _e('Grinding:', 'coffee-wizard-form'); ?></span>
                        <span class="summary-value product-grinding"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label"><?php _e('Price:', 'coffee-wizard-form'); ?></span>
                        <span class="summary-value product-price"></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Step 5: Summary -->
        <div class="coffee-wizard-step-content" data-step="5">
            <h2><?php _e('Order Summary', 'coffee-wizard-form'); ?></h2>
            
            <div class="coffee-wizard-order-summary">
                <div class="order-summary-details">
                    <div class="summary-item">
                        <span class="summary-label"><?php _e('Product:', 'coffee-wizard-form'); ?></span>
                        <span class="summary-value product-name"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label"><?php _e('Weight:', 'coffee-wizard-form'); ?></span>
                        <span class="summary-value product-weight"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label"><?php _e('Grinding:', 'coffee-wizard-form'); ?></span>
                        <span class="summary-value product-grinding"></span>
                    </div>
                    <div class="summary-item notes-item" style="display: none;">
                        <span class="summary-label"><?php _e('Notes:', 'coffee-wizard-form'); ?></span>
                        <span class="summary-value product-notes"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label"><?php _e('Price:', 'coffee-wizard-form'); ?></span>
                        <span class="summary-value product-price"></span>
                    </div>
                </div>
                
                <div class="coffee-wizard-add-to-cart">
                    <button type="button" class="add-to-cart-button"><?php _e('Add to Cart', 'coffee-wizard-form'); ?></button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="coffee-wizard-navigation">
        <button type="button" class="prev-step-button" disabled><?php _e('Previous Step', 'coffee-wizard-form'); ?></button>
        <button type="button" class="next-step-button" disabled><?php _e('Next Step', 'coffee-wizard-form'); ?></button>
    </div>
</div>

<!-- Another Order Modal -->
<div class="coffee-wizard-modal" id="another-order-modal" style="display: none;">
    <div class="coffee-wizard-modal-content">
        <h3><?php _e('Do you have another order?', 'coffee-wizard-form'); ?></h3>
        <div class="coffee-wizard-modal-buttons">
            <button type="button" class="another-order-yes"><?php _e('Yes', 'coffee-wizard-form'); ?></button>
            <button type="button" class="another-order-no"><?php _e('No', 'coffee-wizard-form'); ?></button>
        </div>
    </div>
</div> 