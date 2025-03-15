<?php
/*
Plugin Name: جستجوی زنده
Plugin URI: http://example.com/
Description: افزونه جستجوی زنده برای پست‌ها، برگه‌ها، محصولات و دسته‌بندی محصولات.
Version: 1.0
Author: نام شما
Author URI: http://example.com/
Text Domain: live-search
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // جلوگیری از دسترسی مستقیم
}

class Live_Search_Plugin {
    
    public function __construct() {
        // ثبت شورتکد [live_search] برای نمایش فرم جستجو
        add_shortcode('live_search', array($this, 'render_live_search'));
        // افزودن استایل و اسکریپت‌ها
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        // ثبت اکشن‌های AJAX برای کاربران لاگین کرده و نکرده
        add_action('wp_ajax_live_search', array($this, 'handle_live_search'));
        add_action('wp_ajax_nopriv_live_search', array($this, 'handle_live_search'));
    }
    
    // بارگذاری استایل و اسکریپت‌ها
    public function enqueue_assets() {
        wp_enqueue_style('live-search-style', plugin_dir_url(__FILE__) . 'css/live-search-style.css');
        wp_enqueue_script('live-search-script', plugin_dir_url(__FILE__) . 'js/live-search.js', array('jquery'), '1.0', true);
        wp_localize_script('live-search-script', 'liveSearch', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));
    }
    
    // خروجی شورتکد
    public function render_live_search() {
        ob_start();
        ?>
        <div id="live-search-container">
            <input type="text" id="live-search-input" placeholder="جستجو..." />
            <div id="live-search-results"></div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    // مدیریت درخواست AJAX برای جستجو
    public function handle_live_search() {
        $search_query = isset($_GET['q']) ? sanitize_text_field($_GET['q']) : '';
        
        if ( empty($search_query) ) {
            wp_send_json([]);
            wp_die();
        }
        
        $results = array();
        
        // جستجو در پست‌ها، برگه‌ها و محصولات
        $args = array(
            's'              => $search_query,
            'post_type'      => array('post', 'page', 'product'),
            'posts_per_page' => 10
        );
        $query = new WP_Query($args);
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $results[] = array(
                    'type'      => get_post_type(),
                    'title'     => get_the_title(),
                    'permalink' => get_permalink()
                );
            }
            wp_reset_postdata();
        }
        
        // جستجو در دسته‌بندی‌های محصولات (در صورت نصب WooCommerce)
        if ( post_type_exists('product') ) {
            $terms = get_terms(array(
                'taxonomy'   => 'product_cat',
                'name__like' => $search_query,
                'hide_empty' => true,
                'number'     => 10
            ));
            if ( ! is_wp_error($terms) && ! empty($terms) ) {
                foreach ( $terms as $term ) {
                    $results[] = array(
                        'type'      => 'product_cat',
                        'title'     => $term->name,
                        'permalink' => get_term_link($term)
                    );
                }
            }
        }
        
        // اگر نتیجه‌ای پیدا نشد
        if ( empty($results) ) {
            wp_send_json(array('no_results' => true));
        } else {
            wp_send_json($results);
        }
        wp_die();
    }
}

new Live_Search_Plugin();
