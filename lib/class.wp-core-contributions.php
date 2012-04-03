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
		register_widget('WP_Codex_Contributions_Widget');
	}

	public static function get_items( $username ) {
		if($username == null) return array();

		if( false === ( $formatted = get_transient( 'wp-core-contributions-' . $username ) ) ) {

			$results = wp_remote_retrieve_body(wp_remote_get('https://core.trac.wordpress.org/search?q=props+' . $username . '&noquickjump=1&changeset=on', array('sslverify'=>false)));

			$pattern = '/<dt><a href="(.*?)" class="searchable">\[(.*?)\]: ((?s).*?)<\/a><\/dt>\n\s*(<dd class="searchable">.*\n?.*(?:ixes|ee) #(.*?)\n?<\/dd>)?/';

			preg_match_all($pattern, $results, $matches, PREG_SET_ORDER);

			$formatted = array();

			foreach($matches as $match) {
				array_shift($match);
				$newMatch = array(
					'link' => 'https://core.trac.wordpress.org' . $match[0],
					'changeset' => intval($match[1]),
					'description' => $match[2],
					'ticket' => isset( $match[3] ) ? intval($match[4]) : '',
				);
				array_push($formatted, $newMatch);
			}

			set_transient( 'wp-core-contributions-' . $username, $formatted, 60 * 60 * 12 );
		}

		return $formatted;
	}

	public static function get_changeset_count( $username ) {
		if ( $username == null ) return array();

		if ( false == ( $count = get_transient( 'wp-core-contributions-count-' . $username ) ) ) {
			$results = wp_remote_retrieve_body(wp_remote_get('https://core.trac.wordpress.org/search?q=props+' . $username . '&noquickjump=1&changeset=on', array('sslverify'=>false)));

			$pattern = '/<meta name="totalResults" content="(\d*)" \/>/';

			preg_match($pattern, $results, $matches);

			$count = intval($matches[1]);

			set_transient( 'wp-core-contributions-count-' . $username, $count, 60 * 60 * 12 );
		}

		return $count;
	}
	
	public static function get_codex_items( $username, $limit = 10 ) {
		if ( $username == null ) return array();
		
		if ( false === ( $formatted = get_transient( 'wp-codex-contributions-' . $username ) ) ) {
			
			$results = wp_remote_retrieve_body( wp_remote_get( 'http://codex.wordpress.org/api.php?action=query&list=usercontribs&ucuser=' . $username . '&uclimit=' . $limit . '&ucdir=older&format=xml', array('sslverify'=>false) ) );
			
			$raw = new SimpleXMLElement( $results );
			
			$formatted = array();
			
			// To-Do: Walk through XML doc and create formatted object.
			foreach( $items as $item ) {
				array_shift( $item );
				$newItem = array(
					'link' => '',
					'title' => $item->title,
					'description' => $item->comment,
					'revision' => $item->revid
				);
				array_push( $formatted, $newItem );
			}
			
			set_transient( 'wp-codex-contributions-' . $username, $formatted, 60 * 60 * 12 );
		}
		
		return $formatted;
	}
	
	public static function get_codex_count( $username ) {
		if ( $username == null ) return array();

		if ( false == ( $count = get_transient( 'wp-codex-contributions-count-' . $username ) ) ) {
			
			// To-Do: Get item count

			set_transient( 'wp-codex-contributions-count-' . $username, $count, 60 * 60 * 12 );
		}

		return $count;	
	}
}

endif;
?>
