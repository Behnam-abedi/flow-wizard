<div class="wrap">
    <h1><?php _e('Coffee Wizard Settings', 'coffee-wizard-form'); ?></h1>
    
    <div class="coffee-wizard-admin-container">
        <div class="coffee-wizard-admin-card">
            <h2><?php _e('Welcome to Coffee Wizard', 'coffee-wizard-form'); ?></h2>
            <p><?php _e('This plugin allows you to create a multi-step wizard form for coffee product selection.', 'coffee-wizard-form'); ?></p>
            
            <h3><?php _e('How to Use', 'coffee-wizard-form'); ?></h3>
            <ol>
                <li><?php _e('Add the shortcode <code>[coffee_wizard_form]</code> to any page where you want to display the wizard form.', 'coffee-wizard-form'); ?></li>
                <li><?php _e('Configure the weight options for each coffee category (Specialty and Blend) in the Weight Options page.', 'coffee-wizard-form'); ?></li>
                <li><?php _e('Configure the grinding options in the Grinding Options page.', 'coffee-wizard-form'); ?></li>
                <li><?php _e('Make sure you have created the necessary product categories with the correct hierarchy.', 'coffee-wizard-form'); ?></li>
            </ol>
            
            <h3><?php _e('Required Category Structure', 'coffee-wizard-form'); ?></h3>
            <p><?php _e('The plugin requires the following category structure:', 'coffee-wizard-form'); ?></p>
            <ul>
                <li><?php _e('Quick Order (ID: 150)', 'coffee-wizard-form'); ?>
                    <ul>
                        <li><?php _e('Blend (ID: 129, Slug: blend)', 'coffee-wizard-form'); ?></li>
                        <li><?php _e('Specialty (ID: 130, Slug: specialty)', 'coffee-wizard-form'); ?></li>
                    </ul>
                </li>
            </ul>
            
            <h3><?php _e('Category Icons', 'coffee-wizard-form'); ?></h3>
            <p><?php _e('You can add icon classes to your product categories by editing the category and entering a Font Awesome icon class in the "Category Icon Class" field.', 'coffee-wizard-form'); ?></p>
            
            <div class="coffee-wizard-admin-buttons">
                <a href="<?php echo admin_url('admin.php?page=coffee-wizard-weight'); ?>" class="button button-primary"><?php _e('Configure Weight Options', 'coffee-wizard-form'); ?></a>
                <a href="<?php echo admin_url('admin.php?page=coffee-wizard-grinding'); ?>" class="button button-primary"><?php _e('Configure Grinding Options', 'coffee-wizard-form'); ?></a>
            </div>
        </div>
    </div>
</div> 