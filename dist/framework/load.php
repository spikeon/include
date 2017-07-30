<?php
/**
 * Package:  WordPress Plugin Framework
 * Version:  1.1.114
 * Date:     29-07-2017
 * Copyright 2017 Mike Flynn - mflynn@flynndev.us
 */ 
 


namespace PluginFramework\V_1_1;

// Attempt to tell server to allow url fopen
ini_set("allow_url_fopen", 1);

require_once("funcs/check_version.func.php");
require_once("funcs/register.func.php");

if(! function_exists(__NAMESPACE__ . "\admin_notice")) {

	function admin_notice() {
		$class   = 'notice notice-error';
		$message = __( 'The plugins ' . implode(', ', $GLOBALS['plugin_framework_plugins']) . ' require PHP 5.4.0', 'plugin-core' );

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}

}

if( check_version() == false ) {
	add_action( 'admin_notices', __NAMESPACE__ . '\admin_notice' );
}
else {
	if ( ! class_exists( __NAMESPACE__ . "\Core" ) ) {
		require_once( 'mustache.php' );

		$folder = dirname( __FILE__ ) . '/' . 'traits';
		foreach ( scandir( $folder ) as $filename ) {
			$path = $folder . '/' . $filename;
			if ( is_file( $path ) ) {
				require_once( $path );
			}
		}

		require_once( 'core.class.php' );
	}
}