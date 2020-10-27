<?php

/**
 * Fired during plugin deactivation
 *
 * @link       instagram.com
 * @since      1.0.0
 *
 * @package    Bre_Eventer
 * @subpackage Bre_Eventer/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Bre_Eventer
 * @subpackage Bre_Eventer/includes
 * @author     Alexandr Yushkevych <sahayuskievich@gmail.com>
 */
class Bre_Eventer_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate(){
			
			//Deteting posts and metadata
			$delete_it = get_page_by_title("Old Events");
			wp_delete_post($delete_it->ID,true);

			unregister_post_type('old_events');
			unregister_taxonomy('bre_importance');		
		}

}
