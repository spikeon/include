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
	function id_exists($id){ global $wpdb; return $wpdb->get_var("SELECT count(*) FROM {$wpdb->posts} WHERE ID = '{$id}'") ? true : false; }
	/**
	 * Get Plugin Options
	 * @author Mike Flynn
	 * @since 2.0
	 * @return array
	 */
	function include_get_options(){ global $include_atts; return get_option('include_atts', $include_atts); }
	
	/**
	 * Set Plugin Options
	 * @author Mike Flynn
	 * @since 2.0
	 * @return array
	 */
	function include_set_options($arr){ global $include_atts; $atts = array_merge($include_atts, $arr); return update_option('include_atts', $atts ); }