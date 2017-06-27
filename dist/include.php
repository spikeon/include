<?php
/**
 * Plugin Name: Include
 * Plugin URI: http://wordpress.org/plugins/include/
 * Description: Include a page, post, activity, or other query-object into another.
 * Version: 3.4.55
 * Author: mflynn, cngann, Clear_Code, bmcswee, flynndev
 * Author URI: http://clearcode.info
 * License: GPL2
 */



require_once ('framework/load.php');
require_once( './wp.trait.php' );

require_once( './Instance.class.php' );
require_once ('plugin.class.php');

$Include = new IncludePlugin\Plugin("include", '3.4.55', __FILE__);