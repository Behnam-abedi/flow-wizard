jQuery(document).ready(function($) {
    let searchTimeout;
    const searchDelay = 300; // Delay in milliseconds

    // Open search overlay
    $('.search-trigger').on('click', function() {
        $('.search-overlay').addClass('active');
        setTimeout(() => {
            $('.search-container').addClass('active');
            $('.search-input').focus();
        }, 100);
    });

    // Close search overlay
    $('.search-close').on('click', function() {
        $('.search-container').removeClass('active');
        setTimeout(() => {
            $('.search-overlay').removeClass('active');
            $('.search-input').val('');
            $('.results-container').empty();
        }, 500);
    });

    // Handle search input
    $('.search-input').on('input', function() {
        const searchTerm = $(this).val();
        clearTimeout(searchTimeout);

        if (searchTerm.length > 0) {
            searchTimeout = setTimeout(() => {
                performSearch(searchTerm);
            }, searchDelay);
        } else {
            $('.results-container').empty();
        }
    });

    // Perform AJAX search
    function performSearch(searchTerm) {
        $.ajax({
            url: searchAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'product_search',
                nonce: searchAjax.nonce,
                search_term: searchTerm
            },
            beforeSend: function() {
                $('.results-container').html('<div class="loading">در حال جستجو...</div>');
            },
            success: function(response) {
                if (response.success) {
                    displayResults(response.data);
                }
            },
            error: function() {
                $('.results-container').html('<div class="no-results">خطا در جستجو</div>');
            }
        });
    }

    // Display search results
    function displayResults(products) {
        const container = $('.results-container');
        container.empty();

        if (products.length === 0) {
            container.html('<div class="no-results">محصولی یافت نشد</div>');
            return;
        }

        products.forEach(product => {
            const productHtml = `
                <a href="${product.url}" class="product-item">
                    <img src="${product.image}" alt="${product.title}" class="product-image">
                    <div class="product-info">
                        <div class="product-title">${product.title}</div>
                        <div class="product-price">${product.price}</div>
                    </div>
                </a>
            `;
            container.append(productHtml);
        });
    }

    // Close search on overlay click
    $('.search-overlay').on('click', function(e) {
        if ($(e.target).hasClass('search-overlay')) {
            $('.search-close').click();
        }
    });

    // Close search on ESC key
    $(document).keyup(function(e) {
        if (e.key === "Escape") {
            $('.search-close').click();
        }
    });
}); 