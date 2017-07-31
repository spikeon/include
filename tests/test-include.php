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

	public function log($msg) {
		$this->assertTrue(false, $msg);
	}

	function test_all_on(){

		$v = $GLOBALS['Include']->render('include', [
			'failed'    => false,
			'id'        => 1,
			'slug'      => 'test',
			'post_type' => 'page',
			'content'   => 'test1',
			'hr'        => true,
			'title'     => [
				'content'   => 'Regular',
				'show'      => true,
				'element'   => 'ttl',
				'class'     => 'test_title_class'
			],
			'wrap'      => [
				'show'      => true,
				'element'   => 'wrap',
				'class'     => 'test_wrap_class'
			],
		]);

		$this->assertContains( 'Regular',           $v, "Title doesn't work" );
		$this->assertContains( 'ttl',               $v, "Title Container Element Incorrect" );
		$this->assertContains( 'test_title_class',  $v, "Title Class doesn't work" );
		$this->assertContains( 'hr',                $v, "Horizontal Row doesn't work" );
		$this->assertContains( 'wrap',              $v, "Wrap doesn't work" );
		$this->assertContains( 'test_wrap_class',   $v, "Wrap Class doesn't work" );
		$this->assertContains( 'test1',             $v, "Content doesn't work" );

	}

	function test_all_off(){

		$v = $GLOBALS['Include']->render('include', [
			'failed'    => false,
			'id'        => 1,
			'slug'      => 'test',
			'post_type' => 'page',
			'content'   => 'test1',
			'hr'        => false,
			'title'     => [
				'content'   => 'Regular',
				'show'      => false,
				'element'   => 'ttl',
				'class'     => 'test_title_class'
			],
			'wrap'      => [
				'show'      => false,
				'element'   => 'wrap',
				'class'     => 'test_wrap_class'
			],
		]);

		$this->assertNotContains( 'Regular',           $v, "Title doesn't work" );
		$this->assertNotContains( 'ttl',               $v, "Title Container Element Incorrect" );
		$this->assertNotContains( 'test_title_class',  $v, "Title Class doesn't work" );
		$this->assertNotContains( 'hr',                $v, "Horizontal Row doesn't work" );
		$this->assertNotContains( 'wrap',              $v, "Wrap doesn't work" );
		$this->assertNotContains( 'test_wrap_class',   $v, "Wrap Class doesn't work" );
		$this->assertContains( 'test1',             $v, "Content doesn't work" );

	}


}
