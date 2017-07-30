<?php
/**
 * Class IncludeTest
 *
 * @package Include
 */

/**
 * Include test case.
 */
class IncludeTest extends WP_UnitTestCase {

	public $attributes = [
		'title' 		=> 'ttl',
		'title_class' 	=> 'test_title_class',
		'recursion' 	=> 'weak',
		'hr' 			=> true,
		'wrap' 			=> 'wrapper',
		'wrap_class' 	=> 'test_wrap_class'
	];


	function test_pages() {
		$regular = wp_insert_post([ 'post_title' => 'Regular', 'post_content' => 'test1']);
		$shortcode = wp_insert_post([ 'post_title' => 'Shortcode', 'post_content' => "[include id={$regular}]"]);
		$recursive = wp_insert_post([ 'post_title' => 'Recursive', 'post_content' => '']);
		wp_insert_post(['post_content' => "[include id={$recursive}]", "ID" => $recursive]);

		$pages = [
			'regular'   => $regular,
			'shortcode' => $shortcode,
			'recursive' => $recursive
		];

		$this->assertTrue(is_int($pages['regular']), "Inserted Post 1 failed");
		$this->assertTrue(is_int($pages['shortcode']), "Inserted Post 2 failed");
		$this->assertTrue(is_int($pages['recursive']), "Inserted Post 3 failed");

		return $pages;
	}

	/**
	 * @depends test_pages
	 */
	function test_single_instance($pages){

		$i = new IncludePlugin\Single( $pages['regular'], $this->attributes, $GLOBALS['Include'] );
		$v = $i->view();

		$this->assertContains( 'Regular',           $v, "Title doesn't work" );
		$this->assertContains( 'ttl',               $v, "Title Container Element Incorrect" );
		$this->assertContains( 'test_title_class',  $v, "Title Class doesn't work" );
		$this->assertContains( 'hr',                $v, "Horizontal Row doesn't work" );
		$this->assertContains( 'wrap',              $v, "Wrap doesn't work" );
		$this->assertContains( 'test_wrap_class',   $v, "Wrap Class doesn't work" );
		$this->assertContains( 'test1',             $v, "Content doesn't work" );

		return $pages;
	}

	/**
	 * @depends test_single_instance
	 */
	function test_shortcode($pages) {
		$i = new IncludePlugin\Single( $pages['shortcode'], $this->attributes, $GLOBALS['Include'] );
		$v = $i->view();

		$this->assertContains( 'Regular',           $v, "Title doesn't work" );
		$this->assertContains( 'ttl',               $v, "Title Container Element Incorrect" );
		$this->assertContains( 'test_title_class',  $v, "Title Class doesn't work" );
		$this->assertContains( 'hr',                $v, "Horizontal Row doesn't work" );
		$this->assertContains( 'wrap',              $v, "Wrap doesn't work" );
		$this->assertContains( 'test_wrap_class',   $v, "Wrap Class doesn't work" );
		$this->assertContains( 'test1',             $v, "Content doesn't work" );

		return $pages;
	}
}
