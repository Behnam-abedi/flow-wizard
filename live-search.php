// اصلاح قسمت enqueue scripts
function live_search_scripts() {
    // فقط در صفحاتی که شورتکد وجود دارد
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'live_search')) {
        
        // استایل‌ها
        wp_enqueue_style(
            'live-search-style', 
            plugins_url('assets/css/style.css', __FILE__),
            [],
            filemtime(plugin_dir_path(__FILE__) . 'assets/css/style.css') // جلوگیری از کش
        );
        
        // اسکریپت‌ها
        wp_enqueue_script(
            'live-search-script',
            plugins_url('assets/js/script.js', __FILE__),
            ['jquery'],
            filemtime(plugin_dir_path(__FILE__) . 'assets/js/script.js'),
            true
        );
        
        // انتقال متغیرها
        wp_localize_script('live-search-script', 'live_search_obj', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('live_search_nonce')
        ]);
    }
}