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


	function fail(){
		$this->failed = true;
		return false;
	}

	abstract protected function load($q);

	function __construct($q, $a,  &$plugin) {
		$this->plugin =& $plugin;
		$this->wp_globs();
		unset($a['id'], $a['slug']);
		$this->attributes = $a;
		$this->load($q);
	}
}

class Single   extends Instance {
	function load($q) {
		$this->id = $this->find_id($q);
		if(!$this->id) return false;
		if(!$this->plugin->activate($this->id)) return false;

		$this->slug      = $this->find_slug($this->id);
		$this->post_type = $this->find_post_type($this->id);

		$this->recursion = $this->attributes['recursion'];

		$this->load_wp_query([$this->post_type == 'page' ? 'page_id' : 'p' => $this->id]);

		$this->content   = apply_filters('the_content', strtolower($this->recursion) == "strict" ? preg_replace( "/\[include[^\]]*\]/im", "", get_the_content() ) : get_the_content());

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
		$this->plugin->deactivate($this->id);
		$this->unload_wp_query();
	}

}

class Multiple extends Instance {

	public $children = [];

	protected function addChild($id){
		$this->children[] = new Single($id, $this->attributes, $this->plugin);
	}

	function load($q) {
		$this->id = $this->find_id($q) ?:  get_the_id();
		if(!$this->id) return false;
		if(!$this->plugin->activate($this->id)) return false;

		$this->slug      = $this->find_slug($this->id);
		$this->post_type = $this->find_post_type($this->id);

		$children = get_children( [ 'post_parent' => $this->id, 'post_type'   => $this->post_type, 'numberposts' => -1, 'post_status' => 'publish' ] );
		foreach( (array) $children as $page_child_id => $page_child ) $this->addChild($page_child_id);
		$this->plugin->deactivate($this->id);
	}

}