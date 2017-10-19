<?php
if ( ! class_exists('WP_Meta_Contributions') ) :

class WP_Meta_Contributions {
	public static function init() {
		add_action( 'plugins_loaded',         array( 'WP_Meta_Contributions', 'load_plugin_textdomain' ) );
		add_action( 'widgets_init', array( 'WP_Meta_Contributions', 'register_widget' ) );
	}

	public static function load_plugin_textdomain() {
		load_plugin_textdomain( 'wp-core-contributions-widget', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/lang/' );
	}

	public static function register_widget() {
		register_widget( 'WP_Meta_Contributions_Widget' );
	}

	public static function get_items( $username ) {
		if ( null == $username ) return array();

		if ( false === ( $formatted = get_transient( 'wp-meta-contributions-' . $username ) ) ) {
			$results_url = add_query_arg( array(
					'q'				=>	'props+' . $username,
					'noquickjump'	=>	'1',
					'changeset'		=>	'on'
			), 'https://meta.trac.wordpress.org/search' );
			$response = wp_remote_get( $results_url, array( 'sslverify' => false ) );
			$results = wp_remote_retrieve_body( $response );

			$pattern = '/<dt><a href="(.*?)" class="searchable">\[(.*?)\]: ((?s).*?)<\/a><\/dt>\n\s*(<dd class="searchable">.*\n?.*(?:ixes|ee) #(.*?)\n?<\/dd>)?/';

			preg_match_all( $pattern, $results, $matches, PREG_SET_ORDER );

			$formatted = array();

			foreach ( $matches as $match ) {
				array_shift( $match );
				$new_match = array(
					'link'			=> 'https://meta.trac.wordpress.org' . $match[0],
					'changeset'		=> intval($match[1]),
					'description'	=> $match[2],
					'ticket'		=> isset( $match[3] ) ? intval($match[4]) : '',
				);
				array_push( $formatted, $new_match );
			}

			set_transient( 'wp-meta-contributions-' . $username, $formatted, apply_filters( 'wpcc_meta_transient', 60 * 60 * 12 ) );
		}

		return $formatted;
	}

	public static function get_changeset_count( $username ) {
		if ( null == $username ) return array();

		if ( false == ( $count = get_transient( 'wp-meta-contributions-count-' . $username ) ) ) {
			$results_url = add_query_arg( array(
				'q'				=>	'props+' . $username,
				'noquickjump'	=>	'1',
				'changeset'		=>	'on'
			), 'https://meta.trac.wordpress.org/search' );
			$response = wp_remote_get( $results_url, array( 'sslverify' => false ) );
			$results = wp_remote_retrieve_body( $response );

			$pattern = '/<meta name="totalResults" content="(\d*)" \/>/';

			preg_match( $pattern, $results, $matches );

			$count = intval( $matches[1] );

			set_transient( 'wp-meta-contributions-count-' . $username, $count, apply_filters( 'wpcc_meta_count_transient', 60 * 60 * 12 ) );
		}

		return $count;
	}

}

endif;