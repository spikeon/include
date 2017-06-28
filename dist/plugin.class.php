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

	public $option_tips = [
		'title'			=> 'The type of element to wrap the title with.',
		'title_class' 	=> 'A class to assign to the title wrap.',
		'recursion' 	=> 'Strict will not run the shortcode on included child pages.',
		'hr' 			=> 'Set to anything other than blank to insert a horizontal rule before included content.',
		'wrap'			=> 'Element to wrap included content with.',
		'wrap_class'	=> 'A class to assign to the wrap.'
	];

	function activate($id) {
		if(!$id) return false;
		if(!$this->active) {
			$this->active = true;
			$this->first = $id;
		}

		if(empty($this->running[$id])) $this->running[$id] = false;
		if( $this->running[$id] === true) return false;
		$this->running[$id] = true;
		return true;
	}

	function deactivate($id) {
		$this->running[$id] = false;
		if($id == $this->first) {
			$this->active = false;
			$this->first= false;
		}
		return true;
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

		$i = new Single($a['id'] ?: $a['slug'] ?: $content, $this->atts('include', $a), $this);

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

		$i = new Multiple($a['id'] ?: $a['slug'] ?: $content, $this->atts('include_children', $a) , $this);

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

		$this->init($name, $ver, $file);
	}
}