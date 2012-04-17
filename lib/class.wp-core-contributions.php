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
		
		if ( true || false == ( $formatted = get_transient( 'wp-codex-contributions-' . $username ) ) ) {
			
			$results_url = 'http://codex.wordpress.org/api.php?action=query&list=usercontribs&ucuser=' . $username . '&uclimit=' . $limit . '&ucdir=older&format=php';
			$results = wp_remote_retrieve_body( wp_remote_get( $results_url, array('sslverify'=>false) ) );

			$raw = unserialize( $results );
			
			/* Expected array format is as follows:
			 * Array
			 * (
			 *     [query] => Array
			 *         (
			 *             [usercontribs] => Array
			 *                 (
			 *                     [0] => Array
			 *                         (
			 *                             [user] => Mbijon
			 *                             [pageid] => 23000
			 *                             [revid] => 112024
			 *                             [ns] => 0
			 *                             [title] => Function Reference/add help tab
			 *                             [timestamp] => 2011-12-13T23:49:38Z
			 *                             [minor] =>
			 *                             [comment] => Functions typo fix
			 *                         )
			 **/
			
			$formatted = array();
			
			foreach( $raw['query']['usercontribs'] as $item ) {
				$count = 0;
				$clean_title = preg_replace( '/^Function Reference\//', '', (string)$item['title'], 1, $count );

				$newItem = array(
					'title' => $clean_title,
					'description' => (string)$item['comment'],
					'revision' => (int)$item['revid'],
					'function_ref' => $count == 0 ? false : true
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
			
			$results_url = 'http://codex.wordpress.org/api.php?action=query&list=users&ususers=' . $username . '&usprop=editcount&format=xml';
			$results = wp_remote_retrieve_body( wp_remote_get( $results_url, array('sslverify'=>false) ) );
			
			/* Expected XML format is as follows:
			 * <?xml version="1.0"?>
			 * <api>
  			 *   <query>
			 *     <users>
			 *       <user name="Ericmann" editcount="8" />
			 *     </users>
			 *   </query>
			 * </api>
			 **/
			
			$raw = new SimpleXMLElement( $results );
			$count = (int)$raw->query->users->user["editcount"];
			
			set_transient( 'wp-codex-contributions-count-' . $username, $count, 60 * 60 * 12 );
		}

		return $count;	
	}
}

endif;
?>