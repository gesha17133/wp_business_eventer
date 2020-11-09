<?php 
class Eventer_Widget extends WP_Widget {

/**
 * Register widget with WordPress.
 */
function __construct() {
    parent::__construct(
        'eventer_widget', // Base ID
        esc_html__( "Perevenut' calendar    ", 'text_domain' ), // Name
        array( 'description' => esc_html__( 'A Eventer Widget', 'text_domain' ), ) // Args
    );
}

/**
 * Front-end display of widget.
 *
 * @see WP_Widget::widget()
 *
 * @param array $args     Widget arguments.
 * @param array $instance Saved values from database.
 */
public function widget( $args, $instance ) {
    echo $args['before_widget'];
    if ( ! empty( $instance['title'] ) ) {
        echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
    }
    /*widget area*/
    ?>
    <div class="calendar">
        <div class="headline">
            <span class="click-left"><i class="octicon octicon-triangle-left"></i><<</span>
            <span class="month">JANUARY</span>
            <span class="divider">&nbsp;&nbsp;<i class="octicon octicon-primitive-dot"></i>&nbsp;&nbsp;</span>
            <span class="year">2015</span>
            <span class="click-right"><i class="octicon octicon-triangle-right">>></i></span>
        </div>
        <div class="weekdays">
            <div class="day">MON</div>
            <div class="day">TUE</div>
            <div class="day">WED</div>
            <div class="day">THU</div>
            <div class="day">FRI</div>
            <div class="day">SAT</div>
            <div class="day">SUN</div>
        </div>
        <div class="days">
 
        </div>
    </div>    
    <?php 
    
    echo $args['after_widget'];
}

/**
 * Back-end widget form.
 *
 * @see WP_Widget::form()
 *
 * @param array $instance Previously saved values from database.
 */
public function form( $instance ) {
    $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
    ?>
    <p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label> 
    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <?php 
}

/**
 * Sanitize widget form values as they are saved.
 *
 * @see WP_Widget::update()
 *
 * @param array $new_instance Values just sent to be saved.
 * @param array $old_instance Previously saved values from database.
 *
 * @return array Updated safe values to be saved.
 */
public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

    return $instance;
}

}
?>

<?php

function register_eventer_widget() {
    register_widget( 'Eventer_Widget' );
}
add_action( 'widgets_init', 'register_eventer_widget' );

?>