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
                    <?php do_settings_sections('coffee_wizard_weight_options_specialty'); ?>
                    
                    <div class="coffee-wizard-admin-card">
                        <h2><?php _e('Specialty Weight Options', 'coffee-wizard-form'); ?></h2>
                        <p><?php _e('Configure the weight options for specialty coffee products.', 'coffee-wizard-form'); ?></p>
                        
                        <input type="hidden" name="coffee_wizard_redirect" value="<?php echo esc_url(admin_url('admin.php?page=coffee-wizard-weight&tab=specialty')); ?>">
                        
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
                    <?php do_settings_sections('coffee_wizard_weight_options_blend'); ?>
                    
                    <div class="coffee-wizard-admin-card">
                        <h2><?php _e('Blend Weight Options', 'coffee-wizard-form'); ?></h2>
                        <p><?php _e('Configure the weight options for blend coffee products.', 'coffee-wizard-form'); ?></p>
                        
                        <input type="hidden" name="coffee_wizard_redirect" value="<?php echo esc_url(admin_url('admin.php?page=coffee-wizard-weight&tab=blend')); ?>">
                        
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

<!-- Templates for adding new rows -->
<template id="weight-option-template-specialty">
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
</template>

<template id="weight-option-template-blend">
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
</template>

<!-- Hidden versions for jQuery to access -->
<div style="display:none" id="hidden-templates">
    <script type="text/html" id="weight-option-template-specialty-js">
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

    <script type="text/html" id="weight-option-template-blend-js">
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
</div>

<script>
    jQuery(document).ready(function($) {
        // Transfer template content to the JS-accessible versions if needed
        if ($('#weight-option-template-specialty').length && $('#weight-option-template-specialty-js').length) {
            if (!$('#weight-option-template-specialty-js').html()) {
                $('#weight-option-template-specialty-js').html($('#weight-option-template-specialty').html());
                console.log('Transferred specialty template content');
            }
        }
        
        if ($('#weight-option-template-blend').length && $('#weight-option-template-blend-js').length) {
            if (!$('#weight-option-template-blend-js').html()) {
                $('#weight-option-template-blend-js').html($('#weight-option-template-blend').html());
                console.log('Transferred blend template content');
            }
        }
        
        // Check if we have a tab parameter in the URL
        var urlParams = new URLSearchParams(window.location.search);
        var tab = urlParams.get('tab');
        
        // If we have a tab parameter, activate that tab
        if (tab) {
            $('.coffee-wizard-tab[data-tab="' + tab + '"]').trigger('click');
        }
        
        // Patch the add weight option functionality
        $('.add-weight-option').off('click').on('click', function(e) {
            e.preventDefault();
            console.log('Direct add weight option handler');
            
            var target = $(this).data('target');
            console.log('Target:', target);
            
            var templateId = 'weight-option-template-' + target;
            var templateIdJs = templateId + '-js';
            
            // Try both template locations
            var templateElement = $('#' + templateIdJs);
            if (templateElement.length === 0) {
                templateElement = $('#' + templateId);
            }
            
            console.log('Template element found:', templateElement.length > 0);
            
            if (templateElement.length === 0) {
                console.error('Template not found:', templateId);
                alert('Error: Template not found. Please refresh the page and try again.');
                return;
            }
            
            var template = templateElement.html();
            var container = $('#' + target + '-weight-options');
            
            if (container.length === 0) {
                console.error('Container not found:', target + '-weight-options');
                alert('Error: Option container not found. Please refresh the page and try again.');
                return;
            }
            
            var index = container.find('tr').length;
            template = template.replace(/{index}/g, index);
            
            container.append(template);
            console.log('New weight option added successfully');
        });
    });
</script> 