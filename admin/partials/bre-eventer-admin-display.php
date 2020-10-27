<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       instagram.com
 * @since      1.0.0
 *
 * @package    Bre_Eventer
 * @subpackage Bre_Eventer/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php 
    function events_options_page_output(){
    ?>
    <div class="admin_wrapper_setting_group">
    <h2>Hello to BRE plugin</h2>
    <form action="options.php" method="POST">		
            <?php
				settings_fields( 'option_group' );     
				do_settings_sections( 'bre_events_page' ); 
				submit_button();
			?>

		</form>
    </div>        
    <?php
    }
    add_action('admin_init', 'plugin_settings');
    function plugin_settings(){

        register_setting( 'option_group', 'option_name', 'sanitize_callback' ); 
        add_settings_section( 'section_id', 'MainPage/Archive Dispaly ', '', 'bre_events_page' ); 
        add_settings_field('primer_field1', 'Posts Per Page', 'fill_primer_field1', 'bre_events_page', 'section_id' );
        add_settings_field('primer_field2', 'Post display', 'fill_primer_field2', 'bre_events_page', 'section_id' );
        add_settings_field('primer_field3', 'Custom CSS', 'fill_primer_field3', 'bre_events_page', 'section_id' );
        
    }
    
    
    function fill_primer_field1(){
        $val = get_option('option_name');
        $val = $val ? $val['per_page'] : null;
        ?>
        <input type="number"
               name="option_name[per_page]"
               value="<?php echo esc_attr( $val )?>"max="15" min = "2"/>
        <?php
    }
    
    function fill_primer_field2(){
        $val = get_option('option_name');
        ?>
        <label>
        <input type="radio"
               id="option_radio"
               name="option_name[post_load]"
               value="paginate" <?php if($val['post_load'] == "paginate"){ echo "checked";}?>/>
               page pagination
        </label><br>
        <label>
        <input type="radio" 
               name="option_name[post_load]" 
               value="load_more" <?php if($val['post_load'] == "load_more"){ echo "checked";}?>/>
               Load more
        </label>        
        <?php
    }

    function fill_primer_field3(){
        $val = get_option('option_name');
        $val['custom_css'];
        ?>
        <label>
        Custom CSS
        <textarea name="option_name[custom_css]" value ="pedor" cols="100" rows="30"><?php echo $val['custom_css']?></textarea>
        </label>
        <?php
    }
    
    
    function sanitize_callback($options){ 
       
        foreach($options as $name => & $val){
            if( $name == 'input' )
                $val = strip_tags( $val );
            
            if( $name == 'checkbox' )
                $val = intval( $val );
        }

        return $options;
    }
    

?>