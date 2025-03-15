<?php
/**
 * Search Icon Widget
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Search Icon Widget Class
 */
class Search_Icon_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'search_icon';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return esc_html__('Search Icon', 'search-products-elementor');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-search';
    }

    /**
     * Get widget categories
     */
    public function get_categories() {
        return ['general'];
    }

    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return ['search', 'products', 'ajax', 'woocommerce'];
    }

    /**
     * Register widget controls
     */
    protected function _register_controls() {
        // Icon Section
        $this->start_controls_section(
            'section_icon',
            [
                'label' => esc_html__('Icon Settings', 'search-products-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'icon_size',
            [
                'label' => esc_html__('Icon Size', 'search-products-elementor'),
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
                    '{{WRAPPER}} .search-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Icon Color', 'search-products-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .search-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => esc_html__('Icon Hover Color', 'search-products-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#555555',
                'selectors' => [
                    '{{WRAPPER}} .search-icon:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Search Box Section
        $this->start_controls_section(
            'section_search_box',
            [
                'label' => esc_html__('Search Box Settings', 'search-products-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'search_placeholder',
            [
                'label' => esc_html__('Search Placeholder', 'search-products-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Search Products', 'search-products-elementor'),
            ]
        );

        $this->add_control(
            'search_subtitle',
            [
                'label' => esc_html__('Search Subtitle', 'search-products-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Type to see products', 'search-products-elementor'),
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label' => esc_html__('Overlay Color', 'search-products-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.5)',
                'selectors' => [
                    '.search-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'search_box_background',
            [
                'label' => esc_html__('Search Box Background', 'search-products-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '.search-box' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Input Style Section
        $this->start_controls_section(
            'section_input_style',
            [
                'label' => esc_html__('Input Style', 'search-products-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'input_background',
            [
                'label' => esc_html__('Input Background', 'search-products-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f5f5f5',
                'selectors' => [
                    '.search-input' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_text_color',
            [
                'label' => esc_html__('Input Text Color', 'search-products-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '.search-input' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'input_typography',
                'selector' => '.search-input',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'input_border',
                'selector' => '.search-input',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'input_border_radius',
            [
                'label' => esc_html__('Border Radius', 'search-products-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '.search-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '8',
                    'right' => '8',
                    'bottom' => '8',
                    'left' => '8',
                    'unit' => 'px',
                    'isLinked' => true,
                ],
            ]
        );

        $this->add_responsive_control(
            'input_padding',
            [
                'label' => esc_html__('Padding', 'search-products-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '.search-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '15',
                    'right' => '20',
                    'bottom' => '15',
                    'left' => '20',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="search-icon-widget">
            <div class="search-icon-container">
                <i class="search-icon eicon-search"></i>
            </div>

            <!-- Search popup HTML that will be rendered in the footer -->
            <div class="search-popup-template" style="display:none;">
                <div class="search-overlay"></div>
                <div class="search-box">
                    <div class="search-box-inner">
                        <div class="search-input-container">
                            <input type="text" class="search-input" placeholder="<?php echo esc_attr($settings['search_placeholder']); ?>">
                            <div class="search-subtitle"><?php echo esc_html($settings['search_subtitle']); ?></div>
                        </div>
                        <div class="search-results-container">
                            <div class="search-results-inner">
                                <!-- Results will be loaded here via AJAX -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render widget output in the editor
     */
    protected function _content_template() {
        ?>
        <div class="search-icon-widget">
            <div class="search-icon-container">
                <i class="search-icon eicon-search"></i>
            </div>
        </div>
        <?php
    }
} 