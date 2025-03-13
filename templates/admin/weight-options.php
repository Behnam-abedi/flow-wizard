<div class="wrap">
    <h1><?php _e('Weight Options', 'coffee-wizard-form'); ?></h1>
    
    <div class="coffee-wizard-admin-container">
        <div class="coffee-wizard-admin-tabs">
            <a href="#specialty" class="coffee-wizard-tab active" data-tab="specialty"><?php _e('Specialty', 'coffee-wizard-form'); ?></a>
            <a href="#blend" class="coffee-wizard-tab" data-tab="blend"><?php _e('Blend', 'coffee-wizard-form'); ?></a>
        </div>
        
        <div class="coffee-wizard-admin-tab-content">
            <!-- Specialty Tab -->
            <div id="specialty" class="coffee-wizard-tab-pane active">
                <form method="post" action="options.php" id="specialty-form">
                    <?php settings_fields('coffee_wizard_weight_options_specialty'); ?>
                    
                    <div class="coffee-wizard-admin-card">
                        <h2><?php _e('Specialty Weight Options', 'coffee-wizard-form'); ?></h2>
                        <p><?php _e('Configure the weight options for specialty coffee products.', 'coffee-wizard-form'); ?></p>
                        
                        <table class="form-table">
                            <thead>
                                <tr>
                                    <th><?php _e('Weight', 'coffee-wizard-form'); ?></th>
                                    <th><?php _e('Price Multiplier', 'coffee-wizard-form'); ?></th>
                                    <th><?php _e('Actions', 'coffee-wizard-form'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="specialty-weight-options">
                                <?php
                                $specialty_options = get_option('coffee_wizard_weight_options_specialty', array());
                                
                                if (!empty($specialty_options)) {
                                    foreach ($specialty_options as $index => $option) {
                                        ?>
                                        <tr>
                                            <td>
                                                <input type="text" name="coffee_wizard_weight_options_specialty[<?php echo $index; ?>][weight]" value="<?php echo esc_attr($option['weight']); ?>" placeholder="<?php _e('e.g., 100g', 'coffee-wizard-form'); ?>" required>
                                            </td>
                                            <td>
                                                <input type="number" name="coffee_wizard_weight_options_specialty[<?php echo $index; ?>][price_multiplier]" value="<?php echo esc_attr($option['price_multiplier']); ?>" step="0.01" min="0" placeholder="<?php _e('e.g., 1.0', 'coffee-wizard-form'); ?>" required>
                                            </td>
                                            <td>
                                                <button type="button" class="button remove-weight-option"><?php _e('Remove', 'coffee-wizard-form'); ?></button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    // Default options if none exist
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="text" name="coffee_wizard_weight_options_specialty[0][weight]" value="100g" placeholder="<?php _e('e.g., 100g', 'coffee-wizard-form'); ?>" required>
                                        </td>
                                        <td>
                                            <input type="number" name="coffee_wizard_weight_options_specialty[0][price_multiplier]" value="1.0" step="0.01" min="0" placeholder="<?php _e('e.g., 1.0', 'coffee-wizard-form'); ?>" required>
                                        </td>
                                        <td>
                                            <button type="button" class="button remove-weight-option"><?php _e('Remove', 'coffee-wizard-form'); ?></button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        
                        <button type="button" class="button add-weight-option" data-target="specialty"><?php _e('Add Weight Option', 'coffee-wizard-form'); ?></button>
                        
                        <?php submit_button(__('Save Specialty Weight Options', 'coffee-wizard-form')); ?>
                    </div>
                </form>
            </div>
            
            <!-- Blend Tab -->
            <div id="blend" class="coffee-wizard-tab-pane">
                <form method="post" action="options.php" id="blend-form">
                    <?php settings_fields('coffee_wizard_weight_options_blend'); ?>
                    
                    <div class="coffee-wizard-admin-card">
                        <h2><?php _e('Blend Weight Options', 'coffee-wizard-form'); ?></h2>
                        <p><?php _e('Configure the weight options for blend coffee products.', 'coffee-wizard-form'); ?></p>
                        
                        <table class="form-table">
                            <thead>
                                <tr>
                                    <th><?php _e('Weight', 'coffee-wizard-form'); ?></th>
                                    <th><?php _e('Price Multiplier', 'coffee-wizard-form'); ?></th>
                                    <th><?php _e('Actions', 'coffee-wizard-form'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="blend-weight-options">
                                <?php
                                $blend_options = get_option('coffee_wizard_weight_options_blend', array());
                                
                                if (!empty($blend_options)) {
                                    foreach ($blend_options as $index => $option) {
                                        ?>
                                        <tr>
                                            <td>
                                                <input type="text" name="coffee_wizard_weight_options_blend[<?php echo $index; ?>][weight]" value="<?php echo esc_attr($option['weight']); ?>" placeholder="<?php _e('e.g., 100g', 'coffee-wizard-form'); ?>" required>
                                            </td>
                                            <td>
                                                <input type="number" name="coffee_wizard_weight_options_blend[<?php echo $index; ?>][price_multiplier]" value="<?php echo esc_attr($option['price_multiplier']); ?>" step="0.01" min="0" placeholder="<?php _e('e.g., 1.0', 'coffee-wizard-form'); ?>" required>
                                            </td>
                                            <td>
                                                <button type="button" class="button remove-weight-option"><?php _e('Remove', 'coffee-wizard-form'); ?></button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    // Default options if none exist
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="text" name="coffee_wizard_weight_options_blend[0][weight]" value="100g" placeholder="<?php _e('e.g., 100g', 'coffee-wizard-form'); ?>" required>
                                        </td>
                                        <td>
                                            <input type="number" name="coffee_wizard_weight_options_blend[0][price_multiplier]" value="1.0" step="0.01" min="0" placeholder="<?php _e('e.g., 1.0', 'coffee-wizard-form'); ?>" required>
                                        </td>
                                        <td>
                                            <button type="button" class="button remove-weight-option"><?php _e('Remove', 'coffee-wizard-form'); ?></button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        
                        <button type="button" class="button add-weight-option" data-target="blend"><?php _e('Add Weight Option', 'coffee-wizard-form'); ?></button>
                        
                        <?php submit_button(__('Save Blend Weight Options', 'coffee-wizard-form')); ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/html" id="weight-option-template-specialty">
    <tr>
        <td>
            <input type="text" name="coffee_wizard_weight_options_specialty[{index}][weight]" value="" placeholder="<?php _e('e.g., 100g', 'coffee-wizard-form'); ?>" required>
        </td>
        <td>
            <input type="number" name="coffee_wizard_weight_options_specialty[{index}][price_multiplier]" value="1.0" step="0.01" min="0" placeholder="<?php _e('e.g., 1.0', 'coffee-wizard-form'); ?>" required>
        </td>
        <td>
            <button type="button" class="button remove-weight-option"><?php _e('Remove', 'coffee-wizard-form'); ?></button>
        </td>
    </tr>
</script>

<script type="text/html" id="weight-option-template-blend">
    <tr>
        <td>
            <input type="text" name="coffee_wizard_weight_options_blend[{index}][weight]" value="" placeholder="<?php _e('e.g., 100g', 'coffee-wizard-form'); ?>" required>
        </td>
        <td>
            <input type="number" name="coffee_wizard_weight_options_blend[{index}][price_multiplier]" value="1.0" step="0.01" min="0" placeholder="<?php _e('e.g., 1.0', 'coffee-wizard-form'); ?>" required>
        </td>
        <td>
            <button type="button" class="button remove-weight-option"><?php _e('Remove', 'coffee-wizard-form'); ?></button>
        </td>
    </tr>
</script> 