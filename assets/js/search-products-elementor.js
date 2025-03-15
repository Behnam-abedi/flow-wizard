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
            console.log('Search Products Elementor: DOM is ready');
            
            // Directly add click handler to all search icons
            $(document).on('click', '.search-icon-widget, .search-icon, .search-icon-container', function(e) {
                console.log('Search icon clicked');
                openSearch();
                e.stopPropagation();
            });

            // Add search popup to footer if it doesn't exist already
            if ($('.search-overlay, .search-box').length === 0) {
                console.log('Adding search popup to footer');
                if ($('.search-popup-template').length) {
                    const searchPopupTemplate = $('.search-popup-template').first();
                    $('body').append(searchPopupTemplate.html());
                    searchPopupTemplate.remove();
                }
            }

            // Cache DOM elements
            searchPopup = $('.search-overlay, .search-box');
            searchOverlay = $('.search-overlay');
            searchBox = $('.search-box');
            searchInput = $('.search-input');
            searchResults = $('.search-results-inner');

            // Log if elements are found
            console.log('Search overlay found:', searchOverlay.length > 0);
            console.log('Search box found:', searchBox.length > 0);
            console.log('Search input found:', searchInput.length > 0);
            console.log('Search results found:', searchResults.length > 0);

            // Add close events
            $(document).on('keydown', function(e) {
                if (e.keyCode === KEYCODE.ESC && isSearchActive) {
                    closeSearch();
                }
            });

            searchOverlay.on('click', function() {
                console.log('Overlay clicked, closing search');
                closeSearch();
            });

            // Add input event for search
            $(document).on('input', '.search-input', function() {
                const query = $(this).val();
                console.log('Search input changed:', query);
                
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
        console.log('Opening search popup');
        if (isSearchActive) {
            console.log('Search already active, returning');
            return;
        }
        
        // Make sure elements are re-cached
        searchOverlay = $('.search-overlay');
        searchBox = $('.search-box');
        searchInput = $('.search-input');
        
        // Check if elements exist
        if (searchOverlay.length === 0 || searchBox.length === 0) {
            console.error('Search elements not found, attempting to add them');
            
            // Try to add search elements again
            if ($('.search-popup-template').length) {
                const searchPopupTemplate = $('.search-popup-template').first();
                $('body').append(searchPopupTemplate.html());
                searchPopupTemplate.remove();
                
                // Re-cache elements
                searchOverlay = $('.search-overlay');
                searchBox = $('.search-box');
                searchInput = $('.search-input');
            } else {
                console.error('Search popup template not found');
                return;
            }
        }
        
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
        console.log('Closing search popup');
        if (!isSearchActive) {
            console.log('Search not active, returning');
            return;
        }
        
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
        console.log('Performing search for:', query);
        
        // Re-cache search results element
        searchResults = $('.search-results-inner');
        
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
                console.log('Search response received:', response);
                if (response.success) {
                    displayResults(response.data);
                } else {
                    searchResults.html('<div class="search-no-results">Error: Could not fetch results</div>');
                }
            },
            error: function(error) {
                console.error('AJAX error:', error);
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
        
        console.log('Displaying', count, 'results');
        
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
    console.log('Search Products Elementor: Initializing');
    initSearch();
    
    // Backup initialization
    $(window).on('load', function() {
        console.log('Window loaded, reinitializing search');
        initSearch();
    });
    
    // Make functions available globally for debugging
    window.searchProductsElementor = {
        openSearch: openSearch,
        closeSearch: closeSearch
    };

})(jQuery); 