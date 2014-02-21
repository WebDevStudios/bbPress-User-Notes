<?php
/**
 * Plugin Name: bbPress User Notes
 * Plugin URI:  http://www.webdevstudios.com
 * Description: Add and manage notes for bbPress users
 * Version:     0.1.0
 * Author:      WebDevStudios
 * Author URI:  http://www.webdevstudios.com
 * License:     GPLv2+
 * Text Domain: bbpuser_notes
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2014 WebDevStudios (email : contact@webdevstudios.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
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

// Useful global constants
define( 'BBPUSER_NOTES_VERSION', '0.1.0' );
define( 'BBPUSER_NOTES_URL',     plugin_dir_url( __FILE__ ) );
define( 'BBPUSER_NOTES_PATH',    dirname( __FILE__ ) . '/' );

class bbPress_User_Notes {

	function __construct() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'bbpuser_notes' );
		load_textdomain( 'bbpun', WP_LANG_DIR . '/bbpun/bbpun-' . $locale . '.mo' );
		load_plugin_textdomain( 'bbpuser_notes', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		register_activation_hook( __FILE__, array( &$this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );

		add_action( 'admin_init', array( $this, 'admin' ) );
		add_action( 'init', array( $this, 'front' ) );
	}

	public function admin() {
		require_once( BBPUSER_NOTES_PATH . 'includes/admin_settings.php' );
	}

	public function front() {
		require_once( BBPUSER_NOTES_PATH . 'includes/frontend_settings.php' );
	}
}
// Have a nice day!
$bbPress_User_Notes = new bbPress_User_Notes;
