<?php

/**
 * Fired during plugin activation
 *
 * @link       instagram.com
 * @since      1.0.0
 *
 * @package    Bre_Eventer
 * @subpackage Bre_Eventer/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Bre_Eventer
 * @subpackage Bre_Eventer/includes
 * @author     Alexandr Yushkevych <sahayuskievich@gmail.com>
 */
class Bre_Eventer_Activator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$bre_page = array(
			"ID"           => "BRE_PAGE_ARCHIVE",
			"post_name"    => "bre-page",
			"post_title"   => "Old Events",
			"post_status"  => "publish",
			"post_type"    => "page",
		);
		wp_insert_post($bre_page);				

		$role_persmissions = array(
			"publish_pages" => true,
			"delete_pages"  => false,
			"edit_pages"    => true,
			"publish_posts" => true,
			"edit_posts"    => true,
			
			"read"          => true,
			"delete_posts"  => false,
		);
		add_role('admin_helper','Ассистент адміністратора',$role_persmissions);
	}

}
