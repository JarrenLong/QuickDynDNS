<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Create the widget 
class qddns_widget extends WP_Widget {
	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'qddns_widget', 
			// Widget name will appear in UI
			__('Quick DynDNS', 'qddns_widget_domain'),
			// Widget description
			array( 'description' => __( 'Quick DynDNS widget', 'qddns_widget_domain' ), ) 
		);
	}

	// Creating widget frontend
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( !empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		// This is where you run the code and display the output
		echo __( get_client_ip('widget'), 'qddns_widget_domain' );
		
		if( get_option( 'qddns_show_powered_by_widget', true ) ) {
			echo '<br/><small><a href="http://jlong.co/qddns">Powered by Quick DynDNS</a></small>';
		}

		echo $args['after_widget'];
	}
		
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Your IP Address Is:', 'qddns_widget_domain' );
		}
		// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Text Before IP:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
	}
	
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
}

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'qddns_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );
?>
