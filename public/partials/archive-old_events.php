<?php 
    get_header();
?>

<div class="archive_title_wrapper"><h1>the archive</h1></div>
<div class="container old_events_container">    
    <main class = "events_archive_block">
        <?php
        $day   = $_GET['day'];
        $year  = $_GET['year'];
        $month = $_GET['month'];
        
        $post_date = date($year.'-'.$month.'-'.$day);
        
        $args = array(
            'post_type'      => 'old_events',
            'posts_per_page' => 10,
            'meta_key'    => '_bre_meta_key',
		    'meta_value'  => date($post_date),
        );

        if(isset($day) && isset($year) && isset($month)){
            $query = new WP_Query($args);
            if($query->have_posts()){
            while($query->have_posts()):$query->the_post();
        ?>

        <div class = "post-card-archive">
            <h2 class="the_post_archive_title"><?php the_title();?></h2>
            <div class="archive_img_wrap">
            <?php if(has_post_thumbnail()){
                the_post_thumbnail();
            }else{
            ?>
                <img src="<?php echo plugin_dir_url((__DIR__).'/assets_default/ ')?>cann.jpeg"><?php
            }
            ?>
		    </div>
            <a href="<?php the_permalink();?>" class = "archive_permalink">read more</a>
        </div>    
		
        <?php 
            endwhile;
            }else{
                echo '<h1>You Will Not Find There Anything :(</h1>';
            }
        }
        ?>
    </main>

    <aside class="archive_events_widget_calendar">
        <div class="wrapper_calendar">
        <?php
            dynamic_sidebar('OldEvents')
        ?>
        </div>
    </aside>
    
    </div>
</div>

<?php 
    get_footer();
?>