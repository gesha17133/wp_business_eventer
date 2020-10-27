<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       instagram.com
 * @since      1.0.0
 *
 * @package    Bre_Eventer
 * @subpackage Bre_Eventer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bre_Eventer
 * @subpackage Bre_Eventer/admin
 * @author     Alexandr Yushkevych <sahayuskievich@gmail.com>
 */
class Bre_Eventer_Admin {
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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}




	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bre-eventer-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bre-eventer-admin.js', array( 'jquery' ), $this->version, false );
	}

	/*registeering taxonomies and CPT*/
	public static function cpt_register(){
		$cpt_labels = array(
			'name'               => 'Old_Events',
			'singular_name'      => 'Event', 
			'add_new'            => 'Add New Event',
			'add_new_item'       => 'Add New Event',
			'edit_item'          => 'Edit Event',
			'new_item'           => 'New Event',
			'view_item'          => 'View Event',
			'search_items'       => 'Search Event',
			'not_found'          =>  'Events Not Found',
			'not_found_in_trash' => 'No Events in Trash',
			'parent_item_colon'  => '',
			'menu_name'          => 'Events',
		);
		$cpt_args = array(
			"label"               => "Old Eventer",
			'labels'              => $cpt_labels,
			'description'         => '',
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_rest'        => false,
			'rest_base'           => '',
			'show_in_menu'        => true,
			'exclude_from_search' => false,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'hierarchical'        => false,
			'rewrite'             => array( 'slug' => 'event', 'with_front'=>false, 'pages'=>false, 'feeds'=>false, 'feed'=>false ),
			'has_archive'         => true,
			'query_var'           => true,
			'supports'            => array( 'title', 'editor',"excerpt","author","comments","revisions","thumbnail","data","content","taxonomies"),
			'taxonomies'          => array( 'bre_importance' ),
			'menu_icon'           =>"dashicons-admin-network",
		);
		register_post_type('old_events',$cpt_args);

		register_taxonomy( 'bre_importance', [ 'post','old_events'], [ 
			'label'                 => 'Importance', 
			'labels'                => [
				'name'              => 'Importance',
				'singular_name'     => 'Importance',
				'search_items'      => 'Search Genres',
				'all_items'         => 'All Genres',
				"show_ui"           => false,
				'view_item '        => 'View Genre',
				'parent_item_colon' => 'Parent Genre:',
				'edit_item'         => 'Edit Importance',
				'update_item'       => 'Update Importanc',
				'add_new_item'      => 'Add New Importance',
				'new_item_name'     => 'New Genre Name',
				'menu_name'         => 'Importance',
			],
			'description'           => 'set a mark to importance of your post',
			'public'                => true,
			'publicly_queryable'    => true,
			'hierarchical'          => true,
			'rewrite'               => true,
			'query_var'             => 'bre_importancy',
			'show_admin_column'     => false,
			'show_in_rest'          => null,
			'rest_base'             => null,
		] );

		//setting taxonomies
		wp_insert_term('1', 'bre_importance',
		array(
			"description"=>'if you are able to read',
		));

		wp_insert_term('2', 'bre_importance',
		array(
			"description"=>'something interesting',
		));

		wp_insert_term('3', 'bre_importance',
		array(
			"description"=>'its up to you',
		));

		wp_insert_term('4', 'bre_importance',
		array(
			"description"=>'worth your attantion',
		));

		wp_insert_term('5', 'bre_importance',
		array(
			"description"=>'important day',
		));
		
	}
	

	public static function bre_boxes(){	
		add_meta_box(
		"bre_date_box",
		'Old Event Date',
		'bre_add_datebox',
		'old_events'
		);
	}

	public static function bre_add_datebox($post)
	{
    ?>
	   <label for="date_name">input date for your post</label>
 	   <input type="date" id="date_box_bre" name="date_name" value = "<??>">
	<?php	
	}

	public static function bre_events_sidebar(){
		$sidebar_args = array(
			'name'          => "OldEvents",
			'id'            => "sidebar-bre",
			'description'   => 'this is area for archiva calendar in bre',
			'before_widget' => '<div id="bre_sidebar_area" class="widget_old_eventer">',
			'after_widget'  => "</div>",
			'before_title'  => '<h2 class="widget_bre_title">',
			'after_title'   => "</h2>",
		);
		register_sidebar($sidebar_args);
	}

	public static function add_plugin_page(){
		add_options_page( 'Old Events', 'Events', 'manage_options', 'bre_events_slug', 'events_options_page_output' );
	}
}

/*Widget for a creation good thingss*/


