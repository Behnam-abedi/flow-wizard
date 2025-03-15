(function($) {
    'use strict';

    // Plugin Constants
    const KEYCODE = {
        ESC: 27,
    };

    // Variables
    let searchPopup = null;
    let searchOverlay = null;
    let searchBox = null;
    let searchInput = null;
    let searchResults = null;
    let searchTimer = null;
    let isSearchActive = false;

    /**
     * Initialize Search Functionality
     */
    function initSearch() {
        // When DOM is ready
        $(document).ready(function() {
            // Add search popup to footer
            if ($('.search-popup-template').length) {
                const searchPopupTemplate = $('.search-popup-template').first();
                $('body').append(searchPopupTemplate.html());
                searchPopupTemplate.remove();
            }

            // Cache DOM elements
            searchPopup = $('.search-overlay, .search-box');
            searchOverlay = $('.search-overlay');
            searchBox = $('.search-box');
            searchInput = $('.search-input');
            searchResults = $('.search-results-inner');

            // Add click event to search icon
            $('.search-icon-widget').on('click', openSearch);

            // Add close events
            $(document).on('keydown', function(e) {
                if (e.keyCode === KEYCODE.ESC && isSearchActive) {
                    closeSearch();
                }
            });

            searchOverlay.on('click', closeSearch);

            // Add input event for search
            searchInput.on('input', function() {
                const query = $(this).val();
                
                // Clear previous timer
                if (searchTimer) {
                    clearTimeout(searchTimer);
                }
                
                // Set new timer for search delay
                searchTimer = setTimeout(function() {
                    performSearch(query);
                }, 300); // 300ms delay to prevent too many requests
            });
        });
    }

    /**
     * Open Search Popup
     */
    function openSearch() {
        if (isSearchActive) return;
        
        isSearchActive = true;
        
        // Add active class to body
        $('body').addClass('search-active');
        
        // Show overlay with animation
        searchOverlay.addClass('active');
        
        // Show search box with animation
        setTimeout(function() {
            searchBox.addClass('active');
            
            // Focus on input after animation
            setTimeout(function() {
                searchInput.focus();
            }, 400);
        }, 100);
    }

    /**
     * Close Search Popup
     */
    function closeSearch() {
        if (!isSearchActive) return;
        
        isSearchActive = false;
        
        // Hide search box with animation
        searchBox.removeClass('active');
        
        // Hide overlay after search box animation
        setTimeout(function() {
            searchOverlay.removeClass('active');
            
            // Remove active class from body after all animations
            setTimeout(function() {
                $('body').removeClass('search-active');
                
                // Clear search input and results
                searchInput.val('');
                searchResults.empty();
            }, 300);
        }, 300);
    }

    /**
     * Perform AJAX Search
     */
    function performSearch(query) {
        // Show loading
        searchResults.html('<div class="search-loading"><div class="search-loading-spinner"></div></div>');
        
        // If query is empty, clear results
        if (query.length === 0) {
            searchResults.empty();
            return;
        }
        
        // Make AJAX request
        $.ajax({
            url: searchProductsData.ajax_url,
            type: 'POST',
            data: {
                action: 'search_products',
                nonce: searchProductsData.nonce,
                search_query: query
            },
            success: function(response) {
                if (response.success) {
                    displayResults(response.data);
                } else {
                    searchResults.html('<div class="search-no-results">Error: Could not fetch results</div>');
                }
            },
            error: function() {
                searchResults.html('<div class="search-no-results">Error: Could not fetch results</div>');
            }
        });
    }

    /**
     * Display Search Results
     */
    function displayResults(data) {
        // Clear previous results
        searchResults.empty();
        
        const products = data.products;
        const count = data.count;
        
        // If no products found
        if (count === 0) {
            searchResults.html('<div class="search-no-results">No products found</div>');
            return;
        }
        
        // Build results HTML
        products.forEach(function(product) {
            let productHtml = `
                <a href="${product.permalink}" class="search-result-item">
                    <div class="search-result-image">
                        ${product.image ? `<img src="${product.image}" alt="${product.title}">` : '<div class="no-image"></div>'}
                    </div>
                    <div class="search-result-title">${product.title}</div>
                    <div class="search-result-price">${product.price}</div>
                </a>
            `;
            
            searchResults.append(productHtml);
        });
    }

    // Initialize search functionality
    initSearch();

})(jQuery); 