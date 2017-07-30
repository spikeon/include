<?php
/**
 * Plugin Name: Include
 * Plugin URI: http://flynndev.us/project/include
 * Description: Include a page, post, activity, or other query-object into another.
 * Version: 4.0.6
 * Author: mflynn, cngann, Clear_Code, bmcswee, flynndev
 * Author URI: http://clearcode.info
 * License: GPL2
 */

require_once('framework/load.php');

PluginFramework\register("Include", __FILE__);

if(PluginFramework\check_version()) {
	require_once( 'wp.trait.php' );
	require_once( 'Instance.class.php' );
	require_once( 'plugin.class.php' );

	$Include = new IncludePlugin\Plugin( "include", '4.0.6', __FILE__ );
}