jQuery(document).ready(function($) {
    var searchTimeout;
    
    $(document).on('input', '#live-search-input', function() { // اصلاح شده
        clearTimeout(searchTimeout);
        var searchTerm = $(this).val();
        
        if(searchTerm.length < 1) { // تغییر شرط به 1 کاراکتر
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
                    $('#live-search-results').html('<div class="loading">در حال جستجو...</div>').addClass('active');
                },
                success: function(response) {
                    $('#live-search-results').html(response).addClass('active');
                },
                error: function(xhr, status, error) { // افزودن هندلر خطا
                    console.error("AJAX Error:", status, error);
                }
            });
        }, 200); // کاهش تاخیر به 200ms
    });
    
    // Close results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.live-search-container').length) {
            $('#live-search-results').removeClass('active');
        }
    });
});