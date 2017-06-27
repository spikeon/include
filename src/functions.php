<?php
	/**
	 * ID Exists
	 *
	 * @author Mike Flynn
	 * @since 1.0
	 * @param integer $id The ID to check for
	 * @global $wpdb The WordPress Database Object
	 * @return boolean
	 */
	function id_exists($id) {
		global $wpdb;
		return ;
	}
	/**
	 * Get Plugin Options
	 * @author Mike Flynn
	 * @since 2.0
	 * @return array
	 */
	function include_get_options(){
		global $include_atts;
		return ;
	}
	
	/**
	 * Set Plugin Options
	 * @author Mike Flynn
	 * @since 2.0
	 * @return array
	 */
	function include_set_options($arr){
		global $include_atts;
		$atts = array_merge($include_atts, $arr);
		return update_option('include_atts', $atts );
	}