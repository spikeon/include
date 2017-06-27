<?php
namespace IncludePlugin;
trait WP {
	protected $wpdb;
	protected $wp_query;
	protected $post;
	protected $query_stash;


	function fail($msg = false){
		$this->failed = true;
		if(isset($this->plugin)) $this->plugin->debug( $msg ?: $this);
		else $this->debug($msg ?: $this);
		return false;
	}

	protected function wp_globs() {
		$this->wpdb = &$GLOBALS['wpdb'];
		$this->wp_query = &$GLOBALS['wp_query'];
		$this->post = &$GLOBALS['post'];
	}

	protected function find_post_type($id){
		return $this->wpdb->get_var("SELECT post_type FROM {$this->wpdb->posts} WHERE ID = '{$id}'") ?: false;
	}

	protected function check_id($id){
		return $this->wpdb->get_var("SELECT count(*) FROM {$this->wpdb->posts} WHERE ID = '{$id}'") ? true : false;
	}

	protected function find_id ($subject) {
		if(!$subject) return $this->fail("No ID Given");
		if(is_numeric($subject)) {
			if(!$this->check_id($subject))return $this->fail("Page doesn't exist");
			return $subject * 1;
		}
		else {
			return $this->wpdb->get_var("SELECT ID FROM {$this->wpdb->posts} WHERE post_name = '{$subject}'") ?: false;
		}
	}

	protected function find_slug($id) {
		return $this->wpdb->get_var("SELECT post_name FROM {$this->wpdb->posts} WHERE ID = '{$id}'")?: false;
	}

	protected function load_wp_query($q) {
		$this->query_stash = clone $GLOBALS['wpdb'];
		query_posts([$this->id_col => $this->id]);
		the_post();
		$this->wp_globs();
	}

	protected function unload_wp_query() {
		$GLOBALS['wpdb'] = clone $this->query_stash;
		setup_postdata($GLOBALS['post']);
		$this->wp_globs();
	}

}