<?php
namespace IncludePlugin;
trait WP {
	public $query_stash;

	public function find_post_type($id){
		global $wpdb;
		return $wpdb->get_var("SELECT post_type FROM {$wpdb->posts} WHERE ID = '{$id}'") ?: false;
	}

	public function check_id($id){
		global $wpdb;
		return $wpdb->get_var("SELECT count(*) FROM {$wpdb->posts} WHERE ID = '{$id}'") ? true : false;
	}

	public function find_id ($subject) {
		if(!$subject) return false;
		if(is_numeric($subject)) {
			if(!$this->check_id($subject)) return false;
			return $subject * 1;
		}
		else {
			global $wpdb;
			return $wpdb->get_var("SELECT ID FROM {$wpdb->posts} WHERE post_name = '{$subject}'") ?: false;
		}
	}

	public function find_slug($id) {
		global $wpdb;
		return $wpdb->get_var("SELECT post_name FROM {$wpdb->posts} WHERE ID = '{$id}'")?: false;
	}

	public function load_wp_query($q) {
		global $wp_query;
		$this->query_stash = $wp_query;
		$query = \WP_Query($q);
		$query->the_post();
	}

	public function unload_wp_query() {
		wp_reset_postdata();
	}

}