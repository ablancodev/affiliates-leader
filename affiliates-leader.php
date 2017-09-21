<?php
/**
 * affiliates-leader.php
 *
 * Copyright (c) 2011,2012 Antonio Blanco http://www.eggemplo.com
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author Antonio Blanco (eggemplo)
 * @package affiliates-leader
 * @since affiliates-leader 1.0.0
 *
 * Plugin Name: Affiliates Leader
 * Plugin URI: http://www.eggemplo.com
 * Description: Affiliates leader board
 * Version: 1.0.1
 * Author: eggemplo
 * Author URI: http://www.eggemplo.com
 * Text Domain: affiliates-leader
 * Domain Path: /languages
 * License: GPLv3
 */

define( 'AFFILIATES_LEADER_PLUGIN_NAME', 'affiliates-leader' );

define( 'AFFILIATES_LEADER_FILE', __FILE__ );

if ( !defined( 'AFFILIATES_LEADER_CORE_DIR' ) ) {
	define( 'AFFILIATES_LEADER_CORE_DIR', WP_PLUGIN_DIR . '/affiliates-leader/core' );
}

define( 'AFFILIATES_LEADER_PLUGIN_URL', plugin_dir_url( AFFILIATES_LEADER_FILE ) );

class AffiliatesLeader_Plugin {

	private static $notices = array();

	public static function init() {

		register_activation_hook( AFFILIATES_LEADER_FILE, array( __CLASS__, 'activate' ) );
		register_deactivation_hook( AFFILIATES_LEADER_FILE, array( __CLASS__, 'deactivate' ) );

		register_uninstall_hook( AFFILIATES_LEADER_FILE, array( __CLASS__, 'uninstall' ) );

		add_action( 'init', array( __CLASS__, 'wp_init' ) );

		add_action( 'widgets_init', array( __CLASS__,'affiliates_leader_widgets_init' ) );

	}

	public static function wp_init() {
		load_plugin_textdomain( 'affiliates-leader', false, 'affiliates-leader/languages' );
	}

	public static function affiliates_leader_widgets_init() {
		include_once 'core/class-affiliates-leader-widget.php';

		register_widget( 'AffiliatesLeaderWidget' );
	}

	/**
	 * Plugin activation work.
	 * 
	 */
	public static function activate() {

	}

	/**
	 * Plugin deactivation.
	 *
	 */
	public static function deactivate() {

	}

	/**
	 * Plugin uninstall. Delete database table.
	 *
	 */
	public static function uninstall() {

	}

}
AffiliatesLeader_Plugin::init();
