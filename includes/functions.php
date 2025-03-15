<?php
function live_search_results() {
    $search_term = sanitize_text_field($_POST['search_term']);
    header('Content-Type: text/html; charset=UTF-8'); // حل مشکل کدگذاری
    check_ajax_referer('live_search_nonce', 'security'); // افزودن نانسی
    $results = array();
    
    // Search in posts
    $posts = new WP_Query(array(
        'post_type' => array('post', 'page', 'product'),
        's' => $search_term,
        'posts_per_page' => 10
    ));
    
    // Search in product categories
    $categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'name__like' => $search_term,
        'number' => 10
    ));
    
    // Combine results
    if ($posts->have_posts()) {
        while ($posts->have_posts()) {
            $posts->the_post();
            $results[] = array(
                'title' => get_the_title(),
                'url' => get_permalink(),
                'type' => get_post_type_object(get_post_type())->labels->singular_name
            );
        }
        wp_reset_postdata();
    }
    
    if (!empty($categories)) {
        foreach ($categories as $category) {
            $results[] = array(
                'title' => $category->name,
                'url' => get_term_link($category),
                'type' => 'دسته‌بندی محصول'
            );
        }
    }
    
    // Generate HTML output
    if (!empty($results)) {
        $output = '<div class="live-search-items">';
        foreach ($results as $result) {
            $output .= sprintf(
                '<a href="%s" class="live-search-item">
                    <span class="item-title">%s</span>
                    <span class="item-type">%s</span>
                </a>',
                esc_url($result['url']),
                esc_html($result['title']),
                esc_html($result['type'])
            );
        }
        $output .= '</div>';
    } else {
        $output = '<div class="live-search-no-results">یافت نشد</div>';
    }
    
    echo $output;
    wp_die();
};