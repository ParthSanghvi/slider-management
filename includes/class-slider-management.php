<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://profiles.wordpress.org/parthsanghvi/
 * @since      1.0.0
 *
 * @package    Slider_Management
 * @subpackage Slider_Management/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Slider_Management
 * @subpackage Slider_Management/includes
 * @author     Parth <parthsanghvi2811@gmail.com>
 */
class Slider_Management {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Slider_Management_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SLIDER_MANAGEMENT_VERSION' ) ) {
			$this->version = SLIDER_MANAGEMENT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'slider-management';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->register_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Slider_Management_Loader. Orchestrates the hooks of the plugin.
	 * - Slider_Management_i18n. Defines internationalization functionality.
	 * - Slider_Management_Admin. Defines all hooks for the admin area.
	 * - Slider_Management_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-slider-management-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-slider-management-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-slider-management-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-slider-management-public.php';

		$this->loader = new Slider_Management_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Slider_Management_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Slider_Management_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Slider_Management_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Slider_Management_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Slider_Management_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}



/**
 * Register the custom post type named Slider.
 */
public function sm_register_custom_post_type() {
	$labels = array(
		'name'               => __( 'Sliders', 'slider-management' ),
		'singular_name'      => __( 'Slider', 'slider-management' ),
		'menu_name'          => __( 'Sliders', 'slider-management' ),
		'add_new'            => __( 'Add New', 'slider-management' ),
		'add_new_item'       => __( 'Add New Slider', 'slider-management' ),
		'edit_item'          => __( 'Edit Slider', 'slider-management' ),
		'new_item'           => __( 'New Slider', 'slider-management' ),
		'view_item'          => __( 'View Slider', 'slider-management' ),
		'search_items'       => __( 'Search Sliders', 'slider-management' ),
		'not_found'          => __( 'No Sliders found', 'slider-management' ),
		'not_found_in_trash' => __( 'No Sliders found in Trash', 'slider-management' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'sliders' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 20,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
	);

	register_post_type( 'slider', $args );
}


/*
	Shortcode function to retrive slider on the front side.
*/
function display_slider_posts() {
    $slider_query = new WP_Query(array(
        'post_type' => 'slider',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'slider_show_hide',
                'value' => 'show',
                'compare' => '='
            )
        )
    ));

    if ($slider_query->have_posts()) {
        ob_start();
        ?>
        <div class="slider-posts">
        <?php
        while ($slider_query->have_posts()) {
            $slider_query->the_post();
            $slider_image = get_post_meta(get_the_ID(), 'slider_image', true);
            $slider_title = get_post_meta(get_the_ID(), 'slider_title', true);
            $slider_description = get_post_meta(get_the_ID(), 'slider_description', true);
            $image_url = SM_PLUGIN_URL . '/public/images/slider-placeholder.jpg';
            ?>
            <div class="slider-post">
                <?php if (has_post_thumbnail(get_the_ID())) { ?>
                    <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" alt="<?php echo esc_attr($slider_title); ?>" height="300px;" width="500px;">
                <?php }else{ ?>
                	<img src="<?php echo esc_url($image_url); ?>" alt="slider-placeholder" height="300px;" width="500px;">
                <?php }?>
                <div class="metadata">
                    <table border="1">
                        <tr>
                            <th><?php esc_html_e('Title','slider-management').":" ?></th>
                            <td><?php echo esc_html($slider_title,'slider-management'); ?></td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e('Description','slider-management').":" ?></th>
                            <td><?php echo esc_html($slider_description,'slider-management'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php
        }
        ?>
        </div>
        <?php
        $output = ob_get_clean();
        wp_enqueue_style('slick-css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css');
        wp_enqueue_script('slick-js', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', array('jquery'), '', true);
        ob_start();
        ?>
        <script>
            jQuery(document).ready(function($) {
                $('.slider-posts').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    autoplay: false,
                    autoplaySpeed: 2000
                });
            });
        </script>
        <?php
        $output .= ob_get_clean();
        return $output;
    } else {
        return '';
    }
}
	
	public function register_hooks(){
		add_action('init',array($this,'sm_register_custom_post_type'));
		add_shortcode('slider_posts', array($this,'display_slider_posts'));
	}

}