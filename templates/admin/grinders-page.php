<?php
if (!defined('ABSPATH')) {
    exit;
}

$saved_grinders = get_option('coffee_wizard_grinders', array());
?>

<div class="wrap">
    <h1><?php echo esc_html__('Grinder Options', 'coffee-wizard'); ?></h1>
    
    <div class="coffee-wizard-admin-container">
        <form id="coffee-wizard-grinders-form">
            <div class="coffee-wizard-section">
                <h2><?php echo esc_html__('Manage Grinders', 'coffee-wizard'); ?></h2>
                <p class="description"><?php echo esc_html__('Add coffee grinder options and their prices.', 'coffee-wizard'); ?></p>
                
                <div id="grinders-container">
                    <?php if (!empty($saved_grinders)) : ?>
                        <?php foreach ($saved_grinders as $grinder) : ?>
                            <div class="grinder-row">
                                <input type="text" class="grinder-name" value="<?php echo esc_attr($grinder['name']); ?>" placeholder="<?php echo esc_attr__('Grinder Name', 'coffee-wizard'); ?>" required>
                                <div class="price-input-wrapper">
                                    <span class="currency-symbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
                                    <input type="number" class="grinder-price" value="<?php echo esc_attr($grinder['price']); ?>" placeholder="<?php echo esc_attr__('Price', 'coffee-wizard'); ?>" min="0" step="1000" required>
                                    <span class="price-suffix"><?php echo esc_html__('تومان', 'coffee-wizard'); ?></span>
                                </div>
                                <button type="button" class="button remove-grinder"><?php echo esc_html__('Remove', 'coffee-wizard'); ?></button>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="grinder-row">
                            <input type="text" class="grinder-name" placeholder="<?php echo esc_attr__('Grinder Name', 'coffee-wizard'); ?>" required>
                            <div class="price-input-wrapper">
                                <span class="currency-symbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
                                <input type="number" class="grinder-price" placeholder="<?php echo esc_attr__('Price', 'coffee-wizard'); ?>" min="0" step="1000" required>
                                <span class="price-suffix"><?php echo esc_html__('تومان', 'coffee-wizard'); ?></span>
                            </div>
                            <button type="button" class="button remove-grinder"><?php echo esc_html__('Remove', 'coffee-wizard'); ?></button>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="button" class="button add-grinder">
                    <?php echo esc_html__('Add Grinder Option', 'coffee-wizard'); ?>
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

.grinder-row {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    align-items: center;
}

.grinder-row input.grinder-name {
    width: 250px;
}

.price-input-wrapper {
    display: flex;
    align-items: center;
    gap: 5px;
}

.grinder-row input.grinder-price {
    width: 150px;
}

.currency-symbol,
.price-suffix {
    color: #666;
}

.add-grinder {
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
    $('.add-grinder').on('click', function() {
        const grinderRow = `
            <div class="grinder-row">
                <input type="text" class="grinder-name" placeholder="<?php echo esc_attr__('Grinder Name', 'coffee-wizard'); ?>" required>
                <div class="price-input-wrapper">
                    <span class="currency-symbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
                    <input type="number" class="grinder-price" placeholder="<?php echo esc_attr__('Price', 'coffee-wizard'); ?>" min="0" step="1000" required>
                    <span class="price-suffix"><?php echo esc_html__('تومان', 'coffee-wizard'); ?></span>
                </div>
                <button type="button" class="button remove-grinder"><?php echo esc_html__('Remove', 'coffee-wizard'); ?></button>
            </div>
        `;
        $('#grinders-container').append(grinderRow);
    });

    $(document).on('click', '.remove-grinder', function() {
        const $container = $('#grinders-container');
        if ($container.children().length > 1) {
            $(this).closest('.grinder-row').remove();
        } else {
            alert('<?php echo esc_js(__('You must have at least one grinder option', 'coffee-wizard')); ?>');
        }
    });

    $('#coffee-wizard-grinders-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $spinner = $form.find('.spinner');
        const $submitButton = $form.find('button[type="submit"]');
        
        const grinders = [];
        $('.grinder-row').each(function() {
            const $row = $(this);
            grinders.push({
                name: $row.find('.grinder-name').val(),
                price: $row.find('.grinder-price').val()
            });
        });

        $spinner.addClass('is-active');
        $submitButton.prop('disabled', true);

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'save_grinder_options',
                nonce: coffeeWizardAdmin.nonce,
                grinders: grinders
            },
            success: function(response) {
                if (response.success) {
                    alert('<?php echo esc_js(__('Grinder options saved successfully', 'coffee-wizard')); ?>');
                } else {
                    alert('<?php echo esc_js(__('Error saving grinder options', 'coffee-wizard')); ?>');
                }
            },
            error: function() {
                alert('<?php echo esc_js(__('Error saving grinder options', 'coffee-wizard')); ?>');
            },
            complete: function() {
                $spinner.removeClass('is-active');
                $submitButton.prop('disabled', false);
            }
        });
    });
});
</script> 