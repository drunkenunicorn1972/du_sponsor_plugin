<?php
/**
 * Sponsor Slider Widget
 */

use Elementor\Plugin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Du_Sponsor_Grid_Widget extends \Elementor\Widget_Base
{


    public function get_name()
    {
        return 'du_sponsor_grid';
    }

    public function get_title()
    {
        return __('Sponsor Grid', 'du-elem');
    }

    public function get_icon()
    {
        return 'eicon-gallery-grid';
    }

    public function get_keywords()
    {
        return ['DU', 'grid', 'responsive', 'sponsoren'];
    }

    public function get_categories()
    {
        return ['du_category'];
    }


    protected function _register_controls()
    {

        $this->start_controls_section(
            'DU_event',
            [
                'label' => __('Sponsor Grid', 'du-elem'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // get sponsor categories
        $sponsor_categories = get_terms(array(
            'taxonomy' => 'sponsor_category', // Replace with your custom taxonomy slug
            'hide_empty' => false,               // Show empty categories (set to true to hide empty categories)
        ));

        if (!is_wp_error($sponsor_categories) && !empty($sponsor_categories)) {
            $sponsor_category_options = array('0' => __('All Categories', 'du-sponsors'));
            foreach ($sponsor_categories as $category) {
                $sponsor_category_options[$category->term_id] = $category->name;
            }
        }

        $this->add_control(
            'du_sponsor_category',
            [
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label' => esc_html__( 'Sponsor Category', 'du-sponsors' ),
                'options' => $sponsor_category_options,
                'label_block' => true,
                'multiple' => true,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'du_sponsor_logo_height',
            [
                'type' => \Elementor\Controls_Manager::SELECT,
                'label' => esc_html__( 'Sponsor Logo Height', 'du-sponsors' ),
                'options' => array(
                    '100' => __('Large','du-sponsors'),
                    '75' => __('Medium','du-sponsors'),
                    '50' => __('Small','du-sponsors'),
                ),
                'default' => '75',
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $elem_id = $this->get_id();
        $sponsor_category = $settings['du_sponsor_category'];
        $logo_height = $settings['du_sponsor_logo_height'];
        ?>
        <style>.du-sponsor-grid .du-sponsor-item a img { height: <?php echo $logo_height; ?>px !important; width: auto !important; object-fit: contain; }</style>
        <section class="du-sponsor-grid" id="sponsor-grid-<?php echo $elem_id; ?>">
                <div class="sponsor-grid">
                    <?php

                    // get all posts with post_type=du_sponsor that have the selected category
                    // Arguments for WP_Query
                    $args = array(
                        'post_type' => 'du_sponsor', // Custom post type
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'sponsor_category', // Replace with your taxonomy slug
                                'field'    => 'id',                // Whether you're checking by 'slug', 'id', or 'name'
                                'terms'    => $sponsor_category,   // Replace with the term slug you want to filter by
                                'operator' => 'IN',                  // Use 'IN' to match any. Use 'AND' to match all.
                            ),
                        ),
                        'post_status' => 'publish', // Only get published posts
                        'posts_per_page' => -1,     // Retrieve all matching posts (use a number to limit)
                        'orderby' => 'rand',    // Random sorting for surprise effect
                    );

                    // Create custom query
                    $query = new WP_Query($args);

                    // Check if posts were found
                    if ($query->have_posts()) {
                        while ($query->have_posts()) {
                            $query->the_post(); // Set the global $post variable

                            $sponsor_logo_data = wp_get_attachment_image_src(get_post_meta(get_the_ID(), '_sponsor_logo_id', true), 'medium');
                            $sponsor_logo = $sponsor_logo_data[0];
                            $sponsor_name = get_the_title();
                            $sponsor_url = get_permalink();
                            $sponsor_id = get_the_ID();

                            echo '<div class="du-sponsor-item du-sponsor-item-id-' . $sponsor_id . '">';
                            echo '<a href="' . $sponsor_url . '"><img src="' . $sponsor_logo . '" alt="' . $sponsor_name . '"></a>';
                            echo '</div>';

                        }

                        // Reset post data to avoid affecting other queries
                        wp_reset_postdata();
                    }

                    ?>
                </div>
        </section>
        <?php
    }

    protected function content_template() {
        $elem_id = $this->get_id();
    }
}