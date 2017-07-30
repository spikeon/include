<?php
/**
 * Package:  WordPress Plugin Framework
 * Version:  1.1.110
 * Date:     29-07-2017
 * Copyright 2017 Mike Flynn - mflynn@flynndev.us
 */ 
 


// Attempt to tell server to allow url fopen
ini_set("allow_url_fopen", 1);

if(!function_exists('plugin_framework_check_version')) {
	/**
	 * Check PHP deps
	 *
	 * @return bool
	 */
	function plugin_framework_check_version() {
		return version_compare( PHP_VERSION, '5.4.0', '<' );
	}
}

if(! function_exists("plugin_core_error_admin_notice")) {

	function plugin_core_error_admin_notice() {
		$class   = 'notice notice-error';
		$message = __( 'The plugin ', 'plugin-core' );

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}

}

if( plugin_framework_check_version() ) {
	add_action( 'admin_notices', 'plugin_core_error_admin_notice' );
}
else {
	if ( ! class_exists( "PluginFramework\V_1_1\Core" ) ) {
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