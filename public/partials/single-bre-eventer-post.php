<?php 
    /*
        this is template for single post soy you ken si ze kenedi
    
    */

?>

<?php 
    get_header();
?>

<div class="container container_single_post">
    <h2><?php the_title();?></h2>
        <?php 
            $term = wp_get_post_terms($post->ID,"bre_importance");
            if(!empty($term)){
                foreach($term as $iem);?>
                <div class="bre_importance_descr"><?php  echo($iem->description);?></div>
        <?php
            }    
        ?>
    <div class="bre_single_wrapper">
        <main class="single_wrapper_for_content">
            <div class="butto-liker">
            <a href="<?php echo get_post_type_archive_link('old_events');?>">archive</a>
            </div>
            <div class="single_image_wrapper ">
                <?php
                    if(has_post_thumbnail()){
                    the_post_thumbnail("blog");
                }else{?>
                    <img class="single_bre-post_img" src="<?php echo plugin_dir_url((__DIR__).'/assets_default/ ')?>cann.jpeg" alt="">
                <?php
            }
            ?>
            </div>
            <div class="contnet_area">
                <div class="like-continer">
                    <?php include __DIR__.'/assets_default/like.svg';?>
                    <span id="text-like"></span>
                </div>
                <div class="content">
                    <?php
                    the_content();
                    ?>
                </div>
            </div>
        </main>
        <aside class="single-event-post">
            <div class="commnets_on_post">
            <ol class = "comment_block">
                <?php
                    $comments = get_comments(
                            array(
                                'post_id'=> $post->ID,
                                'status' => 'approve'
                            )
                        );
                    wp_list_comments( array(
                        'per_page'=>5,
                        'reverse_top_level'=>'false',
                    ),
                    $comments);       
                ?>
            </ol>
            </div>
            <div class="comment_form_display">
            
                <?php comment_form();?>
            
            </div>
        </aside>
    </div>
</div>        

<?php
    get_footer();
?>
