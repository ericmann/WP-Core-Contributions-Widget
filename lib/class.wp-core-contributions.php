<?php
if ( ! class_exists('WP_Core_Contributions') ) :

class WP_Core_Contributions {
	public static function init() {
		add_action( 'plugins_loaded',         array( 'WP_Core_Contributions', 'load_plugin_textdomain' ) );
		add_action( 'widgets_init', array( 'WP_Core_Contributions', 'register_widget' ) );
	}

	public static function load_plugin_textdomain() {
		load_plugin_textdomain( 'wp-core-contributions-widget', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/lang/' );
	}

	public static function register_widget() {
		register_widget( 'WP_Core_Contributions_Widget' );
		register_widget( 'WP_Codex_Contributions_Widget' );
	}

	public static function get_items( $username ) {
		if ( null == $username ) return array();

		if ( false === ( $formatted = get_transient( 'wp-core-contributions-' . $username ) ) ) {
			$results_url = add_query_arg( array(
					'q'				=>	'props+' . $username,
					'noquickjump'	=>	'1',
					'changeset'		=>	'on'
			), 'https://core.trac.wordpress.org/search' );
			$response = wp_remote_get( $results_url, array( 'sslverify' => false ) );
			$results = wp_remote_retrieve_body( $response );

			$pattern = '/<dt><a href="(.*?)" class="searchable">\[(.*?)\]: ((?s).*?)<\/a><\/dt>\n\s*(<dd class="searchable">.*\n?.*(?:ixes|ee) #(.*?)\n?<\/dd>)?/';

			preg_match_all( $pattern, $results, $matches, PREG_SET_ORDER );

			$formatted = array();

			foreach ( $matches as $match ) {
				array_shift( $match );
				$new_match = array(
					'link'			=> 'https://core.trac.wordpress.org' . $match[0],
					'changeset'		=> intval($match[1]),
					'description'	=> $match[2],
					'ticket'		=> isset( $match[3] ) ? intval($match[4]) : '',
				);
				array_push( $formatted, $new_match );
			}

			set_transient( 'wp-core-contributions-' . $username, $formatted, apply_filters( 'wpcc_core_transient', 60 * 60 * 12 ) );
		}

		return $formatted;
	}

	public static function get_changeset_count( $username ) {
		if ( null == $username ) return array();

		if ( false == ( $count = get_transient( 'wp-core-contributions-count-' . $username ) ) ) {
			$results_url = add_query_arg( array(
				'q'				=>	'props+' . $username,
				'noquickjump'	=>	'1',
				'changeset'		=>	'on'
			), 'https://core.trac.wordpress.org/search' );
			$response = wp_remote_get( $results_url, array( 'sslverify' => false ) );
			$results = wp_remote_retrieve_body( $response );

			$pattern = '/<meta name="totalResults" content="(\d*)" \/>/';

			preg_match( $pattern, $results, $matches );

			$count = intval( $matches[1] );

			set_transient( 'wp-core-contributions-count-' . $username, $count, apply_filters( 'wpcc_core_count_transient', 60 * 60 * 12 ) );
		}

		return $count;
	}

	public static function get_codex_items( $username, $limit = 10 ) {
		if ( null == $username ) return array();

		if ( true || false == ( $formatted = get_transient( 'wp-codex-contributions2-' . $username ) ) ) {
			$results_url = add_query_arg( array(
				'action'    => 'query',
				'list'      => 'usercontribs',
				'ucuser'    => $username,
				'uclimit'   => $limit,
				'ucdir'     => 'older',
				'format'    => 'json'
			), 'http://codex.wordpress.org/api.php' );
			$response = wp_remote_get( $results_url, array( 'sslverify' => false ) );
			$results  = wp_remote_retrieve_body( $response );
			$raw      = json_decode( $results );
			
			/* Expected object format is as follows:
			 * Object
			 * (
			 *     [query] => Object
			 *         (
			 *             [usercontribs] => Array
			 *                 (
			 *                     [0] => Object
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

			foreach( $raw->query->usercontribs as $item ) {
				$count = 0;
				$clean_title = preg_replace( '/^Function Reference\//', '', (string) $item->title, 1, $count );

				$new_item = array(
					'title'         => $clean_title,
					'description'   => (string) $item->comment,
					'revision'      => (int) $item->revid,
					'function_ref'  => (bool) $count
				);
				array_push( $formatted, $new_item );
			}

			set_transient( 'wp-codex-contributions-' . $username, $formatted, apply_filters( 'wpcc_codex_transient', 60 * 60 * 12 ) );
		}

		return $formatted;
	}

	public static function get_codex_count( $username ) {
		if ( null == $username ) return array();

		if ( false == ( $count = get_transient( 'wp-codex-contributions-count-' . $username ) ) ) {
			
			$results_url = add_query_arg( array(
				'action'	=>	'query',
				'list'		=>	'users',
				'ususers'	=>	$username,
				'usprop'	=>	'editcount',
				'format'	=>	'xml'
			), 'http://codex.wordpress.org/api.php' );
			$response = wp_remote_get( $results_url, array( 'sslverify' => false ) );
			$results = wp_remote_retrieve_body( $response );
			
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
			$count = (int) $raw->query->users->user["editcount"];
			
			set_transient( 'wp-codex-contributions-count-' . $username, $count, apply_filters( 'wpcc_codex_count_transient', 60 * 60 * 12 ) );
		}

		return $count;	
	}
}

endif;