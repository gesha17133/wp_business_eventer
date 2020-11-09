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
 * Author URI:        http://instagram.com/san_sanych1.10
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


function bre_comments_open( $open, $post_id ) {
    $post_type = get_post_type( $post_id );
    if ($post_type == 'old_events'){
        return true;
    }
    return true;
}
add_filter( 'comments_open', 'bre_comments_open',10,2);
add_action("add_meta_boxes", "add_bre_meta");



/**********CUSTOM POST META*************/ 

/* metafield for date  */
function add_bre_meta(){

	add_meta_box(
		"bre_date_box",
		'Old Event Date',
		'bre_add_datebox',
		'old_events'
	);

	add_meta_box(
		"bre_posts_related",
		'Related Posts',
		'bre_add_related_posts',
		'old_events'
	);

	add_meta_box(
		"bre_event_persons",
		'Invited Persons',
		'bre_add_persons',
		'old_events'
	);


}


/************************************************************************************* ADDING_EVENT_DATE */

function bre_add_datebox($post){
	$value = get_post_meta($post->ID, '_bre_meta_key', true);?> 
	<label for="date-bre">The date for a field</label>
	<input
		type="date" 
		name = "bre_date_box"
		id = "bre_date_box"
		value = "<?php echo get_post_meta($post->ID, '_bre_meta_key', true)?>"
		max="<?php echo current_time("Y-m-d");?>"
	>
 <?php
}

/************************************************************************************ RELATED_POSTS_ADDING */
function bre_add_related_posts($post){
	?>
	<select name="selct_area[]" id="select_related_posts" multiple>
		<?php
		 
			if(!empty(get_post_meta($post->ID,'related_posts',true))){
			    $array_related = array_flip(get_post_meta($post->ID,'related_posts',true));
			}
				$posts_required = get_posts([
				'capability_type'=>'post',
				'numberposts'=> -1,
			]);

			foreach($posts_required as $item_post){
			?>
				<option value="<?php echo $item_post->ID?>"
					<?php
						if(isset($array_related)){
							if(array_key_exists($item_post->ID, $array_related)){
								echo("selected");		
							}	
						}
					?>	
				>
				<?php echo $item_post->post_title?>
				</option>
			<?php 	
			}
		?>
	</select>
	<?php 
}


/********************************************************************************** PERSONS_ADMIN_DISPLAY */
function bre_add_persons($post){
	?>
	<table class="persons_list">
		<thead>
			<tr>
				<th>Name</th>
				<th>Surnaame</th>
				<th>Social</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$personas = get_post_meta($post->ID,'invited_persons',true);
			if(!empty($personas)):
				foreach($personas as $person){
			?>
			<tr class="data_person_repeateble empty_row_person" id="person_1">
				<td><input name="name_person[]" type="text" value = "<?php echo $person['name']?>" ></td>
				<td><input name="surname_person[]" type="text"  value = "<?php echo $person['surname']?>" ></td>
				<td><input name="social_person[]" type="text" value = "<?php echo $person['link']?>" required></td>
				<td><input type="button" id="remove_person_row" value="delete person"></td>
			</tr>
			<?php 
			}
			endif;
			?>
			<tr class="data_person_repeateble empty_row_person" id="person_1">
				<td><label for="">Name<input name="name_person[]" type="text"></label></td>
				<td><label for="">Surname<input name="surname_person[]" type="text"></label></td>
				<td><label for="">Social<input name="social_person[]" type="text"></label></td>
				<td><input type="button" id="add_person_row" value="Add person"></td>
				<td><input class="cancel_button" type="button" id="remove_person_row" value="cancel"></td>
			</tr>
		</tbody>
	</table>
	<?php 
}


/**************************************************************************** PERSONS_ADDING */
function save_invited_persons($post_id){
	$names = $_POST['name_person'];
	$surnames = $_POST['surname_person'];
	$social_links = $_POST['social_person'];
	$new_person = array();
	$count = count($names);

	$old_person = get_post_meta($post_id,'invited_persons',false);

	for ( $i = 0; $i < $count; $i++ ){
		if($count != 0){
			if((!empty($names[$i]) || !empty($surnames[$i])) && !empty($social_links[$i])){
				$new_person[$i]['name']    = $names[$i];
				$new_person[$i]['surname'] = $surnames[$i];
				$new_person[$i]['link']    = $social_links[$i];
			}

		}	
	}

	if( !empty($new_person) && ($new_person != $old_person) ){
		update_post_meta( $post_id, 'invited_persons', $new_person );
	}
	if( empty($new_person) && $new_person != $old_person){
		delete_post_meta( $post_id, 'invited_persons');
	}
}


/*********************************** SAVE_POST_DATA_FUNCTIONS & HOOKS */
function save_related_posts($post_id){
	update_post_meta($post_id,'related_posts',$_REQUEST['selct_area'],false);
}

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

add_action('save_post', 'save_invited_persons');
add_action('save_post', 'save_related_posts');
add_action('save_post', 'bre_save_postdata');
do_action("do_meta_boxes",'old_events');

function my_custom_css(){
	$style_block = get_option('option_name');
	$custom_css = $style_block['custom_css'];
	wp_add_inline_style( 'custom-style', $custom_css);
}
add_action( 'wp_enqueue_scripts', 'my_custom_css',1);


/******************************************************************** REST API CUSTOM SEARCH */
add_action('rest_api_init',function($data){

	register_rest_route('/wp/v2/events/',"required_posts",[
		'methods'  => WP_REST_Server::ALLMETHODS,
		'callback' => 'search_section',
		'args'     => array(

			'taxonomy' => array(
				'type'     => 'array',
				'default'  => ['1','2','3','4','5'],
			),	
		
			'from'    => array(
				'type'     => 'date',
				"required" => true,
			),

			'to'    => array(
				'type'     => 'date',
				"required" => true,
			)
		)
	]);
});

function search_section(WP_REST_Request $request){
	
	$get_start_date =  $request['from'];
	$get_end_date =  $request['to'];
	$start_date = date('Y-m-d',strtotime($get_start_date));
	$end_date = date('Y-m-d',strtotime($get_end_date));
	$taxonomy = $request['taxonomy'];
	
	$param_query = array(
		'post_type'      => 'old_events',
		"posts_per_page" => -1,
		's'              => sanitize_text_field($request['search']),
		'meta_query'  =>  array(
			array(
				'key'     => '_bre_meta_key',
				'value'   => array($start_date,$end_date),
				'compare' => 'BETWEEN',
				'type'    => 'DATE'
			)
		),

		'tax_query' => array(
			array(
				'taxonomy' => 'bre_importance',
				'terms'    => $taxonomy,
				'field'    => 'slug',
				'include_children' => true,
				'operator' => 'IN',
			)
		)
	);

	$query = get_posts($param_query);
	$all_events = [];
	
	foreach($query as $post){
		$terms = get_the_terms($post->ID,"bre_importance");
		$related = get_post_meta($post->ID, 'related_posts', true);
		$all_related_info = [];
		$invited_persons = get_post_meta($post->ID,'invited_persons',true);

		foreach($related as $key=>$value){
			$info_single = get_post($value);
			$data_post = array(
				'title' => $info_single->post_title,
				'link'  => $info_single->guid
			);
			array_push($all_related_info, $data_post);
		}

		foreach($terms as $term){
			$name_term = $term->slug;
		}

		$posts_data = array(
			'title'   		=> $post->post_title,
			'date'   	    => get_post_meta($post->ID,'_bre_meta_key', true),
			'link'          => $post->guid,
			'related_posts' => $all_related_info,
			'taxonomy'      => $name_term,
			'personas'      => $invited_persons
		);
		array_push($all_events, $posts_data);	
	}
	return $all_events;
}

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
