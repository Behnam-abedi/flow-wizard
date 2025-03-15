/**
 * Main plugin scripts
 */

(function($) {
    'use strict';

    // Initialize the plugin
    $(document).ready(function() {
        initSearchPlugin();
    });

    /**
     * Initialize the search plugin
     */
    function initSearchPlugin() {
        const $searchIcon = $('.eaps-search-icon');
        const $overlay = $('.eaps-overlay');
        const $searchBox = $('.eaps-search-box');
        const $searchInput = $('.eaps-search-input');
        const $resultsContainer = $('.eaps-results-container');
        
        let searchTimer;
        let lastSearchQuery = '';

        // Open search box when icon is clicked
        $searchIcon.on('click', function(e) {
            e.preventDefault();
            openSearchBox();
        });

        // Close search box when overlay is clicked
        $overlay.on('click', function(e) {
            e.preventDefault();
            closeSearchBox();
        });

        // Prevent closing when clicking inside search box
        $searchBox.on('click', function(e) {
            e.stopPropagation();
        });

        // Handle key presses
        $(document).on('keyup', function(e) {
            // Close on ESC key
            if (e.key === 'Escape') {
                closeSearchBox();
            }
        });

        // Process search when typing
        $searchInput.on('input', function() {
            const searchQuery = $(this).val().trim();
            
            // Don't search if query hasn't changed or is empty
            if (searchQuery === lastSearchQuery) {
                return;
            }
            
            lastSearchQuery = searchQuery;
            
            // Clear previous timer
            if (searchTimer) {
                clearTimeout(searchTimer);
            }
            
            // Set a new timer to prevent too many requests
            searchTimer = setTimeout(function() {
                if (searchQuery.length > 0) {
                    performSearch(searchQuery);
                } else {
                    // Clear results if search is empty
                    $resultsContainer.empty();
                }
            }, 300);
        });

        // Function to open search box
        function openSearchBox() {
            $overlay.addClass('active');
            $searchBox.addClass('active');
            setTimeout(function() {
                $searchInput.focus();
            }, 400);
        }

        // Function to close search box
        function closeSearchBox() {
            $overlay.removeClass('active');
            $searchBox.removeClass('active');
            $searchInput.val('');
            $resultsContainer.empty();
            lastSearchQuery = '';
        }

        // Function to perform AJAX search
        function performSearch(query) {
            $.ajax({
                url: eaps_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'eaps_search_products',
                    nonce: eaps_params.nonce,
                    search_query: query
                },
                beforeSend: function() {
                    $resultsContainer.html('<div class="eaps-loading">در حال جستجو...</div>');
                },
                success: function(response) {
                    $resultsContainer.empty();
                    
                    if (response.success && response.products.length > 0) {
                        // Display products
                        $.each(response.products, function(index, product) {
                            $resultsContainer.append(buildProductItem(product));
                        });
                    } else {
                        // No results found
                        $resultsContainer.html('<div class="eaps-no-results">' + eaps_params.no_results_text + '</div>');
                    }
                },
                error: function() {
                    $resultsContainer.html('<div class="eaps-no-results">خطا در جستجو. لطفا دوباره تلاش کنید.</div>');
                }
            });
        }

        // Function to build product item HTML
        function buildProductItem(product) {
            let imageUrl = product.image || '';
            
            return `
                <a href="${product.permalink}" class="eaps-product-item">
                    <div class="eaps-product-image" style="background-image: url('${imageUrl}');"></div>
                    <div class="eaps-product-details">
                        <div class="eaps-product-title">${product.title}</div>
                        <div class="eaps-product-price">${product.price}</div>
                    </div>
                </a>
            `;
        }
    }

})(jQuery);