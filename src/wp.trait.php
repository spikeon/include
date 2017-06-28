<?php
namespace IncludePlugin;
trait WP {
	public $query_stash;

	public function &wpdb($set = false){
		global $wpdb;
		if($set) $wpdb = $set;
		return $wpdb;
	}

	public function &wp_query($set = false){
		global $wp_query;
		if($set) $wp_query = $set;
		return $wp_query;
	}

	public function &post($set = false){
		global $post;
		if($set) $post = $set;
		return $post;
	}

	public function find_post_type($id){
		return $this->wpdb()->get_var("SELECT post_type FROM {$this->wpdb()->posts} WHERE ID = '{$id}'") ?: false;
	}

	public function check_id($id){
		return $this->wpdb()->get_var("SELECT count(*) FROM {$this->wpdb()->posts} WHERE ID = '{$id}'") ? true : false;
	}

	public function find_id ($subject) {
		if(!$subject) return false;
		if(is_numeric($subject)) {
			if(!$this->check_id($subject))return false;
			return $subject * 1;
		}
		else {
			return $this->wpdb()->get_var("SELECT ID FROM {$this->wpdb()->posts} WHERE post_name = '{$subject}'") ?: false;
		}
	}

	public function find_slug($id) {
		return $this->wpdb()->get_var("SELECT post_name FROM {$this->wpdb()->posts} WHERE ID = '{$id}'")?: false;
	}

	public function load_wp_query($q) {
		$this->query_stash = clone $this->wpdb();
		query_posts($q);
		the_post();
	}

	public function unload_wp_query() {
		$this->wpdb(clone $this->query_stash);
		setup_postdata($GLOBALS['post']);
	}

}