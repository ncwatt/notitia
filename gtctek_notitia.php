<?php
/**
 * @package GtctekNotitia
 * @version 0.0.1
 * 
 * Plugin Name:  Notitia
 * Text Domain:  gtctek-notitia
 * Plugin URI:   https://notitia.co.uk
 * Description:  Notitia is a WordPress plugin that provides comprehensive site management features, including user roles, event scheduling, and content organization.
 * Version:      0.0.1
 * Author:       Gtctek
 * Author URI:   https://gtctek.co.uk
 * License:      GPL2 or later
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

$gtctek_notitia_ver = '0.0.1';

if (!defined('ABSPATH')) {
    die( 'Invalid request.' );
    exit;
}

// Define global properties
define( 'GTCTEK_NOTITIA__VERSION', $gtctek_notitia_ver );
define( 'GTCTEK_NOTITIA__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GTCTEK_NOTITIA__PLUGIN_URI', plugin_dir_url( __FILE__ ) );
define( 'GTCTEK_NOTITIA__PREPEND', 'gtctek' );
define( 'GTCTEK_NOTITIA__TEXT_DOMAIN', 'gtctek-notitia' );

// Include required classes
require_once( GTCTEK_NOTITIA__PLUGIN_DIR . 'classes/class.gtctek.notitia.core.php' );
require_once( GTCTEK_NOTITIA__PLUGIN_DIR . 'classes/class.gtctek.notitia.duplicatepagepost.php' );

// Trigger initialise actions across difference classes
add_action( 'init', [ 'GTCTEK_Notitia_Core', 'init' ] );
add_action( 'init', [ 'GTCTEK_Notitia_DuplicatePagePost', 'init' ] );