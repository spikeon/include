<?php

	namespace PluginFramework\V_1_1;
	trait Errors {

		private $errors = [
			'forbidden' => 'You do not have sufficient permissions to access this page.'
		];

		/**
		 * Add Error
		 *
		 * @param string $name Name to access saved error
		 * @param string $msg Message to display on error
		 */
		public function addError($name, $msg){
			$this->errors[$name] = $msg;
		}

		/**
		 * Throw Error
		 *
		 * @param string $name Name of saved error
		 * @param string $msg Additional message to give context
		 */
		public function error($name, $msg = ""){
			wp_die( __( $this->errors[$name] . "<br>" . $msg ) );
		}

		public function debug($dump){
			wp_die("<pre>" . var_export($dump, true) . "</pre>");
		}

	}