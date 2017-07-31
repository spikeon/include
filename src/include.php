<?php
/**
 * Plugin Name: Include
 * Plugin URI: http://flynndev.us/project/include
 * Description: Include a page, post, activity, or other query-object into another.
 * Version: %ver%
 * Author: mflynn, cngann, Clear_Code, bmcswee, flynndev
 * Author URI: http://clearcode.info
 * License: GPL2
 */

$wordpress_location = '../framework/dist/load.php';

$phpunit_location = dirname(dirname(__FILE__)).'/framework/dist/load.php';

if(file_exists($wordpress_location)) require_once($wordpress_location);
else require_once($phpunit_location);

PluginFramework\V_1_1\register("Include", __FILE__);

if(PluginFramework\V_1_1\check_version()) {
	require_once( 'wp.trait.php' );
	require_once( 'Instance.class.php' );
	require_once( 'plugin.class.php' );

	$GLOBALS['Include'] = new IncludePlugin\Plugin( "include", '%ver%', __FILE__ );
}