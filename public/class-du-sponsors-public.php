<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.drunken-unicorn.eu
 * @since      1.0.0
 *
 * @package    Du_Sponsors
 * @subpackage Du_Sponsors/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Du_Sponsors
 * @subpackage Du_Sponsors/public
 * @author     Drunken Unicorn <contact@drunken-unicorn.eu>
 */
class Du_Sponsors_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Du_Sponsors_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Du_Sponsors_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/du-sponsors-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Du_Sponsors_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Du_Sponsors_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/du-sponsors-public.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Registers a custom post type for Sponsors.
     *
     * This function defines the "du_sponsor" post type with its associated labels,
     * arguments, and configuration options. This post type is intended to manage
     * sponsor-related content, allowing the site administrator to add, edit, and
     * display such entries in a structured format.
     *
     * @return void
     */
    function register_sponsor_post_type() {

        $labels = array(
            'name'               => __('Sponsors','du-sponsors'),
            'singular_name'      => __('Sponsor','du-sponsors'),
            'menu_name'          => __('Sponsors','du-sponsors'),
            'name_admin_bar'     => __('Sponsor','du-sponsors'),
            'add_new'            => __('Add New','du-sponsors'),
            'add_new_item'       => __('Add New Sponsor','du-sponsors'),
            'new_item'           => __('New Sponsor','du-sponsors'),
            'edit_item'          => __('Edit Sponsor','du-sponsors'),
            'view_item'          => __('View Sponsor','du-sponsors'),
            'all_items'          => __('All Sponsors','du-sponsors'),
            'search_items'       => __('Search Sponsors','du-sponsors'),
            'not_found'          => __('No sponsors found.','du-sponsors'),
            'not_found_in_trash' => __('No sponsors found in Trash.','du-sponsors'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'show_in_menu'       => true,
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-megaphone', // Custom icon for Sponsor
            'supports'           => array('title', 'editor', 'thumbnail'),
            'has_archive'        => true,
            'rewrite'            => array('slug' => 'sponsors'),
            'capability_type'    => 'post',
        );

        register_post_type('du_sponsor', $args);
    }

    /**
     * Registers a custom taxonomy for organizing sponsors into categories.
     *
     * This function defines a hierarchical taxonomy with labels and
     * additional arguments for customizing its behavior in the WordPress admin panel.
     * The taxonomy is associated with the custom post type 'du_sponsor'.
     *
     * @return void
     */
    function register_sponsor_taxonomy() {
        $labels = array(
            'name'              => __('Sponsor Categories','du-sponsors'),
            'singular_name'     => __('Sponsor Category','du-sponsors'),
            'search_items'      => __('Search Categories','du-sponsors'),
            'all_items'         => __('All Categories','du-sponsors'),
            'parent_item'       => __('Parent Category','du-sponsors'),
            'parent_item_colon' => __('Parent Category:','du-sponsors'),
            'edit_item'         => __('Edit Category','du-sponsors'),
            'update_item'       => __('Update Category','du-sponsors'),
            'add_new_item'      => __('Add New Category','du-sponsors'),
            'new_item_name'     => __('New Category Name','du-sponsors'),
            'menu_name'         => __('Categories','du-sponsors'),
        );

        $args = array(
            'hierarchical'      => true, // Categories (like posts)
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'sponsor-category'),
        );

        register_taxonomy('sponsor_category', array('du_sponsor'), $args);
    }

    /**
     * Registers a custom meta box for the "Sponsor Details".
     *
     * This function adds a meta box to the 'du_sponsor' post type
     * for managing sponsor-related metadata. The meta box is displayed
     * in the 'normal' context and is assigned a high priority.
     *
     * @return void
     */
    function add_sponsor_meta_boxes() {
        add_meta_box(
            'sponsor_details_meta_box',     // Meta box ID
            __('Sponsor Details', 'du-sponsors'),              // Meta box title
            array($this, 'render_sponsor_details_meta_box'), // Callback function
            'du_sponsor',                      // Post type
            'normal',                       // Context (normal, side, etc.)
            'high'                          // Priority
        );
    }

    /**
     * Renders the "Sponsor Details" meta box.
     *
     * This function generates the HTML form for the sponsor details meta box,
     * allowing users to enter or update sponsor metadata such as website URL,
     * contact email, contact address, phone number, and sponsor logo.
     * A nonce is included for security purposes.
     *
     * @param WP_Post $post The current post object.
     * @return void
     */
    function render_sponsor_details_meta_box($post) {
        // Retrieve current meta data values
        $website = get_post_meta($post->ID, '_sponsor_website', true);
        $contact_email = get_post_meta($post->ID, '_sponsor_contact_email', true);
        $contact_address = get_post_meta($post->ID, '_sponsor_contact_address', true);
        $phone_number = get_post_meta($post->ID, '_sponsor_phone_number', true);
        $logo_id = get_post_meta($post->ID, '_sponsor_logo_id', true);

        // Add nonce for security
        wp_nonce_field('save_sponsor_details', 'sponsor_details_nonce');

        ?>
        <p>
            <label for="sponsor_website"><?php _e('Website','du-sponsors'); ?>:</label><br>
            <input type="url" id="sponsor_website" name="sponsor_website" value="<?php echo esc_attr($website); ?>" style="width: 100%;">
        </p>
        <p>
            <label for="sponsor_contact_email"><?php _e('Contact Email','du-sponsors'); ?>:</label><br>
            <input type="email" id="sponsor_contact_email" name="sponsor_contact_email" value="<?php echo esc_attr($contact_email); ?>" style="width: 100%;">
        </p>
        <p>
            <label for="sponsor_contact_address"><?php _e('Contact Address', 'du-sponsors'); ?>:</label><br>
            <input type="text" id="sponsor_contact_address" name="sponsor_contact_address" value="<?php echo esc_attr($contact_address); ?>" style="width: 100%;">
        </p>
        <p>
            <label for="sponsor_phone_number"><?php _e('Phone Number','du-sponsors'); ?>:</label><br>
            <input type="text" id="sponsor_phone_number" name="sponsor_phone_number" value="<?php echo esc_attr($phone_number); ?>" style="width: 100%;">
        </p>
        <p>
            <label for="sponsor_logo"><?php _e('Sponsor Logo','du-sponsors'); ?>:</label><br>
            <input type="hidden" id="sponsor_logo_id" name="sponsor_logo_id" value="<?php echo esc_attr($logo_id); ?>">
            <button type="button" class="upload-image-button button"><?php _e('Upload Logo','du-sponsors'); ?></button>
            <button type="button" class="remove-image-button button" style="display: <?php echo $logo_id ? 'inline-block' : 'none'; ?>;"><?php _e('Remove Logo','du-sponsors'); ?></button>
            <div class="image-preview" style="margin-top: 10px;">
                <?php if ($logo_id) : ?>
                    <img src="<?php echo wp_get_attachment_image_url($logo_id, 'thumbnail'); ?>" style="max-width: 100px;" alt="<?php _e('Preview image','du-sponsors'); ?>">
                <?php endif; ?>
            </div>
        </p>
        <p>
            <label for="sponsor_advert1"><?php _e('Sponsor Advert 1','du-sponsors'); ?>:</label><br>
            <input type="hidden" id="sponsor_advert1" name="sponsor_advert1" value="<?php echo esc_attr($advert1_id); ?>">
            <button type="button" class="upload-image-button button"><?php _e('Upload Advert 1','du-sponsors'); ?></button>
            <button type="button" class="remove-image-button button" style="display: <?php echo $logo_id ? 'inline-block' : 'none'; ?>;"><?php _e('Remove Advert 1','du-sponsors'); ?></button>
        <div class="image-preview" style="margin-top: 10px;">
            <?php if ($advert1_id) : ?>
                <img src="<?php echo wp_get_attachment_image_url($advert1_id, 'thumbnail'); ?>" style="max-width: 100px;" alt="<?php _e('Preview image','du-sponsors'); ?>">
            <?php endif; ?>
        </div>
        </p>
        <p>
            <label for="sponsor_advert2"><?php _e('Sponsor Advert 2','du-sponsors'); ?>:</label><br>
            <input type="hidden" id="sponsor_advert2" name="sponsor_advert2" value="<?php echo esc_attr($advert2_id); ?>">
            <button type="button" class="upload-image-button button"><?php _e('Upload Advert 2','du-sponsors'); ?></button>
            <button type="button" class="remove-image-button button" style="display: <?php echo $logo_id ? 'inline-block' : 'none'; ?>;"><?php _e('Remove Advert 2','du-sponsors'); ?></button>
        <div class="image-preview" style="margin-top: 10px;">
            <?php if ($advert2_id) : ?>
                <img src="<?php echo wp_get_attachment_image_url($advert2_id, 'thumbnail'); ?>" style="max-width: 100px;" alt="<?php _e('Preview image','du-sponsors'); ?>">
            <?php endif; ?>
        </div>
        </p>
        <script>
            jQuery(document).ready(function($) {
                let mediaUploader;

                $('.upload-image-button').on('click', function(e) {
                    e.preventDefault();
                    if (mediaUploader) {
                        mediaUploader.open();
                        return;
                    }
                    mediaUploader = wp.media({
                        title: '<?php _e('Choose Image','du-sponsors'); ?>',
                        button: { text: '<?php _e('Use this image','du-sponsors'); ?>' },
                        multiple: false
                    });
                    mediaUploader.on('select', function() {
                        const attachment = mediaUploader.state().get('selection').first().toJSON();
                        $('#sponsor_logo_id').val(attachment.id);
                        $('.image-preview').html('<img src="' + attachment.url + '" style="max-width: 100px;">');
                        $('.remove-image-button').show();
                    });
                    mediaUploader.open();
                });

                $('.remove-image-button').on('click', function() {
                    $('#sponsor_logo_id').val('');
                    $('.image-preview').html('');
                    $(this).hide();
                });
            });
        </script>
        <?php
    }

    /**
     * Saves the sponsor details metadata when a post is saved.
     *
     * This function validates the nonce, checks user permissions, and verifies
     * that the request is not an autosave before sanitizing and saving the
     * provided sponsor metadata fields to the post's meta table.
     *
     * @param int $post_id The ID of the post being saved.
     * @return void
     */
    function save_sponsor_details_meta_box($post_id) {
        // Verify nonce
        if (!isset($_POST['sponsor_details_nonce']) || !wp_verify_nonce($_POST['sponsor_details_nonce'], 'save_sponsor_details')) {
            return;
        }

        // Check for autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Sanitize and save meta data
        if (isset($_POST['sponsor_website'])) {
            update_post_meta($post_id, '_sponsor_website', sanitize_text_field($_POST['sponsor_website']));
        }

        if (isset($_POST['sponsor_contact_email'])) {
            update_post_meta($post_id, '_sponsor_contact_email', sanitize_email($_POST['sponsor_contact_email']));
        }

        if (isset($_POST['sponsor_contact_address'])) {
            update_post_meta($post_id, '_sponsor_contact_address', sanitize_text_field($_POST['sponsor_contact_address']));
        }

        if (isset($_POST['sponsor_phone_number'])) {
            update_post_meta($post_id, '_sponsor_phone_number', sanitize_text_field($_POST['sponsor_phone_number']));
        }

        if (isset($_POST['sponsor_logo_id'])) {
            update_post_meta($post_id, '_sponsor_logo_id', intval($_POST['sponsor_logo_id']));
        }

        if (isset($_POST['sponsor_advert1'])) {
            update_post_meta($post_id, '_sponsor_advert1', intval($_POST['sponsor_advert1']));
        }

        if (isset($_POST['sponsor_advert2'])) {
            update_post_meta($post_id, '_sponsor_advert2', intval($_POST['sponsor_advert2']));
        }

    }

    /**
     * Customizes the columns displayed in the sponsor post type list table.
     *
     * This function modifies the list of columns in the admin table for sponsors
     * by adding custom columns for 'Website' and 'Contact Email'.
     *
     * @param array $columns The existing columns in the post type table.
     * @return array The modified columns including custom sponsor-related columns.
     */
    function set_custom_sponsor_columns($columns) {
        $columns['sponsor_website'] = __('Website','du-sponsors');
        $columns['sponsor_contact_email'] = __('Contact Email','du-sponsors');
        return $columns;
    }

    /**
     * Populates custom columns with sponsor-related data in the admin post list table.
     *
     * This function outputs the content for specific custom columns ('sponsor_website'
     * and 'sponsor_contact_email') in the admin area for the registered post type.
     * It retrieves metadata values associated with a sponsor and sanitizes the output
     * for display.
     *
     * @param string $column The name of the current column being rendered.
     * @param int $post_id The ID of the current post being processed.
     *
     * @return void
     */
    function custom_sponsor_column_content($column, $post_id) {
        if ($column == 'sponsor_website') {
            echo esc_url(get_post_meta($post_id, '_sponsor_website', true));
        } elseif ($column == 'sponsor_contact_email') {
            echo esc_html(get_post_meta($post_id, '_sponsor_contact_email', true));
        }
    }


    /**
     * Adds a custom category for Elementor widgets.
     *
     * This function registers a new widget category named 'DU Extra Elements'
     * with the specified title and icon to the Elementor widget manager.
     *
     * @param object $elements_manager An instance of the Elementor elements manager,
     *                                  used to register new widget categories.
     * @return void
     */
    function add_elementor_widget_categories($elements_manager ) {

        $elements_manager->add_category(
            'du_category',
            [
                'title' => esc_html__( 'DU Sponsoren', 'du-sponsors' ),
                'icon' => 'fa fa-sack-dollar',
            ]
        );

    }

    function register_du_sponsor_widgets( $widgets_manager ): void {

        require_once( __DIR__ . '/../widgets/class-du-sponsor-slider.php' );
        require_once( __DIR__ . '/../widgets/class-du-sponsor-grid.php' );

        $widgets_manager->register( new \Du_Sponsor_Slider_Widget() );
        $widgets_manager->register( new \Du_Sponsor_Grid_Widget() );

    }

}
