Include - 4.0.12
===

The Include plugin for WordPress allows you to include the content of one page, post, or other such WordPress object into any other with a shortcode.


**Shortcode: `[include]`**

*Parameters*

* id: the page/post/etc id to use
* slug: the page slug to use to find the ID
* title: the type of element to wrap the title with.  If set to "" no title will be displayed.
 * Default: h2
* title_class: a class to assign to the title
* wrap: the type of element to wrap the entire include area with.  If set to "" no wrap will be applied.
 * Default: div
* wrap_class: a class you want assigned to the wrap.
 * Default: 'included'
* hr: Display a hr before the include.  Set to "" to not display the hr.
* recursion:
 * Options:
  * strict: only show first page, do not run `[include]` if it's included
  * weak: only filter out shortcodes with the same id as the current shortcode to prevent infinite loops
 * Default: weak

*Depreciated Parameters*

These parameters are depreciated, but still supported (for now).

* title_wrapper_elem: old name for title
* title_wrapper_class: old name for title_class

*Example*

`[include id="XXX" title="true" title_elem="h2" title_class="include-title" hr="" recursion="weak" wrap="article" wrap_class="news"]`
`[include slug="hello-world"]`

A shortcode that includes other posts / pages with no nesting of the shortcode, to allow for multiple pages to call each other so that they display their chunks in different orders.

**Shortcode: `[include_children]`**

*Parameters*

* id: the page/post/etc id to use
* slug: the page slug to use to find the ID
* title: the type of element to wrap the title with.  If set to "" no title will be displayed.
 * Default: h2
* title_class: a class to assign to the title
* wrap: the type of element to wrap the entire include area with.  If set to "" no wrap will be applied.
 * Default: div
* wrap_class: a class you want assigned to the wrap.
 * Default: 'included'
* hr: Display a hr before the include.  Set to "" to not display the hr.
* recursion:
 * Options:
  * strict: only show first page, do not run `[include]` if it's included
  * weak: only filter out shortcodes with the same id as the current shortcode to prevent infinite loops
 * Default: weak

*Depreciated Parameters*

These parameters are depreciated, but still supported (for now).

* title_wrapper_elem: old name for title
* title_wrapper_class: old name for title_class

Same as Include, except that if no ID is given, it includes all child pages of the current page, in order.
If an ID is given it includes the child pages of that page, in order.