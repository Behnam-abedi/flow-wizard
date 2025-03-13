jQuery(document).ready(function($) {
    console.log('Coffee Wizard Admin JS loaded');
    
    // Tab functionality for weight options
    $('.coffee-wizard-tab').on('click', function(e) {
        e.preventDefault();
        
        var tab = $(this).data('tab');
        console.log('Tab clicked:', tab);
        
        // Update active tab
        $('.coffee-wizard-tab').removeClass('active');
        $(this).addClass('active');
        
        // Show corresponding tab content
        $('.coffee-wizard-tab-pane').removeClass('active');
        $('#' + tab).addClass('active');
    });
    
    // Add weight option
    $('.add-weight-option').on('click', function() {
        var target = $(this).data('target');
        console.log('Add weight option clicked for', target);
        
        var templateElement = $('#weight-option-template-' + target);
        if (templateElement.length === 0) {
            console.error('Template not found:', 'weight-option-template-' + target);
            return;
        }
        
        var template = templateElement.html();
        var container = $('#' + target + '-weight-options');
        if (container.length === 0) {
            console.error('Container not found:', target + '-weight-options');
            return;
        }
        
        var index = container.find('tr').length;
        console.log('Current row count:', index);
        
        // Replace index placeholder
        template = template.replace(/{index}/g, index);
        
        // Append to container
        container.append(template);
        console.log('New weight option added');
    });
    
    // Remove weight option
    $(document).on('click', '.remove-weight-option', function() {
        var row = $(this).closest('tr');
        
        // Don't remove if it's the only row
        if (row.siblings().length === 0) {
            var message = 'You must have at least one option.';
            if (coffee_wizard_admin && coffee_wizard_admin.i18n && coffee_wizard_admin.i18n.at_least_one_option) {
                message = coffee_wizard_admin.i18n.at_least_one_option;
            }
            alert(message);
            return;
        }
        
        // Ask for confirmation
        var confirmMessage = 'Are you sure you want to delete this option?';
        if (coffee_wizard_admin && coffee_wizard_admin.i18n && coffee_wizard_admin.i18n.confirm_delete) {
            confirmMessage = coffee_wizard_admin.i18n.confirm_delete;
        }
        
        if (confirm(confirmMessage)) {
            // Remove row
            row.remove();
            
            // Reindex remaining rows
            reindexWeightOptions();
            console.log('Weight option removed and reindexed');
        }
    });
    
    // Add grinding option
    $('.add-grinding-option').on('click', function() {
        console.log('Add grinding option clicked');
        
        var templateElement = $('#grinding-option-template');
        if (templateElement.length === 0) {
            console.error('Template not found: grinding-option-template');
            return;
        }
        
        var template = templateElement.html();
        var container = $('#grinding-options');
        if (container.length === 0) {
            console.error('Container not found: grinding-options');
            return;
        }
        
        var index = container.find('tr').length;
        console.log('Current row count:', index);
        
        // Replace index placeholder
        template = template.replace(/{index}/g, index);
        
        // Append to container
        container.append(template);
        console.log('New grinding option added');
    });
    
    // Remove grinding option
    $(document).on('click', '.remove-grinding-option', function() {
        var row = $(this).closest('tr');
        
        // Don't remove if it's the only row
        if (row.siblings().length === 0) {
            var message = 'You must have at least one option.';
            if (coffee_wizard_admin && coffee_wizard_admin.i18n && coffee_wizard_admin.i18n.at_least_one_option) {
                message = coffee_wizard_admin.i18n.at_least_one_option;
            }
            alert(message);
            return;
        }
        
        // Ask for confirmation
        var confirmMessage = 'Are you sure you want to delete this option?';
        if (coffee_wizard_admin && coffee_wizard_admin.i18n && coffee_wizard_admin.i18n.confirm_delete) {
            confirmMessage = coffee_wizard_admin.i18n.confirm_delete;
        }
        
        if (confirm(confirmMessage)) {
            // Remove row
            row.remove();
            
            // Reindex remaining rows
            reindexGrindingOptions();
            console.log('Grinding option removed and reindexed');
        }
    });
    
    // Reindex weight options
    function reindexWeightOptions() {
        // Specialty
        $('#specialty-weight-options tr').each(function(index) {
            $(this).find('input').each(function() {
                var name = $(this).attr('name');
                var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', newName);
            });
        });
        
        // Blend
        $('#blend-weight-options tr').each(function(index) {
            $(this).find('input').each(function() {
                var name = $(this).attr('name');
                var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', newName);
            });
        });
    }
    
    // Reindex grinding options
    function reindexGrindingOptions() {
        $('#grinding-options tr').each(function(index) {
            $(this).find('input').each(function() {
                var name = $(this).attr('name');
                var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', newName);
            });
        });
    }
    
    // Add form submission handling to ensure settings get saved
    $('#specialty-form, #blend-form, #grinding-form').on('submit', function() {
        console.log('Form submitted:', this.id);
    });
}); 