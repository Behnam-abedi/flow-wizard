jQuery(document).ready(function($) {
    // وقتی روی آیکون ذره‌بین کلیک می‌کنیم
    $('#my-ajax-search-icon').on('click', function(e) {
        e.preventDefault();
        // نمایش لایه‌ی تیره
        $('#my-ajax-search-overlay').fadeIn(200);
        // بالا آمدن باکس
        $('#my-ajax-search-box').css('bottom', '0');
        // فوکوس روی اینپوت
        $('#my-ajax-search-input').focus();
    });
    
    // اگر کاربر روی پس‌زمینه‌ی تیره کلیک کرد، باکس بسته شود
    $('#my-ajax-search-overlay').on('click', function() {
        closeSearchBox();
    });
    
    // تابع بستن باکس
    function closeSearchBox() {
        $('#my-ajax-search-box').css('bottom', '-50%');
        $('#my-ajax-search-overlay').fadeOut(200);
    }
    
    // جستجوی ایجکسی با هر دکمه‌ی کیبورد
    $('#my-ajax-search-input').on('keyup', function() {
        let searchVal = $(this).val().trim();
        
        // اگر ورودی خالی بود، نتایج را پاک کن
        if (!searchVal) {
            $('#my-ajax-search-results').html('');
            return;
        }
        
        $.ajax({
            url: aps_ajax_params.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'ajax_search_products',
                search_term: searchVal,
                security: aps_ajax_params.nonce
            },
            success: function(response) {
                if (response.success) {
                    let results = response.data;
                    
                    if (results.length === 0) {
                        // هیچ محصولی یافت نشد
                        $('#my-ajax-search-results').html('<p>چیزی یافت نشد</p>');
                    } else {
                        let html = '';
                        results.forEach(function(item) {
                            html += `
                                <div class="my-ajax-search-item">
                                    <img src="${item.img ? item.img : ''}" alt="${item.name}" />
                                    <a href="${item.link}" target="_blank">
                                        ${item.name}
                                    </a>
                                </div>
                            `;
                        });
                        $('#my-ajax-search-results').html(html);
                    }
                } else {
                    // در صورت بروز خطا
                    $('#my-ajax-search-results').html('<p>خطا در دریافت اطلاعات!</p>');
                }
            },
            error: function() {
                $('#my-ajax-search-results').html('<p>خطا در برقراری ارتباط!</p>');
            }
        });
    });
});
