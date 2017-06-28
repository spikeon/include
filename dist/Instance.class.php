<?php
namespace IncludePlugin;
abstract class Instance {

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

	/**
	 * Load Instance
	 *
	 * @since 4.0.0
	 * @param $identifier
	 * @param Plugin $plugin
	 *
	 * @return mixed
	 */
	abstract protected function load($identifier , $plugin);

	/**
	 * Generate View
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */
	abstract public function view();

	/**
	 * Instance constructor
	 *
	 *
	 * @since 4.0.0
	 * @param integer|string $identifier slug or ID
	 * @param $a Attributes
	 * @param Plugin $plugin
	 */
	function __construct($identifier, $a, $plugin) {
		unset($a['id'], $a['slug']);
		$this->attributes = $a;
		$this->load($identifier , $plugin);
	}

	/**
	 * Strip Content
	 *
	 * @since 4.0.0
	 * @param string $content Page content
	 *
	 * @return string Formatted Page Content
	 */
	function strip_nesting($content){
		return preg_replace( "/\[include[^\]]*\]/im", "", $content);
	}
}

class Single extends Instance {

	function load($identifier, $plugin) {
		$this->id = $plugin->find_id($identifier);
		if(!$this->id) return false;
		if(!$plugin->activate($this->id)) return false;

		$this->slug      = $plugin->find_slug($this->id);
		$this->post_type = $plugin->find_post_type($this->id);

		$this->recursion = $this->attributes['recursion'];

		$plugin->load_wp_query([$this->post_type == 'page' ? 'page_id' : 'p' => $this->id]);

		$c               = get_the_content();
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

		$plugin->unload_wp_query();

		$plugin->deactivate($this->id);

		return true;
	}

	function view() {
		return [
			'id'        => $this->id,
			'slug'      => $this->slug,
			'post_type' => $this->post_type,
			'content'   => $this->content,
			'hr'        => $this->hr,
			'title'     => $this->title,
			'wrap'      => $this->wrap,
 		];
	}

}

class Multiple extends Instance {

	public $children = [];

	protected function addChild($id, $plugin){
		$this->children[] = new Single($id, $this->attributes, $plugin);
	}

	function load($identifier, $plugin) {
		$this->id = $plugin->find_id($identifier) ?:  get_the_id();
		if(!$this->id)  return false;
		if(!$plugin->activate($this->id)) return false;

		$this->slug      = $plugin->find_slug($this->id);
		$this->post_type = $plugin->find_post_type($this->id);

		$children = get_children( [ 'post_parent' => $this->id, 'post_type'   => $this->post_type, 'numberposts' => -1, 'post_status' => 'publish' ] );
		foreach( (array) $children as $page_child_id => $page_child ) $this->addChild($page_child_id, $plugin);

		$plugin->deactivate($this->id);

		return true;
	}

	function view() {
		return [
			'id'        => $this->id,
			'slug'      => $this->slug,
			'post_type' => $this->post_type,
			'content'   => $this->content,
			'hr'        => $this->hr,
			'title'     => $this->title,
			'wrap'      => $this->wrap,
			'children'  => $this->children
		];
	}

}