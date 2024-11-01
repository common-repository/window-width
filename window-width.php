<?php
/**
 * Plugin Name: Window Width
 * Plugin URI: http://momnt.co/makes/window-width
 * Description: A simple addition to the WordPress admin bar that displays the current width of the browser window.
 * Version: 1.0
 * Author: momnt
 * Author URI: http://momnt.co/
 * Text Domain: momnt-window-width
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 *
 * Copyright 2013 George Gecewicz (email: support@momnt.co)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

class Momnt_Window_Width {

	function __construct() {
		add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 1005 );
		add_action( 'wp_head', array( $this, 'header_css' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		if ( ! is_admin() ) {
			return;
		}

		add_action( 'admin_head', array( $this, 'header_css' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_meta_links' ), 10, 2 );
	}

	function admin_bar_menu() {
		global $wp_admin_bar;

		if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
			return;
		}

		$wp_admin_bar->add_menu( array(
			'id'     => 'momnt-window-width',
			'parent' => 'top-secondary',
			'title'  => sprintf( '<span title="%1$s" class="ab-label">%2$s</span>', esc_attr__( 'The current window width, in pixels.', 'momnt-window-width' ), esc_html__( 'Width', 'momnt-window-width' ) ),
			'meta'   => array( 'class' => 'momnt-window-width' )
		) );
	}

	function header_css() {
		ob_start(); ?>

		<style type="text/css">
			/* http://wordpress.org/plugins/window-width */
			#wp-admin-bar-momnt-window-width .ab-label {
				cursor: help;
				transition: all 0.1s ease-in-out 0s;
			}

			@media screen and ( max-width: 782px ) {
				#wp-toolbar > ul > li#wp-admin-bar-momnt-window-width { display: block; }
				#wp-toolbar #wp-admin-bar-momnt-window-width span.ab-label {
					font-size: 20px;
					line-height: 46px;
					display: block;
					color: #999;
				}
			}
		</style>

		<?php echo ob_get_clean();
	}

	function enqueue_scripts() {
		if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
			return;
		}

		wp_enqueue_script( 'momnt-window-width', plugins_url( '/window-width.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
	}

	function plugin_meta_links( $links, $file ) {
		$plugin = plugin_basename( __FILE__ );

		if ( $file == $plugin ) {
			return array_merge( $links, array(
				sprintf( __( '<a href="%s">Rate plugin</a>', 'momnt-window-width' ), 'http://wordpress.org/extend/plugins/window-width/' ),
				sprintf( __( '<a href="%s">Get support</a>', 'momnt-window-width' ), 'http://wordpress.org/support/plugin/window-width' )
			) );
		}

		return $links;
	}
}

function Momnt_Window_Width() {
	new Momnt_Window_Width();
}

add_action( 'init', 'Momnt_Window_Width' );