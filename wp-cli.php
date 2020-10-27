<?php 

if(defined('WP_CLI') && WP_CLI ):

class EVENTS_CLI{
    public function generate_event($args,$assoc_args){
        $importancy = get_terms([
            'taxonomy'   => 'bre_importance',
            'hide_empty' => false 
        ]);
        
        $amount = $assoc_args['amount'];
        $date = $assoc_args['event_date'];
        
        $tax_names = array_map(function($importancy){
            return $importancy->term_id;
        },$importancy);

        $i = 0;
        for($i == 0; $i < $amount; $i++){
        
            $events_create = wp_insert_post([
                'post_title'=>'The Event '.($i+1),
                'post_status' => 'publish',
                'post_type' => 'old_events'
            ]);

            update_post_meta($events_create,'_bre_meta_key',$date);
            wp_set_object_terms($events_create, array_rand(array_flip($tax_names),1),'bre_importance');
            $progress = \WP_CLI\Utils\make_progress_bar('creating_events', $amount);
            $progress->tick();
            WP_CLI::success(($i+1)."Events Created");
        }
    }

    public function delete_event($args,$assoc_args){

        if(!empty($assoc_args['importance'])){
            $max_val = $assoc_args['importance'];
            $min_val = 1; 
            $range = range($min_val,$max_val);

            $posts_to_anigilate = [
                'numberposts' => -1,
                'status' =>'publish',
                'post_type' =>'old_events',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'bre_importance',
                        'terms'    => $range,
                        'field'    => 'slug',
                        'include_children' => true,
                        'operator' => 'IN',
                    )
                )
            ];
            $final_countdown = get_posts($posts_to_anigilate);
            $num = 0;
            foreach($final_countdown as $del_post){
                $num =$num +1;
                wp_delete_post( $del_post->ID,true);
                $progress = \WP_CLI\Utils\make_progress_bar('deletting_events', count($final_countdown));
                $progress->tick();
                WP_CLI::success($num." ".'prosto deleted naher');
            }
        }    

        if(!empty($assoc_args['date'])){
            $start_date   = $assoc_args['date'];
            $today        = date('Y-m-d'); 
            $posts_to_del = [
                'numberposts'=> -1,
                "post_type"   => "old_events", 
                'status'      => 'publish',
                'meta_query'  =>  array(
                    array(
                        'key'     => '_bre_meta_key',
                        'value'   => array($start_date,$today),
                        'compare' => 'BETWEEN',
                        'type'    => 'DATE'
                    )

                ),
            ];
            $progress = \WP_CLI\Utils\make_progress_bar('deletting_events',count($the_posts));
            $num = 0;
            $the_posts = get_posts($posts_to_del);
            foreach($the_posts as $post){
                $num = $num + 1;
                wp_delete_post($post->ID);
                $progress->tick();
                WP_CLI::success($num." ".'prosto deleted posts', count($the_posts));
            }
            
        }

        WP_CLI::success('********************ALL Posts Deleted****************');
        
    }

}
WP_CLI::add_command('old_events', 'EVENTS_CLI');
endif;
?>