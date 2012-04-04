<?php
if ( ! class_exists('WP_Codex_Contributions_Widget') ) :

class WP_Codex_Contributions_Widget extends WP_Widget {
	function WP_Codex_Contributions_Widget() {
		$widget_ops = array(
			'classname' => 'widget_codex_contributions',
			'description' => __( 'Add a list of your contributions to the WordPress Codex as a sidebar widget.', 'wp-core-contributions-widget' )
		);
		
		$this->WP_Widget( false, __( 'WP Codex Contributions', 'wp-core-contributions-widget'), $widget_ops );
	}
	
	function form( $instance ) {
		// Gracefully upgrade if the display count isn't already set
		if ( ! isset( $instance[ 'display-count' ] ) ) $instance[ 'display-count' ] = 5;
		
		if ( $instance && isset( $instance[ 'title' ] ) ) {
			$title = esc_attr( $instance[ 'title' ] );
		} else {
			$title = __( 'WP Codex Contributions', 'wp-core-contributions-widget' );
		}
		
		if ( $instance && isset( $instance[ 'codex-user' ] ) ) {
			$codexUser = esc_attr( $instance[ 'codex-user' ] );
		} else {
			$codexUser = __( 'Codex Username', 'wp-core-contributions-widget' );
		}
		
		if ( $instance && isset( $instance[ 'display-count' ] ) ) {
			$codexCount = esc_attr( $instance[ 'display-count' ] );
		} else {
			$codexCount = 5;
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />

			<label for="<?php echo $this->get_field_id('codex-user'); ?>"><?php _e('Codex Username:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('codex-user'); ?>" name="<?php echo $this->get_field_name('codex-user'); ?>" type="text" value="<?php echo $codexUser; ?>" />

			<label for="<?php echo $this->get_field_id('display-count'); ?>"><?php _e('Display How Many Changes?'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('display-count'); ?>" name="<?php echo $this->get_field_name('display-count'); ?>" type="text" value="<?php echo $codexCount; ?>" />
		</p>
		<?php
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['codex-user'] = strip_tags($new_instance['codex-user']);
		$instance['display-count'] = strip_tags($new_instance['display-count']);
		return $instance;
	}
	
	function widget( $args, $instance ){
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$user = $instance['codex-user'];
		$count = isset($instance['display-count']) ? $instance['display-count'] : 10;

		echo $before_widget;

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		// Widget content
		$items = array_slice( WP_Core_Contributions::get_codex_items($user), 0, $count );
		$total = WP_Core_Contributions::get_codex_count($user);

		// Include template - can be overriden by a theme!
		$template_name = 'wp-codex-contributions-widget-template.php';
		$path = locate_template( $template_name );
		if ( empty( $path ) ) {
			$path = WP_CORE_CONTRIBUTIONS_WIDGET_DIR . 'inc/' . $template_name;
		}

		include_once( $path ); // This include will generate the markup for the widget

		echo $after_widget;
	}
}

endif;