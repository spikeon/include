<?php
namespace IncludePlugin;
trait WP {
	public $wpdb;
	public $wp_query;
	public $post;
	public $query_stash;


	function show_me($msg){
		echo "<pre>".var_export($msg ?: $this, true)."</pre>";
	}

	function fail($msg = false){
		$this->failed = true;

		wp_die("<pre>".var_export($msg ?: $this, true)."</pre>");

		return false;
	}

	public function wp_globs() {
		$this->wpdb = &$GLOBALS['wpdb'];
		$this->wp_query = &$GLOBALS['wp_query'];
		$this->post = &$GLOBALS['post'];
	}

	public function find_post_type($id){
		return $this->wpdb->get_var("SELECT post_type FROM {$this->wpdb->posts} WHERE ID = '{$id}'") ?: false;
	}

	public function check_id($id){
		return $this->wpdb->get_var("SELECT count(*) FROM {$this->wpdb->posts} WHERE ID = '{$id}'") ? true : false;
	}

	public function find_id ($subject) {
		if(!$subject) return $this->fail("No ID Given");
		if(is_numeric($subject)) {
			if(!$this->check_id($subject))return $this->fail("Page doesn't exist");
			return $subject * 1;
		}
		else {
			return $this->wpdb->get_var("SELECT ID FROM {$this->wpdb->posts} WHERE post_name = '{$subject}'") ?: false;
		}
	}

	public function find_slug($id) {
		return $this->wpdb->get_var("SELECT post_name FROM {$this->wpdb->posts} WHERE ID = '{$id}'")?: false;
	}

	public function load_wp_query($q) {
		$this->query_stash = clone $GLOBALS['wpdb'];
		query_posts($q);
		the_post();
		$this->wp_globs();
	}

	public function unload_wp_query() {
		$GLOBALS['wpdb'] = clone $this->query_stash;
		setup_postdata($GLOBALS['post']);
		$this->wp_globs();
	}

}