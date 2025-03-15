<?php
if (!defined('ABSPATH')) {
    exit;
}

$categories = get_option('coffee_wizard_categories', array());
$weights = get_option('coffee_wizard_weights', array());
$grinders = get_option('coffee_wizard_grinders', array());
?>

<div class="coffee-wizard-container">
    <div class="coffee-wizard-progress">
        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>
        <div class="progress-steps">
            <div class="step active" data-step="1">
                <span class="step-number">1</span>
                <span class="step-label"><?php echo esc_html__('Select Coffee', 'coffee-wizard'); ?></span>
            </div>
            <div class="step" data-step="2">
                <span class="step-number">2</span>
                <span class="step-label"><?php echo esc_html__('Choose Weight', 'coffee-wizard'); ?></span>
            </div>
            <div class="step" data-step="3">
                <span class="step-number">3</span>
                <span class="step-label"><?php echo esc_html__('Grinding Options', 'coffee-wizard'); ?></span>
            </div>
            <div class="step" data-step="4">
                <span class="step-number">4</span>
                <span class="step-label"><?php echo esc_html__('Order Details', 'coffee-wizard'); ?></span>
            </div>
        </div>
    </div>

    <div class="coffee-wizard-breadcrumb"></div>

    <div class="coffee-wizard-content">
        <!-- Step 1: Coffee Selection -->
        <div class="wizard-step active" data-step="1">
            <h2><?php echo esc_html__('Select Your Coffee Type', 'coffee-wizard'); ?></h2>
            <div class="category-grid">
                <?php
                if (!empty($categories)) {
                    foreach ($categories as $category_id) {
                        $category = get_term($category_id, 'product_cat');
                        if ($category && !is_wp_error($category)) {
                            $thumbnail_id = get_term_meta($category_id, 'thumbnail_id', true);
                            $image = wp_get_attachment_url($thumbnail_id);
                            ?>
                            <div class="category-item" data-category-id="<?php echo esc_attr($category_id); ?>">
                                <?php if ($image) : ?>
                                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($category->name); ?>">
                                <?php endif; ?>
                                <h3><?php echo esc_html($category->name); ?></h3>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
            </div>
        </div>

        <!-- Step 2: Weight Selection -->
        <div class="wizard-step" data-step="2">
            <h2><?php echo esc_html__('Choose Weight', 'coffee-wizard'); ?></h2>
            <div class="weight-grid">
                <?php
                if (!empty($weights)) {
                    foreach ($weights as $weight) {
                        ?>
                        <div class="weight-item" data-weight="<?php echo esc_attr($weight['weight']); ?>" data-coefficient="<?php echo esc_attr($weight['coefficient']); ?>">
                            <span class="weight-value"><?php echo esc_html($weight['weight']); ?>g</span>
                            <span class="weight-price"></span>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>

        <!-- Step 3: Grinding Options -->
        <div class="wizard-step" data-step="3">
            <h2><?php echo esc_html__('Grinding Options', 'coffee-wizard'); ?></h2>
            <div class="grinding-options">
                <div class="grinding-choice">
                    <label>
                        <input type="radio" name="grinding" value="no" checked>
                        <?php echo esc_html__('No Grinding', 'coffee-wizard'); ?>
                    </label>
                    <label>
                        <input type="radio" name="grinding" value="yes">
                        <?php echo esc_html__('Yes, Grind My Coffee', 'coffee-wizard'); ?>
                    </label>
                </div>

                <div class="grinder-options" style="display: none;">
                    <?php
                    if (!empty($grinders)) {
                        foreach ($grinders as $grinder) {
                            ?>
                            <div class="grinder-item" data-price="<?php echo esc_attr($grinder['price']); ?>">
                                <label>
                                    <input type="radio" name="grinder" value="<?php echo esc_attr($grinder['name']); ?>">
                                    <span class="grinder-name"><?php echo esc_html($grinder['name']); ?></span>
                                    <span class="grinder-price"><?php echo wc_price($grinder['price']); ?></span>
                                </label>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Step 4: Order Details -->
        <div class="wizard-step" data-step="4">
            <h2><?php echo esc_html__('Order Details', 'coffee-wizard'); ?></h2>
            <div class="order-details">
                <textarea id="order-notes" placeholder="<?php echo esc_attr__('Add any special instructions for your order...', 'coffee-wizard'); ?>"></textarea>
                
                <div class="order-summary">
                    <h3><?php echo esc_html__('Order Summary', 'coffee-wizard'); ?></h3>
                    <div class="summary-items"></div>
                    <div class="total-price"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="coffee-wizard-navigation">
        <button type="button" class="button prev-step" style="display: none;">
            <?php echo esc_html__('Previous', 'coffee-wizard'); ?>
        </button>
        <button type="button" class="button next-step">
            <?php echo esc_html__('Next', 'coffee-wizard'); ?>
        </button>
        <button type="button" class="button submit-order" style="display: none;">
            <?php echo esc_html__('Add to Cart', 'coffee-wizard'); ?>
        </button>
    </div>
</div>

<style>
.coffee-wizard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.coffee-wizard-progress {
    margin-bottom: 40px;
}

.progress-bar {
    height: 4px;
    background: #eee;
    margin-bottom: 20px;
    position: relative;
}

.progress-fill {
    height: 100%;
    background: #2271b1;
    width: 25%;
    transition: width 0.3s ease;
}

.progress-steps {
    display: flex;
    justify-content: space-between;
}

.step {
    text-align: center;
    color: #666;
}

.step.active {
    color: #2271b1;
}

.step-number {
    display: inline-block;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #eee;
    line-height: 30px;
    margin-bottom: 5px;
}

.step.active .step-number {
    background: #2271b1;
    color: #fff;
}

.coffee-wizard-breadcrumb {
    margin-bottom: 20px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 4px;
}

.category-grid,
.weight-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.category-item,
.weight-item {
    border: 2px solid #eee;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.category-item:hover,
.weight-item:hover {
    border-color: #2271b1;
}

.category-item.selected,
.weight-item.selected {
    border-color: #2271b1;
    background: rgba(34, 113, 177, 0.1);
}

.category-item img {
    max-width: 100px;
    height: auto;
    margin-bottom: 10px;
}

.grinding-options {
    max-width: 600px;
    margin: 0 auto;
}

.grinding-choice {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin-bottom: 30px;
}

.grinder-options {
    display: grid;
    gap: 15px;
}

.grinder-item {
    padding: 15px;
    border: 1px solid #eee;
    border-radius: 4px;
}

.grinder-item label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.order-details {
    max-width: 800px;
    margin: 0 auto;
}

#order-notes {
    width: 100%;
    min-height: 100px;
    margin-bottom: 30px;
    padding: 10px;
}

.order-summary {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
}

.coffee-wizard-navigation {
    margin-top: 30px;
    display: flex;
    justify-content: space-between;
    gap: 10px;
}

.button {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    background: #2271b1;
    color: #fff;
    font-size: 16px;
}

.button:hover {
    background: #135e96;
}

.button.prev-step {
    background: #6c757d;
}

@media (max-width: 768px) {
    .category-grid,
    .weight-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }

    .step-label {
        display: none;
    }
}
</style> 