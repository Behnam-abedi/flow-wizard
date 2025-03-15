jQuery(document).ready(function($) {
    $('#live-search-input').on('keyup', function() {
        var query = $(this).val();
        
        if(query.length > 0) {
            $.ajax({
                url: liveSearch.ajax_url,
                method: 'GET',
                data: {
                    action: 'live_search',
                    q: query
                },
                success: function(response) {
                    var resultsContainer = $('#live-search-results');
                    resultsContainer.empty();
                    if(response.no_results) {
                        resultsContainer.html('<p>یافت نشد</p>');
                    } else {
                        $.each(response, function(index, item) {
                            resultsContainer.append('<div class="result-item"><a href="'+item.permalink+'">'+item.title+'</a> ('+item.type+')</div>');
                        });
                    }
                }
            });
        } else {
            $('#live-search-results').empty();
        }
    });
});
