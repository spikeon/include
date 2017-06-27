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

	trait Helpers {
		/**
		 * Sterilize String
		 *
		 * Converts string to lower case
		 * Converts all spaces to underscores
		 *
		 * @param string $str
		 *
		 * @return string Sterilized String
		 */
		public function sterilize($str) {
			return strtolower( str_replace(' ', '_', $str ) );
		}

		/**
		 * Concatenate
		 *
		 * Sterilizes all Strings
		 * Glues strings together with _
		 *
		 * @param string|array ...$param Parameter
		 *
		 * @return string
		 */
		public function concat($param){
			$args   = func_get_args();
			$pieces = [];

			foreach($args as $arg) if(!empty($arg)) $pieces[] = $this->sterilize($arg);

			return implode("_", $pieces);
		}

		/**
		 * Generate Prefixed String
		 *
		 * Prefixes with plugin prefix
		 * Sterilizes all Strings
		 * Glues strings together with _
		 *
		 * @param string ...$param Parameters
		 * @return string
		 */
		public function pre($param) {
			$args   = func_get_args();
			$pieces = [$this->prefix];

			foreach($args as $arg) if(!empty($arg)) $pieces[] = $this->sterilize($arg);

			return  implode('_', $pieces);
		}

		/**
		 * Convert pre to title
		 *
		 * Converts a string that has been passed through pre back to a page title
		 *
		 * @param string $s
		 * @return string
		 */
		public function pre_to_title($s){
			$s = preg_replace( '^[^_]+_', '', $s ); // Remove First Chunk
			$s = str_replace("_", " ", $s);
			$s = ucwords($s);
			return $s;
		}

	}