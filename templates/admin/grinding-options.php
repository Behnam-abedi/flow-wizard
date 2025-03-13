<div class="wrap">
    <h1><?php _e('Grinding Options', 'coffee-wizard-form'); ?></h1>
    
    <div class="coffee-wizard-admin-container">
        <form method="post" action="options.php" id="grinding-form">
            <?php settings_fields('coffee_wizard_grinding_options'); ?>
            <?php do_settings_sections('coffee_wizard_grinding_options'); ?>
            
            <div class="coffee-wizard-admin-card">
                <h2><?php _e('Grinding Machine Options', 'coffee-wizard-form'); ?></h2>
                <p><?php _e('Configure the grinding machine options for coffee products.', 'coffee-wizard-form'); ?></p>
                
                <input type="hidden" name="coffee_wizard_redirect" value="<?php echo esc_url(admin_url('admin.php?page=coffee-wizard-grinding')); ?>">
                
                <table class="form-table">
                    <thead>
                        <tr>
                            <th><?php _e('Machine Name', 'coffee-wizard-form'); ?></th>
                            <th><?php _e('Price', 'coffee-wizard-form'); ?></th>
                            <th><?php _e('Actions', 'coffee-wizard-form'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="grinding-options">
                        <?php
                        $grinding_options = get_option('coffee_wizard_grinding_options', array());
                        
                        if (!empty($grinding_options)) {
                            foreach ($grinding_options as $index => $option) {
                                ?>
                                <tr>
                                    <td>
                                        <input type="text" name="coffee_wizard_grinding_options[<?php echo $index; ?>][name]" value="<?php echo esc_attr($option['name']); ?>" placeholder="<?php _e('e.g., French Press', 'coffee-wizard-form'); ?>" required>
                                    </td>
                                    <td>
                                        <input type="number" name="coffee_wizard_grinding_options[<?php echo $index; ?>][price]" value="<?php echo esc_attr($option['price']); ?>" step="1" min="0" placeholder="<?php _e('e.g., 10000', 'coffee-wizard-form'); ?>" required>
                                    </td>
                                    <td>
                                        <button type="button" class="button remove-grinding-option"><?php _e('Remove', 'coffee-wizard-form'); ?></button>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            // Default options if none exist
                            ?>
                            <tr>
                                <td>
                                    <input type="text" name="coffee_wizard_grinding_options[0][name]" value="French Press" placeholder="<?php _e('e.g., French Press', 'coffee-wizard-form'); ?>" required>
                                </td>
                                <td>
                                    <input type="number" name="coffee_wizard_grinding_options[0][price]" value="10000" step="1" min="0" placeholder="<?php _e('e.g., 10000', 'coffee-wizard-form'); ?>" required>
                                </td>
                                <td>
                                    <button type="button" class="button remove-grinding-option"><?php _e('Remove', 'coffee-wizard-form'); ?></button>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                
                <button type="button" class="button add-grinding-option"><?php _e('Add Grinding Option', 'coffee-wizard-form'); ?></button>
                
                <?php submit_button(__('Save Grinding Options', 'coffee-wizard-form')); ?>
            </div>
        </form>
    </div>
</div>

<!-- Template for adding new grinding option -->
<template id="grinding-option-template">
    <tr>
        <td>
            <input type="text" name="coffee_wizard_grinding_options[{index}][name]" value="" placeholder="<?php _e('e.g., French Press', 'coffee-wizard-form'); ?>" required>
        </td>
        <td>
            <input type="number" name="coffee_wizard_grinding_options[{index}][price]" value="0" step="1" min="0" placeholder="<?php _e('e.g., 10000', 'coffee-wizard-form'); ?>" required>
        </td>
        <td>
            <button type="button" class="button remove-grinding-option"><?php _e('Remove', 'coffee-wizard-form'); ?></button>
        </td>
    </tr>
</template>

<!-- Hidden version for jQuery to access -->
<div style="display:none" id="hidden-templates">
    <script type="text/html" id="grinding-option-template-js">
        <tr>
            <td>
                <input type="text" name="coffee_wizard_grinding_options[{index}][name]" value="" placeholder="<?php _e('e.g., French Press', 'coffee-wizard-form'); ?>" required>
            </td>
            <td>
                <input type="number" name="coffee_wizard_grinding_options[{index}][price]" value="0" step="1" min="0" placeholder="<?php _e('e.g., 10000', 'coffee-wizard-form'); ?>" required>
            </td>
            <td>
                <button type="button" class="button remove-grinding-option"><?php _e('Remove', 'coffee-wizard-form'); ?></button>
            </td>
        </tr>
    </script>
</div>

<script>
    jQuery(document).ready(function($) {
        // Transfer template content to the JS-accessible version if needed
        if ($('#grinding-option-template').length && $('#grinding-option-template-js').length) {
            if (!$('#grinding-option-template-js').html()) {
                $('#grinding-option-template-js').html($('#grinding-option-template').html());
                console.log('Transferred grinding template content');
            }
        }
        
        // Patch the add grinding option functionality
        $('.add-grinding-option').off('click').on('click', function(e) {
            e.preventDefault();
            console.log('Direct add grinding option handler');
            
            // Try both template locations
            var templateElement = $('#grinding-option-template-js');
            if (templateElement.length === 0) {
                templateElement = $('#grinding-option-template');
            }
            
            console.log('Grinding template found:', templateElement.length > 0);
            
            if (templateElement.length === 0) {
                console.error('Template not found: grinding-option-template');
                alert('Error: Grinding template not found. Please refresh the page and try again.');
                return;
            }
            
            var template = templateElement.html();
            var container = $('#grinding-options');
            
            if (container.length === 0) {
                console.error('Container not found: grinding-options');
                alert('Error: Grinding options container not found. Please refresh the page and try again.');
                return;
            }
            
            var index = container.find('tr').length;
            template = template.replace(/{index}/g, index);
            
            container.append(template);
            console.log('New grinding option added successfully');
        });
    });
</script> 