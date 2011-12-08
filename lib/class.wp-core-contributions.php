<?php
if ( ! class_exists('WP_Core_Contributions') ) :

class WP_Core_Contributions{
	public static function init() {
		// Register widget
		add_action( 'widgets_init', array( 'WP_Core_Contributions', 'register_widget' ) );
	}

	public static function register_widget() {
		register_widget('WP_Core_Contributions_Widget');
	}
}

endif;
?>