<?php
/*
Plugin Name: WP Core Contributions Widget
Plugin URI: http://jumping-duck.com/wordpress
Description: Add a list of your accepted contributions to WordPress Core as a sidebar widget.
Version: 1.2.3
Author: Eric Mann
Author URI: http://eamann.com
License: GPLv2+
*/

/* Copyright 2011-2012  Eric Mann, Jumping Duck Media
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

define( 'WP_CORE_CONTRIBUTIONS_WIDGET_VERSION', '1.2.3' );
define( 'WP_CORE_CONTRIBUTIONS_WIDGET_URL',     plugin_dir_url( __FILE__ ) );
define( 'WP_CORE_CONTRIBUTIONS_WIDGET_DIR',     dirname( __FILE__ ) . '/' );

// Load widget templates automatically from /lib/ folder
if ( is_dir( dirname(__FILE__) . '/lib/') ) {
    $widgets = glob( WP_CORE_CONTRIBUTIONS_WIDGET_DIR . 'lib/class.*.php' );
    
    foreach ( (array)$widgets as $widget ) {
	include_once($widget);
    }
}

WP_Core_Contributions::init();
?>