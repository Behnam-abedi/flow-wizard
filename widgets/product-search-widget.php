<?php
class Product_Search_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'product_search';
    }

    public function get_title() {
        return 'Product Search';
    }

    public function get_icon() {
        return 'eicon-search';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Content',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'placeholder_text',
            [
                'label' => 'Placeholder Text',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'جستجوی محصولات',
            ]
        );

        $this->add_control(
            'sub_text',
            [
                'label' => 'Sub Text',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'برای دیدن محصولات تایپ کنید',
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => 'Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label' => 'Overlay Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.8)',
            ]
        );

        $this->add_control(
            'search_box_bg',
            [
                'label' => 'Search Box Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="advanced-product-search-widget">
            <div class="search-trigger">
                <i class="eicon-search"></i>
            </div>
            <div class="search-overlay">
                <div class="search-container">
                    <div class="search-box">
                        <div class="search-header">
                            <input type="text" class="search-input" placeholder="<?php echo esc_attr($settings['placeholder_text']); ?>">
                            <div class="search-close">×</div>
                        </div>
                        <div class="search-subtitle"><?php echo esc_html($settings['sub_text']); ?></div>
                        <div class="search-results">
                            <div class="results-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} 