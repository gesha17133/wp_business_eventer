<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       instagram.com
 * @since      1.0.0
 *
 * @package    Bre_Eventer
 * @subpackage Bre_Eventer/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Bre_Eventer
 * @subpackage Bre_Eventer/public
 * @author     Alexandr Yushkevych <sahayuskievich@gmail.com>
 */
class Bre_Eventer_Public {

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
		 * defined in Bre_Eventer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bre_Eventer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		add_theme_support('post_thumbnail');
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bre-eventer-public.css', array(), $this->version, 'all' );
		add_image_size('bre_img',450,220);
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
		 * defined in Bre_Eventer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bre_Eventer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */	

		global $post;
		$status = 'liked';
		$option = get_option('option_name');
		$current_page = get_query_var('paged');
        $args = array(
			"post_type"=>"old_events",
			"posts_per_page"=>$option["per_page"],
			'paged'=>$current_page
		);
		$wp_query = new WP_Query($args); 
		/*main-js-file*/
		wp_register_script( $this->plugin_name, plugin_dir_url(__FILE__).'js/bre-eventer-public.js', array('jquery'));
		wp_enqueue_script($this->plugin_name);
		wp_localize_script( $this->plugin_name,'load_more',array(

			'ajaxurl'      =>admin_url('admin-ajax.php'),
			'posts'        =>json_encode($wp_query),
			'max_page'     => $wp_query->max_num_pages,
			'current_page' => $wp_query->get_query_var('paged') ? get_query_var('paged') : 1,
		));
		/*like-js-file*/
		wp_register_script( 'like', plugin_dir_url(__FILE__).'js/like.js', array('jquery'));
		wp_enqueue_script("like");
		
		if(!empty(get_post_meta(get_the_ID(),'likes_counted'))){
			$count = get_post_meta(get_the_ID())['likes_counted'][0];
		}else{
			$count = 0;
		}
		
		wp_localize_script('like','like_post',array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'count'   => $count,
			'status'  => $status,
			'post_id' => get_the_ID(),
		));

		/*****PUBLIC CALENDAR STYLES****/
		$all_archive = [
			'numberposts'=> -1,
			'post_type' => 'old_events',
		];

		$getter = get_posts($all_archive);
		$get_data_list = [];
		if($getter){
			foreach($getter as $post_item):
				$get_data_list[] = get_post_meta($post_item->ID, "_bre_meta_key");
			endforeach;
			wp_reset_postdata();
		}

		wp_register_script( 'calendar', plugin_dir_url(__FILE__).'js/calendar.js', array('jquery'));
		wp_enqueue_script("calendar");
		wp_localize_script( 'calendar','picker',array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'available_posts' => json_encode($get_data_list),
			'link_to' => get_post_type_archive_link('old_events')
		));		
	}		
}





/******************************** Update function like meta*****************************/
function like_post(){
	update_post_meta($_POST['post_id'],'likes_counted', $_POST['count']);
	update_post_meta($_POST['post_id'], 'status', 'status');
	wp_die();
}
add_action('wp_ajax_like_post', 'like_post');
add_action('wp_ajax_nopriv_like_post', 'like_post');



/*******************************load button query************************/
function reload(){
	$paged = $_POST['current_page'] + 1;
	$option = get_option('option_name');
	$posts = array(
		'posts_per_page'=> $option['per_page'],
		'post_type'=>'old_events',
		'paged'=> $_POST['current_page'] + 1
	);
	query_posts($posts);
	while(have_posts()):the_post();
	?>	

	<div class="item_bre_block">
		<div class="block_wrap">
			<div class="left_post_bre_content">
				<h3 class="bre_event_title">
				<?php
				if( !empty(get_the_title())) { the_title(); }
				else{ echo "zagolovok"; }
				?>
				</h3>
				<p class="event_date_p"><span class="date_event">Event Date: </span><?php echo get_post_meta($post->ID,'_bre_meta_key', true);?></p>
				<?php comments_number();?>
				<p>Views: <span>12</span></p>
				<?php 
				$term = wp_get_post_terms($post->ID,"bre_importance");
				if(!empty($term)) {foreach($term as $item);
				?>
				<a href="#" class="bre-public-importance"><?php echo $item->description?></a>
				<?php 
				}
				else{
				?>
				<span class="bre-public-importance"><?php echo "no priority"?></span>
				<?php } ?>
				<a class="link_bre_post" href="<?php the_permalink();?>"> Read More</a>
			</div>
			
			<div class = "the_bre_img_wrapper">
				<?php
					if(has_post_thumbnail()){the_post_thumbnail('bre_img');}
					else{
				?>
					<img src="<?php echo plugin_dir_url((__DIR__).'/partials/ ')?>/assets_default/cann.jpeg" alt="">
				<?php } ?>
			</div>
			
		</div>    
	
		<div class="the_preview">
			<?php the_excerpt();?>
		</div>
	
	</div>

	<?php endwhile; ?>	
	<?php exit; 
}
	
	add_action('wp_ajax_load_more', 'reload');
	add_action('wp_ajax_nopriv_load_more', 'reload');
?>

 

