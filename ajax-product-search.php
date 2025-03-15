<?php
/**
 * Plugin Name: Ajax Product Search Overlay
 * Plugin URI:  https://example.com
 * Description: افزونه‌ای برای جستجوی ایجکسی محصولات ووکامرس در یک باکس اسلایدی تمام‌عرض از پایین صفحه
 * Version:     1.0
 * Author:      Your Name
 * Author URI:  https://example.com
 * Text Domain: ajax-product-search
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // دسترسی مستقیم ممنوع
}

/**
 * کلاس اصلی افزونه
 */
class Ajax_Product_Search_Overlay {
    
    public function __construct() {
        // ثبت شورت‌کد
        add_shortcode( 'my_ajax_search', [ $this, 'render_search_icon' ] );
        
        // ثبت اسکریپت‌ها و استایل‌ها
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        
        // ثبت ایجکس
        add_action( 'wp_ajax_ajax_search_products', [ $this, 'ajax_search_products' ] );
        add_action( 'wp_ajax_nopriv_ajax_search_products', [ $this, 'ajax_search_products' ] );
    }
    
    /**
     * تابعی که شورت‌کد را برمی‌گرداند.
     * با قرار دادن [my_ajax_search] در هر بخشی از المنتور یا نوشته‌ها/برگه‌ها،
     * آیکون ذره‌بین و ساختار جستجو نمایش داده خواهد شد.
     */
    public function render_search_icon() {
        ob_start(); 
        ?>
        
        <!-- آیکون ذره‌بین -->
        <a href="#" id="my-ajax-search-icon" class="my-ajax-search-icon">
            <!-- می‌توانید از فونت‌آیکون یا SVG دلخواه استفاده کنید -->
            <svg width="24" height="24" viewBox="0 0 24 24">
                <path d="M10 2a8 8 0 105.3 14l4.4 4.4 1.4-1.4-4.4-4.4A8 8 0 0010 2zm0 2a6 6 0 110 12A6 6 0 0110 4z"/>
            </svg>
        </a>

        <!-- لایهٔ نیمه‌شفاف تیره -->
        <div id="my-ajax-search-overlay"></div>

        <!-- باکس جستجو که از پایین می‌آید -->
        <div id="my-ajax-search-box">
            <div class="my-ajax-search-content">
                <h2 class="search-title">جستجوی محصولات</h2>
                <p class="search-subtitle">برای دیدن محصولات تایپ کنید</p>
                
                <input 
                    type="text" 
                    id="my-ajax-search-input" 
                    placeholder="جستجوی محصولات..."
                />
                
                <!-- نمایش نتایج -->
                <div id="my-ajax-search-results"></div>
            </div>
        </div>
        
        <?php
        return ob_get_clean();
    }
    
    /**
     * بارگذاری اسکریپت‌ها و استایل‌های موردنیاز
     */
    public function enqueue_scripts() {
        // حتماً jQuery را فراخوانی کنید
        wp_enqueue_script( 'jquery' );
        
        // اسکریپت اصلی افزونه
        wp_register_script( 
            'ajax-product-search-script', 
            plugin_dir_url( __FILE__ ) . 'assets/js/ajax-product-search.js', 
            ['jquery'], 
            '1.0', 
            true 
        );
        
        // ارسال پارامترهای ایجکس به اسکریپت
        wp_localize_script( 'ajax-product-search-script', 'aps_ajax_params', [
            'ajax_url' => admin_url( 'admin-ajax.php' ), 
            'nonce'    => wp_create_nonce( 'aps_ajax_nonce' )
        ] );
        
        wp_enqueue_script( 'ajax-product-search-script' );
        
        // استایل اصلی افزونه
        wp_register_style( 
            'ajax-product-search-style', 
            plugin_dir_url( __FILE__ ) . 'assets/css/ajax-product-search.css', 
            [], 
            '1.0' 
        );
        wp_enqueue_style( 'ajax-product-search-style' );
    }
    
    /**
     * متد ایجکس برای جستجوی محصولات
     */
    public function ajax_search_products() {
        check_ajax_referer( 'aps_ajax_nonce', 'security' );
        
        // اگر ووکامرس نصب نیست، فقط خروج
        if ( ! class_exists( 'WooCommerce' ) ) {
            wp_send_json_error( 'WooCommerce is not active.' );
        }
        
        $search_term = isset( $_POST['search_term'] ) ? sanitize_text_field( $_POST['search_term'] ) : '';
        
        if ( empty( $search_term ) ) {
            wp_send_json_success( [] ); // اگر چیزی تایپ نشده، نتیجه خالی
        }
        
        // جستجو در محصولات
        $args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            's'              => $search_term,
            'posts_per_page' => 20
        ];
        
        $query = new WP_Query( $args );
        
        $results = [];
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                
                $product_id   = get_the_ID();
                $product      = wc_get_product( $product_id );
                $product_name = get_the_title();
                $product_link = get_permalink();
                $product_img  = get_the_post_thumbnail_url( $product_id, 'thumbnail' );
                
                $results[] = [
                    'id'   => $product_id,
                    'name' => $product_name,
                    'link' => $product_link,
                    'img'  => $product_img
                ];
            }
            wp_reset_postdata();
        }
        
        wp_send_json_success( $results );
    }
}

new Ajax_Product_Search_Overlay();
