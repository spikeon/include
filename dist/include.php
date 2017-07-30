<?php
/**
 * Plugin Name: Include
 * Plugin URI: http://flynndev.us/project/include
 * Description: Include a page, post, activity, or other query-object into another.
 * Version: 4.0.15
 * Author: mflynn, cngann, Clear_Code, bmcswee, flynndev
 * Author URI: http://clearcode.info
 * License: GPL2
 */

require_once('framework/load.php');

PluginFramework\V_1_1\register("Include", __FILE__);

if(PluginFramework\V_1_1\check_version()) {
	require_once( 'wp.trait.php' );
	require_once( 'Instance.class.php' );
	require_once( 'plugin.class.php' );

	$Include = new IncludePlugin\Plugin( "include", '4.0.15', __FILE__ );
}