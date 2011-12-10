<?php
/**
 * Widget Template.
 *
 * @since     0.2
 */

if ( ! defined( 'WP_CORE_CONTRIBUTIONS_WIDGET_DIR' ) ) exit;

$out .= '<ul>';

foreach ($items as $item) {
	$out .= '<li>';

	$out .= '<a href="' . $item['link'] . '">[' . $item['changeset'] . ']</a> ';
	if( $item['ticket'] )
		$out .= 'for <a href="http://core.trac.wordpress.org/ticket/' . $item['ticket'] . '">#' . $item['ticket'] . '</a>';

	$out .= '</li>';
}

$out .= '</ul>';
?>
