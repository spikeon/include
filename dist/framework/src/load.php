<?php

if(!class_exists("PluginFramework\Core")) {
	require_once( './mustache.php' );
	$folder = './traits';
	foreach (scandir($folder) as $filename) {
		$path = $folder . '/' . $filename;
		if (is_file($path)) require_once ($path);
	}
	require_once( './core.class.php' );
}