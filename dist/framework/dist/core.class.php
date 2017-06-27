<?php 
/**
 * Package:  WordPress Plugin Framework
 * Version:  1.1.56
 * Date:     27-06-2017
 * Copyright 2017 Mike Flynn - mflynn@flynndev.us
 */ 
 ?>
<?php

namespace PluginFramework\V_1_1;

/**
 * Plugin Framework
 * Author: Mike Flynn <mflynn@flynndev.us>
 * Version: 1.0
 *
 * Does the heavy lifting for plugin creation as far as adding Menu items, scripts, styles, and shortcodes
 */

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