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
                            }
                            else{?>
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
            <div class = 'search_section'>
                <div class="search-field">
                    <button class ="search_ico "><?php include __DIR__.'/assets_default/search.svg';?></button>
                </div>        
            </div>
            <?php
                dynamic_sidebar('OldEvents')
            ?>
        </aside>
    </div>

    <div class = "overlay_search">
        <div class="search_form">
        
            <div class="wrapping_search">
                <div class="close_modal"><?php include __DIR__.'/assets_default/cancel.svg';?></div>
                <input type="search" 
                    id='site_search_field'
                    name = "looker" 
                    placeholder = "find what you want"
                >
                <button class ="search_ico search_start_button"><?php include __DIR__.'/assets_default/search.svg';?></button>
            </div>    

            <div class="range_date_field">
                    <label for="amount">Date range:</label>                
                <div id="slider-range"></div>

            </div>
            <div class="wrapper_data_importance">
                <div class ="importance_check" id="checkboxes">
                    <?php
                        $terms_search = get_terms([
                            'taxonomy' => 'bre_importance',
                            'order'    => 'ASC',
                            'hide_empty'=>true
                        ]);
                        foreach($terms_search as $search_term){?>    
                            <label for="<?php echo $search_term->name ?>">
                            <input class="imp_style_cls" name="<?php echo $search_term->name ?>" type = "checkbox" value = "<?php echo $search_term->name ?> ">
                            <?php  echo $search_term->description ?>
                            </label>
                        <?php
                        }    
                    ?>
                    <p></p>
                </div>
                <div class="filter_date_user_ui">
                    <p class="range_day_data"> Start day: <input type = "text" id='amount'></p>
                    <p class="range_day_data"> End day: <input type = "text" id='end_date'></p>
                </div>
            </div>
        </div> 
        <div class="preloader">
            <img src="<?php echo plugin_dir_url((__DIR__).'/assets_default/ ')?>preloader.gif" alt="">
            WAITING
        </div>
        <div class="results_div">
        </div>
    </div>
<?php get_footer();?>


