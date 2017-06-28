<?php
namespace IncludePlugin;
abstract class Instance {
	use WP;

	protected $plugin;

	public $id = false;
	public $post_type = "";
	public $slug = false;

	public $wrap = [];
	public $hr = false;
	public $title = [];
	public $content = "";

	public $failed = false;

	protected $recursion;

	protected $attributes = [];

	abstract protected function load($q, $plugin);
	abstract public function view();

	function __construct($q, $a, $plugin) {
		$this->wp_globs();
		unset($a['id'], $a['slug']);
		$this->attributes = $a;
		$this->load($q, $plugin);
	}

	function strip_nesting($content){
		return preg_replace( "/\[include[^\]]*\]/im", "", $content);
	}
}

class Single extends Instance {
	function load($q, $plugin) {
		$this->id = $this->find_id($q);
		if(!$this->id) return false;
		if(!$plugin->activate($this->id)) return false;

		$this->slug      = $this->find_slug($this->id);
		$this->post_type = $this->find_post_type($this->id);

		$this->recursion = $this->attributes['recursion'];

		$this->load_wp_query([$this->post_type == 'page' ? 'page_id' : 'p' => $this->id]);

		$c = get_the_content();
		$this->content   = apply_filters('the_content', strtolower($this->recursion) == "strict" ? $this->strip_nesting($c) : $c);

		$this->hr        = $this->attributes['hr'];

		$this->title = [
			'content'   => get_the_title($this->id),
			'show'      => $this->attributes['title'] ? true : false,
			'element'   => $this->attributes['title'],
			'class'     => $this->attributes['title_class']
		];

		$this->wrap = [
			'show'      => $this->attributes['wrap'] ? true : false,
			'element'   => $this->attributes['wrap'],
			'class'     => $this->attributes['wrap_class']
		];
		$this->unload_wp_query();

		$plugin->deactivate($this->id);
		$this->show_me($this->view());
	}

	function view() {
		return [
			'id' => $this->id,
			'slug' => $this->slug,
			'post_type' => $this->post_type,
			'content' => $this->content,
			'hr' => $this->hr,
			'title' => $this->title,
			'wrap' => $this->wrap,
 		];
	}

}

class Multiple extends Instance {

	public $children = [];

	protected function addChild($id){
		$this->children[] = new Single($id, $this->attributes, $this->plugin);
	}

	function load($q, $plugin) {
		$this->id = $this->find_id($q) ?:  get_the_id();
		if(!$this->id)  return false;
		if(!$plugin->activate($this->id)) return false;

		$this->slug      = $this->find_slug($this->id);
		$this->post_type = $this->find_post_type($this->id);

		$children = get_children( [ 'post_parent' => $this->id, 'post_type'   => $this->post_type, 'numberposts' => -1, 'post_status' => 'publish' ] );
		foreach( (array) $children as $page_child_id => $page_child ) $this->addChild($page_child_id);
		$plugin->deactivate($this->id);
	}

	function view() {
		return [
			'id' => $this->id,
			'slug' => $this->slug,
			'post_type' => $this->post_type,
			'content' => $this->content,
			'hr' => $this->hr,
			'title' => $this->title,
			'wrap' => $this->wrap,
			'children' => $this->children
		];
	}

}