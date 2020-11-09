<?php 
    /*[shortpost_caousel post_number=3 start_from=1 ]*/


    /**** registering my own shortcode for bre events ****/
    function bre_post_shortcode($atts=[]){
        $params = shortcode_atts(array(
            'post_number' => "default",
            'start_from' =>  'default'
        ),$atts);
    /*query params*/
    /*****************************************/
    $optional = get_option('option_name');
    /*****************************************/
    
    foreach($params as $param);
    if($params['start_from'] == 'default'){
        $min_val = 1;
    }

    else $min_val = $params['start_from'];

    if($params['post_number'] == "post_number" ){
        $params['post_number'] =  5; 
    }
    else $post_q = $params['post_number'];

    /**********   GAYaNIALNA SYSTEMA   **********/
    $max_val = 5;
    $range = range($min_val,$max_val);
    $tax_query = array(
        array(
            'taxonomy' => 'bre_importance',
            'terms' => $range,
            'field' => 'slug',
            'include_children' => true,
            'operator' => 'IN'
        )
    );
    $args = array(
        'tax_query'  => $tax_query,    
        'post_type' => 'old_events',
        'post_per_page' => $post_q, 
    );
    /*the query*/
    $entry_query = new WP_Query($args);
    if($entry_query->have_posts()):  
        ob_start();
        while($entry_query->have_posts()):$entry_query->the_post();    
        ?>
            <div class="shor_post_block">
                <a href="<?php the_permalink();?>"><h3 class="the_header_in_shorc_code"><?php the_title();?></h3></a>
                <div class = img_wrapp_block>
                    <?php
                    if(has_post_thumbnail()){
                        the_post_thumbnail();
                    }else{?>
                        <img src="<?php echo plugin_dir_url((__DIR__).'/assets_dummy/ ')?>dummy.png";  alt="">  
                    <?php } ?>
                    <div class="preview_content">
                        <?php if(has_excerpt()){the_excerpt();}else{

                         echo '<p>n publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate the visual form of a document or a typeface without relying on meaningful content. Wikipedia</p>';    
                        }?>
                    </div>
                </div>
            </div>
                    
        <?php    
        endwhile;
        return ob_get_clean();
    endif;
    /*************OUPUTIZM************/
}
add_shortcode( "shortpost_caousel", 'bre_post_shortcode' );
?>
