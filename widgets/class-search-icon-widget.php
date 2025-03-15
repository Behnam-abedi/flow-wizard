<?php
/**
 * Search Icon Widget for Elementor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Search Icon Widget
 * 
 * IMPORTANT: This file is only included AFTER Elementor is fully loaded
 */
class Elementor_Search_Icon_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'search_icon';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('AJAX Search Icon', 'elementor-ajax-search');
    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-search';
    }

    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['eaps-elements'];
    }

    /**
     * Register widget controls.
     */
    protected function _register_controls() {
        // For Elementor 3.5.0 and higher
        $controls_method = method_exists($this, 'register_controls') ? 'register_controls' : '_register_controls';
        
        // Icon Settings Section
        $this->start_controls_section(
            'section_icon',
            [
                'label' => __('Icon Settings', 'elementor-ajax-search'),
            ]
        );

        $this->add_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'elementor-ajax-search'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 24,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eaps-search-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'elementor-ajax-search'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .eaps-search-icon i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Search Box Settings Section
        $this->start_controls_section(
            'section_search_box',
            [
                'label' => __('Search Box Settings', 'elementor-ajax-search'),
            ]
        );

        $this->add_control(
            'search_placeholder',
            [
                'label' => __('Search Placeholder', 'elementor-ajax-search'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('جستجوی محصولات', 'elementor-ajax-search'),
            ]
        );

        $this->add_control(
            'search_subtitle',
            [
                'label' => __('Search Subtitle', 'elementor-ajax-search'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('برای دیدن محصولات تایپ کنید', 'elementor-ajax-search'),
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label' => __('Overlay Color', 'elementor-ajax-search'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.5)',
                'selectors' => [
                    '.eaps-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="eaps-search-container">
            <div class="eaps-search-icon">
                <i class="fa fa-search"></i>
            </div>
            
            <div class="eaps-overlay"></div>
            
            <div class="eaps-search-box">
                <div class="eaps-search-box-inner">
                    <div class="eaps-search-form">
                        <input type="text" class="eaps-search-input" placeholder="<?php echo esc_attr($settings['search_placeholder']); ?>">
                        <div class="eaps-search-subtitle"><?php echo esc_html($settings['search_subtitle']); ?></div>
                    </div>
                    <div class="eaps-search-results">
                        <div class="eaps-results-container"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}