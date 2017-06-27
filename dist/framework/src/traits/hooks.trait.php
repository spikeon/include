<?php
	namespace PluginFramework;
	trait Hooks {
		/**
		 * Hooks
		 * Checks all available hooks and ties related methods to them
		 */
		private function init_hooks(){
			$hook_methods = preg_grep('/^(.+_)?hook_/', get_class_methods($this));

			foreach($hook_methods as $method) add_action(preg_replace('/^(.+_)?hook_/','', $method), [&$this, $method]);
		}

	}