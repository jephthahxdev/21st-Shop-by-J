<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Durotan
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

\Durotan\Markup::instance()->open( 'comments_content', [
	'attr'    => [
		'id'    => 'comments',
		'class' => 'comments-area',
	],
	'actions' => true,
] );

	if ( have_comments() ) :
		do_action( 'durotan_comments_content' );

	endif;

\Durotan\Markup::instance()->close( 'comments_content' );
