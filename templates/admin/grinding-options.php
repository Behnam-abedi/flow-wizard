<div class="wrap">
    <h1><?php _e('Grinding Options', 'coffee-wizard-form'); ?></h1>
    
    <div class="coffee-wizard-admin-container">
        <form method="post" action="options.php" id="grinding-form">
            <?php settings_fields('coffee_wizard_grinding_options'); ?>
            
            <div class="coffee-wizard-admin-card">
                <h2><?php _e('Grinding Machine Options', 'coffee-wizard-form'); ?></h2>
                <p><?php _e('Configure the grinding machine options for coffee products.', 'coffee-wizard-form'); ?></p>
                
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

<script type="text/html" id="grinding-option-template">
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