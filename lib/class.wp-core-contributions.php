<?php
if ( ! class_exists('WP_Core_Contributions') ) :

class WP_Core_Contributions{
	public static function init() {
		add_action( 'init',         array( 'WP_Core_Contributions', 'wp_init' ) );
		add_action( 'widgets_init', array( 'WP_Core_Contributions', 'register_widget' ) );
	}

	public static function wp_init() {
		load_plugin_textdomain( 'wp-core-contributions-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	public static function register_widget() {
		register_widget('WP_Core_Contributions_Widget');
	}

	public static function get_items( $username ) {
		if($username == null) return array();

		if( false === ( $formatted = get_transient( 'wp-core-contributions' ) ) ) {

			$results = wp_remote_retrieve_body(wp_remote_get('https://core.trac.wordpress.org/search?q=props+' . $username . '&noquickjump=1&changeset=on', array('sslverify'=>false)));

			$pattern = '/<dt><a href="(.*?)" class="searchable">\[(.*?)\]: ((?s).*?)<\/a><\/dt>\n\s*(<dd class="searchable">.*\n?.*ixes #(.*?)\n?<\/dd>)?/';

			preg_match_all($pattern, $results, $matches, PREG_SET_ORDER);

			$formatted = array();

			foreach($matches as $match) {
				array_shift($match);
				$newMatch = array(
					'link' => 'https://core.trac.wordpress.org' . $match[0],
					'changeset' => intval($match[1]),
					'description' => $match[2],
					'ticket' => $match[3] ? intval($match[4]) : '',
				);
				array_push($formatted, $newMatch);
			}

			set_transient( 'wp-core-contributions', $formatted, 60 * 60 * 12 );
		}

		return $formatted;
	}
}

endif;
?>
