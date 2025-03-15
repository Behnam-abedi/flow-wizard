<?php
if (!defined('ABSPATH')) {
    exit;
}

$saved_categories = get_option('coffee_wizard_categories', array());
$product_categories = get_terms(array(
    'taxonomy' => 'product_cat',
    'hide_empty' => false,
));
?>

<div class="wrap">
    <h1><?php echo esc_html__('Coffee Categories', 'coffee-wizard'); ?></h1>
    
    <div class="coffee-wizard-admin-container">
        <form id="coffee-wizard-categories-form">
            <div class="coffee-wizard-section">
                <h2><?php echo esc_html__('Select Main Categories', 'coffee-wizard'); ?></h2>
                <p class="description"><?php echo esc_html__('Select two main categories for the wizard form.', 'coffee-wizard'); ?></p>
                
                <div class="coffee-categories-selection">
                    <select name="category_1" id="category_1" class="regular-text">
                        <option value=""><?php echo esc_html__('Select Category 1', 'coffee-wizard'); ?></option>
                        <?php foreach ($product_categories as $category) : ?>
                            <option value="<?php echo esc_attr($category->term_id); ?>" 
                                <?php selected(isset($saved_categories[0]), $category->term_id); ?>>
                                <?php echo esc_html($category->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="category_2" id="category_2" class="regular-text">
                        <option value=""><?php echo esc_html__('Select Category 2', 'coffee-wizard'); ?></option>
                        <?php foreach ($product_categories as $category) : ?>
                            <option value="<?php echo esc_attr($category->term_id); ?>"
                                <?php selected(isset($saved_categories[1]), $category->term_id); ?>>
                                <?php echo esc_html($category->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="coffee-wizard-section">
                <button type="submit" class="button button-primary">
                    <?php echo esc_html__('Save Categories', 'coffee-wizard'); ?>
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

.coffee-categories-selection {
    display: flex;
    gap: 20px;
    margin-top: 15px;
}

.coffee-categories-selection select {
    min-width: 250px;
}

.spinner {
    float: none;
    margin-left: 10px;
    vertical-align: middle;
}
</style>

<script>
jQuery(document).ready(function($) {
    $('#coffee-wizard-categories-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $spinner = $form.find('.spinner');
        const $submitButton = $form.find('button[type="submit"]');
        
        const categories = [
            $('#category_1').val(),
            $('#category_2').val()
        ];

        if (!categories[0] || !categories[1]) {
            alert('<?php echo esc_js(__('Please select both categories', 'coffee-wizard')); ?>');
            return;
        }

        if (categories[0] === categories[1]) {
            alert('<?php echo esc_js(__('Please select different categories', 'coffee-wizard')); ?>');
            return;
        }

        $spinner.addClass('is-active');
        $submitButton.prop('disabled', true);

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'save_coffee_categories',
                nonce: coffeeWizardAdmin.nonce,
                categories: categories
            },
            success: function(response) {
                if (response.success) {
                    alert('<?php echo esc_js(__('Categories saved successfully', 'coffee-wizard')); ?>');
                } else {
                    alert('<?php echo esc_js(__('Error saving categories', 'coffee-wizard')); ?>');
                }
            },
            error: function() {
                alert('<?php echo esc_js(__('Error saving categories', 'coffee-wizard')); ?>');
            },
            complete: function() {
                $spinner.removeClass('is-active');
                $submitButton.prop('disabled', false);
            }
        });
    });
});
</script> 