<?php
/**
 * Package:  WordPress Plugin Framework
 * Version:  1.1.58
 * Date:     27-06-2017
 * Copyright 2017 Mike Flynn - mflynn@flynndev.us
 */ 
 


namespace PluginFramework\V_1_1;

abstract class Core{

	use PluginInfo, Helpers, Hooks, Errors, View, Data, Shortcode, Security, Resources, Pages;

	function init($name, $ver, $file) {
		$this->setPrefix($name);
		$this->setShortcodePrefix($this->getPrefix());
		$this->setVersion($ver);
		$this->setRoot($file);

		$this->setMenuTitle($name);

		$this->init_view();
		$this->init_hooks();
		$this->init_shortcodes();
	}

}