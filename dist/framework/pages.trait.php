<?php 
/**
 * Package:  WordPress Plugin Framework
 * Version:  1.1.56
 * Date:     27-06-2017
 * Copyright 2017 Mike Flynn - mflynn@flynndev.us
 */ 
 ?>
<?php 
/**
 * Package:  WordPress Plugin Framework
 * Version:  1.1.55
 * Date:     27-06-2017
 * Copyright 2017 Mike Flynn - mflynn@flynndev.us
 */ 
 ?>
<?php 
/**
 * Package:  WordPress Plugin Framework
 * Version:  1.1.54
 * Date:     27-06-2017
 * Copyright 2017 Mike Flynn - mflynn@flynndev.us
 */ 
 ?>
<?php 
/**
 * Package:  WordPress Plugin Framework
 * Version:  1.1.53
 * Date:     27-06-2017
 * Copyright 2017 Mike Flynn - mflynn@flynndev.us
 */ 
 ?>
<?php 
/**
 * Package:  WordPress Plugin Framework
 * Version:  1.1.52
 * Date:     27-06-2017
 * Copyright 2017 Mike Flynn - mflynn@flynndev.us
 */ 
 ?>
<?php
	namespace PluginFramework\V_1_1;
	trait Pages {
		private $home_page      = 'index';
		private $menu_title     = 'Plugin Framework';
		/**
		 * Set Home Page
		 *
		 * Which page is home.  Enter as title (ex: Manage Plugin)
		 *
		 * There must me a matching method (ex: page_manage_plugin)
		 *
		 * @param $page
		 */
		public function setHomePage($page){
			$this->home_page = $page;
		}


		/**
		 * Set Menu Title
		 * @param $title
		 */
		public function setMenuTitle($title){
			$this->menu_title = $title;
		}

		private function all_pages(){
			$shortcode_methods = preg_grep('/^page_/', get_class_methods($this));

			$menu_init = false;

			if(method_exists($this, $this->concat('page', $this->home_page))){
				add_menu_page($this->home_page, $this->menu_title, $this->security_level, $this->pre($this->menu_title, $this->home_page), [&$this, $this->concat('page', $this->home_page)]);
				$menu_init = true;
			}

			// Method method
			foreach($shortcode_methods as $method) {

				$title = $this->pre_to_title($method);

				if($this->concat('page', $this->home_page) == $method) continue;

				if(!$menu_init) add_submenu_page( $this->pre($this->menu_title, $this->home_page), $title, $title, $this->security_level,  $this->pre($this->menu_title, $this->home_page), [&$this, $method ] );
				else {
					add_menu_page($title, $this->menu_title, $this->security_level, $this->pre($this->menu_title, $this->home_page), [&$this, $method]);
					$menu_init = true;
				}
			}
		}


		public function pages_hook_admin_menu () {
			$this->all_pages();
		}


	}