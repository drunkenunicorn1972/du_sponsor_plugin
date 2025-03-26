<?php
/**
 * Sponsor Ad Rotator Widget
 */

use Elementor\Plugin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Du_Sponsor_AdRotator_Widget extends \Elementor\Widget_Base
{


    public function get_name()
    {
        return 'du_sponsor_adrotator';
    }

    public function get_title()
    {
        return __('Sponsor Ad Rotator', 'du-elem');
    }

    public function get_icon()
    {
        return 'eicon-price-table';
    }

    public function get_keywords()
    {
        return ['DU', 'advert', 'responsive', 'sponsoren'];
    }

    public function get_categories()
    {
        return ['du_category'];
    }


    protected function _register_controls()
    {

        $this->start_controls_section(
            'DU_sponsor_adrotator',
            [
                'label' => __('Sponsor Ad Rotator', 'du-elem'),
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
            'du_sponsor_ad_rotation',
            [
                'type' => \Elementor\Controls_Manager::SELECT,
                'label' => esc_html__( 'Sponsor Ad Rotation', 'du-sponsors' ),
                'options' => array(
                    'HOR' => __('Horizontal','du-sponsors'),
                    'VER' => __('Vertical','du-sponsors'),
                ),
                'default' => 'VER',
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $elem_id = $this->get_id();
        $sponsor_category = $settings['du_sponsor_category'];
        $ad_rotation = $settings['du_sponsor_ad_rotation'];
        ?>
        <style>.du-sponsor-grid .du-sponsor-item a img { !important; width: auto !important; object-fit: contain; }</style>
        <section class="du-sponsor-adrotator" id="sponsor-adrotator-<?php echo $elem_id; ?>">
                <?php

                // get all posts with post_type=du_sponsor that have the selected category
                // Arguments for WP_Query
                if ($ad_rotation == 'HOR') { $meta_key = '_sponsor_image3'; } else { $meta_key = '_sponsor_image2'; }
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
                    // Retrieve all posts (set any limit you want)
                    'meta_query'     => [
                        [
                            'key'     => $meta_key,   // Meta field name
                            'compare' => 'EXISTS',  // Ensure the meta field exists
                        ],
                        [
                            'key'     => $meta_key,
                            'value'   => '0',        // Ensure it's not empty
                            'compare' => '!=',      // Not equal to an empty value
                        ],
                    ],
                    'post_status' => 'publish', // Only get published posts
                    'posts_per_page' => 1,     // Retrieve all matching posts (use a number to limit)
                    'orderby' => 'rand',    // Random sorting for surprise effect
                );

                // Create custom query
                $query = new WP_Query($args);

                // Check if posts were found
                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post(); // Set the global $post variable

                        $sponsor_ad_data = wp_get_attachment_image_src(get_post_meta(get_the_ID(), $meta_key, true), 'large');
                        if (!empty($sponsor_ad_data[0])) {
                            $sponsor_ad_url = $sponsor_ad_data[0];
                        } else {
                            $sponsor_ad_url = '#';
                        }
                        $sponsor_name = get_the_title();
                        if ($ad_rotation == 'HOR') {
                            $sponsor_url = get_post_meta(get_the_ID(), '_sponsor_adurl2', true);
                        } else {
                            $sponsor_url = get_post_meta(get_the_ID(), '_sponsor_adurl1', true);
                        }
                        $sponsor_id = get_the_ID();

                        echo '<div class="du-sponsor-item du-sponsor-item-id-' . $sponsor_id . '">';
                        echo '<a href="' . $sponsor_url . '" target="_blank"><img src="' . $sponsor_ad_url . '" alt="' . $sponsor_name . '"></a>';
                        echo '</div>';

                    }

                    // Reset post data to avoid affecting other queries
                    wp_reset_postdata();
                }

                ?>
        </section>
        <?php
    }

    protected function content_template() {
        $elem_id = $this->get_id();
    }
}