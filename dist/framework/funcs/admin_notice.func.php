<?php
namespace PluginFramework\V_1_1;
if(! function_exists(__NAMESPACE__ . "\admin_notice")) {

	function admin_notice() {
		$class   = 'notice notice-error';
		$message = __( 'The plugins ' . implode(', ', array_keys($GLOBALS['plugin_framework_v_1_1_plugins'])) . ' require PHP ', 'plugin-core' );

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}

}
