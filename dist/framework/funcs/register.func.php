<?php
namespace PluginFramework\V_1_1;

if(! function_exists(__NAMESPACE__ . "\register")) {
	function register($name, $file){
		if(empty($GLOBALS['plugin_framework_plugins'])) $GLOBALS['plugin_framework_plugins'] = [];
		$GLOBALS['plugin_framework_plugins'][$name] = $file;
	}
}
