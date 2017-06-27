<?php
/**
 * Plugin Name:   Basic Plugin
 * Plugin URI:    http://flynndev.us
 * Description:   Basic plugin test of the WordPress Plugin Framework by FlynnDev
 * Version:       1.0.12
 * Author:        FlynnDev
 * Author URI:    http://flynndev.us
 */

include('../load.php');

/**
 * Class BasicPlugin
 *
 * This plugin does the following:
 * Enqueues a style
 * Registers an admin page with a working form
 * Creates a shortcode
 *
 */

class BasicPlugin extends PluginFramework\Core {
	function __construct() {
		$this->setHomePage("Manage Options");
		$this->addStyle('script', 'basic.css');
		$this->start('basic', '1.0.0', __FILE__);
	}

	public function shortcode_basic($atts, $content){

		$data = $this->pull('options', "This is the default data");

		$view = [
			'prefix' => $this->getPrefix(),
			'basic' => $data['message']
		];
		return $this->view->render('shortcode', $view);
	}

	public function page_manage_options(){
		$this->can();

		$form = $this->pre('options');

		if(!empty($_POST[$form])) $this->push('options', $_POST[$form]);

		$data = $this->pull('options', [ 'message' => 'This is the default data'] );

		$view = [
			'form_namespace' => $form,
			'message' => $data['message'],
		];

		echo $this->render('options', $view);
	}

}