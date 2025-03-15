<?php
if (!defined('ABSPATH')) {
    exit;
}

$saved_weights = get_option('coffee_wizard_weights', array());
?>

<div class="wrap">
    <h1><?php echo esc_html__('Weights & Coefficients', 'coffee-wizard'); ?></h1>
    
    <div class="coffee-wizard-admin-container">
        <form id="coffee-wizard-weights-form">
            <div class="coffee-wizard-section">
                <h2><?php echo esc_html__('Manage Weights', 'coffee-wizard'); ?></h2>
                <p class="description"><?php echo esc_html__('Add weight options and their corresponding price coefficients.', 'coffee-wizard'); ?></p>
                
                <div id="weights-container">
                    <?php if (!empty($saved_weights)) : ?>
                        <?php foreach ($saved_weights as $weight) : ?>
                            <div class="weight-row">
                                <input type="number" class="weight-value" value="<?php echo esc_attr($weight['weight']); ?>" placeholder="<?php echo esc_attr__('Weight (g)', 'coffee-wizard'); ?>" min="1" required>
                                <input type="number" class="coefficient-value" value="<?php echo esc_attr($weight['coefficient']); ?>" placeholder="<?php echo esc_attr__('Coefficient', 'coffee-wizard'); ?>" step="0.01" min="0" required>
                                <button type="button" class="button remove-weight"><?php echo esc_html__('Remove', 'coffee-wizard'); ?></button>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="weight-row">
                            <input type="number" class="weight-value" placeholder="<?php echo esc_attr__('Weight (g)', 'coffee-wizard'); ?>" min="1" required>
                            <input type="number" class="coefficient-value" placeholder="<?php echo esc_attr__('Coefficient', 'coffee-wizard'); ?>" step="0.01" min="0" required>
                            <button type="button" class="button remove-weight"><?php echo esc_html__('Remove', 'coffee-wizard'); ?></button>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="button" class="button add-weight">
                    <?php echo esc_html__('Add Weight Option', 'coffee-wizard'); ?>
                </button>
            </div>

            <div class="coffee-wizard-section">
                <button type="submit" class="button button-primary">
                    <?php echo esc_html__('Save Changes', 'coffee-wizard'); ?>
                </button>
                <span class="spinner"></span>
            </div>
        </form>
    </div>
</div>

<style>
.coffee-wizard-admin-container {
    max-width: 800px;
    margin-top: 20px;
}

.coffee-wizard-section {
    background: #fff;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.weight-row {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    align-items: center;
}

.weight-row input {
    width: 150px;
}

.add-weight {
    margin-top: 10px;
}

.spinner {
    float: none;
    margin-left: 10px;
    vertical-align: middle;
}
</style>

<script>
jQuery(document).ready(function($) {
    $('.add-weight').on('click', function() {
        const weightRow = `
            <div class="weight-row">
                <input type="number" class="weight-value" placeholder="<?php echo esc_attr__('Weight (g)', 'coffee-wizard'); ?>" min="1" required>
                <input type="number" class="coefficient-value" placeholder="<?php echo esc_attr__('Coefficient', 'coffee-wizard'); ?>" step="0.01" min="0" required>
                <button type="button" class="button remove-weight"><?php echo esc_html__('Remove', 'coffee-wizard'); ?></button>
            </div>
        `;
        $('#weights-container').append(weightRow);
    });

    $(document).on('click', '.remove-weight', function() {
        const $container = $('#weights-container');
        if ($container.children().length > 1) {
            $(this).closest('.weight-row').remove();
        } else {
            alert('<?php echo esc_js(__('You must have at least one weight option', 'coffee-wizard')); ?>');
        }
    });

    $('#coffee-wizard-weights-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $spinner = $form.find('.spinner');
        const $submitButton = $form.find('button[type="submit"]');
        
        const weights = [];
        $('.weight-row').each(function() {
            const $row = $(this);
            weights.push({
                weight: $row.find('.weight-value').val(),
                coefficient: $row.find('.coefficient-value').val()
            });
        });

        $spinner.addClass('is-active');
        $submitButton.prop('disabled', true);

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'save_weight_coefficients',
                nonce: coffeeWizardAdmin.nonce,
                weights: weights
            },
            success: function(response) {
                if (response.success) {
                    alert('<?php echo esc_js(__('Weights saved successfully', 'coffee-wizard')); ?>');
                } else {
                    alert('<?php echo esc_js(__('Error saving weights', 'coffee-wizard')); ?>');
                }
            },
            error: function() {
                alert('<?php echo esc_js(__('Error saving weights', 'coffee-wizard')); ?>');
            },
            complete: function() {
                $spinner.removeClass('is-active');
                $submitButton.prop('disabled', false);
            }
        });
    });
});
</script> 