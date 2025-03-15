<?php
function live_search_results() {
    try {
        // بررسی nonce
        if (!check_ajax_referer('live_search_nonce', 'security', false)) {
            throw new Exception('خطای امنیتی!');
        }

        // دریافت و اعتبارسنجی عبارت جستجو
        $search_term = isset($_POST['search_term']) ? sanitize_text_field($_POST['search_term']) : '';
        
        // لاگ برای دیباگ
        error_log('جستجوی دریافت شده: ' . $search_term);

        if (empty($search_term) || strlen($search_term) < 2) {
            throw new Exception('حداقل ۲ کاراکتر نیاز است');
        }

        // جستجو در انواع محتوا
        $results = [];
        
        // 1. پست‌ها و صفحات
        $posts = get_posts([
            'post_type' => ['post', 'page', 'product'],
            's' => $search_term,
            'posts_per_page' => 10,
            'post_status' => 'publish'
        ]);
        
        foreach ($posts as $post) {
            $results[] = [
                'title' => $post->post_title,
                'url' => get_permalink($post->ID),
                'type' => get_post_type_object($post->post_type)->labels->singular_name
            ];
        }

        // 2. دسته‌بندی محصولات
        $categories = get_terms([
            'taxonomy' => 'product_cat',
            'name__like' => $search_term,
            'hide_empty' => false,
            'number' => 5
        ]);
        
        if (!is_wp_error($categories)) {
            foreach ($categories as $cat) {
                $results[] = [
                    'title' => $cat->name,
                    'url' => get_term_link($cat),
                    'type' => 'دسته‌بندی'
                ];
            }
        }

        // تولید خروجی
        if (empty($results)) {
            echo '<div class="live-search-no-results">نتیجه‌ای یافت نشد</div>';
        } else {
            echo '<div class="live-search-items">';
            foreach ($results as $item) {
                printf(
                    '<a href="%s" class="live-search-item">
                        <span class="item-title">%s</span>
                        <span class="item-type %s">%s</span>
                    </a>',
                    esc_url($item['url']),
                    esc_html($item['title']),
                    sanitize_html_class($item['type']),
                    esc_html($item['type'])
                );
            }
            echo '</div>';
        }

    } catch (Exception $e) {
        echo '<div class="live-search-error">' . $e->getMessage() . '</div>';
    }
    
    wp_die();
}