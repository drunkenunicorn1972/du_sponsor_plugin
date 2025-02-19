<?php
/**
 * Sponsor Slider Widget
 */

use Elementor\Plugin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Du_Sponsor_Slider_Widget extends \Elementor\Widget_Base
{


    public function get_name()
    {
        return 'du_sponsor_slider';
    }

    public function get_title()
    {
        return __('Sponsor Slider', 'du-elem');
    }

    public function get_icon()
    {
        return 'eicon-slides';
    }

    public function get_keywords()
    {
        return ['DU', 'slider', 'responsive', 'sponsoren'];
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
                'label' => __('Sponsor Slider', 'du-elem'),
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

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $elem_id = $this->get_id();
        $sponsor_category = $settings['du_sponsor_category'];
?>
        <section class="du-sponsor-splide splide" id="splide-<?php echo $elem_id; ?>">
        <div class="du-splide__track splide__track">
        <ul class="splide__list">
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

                echo '<li class="du-sponsor-slide splide__slide du-sponsor-slide-id-' . $sponsor_id . '">';
                echo '<div class="du-slide-content">';
                echo '<a href="' . $sponsor_url . '"><img src="' . $sponsor_logo . '" alt="' . $sponsor_name . '"></a>';
                echo '</div>';
                echo '</li>';

            }

            // Reset post data to avoid affecting other queries
            wp_reset_postdata();
        }

        ?>
        </ul>
        </div>
        </section>
        <script>
            // document.addEventListener( 'DOMContentLoaded', function() {
            let elementId_<?php echo $elem_id; ?> = '#splide-<?php echo $elem_id; ?>';
            console.log('Starting slider ' + elementId_<?php echo $elem_id; ?>);
            // bind it to splide
            let slider_<?php echo $elem_id; ?> = new Splide(elementId_<?php echo $elem_id; ?>, {
                type: 'loop',
                autoplay: 'play',
                perPage: 5,
                perMove: 1,
                interval: 3000,
                pagination: false,
                autoWidth: true,
                rewind: true
            }).mount();
            // });
        </script>
        <?php
    }

    protected function content_template() {
        $elem_id = $this->get_id();
        ?>
        <script>
            let elementId_<?php echo $elem_id; ?> = '#splide-<?php echo $elem_id; ?>';
            slider_<?php echo $elem_id; ?>.refresh();
        </script>
        <?php
    }
}