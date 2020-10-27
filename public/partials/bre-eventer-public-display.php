<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       instagram.com
 * @since      1.0.0
 *
 * @package    Bre_Eventer
 * @subpackage Bre_Eventer/public/partials
 */
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php get_header();?>
<div class="title-bre-wrappet"><h1 class = " bre_title"><?php the_title();?></h1></div>
<div class="container old_events_container">
    
    <main class = "events_posts_block">
        <?php
         $option = get_option('option_name');?>
        <?php   
                $current_page = get_query_var('paged');
                
                $bre_args = array(
                    "post_type"=>"old_events",
                    "posts_per_page"=>$option["per_page"],
                    'paged'=>$current_page
                );
                $query = new WP_Query($bre_args);
                while($query->have_posts()):$query->the_post();
                global $wp_query;
        ?>  
        <div class="item_bre_block">
            <div class="block_wrap">
                <div class="left_post_bre_content">
                    <h3 class="bre_event_title"><?php
                     if( !empty(get_the_title())){
                        the_title();
                    }else{
                        echo "zagolovok";
                    }
                     ?>
                     </h3>
                    <p class="event_date_p"><span class="date_event">Event Date: </span><?php echo get_post_meta($post->ID,'_bre_meta_key', true);?></p>
                    <?php comments_number();?>
                    <p>Views: <span>12</span></p>
                        <?php 
                        $term = wp_get_post_terms($post->ID,"bre_importance");
                        if(!empty($term)){
                            foreach($term as $item);
                        ?>
                        <a href="#" class="bre-public-importance"><?php echo $item->description?></a>
                        <?php 
                        }else{?>
                            <span class="bre-public-importance"><?php echo "no priority"?></span>
                        <?php    
                        }
                        ?>
                        <a class="link_bre_post" href="<?php the_permalink();?>"> Read More</a>
                </div>
                
                <div class = "the_bre_img_wrapper">
                    <?php if(has_post_thumbnail()){
                        the_post_thumbnail('bre_img');
                    }
                    else{?>
                        <img src="<?php echo plugin_dir_url((__DIR__).'/assets_default/ ')?>cann.jpeg" alt="">
                    <?php
                    }
                    ?>
                </div>
            </div>    
            <div class="the_preview">
                <?php the_excerpt();?>
            </div>
        </div>
        <?php endwhile;?>
        <div class="pg_bre_continer">
             <div class="load-more-target">
             
             </div>
            <?php
            if($option['post_load'] == 'paginate'){
                echo paginate_links(
                    array( 
                    "total" => $query->max_num_pages,
                ));   
            }else{
                if($query->max_num_pages > 1):?>
                <button id="load-more" class="btn">Load More</button>
            <?php endif; } ?>
         </div>
    </main> 
    <aside class="events_widget_calendar">
        <?php
            dynamic_sidebar('OldEvents')
        ?>
    </aside>
</div>
<?php get_footer();?>


