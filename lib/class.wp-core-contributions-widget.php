<?php
if ( ! class_exists('WP_Core_Contributions_Widget') ) :

class WP_Core_Contributions_Widget extends WP_Widget {
	function WP_Core_Contributions_Widget() {
		$widget_ops = array(
			'classname' => 'widget_core_contributions',
			'description' => __( 'Add a list of your accepted contributions to WordPress Core as a sidebar widget.', 'wp-core-contributions-widget' )
		);

		$this->WP_Widget( false, __( 'WP Core Contributions', 'wp-core-contributions-widget' ), $widget_ops );
	}

	function form( $instance ) {
		if ( $instance ) {
			$title = esc_attr( $instance[ 'title' ] );
			$tracUser = esc_attr( $instance[ 'trac-user' ] );
		} else {
			$title = __( 'WP Core Contributions', 'wp-core-contributions-widget' );
			$tracUser = __( 'Trac Username', 'wp-core-contributions-widget' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />

		<label for="<?php echo $this->get_field_id('trac-user'); ?>"><?php _e('Trac Username:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('trac-user'); ?>" name="<?php echo $this->get_field_name('trac-user'); ?>" type="text" value="<?php echo $tracUser; ?>" />
		</p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['trac-user'] = strip_tags($new_instance['trac-user']);
		return $instance;
	}

	function widget( $args, $instance ){
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$user = $instance['trac-user'];

		echo $before_widget;

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		// Widget content
		$items = WP_Core_Contributions::get_items($user);

		// Include template - can be overriden by a theme!
		$template_name = 'wp-core-contributions-widget-template.php';
		$path = locate_template( $template_name );
		if ( empty( $path ) ) {
			$path = WP_CORE_CONTRIBUTIONS_WIDGET_DIR . 'inc/' . $template_name;
		}

		include_once( $path ); // This include will generate the markup for the widget

		echo $after_widget;
	}
}

endif;