<?php
namespace IncludePlugin;
class Plugin extends \PluginFramework\V_1_1\Core {
	use WP;

	/**
	 * Current Included Posts
	 *
	 * @author Mike Flynn
	 * @since 4.0.0
	 * @var array An array of posts currently included to prevent infinite loops
	 */
	protected $running = [];

	/**
	 * Is Include On?
	 *
	 * @since 4.0.0
	 * @author Mike Flynn
	 * @var boolean Is include active
	 */
	protected $active = false;

	/**
	 * First Include
	 *
	 * @since 4.0.0
	 * @author Mike Flynn
	 * @var int post_id of outermost shortcode
	 */
	protected $first = false;


	function activate($id) {
		if(!isset($this->running[$id])) $this->running[$id] = false;
		if( $this->running[$id] === true) return false;
		$this->running[$id] = true;
		return true;
	}

	function deactivate($id) {
		$this->running[$id] = false;
	}

	/**
	 * Include Shortcode
	 *
	 * Creates and returns the "include" shortcode
	 *
	 * @author Mike Flynn
	 * @since 1.0
	 * @param $a The current shortcode attributes
	 * @param $content Content inside of shortcode
	 * @return string The shortcode content
	 */
	function shortcode_include ($a, $content){

		$id = empty($a['id']) ? "" : $a['id'];
		$slug = empty($a['slug']) ? "" : $a['slug'];

		$a = $this->atts('include', $a);

		$i = new Single($id ?: $slug ?: $content, $a , $this);

		$this->debug($i, false);

		return $this->render('include', $i->view() );

	}

	/**
	 * Default Include Shortcode Attributes
	 *
	 * @since 4.0.0
	 * @author Brendan McSweeney, Mike Flynn
	 * @var array An array containing the tips for the options panel
	 */
	public $shortcode_attributes_include = [
		'id' 			=> false,									// (required) The Page/Post Id to Include.  Default: none. Not required if slug is set.
		'slug' 			=> false,									// (optional) The Page/Post Slug to Include. Not recomended as slugs can change.
		'title' 		=> 'h2',									// (optional) Title Wrapper Element. Default: h2.
		'title_class' 	=> '',										// (optional) Class of Title Wrapper Element. Default: none.
		'recursion' 	=> 'weak',									// (optional) Recursion Setting.  Options: strong or weak. Default: weak
		'hr' 			=> '',    									// (optional) Display a hr element before the include.  Set to "" to not show the hr.
		'wrap' 			=> 'div',                                   // (optional) element to wrap the entire include in.
		'wrap_class' 	=> 'included'    							// (optional) class assigned to the wrap. Default: included.
	];


	/**
	 * Include Children Shortcode
	 *
	 * Creates and returns the "include_children" shortcode
	 *
	 * @since 2.0b
	 * @author Brendan McSweeney
	 */
	function shortcode_include_children ($a, $content){

		$id = empty($a['id']) ? "" : $a['id'];
		$slug = empty($a['slug']) ? "" : $a['slug'];

		$a = $this->atts('include_children', $a);

		$i = new Multiple($id ?: $slug ?: $content, $a , $this);

		return $this->render('multiple', $i->view());
	}

	/**
	 * Default Include Children Shortcode Attributes
	 *
	 * @since 4.0.0
	 * @author Brendan McSweeney, Mike Flynn
	 * @var array An array containing the tips for the options panel
	 */
	public $shortcode_attributes_include_children = [
		'id' 			=> false,									// (required) The Page/Post Id to Include.  Default: none. Not required if slug is set.
		'slug' 			=> false,									// (optional) The Page/Post Slug to Include. Not recomended as slugs can change.
		'title' 		=> 'h2',									// (optional) Title Wrapper Element. Default: h2.
		'title_class' 	=> '',										// (optional) Class of Title Wrapper Element. Default: none.
		'recursion' 	=> 'weak',									// (optional) Recursion Setting.  Options: strong or weak. Default: weak
		'hr' 			=> '',    									// (optional) Display a hr element before the include.  Set to "" to not show the hr.
		'wrap' 			=> 'div',                                   // (optional) element to wrap the entire include in.
		'wrap_class' 	=> 'included'    							// (optional) class assigned to the wrap. Default: included.
	];


	function page_include() {
		$this->can();
		$form_name = $this->pre('locations');
		$updated = false;

		if(!empty($_POST[$form_name])){
			$this->setAttributes('include', $_POST[$form_name]);
			$this->setAttributes('include_children', $_POST[$form_name]);
			$updated = true;
		}

		$this->loadAttributes('include');

		$attributes = $this->getAttributes('include');

		$tips = [
			'title'			=> 'The type of element to wrap the title with.',
			'title_class' 	=> 'A class to assign to the title wrap.',
			'recursion' 	=> 'Strict will not run the shortcode on included child pages.',
			'hr' 			=> 'insert a horizontal rule before included content.',
			'wrap'			=> 'Element to wrap included content with.',
			'wrap_class'	=> 'A class to assign to the wrap.'
		];

		$view = [
			'form_name' => $form_name,
			'updated' => $updated,
			'attributes' => [],
		];

		foreach($tips as $key => $tip) {
			$attribute = isset($attributes[$key]) ? $attributes[$key] : "";
			$view['attributes'][] = [
				'name'          => $key,
				'title'         => ucwords(strtolower(str_replace('_', ' ', $key))),
				'is_select'     => $key == 'recursion' ,
				'is_checkbox'   => $key == 'hr',
				'checked'       => $key == 'hr' && $attribute,
				'is_text'       => $key != 'recursion' && $key != 'hr',
				'options'       => $key == 'recursion' ? [ [ "option" => "strict", "name" => "Strict", "selected" => $attribute == 'strict' ], [ "option" => "weak", "name" => "Weak", "selected" => $attribute == 'weak' ] ] : [],
				'value'         => $attribute,
				'tip'           => $tip
			];
		}

		echo $this->render('admin_panel', $view);

	}

	/**
	 * Include Plugin constructor.
	 *
	 * @global $wpdb The wordpress database object
	 * @global $post The current post object
	 * @global $wp_query The wp_query object
	 * @param $name
	 * @param $ver
	 * @param $file
	 */
	function __construct($name, $ver, $file) {
		$this->setShortcodePrefix("");
		$this->setHomePage('include');
		$this->init($name, $ver, $file);
	}
}