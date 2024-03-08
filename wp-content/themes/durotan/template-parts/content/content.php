<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Durotan
 */

\Durotan\Markup::instance()->open( 'post_loop_content',[
	'tag' => 'article',
	'attr' => [
		'id'    => 'post-' . get_the_ID(),

	],
	'actions' => 'after',
]);

\Durotan\Markup::instance()->close( 'post_loop_content' );
