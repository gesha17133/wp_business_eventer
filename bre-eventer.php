<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              instagram.com
 * @since             1.0.0
 * @package           Bre_Eventer
 *
 * @wordpress-plugin
 * Plugin Name:       Business Eventer
 * Plugin URI:        file:///home/user/Downloads/app/downloads.html#
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Alexandr Yushkevych
 * Author URI:        instagram.com/dsfsdfsdgf/sdfsdfs
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bre-eventer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/***WP CLI COMMANDS*****/
require plugin_dir_path( __FILE__ ).'wp-cli.php';

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BRE_EVENTER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bre-eventer-activator.php
 */
function activate_bre_eventer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bre-eventer-activator.php';
	Bre_Eventer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bre-eventer-deactivator.php
 */
function deactivate_bre_eventer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bre-eventer-deactivator.php';
	Bre_Eventer_Deactivator::deactivate();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'activate_bre_eventer' );
register_deactivation_hook( __FILE__, 'deactivate_bre_eventer' );






require plugin_dir_path( __FILE__ ) . 'includes/class-bre-eventer.php';
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require plugin_dir_path( __FILE__ ) .'/public/class-bre-eventer-public.php';

//registering CPT and Taxonomies
function regestration_all_bre(){
	require_once plugin_dir_path( __FILE__ ) . 'admin/class-bre-eventer-admin.php';
	Bre_Eventer_Admin::cpt_register();
}
add_action('init','regestration_all_bre');



function regestration_all_widgets(){
	require_once plugin_dir_path( __FILE__ ) . 'admin/class-bre-eventer-admin.php';
	Bre_Eventer_Admin::bre_events_sidebar();
}
add_action('widgets_init',"regestration_all_widgets");




function admin_Old_events_page(){
	require_once plugin_dir_path( __FILE__ ) . 'admin/class-bre-eventer-admin.php';
	Bre_Eventer_Admin::add_plugin_page();
}

require plugin_dir_path( __FILE__ ) . 'admin/partials/bre-eventer-admin-display.php';
require plugin_dir_path( __FILE__ ) . 'admin/partials/shortcods.php';
add_action('admin_menu','admin_Old_events_page');


/*template filter*/



add_filter('template_include' , 'set_bre_template');
function set_bre_template($template){

	if(is_page('bre-page')){
		$template = __DIR__."/public/partials/bre-eventer-public-display.php";
	}
	return $template;

}

add_filter("single_template","check_bre_cpt");
function check_bre_cpt($single_template){
	global $post;
	if( "old_events" === $post->post_type){
		$single_template = __DIR__."/public/partials/single-bre-eventer-post.php";
	}
	return $single_template;
}

function get_events_archive_template( $archive_template ) {
	global $post;
	if ( is_post_type_archive ( 'old_events' ) ) {
		$archive_template = __DIR__."/public/partials/archive-old_events.php";;
	}
	return $archive_template;
}
add_filter( 'archive_template', 'get_events_archive_template' ) ;


/*widget*/
require plugin_dir_path( __FILE__ ) . 'admin/partials/bre-calendar-widget.php';
/* metafield for date  */
function add_bre_meta(){

	add_meta_box(
		"bre_date_box",
		'Old Event Date',
		'bre_add_datebox',
		'old_events'
	);

}

function bre_comments_open( $open, $post_id ) {
    $post_type = get_post_type( $post_id );
    if ($post_type == 'old_events'){
        return true;
    }
    return true;
}

add_filter( 'comments_open', 'bre_comments_open',10,2);
add_action("add_meta_boxes", "add_bre_meta");


function bre_add_datebox($post){	
	$value = get_post_meta($post->ID, '_bre_meta_key', true);?> 
		<label for="date-bre">The date for a field</label>
		<input type="date" name = "bre_date_box" id = "bre_date_box" value = "<?php echo get_post_meta($post->ID, '_bre_meta_key', true)?>" max="<?php echo current_time("Y-m-d");?>">
<?php
}
?>

<?php
function bre_save_postdata($post_id)
{
    if (array_key_exists('bre_date_box', $_POST)) {
        update_post_meta(
            $post_id,
            '_bre_meta_key',
            $_POST['bre_date_box']
        );
    }
}

add_action('save_post', 'bre_save_postdata');
do_action("do_meta_boxes",'old_events');

function my_custom_css(){
	$style_block = get_option('option_name');
	$custom_css = $style_block['custom_css'];
	wp_add_inline_style( 'custom-style', $custom_css);
}
add_action( 'wp_enqueue_scripts', 'my_custom_css',1);


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bre_eventer(){
	$plugin = new Bre_Eventer();
	$plugin->run();
}
run_bre_eventer();
