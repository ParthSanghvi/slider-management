<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://profiles.wordpress.org/parthsanghvi/
 * @since             1.0.0
 * @package           Slider_Management
 *
 * @wordpress-plugin
 * Plugin Name:       Slider Management
 * Plugin URI:        https://github.com/ParthSanghvi
 * Description:       Plugin will manage sider and it's data
 * Version:           1.0.0
 * Author:            Parth
 * Author URI:        https://profiles.wordpress.org/parthsanghvi//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       slider-management
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SLIDER_MANAGEMENT_VERSION', '1.0.0' );

//Plugin URL
if ( ! defined( 'SM_PLUGIN_URL' ) ) {
    define( 'SM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-slider-management-activator.php
 */
function activate_slider_management() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-slider-management-activator.php';
	Slider_Management_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-slider-management-deactivator.php
 */
function deactivate_slider_management() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-slider-management-deactivator.php';
	Slider_Management_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_slider_management' );
register_deactivation_hook( __FILE__, 'deactivate_slider_management' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-slider-management.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_slider_management() {

	$plugin = new Slider_Management();
	$plugin->run();

}
run_slider_management();

/*
    Add custom meta boxes to slider.
*/
function slider_meta_box() {
    add_meta_box(
        'slider_meta_box',
        'Slider Fields',
        'render_slider_meta_box',
        'slider',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'slider_meta_box');


/*
    Render meta boxes data from database.
*/
function render_slider_meta_box($post) {
    wp_nonce_field(basename(__FILE__), 'slider_nonce');

    $slider_image = get_post_meta($post->ID, 'slider_image', true);
    $slider_title = get_post_meta($post->ID, 'slider_title', true);
    $slider_description = get_post_meta($post->ID, 'slider_description', true);
    $slider_show_hide = get_post_meta($post->ID, 'slider_show_hide', true);
    ?>
    <div class="slider-fields">
        <div class="slider-row">

            <label><?php esc_html_e('Slider Title','slider-management').":" ?></label>
            <input type="text" name="slider_title" value="<?php echo esc_attr($slider_title); ?>">
        </div>
        <div class="slider-row">
            <label><?php esc_html_e('Slider Description','slider-management').":" ?></label>
            <textarea name="slider_description"><?php echo esc_textarea($slider_description); ?></textarea>
        </div>
        <div class="slider-row">
            <label><?php esc_html_e('Slider Show/Hide','slider-management').":" ?></label>
            <input type="radio" name="slider_show_hide" value="show" <?php checked($slider_show_hide, 'show'); ?>> <?php esc_html_e('Show','slider-management');?>
            <input type="radio" name="slider_show_hide" value="hide" <?php checked($slider_show_hide, 'hide'); ?>> <?php esc_html_e('Hide','slider-management');?>
        </div>
    </div>
    <?php
}

/*
    Save slider meta data while we updating the slider posts.
*/
function save_slider_meta_box($post_id) {
    if (!isset($_POST['slider_nonce']) || !wp_verify_nonce($_POST['slider_nonce'], basename(__FILE__))) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['slider_title'])) {
        update_post_meta($post_id, 'slider_title', sanitize_text_field($_POST['slider_title']));
    }

    if (isset($_POST['slider_description'])) {
        update_post_meta($post_id, 'slider_description', sanitize_textarea_field($_POST['slider_description']));
    }

    if (isset($_POST['slider_show_hide'])) {
        update_post_meta($post_id, 'slider_show_hide', sanitize_text_field($_POST['slider_show_hide']));
    }
}
add_action('save_post_slider', 'save_slider_meta_box');