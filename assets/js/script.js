jQuery(document).ready(function($) {
    // فعال‌سازی جستجو با تایمر
    let searchTimer;
    
    // انتخاب مستقیم المنت ورودی
    $('#live-search-input').on('input keyup', function(e) {
        clearTimeout(searchTimer);
        const searchTerm = $(this).val().trim();
        
        // اگر کمتر از ۲ کاراکتر بود
        if(searchTerm.length < 2) {
            $('#live-search-results').html('').removeClass('active');
            return;
        }
        
        // شروع تایمر
        searchTimer = setTimeout(() => {
            console.log('ارسال درخواست برای:', searchTerm); // دیباگ
            
            $.ajax({
                url: live_search_obj.ajaxurl,
                method: 'POST',
                dataType: 'html',
                data: {
                    action: 'live_search',
                    search_term: searchTerm,
                    security: live_search_obj.nonce
                },
                beforeSend: function() {
                    $('#live-search-results').html('<div class="loading">جستجو...</div>').addClass('active');
                },
                success: function(response) {
                    console.log('پاسخ دریافت شد:', response); // دیباگ
                    $('#live-search-results').html(response).addClass('active');
                },
                error: function(xhr, status, error) {
                    console.error('خطای AJAX:', status, error);
                    $('#live-search-results').html('<div class="error">خطا در ارتباط با سرور</div>');
                }
            });
        }, 300);
    });

    // بستن نتایج با کلیک خارج
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.live-search-container').length) {
            $('#live-search-results').removeClass('active');
        }
    });
});