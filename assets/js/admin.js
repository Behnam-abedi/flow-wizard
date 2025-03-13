jQuery(document).ready(function($) {
    // Tab functionality for weight options
    $('.coffee-wizard-tab').on('click', function(e) {
        e.preventDefault();
        
        var tab = $(this).data('tab');
        
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
        var template = $('#weight-option-template-' + target).html();
        var container = $('#' + target + '-weight-options');
        var index = container.find('tr').length;
        
        // Replace index placeholder
        template = template.replace(/{index}/g, index);
        
        // Append to container
        container.append(template);
    });
    
    // Remove weight option
    $(document).on('click', '.remove-weight-option', function() {
        var row = $(this).closest('tr');
        
        // Don't remove if it's the only row
        if (row.siblings().length === 0) {
            alert(coffee_wizard_admin.i18n.at_least_one_option);
            return;
        }
        
        // Remove row
        row.remove();
        
        // Reindex remaining rows
        reindexWeightOptions();
    });
    
    // Add grinding option
    $('.add-grinding-option').on('click', function() {
        var template = $('#grinding-option-template').html();
        var container = $('#grinding-options');
        var index = container.find('tr').length;
        
        // Replace index placeholder
        template = template.replace(/{index}/g, index);
        
        // Append to container
        container.append(template);
    });
    
    // Remove grinding option
    $(document).on('click', '.remove-grinding-option', function() {
        var row = $(this).closest('tr');
        
        // Don't remove if it's the only row
        if (row.siblings().length === 0) {
            alert(coffee_wizard_admin.i18n.at_least_one_option);
            return;
        }
        
        // Remove row
        row.remove();
        
        // Reindex remaining rows
        reindexGrindingOptions();
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
}); 