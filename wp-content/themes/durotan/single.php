<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Durotan
 */

get_header();

	\Durotan\Markup::instance()->open( 'single_post_content', [
		'attr' => [
			'id'    => 'primary',
			'class' => 'content-area',
		],
		'actions' => false,
	] );

		\Durotan\Markup::instance()->open( 'posts_content',[
			'tag'     => 'main',
			'attr' => [
				'id' => 'main',
				'class' => 'site-main',
			],
			'actions' => true,
		]);

			while (have_posts()) :

				the_post();
				get_template_part( 'template-parts/post/content', 'single' );

			endwhile;

		\Durotan\Markup::instance()->close( 'posts_content' );

	\Durotan\Markup::instance()->close( 'single_post_content' );

get_sidebar();
get_footer();