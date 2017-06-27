<?php 
/**
 * Package:  WordPress Plugin Framework
 * Version:  1.1.56
 * Date:     27-06-2017
 * Copyright 2017 Mike Flynn - mflynn@flynndev.us
 */ 
 ?>
<?php

if(!class_exists("PluginFramework\V_1_1\Core")) {
	require_once( 'dist/mustache.php' );
	$folder = 'dist/traits';
	foreach (scandir($folder) as $filename) {
		$path = $folder . '/' . $filename;
		if (is_file($path)) require_once ($path);
	}
	require_once( 'dist/core.class.php' );
}