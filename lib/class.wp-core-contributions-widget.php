<?php
if ( ! class_exists('WP_Core_Contributions_Widget') ) :

class WP_Core_Contributions_Widget extends WP_Widget {
	function __construct() {
		$widget_ops = array(
			'classname' => 'widget_core_contributions',
			'description' => __( 'Add a list of your accepted contributions to WordPress Core as a sidebar widget.', 'wp-core-contributions-widget' )
		);

		parent::__construct( false, __( 'WP Core Contributions', 'wp-core-contributions-widget' ), $widget_ops );
	}

	function form( $instance ) {
		// Gracefully upgrade if the display count isn't already set
		if ( ! isset( $instance[ 'display-count' ] ) ) $instance[ 'display-count' ] = 5;

		if ( $instance && isset( $instance[ 'title' ] ) ) {
			$title = esc_attr( $instance[ 'title' ] );
		} else {
			$title = esc_attr__( 'WP Core Contributions', 'wp-core-contributions-widget' );
		}
		
		if ( $instance && isset( $instance[ 'trac-user' ] ) ) {
			$trac_user = esc_attr( $instance[ 'trac-user' ] );
		} else {
			$trac_user = esc_attr__( 'Trac Username', 'wp-core-contributions-widget' );
		}
		
		if ( $instance && isset( $instance[ 'display-count' ] ) ) {
			$trac_count = absint( $instance[ 'display-count' ] );
		} else {
			$trac_count = 5;
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'wp-core-contributions-widget' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'trac-user' ); ?>"><?php _e( 'Trac Username:', 'wp-core-contributions-widget' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('trac-user'); ?>" name="<?php echo $this->get_field_name( 'trac-user' ); ?>" type="text" value="<?php echo $trac_user; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'display-count' ); ?>"><?php _e( 'Display How Many Tickets?', 'wp-core-contributions-widget' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'display-count' ); ?>" name="<?php echo $this->get_field_name( 'display-count' ); ?>" type="text" value="<?php echo $trac_count; ?>" />
		</p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']			= strip_tags( $new_instance['title'] );
		$instance['trac-user']		= strip_tags( $new_instance['trac-user'] );
		$instance['display-count']	= absint( $new_instance['display-count'] );
		return $instance;
	}

	function widget( $args, $instance ){
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$user = $instance['trac-user'];
		$count = isset( $instance['display-count'] ) ? $instance['display-count'] : 5;

		echo $before_widget;

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		// Widget content
		$items = array_slice( WP_Core_Contributions::get_items( $user ), 0, $count );
		$total = WP_Core_Contributions::get_changeset_count( $user );

		// Include template - can be overriden by a theme!
		$template_name = 'wp-core-contributions-widget-template.php';
		$path = locate_template( $template_name );
		if ( empty( $path ) ) {
			$path = WP_CORE_CONTRIBUTIONS_WIDGET_DIR . 'inc/' . $template_name;
		}

		include( $path ); // This include will generate the markup for the widget

		echo $after_widget;
	}
}

endif;
