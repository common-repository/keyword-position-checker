<?php
class KeywordPositionChecker extends WP_Widget {


    /** constructor */
    function __construct() {
        parent::WP_Widget(false, $name = 'Keyword position checker widget');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {	
        extract( $args );
		global $kpc_plugin_url;
        $instance = wp_parse_args( (array) $instance , array (
        'title' => ''
        ) );        
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $instance['title'] )
                        echo $before_title . $instance['title'] . $after_title; ?>
							<?php echo keyword_position_checker(); ?>
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form( $instance ) {	
	
        $instance = wp_parse_args( (array) $instance, array (
        'title' => ''
        ) );        
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>
        <?php 
    }


} // class utopian_recent_posts
// register Recent Posts widget
add_action('widgets_init', create_function('', 'return register_widget("KeywordPositionChecker");'));

?>