jQuery(document).ready(function($) {
    var searchTimeout;
    
    $('#live-search-input').on('input', function() {
        clearTimeout(searchTimeout);
        var searchTerm = $(this).val();
        
        if(searchTerm.length < 2) {
            $('#live-search-results').html('').removeClass('active');
            return;
        }
        
        searchTimeout = setTimeout(function() {
            $.ajax({
                url: live_search_obj.ajaxurl,
                type: 'POST',
                data: {
                    action: 'live_search',
                    search_term: searchTerm
                },
                beforeSend: function() {
                    $('#live-search-results').html('در حال جستجو...').addClass('active');
                },
                success: function(response) {
                    $('#live-search-results').html(response).addClass('active');
                }
            });
        }, 300);
    });
    
    // Close results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.live-search-container').length) {
            $('#live-search-results').removeClass('active');
        }
    });
});