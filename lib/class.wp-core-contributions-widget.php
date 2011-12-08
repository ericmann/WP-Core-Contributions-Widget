<?php
if ( ! class_exists('WP_Core_Contributions_Widget') ) :

class WP_Core_Contributions_Widget extends WP_Widget {
	function WP_Core_Contributions_Widget() {
		$widget_ops = array(
			'classname' => 'widget_core_contributions',
			'description' => 'Add a list of your accepted contributions to WordPress Core as a sidebar widget.'
		);

		$this->WP_Widget( false, 'WP Core Contributions', $widget_ops );
	}

	function widget( $args, $instance ){

	}


}

endif;